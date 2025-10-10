<?php

namespace App\Service;

use App\Type\TranslatableEntityType;
use Doctrine\ORM\EntityManagerInterface;
use SymfonyCasts\ObjectTranslationBundle\Mapping\Translatable;
use SymfonyCasts\ObjectTranslationBundle\Mapping\TranslatableProperty;

class TranslationHelper
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function getTranslatableEntity(string $name): TranslatableEntityType
    {
        $entities = $this->getTranslatableEntities();

        if (\array_key_exists($name, $entities)) {
            return $entities[$name];
        }

        throw new \RuntimeException(\sprintf('Translation entity %s not found', $name));
    }

    /**
     * @return TranslatableEntityType[]
     */
    public function getTranslatableEntities(): array
    {
        static $entities = [];

        if ($entities) {
            return $entities;
        }

        $driver = $this->entityManager->getConfiguration()->getMetadataDriverImpl();

        if (!$driver) {
            throw new \RuntimeException('Metadata driver not found');
        }

        $classNames = $driver->getAllClassNames();

        foreach ($classNames as $className) {
            $class = new \ReflectionClass($className);
            $classAttributes = $class->getAttributes(Translatable::class);

            if (!$classAttributes) {
                continue;
            }

            $properties = [];
            foreach ($class->getProperties() as $property) {
                $attributes = $property->getAttributes(TranslatableProperty::class);
                if ($attributes) {
                    $properties[$property->getName()] = $attributes[0]->newInstance()->type;
                }
            }

            $entities[$classAttributes[0]->newInstance()->name] = new TranslatableEntityType($className, $properties);
        }

        return $entities;
    }
}
