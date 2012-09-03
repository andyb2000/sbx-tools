<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("../functions.php");

if(!$pxdoc = px_new()) {
	echo "Failed to load paradox driver, contact support!<BR>\n";
	die();
}
$todaydate=date("Ymd");
$db = dbase_open('/var/www/sbschedule/local_soundbox/PlayLog/$todaydate.dbf', 0);
if ($db) {
  dbase_close($db);
} else {
	echo "fail<BR>\n";
};

?>
