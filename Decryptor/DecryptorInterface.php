<?php

namespace App\Lead\SafeEnvBundle\Decryptor;

/**
 * Interface DecryptorInterface
 * @package App\Lead\SafeEnvBundle\Decryptor
 */
interface DecryptorInterface
{
    /**
     * @param string $string
     * @return DecryptorInterface
     */
    public function setString(string $string): self;

    /**
     * @return string
     */
    public function decrypt(): string;
}