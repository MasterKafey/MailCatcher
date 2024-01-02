<?php

namespace App\Business;

use Symfony\Component\Yaml\Yaml;

class ParameterBusiness
{
    private array $defaultConfiguration = [];

    private array $userConfiguration = [];

    public function __construct(
        private readonly string $defaultConfigurationPath,
        private readonly string $userConfigurationPath,
    )
    {

    }

    public function getParameterValue(string $name): mixed
    {
        if (!$this->doesParameterExists($name)) {
            throw new \InvalidArgumentException("Parameter doesn't exist");
        }

        return $this->userConfiguration[$name] ?? $this->defaultConfiguration[$name]['default'];
    }

    public function setParameterValue(string $name, mixed $value, bool $save = true): void
    {
        if (!array_key_exists($name, $this->defaultConfiguration)) {
            throw new \InvalidArgumentException("Parameter $name doesn't exist");
        }

        $this->userConfiguration[$name] = $value;
        if ($save) {
            $this->saveConfiguration();
        }
    }

    public function getDefaultConfiguration(): array
    {
        if (empty($this->defaultConfiguration)) {
            $this->loadConfiguration();
        }

        return $this->defaultConfiguration;
    }

    public function getUserConfiguration(): array
    {
        if (empty($this->userConfiguration)) {
            $this->loadConfiguration();
        }

        return $this->userConfiguration;
    }

    public function loadConfiguration(): void
    {
        $this->defaultConfiguration = Yaml::parseFile($this->defaultConfigurationPath) ?? [];
        $this->userConfiguration = Yaml::parseFile($this->userConfigurationPath) ?? [];
    }

    public function saveConfiguration(): void
    {
        file_put_contents($this->userConfigurationPath, Yaml::dump($this->userConfiguration));
    }

    public function doesParameterExists(string $name): bool
    {
        return array_key_exists($name, $this->getDefaultConfiguration());
    }
}