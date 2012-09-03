<?php session_start();
?>

<html>
<head>
<title>sbschedule -- tmp</title>
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
<?php
$sbx_cartarray=array();
if(!$pxdoc = px_new()) {
echo "FAIL<BR>\n";
};
$fp = fopen("/mnt/sda/studio1/SoundBox.weekly/Data/Cart.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
	echo "Error opening /mnt/sda/studio1/SoundBox.weekly/Data/Cart.DB for get_all_carts()<BR>\n";
	die();
}
$table_info=px_get_info($pxdoc);
$num_of_records=$table_info['numrecords'];
$tmploop=0;
while ($tmploop<$num_of_records) {
	// loading in all the records
	array_push($sbx_cartarray,@px_get_record($pxdoc,$tmploop));
	print_r($sbx_cartarray[$tmploop]);
	$tmploop++;
};
//print_r(px_get_record($pxdoc,5));
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);

?>

</body>
</html>
