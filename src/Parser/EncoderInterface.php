<?php declare(strict_types = 1);

namespace MicroModule\JWT\Parser;

/**
 * An utilitarian class that encodes data according with JOSE specifications
 */
interface EncoderInterface
{

    /**
     * Encodes to JSON, validating the errors
     *
     * @param mixed $data
     * @return string
     * @throws \MicroModule\JWT\Parser\Exception When something goes wrong while encoding
     */
    public function jsonEncode($data): string;

    /**
     * Encodes to base64url
     *
     * @param string $data
     * @return string
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlEncode(string $data): string;

}
