<?php

namespace App\EventSubscriber;

use App\Repository\SessionEventRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function PHPUnit\Framework\stringEndsWith;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $sessionEventRepository;

    public function __construct(SessionEventRepository $sessionEventRepository)
    {
        $this->sessionEventRepository = $sessionEventRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $sessionsEvent = $this->sessionEventRepository
            ->createQueryBuilder('session_event')
            ->where('session_event.startAt BETWEEN :start and :end OR session_event.endAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($sessionsEvent as $sessionEvent) {

            if($sessionEvent->getCourse()){
                $calendarEvent = new Event(
                    $sessionEvent->getCourse()->getName(),
                    $sessionEvent->getStartAt(),
                    $sessionEvent->getEndAt(),
                );
            }
            elseif ($sessionEvent->getConference()){
                $calendarEvent = new Event(
                    $sessionEvent->getConference()->getName(),
                    $sessionEvent->getStartAt(),
                    $sessionEvent->getEndAt(),
                );
            }
            else {
                $calendarEvent = new Event(
                    $sessionEvent->getActivity()->getName(),
                    $sessionEvent->getStartAt(),
                    $sessionEvent->getEndAt(),
                );
            }

            $calendarEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
                'textColor' => 'white',
            ]);

            $calendar->addEvent($calendarEvent);
        }
    }
}