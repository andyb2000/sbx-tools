<?php
if(!$pxdoc = px_new()) {
  /* Error handling */
}
$fp = fopen("carts/Cart.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
  /* Error handling */
}
// ...
print_r(px_get_info($pxdoc));
print_r(px_get_record($pxdoc,5));
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);
?>
