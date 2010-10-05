<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
  
require 'Snoopy.class.php';
$snoopy = new Snoopy();
$snoopy->accept = 'application/json';
$snoopy->set_submit_normal();



define("AC_CATCHALL_PROJECT_ID", "2");
define("AC_API_KEY", "20-MEhaRilS50HcW34ldiDXmEaMPP3jcPHPjPP1X2IQ");
//define("AC_API_KEY", "18-dba4wy3z9TzMtXvOIItKLeAg0AtKhbOicGFAy5x");
define("AC_URL", "http://dev.rowleycontrols.com");
 
// helper function for constructing API URL, see below
$url = makeApiUrl('/projects/'. AC_CATCHALL_PROJECT_ID .'/tickets/add');
 
//prepare the POST data for ticket
    $post_params = array(
      'submitted' => 'submitted',
      'ticket' => array(
          'name' => 'My ticket subject',
          'body' => 'My ticket body',
          'visibility' => 1,
      ),
    );
 
$snoopy->submit($url, $post_params);
 
 // on failure, print http error
if ($snoopy->status != 200) {
  echo $snoopy->headers[0];
}
// on success
else {
  $ticket = json_decode($snoopy->results);
  echo '<pre>';
  var_dump($ticket);
  echo '</pre>';
};
 
 
  /**
   * Construct activeCollab API url
   *   
   */    
  function makeApiUrl($url, $token = AC_API_KEY) {
    if (strpos($url, '?')) {
      $url .= '&';
    } else {
      $url .= '?'; 
    }   
    return AC_URL . $url . "token=" . $token;
  }
?>