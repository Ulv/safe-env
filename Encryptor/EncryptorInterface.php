<?php

namespace App\Lead\SafeEnvBundle\Encryptor;

/**
 * Interface EncryptorInterface
 * @package App\Lead\SafeEnvBundle\Encryptor
 */
interface EncryptorInterface
{
    /**
     * Set string for encoding
     * @param string $string
     * @return EncryptorInterface
     */
    public function setString(string $string): self;

    /**
     * Encrypt string
     * @return string
     */
    public function encrypt(): string;
}