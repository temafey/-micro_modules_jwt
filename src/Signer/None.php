<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Signer;

/**
 */
final class None implements SignerInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAlgorithmId(): string
    {
        return 'none';
    }

    /**
     * {@inheritdoc}
     */
    public function sign(string $payload, Key $key): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function verify(string $expected, string $payload, Key $key): bool
    {
        return $expected === '';
    }
}
