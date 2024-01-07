<?php

namespace App\Transformer;

readonly abstract class AbstractTransformer implements Transformer
{
    protected TransformerChain $transformerChain;

    public function setTransformerChain(TransformerChain $transformerChain): void
    {
        $this->transformerChain = $transformerChain;
    }
}