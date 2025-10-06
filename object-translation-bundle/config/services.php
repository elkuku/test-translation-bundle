<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use SymfonyCasts\ObjectTranslationBundle\ObjectTranslator;
use SymfonyCasts\ObjectTranslationBundle\Twig\ObjectTranslatorExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('symfonycasts.object_translator', ObjectTranslator::class)
            ->args([
                service('translation.locale_switcher'),
                param('kernel.default_locale'),
                abstract_arg('Translation class'),
                service('doctrine'),
            ])
            ->tag('twig.runtime')
            ->alias(ObjectTranslator::class, 'symfonycasts.object_translator')
        ->set('.symfonycasts.object_translator.twig_extension', ObjectTranslatorExtension::class)
            ->tag('twig.extension')
    ;
};
