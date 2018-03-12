#!/usr/bin/php
<?php

//	sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

$script=1;

include_once("functions.php");

header_display();
body_display();
menu_display();
?>
A and B list file copy and song listing

<?php

if(!$pxdoc = px_new()) {
	echo "Failed to load paradox driver, contact support!<BR>\n";
	die();
}

get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();
get_all_filelocations();

$dothis=`mkdir /tmp/song-copy`;

$sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));
foreach ($sbx_cartarray as $out_id => $out_value) {
//	echo "test: ".$out_value['Catalogue']." <BR>\n";
//	echo "test: ".$out_value['Title']." <BR>\n";
	if ($out_value['Catalogue'] == "491") {
		$artist_id=artistlookup($out_value['CartIndex']);
		$filepath=$out_value['FileLocation'];
		$file_locis=$sbx_filelocations[$filepath];
		foreach ($sbx_filelocations as $my_out_id => $my_out_value) {
			if ($my_out_value["FileLocation"] == $filepath) {
				$out_path=$my_out_value["Directory"];
			};
		};
		echo $out_value['Title']."  -  ".$sbx_artistarray[$artist_id]['ArtistName']." ($out_path".$out_value['Filename'].")<BR>\n";
		$file_outis=$out_path.$out_value['Filename'];
		//$docopy=`cp "$fileoutis
		$escp_path=str_replace("\\", "\\\\", $out_path);
		$escp_filename=$out_value['Filename'];
		$escp_outfile=$out_value['Title']." - ".$sbx_artistarray[$artist_id]['ArtistName'].".mp3";
		echo "TEST: $escp_path $escp_outfile\n";
		$docopy=`cd /tmp/song-copy;smbclient -N "$escp_path" <<EOC
get $escp_filename "$escp_outfile"
EOC`;
	};
};
echo "Starting scp transfer to geostat\n";
//                $doscp=`scp -vq /tmp/song-copy/* root@geostat.thebmwz3.co.uk:/media/ABDS-root/music/Latest-RHPOOL/`;
$dorsync=`/usr/bin/rsync -avzxH -e ssh /tmp/song-copy/* root@geostat.thebmwz3.co.uk:/media/ABDS-music/10s/ 2>&1`;
$dochmod=`/usr/bin/ssh root@geostat.thebmwz3.co.uk "chmod 777 /media/ABDS-music/10s/*"`;

?>
