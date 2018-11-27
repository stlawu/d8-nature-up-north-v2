<?php
namespace Drupal\nun_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the genres.
 *
 * @MigrateSource(
 *   id = "djc_address_source"
 * )
 */
class Address extends SqlBase {

/*
// a variable here is "field_contact_information" (the field machine name)
SELECT
    loc.lid lid,
    loc.name name,
    loc.street,
    loc.additional,
    loc.city,
    loc.province,
    loc.postal_code
    rev.entity_id as entity_id,
FROM
    location loc
INNER JOIN
    field_revision_field_event_location rev
ON
    loc.lid=rev.field_event_location_lid
INNER JOIN
    field_data_field_event_location data
ON
    data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id
WHERE loc.name <> '';
*/
  /**
   * {@inheritdoc}
   */
  public function query() {
    $d7_field = $this->configuration['source_field'];
    $query = $this
        ->select('location','loc')
        ->fields('loc',['lid', 'name', 'street','additional','city','province','postal_code','country']);
    $query->innerJoin("field_revision_${d7_field}",'rev',"loc.lid=rev.${d7_field}_lid");
    $query->innerJoin("field_data_${d7_field}",'data','data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id');
    $query->addField('rev','entity_id');
    $query->condition('loc.name','','<>');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'lid' => $this->t('Location ID'),
      'entity_id' => $this->t('Entity ID'),
      'name' => $this->t('Address name'),
      'street' => $this->t('Address street'),
      'additional' => $this->t('Address additional'),
      'city' => $this->t('Address city'),
      'province' => $this->t('Address province'),
      'postal_code' => $this->t('Address postal code'),
      'country' => $this->t('Address country'),
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
