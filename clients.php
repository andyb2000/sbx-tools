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
<u>Client Administration</u><BR><BR>

<form method=post name='clientform'>
<?php
$action=$_REQUEST["action"];
$editid=$_REQUEST["editid"];

switch ($action) {
	case "edit":
		$load_existing=mysql_query("select * from clients where id='$editid'");
		$read_existing=mysql_fetch_array($load_existing);
	case "add":
		// display the add/edit form
	if ($_REQUEST["companyname"]) {$companyname=$_REQUEST["companyname"];} else {$companyname=decodevariable($read_existing["companyname"]);};
	if ($_REQUEST["contactname"]) {$contactname=$_REQUEST["contactname"];} else {$contactname=decodevariable($read_existing["contactname"]);};
	if ($_REQUEST["address"]) {$address=$_REQUEST["address"];} else {$address=decodevariable($read_existing["address"]);};
	if ($_REQUEST["postcode"]) {$postcode=$_REQUEST["postcode"];} else {$postcode=decodevariable($read_existing["postcode"]);};
	if ($_REQUEST["telephone"]) {$telephone=$_REQUEST["telephone"];} else {$telephone=decodevariable($read_existing["telephone"]);};
	if ($_REQUEST["fax"]) {$fax=$_REQUEST["fax"];} else {$fax=decodevariable($read_existing["fax"]);};
	if ($_REQUEST["email"]) {$email=$_REQUEST["email"];} else {$email=decodevariable($read_existing["email"]);};

?>
<input type=hidden name='action' value='save'>
<input type=hidden name='editid' value='<?=$editid?>'>
<table border=1 cellpadding=0 cellspacing=0 width=80%>
<tr><td width=80%>&nbsp;&nbsp;
	<table width=100% border=0 cellpadding=0 cellspacing=0>
	<tr><td>Company Name:</td><td><input type=text name='companyname' size=40 value='<?=$companyname?>'></td></tr>
	<tr><td>Contact Name:</td><td><input type=text name='contactname' size=40 value='<?=$contactname?>'></td></tr>
	<tr><td>Address:</td><td><textarea name='address' cols=30><?=$address?></textarea></td></tr>
	<tr><td>Postcode:</td><td><input type=text name='postcode' value='<?=$postcode?>'></td></tr>
	<tr><td>Telephone:</td><td><input type=text name='telephone' size=40 value='<?=$telephone?>'></td></tr>
	<tr><td>Fax:</td><td><input type=text name='fax' size=40 value='<?=$fax?>'></td></tr>
	<tr><td>Email:</td><td><input type=text name='email' size=40 maxlength=200 value='<?=$email?>'></td></tr>
	<tr><td colspan=2 align=right><input type=submit name=submit value='Save'></td></tr>
	</table>
</td></tr>
</table>
<SCRIPT language="JavaScript">
 var frmvalidator  = new Validator("clientform");
        frmvalidator.addValidation("companyname","req","Please enter a company name");
        frmvalidator.addValidation("contactname","req","Please enter a contact name");
        frmvalidator.addValidation("email","req","Please enter a valid email address");
        frmvalidator.addValidation("email","email");
</script>
<?php
	if ($editid) {
		// load in
	};
	break;
	case "save":
		// do the saving of posted form data
		$companyname=encodevariable($_REQUEST["companyname"]);
		$contactname=encodevariable($_REQUEST["contactname"]);
		$address=encodevariable($_REQUEST["address"]);
		$postcode=encodevariable($_REQUEST["postcode"]);
		$telephone=encodevariable($_REQUEST["telephone"]);
		$fax=encodevariable($_REQUEST["fax"]);
		$email=encodevariable($_REQUEST["email"]);

		if ($editid) {
			$dosql=mysql_query("update clients set companyname='$companyname',contactname='$contactname',address='$address',postcode='$postcode',telephone='$telephone',fax='$fax',email='$email' where id=$editid");
		} else {
			$dosql=mysql_query("insert into clients values('','$companyname','$contactname','$address','$postcode','$telephone','$fax','$email',now(),'$userid')");
		};
		echo "<script language=Javascript>\nself.location='clients.php';\n</script>\n";
	case "delete":
		// do the deleting of an entry $editid
		// should this also delete all related adverts, etc?
		if ($editid) {
			$dodel=mysql_query("delete from clients where id='$editid'");
		};
		echo "<script language=Javascript>\nself.location='clients.php';\n</script>\n";
	break;
default:
?>
<input type=button value='Add new client' onclick='self.location="clients.php?action=add"'><BR><BR>
<table width=90% border=0 cellpadding=0 cellspacing=0>
<tr>
<th align=left>Company Name</th>
<th align=left>Contact Name</th>
<th align=left>Telephone</th>
<th align=left>Email</th>
<th>&nbsp;</th>
</tr>

<?php
$load_clienttable=mysql_query("select * from clients order by companyname");
if (mysql_num_rows($load_clienttable) <> 0) {
	$alternator="#333333";
	while($read_clienttable=mysql_fetch_array($load_clienttable)) {
		echo "<tr bgcolor=$alternator onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='$alternator';\">";
		echo "<td>".decodevariable($read_clienttable["companyname"])."</td>";
		echo "<td>".decodevariable($read_clienttable["contactname"])."</td>";
		echo "<td>".decodevariable($read_clienttable["telephone"])."</td>";
		echo "<td>".decodevariable($read_clienttable["email"])."</td>";
		echo "<td align=right><input type=button value='Edit' onclick='self.location=\"clients.php?action=edit&editid=".$read_clienttable["id"]."\";'>";
		echo "&nbsp;";
		echo "<input type=button value='Delete' onclick='Javascript:if(confirm(\"Are you sure?\")) {self.location=\"clients.php?action=delete&editid=".$read_clienttable["id"]."\";}'>";
		echo "</td>";
		echo "</tr>\n";
		if ($alternator == "#333333") {$alternator="#666666";} else {$alternator="#333333";};
	};
} else {
	echo "<tr><td colspan=5 align=center>Sorry - No clients currently on record</td></tr>\n";
};
?>
</table>

<?php
};
?>

</form>
</td></tr>
</table>
<?php
footer_display();
?>
