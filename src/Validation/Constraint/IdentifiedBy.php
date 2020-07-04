<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Validation\Constraint;

use MicroModule\JWT\Service\Token\TokenInterface;
use MicroModule\JWT\Service\Validation\ConstraintViolationException;

/**
 */
final class IdentifiedBy implements ConstraintInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * IdentifiedBy constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function assert(TokenInterface $token): void
    {
        if (! $token->isIdentifiedBy($this->id)) {
            throw new ConstraintViolationException(
                $this,
                'The token is not identified with the expected ID'
            );
        }
    }
}
