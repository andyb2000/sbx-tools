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
<u>Radio Hartlepool - Top of the Requests Charts!</u><BR>
<?php

if(!$pxdoc = px_new()) {
	echo "Failed to load paradox driver, contact support!<BR>\n";
	die();
}

// get the db3 output from the cli

if ($_REQUEST["calendar"]) {
	$todaydate=$_REQUEST["calendar"];
} else {
	$todaydate=date("Ymd");
};

$mysql_date_yr=substr($todaydate,0,4);
$mysql_date_mn=substr($todaydate,4,2);
$mysql_date_dy=substr($todaydate,6,2);
$mysql_date=$mysql_date_yr."-".$mysql_date_mn."-".$mysql_date_dy;

// which machine? studio1 or studio2
$studioselect=$_REQUEST["studioselect"];
$filtermusic=$_REQUEST["filtermusic"];

$filtermusic=1;
?>
<center>
<form>
<table border=0 cellpading=2>
<?php
echo "<tr><td>Select a studio:</td><td><select name='studioselect'>";
if (!$studioselect) {$studioselect=3;};
echo "<option value='3' ";
if ($studioselect == "3") {echo "selected";};
echo ">Studio 1</option>\n";
echo "<option value='1' ";
if ($studioselect == "1") {echo "selected";};
echo ">Studio 2</option>\n";
echo "<option value='2' ";
if ($studioselect == "2") {echo "selected";};
echo ">ComProd</option>\n";
echo "</select></td></tr>";

$fromtime=$_REQUEST["fromtime"];
$totime=$_REQUEST["totime"];

if (!$fromtime) {$fromtime="00";};
if (!$totime) {$totime="23";};

$zoomcartid=$_REQUEST["zoomcartid"];

function StartOfWeek($year, $week)
{
    $Jan1 = mktime(1,1,1,1,1,$year);
    $MondayOffset = (11-date('w',$Jan1))%7-3;
    $desiredMonday = strtotime(($week-1) . ' weeks '.$MondayOffset.' days', $Jan1);
    return $desiredMonday;
} 

$start_of_week=StartOfWeek(date("Y"),date("W"));
        $the_start_date=date("Y-m-d",$start_of_week);
	// $the_start_date="2010-03-01";
        // $the_end_date=date("Y-m-d",strtotime(date("Y-m-d", strtotime($the_start_date)) . " +7 days"));
	$the_end_date=date("Y-m-d");
        $fromtime="12";
        $totime="13";
        echo "DEBUG: $the_start_date to $the_end_date  (from time $fromtime to $totime)<BR>\n";

if ($zoomcartid) {
	$query_build="select id,cartindex,lastplayed from sbxlog where cartindex='$zoomcartid' and sbxcomputer='$studioselect' and
date(startdate) between '$the_start_date' and '$the_end_date'
and
hour(starttime) >= $fromtime and hour(starttime) < $totime
and timediff(endtime,starttime) > '00:01:00'
order by lastplayed";
	 $load_mysql=mysql_query($query_build) or die("failed to query playlog ".mysql_error());
	echo "QUERY: $query_build<BR>\n";
} else {
	$query_build="select id,cartindex,lastplayed, count(*) from sbxlog where sbxcomputer='$studioselect' and 
date(startdate) between '$the_start_date' and '$the_end_date'
and
hour(starttime) >= $fromtime and hour(starttime) < $totime 
and timediff(endtime,starttime) > '00:01:00'
group by cartindex order by count(*) desc limit 20";
	$load_mysql=mysql_query($query_build) or die("failed to query playlog ".mysql_error());
	echo "QUERY: $query_build<BR>\n";
};

// select * from sbxlog where startdate='$mysql_date' and hour(starttime) between $fromtime and $totime and sbxcomputer='$studioselect' 
// order by startdate,starttime,enddate,endtime") or die("Failed to query sbxlog ".mysql_error());

?>
<tr><td>Select date:</td><td>
<input type="text" id="calendar" name="calendar" value="<?=$_REQUEST["calendar"]?>">
    <button id="trigger">...</button>
    <script type="text/javascript">//<![CDATA[
      Zapatec.Calendar.setup({
        firstDay          : 1,
        inputField        : "calendar",
        button            : "trigger",
        ifFormat          : "%Y%m%d",
        daFormat          : "%Y%m%d"
      });
    //]]></script>
</td></tr>
<tr><td>Select time period</td><td>
From (Hour only):<input type=text name='fromtime' size=3 value='<?=$fromtime?>'>&nbsp;&nbsp;To (Hour only):<input type=text name='totime' size=3 value='<?=$totime?>'>
</td></tr>
<tr><td>Filter music only:</td><td><input type=checkbox name='filtermusic' value='1' <?php if ($_REQUEST["filtermusic"]) {echo "checked";}; ?>></td></tr>
<tr><td>Refresh after changes:</td><td><input type=submit name=refreshbutton value="Refresh"></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
</table>
<?php
echo "</form></center><BR>\n";

get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();

// print_r($sbx_cartarray);
//[28] => Array
//        (
//            [CartIndex] => 13488
 //           [BPM] => 
//            [Catalogue] => 59
//            [FadeTime] => 
//            [Filename] => David Bowie - Starman.mp3
//            [FileLocation] => 28
//            [Flags] => 
 //           [Energy] => 
//            [Extro] => 0
//            [ExtIndex] => 
//            [HWControl] => 
//            [Intro] => 0
//            [IOControl] => 0
//            [Length] => 244312
//            [Notes] => 
//            [SBxAudio] => 
//            [Start] => 0
//            [Sway] => 
//            [Tempo] => 
//            [Title] => STARMAN
//            [UserIndex] => 
//           [Volume] => 100
//        )
// $keyfind=array_search("13488",$sbx_cartarray);
//$foundcart=lookupcartindex("13584");
//print_r($sbx_cartarray[$foundcart]);
//foreach ($sbx_cartarray as $indid => $indarray) {
//	//print_r($indarray);
//	if ($indarray['CartIndex'] == "13434") {
//		echo "FOUND entry:<BR>\n";
//		print_r($indarray);
//	};
//};
// print_r($sbx_cartarray);
echo "</PRE>\n";
$musiclist=array();

$sbx_output="[Settings]\n\n[Clock]\n";
$sbx_output.="C,\$00009D98\n";
$sbx_output_counter=1;
?>
<center>
<table border=1 cellpadding=0 cellspacing=0 width=85%>
<tr>
<!-- <th>Version</th> -->
id,cartindex,lastplayed, count(*)

<th>CartIndex</th>
<th>Last Played</th>
<th>Number of times played</th>
<th>Mark Played</th>
<th>Title</th>
<th>Artist</th>
<th>Catalogue</th>

<?php
while ($fetch_playlog=mysql_fetch_array($load_mysql)) {
//	if ($fetch_playlog['sbxcomputer'] == $studioselect) {
	$foundcart=lookupcartindex($fetch_playlog['cartindex']);
        $catalogue_id=cataloguelookup($sbx_cartarray[$foundcart]['CartIndex']);
        $catalogue_nameout=$sbx_cataloguearray[$catalogue_id]['Description'];

	if ($filtermusic) {
			if ($catalogue_id == 7 || $catalogue_id == 8 || $catalogue_id == 103 || $catalogue_id == 17 || $catalogue_id == 21 || $catalogue_id == 116 || $catalogue_id == 94 || $catalogue_id == 69 || $catalogue_id == 38 || $catalogue_id == 41 || $catalogue_id == 13 || $catalogue_id == 72) {
				continue;
			};
        };
	switch ($sbx_output_counter) {
		case 2:
			// break for weather
			$sbx_output.="L\n"; // stop bar
			$sbx_output.="C,\$0000CCF7\n"; // weather cart
		break;
		case($sbx_output_counter%2!==0):
			// odd numbers
			$sbx_output.="C,\$00009D90\n"; // sweeper
		break;
	};
	array_push($musiclist,"C,\$0000".dechex($fetch_playlog["cartindex"]));
	$sbx_output.="C,\$0000".dechex($fetch_playlog["cartindex"])."\n";
	$sbx_output_counter++;
	echo "<tr onMouseOver=\"this.bgColor='gold';this.style.cursor='hand';\" onclick=\"self.location='sbxlog_rhcharts.php?studioselect=$studioselect&calendar=$todaydate&fromtime=$fromtime&totime=$totime&zoomcartid=".$fetch_playlog["cartindex"]."';\" onMouseOut=\"this.bgColor='#000000';\">";
	echo "<td>".$fetch_playlog["cartindex"]." C,\$0000".dechex($fetch_playlog["cartindex"])."</td>";
	echo "<td>".$fetch_playlog["lastplayed"]."</td>";
	echo "<td>".$fetch_playlog[3]."&nbsp;</td>";
	echo (!empty($fetch_playlog['markplayed']))? "<td>".$fetch_playlog['markplayed']."</td>" : "<td>&nbsp;</td>";
if ($foundcart) {
	$artist_id=artistlookup($fetch_playlog['cartindex']);
	echo (!empty($sbx_cartarray[$foundcart]['Title']))? "<td>".$sbx_cartarray[$foundcart]['Title']."</td>" : "<td>&nbsp;</td>";
	echo (!empty($sbx_artistarray[$artist_id]['ArtistName']))? "<td>".$sbx_artistarray[$artist_id]['ArtistName']."</td>" : "<td>&nbsp;</td>";
} else {
	echo "<td>".$fetch_playlog['cartindex']."</td><td>&nbsp;</td>";
};
	echo "<td>($catalogue_id) ".$catalogue_nameout."</td>";
//	echo "<td>".$playlog_value['Markplayed']."</td>";
//	echo (!empty($fetch_playlog['markplayed']))? "<td>".$fetch_playlog['markplayed']."</td>" : "<td>&nbsp;</td>";
//	echo "<td>".date("d/m/Y",strtotime($fetch_playlog['startdate']))."</td>";
//	echo "<td>".$fetch_playlog['starttime']."</td>";
//	echo "<td>".date("d/m/Y",strtotime($fetch_playlog['enddate']))."</td>";
//	echo "<td>".$fetch_playlog['endtime']."</td>";
//	echo "<td>".$fetch_playlog['totaltime']."</td>";
//	echo "<td>".$fetch_playlog['userindex']."</td>";
//	echo "<td>".$fetch_playlog['compindex']."</td>";
	echo "</tr>\n";
//	};
};
?>
</table>
</center>
<?php

//$todaydate=date("Ymd");
//$fp = fopen("/var/www/sbschedule/local_soundbox/PlayLog/$todaydate.DBF", "r");
//if(!px_open_fp($pxdoc, $fp)) {
//	echo "could not open local_soundbox/PlayLog/$todaydate.DBF<BR>\n";
//}
// ...
//print_r(px_get_info($pxdoc));
//print_r(px_get_record($pxdoc,5));
//px_close($pxdoc);
//px_delete($pxdoc);
//fclose($fp);


?>
</td></tr>
</table>
<PRE>
[Settings]

[Clock]
C,$00009D98
<?php
	// process musiclist
	// runing order:
	echo $musiclist[9]."\n";
	echo "L\n";			// break
	echo "C,\$0000CCF7\n";		// weather
	echo $musiclist[8]."\n";
	echo "C,\$0000D8E8\n";		//music imager
	echo $musiclist[7]."\n";
	echo "L\n";                     // break
	echo "C,\$0000C8F7\n";		// break bumper
	echo "C,\$000088D9\n";		// commercial break 4
	echo "C,\$000095CD\n";		// jingle light medium piano
	echo $musiclist[6]."\n";
	echo "L\n";
	echo $musiclist[5]."\n";
	echo "L\n";
	echo "C,\$0000C900\n";		// break bumper
	echo "C,\$0000EA52\n";		// promo saturday musicsport
	echo "C,\$00008898\n";		// commercial break 5
	echo "C,\$000095CB\n";		// jingle light medium sg
	echo $musiclist[4]."\n";
	echo "C,\$0000C913\n";		// sweeper 1024 hard swoosh
	echo $musiclist[3]."\n";
	echo "L\n";
	echo "C,\$0000CC4B\n";		// sweeper number 3
	echo $musiclist[2]."\n";
	echo "C,\$00009D90\n";		// sweeper andybrown
	echo "C,\$0000CC4A\n";		// sweeper number 2
	echo $musiclist[1]."\n";
	echo "L\n";
	echo "C,\$0000C8F6\n";		// break bumper tunestochewto
	echo "C,\$0000881C\n";		// commercial break 6
	echo "C,\$000095CC\n";		// jingle light medium strummy
	echo "C,\$0000CC49\n";		// sweeper number 1
	echo $musiclist[0]."\n";
	echo "L\n";

?>
</PRE>
<?php
footer_display();
?>
