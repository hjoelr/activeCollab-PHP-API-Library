<?php

require_once '../lib/Snoopy.class.php';	// This class does the communication
										// using HTTP GET and POST to the
										// activeCollab installation.

require_once '../helpers/AcHelper.class.php';
require_once '../objects/AcTicket.class.php';

//require_once '../exceptions/HttpException.class.php';

class ModelAcTicket extends ModelAcObject
{
	
	public function __construct($acBaseURL, $userAPIKey=null)
	{
		parent::__construct($acBaseURL, $userAPIKey);
	}
	
/*======================================================================*\
	Function:	load
	Purpose:	Loads a specified ticket from activeCollab.
	Input:		$project_id	the ID of the project to load the ticket
							from.
				$ticket_id	the ID of the actual ticket to load.
	Output:		AcTicket object updated with information from
				activeCollab.
\*======================================================================*/
	
	public function load($project_id, $ticket_id)
	{
		$snoopy = new Snoopy();
		$snoopy->accept = 'application/json';
		
		$url = AcHelper::makeApiUrl($this->acBaseURL .'/projects/'. $project_id .'/tickets/'. $ticket_id, $this->userAPIKey);
		
		$snoopy->fetch($url);
 
		// on failure, throw an exception
		if ($snoopy->status != 200) {
			throw new HttpException($snoopy->results, $snoopy->status);
		}
		
		$rawTicket = json_decode($snoopy->results);
		
		echo '<pre>', var_dump($rawTicket), '</pre>';
			
		$acTicket = new AcTicket($rawTicket);
		
		return $acTicket;
	}
	
/*======================================================================*\
	Function:	update
	Purpose:	Create a new ticket in activeCollab if the ticket_id is
				empty in $acTicket.  If the ticket_id is set in
				$acTicket, then this function will update the existing
				ticket with the new values in this ticket.
	Require:	$acTicket->project_id is set to a valid project.
	Input:		$acTicket	a pre-populated AcTicket object to be
							created on ActiveCollab.
	Output:		AcTicket object updated with information from
				activeCollab after the ticket has been created.
\*======================================================================*/
	
	public function update($acTicket)
	{
		
	}
}