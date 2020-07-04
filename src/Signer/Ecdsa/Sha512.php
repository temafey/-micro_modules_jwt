<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Signer\Ecdsa;

use MicroModule\JWT\Service\Signer\Ecdsa;

/**
 * Signer for ECDSA SHA-512
 */
final class Sha512 extends Ecdsa
{

    public function getAlgorithmId(): string
    {
        return 'ES512';
    }

    public function getAlgorithm(): string
    {
        return 'sha512';
    }

}
