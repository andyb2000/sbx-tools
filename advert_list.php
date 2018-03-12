<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------
$script=1;
include_once("functions.php");

//header_display();
//body_display();
//menu_display();

get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();
get_all_filelocations();
?>
<?php
$action=$_REQUEST["action"];
$breakid=$_REQUEST["breakid"];
$collection=$_REQUEST["collection"];

	// check if soundbox drive is mounted, as we fail if it isnt
	$dothis=`cat /etc/mtab |grep soundbox`;
	if (strpos($dothis, "soundbox") === false) {
		echo "<B><font color=red size=+1>WARNING : Soundbox drive is not active, contact Andy Brown for support<BR><BR>DO NOT MAKE CHANGES - They will not be reflected in soundbox!<BR></font>\n";
	};

$breaknum=$_REQUEST["breaknum"];
// array_multisort($sbx_cartarray[0]['Title'], SORT_ASC, SORT_STRING);
$sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));

foreach($sbx_cartarray as $sbxcartid => $sbxcartdata) {
	if ($sbxcartdata['Catalogue'] == "73") {
	$total_length=0;
if ($breaknum) {
	$find_break="ZZ3LEGENDSBREAK".$breaknum;
} else {
	$find_break="ZZ3LEGENDSBREAK";
};
if (stripos($sbxcartdata['Title'], $find_break) !== false) {
//	echo "$sbxcartid ".$sbxcartdata['Title']."&nbsp;";
	$cartlist=$sbxcartdata['Filename'];
	$cartlist_csv=substr($cartlist,2);
	$cartlist_array=explode(",",$cartlist_csv);
	foreach($cartlist_array as $sbx_cartentry) {
		$foundcart=lookupcartindex($sbx_cartentry);
			$artist_id=artistlookup($sbx_cartentry);
			$safe_title=urlencode($sbx_cartarray[$foundcart]['Title']);
                                $crt_filename=$sbx_cartarray[$foundcart]['Filename'];
                                $crt_filelocation=$sbx_cartarray[$foundcart]['FileLocation'];
				$crt_length=$sbx_cartarray[$foundcart]['Length'];
				foreach ($sbx_filelocations as $my_out_id => $my_out_value) {
                        		if ($my_out_value["FileLocation"] == $crt_filelocation) {
	                	                $out_path=$my_out_value["Directory"];
        		                };
		                };
			if ($sbx_cartarray[$foundcart]['Title']) {
				$total_length=$total_length+$crt_length;
//				echo $sbx_cartarray[$foundcart]['CartIndex'] . " ";
//				echo $crt_length . " ";
//				echo $out_path . $crt_filename . "  -  " . $sbx_cartarray[$foundcart]['Title'];
//				echo "\"" . $out_path . $crt_filename . "\"";
				echo $out_path . $crt_filename;
			};
			echo "\n";
	};
	$total_length=$total_length/60;
//	echo "<BR>Total length: $total_length<BR>\n";
	$cartindex=$sbxcartdata['CartIndex'];
};
	};
};
?>
