<?php

// one off, just display what the fields are for each db file

include_once("../functions.php");

if(!$pxdoc = px_new()) {
	echo "Failed to create paradox\n";
	die();
}
$dir = "/var/www/sbschedule/local_soundbox/";
$filesarray=array();
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		if (filetype($dir.$file) == "file") {
		if (strpos($file, ".DB") !== false) {
		array_push($filesarray,$dir.$file);
            echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
		};
		};
        }
        closedir($dh);
    }
}

foreach ($filesarray as &$value) {
	echo "Processing $value\n";
$fp = fopen("$value", "r");
if(!px_open_fp($pxdoc, $fp)) {
  /* Error handling */
}
// ...
$table_info=px_get_info($pxdoc);
print_r(px_get_schema($pxdoc));
//$num_of_fields=$table_info['numfields'];
//$tmploop=0;
//while ($tmploop<$num_of_fields) {
//	print_r(px_get_field($pxdoc,$tmploop));
//	$tmploop++;
//};
// print_r(px_get_record($pxdoc,5));
px_close($pxdoc);
fclose($fp);
};
px_delete($pxdoc);
?>
