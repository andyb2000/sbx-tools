<?php

//      sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");

?>
<form method=post name='schedulemusicform'>
<input type=hidden name='action' value='<?=$_REQUEST["action"]?>'>
<center><b>Edit Saved Runorder (From Soundbox)</b></center><BR>
<?php
get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();

$load_runorder=$_REQUEST["runorderfilename"];
?>
Select a runorder that is saved in SoundBox: <select name=runorderfilename>
<option value=''></option>
<?php
	if ($dh = opendir("/mnt/soundbox/Data/ProdSave/")) {
        while (($file = readdir($dh)) !== false) {
		if ($file != "." && $file != "..") {
			list($sblistentry,$ext)=split(".sbd",$file);
	            echo "<option value='$file' ";
			if ($load_runorder == $file) {echo "selected";};
		    echo ">$sblistentry</option>\n";
		};
        }
        closedir($dh);
	};
?>
</select>&nbsp;<input type=submit name=submitfilename value='Load'>
<input type=submit name=csv value='CSV'>
<BR><BR>
<table border=1 cellpadding=2 cellspacing=1 width=85%>
<tr><td><center>
<?php
if ($_REQUEST["csv"]) {
	echo "category,song,artist,additional<BR>\n";
} else {
?>
<table border=1 cellpadding=1 cellspacing=1 width=85%>
<?php
};
if ($load_runorder) {
	$loadfile="/mnt/soundbox/Data/ProdSave/$load_runorder";
	$runorder_lines=file($loadfile);
	foreach ($runorder_lines as $line_num => $line_entry) {
		$line_entry=rtrim($line_entry);
		$output_line="";
		if (strpos($line_entry,"C,") !== false) {
			list($junk,$hexentry)=split(",",$line_entry);
			$dec_number=hexdec($hexentry);
//			$output_line.="<b>$line_num : $line_entry ($dec_number)</b><BR>\n";
			$foundcart=lookupcartindex($dec_number);
			if ($foundcart) {
				$cat_id=$sbx_cartarray[$foundcart]['Catalogue'];
				$catal_index=catlookup($cat_id);
				$skin_number=$sbx_cataloguearray[$catal_index]['CartSkinNumber'];
				$catal_name=$sbx_cataloguearray[$catal_index]['Description'];
				$artist_id=artistlookup($dec_number);
if ($_REQUEST["csv"]) {
	$output_line.="";
} else {
				if ($skin_number == "0") {
					$output_line.="<tr bgcolor='#C0C0C0'>";
				} elseif($skin_number == "4") {
					$output_line.="<tr bgcolor='#33CC66'>";
				} elseif($skin_number == "5") {
					// Jingles
                                        $output_line.="<tr bgcolor='#0000CC'>";
                                } elseif($skin_number == "6") {
					// 00s
                                        $output_line.="<tr bgcolor='#99CCFF'>";
                                } elseif($skin_number == "9") {
					// jingles
                                        $output_line.="<tr bgcolor='#FF6600'>";
                                } elseif($skin_number == "2") {
					// 80s
                                        $output_line.="<tr bgcolor='#FFC7E7'>";
				} else {
					$output_line.="<tr>";
				};
};
if($_REQUEST["csv"]) {
	$output_line.="$catal_name,".$sbx_cartarray[$foundcart]['Title'].",".$sbx_artistarray[$artist_id]['ArtistName'].",''<BR>\n";
} else {
				$output_line.="<td>($catal_name)&nbsp;[$hexentry]&nbsp;[$dec_number]<BR>";
				$output_line.=$sbx_cartarray[$foundcart]['Title']."<BR>".$sbx_artistarray[$artist_id]['ArtistName']."<BR>\n</td>\n";
};
			} else {
if($_REQUEST["csv"]) {
	$output_line.="$line_num,$line_entry,$dec_number<BR>\n";
} else {
				$output_line.="<tr><td>$line_num : '$line_entry' ($dec_number)<BR>\n</td>";
};
			};
		};
		if(strpos($line_entry,"L") !== false) {
if($_REQUEST["csv"]) {
	$output_line.=",,,STOP<BR>\n";
} else {
			$output_line.="<tr bgcolor='red'><td>$line_num : $line_entry&nbsp;STOP</td>\n";
};
		};
		if (!$output_line) {
			echo "<input type=hidden name='line[$line_num]' value='$line_entry'>\n";
//			echo "<tr><td>";
//			echo "$line_num : '$line_entry'<BR>\n";
//			echo "</td></tr>\n";
		} else {
			echo "$output_line\n";
if(!$_REQUEST["csv"]) {
//			echo "<td align=center><input type=button value='Delete' onclick='Javascript:if (confirm(\"Are you sure to delete?\")) {self.location=\"/sbschedule/scheduling.php?action=editrunorder&runorderfilename=$load_runorder&runaction=delete&line=$line_num\";};'>&nbsp;";
//			echo "<input type=button value='up' onclick='self.location=\"/sbschedule/scheduling.php?action=editrunorder&runorderfilename=$load_runorder&runaction=up&line=$line_num\";'>&nbsp;";
//			echo "<input type=button value='down' onclick='self.location=\"/sbschedule/scheduling.php?action=editrunorder&runorderfilename=$load_runorder&runaction=down&line=$line_num\";'>";
			echo "</td></tr>\n";
			echo "<input type=hidden name='line[$line_num]' value='$line_entry'>\n";
};
		};
	};
?>
<tr>
	<td colspan=2>
		<input type=button value='Add'>
<?php
echo "<select name='new_catalogue' onchange='document.forms[0].submit();'><option value=''></option>\n";
                        // load in a list of catalogues first
                        $sbx_cataloguearray=php_multisort($sbx_cataloguearray, array(array('key'=>'Description')));
                        foreach ($sbx_cataloguearray as $out_id => $out_value) {
                                echo "<option value='".$out_value['Catalogue']."' ";
                                if ($_REQUEST["new_catalogue"] == $out_value['Catalogue']) {echo "selected";};
                                echo ">".$out_value['Description']."</option>\n";
                        };
                echo "</select>";
                if ($_REQUEST["new_catalogue"]) {
                        // Load in all the carts connected with this catalogue
                        echo "&nbsp;<select name='new_cartindex'><option value=''>None/Random Selection</option>\n";
                        $sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));
                        foreach ($sbx_cartarray as $out_id => $out_value) {
                                if ($out_value['Catalogue'] == $_REQUEST["new_catalogue"]) {
                                        echo "<option value='".$out_value['CartIndex']."' ";
                                        if ($_REQUEST["new_cartindex"] == $out_value['CartIndex']) {echo "selected";};
                                        $artist_id=artistlookup($out_value['CartIndex']);
                                        if ($sbx_artistarray[$artist_id]['ArtistName']) {
                                                echo ">".$out_value['Title']."  -  ".$sbx_artistarray[$artist_id]['ArtistName']."</option>\n";
                                        } else {
                                                echo ">".$out_value['Title']."</option>\n";
                                        };
                                };
                        };
                        echo "</select>\n";
                };
?>
	</td>
</tr>
</td></tr></table>
</td></tr></table>

<?php
};
?>

<BR>
</form>
