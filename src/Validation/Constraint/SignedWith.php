<?php
declare(strict_types=1);

namespace MicroModule\JWT\Validation\Constraint;

use MicroModule\JWT\Signer\SignerInterface;
use MicroModule\JWT\Signer\Key;
use MicroModule\JWT\Token\TokenInterface;
use MicroModule\JWT\Token\Plain;
use MicroModule\JWT\Validation\ConstraintViolationException;

/**
 */
final class SignedWith implements ConstraintInterface
{
    /**
     * @var SignerInterface
     */
    private $signer;

    /**
     * @var Key
     */
    private $key;

    /**
     * SignedWith constructor.
     *
     * @param SignerInterface $signer
     * @param Key $key
     */
    public function __construct(SignerInterface $signer, Key $key)
    {
        $this->signer = $signer;
        $this->key    = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function assert(TokenInterface $token): void
    {
        if (! $token instanceof Plain) {
            throw new ConstraintViolationException($this, 'You should pass a plain token');
        }

        if ($token->headers()->get('alg') !== $this->signer->getAlgorithmId()) {
            throw new ConstraintViolationException($this, 'Token signer mismatch');
        }

        if (! $this->signer->verify($token->signature()->hash(), $token->payload(), $this->key)) {
            throw new ConstraintViolationException($this, 'Token signature mismatch');
        }
    }
}
