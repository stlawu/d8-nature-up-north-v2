<?php
namespace Drupal\map_component;

class Marker {
    private $id;
    private $lat;
    private $lon;
    private $title;
    private $url;
    private $description;
    private $icon;

    public function __construct($id,$lat,$lon,$title) {
        $this->id = $id;
        $this->lat = floatval($lat);
        $this->lon = floatval($lon);
        $this->title = $title;
    }

    public function id($n = NULL) {
        if(func_num_args()) {
            $this->id = $n;
            return $this;
        }
        return $this->id;
    }

    public function lat($n = NULL) {
        if(func_num_args()) {
            $this->lat = $n;
            return $this;
        }
        return $this->lat;
    }

    public function lon($n = NULL) {
        if(func_num_args()) {
            $this->lon = $n;
            return $this;
        }
        return $this->lon;
    }

    public function url($n = NULL) {
        if(func_num_args()) {
            $this->url = $n;
            return $this;
        }
        return $this->url;
    }

    public function title($n = NULL) {
        if(func_num_args()) {
            $this->title = $n;
            return $this;
        }
        return $this->title;
    }

    public function description($n = NULL) {
        if(func_num_args()) {
            $this->description = $n;
            return $this;
        }
        return $this->description;
    }

    public function icon($n = NULL) {
        if(func_num_args()) {
            $this->icon = $n;
            return $this;
        }
        return $this->icon;
    }

    public function toJson() {
        $return = [
            'id' => $this->id,
            'latitude' => $this->lat,
            'longitude' => $this->lon,
            'title' => $this->title
        ];
        if(isset($this->url)) {
            $return['_self'] = $this->url;
        }
        if(isset($this->description)) {
            $return['description'] = $this->description;
        }
        if(isset($this->icon)) {
            $return['icon'] = $this->icon;
        }
        return $return;
    }
}
