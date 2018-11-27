<?php
namespace Drupal\nun_migration\Plugin\migrate\destination;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\migrate\destination\DestinationBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Row;

use Drupal\profile\Entity\Profile as D8Profile;;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Ran into:
 * https://www.drupal.org/node/2705925
 *
 * @MigrateDestination(
 *   id = "d8_profile_djc"
 * )
 */
class Profile extends DestinationBase {

    public function import(Row $row, array $old_destination_id_values = []) {
        $destination = $row->getDestination();
        //print('Profile.import='.print_r($destination,TRUE)."\n");
        $profile = D8Profile::create($destination);
        $profile->save();
        return [$destination['pid']]; // track by source pid.
    }

    public function fields(MigrationInterface $migration = NULL) {
        return [];
    }

    public function getIds() {
        return [
           'pid' => [
               'type' => 'integer'
               ]
           ];
    }
}
