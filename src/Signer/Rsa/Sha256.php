<?php declare(strict_types = 1);

namespace MicroModule\JWT\Signer\Rsa;

use MicroModule\JWT\Signer\Rsa;
use const OPENSSL_ALGO_SHA256;

/**
 * Signer for RSA SHA-256
 */
final class Sha256 extends Rsa
{

    public function getAlgorithmId(): string
    {
        return 'RS256';
    }

    public function getAlgorithm(): int
    {
        return \OPENSSL_ALGO_SHA256;
    }

}
