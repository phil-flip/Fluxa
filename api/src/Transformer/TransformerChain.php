<?php

namespace App\Transformer;

use LogicException;

final readonly class TransformerChain extends AbstractTransformer implements Transformer
{
    /**
     * @param Transformer[] $transformers
     */
    public function __construct(private array $transformers)
    {
    }

    public function transform($data, array $context = []): mixed
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supports($data)) {
                return $transformer->transform($data, $context);
            }
        }

        $type = is_object($data) ? get_class($data) : gettype($data);

        throw new LogicException(sprintf('No transformer found for data type: %s', $type));
    }

    public function supports($data): true
    {
        return true;
    }
}