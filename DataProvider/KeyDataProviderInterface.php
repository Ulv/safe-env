<?php

namespace App\Lead\SafeEnvBundle\DataProvider;

/**
 * Interface KeyDataProviderInterface
 * @package App\Lead\SafeEnvBundle\DataProvider
 */
interface KeyDataProviderInterface
{
    /**
     * @return mixed
     */
    public function getKey();
}