<?php
function nun_helper_views_data_alter(&$data) {
    // clobbering any normal string filtering options
    $data['node__field_event_date']['field_event_date_value']['filter']['id'] = 'event_upcoming_or_past';
}
