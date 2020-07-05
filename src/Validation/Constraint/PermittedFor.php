<?php
declare(strict_types=1);

namespace MicroModule\JWT\Validation\Constraint;

use MicroModule\JWT\Validation\ConstraintViolationException;
use MicroModule\JWT\Token\TokenInterface;

/**
 */
final class PermittedFor implements ConstraintInterface
{
    /**
     * @var string
     */
    private $audience;

    /**
     * PermittedFor constructor.
     * @param string $audience
     */
    public function __construct(string $audience)
    {
        $this->audience = $audience;
    }

    /**
     * {@inheritdoc}
     */
    public function assert(TokenInterface $token): void
    {
        if (! $token->isPermittedFor($this->audience)) {
            throw new ConstraintViolationException(
                $this,
                'The token is not allowed to be used by this audience'
            );
        }
    }
}
