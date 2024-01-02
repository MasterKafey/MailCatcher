<?php

namespace App\Doctrine\Subscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class FixtureSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {

    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $values = $this->getEntityValues($args);
        $this->logger->info('CREATED', ['class' => get_class($entity), 'values' => $values]);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $values = $this->getEntityValues($args);
        $this->logger->info('UPDATED', ['class' => get_class($entity), 'values' => $values]);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        $values = $this->getEntityValues($args);
        $this->logger->info('REMOVED', ['class' => get_class($entity), 'values' => $values]);
    }

    protected function getEntityValues(LifecycleEventArgs $args): array
    {
        $entity = $args->getObject();
        $manager = $args->getObjectManager();
        $className = $manager->getClassMetadata(get_class($entity))->getName();
        $reflectionClass = new \ReflectionClass($className);

        $values = [];
        do {
            foreach ($reflectionClass->getProperties() as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($entity);
                if (is_object($value) && method_exists($value, 'getId')) {
                    $value = ['id' => $value->getId()];
                }
                $values[$property->getName()] = $value;
            }
        } while ($reflectionClass = $reflectionClass->getParentClass());

        return $values;
    }
}