<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service\Token;

/**
 *
 */
final class DataSet
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $encoded;

    /**
     * DataSet constructor.
     *
     * @param array $data
     * @param string $encoded
     */
    public function __construct(array $data, string $encoded)
    {
        $this->data    = $data;
        $this->encoded = $encoded;
    }

    /**
     * @param string $name
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function get(string $name, $default = null)
    {
        return $this->data[$name] ?? $default;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->data);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->encoded;
    }
}
