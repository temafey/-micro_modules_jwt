<?php declare(strict_types = 1);

namespace MicroModule\JWT\Service\Token;

use DateTimeImmutable;
use MicroModule\JWT\Service\Signer\Key;
use MicroModule\JWT\Service\Signer\SignerInterface;

interface BuilderInterface
{

    /**
     * Appends a new audience
     */
    public function permittedFor(string $audience): BuilderInterface;

    /**
     * Configures the expiration time
     */
    public function expiresAt(DateTimeImmutable $expiration): BuilderInterface;

    /**
     * Configures the token id
     */
    public function identifiedBy(string $id): BuilderInterface;

    /**
     * Configures the time that the token was issued
     */
    public function issuedAt(DateTimeImmutable $issuedAt): BuilderInterface;

    /**
     * Configures the issuer
     */
    public function issuedBy(string $issuer): BuilderInterface;

    /**
     * Configures the time before which the token cannot be accepted
     */
    public function canOnlyBeUsedAfter(DateTimeImmutable $notBefore): BuilderInterface;

    /**
     * Configures the subject
     */
    public function relatedTo(string $subject): BuilderInterface;

    /**
     * Configures a header item
     */
    public function withHeader(string $name, $value): BuilderInterface;

    /**
     * Configures a claim item
     *
     * @throws \InvalidArgumentException When trying to set a registered claim
     */
    public function withClaim(string $name, $value): BuilderInterface;

    /**
     * Returns a signed token to be used
     */
    public function getToken(SignerInterface $signer, Key $key): Plain;

}
