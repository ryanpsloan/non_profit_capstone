<?php
/**
 * Created by PhpStorm.
 * User: dameo_000
 * Date: 7/11/2014
 * Time: 08:50
 */

class Event {
	private $eventId;

	private $eventTitle;

	private $eventDate;

	private $eventLocation;

	/**
	 * @param mixed $eventId product id (or null if new object)
	 * @param string $eventTitle event title
	 * @param string $eventDate the date of the event
	 * @param string $eventLocation where it is taking place
	 **/
	public function __construct($eventId, $eventTitle, $eventDate, $eventLocation){
		try {
			$this->setEventId($newEventId);
			$this->setEventTitle($newEventTitle);
			$this->setEventDate($newEventDate);
			$this->setEventLocation($newEventLocation);
		} catch(UnexpectedValueException $unexpectedValue) {
			throw(new UnexpectedValueException("Unable to construct Event", 0, $unexpectedValue));
		} catch(RangeException $range){
			throw(new RangeException("Unable to construct Event", 0, $range));
		}
	}
	/**
	 * @param mixed $newEventId event id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if event id is not positive
	 **/
	public function setEventId($newEventId){
		if($newEventId === null){
			$this->eventId = null;
			return;
		}

		if(filter_var($newEventId, FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("eventId $newEventId is not numeric"));
		}

		$newEventId = intval($newEventId);
		if($newEventId <= 0) {
			throw(new RangeException("eventId $newEventId is not positive"));
		}

		$this->eventId = $newEventId;
	}

	public function setEventId($newEventTitle){
		$newEventTitle = trim($newEventTitle);
		$newEventTitle = filter_var($newEventTitle, FILTER_SANITIZE_STRING);

		$this->eventTitle = $newEventTitle;
	}

	public function  setEventDate($newEventDate){

	}
}