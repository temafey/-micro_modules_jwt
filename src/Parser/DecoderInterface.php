<?php declare(strict_types = 1);

namespace MicroModule\JWT\Parser;

/**
 * An utilitarian class that decodes data according with JOSE specifications
 */
interface DecoderInterface
{

    /**
     * Decodes from JSON, validating the errors
     *
     * @param string $json
     * @return mixed
     * @throws \MicroModule\JWT\Parser\Exception When something goes wrong while decoding
     */
    public function jsonDecode(string $json);

    /**
     * Decodes from Base64URL
     *
     * @param string $data
     * @return string
     * @link http://tools.ietf.org/html/rfc4648#section-5
     */
    public function base64UrlDecode(string $data): string;

}
