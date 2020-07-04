<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Signer\Hmac;

use MicroModule\JWT\Service\Signer\Hmac;

/**
 * Signer for HMAC SHA-384
 */
final class Sha384 extends Hmac
{

    public function getAlgorithmId(): string
    {
        return 'HS384';
    }

    public function getAlgorithm(): string
    {
        return 'sha384';
    }

}
