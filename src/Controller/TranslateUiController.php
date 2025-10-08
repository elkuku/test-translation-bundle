<?php

namespace App\Controller;

use App\Entity\Translation;
use App\Service\TranslationHelper;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

            // Load translations (TODO: Join?)
            $objectIds = [];
            $repository = $doctrine->getRepository(Translation::class);

            foreach ($items as $item) {
                $objectIds[] = $item->getId();
            }

            $translations = $repository->findByObjectAndObjectIds($objectName, $objectIds);

            $translationIndex = [];
            foreach ($translations as $translation) {
                $fieldName = "{$translation->objectId}-{$translation->field}-{$translation->locale}";
                // TODO: Support for different states (e.g. translated, draft...)
                $translationIndex[$fieldName] = 'translated';
            }

            $htmlContent = $this->renderView('translate_ui/_list.html.twig', [
                'objectName' => $objectName,
                'items' => $items,
                'fields' => $entity->properties,
                'defaultLocale' => $defaultLocale,
                'identifierName' => $identifiers[0],
                'translationIndex' => $translationIndex,
            ]);
        } catch (\RuntimeException $exception) {
            $htmlContent = $exception->getMessage();
        }

        return new Response($htmlContent);
    }

    #[Route('/translate-ui/{objectName}/{id}/{field}/{locale}/{fieldIndex}', name: 'app_translate_ui_item', methods: ['GET'])]
    public function item(
        string $objectName, string $id, string $field, string $locale, int $fieldIndex,
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
            'field' => $field,
            'id' => $id,
            'locale' => $locale,
            'entity' => $entity,
            'object' => $object,
            'fieldIndex' => $fieldIndex,
        ]);

        return new Response($htmlContent);
    }

    #[Route('/translate-ui/save', name: 'app_translate_ui_save', methods: ['POST'])]
    public function save(
        ManagerRegistry $doctrine,
        Request $request,
    ): JsonResponse {
        $statusCode = 200;

        $data = \json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

        /**
         * @var Translation $translation
         */
        $translation = $doctrine
            ->getRepository(Translation::class)
            ->findOneBy([
                'locale' => $data->locale,
                'objectType' => $data->objectName,
                'field' => $data->field,
                'objectId' => $data->id,
            ]);

        if (!$translation) {
            $translation = new Translation();

            $translation->locale = $data->locale;
            $translation->objectType = $data->objectName;
            $translation->field = $data->field;
            $translation->objectId = $data->id;
        }

        $translation->value = $data->value;

        $manager = $doctrine->getManager();
        $manager->persist($translation);
        $manager->flush();

        return $this->json(['OK'], $statusCode);
    }
}
