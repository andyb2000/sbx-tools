<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");
setcookie ("sbschedule", "", time() - 3600);
header("Location: index.php");
exit;
?>
