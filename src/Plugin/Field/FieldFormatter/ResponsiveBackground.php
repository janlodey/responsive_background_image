<?php

/**
 * @file
 * Contains \Drupal\responsive_background_image\Plugin\Field\FieldFormatter\ResponsiveBackground.
 */

namespace Drupal\responsive_background_image\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * @FieldFormatter(
 *  id = "responsive_background_image",
 *  label = @Translation("Responsive Background Image"),
 *  field_types = {"image"}
 * )
 */
class ResponsiveBackground extends ImageFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
        'image_style_desktop' => '',
        'image_style_tablet' => '',
        'image_style_mobile' => ''
    );
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $image_styles = image_style_options(FALSE);
    $description_link = Link::fromTextAndUrl(
      $this->t('Configure Image Styles'),
      Url::fromRoute('entity.image_style.collection')
    );
    $element['image_style_desktop'] = [
      '#title' => t('Image style desktop'),
      '#type' => 'select',
      '#default_value' => $this->getSetting('image_style_desktop'),
      '#empty_option' => t('None (original image)'),
      '#options' => $image_styles,
    ];
    $element['image_style_tablet'] = [
        '#title' => t('Image style tablet'),
        '#type' => 'select',
        '#default_value' => $this->getSetting('image_style_tablet'),
        '#empty_option' => t('None (original image)'),
        '#options' => $image_styles
    ];
    $element['image_style_mobile'] = [
        '#title' => t('Image style mobile'),
        '#type' => 'select',
        '#default_value' => $this->getSetting('image_style_mobile'),
        '#empty_option' => t('None (original image)'),
        '#options' => $image_styles,
        '#description' => $description_link->toRenderable() + [
                '#access' => $this->currentUser->hasPermission('administer image styles')
            ],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();

    $image_styles = image_style_options(FALSE);


    // Unset possible 'No defined styles' option.
    unset($image_styles['']);

    $image_style_setting['desktop'] = $this->getSetting('image_style_desktop');
    $image_style_setting['tablet'] = $this->getSetting('image_style_tablet');
    $image_style_setting['mobile'] = $this->getSetting('image_style_mobile');

    // Styles could be lost because of enabled/disabled modules that defines
    // their styles in code.
    if (isset($image_styles[$image_style_setting['desktop']])) {
      $summary[] = '';
      foreach($image_style_setting as $key => $item){
        $summary[] .= t('Image style ') . $key .': '. $image_styles[$image_style_setting[$key]];
      }
    }
    else {
      $summary[] = t('Original images');
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = array();

    $settings = $this->getSettings();
    $files = $this->getEntitiesToView($items, $langcode);
    // prep image styles we want
    $image_style['data-src'] = $settings['image_style_desktop'] ? $settings['image_style_desktop'] : NULL;
    $image_style['data-src-tablet'] = $settings['image_style_tablet'] ? $settings['image_style_tablet'] : NULL;
    $image_style['data-src-mobile'] = $settings['image_style_mobile'] ? $settings['image_style_mobile'] : NULL;

    // Early opt-out if the field is empty.
    if (empty($files)) {
      return $elements;
    }

    foreach ($files as $delta => $file) {

      $item_attributes = array();
      $image_url = '';

      foreach($image_style as $key => $item){
        if ($item) {
          $style = $this->imageStyleStorage->load($item);
          $image_url = $style->buildUrl($file->getFileUri());
        } else {
          $image_url = file_create_url($file->getFileUri());
        }

        $item_attributes[$key] = $image_url;

      }

      $elements[$delta] = array(
        '#markup' => '',
        '#data-src' => $item_attributes,
        '#attached' => array(
          'library' => array(
            'responsive_background_image/b-lazy'
          ),
        ),
      );

    }

    return $elements;

  }

}