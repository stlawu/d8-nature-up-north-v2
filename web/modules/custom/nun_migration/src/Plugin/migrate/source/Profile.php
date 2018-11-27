<?php

namespace Drupal\nun_migration\Plugin\migrate\source;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\d7\FieldableEntity;
use Drupal\Core\Database\Query\SelectInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Extension\ModuleHandler;
use Drupal\Core\State\StateInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Drupal 7 node source from database.
 *
 * @MigrateSource(
 *   id = "d7_profile2_djc"
 * )
 */
class Profile extends FieldableEntity {
  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, StateInterface $state, EntityManagerInterface $entity_manager, ModuleHandlerInterface $module_handler) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration, $state, $entity_manager);
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('state'),
      $container->get('entity.manager'),
      $container->get('module_handler')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Select node in its last revision.
    $query = $this->select('profile', 'p')
      ->fields('p', [
        'pid',
        'type',
        'uid',
        'label',
        'created',
        'changed',
    ]);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    /*
    print("prepareRow.type=".$row->getSourceProperty('type')."\n");
    print("prepareRow.row=".print_r($row,TRUE)."\n");
    */
    // Get Field API field values.
    foreach (array_keys($this->getFields('profile2', $row->getSourceProperty('type'))) as $field) {
      $pid = $row->getSourceProperty('pid');
      $row->setSourceProperty($field, $this->getFieldValues('profile2', $field, $pid));
    }

    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'pid' => $this->t('Profile ID'),
      'type' => $this->t('Type'),
      'uid' => $this->t('User ID'),
      'label' => $this->t('Profile label'),
      'created' => $this->t('Created timestamp'),
      'changed' => $this->t('Modified timestamp'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids['pid']['type'] = 'integer';
    $ids['pid']['alias'] = 'p';
    return $ids;
  }
}
