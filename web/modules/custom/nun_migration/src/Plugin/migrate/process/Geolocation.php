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
 * Translate D7 geofield to geolocation @ D8
 *
 * @MigrateProcessPlugin(
 *   id = "geolocation_djc"
 * )
 */
class Geolocation extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
      // lat -> lat (no change)
      // lon -> lng
      if(isset($value['lon'])) {
          $value['lng'] = $value['lon'];
      }
      //print("geolocation.value=".print_r($value,TRUE)."\n");
      /*
      geolocation.value=Array
(
    [wkt] => POINT (-74.81827 44.53213)
    [geo_type] => point
    [lat] => 44.5321
    [lon] => -74.8183
    [left] => -74.8183
    [top] => 44.5321
    [right] => -74.8183
    [bottom] => 44.5321
    [srid] =>
    [accuracy] =>
    [source] =>
    [lng] => -74.8183
)
*/
      return $value;
  }
}
