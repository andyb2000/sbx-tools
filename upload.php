<?php
$session = md5(uniqid(rand(), true));
$upload_fields = 1;

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

$sbx_title=$_REQUEST["sbx_title"];
$sbx_catalogue=$_REQUEST["sbx_catalogue"];
$sbx_filename=$_REQUEST["sbx_filename"];

if ($sbx_title && $sbx_catalogue) {
	// move the file to soundbox itself
	//  (copy file to \\sbxstudio1\AudioImports
	// Add the Cart to the soundbox database with all the bits
	//  CartIndex incremental
	//  BPM == blank
	//  Catalogue == $sbx_catalogue
	//  FadeTime == blank
	//  Filename == $sbx_filename
	//  File Location == 54 (AudioImports)
	//  Flags == blank
	//  Energy == blank
	//  Extro == 0
	//  ExtIndex == blank
	//  HWControl == blank
	//  Intro == 0
	//  IOControl == 0
	//  Length == (calculate?)
	//  Notes == blank
	//  SBxAudio == blank
	//  Start == 0
	//  Sway == blank
	//  Temp == blank
	//  Title == $sbx_title
	//  UserIndex == blank
	//  Volume == 100

if(!$pxdoc_write = px_new()) {
echo "FAIL<BR>\n";
};
//$fp_write = fopen("/mnt/soundbox/Data/Cart.DB", "r+");
$fp_write = fopen("/var/www/sbschedule/local_soundbox/Cart.DB", "r+");
if(!px_open_fp($pxdoc_write, $fp_write)) {
        echo "Error opening /mnt/soundbox/Data/Cart.DB for get_all_carts()<BR>\n";
        die();
}
	$table_info=px_get_info($pxdoc_write);
	$num_records=$table_info['numrecords'];
	$num_records=$num_records-1;
	// last record retrieve and increment
	$px_cart_info=px_get_record($pxdoc_write,$num_records);
	$last_cartindex=$px_cart_info['CartIndex'];
	$new_cartindex=$last_cartindex+1;

	// process and check the MP3 to retrieve the time in seconds kinda thing
	$mp3_lengthtime=rtrim(`/usr/bin/checkmp3 -p /var/www/sbschedule/scripts/upload/uploadedfiles/$sbx_filename 2>&1 |grep LENGTH | awk '{print $10; }' | sed 's/://i' | sed 's/\.//i'`);

	// move the file now
	$mp3_mover=@rename("/var/www/sbschedule/scripts/upload/uploadedfiles/$sbx_filename", "/mnt/audioimports/$sbx_filename");

	$new_details_array=array('CartIndex'=>"$new_cartindex",'BPM'=>'','Catalogue'=>"$sbx_catalogue",'FadeTime'=>'','Filename'=>"$sbx_filename",'FileLocation'=>54,'Flags'=>'','Energy'=>'','Extro'=>0,'ExtIndex'=>'','HWControl'=>'','Intro'=>0,'IOControl'=>0,'Length'=>"$mp3_lengthtime",'Notes'=>'','SBxAudio'=>'','Start'=>0,'Sway'=>'','Tempo'=>'','Title'=>"$sbx_title",'UserIndex'=>'','Volume'=>100);

// echo "<BR><B>NOT INSERTING!</B><BR>Array value:<BR><PRE>\n";print_r($new_details_array);echo "</PRE>\n<BR>\n";
	$do_insert=px_put_record($pxdoc_write,$new_details_array);
	echo "INSERT returns: $do_insert<BR>\n";
	px_close($pxdoc_write);
	px_delete($pxdoc_write);
	fclose($fp_write);

	
};
?>
<script language=Javascript>
var formval='';
function dosbxtitle(formval) {
	var newval=formval.substring(0, formval.length-4);
	document.forms[1].sbx_title.value=newval;
	document.forms[1].sbx_filename.value=formval;
};
</script>
<table width=100% border=1 cellpadding=0 cellspacing=0>
<tr><td width=100%>
<u>Upload audio to SoundBox</u><BR><BR>
<BR>
Upload audio files to soundbox by clicking on BROWSE, then find the file on your computer and click OPEN/SEND.<BR>
The Green progress bar to the right of your file will show, and when the bar is fully green it is complete.<BR>
Once the file is uploaded, type your Title and select the Catalogue, then click on 'Store in SoundBox'<BR>
<BR>
<div style="width: 800px; margin: 2em auto;">
  <div style="width: 40em; border: 3px ridge #ff0000; padding: .5em">
    <?php for ($i=0; $i < $upload_fields; $i++) { ?>
      <form METHOD="POST" enctype="multipart/form-data" name="form<?php echo $i; ?>" id="form<?php echo $i; ?>" action="./scripts/upload/upload.cgi?sID=<?php echo $session; ?>form<?php echo $i; ?>" target="form<?php echo $i; ?>_iframe">
    	  <div class="progressBox"><div class="progressBar" id="<?php echo $session . 'form' .$i.'_progress'; ?>">&nbsp;
</div></div>
        <div class="fileName" id="<?php echo $session . 'form' .$i.'_fileName'; ?>"></div>
        <input style="" type="file" name="file<?php echo $i; ?>" onchange="uploadForm('<?php echo 'form'.$i; ?>', '<?php echo $session . 'form' .$i; ?>');dosbxtitle(document.forms[0].file0.value);" />
    	</form>
    	<iframe name="form<?php echo $i; ?>_iframe" id="form<?php echo $i; ?>_iframe" src="scripts/upload/blank.html" class="loader"></iframe> 
  	<?php } ?>
<form method=post>
<input type=hidden name='sbx_filename'>
        <table border=0 cellpadding=1>
        <tr><td>Title:</td><td><input type=text name='sbx_title' id='sbx_title' size=50></td>
        <tr><td>Catalogue:</td><td><select name='sbx_catalogue'>
<option value=''></option>
<?php
$sbx_cataloguearray=php_multisort($sbx_cataloguearray, array(array('key'=>'Description')));
foreach ($sbx_cataloguearray as $out_id => $out_value) {
	echo "<option value='".$out_value['Catalogue']."'>".$out_value['Description']."</option>\n";
};
?>
</select></tr>
        <tr><td colspan=2 align=right><input type=submit name=submit value='Store in SoundBox'></td></tr>
        </table>
</form>    
  </div>
</div>
</td></tr>
</table>
<?php
footer_display();
?>
