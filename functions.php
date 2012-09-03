<?php

//	Functions for sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

$dbhost = "localhost";
$dbusername = "admin";
$dbpassword = "atomic";
$dbdb = "sbschedule";

$studio1="192.168.2.194";
$studio2="192.168.2.179";
$server="";

// hourclocks use the Catalogue number from Cat.DB prefixed with a C
// or the individual cart prefixed with a S
//  258 == sweepers
//  34988 == commercial break 1
//  34967 == commercial break 2
//  34842 == commercial break 3
//  35033 == commercial break 4
//  34968 == commercial break 5
//  34844 == commercial break 6
//  35971 == commercial break 7
//  35972 == commercial break 8
//  35973 == commercial break 9
//  35974 == commercial break 10
//  35975 == commercial break 11
//  35976 == commercial break 12
//  L == stopbar

$hourclock1="C96,S38696,C70,C258,C63,L,S34988,jingle,C62,L,C70,L,S34967,jingle,C96,C258,C243,L,C63,sw,C59,L,S34842,jingle,C62,C258,C63,L,C243";
$hourclock2="";
$hourclock3="";
$hourclock4="";

function logincheck() {
	// check if a users cookie is set and they are a valid user in our db
	global $db,$script;
	if (!$script) {
	$read_session=$_COOKIE["sbschedule"];
	if ($read_session) {
		$check_db=mysql_query("select * from adminusers where username='$read_session'");
		if (mysql_num_rows($check_db) <> 0) {
			$get_username=mysql_fetch_array($check_db);
			$load_username=$get_username["username"];
			// $_SESSION["sbschedule"]=$load_username;
			setcookie("sbschedule", $load_username);
		} else {
			login_page();
		};
	} else {
		login_page();
	};
	};
};

function login_page() {
	global $db,$script;
	if (!$script) {
	include_once("html/login.php");
	exit;
	};
};

function db_connect() {
        global $db,$dbhost,$dbusername,$dbpassword,$dbdb,$dbtype,$debug;
        $db = mysql_connect($dbhost, $dbusername, $dbpassword) or errordie("Error with connection to database - FATAL PROBLEM, please contact support.");
        mysql_select_db($dbdb,$db) or errordie("Error with selection of database - FATAL PROBLEM, please contact support.");
        if($debug) {echo "DEBUG> database connected<BR>";};
};

function querydb($query) {
// queries database and returns an array of results if applicable
global $db;
        $getdata=mysql_query($query,$db) or errordie("Failed to query db ('$query') : ".mysql_error());
        if (mysql_num_rows($getdata) <> 0) {
                $getarray=mysql_fetch_array($getdata);
        } else {
                $getarray="";
        };
return $getarray;
};

function dbdirect($query) {
global $db;
// queries database and returns array handle for results processing
        $getdata=mysql_query($query,$db) or errordie("Failed to query db ($query) : ".mysql_error());
        return $getdata;
};

function encodevariable($varin) {
// encodes a variable for safe entry to databases
        $varout=urlencode($varin);
        return $varout;
};

function decodevariable($varin) {
// decodes a variable from databases
        $varout=urldecode($varin);
        return $varout;
};

function humandate($varin) {
// converts mysql to human  (yy-mm-dd input and dd-mm-yy output)
        list($yy,$mm,$dd)=split("-",$varin);
        $varout=$dd."-".$mm."-".$yy;
        return $varout;
};

function mysqldate($varin) {
// converts human to mysql (dd-mm-yy input and yy-mm-dd output)
        list($dd,$mm,$yy)=split("-",$varin);
        $varout=$yy."-".$mm."-".$dd;
        return $varout;
};

function errordie($error) {
global $db,$debug,$GLOBALS;
        // we bugged out. tell the user what went wrong, contact somebody then go to peepies.
        echo "<HTML><BODY bgcolor='#ffffff'><BR>";
        echo "<table width=100% border=0 cellpadding=0 cellspacing=0><tr><td>A-Comm Web Hosting</td><td align=center><B>Radio Hartlepool</B></td><td align=right>&nbsp;</td></tr></table><BR><center>";
        echo "<h2>There was a major problem</h2><BR>We could not perform this operation.<BR>The exact error messageis shown below, please contact Andy Brown (<a href='mailto:andy@radiohartlepool.co.uk";
        echo "'>andy@radiohartlepool.co.uk</a>";
        echo ")<BR><BR>";
        echo "<hr width=98%><BR><font color=red>Error : $error</font><BR><hr width=98%><BR>";
        echo "<BR><BR><a href='/'>Click here to return to the homepage for this site</a><BR>";
        echo "</CENTER></BODY></HTML>\n";
        die(); //goooooo bye bye
if($debug) {echo "DEBUG> errordiefunction completed- you will NEVER see this<BR>";};
};


function dircopy($src_dir, $dst_dir,$UploadDate=false, $verbose = false, $use_cached_dir_trees = false)
    {  
        static $cached_src_dir;
        static $src_tree;
        static $dst_tree;
        $num = 0;

        if(($slash = substr($src_dir, -1)) == "\\" || $slash == "/") $src_dir = substr($src_dir, 0, strlen($src_dir) - 1);
        if(($slash = substr($dst_dir, -1)) == "\\" || $slash == "/") $dst_dir = substr($dst_dir, 0, strlen($dst_dir) - 1);
        if (!$use_cached_dir_trees || !isset($src_tree) || $cached_src_dir != $src_dir)
        {
            $src_tree = get_dir_tree($src_dir,true,$UploadDate);
            $cached_src_dir = $src_dir;
            $src_changed = true;
        }
        if (!$use_cached_dir_trees || !isset($dst_tree) || $src_changed)
            $dst_tree = get_dir_tree($dst_dir,true,$UploadDate);
        if (!is_dir($dst_dir)) mkdir($dst_dir, 0777, true);

          foreach ($src_tree as $file => $src_mtime)
        {
            if (!isset($dst_tree[$file]) && $src_mtime === false)
                mkdir("$dst_dir/$file");
            elseif (!isset($dst_tree[$file]) && $src_mtime || isset($dst_tree[$file]) && $src_mtime > $dst_tree[$file]) 
            {
                if (copy("$src_dir/$file", "$dst_dir/$file"))
                {
                    if($verbose) echo "Copied '$src_dir/$file' to '$dst_dir/$file'<br>\r\n";
                    touch("$dst_dir/$file", $src_mtime);
                    $num++;
                } else
                    echo "<font color='red'>File '$src_dir/$file' could not be copied!</font><br>\r\n";
            }      
        }
        return $num;
    }

    function get_dir_tree($dir, $root = true,$UploadDate)
    {
        static $tree;
        static $base_dir_length;
     
        if ($root)
        {
            $tree = array();
            $base_dir_length = strlen($dir) + 1;
        }

        if (is_file($dir))
        {
           if($UploadDate!=false)
            {
                   if(filemtime($dir)>strtotime($UploadDate))
                    $tree[substr($dir, $base_dir_length)] = date('Y-m-d H:i:s',filemtime($dir));   
            }
            else
                $tree[substr($dir, $base_dir_length)] = date('Y-m-d H:i:s',filemtime($dir));
        }
        elseif ((is_dir($dir) && substr($dir, -4) != ".svn") && $di = dir($dir) )
        {
            if (!$root) $tree[substr($dir, $base_dir_length)] = false;
            while (($file = $di->read()) !== false)
                if ($file != "." && $file != "..")
                    get_dir_tree("$dir/$file", false,$UploadDate);
            $di->close();
        }
        if ($root)
            return $tree;   
    }



// paradox like functions

function get_all_carts() {
	global $sbx_cartarray;
$sbx_cartarray=array();
if(!$pxdoc = px_new()) {
echo "FAIL<BR>\n";
};
$fp = fopen("/mnt/soundbox/Data/Cart.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
	echo "Error opening /mnt/soundbox/Data/Cart.DB for get_all_carts()<BR>\n";
	die();
}
$table_info=px_get_info($pxdoc);
$num_of_records=$table_info['numrecords'];
$tmploop=0;
while ($tmploop<$num_of_records) {
	// loading in all the records
	array_push($sbx_cartarray,@px_get_record($pxdoc,$tmploop));
	$tmploop++;
};
// print_r(px_get_record($pxdoc,5));
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);
};

function multidimArrayLocate($array, $text){
  foreach($array as $key => $arrayValue){
    if (is_array($arrayValue)){
      if ($key == $text) $arrayResult[$key] = $arrayValue;
      $temp[$key] = multidimArrayLocate($arrayValue, $text);
      if ($temp[$key]) $arrayResult[$key] = $temp[$key];
    }
    else{
      if ($key == $text) $arrayResult[$key] = $arrayValue;
      if ($arrayValue == $text) $arrayResult[$key] = $arrayValue;
    }
  }
  return $arrayResult;
}

function array_search_recursive($needle, $haystack)
  {    
    foreach ($haystack as $k => $v)
    {
      for ($i=0; $i<count($v); $i++)
        if ($v[$i] === $needle || $k[$i] === $needle)
        {
          return true;
          break;
        }
    }
  }

function lookupcartindex($cartindex) {
	global $sbx_cartarray;
unset($foundentry);
foreach ($sbx_cartarray as $indid => $indarray) {
      if ($indarray['CartIndex'] == $cartindex) {
		$foundentry=$indid;
      };
};
return $foundentry;
};

function get_all_filelocations() {
	//FileLoc.DB
        global $sbx_filelocations;
$sbx_filelocations=array();
if(!$pxdoc = px_new()) {
echo "FAIL<BR>\n";
};
$fp = fopen("/mnt/soundbox/Data/FileLoc.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
        echo "Error opening /mnt/soundbox/Data/FileLoc.DB for get_all_filelocations()<BR>\n";
        die();
}
$table_info=px_get_info($pxdoc);
$num_of_records=$table_info['numrecords'];
$tmploop=0;
while ($tmploop<$num_of_records) {
        // loading in all the records
        array_push($sbx_filelocations,@px_get_record($pxdoc,$tmploop));
        $tmploop++;
};
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);
};

function get_all_artists() {
	global $sbx_artistarray;
$sbx_artistarray=array();
if(!$pxdoc = px_new()) {
echo "FAIL<BR>\n";
};
$fp = fopen("/mnt/soundbox/Data/Artist.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
        echo "Error opening /mnt/soundbox/Data/Artist.DB for get_all_carts()<BR>\n";
        die();
}
$table_info=px_get_info($pxdoc);
$num_of_records=$table_info['numrecords'];
$tmploop=0;
while ($tmploop<$num_of_records) {
        // loading in all the records
        array_push($sbx_artistarray,@px_get_record($pxdoc,$tmploop));
        $tmploop++;
};
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);
};

function get_all_artilink() {
	global $sbx_artilink;
$sbx_artilink=array();
if(!$pxdoc = px_new()) {
echo "FAIL<BR>\n";
};
$fp = fopen("/mnt/soundbox/Data/ArtiLink.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
        echo "Error opening /mnt/soundbox/Data/ArtiLink.DB for get_all_carts()<BR>\n";
        die();
};
$table_info=px_get_info($pxdoc);
$num_of_records=$table_info['numrecords'];
$tmploop=0;
while ($tmploop<$num_of_records) {
        // loading in all the records
        array_push($sbx_artilink,px_get_record($pxdoc,$tmploop));
        $tmploop++;
};
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);
};

function artistlookup($cartindex) {
	global $sbx_cartarray,$sbx_artilink,$sbx_artistarray;
	// we have been passed the CartIndex for a track, so use the artilink to find the artist entry then get details from artistarray
unset($foundentry);
foreach ($sbx_artilink as $indid => $indarray) {
      if ($indarray['CartIndex'] == $cartindex) {
                $foundentry=$indid;
      };
};
$a_artilink=$sbx_artilink[$foundentry]['ArtistIndex'];
unset($foundentry);
foreach ($sbx_artistarray as $indid => $indarray) {
      if ($indarray['ArtistIndex'] == $a_artilink) {
                $foundentry=$indid;
      };
};

// echo "ARTISTLOOKUP: $foundentry<BR>\n";

return $foundentry;
};

function get_all_catalogues() {
	global $sbx_cataloguearray;
$sbx_cataloguearray=array();
if(!$pxdoc = px_new()) {
echo "FAIL<BR>\n";
};
$fp = fopen("/mnt/soundbox/Data/Cat.DB", "r");
if(!px_open_fp($pxdoc, $fp)) {
        echo "Error opening /mnt/soundbox/Data/Cat.DB for get_all_catalogues()<BR>\n";
        die();
}
$table_info=px_get_info($pxdoc);
$num_of_records=$table_info['numrecords'];
$tmploop=0;
while ($tmploop<$num_of_records) {
        // loading in all the records
        array_push($sbx_cataloguearray,@px_get_record($pxdoc,$tmploop));
        $tmploop++;
};
px_close($pxdoc);
px_delete($pxdoc);
fclose($fp);
};

function catlookup($catindex) {
global $sbx_cartarray,$sbx_cataloguearray;
unset($foundentry);
foreach ($sbx_cataloguearray as $indid => $indarray) {
	if ($indarray['Catalogue'] == $catindex) {
		$foundentry=$indid;
	};
};
return $foundentry;
};

// lookup the catalogue a cart belongs in from the cartid only
function cataloguelookup($ctindex) {
global $sbx_cartarray,$sbx_cataloguearray;
unset($foundentry);
$get_cat=$sbx_cartarray[$ctindex];
foreach ($sbx_cartarray as $out_id => $out_value) {
	if ($out_value['CartIndex'] == $ctindex) {
		$catid_lk=$out_value['Catalogue'];
	};
};

foreach ($sbx_cataloguearray as $indid => $indarray) {
//      if ($indarray['Catalogue'] == $catindex) {
	if ($indarray['Catalogue'] == $catid_lk) {
                $foundentry=$indid;
      };
};
//$a_artilink=$sbx_cataloguearray[$foundentry]['ArtistIndex'];

// echo "ARTISTLOOKUP: $foundentry<BR>\n";

return $foundentry;

};

function chomp(&$string)
{
        if (is_array($string))
        {
                foreach($string as $i => $val)
                {
                        $endchar = chomp($string[$i]);
                }
        } else {
                $endchar = substr("$string", strlen("$string") - 1, 1);
                if ($endchar == "\n")
                {
                        $string = substr("$string", 0, -1);
                }
        }
        return $endchar;
} 



function php_multisort($data,$keys){
  // List As Columns
  foreach ($data as $key => $row) {
    foreach ($keys as $k){
      $cols[$k['key']][$key] = $row[$k['key']];
    }
  }
  // List original keys
  $idkeys=array_keys($data);
  // Sort Expression
  $i=0;
  foreach ($keys as $k){
    if($i>0){$sort.=',';}
    $sort.='$cols['.$k['key'].']';
    if($k['sort']){$sort.=',SORT_'.strtoupper($k['sort']);}
    if($k['type']){$sort.=',SORT_'.strtoupper($k['type']);}
    $i++;
  }
  $sort.=',$idkeys';
  // Sort Funct
  $sort='array_multisort('.$sort.');';
  eval($sort);
  // Rebuild Full Array
  foreach($idkeys as $idkey){
    $result[$idkey]=$data[$idkey];
  }
  return $result;
} 

db_connect();
logincheck();
include_once("html/header.php");
include_once("html/footer.php");
include_once("html/body.php");

?>
