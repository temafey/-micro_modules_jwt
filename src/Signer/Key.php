<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Signer;

use InvalidArgumentException;

/**
 */
final class Key
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $passphrase;

    /**
     * @param string $content
     * @param string $passphrase
     */
    public function __construct(string $content, string $passphrase = '')
    {
        $this->setContent($content);
        $this->passphrase = $passphrase;
    }

    /**
     * @param string $content
     *
     * @throws InvalidArgumentException
     */
    private function setContent(string $content): void
    {
        if (\strpos($content, 'file://') === 0) {
            $content = $this->readFile($content);
        }

        $this->content = $content;
    }

    /**
     * @param string $content
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    private function readFile(string $content): string
    {
        $file = \substr($content, 7);

        if (! \is_readable($file)) {
            throw new \InvalidArgumentException('You must inform a valid key file');
        }

        return \file_get_contents($file);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string
     */
    public function getPassphrase(): string
    {
        return $this->passphrase;
    }
}
