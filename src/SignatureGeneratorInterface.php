<?php

namespace Shopee;

use Psr\Http\Message\UriInterface;

interface SignatureGeneratorInterface
{
    public function generateSignature(UriInterface $uri, array $params): string;
}
