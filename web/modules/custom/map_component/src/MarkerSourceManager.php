<?php
namespace Drupal\map_component;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Plugin manager for MarkerSources
 */
class MarkerSourceManager extends DefaultPluginManager {
    public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
        parent::__construct(
            'Plugin/markers',
            $namespaces,
            $module_handler,
            'Drupal\map_component\MarkerSourceInterface',
            'Drupal\map_component\Annotation\MarkerSource'
        );
        $this->alterInfo('marker_source_info');
        $this->setCacheBackend($cache_backend, 'marker_source_plugins');
        $this->factory = new DefaultFactory($this->getDiscovery());
    }
}
