<?php
namespace Drupal\nun_maps\Plugin\markers;

use Drupal\map_component\MarkerSourceBase;

/**
 * @MarkerSource(
 *   id = "trail",
 * )
 */
class Trails extends MarkerSourceBase {

    protected function query() {
        $query = $this->database()->select('node','n');
        $query->addField('n','nid','th_id');
        $query->addField('n','type','type');
        $query->innerJoin('node_revision','r','n.vid=r.vid AND n.nid=r.nid');

        $query->join('node_field_data','data','data.vid=r.vid AND data.nid=r.nid AND data.status=1');
        $query->addField('data','title');

        $query->join('node__field_trail_access','descript','descript.entity_id=r.nid AND descript.revision_id=r.vid');
        $query->addField('descript','field_trail_access_value','description');

        $query->join('node__field_trail','trail','trail.entity_id=r.nid AND trail.revision_id=r.vid');
        $query->addField('trail','field_trail_target_id','id');

        $query->rightJoin('node__field_location','loc','loc.entity_id=r.nid AND loc.revision_id=r.vid');
        $query->addField('loc','field_location_lat','lat');
        $query->addField('loc','field_location_lng','lon');

        $query->where('n.type=\'trail_head\'');
        $query->orderBy('r.revision_timestamp','DESC');

        return $query;
    }

    public function getIcon() { return '/modules/custom/nun_maps/img/hiker.png'; }
}
