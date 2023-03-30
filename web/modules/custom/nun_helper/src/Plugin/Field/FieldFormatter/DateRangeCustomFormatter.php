<?php
namespace Drupal\nun_helper\Plugin\Field\FieldFormatter;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 *
 * @FieldFormatter(
 *   id = "nun_daterange_custom",
 *   label = @Translation("NUN Custom"),
 *   field_types = {
 *     "daterange"
 *   }
 * )
 */
class DateRangeCustomFormatter extends FormatterBase {
  const DATE_SEP = 'to';
  const FMT_DAY = 'F j, Y';
  const FMT_FULL = 'F j, Y - g:i A';
  const FMT_TIME = 'g:i A';

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
      $summary = array();
      $settings = $this->getSettings();

      $summary[] = t('Formats date ranges taking into account differences in start/end range..');

      return $summary;
  }

  protected function buildDate(DrupalDateTime $date,$fmt = DateRangeCustomFormatter::FMT_FULL) {
      return [
          '#plain_text' => $date->format($fmt,[
              'timezone' => date_default_timezone_get(),  // old: drupal_get_user_timezone(),
          ]),
      ];
  }

  private function sameDay(DrupalDateTime $d1, DrupalDateTime $d2) {
      return $d1->format(DateRangeCustomFormatter::FMT_DAY) == $d2->format(DateRangeCustomFormatter::FMT_DAY);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      if (!empty($item->start_date) && !empty($item->end_date)) {
        /** @var \Drupal\Core\Datetime\DrupalDateTime $start_date */
        $start_date = $item->start_date;
        /** @var \Drupal\Core\Datetime\DrupalDateTime $end_date */
        $end_date = $item->end_date;

        if ($start_date->getTimestamp() !== $end_date->getTimestamp()) {
            if($this->sameDay($start_date,$end_date)) {
                // format one complete date and one time
                $elements[$delta] = [
                  'start_date' => $this->buildDate($start_date),
                  'separator' => ['#plain_text' => ' ' . DateRangeCustomFormatter::DATE_SEP . ' '],
                  'end_date' => $this->buildDate($end_date,DateRangeCustomFormatter::FMT_TIME),
                ];
            } else {
                // format two complete days
                $elements[$delta] = [
                  'start_date' => $this->buildDate($start_date),
                  'separator' => ['#plain_text' => ' ' . DateRangeCustomFormatter::DATE_SEP . ' '],
                  'end_date' => $this->buildDate($end_date),
                ];
            }

        }
        else {
          $elements[$delta] = $this->buildDate($start_date);
        }
      }
    }

    return $elements;
  }
}
