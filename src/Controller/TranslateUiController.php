<?php

namespace App\Controller;

use App\Service\TranslationHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\LocaleAwareInterface;
use SymfonyCasts\ObjectTranslationBundle\ObjectTranslator;

final class TranslateUiController extends AbstractController
{
    #[Route('/translate-ui', name: 'app_translate_ui')]
    public function index(
        #[Autowire('%kernel.default_locale%')] string $defaultLocale,
        TranslationHelper $translationHelper,
    ): Response {
        return $this->render('translate_ui/index.html.twig', [
            'objectNames' => \array_keys($translationHelper->getTranslatableEntities()),
            'defaultLocale' => $defaultLocale,
        ]);
    }

    #[Route('/translate-ui/list/{objectName}', name: 'app_translate_ui_list')]
    public function list(
        string $objectName,
        #[Autowire('%kernel.default_locale%')] string $defaultLocale,
        ManagerRegistry $doctrine,
        TranslationHelper $translationHelper,
    ): Response {
        try {
            $entity = $translationHelper->getTranslatableEntity($objectName);

            // TODO: pagination
            $items = $doctrine->getRepository($entity->className)->findAll();

            $objectManager = $doctrine->getManagerForClass($entity->className);

            if (!$objectManager) {
                throw new \RuntimeException('Object manager not found');
            }

            $identifiers = $objectManager->getClassMetadata($entity->className)->getIdentifier();

            $htmlContent = $this->renderView('translate_ui/_list.html.twig', [
                'objectName' => $objectName,
                'items' => $items,
                'properties' => $entity->properties,
                'defaultLocale' => $defaultLocale,
                'identifierName' => $identifiers[0],
            ]);
        } catch (\RuntimeException $exception) {
            $htmlContent = $exception->getMessage();
        }

        return new Response($htmlContent);
    }

    #[Route('/translate-ui/{objectName}/{id}/{locale}/{fieldIndex}', name: 'app_translate_ui_item')]
    public function item(
        string $objectName, string $id, string $locale, int $fieldIndex,
        ObjectTranslator $translator,
        LocaleAwareInterface $localeAware,
        TranslationHelper $translationHelper,
        ManagerRegistry $doctrine,
    ): Response {
        $entity = $translationHelper->getTranslatableEntity($objectName);

        $objectManager = $doctrine->getManagerForClass($entity->className);

        if (!$objectManager) {
            throw new \RuntimeException('Object manager not found');
        }

        $identifiers = $objectManager->getClassMetadata($entity->className)->getIdentifier();
        $identifierName = $identifiers[0];

        $object = $doctrine
            ->getRepository($entity->className)
            ->findOneBy([$identifierName => $id]);

        if (!$object) {
            throw new \RuntimeException('Object not found');
        }

        $localeAware->setLocale($locale);

        $object = $translator->translate($object);

        $htmlContent = $this->renderView('translate_ui/_item.html.twig', [
            'objectName' => $objectName,
            $identifierName => $id,
            'locale' => $locale,
            'entity' => $entity,
            'object' => $object,
            'fieldIndex' => $fieldIndex,
        ]);

        return new Response($htmlContent);
    }
}
