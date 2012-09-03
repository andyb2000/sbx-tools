<?php

//	Functions for sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

//  body for html generation

function body_display() {
?>
<body>
<center><table width=100% border=1 cellpadding=0 cellspacing=0><tr><td><h2>SB-Schedule+</h2>
</td></tr>
</table>
</center>
<BR>
<?php
};

function menu_display() {
?>
<table width=100% border=1 align=center>
<tr><td width=100%>
	<table width=100% border=0 cellpadding=0 cellspacing=2>
	<tr>
		<td><a href='index.php'>Main Status Page</a></td>
<!--		<td>&nbsp;**&nbsp;</td>
		<td><a href='clients.php'>Client Administration</a></td> -->
		<td>&nbsp;**&nbsp;</td>
		<td><a href='advertcarts.php'>Advert Carts</a></td>
		<td>&nbsp;**&nbsp;</td>
		<td><a href='advertcollections.php'>Advert Collections</a></td>
		<td>&nbsp;**&nbsp;</td>
		<td><a href='scheduling.php'>Scheduling</a></td>
<!--		<td>&nbsp;**&nbsp;</td>
		<td><a href='music.php'>Music Scheduling</a></td> -->
		<td>&nbsp;**&nbsp;</td>
		<td><a href='sbxlog.php'>SoundBox Play Logs</a></td>
		<td>&nbsp;**&nbsp;</td>
		<td><a href='sbxlog_topsongs.php'>Top Songs</a></td>
<!--		<td>&nbsp;**&nbsp;</td>
		<td><a href='sbxprocess.php'>SoundBox Processing</a></td> -->
<!--		<td>&nbsp;**&nbsp;</td>
		<td><a href='admin.php'>Administration</a></td> -->
		<td>&nbsp;**&nbsp;</td>
		<td><a href='logout.php'>Logout</a></td>
	</td></tr>
	</table>
</td></tr>
</table>
<?php
};
?>
