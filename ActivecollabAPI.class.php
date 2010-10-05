<?php

/*******************************************************

ActivecollabAPI is a PHP library that simplifies the
task of communicating with an activeCollab installation
using the activeCollab API.

Author: Joel Rowley
Copyright (c): 2010 Rowley Controls, all rights reserved
Version: 0.0.1

********************************************************/

require_once 'Snoopy.class.php';	// This class does the communication
									// using HTTP GET and POST to the
									// activeCollab installation.

require_once 'objects/AcTicket.class.php';


class ActivecollabAPI
{

	var $acBaseURL	= ""			// The base url of the activeCollab
									// install.  Include trailing slash.
	var $userAPIKey	= "";			// The activeCollab user API key.
	
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
	
	public function __construct($acBaseURL="", $userAPIKey="")
	{
		$this->setBaseURL($acBaseURL);
		$this->setUserAPIKey($userAPIKey);
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
		$this->acBaseURL = $acBaseURL;
	}
	
/*======================================================================*\
	Function:	setUserAPIKey
	Purpose:	Set the user API key.
	Input:		$userAPIKey	the API key that the class should use.
	Output:		none
\*======================================================================*/

	public function setUserAPIKey($userAPIKey)
	{
		$this->userAPIKey = $userAPIKey;
	}
}

?>