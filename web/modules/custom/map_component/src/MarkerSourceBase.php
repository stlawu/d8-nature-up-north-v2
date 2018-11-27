<?php
namespace Drupal\map_component;

use Drupal\Core\Url;
use Drupal\Component\Plugin\PluginBase;

abstract class MarkerSourceBase extends PluginBase  implements MarkerSourceInterface {

    public function getIcon() { return NULL; }
    public function getType() { return 'cluster'; }

    public function getMarkers() {
        $result = $this->query()->execute();
        $markers = [];
        while($row = $result->fetchAssoc()) {
            $markers[] = $this->createMarker($row);
        }
        return $markers;
    }

    protected function createMarker($row) {
        $marker = (new Marker($row['id'],$row['lat'],$row['lon'],$row['title']))->url($this->url($row));
        if(isset($row['description'])) {
            $marker->description($row['description']);
        }
        return $marker;
    }

    protected function url($row) {
        if(isset($row['id'])) {
            return Url::fromUri("base:/node/${row['id']}",['absolute'=>TRUE])->toString();
        }
        return NULL;
    }

    /**
     *
     */
    abstract protected function query();

    protected function database() {
        return \Drupal::getContainer()->get('database');
    }
}
