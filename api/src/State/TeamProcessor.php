<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Team as TeamResource;
use App\Entity\Status;
use App\Entity\Team as TeamEntity;
use App\Entity\User;
use App\Entity\Workflow;
use App\Helper\Color;
use App\System\SystemState;
use App\Transformer\TransformerChain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

readonly class TeamProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TransformerChain $transformer,
        private SluggerInterface $slugger,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * @param TeamResource $data
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
        /** @var TeamEntity $team */
        $team = $this->transformer->transform($data);
        $team->slug = $this->slugger->slug($team->name)->lower();
        $team->workflow = $this->createWorkflow();
        $team->properties = [
            'color' => Color::createRandomColor(),
        ];

        // Remove this when we support custom workspaces
        /** @var User $user */
        $user = $this->tokenStorage->getToken()?->getUser();
        $team->workspace = $user->workspaces->first();

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $this->transformer->transform($team);
    }

    private function createWorkflow(): Workflow
    {
        $workflow = new Workflow();

        $defaultStatuses = [
            SystemState::BACKLOG->value => 'Backlog',
            SystemState::TO_DO->value => 'To do',
            SystemState::IN_PROGRESS->value => 'In progress',
            SystemState::COMPLETED->value => 'Done',
            SystemState::CANCELED->value => 'Canceled',
        ];
        foreach (SystemState::cases() as $state) {
            $status = new Status();
            $status->state = $state;
            $status->name = $defaultStatuses[$state->value];
            $status->workflow = $workflow;

            $workflow->statuses->add($status);
        }

        return $workflow;
    }
}
