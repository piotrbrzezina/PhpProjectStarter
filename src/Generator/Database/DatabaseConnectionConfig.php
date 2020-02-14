<?php

declare(strict_types=1);

namespace App\Generator\Database;

class DatabaseConnectionConfig
{
    private string $host;
    private string $port;
    private string $version;
    private string $user;
    private string $password;
    private string $databaseName;
    private string $protocol;

    public function __construct(
        string $protocol,
        string $host,
        string $port,
        string $version,
        string $user,
        string $password,
        string $databaseName
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->version = $version;
        $this->user = $user;
        $this->password = $password;
        $this->databaseName = $databaseName;
        $this->protocol = $protocol;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getDatabaseName(): string
    {
        return $this->databaseName;
    }

    public function getProtocol(): string
    {
        return $this->protocol;
    }
}
