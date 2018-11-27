<?php
namespace Drupal\nun_maps\Controller;

use Drupal\Core\Controller\ControllerBase;

class Maps extends ControllerBase {

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

    public function encounters() {
        return $this->theme('encounter');
    }

    public function trails() {
        return $this->theme('trail');
    }

    public function combined() {
        return $this->theme('encounter,trail_unclustered');
    }


}
