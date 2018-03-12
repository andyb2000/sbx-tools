<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");

header_display();
body_display();
menu_display();

get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();
?>
<table width=100% border=1 cellpadding=1 cellspacing=2>
<tr><td width=100%>
<u>Advert Collections Administration</u><BR><BR>

<form method=post name='cartform'>
<?php
$action=$_REQUEST["action"];
$breakid=$_REQUEST["breakid"];
$collection=$_REQUEST["collection"];

if ($action == "modifybreak" && $breakid && $collection) {
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
	$px_cart_info=px_get_record($pxdoc_write,$collection);

	$break_list=substr($px_cart_info['Filename'],2);
	$break_list_array=explode(",",$break_list);
	//$new_filename=str_replace(",$breakid","",$px_cart_info['Filename']);
	$out_break_list_array=array();

	foreach($break_list_array as $arrvalue){
		if ($arrvalue != $breakid) {
			array_push($out_break_list_array,"$arrvalue");
		};
	};

        // work out the runtime of this advert break
                $break_total_time=0;
                foreach($out_break_list_array as $arrvalue){
                        $cart_id=lookupcartindex($arrvalue);
                        $ad_1=px_get_record($pxdoc_write,$cart_id);
                        $ad_1_playtime=$ad_1['Length'];
                        $break_total_time=$break_total_time+$ad_1_playtime;
                };
	$out_break_list="C:".implode(",",$out_break_list_array);
	$new_details_array=array('CartIndex'=>$px_cart_info['CartIndex'],'BPM'=>$px_cart_info['BPM'],'Catalogue'=>$px_cart_info['Catalogue'],'FadeTime'=>$px_cart_info['FadeTime'],'Filename'=>$out_break_list,'FileLocation'=>$px_cart_info['FileLocation'],'Flags'=>$px_cart_info['Flags'],'Energy'=>$px_cart_info['Energy'],'Extro'=>$px_cart_info['Extro'],'ExtIndex'=>$px_cart_info['ExtIndex'],'HWControl'=>$px_cart_info['HWControl'],'Intro'=>$px_cart_info['Intro'],'IOControl'=>$px_cart_info['IOControl'],'Length'=>$break_total_time,'Notes'=>$px_cart_info['Notes'],'SBxAudio'=>$px_cart_info['SBxAudio'],'Start'=>$px_cart_info['Start'],'Sway'=>$px_cart_info['Sway'],'Tempo'=>$px_cart_info['Tempo'],'Title'=>$px_cart_info['Title'],'UserIndex'=>$px_cart_info['UserIndex'],'Volume'=>$px_cart_info['Volume']);
// echo "<BR><B>NOT UPDATING!</B><BR>\n";
	$do_updater=px_update_record($pxdoc_write,$new_details_array,$collection);
	px_close($pxdoc_write);
	px_delete($pxdoc_write);
	fclose($fp_write);
?>
<script language=Javascript>
self.location='advertcollections.php';
</script>
<?php
};

?>
Current advert carts loaded in Soundbox:<BR>
Click on an Advert to delete it from the commercial break<BR>
<BR>
<center>
<?php

	// check if soundbox drive is mounted, as we fail if it isnt
	$dothis=`cat /etc/mtab |grep soundbox`;
	if (strpos($dothis, "soundbox") === false) {
		echo "<B><font color=red size=+1>WARNING : Soundbox drive is not active, contact Andy Brown for support<BR><BR>DO NOT MAKE CHANGES - They will not be reflected in soundbox!<BR></font>\n";
	};
?>
<table border=1 cellpadding=2 cellspacing=2 width=85%>
<tr>
<th>Title</th>
<th>Cart Numbers (raw)</th>
<th>&nbsp;</th>
</tr>

<?php
// array_multisort($sbx_cartarray[0]['Title'], SORT_ASC, SORT_STRING);
$sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));

foreach($sbx_cartarray as $sbxcartid => $sbxcartdata) {
	if ($sbxcartdata['Catalogue'] == "73") {
	$advert_length=0;
	echo "<tr onMouseOver=\"this.bgColor='darkblue';\" onMouseOut=\"this.bgColor='#000000';\">";
	echo "<td>$sbxcartid ".$sbxcartdata['Title']."</td>";
	$cartlist=$sbxcartdata['Filename'];
	$cartlist_csv=substr($cartlist,2);
	$cartlist_array=explode(",",$cartlist_csv);
//	echo "<td>".$cartlist_csv."&nbsp;<BR>";
	echo "<td>";
	foreach($cartlist_array as $sbx_cartentry) {
//		echo "$sbx_cartentry<BR>\n";
		$foundcart=lookupcartindex($sbx_cartentry);
//		if ($foundcart) {
			$crt_length=$sbx_cartarray[$foundcart]['Length'];
			$artist_id=artistlookup($sbx_cartentry);
			$safe_title=urlencode($sbx_cartarray[$foundcart]['Title']);
			$ad_lt=$sbx_cartarray[$foundcart]['Length']/1000;
        		// echo (!empty($sbx_cartarray[$foundcart]['Title']))? "<a title='".$sbx_cartarray[$foundcart]['Title']."' href='Javascript:if(confirm(\"Are you sure you wish to delete advert ".$safe_title."\")) {self.location=\"advertcollections.php?action=modifybreak&breakid=".$sbx_cartarray[$foundcart]['CartIndex']."&collection=$sbxcartid\";} else {alert(\"IGNORE\")};'><font color=#FFFFFF>".$sbx_cartarray[$foundcart]['Title']." (".gmdate("i:s", $ad_lt).") ID:".$sbx_cartentry."</font></a>" : "&nbsp;";
			echo "<a title='".$sbx_cartarray[$foundcart]['Title']."' href='Javascript:if(confirm(\"Are you sure you wish to delete advert ".$safe_title."\")) {self.location=\"advertcollections.php?action=modifybreak&breakid=".$sbx_cartentry."&collection=$sbxcartid\";} else {alert(\"IGNORE\")};'><font color=#FFFFFF>".$sbx_cartarray[$foundcart]['Title']." (".gmdate("i:s", $ad_lt).") ID:".$sbx_cartentry."</font></a>\n";
			echo "<BR>\n";
//		};
		$advert_length=$advert_length+$crt_length;
	};
	echo "</td>";
	$cartindex=$sbxcartdata['CartIndex'];
	echo "<td><center><input type=button value='Insert Advert' onclick=\"Javascript:var a=window.open('advertcollections_pop.php?action=modify&cartid=$sbxcartid','winpop','width=500,height=300,location=0,resizable=1,scroll=1,scrollbars=1');\"><BR>";
	$advert_length=$advert_length/1000;
	echo "Break length: ".gmdate("i:s", $advert_length). "(mm:ss)";
	echo "</center></td>\n";
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
