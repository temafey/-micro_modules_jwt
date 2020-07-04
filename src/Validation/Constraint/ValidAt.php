<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Validation\Constraint;

use DateTimeImmutable;
use MicroModule\JWT\Service\Token\TokenInterface;
use MicroModule\JWT\Service\Validation\Constraint\ConstraintViolationException;

final class ValidAt implements ConstraintInterface
{

    /** @var \DateTimeImmutable */
    private $clock;

    /**
     * ValidAt constructor.
     *
     * @param \DateTimeImmutable $clock
     */
    public function __construct(DateTimeImmutable $clock)
    {
        $this->clock = $clock;
    }

    public function assert(TokenInterface $token): void
    {
        $this->assertIssueTime($token, $this->clock);
        $this->assertMinimumTime($token, $this->clock);
        $this->assertExpiration($token, $this->clock);
    }

    private function assertExpiration(TokenInterface $token, DateTimeImmutable $now): void
    {
        if ($token->isExpired($now)) {
            throw new ConstraintViolationException($this, 'The token is expired');
        }
    }

    private function assertMinimumTime(TokenInterface $token, DateTimeImmutable $now): void
    {
        if (! $token->isMinimumTimeBefore($now)) {
            throw new \MicroModule\JWT\Service\Validation\Constraint\ConstraintViolationException($this, 'The token cannot be used yet');
        }
    }

    private function assertIssueTime(TokenInterface $token, DateTimeImmutable $now): void
    {
        if (! $token->hasBeenIssuedBefore($now)) {
            throw new \MicroModule\JWT\Service\Validation\Constraint\ConstraintViolationException($this, 'The token was issued in the future');
        }
    }

}
