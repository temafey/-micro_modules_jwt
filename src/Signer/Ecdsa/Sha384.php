<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Signer\Ecdsa;

use MicroModule\JWT\Service\Signer\Ecdsa;

/**
 * Signer for ECDSA SHA-384
 */
final class Sha384 extends Ecdsa
{

    public function getAlgorithmId(): string
    {
        return 'ES384';
    }

    public function getAlgorithm(): string
    {
        return 'sha384';
    }

}
