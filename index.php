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
<u>Main Status Page</u><BR><BR>
Current status: Operational<BR>
<?php
//    echo "Last Soundbox sync: " . date ("H:i:s d/m/Y", filemtime("local_soundbox/touchfile.txt"))."<BR>";
?>
Studio1 status: <font color=green>Online</font><BR>
Studio2 status: <font color=red>Online</font><BR>
Server status: <font color=green>Online</font><BR>

</td></tr>
</table>
<BR><BR>
<?php
footer_display();
?>
