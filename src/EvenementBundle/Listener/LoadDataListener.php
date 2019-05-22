<?php
/**
 * Created by PhpStorm.
 * User: Bouazza Med
 * Date: 27/02/2019
 * Time: 06:39
 */

namespace EvenementBundle\Listener;

use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;
use AppBundle\Entity\CalendarEvent as MyCustomEvent;
/**
 * Class LoadDataListener
 * @package EvenementBundle\Listener
 */
class LoadDataListener
{

    /**
     * @param CalendarEvent $calendarEvent
     *
     * @return FullCalendarEvent[]
     */
    public function loadData(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStart();
        $endDate = $calendarEvent->getEnd();
        $filters = $calendarEvent->getFilters();

        //You may want do a custom query to populate the events

        $calendarEvent->addEvent(new MyCustomEvent('Event Title 1', new \DateTime()));
        $calendarEvent->addEvent(new MyCustomEvent('Event Title 2', new \DateTime()));
    }
}