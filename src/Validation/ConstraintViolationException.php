<?php
declare(strict_types=1);

namespace MicroModule\JWT\Validation;

use MicroModule\JWT\Exception;
use MicroModule\JWT\Validation\Constraint\ConstraintInterface;
use Throwable;

/**
 *
 */
final class ConstraintViolationException extends Exception
{
    /**
     * Constraint object
     * @var ConstraintInterface
     */
    private $constraint;

    /**
     * ConstraintViolationException constructor.
     *
     * @param ConstraintInterface $constraint
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     *
     */
    public function __construct(ConstraintInterface $constraint, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->constraint = $constraint;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Return constraint object
     *
     * @return ConstraintInterface
     */
    public function getConstraint(): ConstraintInterface
    {
        return $this->constraint;
    }
}
