<?php
/**
 * Created by PhpStorm.
 * User: Cass
 * Date: 11/7/2014
 * Time: 11:03 AM
 */
class UserCause
{
	/** @var  find profile Id */
	private $profileId;
	/** @var  cause Id */
	private $causeId;

	/**
	 * constructor for UserCause
	 *
	 * @param string $newProfile
	 * @param string $newCause
	 * @throws UnexpectedValueException when a parameter is of the wrong type
	 * @throws RangeException when a parameter is invalid
	 **/

	public function __construct($newProductId, $newProductName, $newDescription, $newPrice)
	{
		try {
			$this->setProfileId($newProfileId);
			$this->setCauseId($newcauseId);
		} catch(UnexpectedValueException $unexpectedValue) {
			// rethrow to the caller
			throw(new UnexpectedValueException("Unable to construct Product", 0, $unexpectedValue));
		} catch(RangeException $range) {
			// rethrow to the caller
			throw(new RangeException("Unable to construct Product", 0, $range));
		}
	}
}
