<?php declare(strict_types = 1);

namespace MicroModule\JWT\Validation;

use MicroModule\JWT\Token\TokenInterface;
use MicroModule\JWT\Validation\Constraint\ConstraintInterface;

interface ValidatorInterface
{

    /**
     * @param \MicroModule\JWT\Token\TokenInterface $token
     * @param \MicroModule\JWT\Validation\Constraint\ConstraintInterface[] ...$constraints
     */
    public function assert(TokenInterface $token, ConstraintInterface ...$constraints): void;

    /**
     * @param \MicroModule\JWT\Token\TokenInterface $token
     * @param \MicroModule\JWT\Validation\Constraint\ConstraintInterface[] ...$constraints
     * @return bool
     */
    public function validate(TokenInterface $token, ConstraintInterface ...$constraints): bool;

}
