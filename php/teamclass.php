<?php
class Team{
	private $teamId;
	/** Team  name */
	private $teamName;
	/** Team description **/
	private $teamCause;
}
	public function __construct($newTeamId, $newTeamName, $newTeamCause) {
		try {
			$this->setTeamId($newTeamId);
			$this->setTeamName($newTeamName);
			$this->setTeamCause($newTeamCause);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Team", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Team", 0, $range));
		}
	}
	public function getTeamId() {
		return($this->teamId);
	}
	public function setTeamId($newTeamId) {
			if($newTeamId === null) {
			$this->teamId = null;
			return;
		}
		if(filter_var($newTeamId, FILTER_VALIDATE_INT) === false) {
			throw(new UnexpectedValueException("team id $newTeamId is not numeric"));
		}
		$newTeamId = intval($newTeamId);
		if($newTeamId <= 0) {
			throw(new RangeException("team Id $newTeamId is not positive"));
		}
		$this->yeamd = $newTeamId;
	}


/**
 * Created by PhpStorm.
 * User: Cass
 * Date: 11/7/2014
 * Time: 11:02 AM
 */ 