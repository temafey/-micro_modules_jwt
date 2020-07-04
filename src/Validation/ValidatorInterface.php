<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Validation;

use MicroModule\JWT\Service\Token\TokenInterface;
use MicroModule\JWT\Service\Validation\Constraint\ConstraintInterface;

interface ValidatorInterface
{

    /**
     * @param \MicroModule\JWT\Service\Token\TokenInterface $token
     * @param \MicroModule\JWT\Service\Validation\Constraint\ConstraintInterface[] ...$constraints
     */
    public function assert(TokenInterface $token, ConstraintInterface ...$constraints): void;

    /**
     * @param \MicroModule\JWT\Service\Token\TokenInterface $token
     * @param \MicroModule\JWT\Service\Validation\Constraint\ConstraintInterface[] ...$constraints
     * @return bool
     */
    public function validate(TokenInterface $token, ConstraintInterface ...$constraints): bool;

}
