<?php
namespace Drupal\nun_maps\Plugin\markers;

use Drupal\map_component\MarkerSourceBase;

/**
 * @MarkerSource(
 *   id = "trail_unclustered",
 * )
 */
class TrailsUnclustered extends Trails {
    public function getType() { return NULL; }
}
