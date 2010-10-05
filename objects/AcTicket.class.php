<?php

require_once 'AcObject.class.php';
require_once '../helpers/AcHelper.class.php';
require_once '../lib/Snoopy.class.php';

class AcTicket extends AcObject
{
	
	private $ticket_id			= null;
	private $priority			= 0;
	private $due_on				= null;
	private $completed_on		= null;
	private $completed_by_id	= null;
	private $tags				= array();		// Array of AcTags
	private $comments			= array();		// Array of AcComments
	private $tasks				= array();		// Array of AcTasks
	private $attachments		= array();		// Array of AcAttachments
	private $assignees			= array();		// Array of AcAssignees

	// prevent instantiation from the outside
	protected function __construct($acBaseUrl, $userApiKey, $rawTicket)
	{
		parent::__construct($acBaseUrl, $userApiKey);
		
		$this->populate($rawTicket);
	}
	
	// populates the values in this ticket with the ones from the specified
	// ticket that is the result of json_decode($snoopy->results).
	protected function populate($rawTicket)
	{
		parent::populate($rawTicket);
		
		if (isset($rawTicket->ticket_id)) {
			$this->setTicketId($rawTicket->ticket_id);
		}
		if (isset($rawTicket->priority)) {
			$this->setPriority($rawTicket->priority);
		}
		if (isset($rawTicket->due_on)) {
			$this->setDueOn($rawTicket->due_on);
		}
		if (isset($rawTicket->completed_on)) {
			$this->setCompletedOn($rawTicket->completed_on);
		}
		if (isset($rawTicket->completed_by_id)) {
			$this->setCompletedById($rawTicket->completed_by_id);
		}
		if (isset($rawTicket->tags)) {
			$this->setTags($rawTicket->tags);
		}
		if (isset($rawTicket->comments)) {
			$this->setComments($rawTicket->comments);
		}
		if (isset($rawTicket->tasks)) {
			$this->setTasks($rawTicket->tasks);
		}
		if (isset($rawTicket->attachments)) {
			$this->setAttachments($rawTicket->attachments);
		}
		
		echo '<h2>Populate</h2>';
		echo '<pre>';
		var_dump($rawTicket->assignees);
		echo '</pre>';
		
		if (isset($rawTicket->assignees) && is_array($rawTicket->assignees)) {
			$assigneeIds = array();
			$owner = null;
			foreach ($rawTicket->assignees as $assignee) {
				$assigneeIds[] = $assignee->user_id;
				
				if ($assignee->is_owner) {
					$owner = $assignee->user_id;
				}
			}
			$this->setAssignees(array($assigneeIds, $owner));
		}
	}
	
	public static function load($acBaseUrl, $userApiKey, $project_id, $ticket_id)
	{
		$snoopy = new Snoopy();
		$snoopy->accept = 'application/json';
		
		$url = AcHelper::makeApiUrl($acBaseUrl .'/projects/'. $project_id .'/tickets/'. $ticket_id, $userApiKey);
		
		$snoopy->fetch($url);
 
		// on failure, throw an exception
		if ($snoopy->status != 200) {
			throw new Exception($snoopy->headers[0]);
		}
		
		$rawTicket = json_decode($snoopy->results);
			
		$acTicket = new AcTicket($acBaseUrl, $userApiKey, $rawTicket);
		
		return $acTicket;
	}
	
	public static function create($acBaseUrl, $userApiKey, $project_id, $name, $body, 
									$tags=null, $visibility=1, $priority=0, 
									$due_on=null, $assignees=null, 
									$milestone_id=null, $parent_id=null)
	{
		
		$post_params = AcTicket::createTicketPostArray($name, $body, $tags, 
									$visibility, $priority, $due_on, 
									$assignees, $milestone_id, $parent_id);
	
		$rawTicket = AcHelper::sendPostRequest('/projects/'. $project_id .'/tickets/add',
											$post_params, $acBaseUrl, $userApiKey);
			
		//$acTicket = new AcTicket($acBaseUrl, $userApiKey, $rawTicket);
		
		$acTicket = AcTicket::load($acBaseUrl, $userApiKey, $project_id, $rawTicket->ticket_id);
		
		return $acTicket;
	}
	
	// save changes to the ticket or create a new ticket
	public function save($acBaseUrl=null, $userApiKey=null)
	{
		$tagsComma = "";
		for ($i=0; $i < count($this->tags); ++$i) {
			$tagsComma .= ($i != 0) ? ', ' : '';
			$tagsComma .= $this->tags[$i];
		}
		
		$post_params = AcTicket::createTicketPostArray(
				$this->name, $this->body, $tagsComma, $this->visibility,
				$this->priority, $this->due_on, $this->assignees,
				$this->milestone_id, $this->parrent_id);
		
		$baseUrl = $this->acBaseUrl;
		if ($acBaseUrl != null && is_string($acBaseUrl) && $acBaseUrl != "") {
			$baseUrl = $acBaseUrl;
		}
		
		$apiKey = $this->userApiKey;
		if ($userApiKey != null && is_string($userApiKey) && $userApiKey != "") {
			$apiKey = $userApiKey;
		}
		
		$rawTicket = AcHelper::sendPostRequest('/projects/' . $this->project_id . '/tickets/' . $this->ticket_id . '/edit',
									$post_params, $baseUrl, $apiKey);
		
		$this->populate($rawTicket);
		
		echo '<h2>Save</h2>';
		echo '<pre>';
		//var_dump($post_params);
		//var_dump($rawTicket);
		var_dump($this);
		echo '</pre>';
	}
	
	// helper function for populating the post parameters
	private static function createTicketPostArray($name, $body, 
									$tags, $visibility, $priority, 
									$due_on, $assignees, 
									$milestone_id, $parent_id)
	{
	
		$ticketArray = array();
		
		if ($name != null && is_string($name)) {
			$ticketArray['name'] = $name;
		}
		if ($body != null && is_string($body)) {
			$ticketArray['body'] = $body;
		}
		if ($tags != null && is_string($tags)) {
			$ticketArray['tags'] = $tags;
		}
		if ($visibility != null && is_int($visibility)
			&& ($visibility == 1 || $visibility == 0)) {
			$ticketArray['visibility'] = $visibility;
		} else {
			// default to normal visibility
			$ticketArray['visibility'] = 1;
		}
		if ($priority != null && is_int($priority) && $priority >= -2
			&& $priority <= 2) {
			$ticketArray['priority'] = $priority;
		}
		// eg. 2010-09-15
		if ($due_on != null && preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/i', $due_on)) {
			$ticketArray['due_on'] = $due_on;
		}
		if ($assignees != null && is_array($assignees)
			&& count($assignees) == 2) {
			
			$ticketArray['assignees'] = $assignees;
		}
		if ($milestone_id != null && is_int($milestone_id)) {
			$ticketArray['milestone_id'] = $milestone_id;
		}
		if ($parent_id != null && is_int(parent_id)) {
			$ticketArray['parent_id'] = $parent_id;
		}
		
		$post_params = array(
			'submitted' => 'submitted',
			'ticket' => $ticketArray
		);
		
		return $post_params;
	}

	// --------------- Getters ----------------
	
	// returns an integer between -2 and 2
	public function getPriority()
	{
		return $this->priority;
	}
	
	// returns string in format YYYY-MM-DD
	public function getDueOn()
	{
		return $this->due_on;
	}
	
	// returns an array of tags
	public function getTags()
	{
		return $this->tags;
	}
	
	// returns an array in the following format: array(array(int [, int, ...]), int);
	public function getAssignees()
	{
		return $this->assignees;
	}
	
	public function getTicketId()
	{
		return $this->ticket_id;
	}
	
	// -------------- Setters -------------------
	
	public function setPriority($priority)
	{
		if (isset($priority) && is_int($priority) && $priority >= -2 && $priority <= 2) {
			$this->priority = $priority;
		} else {
			throw new Exception("setPriority expected a priority number between -2 and 2 but was given the following: " . $priority);
		}
	}
	
	// string in date format 2010-09-15
	public function setDueOn($due_on)
	{
		if (isset($due_on) && preg_match('/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/i', $due_on)) {
			$this->due_on = $due_on;
		} elseif ($due_on == null) {
			$this->due_on = null;
		} else {
			throw new Exception("setDueOn expected a date in the format YYYY-MM-DD but was given the following: " . $due_on);
		}
	}
	
	// expects an array of tags
	public function setTags($tags)
	{
		if (isset($tags) && is_array($tags)) {
			$this->tags = $tags;
		} elseif ($tags == null) {
			$this->tags = null;
		} else {
			throw new Exception("setTags expected an array of tags and was given something else {" . $tags . "}");
		}
	}
	
	// expects an array in this format: array(array(int [, int, ...]), int)
	public function setAssignees($assignees)
	{
		if (isset($assignees) && is_array($assignees) && count($assignees) == 2
			&& is_array($assignees[0]) && is_int($assignees[1])) {
			
			$foundOwner = false;
			$valid = true;
			foreach ($assignees[0] as $assignee) {
				if (isset($assignee) && is_int($assignee)) {
					if ($assignees[1] == $assignee) {
						$foundOwner = true;
					}
				} else {
					$valid = false;
				}
			}
			
			if ($foundOwner && $valid) {
				$this->assignees = $assignees;
			} else {
				throw new Exception("setAssignees expected an array in this format: array(array(int [, int, ...]), int).  Instead it got this: " . $assignees);
			}
		} elseif ($assignees == null) {
			$this->assignees = null;
		} else {
			throw new Exception("setAssignees expected an array in this format: array(array(int [, int, ...]), int).  Instead it got this: " . $assignees);
		}
	}
	
	private function setTicketId($ticket_id)
	{
		$this->ticket_id = $ticket_id;
	}
	
	private function setCompletedOn($completed_on)
	{
		$this->completed_on = $completed_on;
	}
	
	private function setCompletedById($completed_by_id)
	{
		$this->completed_by_id = $completed_by_id;
	}
	
	private function setComments($comments)
	{
		$this->comments = $comments;
	}
	
	private function setTasks($tasks)
	{
		$this->tasks = $tasks;
	}
	
	private function setAttachments($attachments)
	{
		$this->attachments = $attachments;
	}
	
}

?>