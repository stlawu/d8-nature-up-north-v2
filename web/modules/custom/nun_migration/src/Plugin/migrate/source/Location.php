<?php
namespace Drupal\nun_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the genres.
 *
 * @MigrateSource(
 *   id = "djc_location_source"
 * )
 */
class Location extends SqlBase {

/*
// a variable here is "field_contact_information" (the field machine name)
SELECT
    loc.name name,
    loc.lid lid,
    rev.entity_id as entity_id,
    loc.latitude,
    loc.longitude
FROM
    location loc
INNER JOIN
    field_revision_field_contact_information rev
ON
    loc.lid=rev.field_contact_information_lid
INNER JOIN
    field_data_field_contact_information data
ON
    data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id
WHERE
    loc.latitude <> 0.0 and loc.longitude <> 0.0
*/
  /**
   * {@inheritdoc}
   */
  public function query() {
    $d7_field = $this->configuration['source_field'];
    $query = $this
        ->select('location','loc')
        ->fields('loc',['lid', 'latitude', 'longitude']);
    $query->innerJoin("field_revision_${d7_field}",'rev',"loc.lid=rev.${d7_field}_lid");
    $query->innerJoin("field_data_${d7_field}",'data','data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id');
    $query->addField('rev','entity_id');
    $query->condition('loc.latitude',0.0,'<>')
        ->condition('loc.longitude',0.0,'<>');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'lid' => $this->t('Location ID'),
      'entity_id' => $this->t('Entity ID'),
      'latitude' => $this->t('Latitude'),
      'longitude' => $this->t('Longitude'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'lid' => [
        'type' => 'integer',
        'alias' => 'loc',
      ],
    ];
  }
}
