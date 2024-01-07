<?php

namespace App\DependencyInjection\Compiler;

use App\Transformer\TransformerChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TransformerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(TransformerChain::class)) {
            return;
        }

        $transformerReferences = [];
        $transformerChainReference = new Reference(TransformerChain::class);

        $taggedTransformers = $container->findTaggedServiceIds('app.transformer');
        foreach ($taggedTransformers as $transformerServiceId => $tags) {
            if ($transformerServiceId === TransformerChain::class) {
                continue;
            }

            $transformerDefinition = $container->findDefinition($transformerServiceId);
            $transformerDefinition->addMethodCall('setTransformerChain', [$transformerChainReference]);

            $transformerReferences[] = new Reference($transformerServiceId);
        }

        $transformerChainDefinition = $container->findDefinition(TransformerChain::class);
        $transformerChainDefinition->addArgument($transformerReferences);
    }
}
