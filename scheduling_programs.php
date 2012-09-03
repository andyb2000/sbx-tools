<?php

//      sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");

if ($_REQUEST["subaction"]) {
	if ($_REQUEST["subaction"] == "Save") {
		$showname=$_REQUEST["showname"];
	} else {
		$showname="";
	};
//	$check_exist=mysql_query("select * from shows where showname like '$showname'");
//	if (mysql_num_rows($check_exist) <> 0) {
//	};
	$starthour=0;
	$tmphour=$starthour;
	while ($tmphour < 24) {
		$tmphour++;
		if ($_REQUEST["mon_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='mon_$tmphour'");
		};
		if ($_REQUEST["tues_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='tues_$tmphour'");
		};
		if ($_REQUEST["wed_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='wed_$tmphour'");
		};
		if ($_REQUEST["thurs_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='thurs_$tmphour'");
		};
		if ($_REQUEST["fri_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='fri_$tmphour'");
		};
		if ($_REQUEST["sat_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='sat_$tmphour'");
		};
		if ($_REQUEST["sun_$tmphour"] == "1") {
			$dosql=mysql_query("update shows set showname='$showname' where timeschedule='sun_$tmphour'");
		};
	};
};

if ($_REQUEST["sbresync"]) {
	
};
?>
<form method=post name='programform'>
<input type=hidden name='action' value='<?=$_REQUEST["action"]?>'>
<center><b>Program Scheduling</b></center><BR>
<BR><BR>
<table width=95% border=1 cellpadding=0 cellspacing=1>
<tr><td width=80>&nbsp;</td><th>Monday</td><th>Tuesday</td><th>Wednesday</td><th>Thursday</td><th>Friday</td><th>Saturday</td><th>Sunday</td></tr>
<?php
$starthour=0;
$tmphour=$starthour;
$alternator="#333333";
while ($tmphour < 24) {
	$tmphour++;
	$lasthour=$tmphour-1;
	echo "<tr bgcolor=$alternator><td align=right width=80>$lasthour:00-$tmphour:00</td>";
	$check_mon=querydb("select * from shows where timeschedule='mon_$tmphour'");
	echo "<td><input type=checkbox name='mon_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td>";
	$check_mon=querydb("select * from shows where timeschedule='tues_$tmphour'");
	echo "<td><input type=checkbox name='tues_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td>";
	$check_mon=querydb("select * from shows where timeschedule='wed_$tmphour'");
	echo "<td><input type=checkbox name='wed_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td>";
	$check_mon=querydb("select * from shows where timeschedule='thurs_$tmphour'");
	echo "<td><input type=checkbox name='thurs_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td>";
	$check_mon=querydb("select * from shows where timeschedule='fri_$tmphour'");
	echo "<td><input type=checkbox name='fri_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td>";
	$check_mon=querydb("select * from shows where timeschedule='sat_$tmphour'");
	echo "<td><input type=checkbox name='sat_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td>";
	$check_mon=querydb("select * from shows where timeschedule='sun_$tmphour'");
	echo "<td><input type=checkbox name='sun_$tmphour' value=1 onclick=\"if ('".$check_mon["showname"]."' != '') {document.forms[0].showname.value='".$check_mon["showname"]."';};\">&nbsp;".$check_mon["showname"]."</td></tr>\n";
	if ($alternator == "#333333") {$alternator="#666666";} else {$alternator="#333333";};
};
?>
</table><BR>
Type in a showname to assign to the ticked boxes: <input type=text name='showname'>&nbsp;<input type=submit name='subaction' value='Save'><BR>
OR<BR>
Click DELETE to clear the shows for the ticked boxes: <input type=submit name='subaction' value='Delete'>
<BR>
</form>
