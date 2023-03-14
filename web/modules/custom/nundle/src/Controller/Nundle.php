<?php
namespace Drupal\nundle\Controller;

use Drupal\Core\Controller\ControllerBase;

class Nundle extends ControllerBase {

  private function theme($sources) {
    // requires the geolocation module be configured with the key to use.
    $config = \Drupal::config('geolocation.settings');
    return [
      '#theme' => 'map-component',
      '#api_key' => $config->get('google_map_api_key'),
      '#marker_sources' => $sources,
      '#attached' => [
        'library' => [
          'map_component/component',
        ],
      ],
    ];
  }

  public function game() {
    return [
      '#markup' => file_get_contents(DRUPAL_ROOT . '/libraries/nundle/nundle/index.html')
    ];
  }

  public function backend() {
    return $this->theme('trail');
  }

  public function return() {
    return $this->theme('encounter,trail_unclustered');
  }
}
