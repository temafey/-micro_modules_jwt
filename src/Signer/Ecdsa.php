<?php declare(strict_types = 1);

namespace MicroModule\JWT\Signer;

use Mdanter\Ecc\EccFactory;
use MicroModule\JWT\Signer\Ecdsa\EccAdapter;
use MicroModule\JWT\Signer\Ecdsa\KeyParser;

/**
 * Base class for ECDSA signers
 */
abstract class Ecdsa implements SignerInterface
{

    /** @var \MicroModule\JWT\Signer\Ecdsa\EccAdapter */
    private $adapter;

    /** @var \MicroModule\JWT\Signer\Ecdsa\KeyParser */
    private $keyParser;

    public static function create(): Ecdsa
    {
        $mathInterface = EccFactory::getAdapter();

        return new static(
            EccAdapter::create($mathInterface),
            KeyParser::create($mathInterface)
        );
    }

    public function __construct(EccAdapter $adapter, KeyParser $keyParser)
    {
        $this->adapter   = $adapter;
        $this->keyParser = $keyParser;
    }

    final public function sign(string $payload, Key $key): string
    {
        return $this->adapter->createHash(
            $this->keyParser->getPrivateKey($key),
            $this->adapter->createSigningHash($payload, $this->getAlgorithm()),
            $this->getAlgorithm()
        );
    }

    final public function verify(string $expected, string $payload, Key $key): bool
    {
        return $this->adapter->verifyHash(
            $expected,
            $this->keyParser->getPublicKey($key),
            $this->adapter->createSigningHash($payload, $this->getAlgorithm()),
            $this->getAlgorithm()
        );
    }

    /**
     * Returns the name of algorithm to be used to create the signing hash
     *
     * @return string
     */
    abstract public function getAlgorithm(): string;

}
