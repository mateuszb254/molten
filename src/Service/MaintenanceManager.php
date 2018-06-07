<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class MaintenanceManager
{
    /**
     * @var string
     */
    private $configPath;

    /**
     * @var array
     */
    private $config;

    public function __construct(string $configPath)
    {
        $this->configPath = $configPath;
        $this->config = Yaml::parseFile($configPath);
    }

    public function setMaintenanceStatus(bool $status): self
    {
        $this->config['maintenance']['isMaintenance'] = $status;

        return $this;
    }

    public function getMaintenanceStatus(): bool
    {
        return $this->config['maintenance']['isMaintenance'];
    }

    public function updateMaintenance(): void
    {
        $file = Yaml::dump($this->config);

        file_put_contents($this->configPath, $file);
    }
}