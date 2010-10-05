<?php

require_once '../lib/Snoopy.class.php';	// This class does the communication
										// using HTTP GET and POST to the
										// activeCollab installation.

require_once '../helpers/AcHelper.class.php';

class ModelAcObject
{
	protected $acBaseURL	= null;		// The base url of the activeCollab
										// install.  Include trailing slash.
	protected $userAPIKey	= null;		// The activeCollab user API key.
	
	
	
	public function __construct($acBaseURL, $userAPIKey=null)
	{
		$this->setBaseURL($acBaseURL);
		$this->setUserAPIKey($userAPIKey);
	}
	
	public function setBaseURL($acBaseURL)
	{
		if (empty($acBaseURL)) {
			throw new Exception('Please be sure to specify the activeCollab base URL when creating the ModelAcObject.');
		}
		
		$this->acBaseURL = $acBaseURL;
	}
	
	public function setUserAPIKey($userAPIKey)
	{
		$this->userAPIKey = $userAPIKey;
	}
	
/*======================================================================*\
	Function:	delete
	Purpose:	Deletes the specified object in activeCollab.  This
				could be a ticket, project, {flush out what are possible}
	Input:		$project_id	the ID of the project from which to delete
							the object.
				$object_id	the ID of the object to delete.
	Output:		none
\*======================================================================*/
	
	public function delete($project_id, $object_id)
	{
		$snoopy = new Snoopy();
		$snoopy->accept = 'application/json';
		
		$url = AcHelper::makeApiUrl($this->acBaseURL .'/projects/'. $project_id .'/objects/'. $object_id . '/move-to-trash', $this->userAPIKey);
		
		$postParams = array('submitted' => 'submitted');
		
		return AcHelper::sendPostRequest($url, $postParams, 'json');
	}
}