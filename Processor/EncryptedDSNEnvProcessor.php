<?php

namespace App\Lead\SafeEnvBundle\Processor;

use App\Lead\SafeEnvBundle\Decryptor\DecryptorInterface;
use App\Lead\SafeEnvBundle\Encryptor\EncryptorInterface;
use App\Lead\SafeEnvBundle\LeadSafeEnvBundle;
use App\Lead\SafeEnvBundle\Object\DSN;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Symfony\Component\DependencyInjection\EnvVarProcessorInterface;

/**
 * Class EncryptedDSNEnvProcessor
 * @package App\Lead\SafeEnvBundle\Processor
 */
class EncryptedDSNEnvProcessor implements EnvVarProcessorInterface
{
    /**
     * @var EnvVarProcessorInterface
     */
    private $envVarProcessor;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @var DecryptorInterface
     */
    private $decryptor;

    /**
     * EncryptedDSNEnvProcessor constructor.
     * @param EnvVarProcessorInterface $envVarProcessor
     * @param EncryptorInterface $encryptor
     * @param DecryptorInterface $decryptor
     */
    public function __construct(EnvVarProcessorInterface $envVarProcessor, EncryptorInterface $encryptor, DecryptorInterface $decryptor)
    {
        $this->envVarProcessor = $envVarProcessor;
        $this->encryptor = $encryptor;
        $this->decryptor = $decryptor;
    }

    /**
     * @param string $prefix
     * @param string $name
     * @param \Closure $getEnv
     * @return string
     * @throws \App\Lead\SafeEnvBundle\Exception\DSNException
     */
    public function getEnv($prefix, $name, \Closure $getEnv)
    {
        $name = $this->envVarProcessor->getEnv('resolve', $name, $getEnv);
        $originalDsn = new DSN($name);
        $encryptedDsn = $originalDsn->clone();
        try {
            $username = $originalDsn->getUser()
                ? $this->decryptor->setString($originalDsn->getUser())->decrypt()
                : '';
            $password = $originalDsn->getPass()
                ? $this->decryptor->setString($originalDsn->getPass())->decrypt()
                : '';
            $encryptedDsn->setUser($username);
            $encryptedDsn->setPass($password);
            return (string)$encryptedDsn;
        } catch (WrongKeyOrModifiedCiphertextException $exception) {
            // DSN is not encrypted?
            return (string)$originalDsn;
        }
    }

    /**
     * @return array|string[]
     */
    public static function getProvidedTypes()
    {
        return [
            LeadSafeEnvBundle::DSN_VAR_PROCESSOR => 'string',
        ];
    }
}