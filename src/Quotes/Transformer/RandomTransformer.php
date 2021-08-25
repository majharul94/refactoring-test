<?php

declare(strict_types=1);

namespace Refactoring\Quotes\Transformer;

class RandomTransformer
{
    public function transform($objectQuote) {
        return [
            'text' => $objectQuote['value'],
            'ext_id' => $objectQuote['quote_id'] ?? ''
        ];
    }
}