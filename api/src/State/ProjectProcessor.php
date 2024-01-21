<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Project as ProjectResource;
use App\Entity\Project as ProjectEntity;
use App\Entity\User;
use App\Repository\TeamRepository;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class ProjectProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
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

        // Truncate the code to 3 characters if it's longer, or return as is if shorter
        return substr($code, 0, 3);
    }
}
