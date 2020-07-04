<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Parser;

/**
 * An utilitarian class that encodes and decodes data according with JOSE specifications
 *
 */
final class Parser implements EncoderInterface, DecoderInterface
{
    /**
     * {@inheritdoc}
     */
    public function jsonEncode($data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $this->verifyJsonError('Error while encoding to JSON');

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonDecode(string $json)
    {
        $data = json_decode($json, true);
        $this->verifyJsonError('Error while decoding from JSON');

        return $data;
    }

    /**
     * Throws a parsing exception when an error happened while encoding or decoding
     *
     * @param string $message
     *
     * @throws Exception
     */
    private function verifyJsonError(string $message): void
    {
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(sprintf('%s: %s', $message, json_last_error_msg()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function base64UrlEncode(string $data): string
    {
        return str_replace('=', '', strtr(base64_encode($data), '+/', '-_'));
    }

    /**
     * {@inheritdoc}
     */
    public function base64UrlDecode(string $data): string
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        $result = base64_decode(strtr($data, '-_', '+/'));

        if (!$result) {
            throw new Exception("Error while decoding from base64.");
        }

        return $result;
    }
}
