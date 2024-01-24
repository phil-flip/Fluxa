<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Project as ProjectResource;
use App\Entity\Project;
use App\Entity\Project as ProjectEntity;
use App\Entity\User;
use App\Helper\UserHelper;
use App\Repository\ProjectRepository;
use App\Repository\TeamRepository;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class ProjectProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProjectRepository $projectRepository,
        private TeamRepository $teamRepository,
        private TransformerChain $transformer,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @param ProjectResource $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return mixed
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): mixed {
        /** @var ProjectEntity $project */
        $project = $this->transformer->transform($data);
        $project->code = $this->generateProjectCode($project->name);

        // Remove this when we support custom workspaces
        /** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        $project->workspace = $user->workspaces->first();

        $this->entityManager->persist($project);

        $team = $this->teamRepository->find($data->teamId);
        if (!$team) {
            throw new RuntimeException(sprintf('No team found for team ID "%s"', $data->teamId));
        }
        $team->projects->add($project);
        $project->teams->add($team);

        $this->entityManager->flush();

        return $this->transformer->transform($project);
    }

    private function generateProjectCode(string $projectName): string
    {
        // Remove any non-alphabetic characters and convert to uppercase
        $cleanedName = strtoupper(preg_replace("/[^a-zA-Z\s]/", "", $projectName));

        // Split the name into words
        $words = explode(' ', $cleanedName);
        $code = '';

        // Extract the first letter of each word
        foreach ($words as $word) {
            if (!empty($word)) {
                $code .= $word[0];
            }
        }

        // If the code is less than 3 characters and there's enough length in the first word, extract more characters
        if (strlen($code) < 3 && strlen($words[0]) >= 3) {
            $additionalCharsNeeded = 3 - strlen($code);
            $code .= substr($words[0], 1, $additionalCharsNeeded);
        }

        // Pad the code with random uppercase letters if it's shorter than 3 characters
        while (strlen($code) < 3) {
            $code .= chr(rand(65, 90)); // ASCII values for A-Z
        }

        // Truncate the code to 3 characters if it's longer, or return as is if shorter
        $code = substr($code, 0, 3);

        $originalCode = $code;
        $reservedProjectCodes = $this->getReservedProjectCodes();
        $additionalCharacterCode = 65;
        while (in_array($code, $reservedProjectCodes)) {
            $code = $originalCode . chr($additionalCharacterCode);
            $additionalCharacterCode++;

            // Keep adding characters if needed
            if ($additionalCharacterCode === 90) {
                $originalCode = $code;
                $additionalCharacterCode = 65;
            }
        }

        return $code;
    }

    private function getReservedProjectCodes(): array
    {
        $projects = $this->projectRepository->findForWorkspaces(UserHelper::getWorkspaceIds($this->tokenStorage));

        return array_map(fn(Project $project) => $project->code, $projects);
    }
}
