<?php declare(strict_types = 1);

namespace MicroModule\JWT\Signer\Hmac;

use MicroModule\JWT\Signer\Hmac;

/**
 * Signer for HMAC SHA-512
 */
final class Sha512 extends Hmac
{

    public function getAlgorithmId(): string
    {
        return 'HS512';
    }

    public function getAlgorithm(): string
    {
        return 'sha512';
    }

}
