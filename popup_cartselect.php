<?php

//      sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

include_once("functions.php");

?>
<HTML>
<HEAD>
<style type="text/css">
<!--
div.scroll {
height: 200px;
width: 300px;
overflow: auto;
border: 1px solid #666;
background-color: #ccc;
padding: 8px;
}
-->
</style>
<script>
var preEl ;
var orgBColor;
var orgTColor;
function HighLightTR(el, backColor,textColor){
  if(typeof(preEl)!='undefined') {
     preEl.bgColor=orgBColor;
     try{ChangeTextColor(preEl,orgTColor);}catch(e){;}
  }
  orgBColor = el.bgColor;
  orgTColor = el.style.color;
  el.bgColor=backColor;

  try{ChangeTextColor(el,textColor);}catch(e){;}
  preEl = el;
}


function ChangeTextColor(a_obj,a_color){  ;
   for (i=0;i<a_obj.cells.length;i++)
    a_obj.cells(i).style.color=a_color;
}
var current_highlight="";
function return_entry() {
	if (current_highlight == "") {
		alert('No cart has been highlighted. Click on the cart to add');
	} else {
		alert(current_highlight);
	};
};
</script>
</HEAD>
<BODY>

<form method=post name='popform'>
<?php
get_all_carts();
get_all_artists();
get_all_artilink();
get_all_catalogues();

foreach ($_GET as $key => $value) {
            if ($key != "C") {  // ignore this particular $_GET value
                $querystring .= $key."=".$value;
            }
};
$cat=$_REQUEST["cat"];
$return_boxname=$_REQUEST["return_boxname"];
?>
<table width=100% border=0 cellpadding=0 cellspacing=0>
<tr><td>&nbsp;</td><td><input type=button value='Artists' onclick='self.location="popup_cartselect.php?carttype=artists";'>
<input type=button value='Titles' onclick='self.location="popup_cartselect.php?carttype=titles";'>
<input type=button value='Length' onclick='self.location="popup_cartselect.php?carttype=length";'>
<input type=button value='Jingle' onclick='self.location="popup_cartselect.php?carttype=jingle";'>
<input type=button value='Other' onclick='self.location="popup_cartselect.php?carttype=other";'>
</td></tr>
<tr><td width=200 align=top valign=top>
<div class="scroll">
<table border=1 width=100%>
<?php
                        $sbx_cartarray=php_multisort($sbx_cartarray, array(array('key'=>'Title')));
                        foreach ($sbx_cartarray as $out_id => $out_value) {
                                if ($out_value['Catalogue'] == $cat) {
                                        $artist_id=artistlookup($out_value['CartIndex']);
                                        if ($sbx_artistarray[$artist_id]['ArtistName']) {
                                                echo "<tr onClick=\"current_highlight='".$out_value['CartIndex']."';alert(current_highlight);HighLightTR(this,'#c9cc99','cc3333');\"><td>".$out_value['Title']."<BR>".$sbx_artistarray[$artist_id]['ArtistName']."</td></tr>\n";
                                        } else {
                                                echo "<tr onClick=\"current_highlight='".$out_value['CartIndex']."';alert(current_highlight);HighLightTR(this,'#c9cc99','cc3333');\"><td>".$out_value['Title']."</td></tr>\n";
                                        };
                                };
                        };
?>
</table>
</div> 
</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<table border=0>
<?php
$sbx_cataloguearray_sort=php_multisort($sbx_cataloguearray, array(array('key'=>'Description')));
foreach ($sbx_cataloguearray_sort as $indid => $indarray) {
	// print_r($indarray);
	if (($_REQUEST["carttype"] == "jingle") && ($indarray['CartType'] == "J" || $indarray['CartType'] == "S") || ($_REQUEST["carttype"] == "artists") && ($indarray['CartType'] == "M") || ($_REQUEST["carttype"] == "titles") && ($indarray['CartType'] == "M") || ($_REQUEST["carttype"] == "length") && ($indarray['CartType'] == "M") || ($_REQUEST["carttype"] == "other") && ($indarray['CartType'] == "O")) {
		echo "<tr><td><input type=button onclick='self.location=\"popup_cartselect.php?cat=".$indarray['Catalogue']."&carttype=".$_REQUEST["carttype"]."\";' value=\"".$indarray['Description']."\"></td></tr>\n";
	};
};
?>
</table>
</td></tr>
</table>
<center>
<input type=button name=submit value='Add highlighted' onclick="Javascript:return_entry();">
</center>
</BODY>
</HTML>
