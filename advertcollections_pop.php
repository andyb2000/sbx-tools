<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");

header_display();
body_display();
// menu_display();
get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();
?>
<table width=100% border=1 cellpadding=0 cellspacing=0>
<tr><td width=100%>
<u>Add adverts to this commercial break</u><BR><BR>

<form method=post name='cartform'>
<?php
$action=$_REQUEST["action"];
$cartid=$_REQUEST["cartid"];
$adid=$_REQUEST["adid"];
if ($action == "modify" && $cartid && $adid) {
	if(!$pxdoc_write = px_new()) {
echo "FAIL<BR>\n";
};
$fp_write = fopen("/mnt/soundbox/Data/Cart.DB", "r+");
if(!px_open_fp($pxdoc_write, $fp_write)) {
        echo "Error opening /mnt/soundbox/Data/Cart.DB for get_all_carts()<BR>\n";
        die();
}
	$table_info=px_get_info($pxdoc_write);
	// get existing cart info
	$px_cart_info=px_get_record($pxdoc_write,$cartid);

	if ($px_cart_info['Filename'] == "C:") {
		$break_list=$px_cart_info['Filename']."$adid";
	} else {
		$break_list=$px_cart_info['Filename'].",$adid";
	};

	// work out the runtime of this advert break
		$break_list_sub=substr($break_list,2);
		$break_list_array=explode(",",$break_list_sub);
		$break_total_time=0;
		foreach($break_list_array as $arrvalue){
			$cart_id=lookupcartindex($arrvalue);
			$ad_1=px_get_record($pxdoc_write,$cart_id);
			$ad_1_playtime=$ad_1['Length'];
			$break_total_time=$break_total_time+$ad_1_playtime;
		};
	$new_details_array=array('CartIndex'=>$px_cart_info['CartIndex'],'BPM'=>$px_cart_info['BPM'],'Catalogue'=>$px_cart_info['Catalogue'],'FadeTime'=>$px_cart_info['FadeTime'],'Filename'=>$break_list,'FileLocation'=>$px_cart_info['FileLocation'],'Flags'=>$px_cart_info['Flags'],'Energy'=>$px_cart_info['Energy'],'Extro'=>$px_cart_info['Extro'],'ExtIndex'=>$px_cart_info['ExtIndex'],'HWControl'=>$px_cart_info['HWControl'],'Intro'=>$px_cart_info['Intro'],'IOControl'=>$px_cart_info['IOControl'],'Length'=>$break_total_time,'Notes'=>$px_cart_info['Notes'],'SBxAudio'=>$px_cart_info['SBxAudio'],'Start'=>$px_cart_info['Start'],'Sway'=>$px_cart_info['Sway'],'Tempo'=>$px_cart_info['Tempo'],'Title'=>$px_cart_info['Title'],'UserIndex'=>$px_cart_info['UserIndex'],'Volume'=>$px_cart_info['Volume']);
//	echo "<BR><BR><b>NOT UPDATING!</b><BR>\n";
//	$do_updater=px_update_record($pxdoc_write,$new_details_array,$cartid);
//      22/05/2019 - new method via OOP method as apparently this works
        px_close($pxdoc_write);
        px_delete($pxdoc_write);
        fclose($fp_write);
// Now open it again in OOP method:
        $fp_write = fopen("/mnt/soundbox/Data/Cart.DB", "r+");
        $pxdoc = new paradox_db();
if(!$pxdoc->open_fp($fp_write)) {
        echo "Error opening /mnt/soundbox/Data/Cart.DB for get_all_carts() using OOP method<BR>\n";
        die();
}
        $pxdoc->update_record($new_details_array,$cartid);
        $pxdoc->close();
        fclose($fp_write);
echo "UPDATE said: $do_updater<BR>\n";
?>
<script language=Javascript>
//parent.window.opener.location.href = "advertcollections.php";
self.close();
</script>
<?php
};

?>
<center>
<table border=1 cellpadding=0 cellspacing=0 width=85%>
<tr>
<th>Title</th>
<th>Length (sec)</th>
<th>&nbsp;</th>
</tr>

<?php
// array_multisort($sbx_cartarray[0]['Title'], SORT_ASC, SORT_STRING);
$sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));

foreach($sbx_cartarray as $sbxcartid => $sbxcartdata) {
	if ($sbxcartdata['Catalogue'] == "85") {
	echo "<tr onMouseOver=\"this.bgColor='darkblue';\" onMouseOut=\"this.bgColor='#000000';\">";
	echo "<td>$sbxcartid ".$sbxcartdata['Title']."</td>";
	$cartlist=$sbxcartdata['Filename'];
	$cartlist_csv=substr($cartlist,2);
	$cartlist_array=explode(",",$cartlist_csv);
	$cartindex=$sbxcartdata['CartIndex'];
	$advert_length=$sbxcartdata['Length']/1000;
	echo "<td>".gmdate("i:s", $advert_length)."</td>\n";
	echo "<td><center><input type=button value='Add' onclick=\"Javascript:var a=window.open('advertcollections_pop.php?action=modify&cartid=$cartid&adid=$cartindex','winpop','width=300,height=400,location=0,resizable=1');\"></center></td>\n";
	echo "</tr>\n";
	};
};
?>
</table>

</td></tr>
</table>
<?php
footer_display();
?>
