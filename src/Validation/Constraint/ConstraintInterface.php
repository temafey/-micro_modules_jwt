<?php declare(strict_types = 1);

namespace MicroModule\JWT\Validation\Constraint;

use MicroModule\JWT\Token\TokenInterface;

interface ConstraintInterface
{

    public function assert(TokenInterface $token): void;

}
