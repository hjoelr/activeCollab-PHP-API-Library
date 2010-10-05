<?php

require_once '../objects/AcTicket.class.php';

$ticket = AcTicket::load('http://dev.rowleycontrols.com', '20-MEhaRilS50HcW34ldiDXmEaMPP3jcPHPjPP1X2IQ', 2, 39);

echo '<h1>Load Dump</h1>';
echo '<pre>';
var_dump($ticket);
echo '</pre>';

$ticket->setVisibility(1);
$ticket->setDueOn(null);
$ticket->setPriority(0);

$ticket->save();

//$ticket = AcTicket::create('http://dev.rowleycontrols.com', '20-MEhaRilS50HcW34ldiDXmEaMPP3jcPHPjPP1X2IQ', 2, 'This is another ticket.', 'This is the body of the new ticket.', 'test ticket, api', 0, 2, '2010-09-15', array(array(20, 22), 20), 406, 364);

echo '<h1>Save Dump</h1>';
echo '<pre>';
var_dump($ticket);
echo '</pre>';

$ticket->complete();


?>