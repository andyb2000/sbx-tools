<?php

//	Functions for sb-schedule+ by Andy Brown (C) 2008 andy@broadcast-tech.co.uk
// ----------------------------------------------------------------------------------------

//  header for html generation

function header_display() {
?>
<html>
<head>
<title>SB-Schedule+ by Andy Brown</title>
<style type="text/css">

Body {font-family : "Courier New", Courier, "Andale Mono", Monospace; background-color : #000000; color : #FFFFFF; }

A:link {color : #FF0000; font-weight : bold; text-decoration : underline; background : #; font-size : 8pt;}

A:visited {color : #FF0000; font-weight : bold; text-decoration : underline; background : #; font-size : 8pt;}

A:hover {color : #00FFFF; font-weight : bold; text-decoration : overline; background : #; font-size : 8pt;}

A:active {color : #FF0000; font-weight : bold; text-decoration : underline; background : #; font-size : 8pt;}

</style>
<link rel="stylesheet" href="calendar/forest.css">
<script language="JavaScript" src="html/gen_validatorv31.js" type="text/javascript"></script>
<script type="text/javascript" src="calendar/zapatec.js"></script>
<script type="text/javascript" src="calendar/calendar.js"></script>
<script type="text/javascript" src="calendar/calendar-en.js"></script>

<link href="scripts/upload/upload.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="scripts/upload/json_c.js"></script>
<script type="text/javascript" src="scripts/upload/upload_form.js"></script>
<script type="text/javascript" src="scripts/upload/sr_c.js"></script>

</head>
<?php
};

?>
