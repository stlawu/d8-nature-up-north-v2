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
 * Default migration simply copies the value but this breaks date fields because:
 * D7 date was stored like: 2013-03-03 00:00:00 (local)
 * D8 wants it like: 2013-03-03T04:00:00 (GMT)
 *
 * @MigrateProcessPlugin(
 *   id = "date_djc"
 * )
 */
class Date extends ProcessPluginBase {

  private function translateDate($d) {
      $date = new \DateTime($d);
      return gmdate('Y-m-d\TH:i:s',$date->getTimestamp());
  }
  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
      if(isset($value['value'])) {
          $value['value'] = $this->translateDate($value['value']);
      }
      // may be set for a date range.
      if(isset($value['value2'])) {
          $value['value2'] = $this->translateDate($value['value2']);
          $value['value'] = $value['value'];
          $value['end_value'] = $value['value2'];
      }
      return $value;
  }

}
