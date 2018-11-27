<?php
namespace Drupal\nun_helper\Plugin\Field\FieldFormatter;

use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Extension of default image formatter that only displays the first image.
 *
 * @FieldFormatter(
 *   id = "first_image",
 *   label = @Translation("First Image Only"),
 *   field_types = {
 *     "image"
 *   },
 *   quickedit = {
 *     "editor" = "image"
 *   }
 * )
 */
class FirstImageFormatter extends ImageFormatter {

    /**
     * {@inheritdoc}
     */
    public function viewElements(FieldItemListInterface $items, $langcode) {
        /*
        if(!$items->isEmpty()) {
            $entity = $items->getEntity();
            $seen = &drupal_static(__FUNCTION__,array());
            if(in_array($entity->id(),$seen)) {
                return [];
            }
            $seen[] = $entity->id();
        }*/
        $elements = parent::viewElements($items,$langcode);
        while(count($elements) > 1) {
            array_pop($elements);
        }
        return $elements;
    }
}
