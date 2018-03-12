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
<u>Soundbox play log</u><BR>
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
//$db3lines=exec("/usr/bin/dbview /mnt/soundbox/Data/PlayLog/$todaydate.dbf",$db3lines_array);
// $db3lines_array=explode($db3lines,"\n");
//$runcount=0;
//$playlog=array();
//foreach($db3lines_array as $db3_var) {
//	if ($db3_var != "") {
//		list($in_key,$in_val)=split(" :",$db3_var);
//		$in_key=trim($in_key);
//		$in_val=trim($in_val);
//			$playlog[$runcount][$in_key]=$in_val;
//	} else {
//		$runcount++;
//	};
//};
// print_r($playlog);

$mysql_date_yr=substr($todaydate,0,4);
$mysql_date_mn=substr($todaydate,4,2);
$mysql_date_dy=substr($todaydate,6,2);
$mysql_date=$mysql_date_yr."-".$mysql_date_mn."-".$mysql_date_dy;

// which machine? studio1 or studio2
$studioselect=$_REQUEST["studioselect"];

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
$filtermusic=$_REQUEST["filtermusic"];

if (!$fromtime) {$fromtime="09";};
if (!$totime) {$totime="11";};

$load_mysql=mysql_query("select * from sbxlog where startdate='$mysql_date' and hour(starttime) between $fromtime and $totime and sbxcomputer='$studioselect' order by startdate,starttime,enddate,endtime") or die("Failed to query sbxlog ".mysql_error());
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
<BR>
<center><input type="button" value=" Print this page " onclick="window.print();return false;" /></center>
<?php
echo "</form></center><BR>\n";

flush();
set_time_limit(300);
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
?>
<center>
<table border=1 cellpadding=0 cellspacing=0 width=85%>
<tr>
<!-- <th>Version</th> -->
<th>Song Title</th>
<th>Artist Name</th>
<th>Category</th>
<th>MarkPlayed</th>
<th>StartDate</th>
<th>StartTime</th>
<th>EndDate</th>
<th>EndTime</th>
<!-- <th>TotalTime</th>
<th>UserIndex</th>
<th>Compindex</th></tr> -->
<?php
// foreach ($playlog as $playlog_key => $playlog_value) {
while ($fetch_playlog=mysql_fetch_array($load_mysql)) {
	$hr_starttime=substr($fetch_playlog['starttime'],0,2);
	$hr_endtime=substr($fetch_playlog['endtime'],0,2);
	if ($fetch_playlog['sbxcomputer'] == $studioselect) {
	$foundcart=lookupcartindex($fetch_playlog['cartindex']);
	if ($foundcart) {
		$artist_id=artistlookup($fetch_playlog['cartindex']);
		if ($filtermusic) {
			if (!$sbx_artistarray[$artist_id]['ArtistName']) {continue;};
		};
	};
	echo "<tr onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='#000000';\">";
//	echo "<td>".$playlog_value['Version']."</td>";
if ($foundcart) {
//	$artist_id=artistlookup($fetch_playlog['cartindex']);
//	echo "<td>".$sbx_cartarray[$foundcart]['Title'];
	echo (!empty($sbx_cartarray[$foundcart]['Title']))? "<td>".$sbx_cartarray[$foundcart]['Title']."</td>" : "<td>&nbsp;</td>";
//	echo "and: ".$sbx_artilink[$artist_id]['ArtistIndex'];
//	$a_artilink=$sbx_artilink[$artist_id]['ArtistIndex'];
//	echo "<td>".$sbx_artistarray[$artist_id]['ArtistName']."</td>\n";
	echo (!empty($sbx_artistarray[$artist_id]['ArtistName']))? "<td>".$sbx_artistarray[$artist_id]['ArtistName']."</td>" : "<td>&nbsp;</td>";
} else {
	echo "<td>".$fetch_playlog['cartindex']."</td><td>&nbsp;</td>";
};
	$catalogue_id=cataloguelookup($fetch_playlog['catalogue']);
	echo "<td>".$fetch_playlog['catalogue']."</td>";
//	echo "<td>".$playlog_value['Markplayed']."</td>";
	echo (!empty($fetch_playlog['markplayed']))? "<td>".$fetch_playlog['markplayed']."</td>" : "<td>&nbsp;</td>";
	echo "<td>".date("d/m/Y",strtotime($fetch_playlog['startdate']))."</td>";
	echo "<td>".$fetch_playlog['starttime']."</td>";
	echo "<td>".date("d/m/Y",strtotime($fetch_playlog['enddate']))."</td>";
	echo "<td>".$fetch_playlog['endtime']."</td>";
//	echo "<td>".$fetch_playlog['totaltime']."</td>";
//	echo "<td>".$fetch_playlog['userindex']."</td>";
//	echo "<td>".$fetch_playlog['compindex']."</td>";
	echo "</tr>\n";
	};
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
<?php
footer_display();
?>
