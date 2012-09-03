<?php
if ($_REQUEST["submit"] == "Login") {
	// check for valid login
	if ($_REQUEST["username"] && $_REQUEST["password"]) {
		$read_user=$_REQUEST["username"];
		$read_pass=$_REQUEST["password"];
		$check_db=mysql_query("select * from adminusers where username='$read_user' and password='$read_pass' limit 1");
		if (mysql_num_rows($check_db) <> 0) {
			setcookie("sbschedule", "$read_user");
//			echo "Cookie SET<BR>\n";
			header("Location: index.php");
			exit;
		} else {
			sleep(5);
			echo "User/Pass failure<BR>\n";
//			echo "<script language=Javascript>\nself.location='index.php'\n</script>\n";
			exit;
		};
	};
};
?>
<form method=post name='loginform'>
<center>
<table border=1 cellpadding=1>
<tr><td>
	Login to use this service:<BR>
	<table border=0 cellpadding=0 cellspacing=0>
		<tr><td>Username: </td><td><input type=text name='username'></td></tr>
		<tr><td>Password: </td><td><input type=password name='password'></td></tr>
		<tr><td colspan=2 align=right><input type=submit name=submit value='Login'></td></tr>
	</table>

</td></tr>
</table>
</center>
</form>
