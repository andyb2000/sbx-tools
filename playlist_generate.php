#!/usr/bin/php
<?php

//      sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------


//	This will generate a playlist for 1hr based on randomly picking from categories and checking the
//	songs havent been played over the past 24hrs
//	If there arent enough songs in the category to do 24hrs then it uses least played, or oldest

$script=1;	// set script to be 1 which bypasses the login procedures

include_once("functions.php");

$todaydate=date("Ymd");
get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();

// the sequence we use is this


	// check if it already exists, as thats tidy
	$check_before=mysql_query("select id from playlog where cartindex='$cartindex' and lastplayed='$lastplayed' and sbxcomputer='$sbxcomputer'");

?>
