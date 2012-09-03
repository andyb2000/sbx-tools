#!/usr/bin/php
<?php

//      sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------


//	This script should run only once a day as it will import everything from the playlog on soundbox into our own
//	playlog database.

$script=1;	// set script to be 1 which bypasses the login procedures

include_once("functions.php");

$todaydate=date("Ymd");
// $todaydate=date('Ymd', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));	// yesterdays date
// $yesterday=date("d/m/Y", time()-86400);
$db3lines=exec("/usr/bin/dbview /mnt/soundbox/Data/PlayLog/$todaydate.dbf",$db3lines_array);
$runcount=0;
$playlog=array();
echo "Reading dbf loop ($todaydate.dbf)\n";
foreach($db3lines_array as $db3_var) {
        if ($db3_var != "") {
                // list($in_key,$in_val)=split(" :",$db3_var);
		list($in_key,$in_val)=explode(" :",$db3_var);
                $in_key=trim($in_key);
                $in_val=trim($in_val);
                        $playlog[$runcount][$in_key]=$in_val;
        } else {
                $runcount++;
        };
};
echo "back from dbf loop\n";

echo "Into array loop\n";
foreach ($playlog as $playlog_key => $playlog_value) {
	$startdate=$playlog_value['Startdate'];
	$starttime=$playlog_value['Starttime'];
        $hr_starttime=substr($playlog_value['Starttime'],0,2);
        $hr_endtime=substr($playlog_value['Endtime'],0,2);
        $sbxcomputer=$playlog_value['Compindex'];
	$catalogue=$playlog_value['Catalogue'];
	$filename=$playlog_value['Filename'];
	$filelocation=$playlog_value['FileLocation'];
	$title=$playlog_value['Title'];
	$cartindex=$playlog_value['Cartindex'];
	$lastplayed_date=$playlog_value['Enddate'];
	$lastplayed_time=$playlog_value['Endtime'];
	$last_played_date_yr=substr($lastplayed_date,0,4);
	$last_played_date_mn=substr($lastplayed_date,4,2);
	$last_played_date_dy=substr($lastplayed_date,6,2);
	$last_played_date=$last_played_date_yr."-".$last_played_date_mn."-".$last_played_date_dy;
	$lastplayed=$last_played_date." ".$lastplayed_time;

	// check if it already exists, as thats tidy
	$check_before=mysql_query("select id from playlog where cartindex='$cartindex' and lastplayed='$lastplayed' and sbxcomputer='$sbxcomputer'");
	if (mysql_num_rows($check_before) <> 0) {
		echo "Row already exists for this entry...Skipping\n";
	} else {
		$sql_ins="insert into playlog values('','$cartindex','$lastplayed','$sbxcomputer')";
		$do_sql=mysql_query($sql_ins) or die("Failed with sql insert: ".mysql_error());
		echo "$sql_ins\n";
		echo "-----\n";
	};
	echo "check  select id from sbxlog where cartindex='$cartindex' and lastplayed='$lastplayed' and sbxcomputer='$sbxcomputer'\n";
	$check_before=mysql_query("select id from sbxlog where cartindex='$cartindex' and lastplayed='$lastplayed' and sbxcomputer='$sbxcomputer'");
	if (mysql_num_rows($check_before) <> 0) {
		echo "Row already in sbxlog table..Skipping\n";
	} else {
		echo "insert into sbxlog values('','$cartindex','$startdate')\n";
		$ins=mysql_query("insert into sbxlog values('','$cartindex','$startdate','$starttime','$lastplayed_date','$lastplayed_time','$catalogue','$filename','$filelocation','$title','$filelocation_description','$filelocation_directory','$cataloguename','$lastplayed','$sbxcomputer')") or die("error doing insert to sbxlog ".mysql_error());
	};
};
echo "end array loop\n";
?>
