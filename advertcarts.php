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
<u>Advert Carts Administration</u><BR><BR>

<form method=post name='cartform'>
<?php
$action=$_REQUEST["action"];
$cartid=$_REQUEST["cartid"];

switch ($action) {
        case "connect":
		get_all_carts();
		foreach($sbx_cartarray as $sbxcartid => $sbxcartdata) {
		        if ($sbxcartdata['CartIndex'] == "$cartid") {
		        	$sbx_cart_title=$sbxcartdata['Title'];
				$sbx_cart_filename=$sbxcartdata['Filename'];
				$sbx_cart_filepath=$sbxcartdata['FileLocation'];
			};
		};
?>
<input type=hidden name=action value='save'>
<input type=hidden name=cartid value='<?=$cartid?>'>
<input type=hidden name=cartname value='<?=$sbx_cart_title?>'>
<input type=hidden name=cartfilename value='<?=$sbx_cart_filename?>'>
<input type=hidden name=cartfilepath value='<?=$sbx_cart_filepath?>'>
Select the client to connect this cart with:<BR><BR>
<table border=0 cellpadding=0 cellspacing=0>
<tr><td>Cart name: </td><td><?=$sbx_cart_title?></td></tr>
<tr><td>Cart filename: </td><td><?=$sbx_cart_filename?></td></tr>
<tr><td>Client company name: </td><td><select name='companyname'><option value=''></option><?php
$load_clients=mysql_query("select id,companyname from clients order by companyname");
while ($read_clients=mysql_fetch_array($load_clients)) {
	echo "<option value='".$read_clients["id"]."'>".decodevariable($read_clients["companyname"])."</option>\n";
};
?></select></td></tr>
<tr><td colspan=2 align=right><input type=submit name=submit value='Save'></td></tr>
</table>
</table>
<?php
	break;
        case "save":
              $readin_cartname=encodevariable($_REQUEST["cartname"]);
              $readin_cartfilename=encodevariable($_REQUEST["cartfilename"]);
              $readin_cartfilepath=encodevariable($_REQUEST["cartfilepath"]);
              $readin_companyname=$_REQUEST["companyname"];
              $doins=mysql_query("insert into advertcarts values('','$cartid','$readin_cartfilename','$readin_cartfilepath','$readin_cartname','$readin_companyname')");
              echo "<script language=Javascript>\nself.location='advertcarts.php';\n</script>\n";
        break;

	default:
?>
Current advert carts loaded in Soundbox:<BR>
<center>
<table border=1 cellpadding=0 cellspacing=0 width=85%>
<tr>
<th>Title</th>
<th>Filename</th>
<th>Client Name Connection</th>
</tr>

<?php
get_all_carts();
// array_multisort($sbx_cartarray[0]['Title'], SORT_ASC, SORT_STRING);
$sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));

foreach($sbx_cartarray as $sbxcartid => $sbxcartdata) {
	if ($sbxcartdata['Catalogue'] == "85") {
	echo "<tr onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='#000000';\">";
	echo "<td>".$sbxcartdata['Title']."</td>";
	echo "<td>".$sbxcartdata['Filename']."</td>";
	$cartindex=$sbxcartdata['CartIndex'];
	$dodi=mysql_query("select * from advertcarts where cartid=$cartindex");
	if (mysql_num_rows($dodi) <> 0) {
		$load_dodi=mysql_fetch_array($dodi);
		$get_clientname=mysql_query("select * from clients where id=".$load_dodi["clientid"]);
		$load_clientname=mysql_fetch_array($get_clientname);
		echo "<td>".decodevariable($load_clientname['companyname'])."</td>\n";
	} else {
		echo "<td><center>*NONE*&nbsp;&nbsp;<!-- <input type=button value='Connect' onclick=\"self.location='advertcarts.php?action=connect&cartid=$cartindex';\"> --></center></td>\n";
	};
	echo "</tr>\n";
	};
};
?>
</table>

<?php
};
?>

</td></tr>
</table>
<?php
footer_display();
?>
