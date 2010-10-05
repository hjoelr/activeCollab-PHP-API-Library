<html>
<head>
<title>Test activeCollab Object Load</title>
</head>
<body>
<?php
require_once '../ActivecollabAPI.class.php';
require_once '../exceptions/HttpException.class.php';

$api = new ActivecollabAPI('http://dev.rowleycontrols.com', '20-MEhaRilS50HcW34ldiDXmEaMPP3jcPHPjPP1X2IQ');

$submitted = $_POST['submitted'];
$project_id = $_POST['project_id'];
$ticket_id = $_POST['ticket_id'];

if (!empty($submitted) && $submitted == 'submitted'
	&& !empty($project_id) && !empty($ticket_id)) {
	
	echo '<h1>Loading {project:' . $project_id . ', ticket:' . $ticket_id . '}</h1>';
	
	$return = null;
	$error = false;
	try {
		$return = $api->Tickets->load($project_id, $ticket_id);
	} catch (HttpException $e) {
		echo '<h2>Error occurred while loading {project:' . $project_id . ', ticket:' . $ticket_id . '}:</h2>';
		echo '<pre>', $e->getMessage(), '</pre>';
		$error = true;
	}
	
	if (!$error) {
		echo '<h2>Successfully loaded ticket {project:' . $project_id . ', ticket:' . $ticket_id . '}</h2>';
		echo '<pre>', var_dump($return), '</pre>';
	}
}
?>

<h1>Test activeCollab Object Load Form</h1>
<div>Enter the Project ID and Ticket ID of the ticket you wish to load.</div>
<form method="post" action="TestAcTicketLoad.php">
<label for="project_id">Project Id:</label>
<input type="text" name="project_id" value="<?php echo $project_id; ?>" /><br />
<label for="ticket_id">Ticket Id:</label>
<input type="text" name="ticket_id" value="<?php echo $ticket_id; ?>" /><br />
<input type="submit" value="Test Values" />
<input type="hidden" name="submitted" value="submitted" />
</form>
</body>
</html>
