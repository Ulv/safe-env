<?php

namespace App\Lead\SafeEnvBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Bundle for safe encryption
 * @package App\Lead\SafeEnvBundle
 */
class LeadSafeEnvBundle extends Bundle
{
    public const VERSION = '0.0.1';
    public const DSN_VAR_PROCESSOR = 'decrypt_resolve';
}