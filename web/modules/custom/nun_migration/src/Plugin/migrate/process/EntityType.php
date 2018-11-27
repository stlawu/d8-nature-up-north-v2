<?php

/**
 * @file
 * Contains \Drupal\geofield\Plugin\migrate\process\GeofieldLatLon.
 */
namespace Drupal\nun_migration\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * IMPORTANT:
 * This class maps the d7 entity type profile2 to the d8 type profile.
 * This was an attempt to get the base migration to migrate profiles, and it's
 * probably possible BUT when the fields get migrated over they are of types
 * not matching those found in the 7 system (text long as opposed to plain), etc.
 *
 * SO profiles are NOT being migrated with the base migration but as a post migration
 * step.  Leaving this class and its usage in place (let base migration create the
 * wrong fields and then over-write them since they have no data in them).  If
 * nothing else this can remain for later reference AND its presence in the
 * migration process cuts down a great deal on the errors logged during migration
 * due to profile2 making it easier to see actual problems.
 *
 * @MigrateProcessPlugin(
 *   id = "entity_type_djc"
 * )
 */
class EntityType extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
      if($value === 'profile2') {
          return 'profile';
      }
      return $value;
  }

}
