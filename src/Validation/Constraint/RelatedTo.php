<?php
declare(strict_types=1);

namespace MicroModule\JWT\Validation\Constraint;

use MicroModule\JWT\Token\TokenInterface;
use MicroModule\JWT\Validation\ConstraintViolationException;

/**
 */
final class RelatedTo implements ConstraintInterface
{
    /**
     * @var string
     */
    private $subject;

    /**
     * RelatedTo constructor.
     * @param string $subject
     */
    public function __construct(string $subject)
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function assert(TokenInterface $token): void
    {
        if (! $token->isRelatedTo($this->subject)) {
            throw new ConstraintViolationException($this, 'The token is not related to the expected subject');
        }
    }
}
