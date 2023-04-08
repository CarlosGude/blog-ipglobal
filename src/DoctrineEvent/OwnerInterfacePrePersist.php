<?php

namespace App\DoctrineEvent;

use App\Entity\Interfaces\OwnerInterface;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[AsDoctrineListener('prePersist'/* , 500, 'default' */)]
class OwnerInterfacePrePersist
{
    public function __construct(protected Security $security)
    {
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof OwnerInterface) {
            return;
        }

        if ($entity->getUser()) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new UnauthorizedHttpException('Only authorized users can persist this entity');
        }

        $entity->setUser($user);
    }
}
