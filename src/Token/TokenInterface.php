<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Token;

use DateTimeInterface;

/**
 * Defines the basic structure of plain and encoded tokens
 */
interface TokenInterface
{

    /**
     * Returns the token claims
     *
     * @return \MicroModule\JWT\Service\Token\DataSet
     */
    public function claims(): DataSet;

    /**
     * Returns the token headers
     *
     * @return \MicroModule\JWT\Service\Token\DataSet
     */
    public function headers(): DataSet;

    /**
     * Returns if the token is allowed to be used by the audience
     *
     * @param string $audience
     * @return bool
     */
    public function isPermittedFor(string $audience): bool;

    /**
     * Returns if the token has the given id
     *
     * @param string $id
     * @return bool
     */
    public function isIdentifiedBy(string $id): bool;

    /**
     * Returns if the token has the given subject
     *
     * @param string $subject
     * @return bool
     */
    public function isRelatedTo(string $subject): bool;

    /**
     * Returns if the token was issued by any of given issuers
     *
     * @param string[] ...$issuers
     * @return bool
     */
    public function hasBeenIssuedBy(string ...$issuers): bool;

    /**
     * Returns if the token was issued before of given time
     *
     * @param \DateTimeInterface $now
     * @return bool
     */
    public function hasBeenIssuedBefore(DateTimeInterface $now): bool;

    /**
     * Returns if the token minimum time is before than given time
     *
     * @param \DateTimeInterface $now
     * @return bool
     */
    public function isMinimumTimeBefore(DateTimeInterface $now): bool;

    /**
     * Returns if the token is expired
     *
     * @param \DateTimeInterface $now
     * @return bool
     */
    public function isExpired(DateTimeInterface $now): bool;

    /**
     * Returns an encoded representation of the token
     *
     * @return string
     */
    public function __toString(): string;

}
