<?php declare(strict_types = 1);

namespace MicroModule\JWT\Parser;

use MicroModule\JWT\Token\TokenInterface;

/**
 * This class parses the JWT strings and convert them into tokens
 */
interface ParserInterface
{

    /**
     * Parses the JWT and returns a token
     *
     * @param string $jwt
     * @return \MicroModule\JWT\Token\TokenInterface
     * @throws \InvalidArgumentException
     */
    public function parse(string $jwt): TokenInterface;

}
