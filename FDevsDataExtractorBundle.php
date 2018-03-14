<?php

namespace FDevs\Bridge\DataExtractor;

use FDevs\Bridge\DataExtractor\DependencyInjection\Compailer\DataExtensionPass;
use FDevs\Bridge\DataExtractor\DependencyInjection\Compailer\TargetExtensionPass;
use FDevs\Bridge\DataExtractor\DependencyInjection\FDevsDataExtractorExtension;
use FDevs\Container\Compiler\ServiceLocatorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FDevsDataExtractorBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new DataExtensionPass())
            ->addCompilerPass(new TargetExtensionPass())
            ->addCompilerPass(new ServiceLocatorPass('f_devs_data_extractor.container_target_extension', 'f_devs_data_extractor.target_extension'))
            ->addCompilerPass(new ServiceLocatorPass('f_devs_data_extractor.container_data_extension', 'f_devs_data_extractor.data_extension'))
            ->addCompilerPass(new ServiceLocatorPass('f_devs_data_extractor.container_extract_factory', 'f_devs_data_extractor.extracted_factory'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensionClass()
    {
        return FDevsDataExtractorExtension::class;
    }
}
