<?php
/**
 * This is the class that will connect event and team and allow them to draw permissions from eachother
 * Connecting class for event and team
 *
 * Author: Dameon Smith
 */

require_once("../php/event.php");
require_once("../php/team.php");


class EventTeam {
	// The id and foreign key for team
	private $teamId;
	// The id and foreign key for Event
	private $eventId;
	// The status for the team, whether the team is the creator or not
	private $teamStatus;
	// The commenting permissions teams have within events
	private $commentingPermission;
	// The ban status of a team within an event
	private $banStatus;



	public function __construct($newTeamId, $newEventId, $newTeamStatus, $newCommentingPermission, $newBanStatus){
		try {
			$this->setTeamId($newTeamId);
			$this->setEventId($newEventId);
			$this->setTeamStatus($newTeamStatus);
			$this->setCommentingPermission($newCommentingPermission);
			$this->setBanStatus($newBanStatus);
		} catch(UnexpectedValueException $unexpectedValue){
			throw(new UnexpectedValueException("Could not construct object eventTeam", 0, $unexpectedValue));
		} catch(RangeException $range){
			throw(new RangeException("Could not construct object eventTeam", 0, $range));
		}
	}

	public function __get($name)
	{
		$data = array("teamId" => $this->teamId,
						  "eventId" => $this->eventId,
						  "eventTitle" => $this->teamStatus,
						  "eventDate" => $this->commentingPermission,
						  "eventLocation" => $this->banStatus);
		if(array_key_exists($name, $data)) {
			return $data[$name];
		} else {
			throw(new InvalidArgumentException("Unable to get $name"));
		}
	}

	/**
	 * @param mixed $newTeamId team id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if event id is not positive
	 **/
	public function setTeamId($newTeamId) {
		if($this->teamId === null){
			$this->teamId = null;
			return;
		}

		if(filter_var($newTeamId, FILTER_VALIDATE_INT) === false){
			throw(new UnexpectedValueException("teamId $newTeamId is not numeric"));
		}

		$newTeamId = intval($newTeamId);
		if($newTeamId <= 0){
			throw(new RangeException("teamId $newTeamId is not positive."));
		}

		$this->teamId =$newTeamId;
	}

	/**
	 * @param mixed $newEventId event id (or null if new object)
	 * @throws UnexpectedValueException if not an integer or null
	 * @throws RangeException if event id is not positive
	 **/
	public function setEventId($newEventId){
		if($this->eventId === null){
			$this->eventId = null;
			return;
		}

		if(filter_var($newEventId,FILTER_SANITIZE_INT) === false){
			throw(new UnexpectedValueException("eventId $newEventId is not numeric."));
		}

		if($newEventId <=0){
			throw(new RangeException("eventId is not positive."));
		}

		$this->eventId = $newEventId;
	}

	public function setTeamStatus($newTeamStatus){
		if($newTeamStatus === null){
			$newTeamStatus = null;
			return;
		}

		if(filter_var($newTeamStatus, FILTER_SANITIZE_INT) === false){
			throw(new UnexpectedValueException())
		}
	}
}