<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Signer\Hmac;

use MicroModule\JWT\Service\Signer\Hmac;

/**
 * Signer for HMAC SHA-256
 */
final class Sha256 extends Hmac
{

    public function getAlgorithmId(): string
    {
        return 'HS256';
    }

    public function getAlgorithm(): string
    {
        return 'sha256';
    }

}
