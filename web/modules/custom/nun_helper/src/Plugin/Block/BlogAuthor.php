<?php
namespace Drupal\nun_helper\Plugin\Block;

use Drupal\Core\Url;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Block\BlockBase;
use \Drupal\user\Entity\User;

/**
 * @Block(
 *   id = "blog_author",
 *   admin_label = @Translation("Blog author block (placed in code)."),
 * )
 */
 class BlogAuthor extends BlockBase {
     /**
      * {@inheritdoc}
      */
     public function build() {
         $return = [
             '#theme' => 'blog_author_block',
         ];
         $routeMatch = \Drupal::routeMatch();
         if('entity.node.canonical' === $routeMatch->getRouteName()) {
             $node = $routeMatch->getParameter('node');
             if('blog' === $node->getType()) {
                 $user = User::load($node->getOwnerId());
                 $return['#user_url'] = Url::fromUri('base:/user/'.$user->id(),['absolute'=>TRUE])->toString();
                 $profile_storage = \Drupal::entityManager()->getStorage('profile');

                 $profile = $profile_storage->loadByUser($user, 'main');
                 $blogger = $profile_storage->loadByUser($user,'blogger');

                 $photo_field = $profile->get('field_profile_photo');
                 if(!$photo_field->isEmpty()) {
                     $photo_field_value = $photo_field->getValue();
                     $return['#image_url'] = ImageStyle::load('medium')->buildUrl($photo_field->entity->uri->value);
                     $return['#image_title'] = $photo_field_value['title'];
                     $return['#image_alt'] = $photo_field_value['alt'];
                 } else {
                     $default_image = $photo_field->getSetting('default_image');
                     if($default_image && $default_image['uuid']) {
                         $default_photo_file = \Drupal::service('entity.repository')->loadEntityByUuid('file',$default_image['uuid']);
                         if($default_photo_file) {
                             $return['#image_url'] = ImageStyle::load('medium')->buildUrl($default_photo_file->getFileUri());
                         }
                     }
                 }
                 $return['#full_name'] = $profile->get('field_profile_full_name')->value;
                 $return['#hometown'] = $profile->get('field_profile_hometown')->value;
                 $return['#current_location'] = $profile->get('field_profile_current_location')->value;

                 if($blogger) {
                     $return['#bio'] = $blogger->get('field_bio')->value;
                 }
             }
         }
         return $return;
     }
 }
