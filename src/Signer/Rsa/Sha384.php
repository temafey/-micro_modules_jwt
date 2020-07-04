<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Signer\Rsa;

use MicroModule\JWT\Service\Signer\Rsa;
use const OPENSSL_ALGO_SHA384;

/**
 * Signer for RSA SHA-384
 */
final class Sha384 extends Rsa
{

    public function getAlgorithmId(): string
    {
        return 'RS384';
    }

    public function getAlgorithm(): int
    {
        return \OPENSSL_ALGO_SHA384;
    }

}
