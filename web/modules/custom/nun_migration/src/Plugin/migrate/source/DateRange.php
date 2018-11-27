<?php
namespace Drupal\nun_migration\Plugin\migrate\source;

use Drupal\migrate\Row;
use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Source plugin for the genres.
 *
 * @MigrateSource(
 *   id = "djc_date_range_source"
 * )
 */
class DateRange extends SqlBase {

/*
SELECT
    data.field_event_date_value as value,
    data.field_event_date_value2 as value2,
    rev.entity_id as entity_id
FROM
    field_revision_field_event_date rev
INNER JOIN
    field_data_field_event_date data
ON
    data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id
*/
  /**
   * {@inheritdoc}
   */
  public function query() {
    $d7_field = $this->configuration['source_field'];
    $query = $this
        ->select("field_revision_${d7_field}",'rev')
        ->fields('rev',['entity_id']);
    $query->addField('data',"${d7_field}_value",'value');
    $query->addField('data',"${d7_field}_value2",'value2');
    $query->innerJoin("field_data_${d7_field}",'data','data.revision_id=rev.revision_id AND data.entity_id=rev.entity_id');
    return $query;
  }

  private function translateDate($d) {
      $date = new \DateTime($d, new \DateTimeZone('UTC'));
      return gmdate('Y-m-d\TH:i:s',$date->getTimestamp());
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
      $value = $row->getSourceProperty('value');
      $row->setSourceProperty('value',$this->translateDate($value));
      $value2 = $row->getSourceProperty('value2');
      $row->setSourceProperty('end_value',$this->translateDate($value2));
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'entity_id' => $this->t('Entity ID'),
      'value' => $this->t('Start date'),
      'value2' => $this->t('End date'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'entity_id' => [
        'type' => 'integer',
        'alias' => 'rev',
      ],
    ];
  }
}
