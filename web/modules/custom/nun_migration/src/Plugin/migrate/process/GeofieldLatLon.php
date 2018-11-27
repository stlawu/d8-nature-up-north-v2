<?php

/**
 * @file
 * Contains \Drupal\geofield\Plugin\migrate\process\GeofieldLatLon.
 */
namespace Drupal\nun_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * The OOTB implementation doesn't work...
 * This class is not in use.  The decision was made to migrate all D7 geofield
 * instances over to geolocation fields (since this way we have a single field type
 * for lat/lon).
 * Leaving source in case the decision gets reversed at some point.
 *
 * Process latitude and longitude and return the value for the D8 geofield.
 *
 * @MigrateProcessPlugin(
 *   id = "geofield_latlon_djc"
 * )
 */
class GeofieldLatLon extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $lat = isset($value['lat']) ? $value['lat'] : NULL;
    $lon = isset($value['lon']) ? $value['lon'] : NULL;
    //print("transform lat=${lat} lon=${lon} (".print_r($value,TRUE).")\n");
    if (empty($lat) || empty($lon)) {
      return NULL;
     }

    $lonlat = \Drupal::service('geofield.wkt_generator')->WktBuildPoint(array($lon, $lat));
    //print("transform lonlat=${lonlat}\n");
    return $lonlat;
  }

}
