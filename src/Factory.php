<?php
declare(strict_types=1);

namespace MicroModule\JWT\Service;

use MicroModule\JWT\Service\Signer\Key as SignerKey;
use MicroModule\JWT\Service\Signer\SignerInterface;
use MicroModule\JWT\Service\Token\BuilderInterface;
use MicroModule\JWT\Service\Parser\ParserInterface;
use MicroModule\JWT\Service\Validation\ConstraintViolationException;
use MicroModule\JWT\Service\Validation\InvalidTokenException;
use MicroModule\JWT\Service\Validation\ValidatorInterface;
use MicroModule\JWT\Service\Validation\Constraint;

/**
 *
 */
final class Factory
{
    const CLAIMS_PARAM_NAME     = 'claims';
    const TOKEN_PARAM_NAME      = 'token';
    const AUDIENCE_PARAM_NAME   = 'aud';
    const VALID_PARAM_NAME      = 'valid';
    const CONSTRAINT_PARAM_NAME = 'constraints';

    /**
     * @var SignerInterface
     */
    protected $signer;

    /**
     * @var SignerKey
     */
    protected $signerPrivateKey;

    /**
     * @var SignerKey
     */
    protected $signerPublicKey;

    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var \DateTimeImmutable
     */
    protected $currentDate;

    /**
     * @var string
     */
    protected $issuer;

    /**
     * @var string
     */
    protected $audience;

    /**
     * @var string
     */
    protected $uniqueId;

    /**
     * Token activation interval in seconds
     * @var integer
     */
    protected $usedAfterInterval = 0;

    /**
     * Token expire interval in seconds
     * @var integer
     */
    protected $expireInterval;

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @param array $claims
     * @param array $headers
     *
     * @return string
     *
     * @throws \Exception
     */
    public function generateToken(array $claims = [], array $headers = []): string
    {
        $builder = $this->getBuilder();

        $builder->issuedBy($this->getIssuer()); // Configures the issuer (iss claim)
        $builder->permittedFor($this->getAudience()); // Configures the audience (aud claim)
        $builder->identifiedBy($this->getUniqueId()); // Configures the id (jti claim), replicating as a header item
        $builder->issuedAt($this->getCurrentDate()); // Configures the time that the token was issue (iat claim)
        $builder->canOnlyBeUsedAfter($this->getUsedAfterDate()); // Configures the time that the token can be used (nbf claim)
        $builder->expiresAt($this->getExpireDate()); // Configures the expiration time of the token (nbf claim)

        foreach ($claims as $key => $value) {
            $builder->withClaim($key, $value);
        }

        foreach ($headers as $key => $value) {
            $builder->withHeader($key, $value);
        }

        $signer = $this->getSigner();
        $keychain = $this->getSignerPrivateKey();

        return (string) $builder->getToken($signer, $keychain); // creates a signature using your private key
    }

    /**
     * @param string $token
     *
     * @return array
     */
    public function verifyToken(string $token): array
    {
        $parser = $this->getParser();

        $arguments = [];
        $tokenClaim = $parser->parse($token);
        $arguments[] = $tokenClaim;
        $arguments += $this->getConstraints();

        $validator = $this->getValidator();
        $result = [self::VALID_PARAM_NAME => true];
        $result[self::CLAIMS_PARAM_NAME] = $tokenClaim->claims()->all();
        $result[self::CONSTRAINT_PARAM_NAME] = [];

        try {
            call_user_func_array([$validator, 'assert'], $arguments);
        } catch (InvalidTokenException $e) {
            $result[self::VALID_PARAM_NAME] = false;

            foreach ($e->getViolations() as $violation) {
                if ($violation instanceof ConstraintViolationException) {
                    $shortConstraintName = (new \ReflectionClass($violation->getConstraint()))->getShortName();
                    $result[self::CONSTRAINT_PARAM_NAME][$shortConstraintName] = $violation->getMessage();
                }
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getConstraints(): array
    {
        $constrains = [];
        $constrains[] = $this->getConstraintValidAt();
        $constrains[] = $this->getConstraintIssuedBy();
        $constrains[] = $this->getConstraintSignedWith();

        return $constrains;
    }

    /**
     * @return Constraint\ValidAt
     */
    public function getConstraintValidAt(): Constraint\ValidAt
    {
        return new Constraint\ValidAt($this->getCurrentDate());
    }

    /**
     * @return Constraint\IssuedBy
     */
    public function getConstraintIssuedBy(): Constraint\IssuedBy
    {
        return new Constraint\IssuedBy($this->getIssuer());
    }

    /**
     * @return Constraint\SignedWith
     */
    public function getConstraintSignedWith(): Constraint\SignedWith
    {
        return new Constraint\SignedWith($this->getSigner(), $this->getSignerPublicKey());
    }

    /**
     * @return string
     */
    public function getIssuer(): string
    {
        return $this->issuer;
    }

    /**
     * @param string $issuer
     *
     * @return Factory
     */
    public function setIssuer(string $issuer): self
    {
        $this->issuer = $issuer;

        return $this;
    }

    /**
     * @return string
     */
    public function getAudience(): string
    {
        return $this->audience;
    }

    /**
     * @param string $audience
     *
     * @return Factory
     */
    public function setAudience(string $audience): self
    {
        $this->audience = $audience;

        return $this;
    }

    /**
     * @return string
     *
     * @throws \Exception
     */
    public function getUniqueId(): string
    {
        if (null === $this->uniqueId) {
            $this->uniqueId = base64_encode(random_bytes(32));
        }

        return $this->uniqueId;
    }

    /**
     * @return \DateTimeImmutable
     */
    protected function getCurrentDate()
    {
        if (null === $this->currentDate) {
            $this->currentDate = new \DateTimeImmutable();
        }

        return $this->currentDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    protected function getUsedAfterDate()
    {
        $dateInterval = new \DateInterval('PT' . $this->getUsedAfterInterval() . 'S');
        $usedAfterDate = $this->getCurrentDate()->add($dateInterval);

        return $usedAfterDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    protected function getExpireDate()
    {
        $dateInterval = new \DateInterval('PT' . $this->getExpireInterval() . 'S');
        $expireDate = $this->getCurrentDate()->add($dateInterval);

        return $expireDate;
    }

    /**
     * @return SignerInterface
     */
    public function getSigner(): SignerInterface
    {
        return $this->signer;
    }

    /**
     * @param SignerInterface $signer
     *
     * @return Factory
     *
     * @required
     */
    public function setSigner(SignerInterface $signer): self
    {
        $this->signer = $signer;

        return $this;
    }

    /**
     * @return ParserInterface
     */
    public function getParser(): ParserInterface
    {
        return $this->parser;
    }

    /**
     * @param ParserInterface $parser
     *
     * @return Factory
     *
     * @required
     */
    public function setParser(ParserInterface $parser): self
    {
        $this->parser = $parser;

        return $this;
    }

    /**
     * @return SignerKey
     */
    public function getSignerPrivateKey(): SignerKey
    {
        return $this->signerPrivateKey;
    }

    /**
     * @param SignerKey $signerKey
     *
     * @return Factory
     *
     * @required
     */
    public function setSignerPrivateKey(SignerKey $signerKey): self
    {
        $this->signerPrivateKey = $signerKey;

        return $this;
    }

    /**
     * @return SignerKey
     */
    public function getSignerPublicKey(): SignerKey
    {
        return $this->signerPublicKey;
    }

    /**
     * @param SignerKey $signerKey
     *
     * @return Factory
     *
     * @required
     */
    public function setSignerPublicKey(SignerKey $signerKey): self
    {
        $this->signerPublicKey = $signerKey;

        return $this;
    }

    /**
     * @return BuilderInterface
     */
    public function getBuilder(): BuilderInterface
    {
        return $this->builder;
    }

    /**
     * @param BuilderInterface $builder
     *
     * @return Factory
     *
     * @required
     */
    public function setBuilder(BuilderInterface $builder): self
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     *
     * @return Factory
     *
     * @required
     */
    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsedAfterInterval(): int
    {
        return $this->usedAfterInterval;
    }

    /**
     * @param integer $usedAfterInterval
     *
     * @return Factory
     */
    public function setUsedAfterInterval(int $usedAfterInterval): self
    {
        $this->usedAfterInterval = $usedAfterInterval;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpireInterval(): int
    {
        return $this->expireInterval;
    }

    /**
     * @param integer $expireInterval
     *
     * @return Factory
     */
    public function setExpireInterval(int $expireInterval): self
    {
        $this->expireInterval = $expireInterval;

        return $this;
    }
}
