<?php
namespace Drupal\nun_migration\Plugin\migrate\destination;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Row;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @MigrateDestination(
 *   id = "djc_field_populator"
 * )
 */
class FieldPopulator extends DestinationBase {

    public function import(Row $row, array $old_destination_id_values = []) {
        if(!isset($this->configuration['destination_field'])) {
            throw new MigrateException('Missing configuration.');
        }
        $destination = $row->getDestination();
        if(!isset($destination['entity_id'])) {
            throw new MigrateException('Missing entity_id.');
        }
        $entityTypeManager = \Drupal::getContainer()->get('entity_type.manager');
        $storage = $entityTypeManager->getStorage('node');
        $entity = $storage->load($destination['entity_id']);
        unset($destination['entity_id']);
        $entity->set($this->configuration['destination_field'],$row->getDestination());
        $entity->save();
        return [$entity->id()];
    }

    public function fields(MigrationInterface $migration = NULL) {
        $d7_field = $this->configuration['destination_field'];
        $fields = [];
        $fields[$d7_field] = $d7_field;
        return $fields;
    }

    public function getIds() {
        return [
           'nid' => [
               'type' => 'integer'
               ]
           ];
    }
}
