<?php

namespace SymfonyCasts\ObjectTranslationBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use SymfonyCasts\ObjectTranslationBundle\Model\Translation;

final class ObjectTranslationBundle extends AbstractBundle
{
    protected string $extensionAlias = 'symfonycasts_object_translation';

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.php');
        $builder->getDefinition('symfonycasts.object_translator')
            ->setArgument(2, $config['translation_class']);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver(
            [__DIR__ . '/../config/doctrine/mapping' => 'SymfonyCasts\ObjectTranslationBundle\Model',],
        ));
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition
            ->rootNode()
            ->children()
            ->stringNode('translation_class')
            ->info('The class name of your Translation entity')
            ->example('App\Entity\Translation')
            ->isRequired()
            ->cannotBeEmpty()
            ->validate()
            ->ifTrue(fn($v) => !is_a($v, Translation::class, true))
            ->thenInvalid('The translation class %s must extend SymfonyCasts\ObjectTranslationBundle\Model\Translation.')
            ->end()
            ->end()
            ->end();
    }
}
