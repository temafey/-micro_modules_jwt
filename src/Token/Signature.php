<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Token;

/**
 * This class represents a token signature
 *
 */
final class Signature
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $encoded;

    /**
     * @return Signature
     */
    public static function fromEmptyData(): self
    {
        return new self('', '');
    }

    /**
     * Signature constructor.
     *
     * @param string $hash
     * @param string $encoded
     */
    public function __construct(string $hash, string $encoded)
    {
        $this->hash    = $hash;
        $this->encoded = $encoded;
    }

    /**
     * @return string
     */
    public function hash(): string
    {
        return $this->hash;
    }

    /**
     * Returns the encoded version of the signature
     */
    public function __toString(): string
    {
        return $this->encoded;
    }
}
