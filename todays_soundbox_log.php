<?php session_start();
  if (isset($_GET["order"])) $order = @$_GET["order"];
  if (isset($_GET["type"])) $ordtype = @$_GET["type"];

  if (isset($_POST["filter"])) $filter = @$_POST["filter"];
  if (isset($_POST["filter_field"])) $filterfield = @$_POST["filter_field"];
  $wholeonly = false;
  if (isset($_POST["wholeonly"])) $wholeonly = @$_POST["wholeonly"];

  if (!isset($order) && isset($_SESSION["order"])) $order = $_SESSION["order"];
  if (!isset($ordtype) && isset($_SESSION["type"])) $ordtype = $_SESSION["type"];
  if (!isset($filter) && isset($_SESSION["filter"])) $filter = $_SESSION["filter"];
  if (!isset($filterfield) && isset($_SESSION["filter_field"])) $filterfield = $_SESSION["filter_field"];

?>

<html>
<head>
<title>sbschedule -- todays soundbox log</title>
<meta name="generator" http-equiv="content-type" content="text/html">
<style type="text/css">
  body {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .bd {
    background-color: #FFFFFF;
    color: #004080;
    font-family: Arial;
    font-size: 12px;
  }
  .tbl {
    background-color: #FFFFFF;
  }
  a:link { 
    color: #FF0000;
    font-family: Arial;
    font-size: 12px;
  }
  a:active { 
    color: #0000FF;
    font-family: Arial;
    font-size: 12px;
  }
  a:visited { 
    color: #800080;
    font-family: Arial;
    font-size: 12px;
  }
  .hr {
    background-color: #336699;
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:link {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:active {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  a.hr:visited {
    color: #FFFFFF;
    font-family: Arial;
    font-size: 12px;
  }
  .dr {
    background-color: #FFFFFF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
  .sr {
    background-color: #FFFFCF;
    color: #000000;
    font-family: Arial;
    font-size: 12px;
  }
</style>
</head>
<body>
<table class="bd" width="100%"><tr><td class="hr">SB-Schedule+ by Andy Brown (C)2008 andy@broadcast-tech.co.uk</td></tr></table>
<table width="100%">
<tr>

<td width="10%" valign="top">
<li><a href="adminusers.php?a=reset">adminusers</a>
<li><a href="advertcarts.php?a=reset">advertcarts</a>
<li><a href="clients.php?a=reset">clients</a>
<li><a href="sbxlog.php?a=reset">sbxlog</a>
<li><a href="schedule.php?a=reset">schedule</a>
</td>
<td width="5%">
</td>
<td bgcolor="#e0e0e0">
</td>
<td width="5%">
</td>
<td width="80%" valign="top">
<?php
  if (!login()) exit;
?>
<div style="float: right"><a href="todays_soundbox_log.php?a=logout">[ Logout ]</a></div>
<br>
<?php
  $conn = connect();
  $showrecs = 50;
  $pagerange = 10;

  $a = @$_GET["a"];
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  switch ($a) {
    case "view":
      viewrec($recid);
      break;
    default:
      select();
      break;
  }

  if (isset($order)) $_SESSION["order"] = $order;
  if (isset($ordtype)) $_SESSION["type"] = $ordtype;
  if (isset($filter)) $_SESSION["filter"] = $filter;
  if (isset($filterfield)) $_SESSION["filter_field"] = $filterfield;
  if (isset($wholeonly)) $_SESSION["wholeonly"] = $wholeonly;

  mysql_close($conn);
?>
</td></tr></table>

</body>
</html>

<?php function select()
  {
  global $a;
  global $showrecs;
  global $page;
  global $filter;
  global $filterfield;
  global $wholeonly;
  global $order;
  global $ordtype;


  if ($a == "reset") {
    $filter = "";
    $filterfield = "";
    $wholeonly = "";
    $order = "";
    $ordtype = "";
  }

  $checkstr = "";
  if ($wholeonly) $checkstr = " checked";
  if ($ordtype == "asc") { $ordtypestr = "desc"; } else { $ordtypestr = "asc"; }
  $res = sql_select();
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr><td>Table: todays soundbox log</td></tr>
<tr><td>Records shown <?php echo $startrec + 1 ?> - <?php echo $reccount ?> of <?php echo $count ?></td></tr>
</table>
<hr size="1" noshade>
<form action="todays_soundbox_log.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>Custom Filter</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">All Fields</option>
<option value="<?php echo "id" ?>"<?php if ($filterfield == "id") { echo "selected"; } ?>><?php echo htmlspecialchars("id") ?></option>
<option value="<?php echo "cartindex" ?>"<?php if ($filterfield == "cartindex") { echo "selected"; } ?>><?php echo htmlspecialchars("cartindex") ?></option>
<option value="<?php echo "startdate" ?>"<?php if ($filterfield == "startdate") { echo "selected"; } ?>><?php echo htmlspecialchars("startdate") ?></option>
<option value="<?php echo "starttime" ?>"<?php if ($filterfield == "starttime") { echo "selected"; } ?>><?php echo htmlspecialchars("starttime") ?></option>
<option value="<?php echo "enddate" ?>"<?php if ($filterfield == "enddate") { echo "selected"; } ?>><?php echo htmlspecialchars("enddate") ?></option>
<option value="<?php echo "endtime" ?>"<?php if ($filterfield == "endtime") { echo "selected"; } ?>><?php echo htmlspecialchars("endtime") ?></option>
<option value="<?php echo "catalogue" ?>"<?php if ($filterfield == "catalogue") { echo "selected"; } ?>><?php echo htmlspecialchars("catalogue") ?></option>
<option value="<?php echo "filename" ?>"<?php if ($filterfield == "filename") { echo "selected"; } ?>><?php echo htmlspecialchars("filename") ?></option>
<option value="<?php echo "filelocation" ?>"<?php if ($filterfield == "filelocation") { echo "selected"; } ?>><?php echo htmlspecialchars("filelocation") ?></option>
<option value="<?php echo "title" ?>"<?php if ($filterfield == "title") { echo "selected"; } ?>><?php echo htmlspecialchars("title") ?></option>
<option value="<?php echo "filelocation_description" ?>"<?php if ($filterfield == "filelocation_description") { echo "selected"; } ?>><?php echo htmlspecialchars("filelocation_description") ?></option>
<option value="<?php echo "filelocation_directory" ?>"<?php if ($filterfield == "filelocation_directory") { echo "selected"; } ?>><?php echo htmlspecialchars("filelocation_directory") ?></option>
<option value="<?php echo "cataloguename" ?>"<?php if ($filterfield == "cataloguename") { echo "selected"; } ?>><?php echo htmlspecialchars("cataloguename") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Whole words only</td>
</td></tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="action" value="Apply Filter"></td>
<td><a href="todays_soundbox_log.php?a=reset">Reset Filter</a></td>
</tr>
</table>
</form>
<hr size="1" noshade>
<?php showpagenav($page, $pagecount); ?>
<br>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
<tr>
<td class="hr">&nbsp;</td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "id" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("id") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "cartindex" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("cartindex") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "startdate" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("startdate") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "starttime" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("starttime") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "enddate" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("enddate") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "endtime" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("endtime") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "catalogue" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("catalogue") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "filename" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("filename") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "filelocation" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("filelocation") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "title" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("title") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "filelocation_description" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("filelocation_description") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "filelocation_directory" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("filelocation_directory") ?></a></td>
<td class="hr"><a class="hr" href="todays_soundbox_log.php?order=<?php echo "cataloguename" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("cataloguename") ?></a></td>
</tr>
<?php
  for ($i = $startrec; $i < $reccount; $i++)
  {
    $row = mysql_fetch_assoc($res);
    $style = "dr";
    if ($i % 2 != 0) {
      $style = "sr";
    }
?>
<tr>
<td class="<?php echo $style ?>"><a href="todays_soundbox_log.php?a=view&recid=<?php echo $i ?>">View</a></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["id"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["cartindex"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["startdate"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["starttime"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["enddate"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["endtime"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["catalogue"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["filename"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["filelocation"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["title"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["filelocation_description"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["filelocation_directory"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["cataloguename"]) ?></td>
</tr>
<?php
  }
  mysql_free_result($res);
?>
</table>
<br>
<?php showpagenav($page, $pagecount); ?>
<?php } ?>

<?php function login()
{
  global $_POST;
  global $_SESSION;

  global $_GET;
  if (isset($_GET["a"]) && ($_GET["a"] == 'logout')) $_SESSION["logged_in"] = false;
  if (!isset($_SESSION["logged_in"])) $_SESSION["logged_in"] = false;
  if (!$_SESSION["logged_in"]) {
    $login = "";
    $password = "";
    if (isset($_POST["login"])) $login = @$_POST["login"];
    if (isset($_POST["password"])) $password = @$_POST["password"];

    if (($login != "") && ($password != "")) {
      $conn = mysql_connect("192.168.2.5", "admin", "atomic");
      mysql_select_db("sbschedule");
      $sql = "select `password` from `adminusers` where `username` = '" .$login ."'";
      $res = mysql_query($sql, $conn) or die(mysql_error());
      $row = mysql_fetch_assoc($res) or $row = array(0 => "");;
      if (isset($row)) reset($row);

      if (isset($password) && ($password == trim(current($row)))) {
        $_SESSION["logged_in"] = true;
    }
    else {
?>
<p><b><font color="-1">Sorry, the login/password combination you've entered is invalid</font></b></p>
<?php } } }if (isset($_SESSION["logged_in"]) && (!$_SESSION["logged_in"])) { ?>
<form action="todays_soundbox_log.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td>Login</td>
<td><input type="text" name="login" value="<?php echo $login ?>"></td>
</tr>
<tr>
<td>Password</td>
<td><input type="password" name="password" value="<?php echo $password ?>"></td>
</tr>
<tr>
<td><input type="submit" name="action" value="Login"></td>
</tr>
</table>
</form>
<?php
  }
  if (!isset($_SESSION["logged_in"])) $_SESSION["logged_in"] = false;
  return $_SESSION["logged_in"];
} ?>

<?php function showrow($row, $recid)
  {
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("id")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["id"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("cartindex")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["cartindex"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("startdate")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["startdate"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("starttime")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["starttime"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("enddate")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["enddate"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("endtime")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["endtime"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("catalogue")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["catalogue"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("filename")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["filename"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("filelocation")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["filelocation"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("title")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["title"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("filelocation_description")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["filelocation_description"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("filelocation_directory")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["filelocation_directory"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("cataloguename")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["cataloguename"]) ?></td>
</tr>
</table>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<?php if ($page > 1) { ?>
<td><a href="todays_soundbox_log.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
<?php } ?>
<?php
  global $pagerange;

  if ($pagecount > 1) {

  if ($pagecount % $pagerange != 0) {
    $rangecount = intval($pagecount / $pagerange) + 1;
  }
  else {
    $rangecount = intval($pagecount / $pagerange);
  }
  for ($i = 1; $i < $rangecount + 1; $i++) {
    $startpage = (($i - 1) * $pagerange) + 1;
    $count = min($i * $pagerange, $pagecount);

    if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      for ($j = $startpage; $j < $count + 1; $j++) {
        if ($j == $page) {
?>
<td><b><?php echo $j ?></b></td>
<?php } else { ?>
<td><a href="todays_soundbox_log.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="todays_soundbox_log.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="todays_soundbox_log.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="todays_soundbox_log.php">Index Page</a></td>
<?php if ($recid > 0) { ?>
<td><a href="todays_soundbox_log.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Prior Record</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="todays_soundbox_log.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Next Record</a></td>
<?php } ?>
</tr>
</table>
<hr size="1" noshade>
<?php } ?>


<?php function viewrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("view", $recid, $count);
?>
<br>
<?php showrow($row, $recid) ?>
<?php
  mysql_free_result($res);
} ?>

<?php function connect()
{
  $conn = mysql_connect("192.168.2.5", "admin", "atomic");
  mysql_select_db("sbschedule");
  return $conn;
}

function sqlstr($val)
{
  return str_replace("'", "''", $val);
}

function sql_select()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT * FROM (select * from sbxlog where startdate=today()) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`id` like '" .$filterstr ."') or (`cartindex` like '" .$filterstr ."') or (`startdate` like '" .$filterstr ."') or (`starttime` like '" .$filterstr ."') or (`enddate` like '" .$filterstr ."') or (`endtime` like '" .$filterstr ."') or (`catalogue` like '" .$filterstr ."') or (`filename` like '" .$filterstr ."') or (`filelocation` like '" .$filterstr ."') or (`title` like '" .$filterstr ."') or (`filelocation_description` like '" .$filterstr ."') or (`filelocation_directory` like '" .$filterstr ."') or (`cataloguename` like '" .$filterstr ."')";
  }
  if (isset($order) && $order!='') $sql .= " order by `" .sqlstr($order) ."`";
  if (isset($ordtype) && $ordtype!='') $sql .= " " .sqlstr($ordtype);
  $res = mysql_query($sql, $conn) or die(mysql_error());
  return $res;
}

function sql_getrecordcount()
{
  global $conn;
  global $order;
  global $ordtype;
  global $filter;
  global $filterfield;
  global $wholeonly;

  $filterstr = sqlstr($filter);
  if (!$wholeonly && isset($wholeonly) && $filterstr!='') $filterstr = "%" .$filterstr ."%";
  $sql = "SELECT COUNT(*) FROM (select * from sbxlog where startdate=today()) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`id` like '" .$filterstr ."') or (`cartindex` like '" .$filterstr ."') or (`startdate` like '" .$filterstr ."') or (`starttime` like '" .$filterstr ."') or (`enddate` like '" .$filterstr ."') or (`endtime` like '" .$filterstr ."') or (`catalogue` like '" .$filterstr ."') or (`filename` like '" .$filterstr ."') or (`filelocation` like '" .$filterstr ."') or (`title` like '" .$filterstr ."') or (`filelocation_description` like '" .$filterstr ."') or (`filelocation_directory` like '" .$filterstr ."') or (`cataloguename` like '" .$filterstr ."')";
  }
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
} ?>
