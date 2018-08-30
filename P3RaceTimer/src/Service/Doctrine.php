<?php
namespace P3RaceTimer\Service;

use \Doctrine\ORM\EntityManager;

class Doctrine
{
    private $settings;

    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    public function entityManager()
    {
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
            $this->settings['meta']['entity_path'],
            $this->settings['meta']['auto_generate_proxies'],
            $this->settings['meta']['proxy_dir'],
            $this->settings['meta']['cache'],
            false
        );
        $entityManager = EntityManager::create($this->settings['connection'], $config);
        return $entityManager;
    }
}
