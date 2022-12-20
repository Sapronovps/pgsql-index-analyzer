<?php

declare(strict_types=1);

namespace Sapronovps\PgsqlIndexAnalyzer\Option;

interface OptionsInterface
{
    public function setHost(string $host): self;

    public function setPort(int $port): self;

    public function setDbName(string $dbName): self;

    public function setUser(string $user): self;

    public function setPassword(string $password): self;

    public function getHost(): string;

    public function getPort(): int;

    public function getDbName(): string;

    public function getUser(): string;

    public function getPassword(): string;

    public function getDsn(): string;

    public static function createOptions(array $options): self;
}