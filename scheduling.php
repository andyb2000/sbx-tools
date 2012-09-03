<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");

header_display();
body_display();
menu_display();
?>
<table width=100% border=1 cellpadding=0 cellspacing=0>
<tr><td width=100%>
<u>Scheduling</u><BR><BR>
<center><a href='scheduling.php?action=programs'>Programming Setup</a>&nbsp;|&nbsp;<!-- <a href='scheduling.php?action=musicschedule'>Music Scheduling</a> -->
&nbsp;|&nbsp;<a href='scheduling.php?action=editrunorder'>View Saved Runorder</a>
</center><BR><BR>

<?php
$action=$_REQUEST["action"];
switch ($action) {
        case "programs":
		include_once("scheduling_programs.php");
		break;
        case "advertschedule":
		include_once("scheduling_adverts.php");
		break;
	case "musicschedule":
		include_once("scheduling_music.php");
		break;
	case "editrunorder":
		include_once("scheduling_editrunorder.php");
		break;
};
?>

</td></tr>
</table>
<?php
footer_display();
?>
