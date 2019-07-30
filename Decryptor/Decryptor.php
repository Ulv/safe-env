<?php

namespace App\Lead\SafeEnvBundle\Decryptor;

use App\Lead\SafeEnvBundle\DataProvider\KeyDataProviderInterface;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

/**
 * Class Decryptor
 * @package App\Lead\SafeEnvBundle\Decryptor
 */
class Decryptor implements DecryptorInterface
{
    /**
     * @var KeyDataProviderInterface
     */
    private $keyDataProvider;

    /**
     * @var string
     */
    private $string = '';

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
     * @return DecryptorInterface
     */
    public function setString(string $string): DecryptorInterface
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return string
     * @throws \Defuse\Crypto\Exception\BadFormatException
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException
     */
    public function decrypt(): string
    {
        return Crypto::decrypt($this->string, $this->loadKey());
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