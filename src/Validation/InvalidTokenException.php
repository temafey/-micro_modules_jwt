<?php
declare(strict_types=1);

namespace MicroModule\JWT\Validation;

use MicroModule\JWT\Exception;

/**
 *
 */
final class InvalidTokenException extends Exception
{
    /**
     * @var ConstraintViolationException[]
     */
    private $violations = [];

    /**
     * @param ConstraintViolationException[] ...$violations
     *
     * @return InvalidTokenException
     */
    public static function fromViolations(ConstraintViolationException ...$violations): self
    {
        $exception             = new self(self::buildMessage($violations));
        $exception->violations = $violations;

        return $exception;
    }

    /**
     * @param array $violations
     *
     * @return string
     */
    private static function buildMessage(array $violations): string
    {
        $violations = \array_map(
            function (ConstraintViolationException $violation): string {
                return '- ' . $violation->getMessage();
            },
            $violations
        );

        $message  = "The token violates some mandatory constraints, details:\n";
        $message .= \implode("\n", $violations);

        return $message;
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
