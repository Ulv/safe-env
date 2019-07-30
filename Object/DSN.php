<?php

namespace App\Lead\SafeEnvBundle\Object;

use App\Lead\SafeEnvBundle\Exception\DSNException;

/**
 * Class representing DSN string (database connection)
 * @package App\Security\Env
 */
class DSN
{
    /**
     * @var string
     */
    private $scheme = '';

    /**
     * @var string
     */
    private $host = '';

    /**
     * @var string
     */
    private $port = '';

    /**
     * @var string
     */
    private $user = '';

    /**
     * @var string
     */
    private $pass = '';

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $query = '';

    /**
     * @var string
     */
    private $fragment = '';

    /**
     * DSN constructor.
     * @param string $dsnString
     * @throws DSNException
     */
    public function __construct($dsnString = '')
    {
        if ($dsnString) {
            $this->fromString($dsnString);
        }
    }

    /**
     * Init class with values parsed from parameter string
     * @param string $dsnString
     * @throws DSNException if DSN invalid
     */
    public function fromString(string $dsnString): void
    {
        $parsedEnv = parse_url($dsnString);
        if (false === $parsedEnv) {
            throw new DSNException(sprintf('Invalid DSN in env var "%s"', $dsnString));
        }
        if (!isset($parsedEnv['scheme'], $parsedEnv['host'])) {
            throw new DSNException(sprintf('Invalid DSN env var "%s": schema and host expected.', $dsnString));
        }

        foreach ($parsedEnv as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     */
    public function setPass(string $pass): void
    {
        $this->pass = $pass;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = '/' === $path ? '' : substr($path, 1);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%s://%s:%s@%s:%d/%s',
            $this->scheme,
            $this->user,
            $this->pass,
            $this->host,
            $this->port,
            $this->path
        );
    }

    /**
     * Clone object
     * @return DSN
     * @throws DSNException
     */
    public function clone():self
    {
        return new self((string)$this);
    }
}