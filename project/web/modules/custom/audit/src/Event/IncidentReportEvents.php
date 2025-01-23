<?php

namespace Drupal\audit\Event;

/**
 * Defines events for the IncidentReports.
 */
final class IncidentReportEvents {

    /**
     * Dispatched event when a new incident is reported.
     * 
     * @Event 
     * 
     * @see \Drupal\audit\Event\IncidentReport
     */
    const NEW_INCIDENT = 'audit.new_incident_report';
}