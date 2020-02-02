<?php

declare(strict_types=1);

namespace App\Config;

use Psr\Log\LoggerInterface;
use ReflectionClass;
use ReflectionException;

final class ConfigCollection
{
    /**
     * @var array[]
     */
    private array $configs = [];

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param object[] $configs
     */
    public function add(array $configs): void
    {
        $interfaces = [];
        foreach ($configs as $config) {
            try {
                $reflection = new ReflectionClass($config);
                $interfaces = $reflection->getInterfaceNames();
            } catch (ReflectionException $e) {
                $this->logger->debug($e->getMessage());
            }
            $interfaces[] = get_class($config);

            foreach ($interfaces as $interface) {
                if (!array_key_exists($interface, $this->configs)) {
                    $this->configs[$interface] = [];
                }
                $this->configs[$interface][] = $config;
            }
        }
    }

    public function has(string $fqcn): bool
    {
        return array_key_exists($fqcn, $this->configs);
    }

    /**
     * @param string $fqcn
     *
     * @return object[]
     */
    public function get(string $fqcn): array
    {
        if ($this->has($fqcn)) {
            return $this->configs[$fqcn];
        }

        return [];
    }
}
