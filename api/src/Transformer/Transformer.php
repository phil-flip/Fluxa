<?php

namespace App\Transformer;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @template Input
 * @template Output
 */
#[AutoconfigureTag('app.transformer')]
interface Transformer
{
    /**
     * @param Input $data
     * @return Output
     */
    public function transform($data, array $context = []): mixed;

    /**
     * @param Input $data
     * @return bool
     */
    public function supports($data): bool;

    public function setTransformerChain(TransformerChain $transformerChain): void;
}
