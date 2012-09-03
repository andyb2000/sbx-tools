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
<title>sbschedule -- schedule</title>
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
<li><a href="todays_soundbox_log.php?a=reset">todays soundbox log</a>
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
<div style="float: right"><a href="schedule.php?a=logout">[ Logout ]</a></div>
<br>
<?php
  $conn = connect();
  $showrecs = 50;
  $pagerange = 10;

  $a = @$_GET["a"];
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  $sql = @$_POST["sql"];

  switch ($sql) {
    case "insert":
      sql_insert();
      break;
    case "update":
      sql_update();
      break;
    case "delete":
      sql_delete();
      break;
  }

  switch ($a) {
    case "add":
      addrec();
      break;
    case "view":
      viewrec($recid);
      break;
    case "edit":
      editrec($recid);
      break;
    case "del":
      deleterec($recid);
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
<tr><td>Table: schedule</td></tr>
<tr><td>Records shown <?php echo $startrec + 1 ?> - <?php echo $reccount ?> of <?php echo $count ?></td></tr>
</table>
<hr size="1" noshade>
<form action="schedule.php" method="post">
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><b>Custom Filter</b>&nbsp;</td>
<td><input type="text" name="filter" value="<?php echo $filter ?>"></td>
<td><select name="filter_field">
<option value="">All Fields</option>
<option value="<?php echo "id" ?>"<?php if ($filterfield == "id") { echo "selected"; } ?>><?php echo htmlspecialchars("id") ?></option>
<option value="<?php echo "showid" ?>"<?php if ($filterfield == "showid") { echo "selected"; } ?>><?php echo htmlspecialchars("showid") ?></option>
<option value="<?php echo "lp_clientid" ?>"<?php if ($filterfield == "lp_clientid") { echo "selected"; } ?>><?php echo htmlspecialchars("clientid") ?></option>
<option value="<?php echo "lp_advertcartid" ?>"<?php if ($filterfield == "lp_advertcartid") { echo "selected"; } ?>><?php echo htmlspecialchars("advertcartid") ?></option>
<option value="<?php echo "position" ?>"<?php if ($filterfield == "position") { echo "selected"; } ?>><?php echo htmlspecialchars("position") ?></option>
<option value="<?php echo "lastplayed" ?>"<?php if ($filterfield == "lastplayed") { echo "selected"; } ?>><?php echo htmlspecialchars("lastplayed") ?></option>
</select></td>
<td><input type="checkbox" name="wholeonly"<?php echo $checkstr ?>>Whole words only</td>
</td></tr>
<tr>
<td>&nbsp;</td>
<td><input type="submit" name="action" value="Apply Filter"></td>
<td><a href="schedule.php?a=reset">Reset Filter</a></td>
</tr>
</table>
</form>
<hr size="1" noshade>
<?php showpagenav($page, $pagecount); ?>
<br>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="100%">
<tr>
<td class="hr">&nbsp;</td>
<td class="hr">&nbsp;</td>
<td class="hr">&nbsp;</td>
<td class="hr"><a class="hr" href="schedule.php?order=<?php echo "id" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("id") ?></a></td>
<td class="hr"><a class="hr" href="schedule.php?order=<?php echo "showid" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("showid") ?></a></td>
<td class="hr"><a class="hr" href="schedule.php?order=<?php echo "lp_clientid" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("clientid") ?></a></td>
<td class="hr"><a class="hr" href="schedule.php?order=<?php echo "lp_advertcartid" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("advertcartid") ?></a></td>
<td class="hr"><a class="hr" href="schedule.php?order=<?php echo "position" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("position") ?></a></td>
<td class="hr"><a class="hr" href="schedule.php?order=<?php echo "lastplayed" ?>&type=<?php echo $ordtypestr ?>"><?php echo htmlspecialchars("lastplayed") ?></a></td>
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
<td class="<?php echo $style ?>"><a href="schedule.php?a=view&recid=<?php echo $i ?>">View</a></td>
<td class="<?php echo $style ?>"><a href="schedule.php?a=edit&recid=<?php echo $i ?>">Edit</a></td>
<td class="<?php echo $style ?>"><a href="schedule.php?a=del&recid=<?php echo $i ?>">Delete</a></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["id"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["showid"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["lp_clientid"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["lp_advertcartid"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["position"]) ?></td>
<td class="<?php echo $style ?>"><?php echo htmlspecialchars($row["lastplayed"]) ?></td>
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
<form action="schedule.php" method="post">
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
<td class="hr"><?php echo htmlspecialchars("showid")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["showid"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("clientid")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["lp_clientid"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("advertcartid")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["lp_advertcartid"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("position")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["position"]) ?></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("lastplayed")."&nbsp;" ?></td>
<td class="dr"><?php echo htmlspecialchars($row["lastplayed"]) ?></td>
</tr>
</table>
<?php } ?>

<?php function showroweditor($row, $iseditmode)
  {
  global $conn;
?>
<table class="tbl" border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="hr"><?php echo htmlspecialchars("id")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="id" value="<?php echo str_replace('"', '&quot;', trim($row["id"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("showid")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="showid" value="<?php echo str_replace('"', '&quot;', trim($row["showid"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("clientid")."&nbsp;" ?></td>
<td class="dr"><select name="clientid">
<option value=""></option>
<?php
  $sql = "select `id`, `companyname` from `clients`";
  $res = mysql_query($sql, $conn) or die(mysql_error());

  while ($lp_row = mysql_fetch_assoc($res)){
  $val = $lp_row["id"];
  $caption = $lp_row["companyname"];
  if ($row["clientid"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("advertcartid")."&nbsp;" ?></td>
<td class="dr"><select name="advertcartid">
<option value=""></option>
<?php
  $sql = "select `id`, `name` from `advertcarts`";
  $res = mysql_query($sql, $conn) or die(mysql_error());

  while ($lp_row = mysql_fetch_assoc($res)){
  $val = $lp_row["id"];
  $caption = $lp_row["name"];
  if ($row["advertcartid"] == $val) {$selstr = " selected"; } else {$selstr = ""; }
 ?><option value="<?php echo $val ?>"<?php echo $selstr ?>><?php echo $caption ?></option>
<?php } ?></select>
</td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("position")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="position" value="<?php echo str_replace('"', '&quot;', trim($row["position"])) ?>"></td>
</tr>
<tr>
<td class="hr"><?php echo htmlspecialchars("lastplayed")."&nbsp;" ?></td>
<td class="dr"><input type="text" name="lastplayed" value="<?php echo str_replace('"', '&quot;', trim($row["lastplayed"])) ?>"></td>
</tr>
</table>
<?php } ?>

<?php function showpagenav($page, $pagecount)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="schedule.php?a=add">Add Record</a>&nbsp;</td>
<?php if ($page > 1) { ?>
<td><a href="schedule.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;</td>
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
<td><a href="schedule.php?page=<?php echo $j ?>"><?php echo $j ?></a></td>
<?php } } } else { ?>
<td><a href="schedule.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a></td>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
<td>&nbsp;<a href="schedule.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="schedule.php">Index Page</a></td>
<?php if ($recid > 0) { ?>
<td><a href="schedule.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Prior Record</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="schedule.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Next Record</a></td>
<?php } ?>
</tr>
</table>
<hr size="1" noshade>
<?php } ?>

<?php function addrec()
{
?>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="schedule.php">Index Page</a></td>
</tr>
</table>
<hr size="1" noshade>
<form enctype="multipart/form-data" action="schedule.php" method="post">
<p><input type="hidden" name="sql" value="insert"></p>
<?php
$row = array(
  "id" => "",
  "showid" => "",
  "clientid" => "",
  "advertcartid" => "",
  "position" => "",
  "lastplayed" => "");
showroweditor($row, false);
?>
<p><input type="submit" name="action" value="Post"></p>
</form>
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
<br>
<hr size="1" noshade>
<table class="bd" border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="schedule.php?a=add">Add Record</a></td>
<td><a href="schedule.php?a=edit&recid=<?php echo $recid ?>">Edit Record</a></td>
<td><a href="schedule.php?a=del&recid=<?php echo $recid ?>">Delete Record</a></td>
</tr>
</table>
<?php
  mysql_free_result($res);
} ?>

<?php function editrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("edit", $recid, $count);
?>
<br>
<form enctype="multipart/form-data" action="schedule.php" method="post">
<input type="hidden" name="sql" value="update">
<input type="hidden" name="xid" value="<?php echo $row["id"] ?>">
<?php showroweditor($row, true); ?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php function deleterec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("del", $recid, $count);
?>
<br>
<form action="schedule.php" method="post">
<input type="hidden" name="sql" value="delete">
<input type="hidden" name="xid" value="<?php echo $row["id"] ?>">
<?php showrow($row, $recid) ?>
<p><input type="submit" name="action" value="Confirm"></p>
</form>
<?php
  mysql_free_result($res);
} ?>

<?php function connect()
{
  $conn = mysql_connect("192.168.2.5", "admin", "atomic");
  mysql_select_db("sbschedule");
  return $conn;
}

function sqlvalue($val, $quote)
{
  if ($quote)
    $tmp = sqlstr($val);
  else
    $tmp = $val;
  if ($tmp == "")
    $tmp = "NULL";
  elseif ($quote)
    $tmp = "'".$tmp."'";
  return $tmp;
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
  $sql = "SELECT * FROM (SELECT t1.`id`, t1.`showid`, t1.`clientid`, lp2.`companyname` AS `lp_clientid`, t1.`advertcartid`, lp3.`name` AS `lp_advertcartid`, t1.`position`, t1.`lastplayed` FROM `schedule` AS t1 LEFT OUTER JOIN `clients` AS lp2 ON (t1.`clientid` = lp2.`id`) LEFT OUTER JOIN `advertcarts` AS lp3 ON (t1.`advertcartid` = lp3.`id`)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`id` like '" .$filterstr ."') or (`showid` like '" .$filterstr ."') or (`lp_clientid` like '" .$filterstr ."') or (`lp_advertcartid` like '" .$filterstr ."') or (`position` like '" .$filterstr ."') or (`lastplayed` like '" .$filterstr ."')";
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
  $sql = "SELECT COUNT(*) FROM (SELECT t1.`id`, t1.`showid`, t1.`clientid`, lp2.`companyname` AS `lp_clientid`, t1.`advertcartid`, lp3.`name` AS `lp_advertcartid`, t1.`position`, t1.`lastplayed` FROM `schedule` AS t1 LEFT OUTER JOIN `clients` AS lp2 ON (t1.`clientid` = lp2.`id`) LEFT OUTER JOIN `advertcarts` AS lp3 ON (t1.`advertcartid` = lp3.`id`)) subq";
  if (isset($filterstr) && $filterstr!='' && isset($filterfield) && $filterfield!='') {
    $sql .= " where " .sqlstr($filterfield) ." like '" .$filterstr ."'";
  } elseif (isset($filterstr) && $filterstr!='') {
    $sql .= " where (`id` like '" .$filterstr ."') or (`showid` like '" .$filterstr ."') or (`lp_clientid` like '" .$filterstr ."') or (`lp_advertcartid` like '" .$filterstr ."') or (`position` like '" .$filterstr ."') or (`lastplayed` like '" .$filterstr ."')";
  }
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
}

function sql_insert()
{
  global $conn;
  global $_POST;

  $sql = "insert into `schedule` (`id`, `showid`, `clientid`, `advertcartid`, `position`, `lastplayed`) values (" .sqlvalue(@$_POST["id"], false).", " .sqlvalue(@$_POST["showid"], false).", " .sqlvalue(@$_POST["clientid"], false).", " .sqlvalue(@$_POST["advertcartid"], false).", " .sqlvalue(@$_POST["position"], false).", " .sqlvalue(@$_POST["lastplayed"], true).")";
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_update()
{
  global $conn;
  global $_POST;

  $sql = "update `schedule` set `id`=" .sqlvalue(@$_POST["id"], false).", `showid`=" .sqlvalue(@$_POST["showid"], false).", `clientid`=" .sqlvalue(@$_POST["clientid"], false).", `advertcartid`=" .sqlvalue(@$_POST["advertcartid"], false).", `position`=" .sqlvalue(@$_POST["position"], false).", `lastplayed`=" .sqlvalue(@$_POST["lastplayed"], true) ." where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}

function sql_delete()
{
  global $conn;

  $sql = "delete from `schedule` where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}
function primarykeycondition()
{
  global $_POST;
  $pk = "";
  $pk .= "(`id`";
  if (@$_POST["xid"] == "") {
    $pk .= " IS NULL";
  }else{
  $pk .= " = " .sqlvalue(@$_POST["xid"], false);
  };
  $pk .= ")";
  return $pk;
}
 ?>
