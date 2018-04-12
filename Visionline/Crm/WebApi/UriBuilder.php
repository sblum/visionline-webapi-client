<?php
/**
 * Defines the class UriBuilder.
 */

namespace Visionline\Crm\WebApi;

/**
 * Utility class that builds an uri.
 */
class UriBuilder
{
    /**
     * The scheme.
     *
     * @var string
     */
    private $scheme;

    /**
     * The host.
     *
     * @var string
     */
    private $host;

    /**
     * The port.
     *
     * @var int
     */
    private $port;

    /**
     * The user.
     *
     * @var string
     */
    private $user;

    /**
     * The password.
     *
     * @var string
     */
    private $pass;

    /**
     * The path.
     *
     * @var string
     */
    private $path;

    /**
     * The query.
     *
     * @var string
     */
    private $query;

    /**
     * The fragment.
     *
     * @var string
     */
    private $fragment;

    /**
     * Create an UriBuilder.
     *
     * @param string $uri The URI to start with
     */
    public function __construct($uri)
    {
        $parts = \parse_url($uri);
        foreach ($parts as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Returns whether the specified URI is full-qualified.
     *
     * @return bool true, if the specified URI is full-qualified
     */
    public function isFullQualified()
    {
        return !empty($this->scheme) && !empty($this->host);
    }

    /**
     * Adds a parameter to the current URI.
     *
     * @param string $name  The parameters name
     * @param string $value The parameters value
     */
    public function addParameter($name, $value)
    {
        if (!empty($this->query)) {
            $this->query .= '&';
        }

        $this->query .= \urlencode($name);
        $this->query .= '=';
        $this->query .= \urlencode($value);
    }

    /**
     * Adds an array of parameters to the current URI,
     * where the array items key is the parameters name and the
     * array items value is the parameters value.
     *
     * @param array $parameters The parameters array
     */
    public function addParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $this->addParameter($key, $value);
        }
    }

    /**
     * Returns the resulting URI.
     *
     * @return string The URI
     */
    public function __toString()
    {
        $parts = [];
        if (!empty($this->scheme)) {
            \array_push($parts, $this->scheme, ':');
        }

        if (!empty($this->host)) {
            \array_push($parts, '//');

            if (!empty($this->user)) {
                \array_push($parts, $this->user);

                if (!empty($this->pass)) {
                    \array_push($parts, ':', $this->pass);
                }

                \array_push($parts, '@');
            }

            \array_push($parts, $this->host);
        }

        if (!empty($this->port)) {
            \array_push($parts, ':', $this->port);
        }

        if (!empty($this->path)) {
            \array_push($parts, $this->path);
        }

        if (!empty($this->query)) {
            \array_push($parts, '?', $this->query);
        }

        if (!empty($this->fragment)) {
            \array_push($parts, '#', $this->fragment);
        }

        return \implode('', $parts);
    }

    /**
     * Sets the scheme of the URI.
     *
     * @param string $scheme The scheme
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }

    /**
     * Gets the scheme of the URI.
     *
     * @return string The scheme
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Sets the host of the URI.
     *
     * @param string $host The host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Gets the host of the URI.
     *
     * @return string The host
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the path of the URI.
     *
     * @param string $path The path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * Gets the path of the URI.
     *
     * @return string The path
     */
    public function getPath()
    {
        return $this->path;
    }
}
