<?php

namespace FDevs\Bridge\DataExtractor\DependencyInjection\Compailer;

use FDevs\Container\Compiler\ResolveParamTrait;
use FDevs\DataExtractor\Extension\Target\ChainExtension;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TargetExtensionPass implements CompilerPassInterface
{
    use ResolveParamTrait;
    private const TAG_TARGET = 'f_devs_data_extractor.target_extension';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(ChainExtension::class)) {
            return;
        }
        $targets = [];
        foreach ($container->findTaggedServiceIds(self::TAG_TARGET) as $taggedServiceId => $attr) {
            foreach ($attr as $item) {
                if (isset($item['target'])) {
                    $target = $this->resolveParam($container, $item['target']);
                    $priority = isset($item['priority']) ? (int) $item['priority'] : 0;
                    $targets[$target][$priority][] = new Reference($taggedServiceId);
                }
            }
        }
        foreach ($targets as $target => $extensions) {
            krsort($extensions);
            $extensions = call_user_func_array('array_merge', $extensions);

            $def = new ChildDefinition(ChainExtension::class);
            $def
                ->setArgument('$extensions', $extensions)
                ->addTag(self::TAG_TARGET, ['id' => $target])
            ;
            $container->setDefinition(self::TAG_TARGET.$target.ContainerBuilder::hash($def), $def);
        }
    }
}
