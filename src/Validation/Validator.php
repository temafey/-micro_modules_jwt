<?php
declare(strict_types=1);

namespace MicroModule\JWT\Validation;

use MicroModule\JWT\Token\TokenInterface;
use MicroModule\JWT\Validation\Constraint\ConstraintInterface;

/**
 *
 */
final class Validator implements ValidatorInterface
{
    /**
     * Violation constraint object
     * @var ConstraintInterface
     */
    private $violationConstraint;

    /**
     * Violation message
     * @var string
     */
    private $violationMessage = '';

    /**
     * {@inheritdoc}
     */
    public function assert(TokenInterface $token, ConstraintInterface ...$constraints): void
    {
        $this->violationConstraint = null;
        $this->violationMessage = '';

        $violations = [];

        foreach ($constraints as $constraint) {
            $this->checkConstraint($constraint, $token, $violations);
        }

        if ($violations) {
            throw InvalidTokenException::fromViolations(...$violations);
        }
    }

    /**
     * Check constraint
     *
     * @param ConstraintInterface $constraint
     * @param TokenInterface $token
     * @param array $violations
     *
     * @return void
     */
    private function checkConstraint(
        ConstraintInterface $constraint,
        TokenInterface $token,
        array &$violations
    ): void {
        try {
            $constraint->assert($token);
        } catch (ConstraintViolationException $e) {
            $violations[] = $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(TokenInterface $token, ConstraintInterface ...$constraints): bool
    {
        $this->violationConstraint = null;
        $this->violationMessage = '';

        try {
            foreach ($constraints as $constraint) {
                $constraint->assert($token);
            }

            return true;
        } catch (ConstraintViolationException $e) {
            $this->violationConstraint = $constraint;
            $this->violationMessage = $e->getMessage();
            return false;
        }
    }

    /**
     * Return violation constraint
     *
     * @return ConstraintInterface
     */
    public function getViolationConstraint()
    {
        return $this->violationConstraint;
    }

    /**
     * Return violation message
     *
     * @return string
     */
    public function getViolationMessage(): string
    {
        return $this->violationMessage;
    }
}
