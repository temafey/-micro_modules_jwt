<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Validation\Constraint;

use MicroModule\JWT\Service\Token\TokenInterface;

interface ConstraintInterface
{

    public function assert(TokenInterface $token): void;

}
