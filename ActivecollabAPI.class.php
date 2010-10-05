<?php

/*******************************************************

ActivecollabAPI is a PHP library that simplifies the
task of communicating with an activeCollab installation
using the activeCollab API.

Author: Joel Rowley
Copyright (c): 2010 Rowley Controls, all rights reserved
Version: 0.0.1

********************************************************/

//require_once 'lib/Snoopy.class.php';	// This class does the communication
										// using HTTP GET and POST to the
										// activeCollab installation.

//require_once 'objects/AcTicket.class.php';

//require_once 'exceptions/HttpException.class.php';

require_once 'models/ModelAcObject.class.php';
require_once 'models/ModelAcTicket.class.php';



class ActivecollabAPI
{
	var $Objects = null;		// ModelAcObject instance used for
							// manipulating objects in the activeCollab
							// install.
	
	var $Tickets = null;	// ModelAcTicket object used for manipulating
							// tickets in the activeCollab install.
	
	
	
/*======================================================================*\
	Function:	Constructor
	Purpose:	initialize the ActivecollabAPI class
				with the default values.
	Input:		$acBaseURL	the base URL of the activeCollab
							installation.  This should include the
							trailing slash.
				$userAPIKey	the API key that the class should use.
	Output:		ActivecollabAPI object
\*======================================================================*/
	
	public function __construct($acBaseURL, $userAPIKey=null)
	{
		$this->Objects = new ModelAcObject($acBaseURL, $userAPIKey);
		$this->Tickets = new ModelAcTicket($acBaseURL, $userAPIKey);
	}
	
/*======================================================================*\
	Function:	setBaseURL
	Purpose:	Set the base URL of the activeCollab installation.
	Input:		$acBaseURL	the base URL of the activeCollab
							installation.  This should include the
							trailing slash.
	Output:		none
\*======================================================================*/

	public function setBaseURL($acBaseURL)
	{
		$this->Objects->setBaseURL($acBaseURL);
		$this->Tickets->setBaseURL($acBaseURL);
	}
	
/*======================================================================*\
	Function:	setUserAPIKey
	Purpose:	Set the user API key.
	Input:		$userAPIKey	the API key that the class should use.
	Output:		none
\*======================================================================*/

	public function setUserAPIKey($userAPIKey)
	{
		$this->Objects->setUserAPIKey($userAPIKey);
		$this->Tickets->setUserAPIKey($userAPIKey);
	}
}

?>