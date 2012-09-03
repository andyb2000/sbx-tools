<?php

// this will import carts from soundbox
//  for safety, we'll mount the soundbox location, copy it all down locally and work on the local copy
//  so we dont fsck up soundbox databases

include_once("../functions.php");

@mkdir("/var/www/sbschedule/local_soundbox");
dircopy("/mnt/soundbox/Data", "/var/www/sbschedule/local_soundbox");

//if(!$pxdoc = px_new()) {
//	echo "Failed to create paradox\n";
//	die();
//}
//$fp = fopen("/var/www/sbschedule/local_soundbox/Cart.DB", "r");
//if(!px_open_fp($pxdoc, $fp)) {
//  /* Error handling */
//}
// ...
//print_r(px_get_info($pxdoc));
//print_r(px_get_record($pxdoc,5));
//px_close($pxdoc);
//px_delete($pxdoc);
//fclose($fp);
?>
