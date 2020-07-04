<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Token;

use DateTimeImmutable;
use MicroModule\JWT\Service\Parser;
use MicroModule\JWT\Service\Signer\Key;
use MicroModule\JWT\Service\Signer\SignerInterface;

/**
 * This class makes easier the token creation process
 *
 */
final class Builder implements BuilderInterface
{
    /**
     * The token header
     *
     * @var array
     */
    private $headers = ['typ'=> 'JWT', 'alg' => 'none'];

    /**
     * The token claim set
     *
     * @var array
     */
    private $claims = [];

    /**
     * The data encoder
     *
     * @var Parser\Encoder
     */
    private $encoder;

    /**
     * Initializes a new builder
     *
     * @param Parser\EncoderInterface $encoder
     */
    public function __construct(Parser\EncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function permittedFor(string $audience): BuilderInterface
    {
        $audiences = $this->claims[RegisteredClaims::AUDIENCE] ?? [];

        if (! \in_array($audience, $audiences)) {
            $audiences[] = $audience;
        }

        return $this->setClaim(RegisteredClaims::AUDIENCE, $audiences);
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt(DateTimeImmutable $expiration): BuilderInterface
    {
        return $this->setClaim(RegisteredClaims::EXPIRATION_TIME, $expiration);
    }

    /**
     * {@inheritdoc}
     */
    public function identifiedBy(string $id): BuilderInterface
    {
        return $this->setClaim(RegisteredClaims::ID, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function issuedAt(DateTimeImmutable $issuedAt): BuilderInterface
    {
        return $this->setClaim(RegisteredClaims::ISSUED_AT, $issuedAt);
    }

    /**
     * {@inheritdoc}
     */
    public function issuedBy(string $issuer): BuilderInterface
    {
        return $this->setClaim(RegisteredClaims::ISSUER, $issuer);
    }

    /**
     * {@inheritdoc}
     */
    public function canOnlyBeUsedAfter(DateTimeImmutable $notBefore): BuilderInterface
    {
        return $this->setClaim(RegisteredClaims::NOT_BEFORE, $notBefore);
    }

    /**
     * {@inheritdoc}
     */
    public function relatedTo(string $subject): BuilderInterface
    {
        return $this->setClaim(RegisteredClaims::SUBJECT, $subject);
    }

    /**
     * {@inheritdoc}
     */
    public function withHeader(string $name, $value): BuilderInterface
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function withClaim(string $name, $value): BuilderInterface
    {
        if (\in_array($name, RegisteredClaims::ALL, true)) {
            throw new \InvalidArgumentException('You should use the correct methods to set registered claims');
        }

        return $this->setClaim($name, $value);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return BuilderInterface
     */
    private function setClaim(string $name, $value): BuilderInterface
    {
        $this->claims[$name] = $value;

        return $this;
    }

    /**
     * @param array $items
     *
     * @return string
     */
    private function encode(array $items): string
    {
        return $this->encoder->base64UrlEncode(
            $this->encoder->jsonEncode($items)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getToken(SignerInterface $signer, Key $key): Plain
    {
        $headers        = $this->headers;
        $headers['alg'] = $signer->getAlgorithmId();

        $encodedHeaders = $this->encode($headers);
        $encodedClaims  = $this->encode($this->formatClaims($this->claims));

        $signature        = $signer->sign($encodedHeaders . '.' . $encodedClaims, $key);
        $encodedSignature = $this->encoder->base64UrlEncode($signature);

        return new Plain(
            new DataSet($headers, $encodedHeaders),
            new DataSet($this->claims, $encodedClaims),
            new Signature($signature, $encodedSignature)
        );
    }

    /**
     * @param array $claims
     *
     * @return array
     */
    private function formatClaims(array $claims): array
    {
        if (isset($claims[RegisteredClaims::AUDIENCE][0]) && ! isset($claims[RegisteredClaims::AUDIENCE][1])) {
            $claims[RegisteredClaims::AUDIENCE] = $claims[RegisteredClaims::AUDIENCE][0];
        }

        foreach (\array_intersect(RegisteredClaims::DATE_CLAIMS, \array_keys($claims)) as $claim) {
            $claims[$claim] = $this->convertDate($claims[$claim]);
        }

        return $claims;
    }

    /**
     * @return string
     */
    private function convertDate(\DateTimeImmutable $date): string
    {
        $seconds      = $date->format('U');
        $microseconds = $date->format('u');

        if ((int) $microseconds === 0) {
            return $seconds;
        }

        return $seconds . '.' . $microseconds;
    }
}
