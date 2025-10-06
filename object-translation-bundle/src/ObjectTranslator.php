<?php

namespace SymfonyCasts\ObjectTranslationBundle;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use SymfonyCasts\ObjectTranslationBundle\Mapping\Translatable;
use SymfonyCasts\ObjectTranslationBundle\Model\Translation;

final class ObjectTranslator
{
    private \WeakMap $translatedObjects;

    public function __construct(
        private readonly LocaleAwareInterface $localeAware,
        private                               $defaultLocale,
        private readonly string               $translationClass,
        private readonly ManagerRegistry      $doctrine,
    )
    {
        $this->translatedObjects = new \WeakMap();
    }

    /**
     * @template T of object
     *
     * @param T $object
     *
     * @return T
     */
    public function translate(object $object)
    {
        $locale = $this->localeAware ? $this->localeAware->getLocale() : $this->defaultLocale;

        if ($this->defaultLocale === $locale) {
            return $object;
        }

        return $this->translatedObjects[$object] ??= new TranslatedObject($object, $this->translationsFor($object, $locale));
    }

    private function translationsFor(object $object, string $locale): array
    {
        $class = new \ReflectionClass($object);
        $type = $class->getAttributes(Translatable::class)[0]?->newInstance()->name ?? null;

        if (!$type) {
            throw new \LogicException(sprintf('Class "%s" is not translatable.', $object::class));
        }

        $om = $this->doctrine->getManagerForClass($object::class);

        if (!$om) {
            throw new \LogicException(sprintf('No object manager found for class "%s".', $object::class));
        }

        $id = $om->getClassMetadata($object::class)->getIdentifierValues($object);
        if (count($id) > 1) {
            throw new \LogicException(sprintf('Class "%s" must have a single identifier to be translatable', $object::class));
        }

        $id = reset($id);

        /**
         * @var Translation[] $translations
         */
        $translations = $this->doctrine
            ->getRepository($this->translationClass)
            ->findBy([
                'locale' => $locale,
                'objectType' => $type,
                'objectId' => $id,
            ]);

        $translationValues = [];

        foreach ($translations as $translation) {
            $translationValues[$translation->field] = $translation->value;
        }

        return $translationValues;
    }
}
