<?php
namespace Drupal\nun_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the genres.
 *
 * @MigrateSource(
 *   id = "djc_email_source"
 * )
 */
class Email extends SqlBase {

/*
// a variable here is "field_contact_information" (the field machine name)
SELECT
    loc.lid lid,
    loc.email email,
    rev.entity_id as entity_id
FROM
    location_email loc
INNER JOIN
    field_revision_field_event_location rev
ON
    loc.lid=rev.field_event_location_lid
INNER JOIN
    field_data_field_event_location data
ON
    data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id
WHERE loc.email <> '';
*/
  /**
   * {@inheritdoc}
   */
  public function query() {
    $d7_field = $this->configuration['source_field'];
    $query = $this
        ->select('location_email','loc')
        ->fields('loc',['lid', 'email']);
    $query->innerJoin("field_revision_${d7_field}",'rev',"loc.lid=rev.${d7_field}_lid");
    $query->innerJoin("field_data_${d7_field}",'data','data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id');
    $query->addField('rev','entity_id');
    $query->condition('loc.email','','<>');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'lid' => $this->t('Location ID'),
      'entity_id' => $this->t('Entity ID'),
      'email' => $this->t('Email address'),
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
