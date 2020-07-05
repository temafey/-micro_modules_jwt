<?php
declare(strict_types=1);

namespace MicroModule\JWT\Signer;

/**
 * Base class for hmac signers
 *
 */
abstract class Hmac implements SignerInterface
{
    /**
     * {@inheritdoc}
     */
    final public function sign(string $payload, Key $key): string
    {
        return \hash_hmac($this->getAlgorithm(), $payload, $key->getContent(), true);
    }

    /**
     * {@inheritdoc}
     */
    final public function verify(string $expected, string $payload, Key $key): bool
    {
        return \hash_equals($expected, $this->sign($payload, $key));
    }

    /**
     * Returns the algorithm name
     *
     * @return string
     */
    abstract public function getAlgorithm(): string;
}
