<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Validation\Constraint;

use MicroModule\JWT\Service\Token\TokenInterface;
use MicroModule\JWT\Service\Validation\ConstraintViolationException;

/**
 */
final class IssuedBy implements ConstraintInterface
{
    /**
     * @var array
     */
    private $issuers;

    public function __construct(string ...$issuers)
    {
        $this->issuers = $issuers;
    }

    /**
     * {@inheritdoc}
     */
    public function assert(TokenInterface $token): void
    {
        if (! $token->hasBeenIssuedBy(...$this->issuers)) {
            throw new ConstraintViolationException(
                $this,
                'The token was not issued by the given issuers'
            );
        }
    }
}
