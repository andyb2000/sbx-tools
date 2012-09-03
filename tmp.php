<?php
include("functions.php");

	$starthour=0;
        $tmphour=$starthour;
        while ($tmphour < 24) {
                $tmphour++;
$dosql=mysql_query("insert into shows values('','','mon_$tmphour')");
$dosql=mysql_query("insert into shows values('','','tues_$tmphour')");
$dosql=mysql_query("insert into shows values('','','wed_$tmphour')");
$dosql=mysql_query("insert into shows values('','','thurs_$tmphour')");
$dosql=mysql_query("insert into shows values('','','fri_$tmphour')");
$dosql=mysql_query("insert into shows values('','','sat_$tmphour')");
$dosql=mysql_query("insert into shows values('','','sun_$tmphour')");
	};
