<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class KingdomExtension extends AbstractExtension
{
    private const REQUIRED_KEYS = ['path', 'shinsoo', 'jinno', 'chunjo'];

    private $path;
    private $file_names = [];
    private $format;

    public function __construct(array $config)
    {
        $this->validateConfig($config);

        $this->path = $config['path'];
        $this->file_names = [
            'shinsoo' => $config['shinsoo'],
            'chunjo' => $config['chunjo'],
            'jinno' => $config['jinno']
        ];
        $this->format = $config['format'];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('kingdom', [
                $this, 'kingdomFilter'
            ])
        ];
    }

    public function kingdomFilter(int $kingdom)
    {
        $url = $this->path.'/';

        switch ($kingdom) {
            case 1:
                $url .= $this->file_names['shinsoo'];
                break;
            case 2:
                $url .= $this->file_names['chunjo'];
                break;
            case 3:
                $url .= $this->file_names['jinno'];
                break;
        }

        $url .= '.'.$this->format;

        return $url;
    }

    private function validateConfig(array $config): void
    {
        foreach (self::REQUIRED_KEYS as $key) {
            if (!array_key_exists($key, $config)) {
                throw new ParameterNotFoundException(sprintf('Cannot find key \'%s\'.', $key));
            }
        }
    }
}