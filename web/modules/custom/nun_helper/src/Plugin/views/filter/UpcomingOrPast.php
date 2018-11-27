<?php
namespace Drupal\nun_helper\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\Views;

/**
 * Filters event date ranges.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("event_upcoming_or_past")
 */
class UpcomingOrPast extends FilterPluginBase {
    public function operatorOptions() {
        return [
            'upcoming' => $this->t('Upcoming'),
            'past' => $this->t('Past'),
        ];
    }

    public function query() {
        $nu_in_utc = new \DateTime('now', new \DateTimezone('UTC'));
        $nu_in_utc_in_iso = $nu_in_utc->format('Y-m-d\TH:i:s');
        switch($this->operator) {
            case 'upcoming':
                $this->query->addWhere('AND', 'node__field_event_date.field_event_date_value', $nu_in_utc_in_iso, '>=');
                break;
            case 'past':
                $this->query->addWhere('AND', 'node__field_event_date.field_event_date_value', $nu_in_utc_in_iso, '<');
                break;
        }
    }
}
