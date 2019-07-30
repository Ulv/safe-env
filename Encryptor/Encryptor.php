<?php

namespace App\Lead\SafeEnvBundle\Encryptor;

use App\Lead\SafeEnvBundle\DataProvider\KeyDataProviderInterface;
use App\Lead\SafeEnvBundle\Exception\EncryptionException;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

/**
 * Class for credentials encryption
 * @package App\Lead\SafeEnvBundle\Encryptor
 */
class Encryptor implements EncryptorInterface
{
    /**
     * @var string
     */
    private $string = '';

    /**
     * @var KeyDataProviderInterface
     */
    private $keyDataProvider;

    /**
     * Decryptor constructor.
     * @param KeyDataProviderInterface $keyDataProvider
     */
    public function __construct(KeyDataProviderInterface $keyDataProvider)
    {
        $this->keyDataProvider = $keyDataProvider;
    }

    /**
     * @param string $string
     * @return EncryptorInterface
     */
    public function setString(string $string): EncryptorInterface
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return string
     * @throws EncryptionException
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function encrypt(): string
    {
        if (!$this->string) {
            throw new EncryptionException('Use setString() before using '.__METHOD__);
        }

        return Crypto::encrypt($this->string, $this->loadKey());
    }

    /**
     * @return Key
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    private function loadKey()
    {
        $encryptionKey = $this->keyDataProvider->getKey();
        return Key::loadFromAsciiSafeString($encryptionKey);
    }
}