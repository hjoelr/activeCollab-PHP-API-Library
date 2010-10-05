<html>
<head>
<title>Test activeCollab Object Delete</title>
</head>
<body>
<?php
require_once '../ActivecollabAPI.class.php';

$api = new ActivecollabAPI('http://dev.rowleycontrols.com', '20-MEhaRilS50HcW34ldiDXmEaMPP3jcPHPjPP1X2IQ');

$submitted = $_POST['submitted'];
$project_id = $_POST['project_id'];
$object_id = $_POST['object_id'];

if (!empty($submitted) && $submitted == 'submitted'
	&& !empty($project_id) && !empty($object_id)) {
	
	echo '<h1>Deleting {project:' . $project_id . ', object:' . $object_id . '}</h1>';
	
	$error = false;
	try {
		$return = $api->Objects->delete($project_id, $object_id);
		
		echo '<h2>Deleted object dump</h2>';
		echo '<pre>', var_dump($return), '</pre>';
	} catch (Exception $e) {
		echo '<h2>Error occurred while deleting {project:' . $project_id . ', object:' . $object_id . '}:</h2>';
		echo '<pre>', $e->getMessage(), '</pre>';
		$error = true;
	}
	
	if (!$error) {
		echo '<h2>Successfully deleted object {project:' . $project_id . ', object:' . $object_id . '}</h2>';
	}
}
?>

<h1>Test activeCollab Object Delete Form</h1>
<div>Enter the Project ID and Object ID of the object you wish to delete.</div>
<form method="post" action="TestObjectDelete.php">
<label for="project_id">Project Id:</label>
<input type="text" name="project_id" value="<?php echo $project_id; ?>" /><br />
<label for="object_id">Object Id:</label>
<input type="text" name="object_id" value="<?php echo $object_id; ?>" /><br />
<input type="submit" value="Test Values" />
<input type="hidden" name="submitted" value="submitted" />
</form>
</body>
</html>
