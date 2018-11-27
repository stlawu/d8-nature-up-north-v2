<?php

namespace Drupal\nun_helper\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'nun_geolocation_maplink' formatter.
 *
 * @FieldFormatter(
 *   id = "nun_geolocation_maplink",
 *   module = "nun_helper",
 *   label = @Translation("Geolocation Maplink (NUN)"),
 *   field_types = {
 *     "geolocation"
 *   }
 * )
 */
class GeolocationMaplinkFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#theme' => 'geolocation_maplink_formatter',
        '#lat' => $item->lat,
        '#lng' => $item->lng,
      ];
    }

    return $element;
  }

}
