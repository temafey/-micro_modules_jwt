<?php declare(strict_types = 1);

namespace MicroModule\JWT\Signer;

/**
 * Basic interface for token signers
 */
interface SignerInterface
{

    /**
     * Returns the algorithm id
     *
     * @return string
     */
    public function getAlgorithmId(): string;

    /**
     * Creates a hash for the given payload
     *
     * @param string $payload
     * @param \MicroModule\JWT\Signer\Key $key
     * @return string
     * @throws \InvalidArgumentException When given key is invalid
     */
    public function sign(string $payload, Key $key): string;

    /**
     * Returns if the expected hash matches with the data and key
     *
     * @param string $expected
     * @param string $payload
     * @param \MicroModule\JWT\Signer\Key $key
     * @return bool
     * @throws \InvalidArgumentException When given key is invalid
     */
    public function verify(string $expected, string $payload, Key $key): bool;

}
