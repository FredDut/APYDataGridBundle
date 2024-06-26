<?php

namespace APY\DataGridBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class TranslationPass.
 *
 * @author Quentin FERRER
 */
class TranslationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition('jms_translation.extractor.file_extractor')) {
            return;
        }

        $extractor = new Definition(\APY\DataGridBundle\Translation\ColumnTitleAnnotationTranslationExtractor::class);
        $extractor
            ->setPublic(false)
            ->addTag('jms_translation.file_visitor');

        $container->setDefinition('grid.translation_extractor', $extractor);
    }
}
