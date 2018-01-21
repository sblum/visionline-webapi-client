<?php
/**
 * Defines the class Connection.
 */

namespace Visionline\Crm\WebApi;

/**
 * Specifies a connection to a CRM-VISIONLINE system.
 */
class Connection
{
    /**
     * The host of the CRM-VISIONLINE system, usually 'localhost'.
     *
     * @var string
     */
    public $host;

    /**
     * The port of the CRM-VISIONLINE system, e.g. 5030.
     *
     * @var int
     */
    public $port;

    /**
     * The username under which the webservice calls should be executed.
     *
     * @var string
     */
    public $username;

    /**
     * The password of the user.
     *
     * @var string
     */
    public $password;

    /**
     * Creates a Connection object that specifies the conneciton settings
     * to a CRM-VISIONLINE system.
     *
     * @param string $host     The host of the CRM-VISIONLINE system, usually 'localhost'
     * @param int    $port     The port of the CRM-VISIONLINE system, e.g. 5030
     * @param string $username The username under which the webservice calls should be executed
     * @param string $password The password of the user
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($host, $port, $username, $password)
    {
        $port = (int) $port;

        if (!\is_string($host)) {
            throw new \InvalidArgumentException('"host" must be a valid hostname. Input was: '.$host);
        }

        if (!\is_int($port)) {
            throw new \InvalidArgumentException('"port" must be a valid port number. Input was: '.$port);
        }

        if (!\is_string($username)) {
            throw new \InvalidArgumentException('"username" must be a string. Input was: '.$username);
        }

        if (!\is_string($password)) {
            throw new \InvalidArgumentException('"password" must be a string. Input was: '.$password);
        }

        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }
}
