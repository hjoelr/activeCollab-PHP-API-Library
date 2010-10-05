<?php

//require_once '../lib/Snoopy.class.php';
	
class AcObject
{
	protected $acBaseUrl		= null;
	protected $userApiKey		= null;

	protected $object_id		= null;
	protected $name 			= null;
	protected $body				= null;
	protected $state			= null;
	protected $visibility		= null;
	protected $created_on		= null;
	protected $created_by_id	= null;
	protected $updated_on		= null;
	protected $updated_by_id	= null;
	protected $version			= null;
	protected $permalink		= null;
	protected $project_id		= null;
	protected $parent_id		= null;
	protected $milestone_id		= null;
	protected $permissions		= null;			// An AcTicketPermission
	
	protected function __construct($acBaseUrl, $userApiKey)
	{
		$this->acBaseUrl = $acBaseUrl;
		$this->userApiKey = $userApiKey;
	}
	
	protected function populate($rawTicket)
	{
		$this->object_id = $rawTicket->id;
		$this->name = $rawTicket->name;
		$this->body = $rawTicket->body;
		$this->state = $rawTicket->state;
		$this->visibility = $rawTicket->visibility;
		$this->created_on = $rawTicket->created_on;
		$this->created_by_id = $rawTicket->created_by_id;
		$this->updated_on = $rawTicket->updated_on;
		$this->updated_by_id = $rawTicket->updated_by_id;
		$this->version = $rawTicket->version;
		$this->permalink = $rawTicket->permalink;
		$this->project_id = $rawTicket->project_id;
		$this->parent_id = $rawTicket->parent_id;
		$this->milestone_id = $rawTicket->milestone_id;
		$this->permissions = $rawTicket->permissions;
	}
	
	public function complete($acBaseUrl=null, $userAPIKey=null)
	{
		$baseUrl = $this->acBaseUrl;
		if ($acBaseUrl != null && is_string($acBaseUrl) && $acBaseUrl != "") {
			$baseUrl = $acBaseUrl;
		}
		
		$apiKey = $this->userApiKey;
		if ($userApiKey != null && is_string($userApiKey) && $userApiKey != "") {
			$apiKey = $userApiKey;
		}
		
		$rawObject = AcHelper::sendPostRequest('/projects/'. $this->project_id . '/objects/' . $this->object_id . '/complete',
									array('submitted'=>'submitted'),
									$baseUrl, $apiKey);
		
		$this->populate($rawObject);
	}
	
	public function open($acBaseUrl, $userAPIKey)
	{
	
	}
	
	public function star($acBaseUrl, $userAPIKey)
	{
	
	}
	
	public function unstar($acBaseUrl, $userAPIKey)
	{
	
	}
	
	public function subscribe($acBaseUrl, $userAPIKey)
	{
	
	}
	
	public function unsubscribe($acBaseUrl, $userAPIKey)
	{
	
	}
	
	public function moveToTrash($acBaseUrl, $userAPIKey)
	{
	
	}
	
	public function restoreFromTrash($acBaseUrl, $userAPIKey)
	{
	
	}
	
	
	// --------------- Getters ------------------
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getBody()
	{
		return $this->body;
	}
	
	public function getVisibility()
	{
		return $this->visibility;
	}
	
	
	// --------------- Setters ------------------
	
	// expects a string name; cannot be null or empty
	public function setName($name)
	{
		if (isset($name) && is_string($name) && $name != "") {
			$this->name = $name;
		} else {
			throw new Exception("setName expects a string name.  It was given the following: " . $name);
		}
	}
	
	// expects a string name; cannot be null or empty
	public function setBody($body)
	{
		if (isset($body) && is_string($body) && $body != "") {
			$this->body = $body;
		} else {
			throw new Exception("setName expects a string body.  It was given the following: " . $body);
		}
	}
	
	// expects integer 0 or 1.
	public function setVisibility($visibility)
	{
		if (isset($visibility) && is_int($visibility) && ($visibility == 0 || $visibility == 1)) {
			$this->visibility = $visibility;
		} else {
			throw new Exception("setVisibility expected a number between 0 and 1 but was given the following: " . $visibility);
		}
	}
}
	
?>