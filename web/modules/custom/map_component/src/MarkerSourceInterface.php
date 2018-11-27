<?php
namespace Drupal\map_component;

use Drupal\Core\Entity\EntityInterface;



/**
 * Defines a marker source.
 */
interface MarkerSourceInterface {
    public function getIcon();
    public function getType();
    public function getMarkers();
}
