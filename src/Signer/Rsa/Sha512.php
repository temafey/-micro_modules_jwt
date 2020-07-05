<?php declare(strict_types = 1);

namespace MicroModule\JWT\Signer\Rsa;

use MicroModule\JWT\Signer\Rsa;
use const OPENSSL_ALGO_SHA512;

/**
 * Signer for RSA SHA-512
 */
final class Sha512 extends Rsa
{

    public function getAlgorithmId(): string
    {
        return 'RS512';
    }

    public function getAlgorithm(): int
    {
        return \OPENSSL_ALGO_SHA512;
    }

}
