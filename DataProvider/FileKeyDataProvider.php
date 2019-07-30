<?php

namespace App\Lead\SafeEnvBundle\DataProvider;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;

/**
 * Class FileKeyDataProvider. Provides encryption key from file
 * @package App\Lead\SafeEnvBundle\DataProvider
 */
class FileKeyDataProvider implements KeyDataProviderInterface
{
    /**
     * @var string
     */
    private $filename = '';

    /**
     * FileKeyDataProvider constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function loadKey(): string
    {
        $locator = new FileLocator();
        $fn = $locator->locate($this->filename);
        return is_array($fn) ? reset($fn) : $fn;
    }

    /**
     * @return string
     * @throws FileLocatorFileNotFoundException
     */
    public function getKey()
    {
        $key = file_get_contents($this->loadKey());
        if (!$key) {
            throw new FileLocatorFileNotFoundException(sprintf('Keyfile %s not found!', $this->filename));
        }
        return trim($key);
    }
}