<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Connection;

use PDO;
use Sapronovps\PgsqlIndexAnalyzer\Option\Options;
use Sapronovps\PgsqlIndexAnalyzer\Option\OptionsInterface;

class Connection implements ConnectionInterface
{
    private ?PDO $pdo = null;

    private ?OptionsInterface $options = null;

    public function __construct(OptionsInterface|array $options)
    {
        $this->createOptions($options);
        $this->connect();
    }

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

    public function connect(): void
    {
        if ($this->pdo === null) {
            $dsn = $this->options->getDsn();
            $user = $this->options->getUser();
            $password = $this->options->getPassword();

            $this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
    }

    public function one(string $sql)
    {
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function all($sql): array
    {
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
}