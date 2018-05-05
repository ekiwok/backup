<?php
declare(strict_types=1);

namespace Ekiwok\WPBackup;

final class Database
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $dbPort;

    public function __construct(string $dbName, string $dbUser, string $dbPassword, string $dbHost, int $dbPort)
    {
        $this->name     = $dbName;
        $this->user     = $dbUser;
        $this->password = $dbPassword;
        $this->host     = $dbHost;
        $this->dbPort   = $dbPort;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getDbPort(): int
    {
        return $this->dbPort;
    }
}
