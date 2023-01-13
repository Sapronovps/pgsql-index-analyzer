<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Connection;

use PDO;
use Sapronovps\PgsqlIndexAnalyzer\Exception\InvalidOptionsParameter;
use Sapronovps\PgsqlIndexAnalyzer\Option\Options;
use Sapronovps\PgsqlIndexAnalyzer\Option\OptionsInterface;

/**
 * Connection to Postgresql.
 */
final class Connection implements ConnectionInterface
{
    private ?PDO $pdo = null;

    private ?OptionsInterface $options = null;

    /**
     * @param OptionsInterface|array<string, mixed> $options
     */
    public function __construct(OptionsInterface|array $options)
    {
        $this->createOptions($options);
        $this->connect();
    }

    /**
     * @param OptionsInterface|array<string, mixed> $options
     * @return void
     */
    public function createOptions(OptionsInterface|array $options): void
    {
        if ($this->options === null) {
            if ($options instanceof OptionsInterface) {
                $this->options = $options;
            }
            if (is_array($options)) {
                $this->options = Options::createOptions($options);
            }
        }
    }

    /**
     * @return void
     */
    public function connect(): void
    {
        if ($this->pdo === null && $this->options !== null) {
            $dsn = $this->options->getDsn();
            $user = $this->options->getUser();
            $password = $this->options->getPassword();

            $this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
    }

    /**
     * @param string $sql
     * @return array<mixed>
     * @throws InvalidOptionsParameter
     */
    public function one(string $sql): array
    {
        return $this->getPdo()->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @param string $sql
     * @return array<mixed>
     * @throws InvalidOptionsParameter
     */
    public function all(string $sql): array
    {
        return $this->getPdo()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function disconnect(): void
    {
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    /**
     * @return PDO
     * @throws InvalidOptionsParameter
     */
    private function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $this->connect();
        }
        if ($this->pdo === null) {
            throw new InvalidOptionsParameter('Cannot create connect, because invalid options parameter.');
        }

        return $this->pdo;
    }
}