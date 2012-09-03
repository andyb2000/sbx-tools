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
};

$read_general=mysql_query("select * from schedule_general");
$load_general=mysql_fetch_array($read_general);
$artists_repeathours=$load_general["artists_repeathours"];
$songs_repeathours=$load_general["songs_repeathours"];


?>
<form method=post name='schedulemusicform'>
<input type=hidden name='action' value='<?=$_REQUEST["action"]?>'>
<center><b>Music Scheduling</b></center><BR>
General options:<BR>
<table cellpadding=0 cellspacing=0>
<tr><td>Repetition of artists:</td><td><input type=text name='artists_repeathours' size=3 value='<?=$artists_repeathours?>'>(in hrs)</td></tr>
<tr><td>Repetition of songs:</td><td><input type=text name='songs_repeathours' size=3 value='<?=$songs_repeathours?>'>(in hrs)</td></tr>
<tr><td colspan=2 align=right><input type=submit name='savegeneral' value='Save general options'></td></tr>
</table>
<BR><BR>
Individual show setup:<BR>
Select a show: <select name='showselect'>
<option value=''></option>
<?php
$loadshow=$_REQUEST["showselect"];
$check_shows=mysql_query("select * from shows where showname <> \"\" group by showname order by showname");
while ($read_shows=mysql_fetch_array($check_shows)) {
	echo "<option value='".$read_shows["id"]."' ";
	if ($loadshow == $read_shows["id"]) {echo "selected";};
	echo ">".$read_shows["showname"]."</option>\n";
};
?>
</select>&nbsp;<input type=submit name='loadshow' value='Load show'>
&nbsp;&nbsp;
<input type=submit name='createshow' value='Create Show Running Order In Soundbox'>
<?php
get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();

if ($_REQUEST["createshow"]) {

};

$maxid=$_REQUEST["maxid"];
$load_up=$_REQUEST["up"];
$load_down=$_REQUEST["down"];

$load_delete=$_REQUEST["delete"];

if ($load_delete) {
foreach ($load_delete as $tmp_id=>$tmp_delete) {
	echo "delete: $tmp_id $tmp_delete<BR>\n";
	$dodel=mysql_query("delete from show_music_schedule where id=$tmp_id");
	// fix sequencing
	$dofix=mysql_query("update show_music_schedule set sequence=@sequence:=@sequence+1 where sequence<9990 and (@sequence:=0)+1 and showid=$loadshow order by sequence");
};
};

if ($load_up) {
foreach ($load_up as $tmp_id=>$tmp_up) {
	echo "Up: $tmp_id $tmp_up<BR>\n";
	$curr_entry=mysql_query("select * from show_music_schedule where id=$tmp_id");
	$load_entry=mysql_fetch_array($curr_entry);
	$load_current_sequence=$load_entry["sequence"];
	$new_current_position=$load_current_sequence-1;

	$other_entry=mysql_query("select * from show_music_schedule where sequence=$new_current_position");
	$load_other_entry=mysql_fetch_array($other_entry);
	$other_entry_id=$load_other_entry["id"];
	$other_entry_new_position=$new_current_position+1;

	$change_other=mysql_query("update show_music_schedule set sequence=$other_entry_new_position where id=$other_entry_id");
	$change_new=mysql_query("update show_music_schedule set sequence=$new_current_position where id=$tmp_id");
	$dofix=mysql_query("update show_music_schedule set sequence=@sequence:=@sequence+1 where sequence<9990 and (@sequence:=0)+1 and showid=$loadshow order by sequence");
};
};

if ($load_down) {
foreach ($load_down as $tmp_id=>$tmp_down) {
	echo "Down: $tmp_id $tmp_down<BR>\n";
        $curr_entry=mysql_query("select * from show_music_schedule where id=$tmp_id");
        $load_entry=mysql_fetch_array($curr_entry);
        $load_current_sequence=$load_entry["sequence"];
        $new_current_position=$load_current_sequence+1;
        $other_entry=mysql_query("select * from show_music_schedule where sequence=$new_current_position");
        $load_other_entry=mysql_fetch_array($other_entry);
        $other_entry_id=$load_other_entry["id"];
        $other_entry_new_position=$new_current_position-1;
        $change_other=mysql_query("update show_music_schedule set sequence=$other_entry_new_position where id=$other_entry_id");
        $change_new=mysql_query("update show_music_schedule set sequence=$new_current_position where id=$tmp_id");
	$dofix=mysql_query("update show_music_schedule set sequence=@sequence:=@sequence+1 where sequence<9990 and (@sequence:=0)+1 and showid=$loadshow order by sequence");
};
};


if ($_REQUEST["save"] && $loadshow && $_REQUEST["new_catalogue"]) {
	$find_last_seq=mysql_query("select sequence from show_music_schedule where showid=$loadshow order by sequence desc limit 1");
	$load_last_seq=mysql_fetch_array($find_last_seq);
	$the_last_seq=$load_last_seq["sequence"];
	$new_sequence=$the_last_seq+1;
	$new_catalogue=$_REQUEST["new_catalogue"];
	$new_cartindex=$_REQUEST["new_cartindex"];
	$new_notes=$_REQUEST["new_notes"];
	$sql_ins="insert into show_music_schedule values('','$new_catalogue','$new_cartindex','','$new_sequence','$new_notes',$loadshow)";
	$dosql=mysql_query($sql_ins);
};

	if ($loadshow) {

		$top_seq_no=mysql_query("select sequence from show_music_schedule order by sequence asc limit 1");
		$load_top_seq_no=mysql_fetch_array($top_seq_no);
		$btm_seq_no=mysql_query("select sequence from show_music_schedule order by sequence desc limit 1");
		$load_btm_seq_no=mysql_fetch_array($btm_seq_no);

		$top_sequence=$load_top_seq_no["sequence"];
		$btm_sequence=$load_btm_seq_no["sequence"];

		$get_show_music_schedule=mysql_query("select * from show_music_schedule where showid=$loadshow order by sequence");
		echo "<table border=1 cellpadding=0 cellspacing=1 width=85%><tr><td>Order</td><td>Catalogue/Cart</td><td>notes</td></tr>\n";
		while ($load_show_music_schedule=mysql_fetch_array($get_show_music_schedule)) {
			echo "<tr onMouseOver=\"this.bgColor='darkblue';\" onMouseOut=\"this.bgColor='#000000';\">";
			echo "<td>".$load_show_music_schedule["sequence"]."&nbsp;<input type=submit name='up[".$load_show_music_schedule["id"]."]' value='up' ";
//			if ($top_sequence
			echo ">&nbsp;|&nbsp;<input type=submit name='down[".$load_show_music_schedule["id"]."]' value='down'>&nbsp;|&nbsp;<input type=submit name='delete[".$load_show_music_schedule["id"]."]' value='delete'></td>";
			if ($load_show_music_schedule["cartindex"]) {
				$foundcart=lookupcartindex($load_show_music_schedule["cartindex"]);
				$artist_id=artistlookup($load_show_music_schedule["cartindex"]);
				// echo "<td>".$load_show_music_schedule["catalogue"].$load_show_music_schedule["cartindex"]."</td>";
				if ($sbx_artistarray[$artist_id]['ArtistName']) {
					echo "<td>".$sbx_cartarray[$foundcart]['Title']."  -  ".$sbx_artistarray[$artist_id]['ArtistName']."</td>\n";
				} else {
					echo "<td>".$sbx_cartarray[$foundcart]['Title']."</td>\n";
				};
			} else {
				echo "<td>".$load_show_music_schedule["catalogue"]."</td>";
				print_r($sbx_cataloguearray[$load_show_music_schedule["catalogue"]]);
			};
			echo "<td>".$load_show_music_schedule["notes"]."</td></tr>\n";
			$last_id=$load_show_music_schedule["id"];
		};
		echo "<tr><td colspan=3>&nbsp;</td></tr>\n";
		echo "<tr><td>&nbsp;</td><td><select name='new_catalogue' onchange='document.forms[0].submit();'><option value=''></option>\n";
			// load in a list of catalogues first
			$sbx_cataloguearray=php_multisort($sbx_cataloguearray, array(array('key'=>'Description')));
			foreach ($sbx_cataloguearray as $out_id => $out_value) {
			        echo "<option value='".$out_value['Catalogue']."' ";
				if ($_REQUEST["new_catalogue"] == $out_value['Catalogue']) {echo "selected";};
				echo ">".$out_value['Description']."</option>\n";
			};
		echo "</select>";
		if ($_REQUEST["new_catalogue"]) {
			// Load in all the carts connected with this catalogue
			echo "&nbsp;<select name='new_cartindex'><option value=''>None/Random Selection</option>\n";
			$sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));
			foreach ($sbx_cartarray as $out_id => $out_value) {
				if ($out_value['Catalogue'] == $_REQUEST["new_catalogue"]) {
					echo "<option value='".$out_value['CartIndex']."' ";
					if ($_REQUEST["new_cartindex"] == $out_value['CartIndex']) {echo "selected";};
					$artist_id=artistlookup($out_value['CartIndex']);
					if ($sbx_artistarray[$artist_id]['ArtistName']) {
						echo ">".$out_value['Title']."  -  ".$sbx_artistarray[$artist_id]['ArtistName']."</option>\n";
					} else {
						echo ">".$out_value['Title']."</option>\n";
					};
				};
			};
			echo "</select>\n";
		};
		echo "<input type=hidden name='maxid' value='$last_id'>\n";
		echo "</td><td><input type=text name='new_notes' value='".$_REQUEST["new_notes"]."' size=50>&nbsp;&nbsp;<input type=submit name='save' value='Save'></td></tr>\n";
		echo "</table><BR>\n";
	};
?>
<BR>
</form>
