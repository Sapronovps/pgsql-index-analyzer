<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Option;

use Sapronovps\PgsqlIndexAnalyzer\Exception\InvalidOptionsParameter;

final class Options implements OptionsInterface
{
    private string $host = 'localhost';

    private int $port = 5432;

    private string $dbName = '';

    private string $user = '';

    private string $password = '';

    private static array $options = [
        'host',
        'port',
        'dbName',
        'user',
        'password'
    ];

    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function setDbName(string $dbName): self
    {
        $this->dbName = $dbName;

        return $this;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     * @throws InvalidOptionsParameter
     */
    public function getDsn(): string
    {
        $this->validateOptions();

        return 'pgsql:host=' . $this->getHost() . ';port=' . $this->getPort() . ';dbname=' . $this->getDbName() . ';';
    }

    public static function createOptions(array $options): Options
    {
        $newOptions = new self();

        foreach (static::$options as $internalOption) {
            if (isset($options[$internalOption])) {
                $methodName = 'set' . ucfirst($internalOption);
                $newOptions->$methodName($options[$internalOption]);
            }
        }

        return $newOptions;
    }

    /**
     * @return void
     * @throws InvalidOptionsParameter
     */
    private function validateOptions(): void
    {
        foreach (self::$options as $option) {
            if (!$this->{$option}) {
                throw new InvalidOptionsParameter('Invalid Options Parameter: ' . $option);
            }
        }
    }
}