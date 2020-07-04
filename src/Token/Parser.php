<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Token;

use DateTimeImmutable;
use InvalidArgumentException;
use MicroModule\JWT\Service\Parser\ParserInterface;
use MicroModule\JWT\Service\Parser\DecoderInterface;

/**
 * This class parses the JWT strings and convert them into tokens
 *
 */
final class Parser implements ParserInterface
{
    /**
     * The data decoder
     *
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * Initializes the object
     *
     * @param DecoderInterface $decoder
     */
    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    /**
     * {@inheritdoc}
     */
    public function parse(string $jwt): TokenInterface
    {
        [$encodedHeaders, $encodedClaims, $encodedSignature] = $this->splitJwt($jwt);

        $header = $this->parseHeader($encodedHeaders);

        return new Plain(
            new DataSet($header, $encodedHeaders),
            new DataSet($this->parseClaims($encodedClaims), $encodedClaims),
            $this->parseSignature($header, $encodedSignature)
        );
    }

    /**
     * Splits the JWT string into an array
     *
     * @param string $jwt
     *
     * @return array
     *
     * @throws InvalidArgumentException When JWT doesn't have all parts
     */
    private function splitJwt(string $jwt): array
    {
        $data = \explode('.', $jwt);

        if (\count($data) !== 3) {
            throw new InvalidArgumentException('The JWT string must have two dots');
        }

        return $data;
    }

    /**
     * Parses the header from a string
     *
     * @param string $data
     *
     * @return array
     *
     * @throws InvalidArgumentException When an invalid header is informed
     */
    private function parseHeader(string $data): array
    {
        $header = (array) $this->decoder->jsonDecode($this->decoder->base64UrlDecode($data));

        if (isset($header['enc'])) {
            throw new InvalidArgumentException('Encryption is not supported yet');
        }

        return $header;
    }

    /**
     * Parses the claim set from a string
     *
     * @param string $data
     *
     * @return array
     */
    private function parseClaims(string $data): array
    {
        $claims = (array) $this->decoder->jsonDecode($this->decoder->base64UrlDecode($data));

        if (isset($claims[RegisteredClaims::AUDIENCE])) {
            $claims[RegisteredClaims::AUDIENCE] = (array) $claims[RegisteredClaims::AUDIENCE];
        }

        foreach (\array_intersect(RegisteredClaims::DATE_CLAIMS, \array_keys($claims)) as $claim) {
            $claims[$claim] = $this->convertDate((string) $claims[$claim]);
        }

        return $claims;
    }

    /**
     * Convert date to valid format
     *
     * @param string $value
     *
     * @return DateTimeImmutable
     */
    private function convertDate(string $value): DateTimeImmutable
    {
        if (\strpos($value, '.') === false) {
            return new DateTimeImmutable('@' . $value);
        }

        return DateTimeImmutable::createFromFormat('U.u', $value);
    }

    /**
     * Returns the signature from given data
     *
     * @param array $header
     * @param string $data
     *
     * @return Signature
     */
    private function parseSignature(array $header, string $data): Signature
    {
        if ($data === '' || ! isset($header['alg']) || $header['alg'] === 'none') {
            return Signature::fromEmptyData();
        }

        $hash = $this->decoder->base64UrlDecode($data);

        return new Signature($hash, $data);
    }
}
