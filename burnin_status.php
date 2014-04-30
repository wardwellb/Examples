<?php

header("Cache-Control: no-store, no-cache, must-revalidate");
include ($_SERVER["DOCUMENT_ROOT"]."/teweb/scripts/common.php");
include("burnin-functions.php");

echo '
	<html>
    <head>
    <title>Test Engineering DC-DC Burnin Single Screen View</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" href="http://'  . $_SERVER['HTTP_HOST'] . '/teweb/style/style1.css" type="text/css">
    <script language="JavaScript" src="http://'  . $_SERVER['HTTP_HOST'] . '/teweb/scripts/vysutil.js"></script>

    <style type=\'text/css\'>

    #dhtmltooltip{
    position: absolute;
    width: 240px;
    border: 2px solid black;
    padding: 2px;
    background-color: white;
    visibility: hidden;
    z-index: 100;
    }
	
    </style>
    <script type="text/javascript">
    function hideLoadingMessage(){
      document.getElementById(\'spnLoading\').style.display = \'none\'
    }
    </script>
    <meta http-equiv="refresh" content="300";url="index.php">

    </head>';
	echo '<body bgcolor="#FFFFFF" text="#000000" background="http://' . $_SERVER['HTTP_HOST'] . '/teweb/graphics/tewebdev.jpg">';
	echo '<div id="dhtmltooltip"></div>';
    echo '<script language="JavaScript" src="http://' . $_SERVER['HTTP_HOST'] . '/teweb/scripts/tooltip.js"></script>';

set_time_limit(120);


  global $hr_limit;

  echo "<span id='spnLoading' style=' display:inline;'>";
  echo "<center><font size=3><b>Loading...please wait</b></font>";
  echo "</center>";
  echo "<p>&nbsp;</p>";
  echo "</span>";
    ob_flush();
    flush();
	
  //color constants
  $fail_color = "red";
  $pass_color = "green";
  $running_color = "#0099FF";     //blue
  $incomplete_color = "#666666"; //dark-grey
  $unload_color = "yellow";


  
foreach($burnin_tester_names as $tester){
	$array = process_burnin_file("//Verdi2/".$tester."/Burnin.xml");
	if (!$doc_array[$tester]) $doc_array[$tester] = array();
	$burnin_summary_array[$tester] = array();
	$burnin_summary_array[$tester] = $array;
}

foreach($acburnin_tester_names as $tester){
    $array = process_acburnin_file("//Verdi2/".$tester."/Burnin.xml");
    if (!$doc_array[$tester]) $doc_array[$tester] = array();
    $acburnin_summary_array[$tester] = array();
	$acburnin_summary_array[$tester] = $array;
}
	
foreach($ibcburnin_tester_names as $tester){
    $array = process_ibcburnin_file("//Verdi2/".$tester."/IBC.xml");
	if (!$doc_array[$tester]) $doc_array[$tester] = array();
    $ibcburnin_summary_array[$tester] = array();
    $ibcburnin_summary_array[$tester] = $array;
}

  $now = date("D M j H:i:s Y", time());
  //create legend table
  $w_leg_col = 15;
  $h_leg_col = 1;
  echo "<table border=0 align='center' cellspacing=2 cellpadding=2>\n";
  echo "<tr><td align='center' style='font-size:0.8em'><b>Last Update: $now</b></td></tr>\n";
  echo "<tr><td>\n";
  echo "<table bgcolor= '#E9E9E9' align='left' border='0' cellspacing=2 cellapdding=2>\n";
  echo "  <tr >\n";
  echo "    <td width='$w_leg_col' bgcolor='$running_color'>&nbsp</td>\n";
  echo "    <td nowrap style='font-size:0.8em'>Running</td>\n";
  echo "    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
  echo "    <td width='$w_leg_col' bgcolor='$pass_color'>&nbsp</td>\n";
  echo "    <td nowrap style='font-size:0.8em'>Passed</td>\n";
  echo "    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
  echo "    <td width='$w_leg_col' bgcolor='$fail_color'>&nbsp</td>\n";
  echo "    <td nowrap style='font-size:0.8em'>Failed</td>\n";
  echo "    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
  echo "    <td width='$w_leg_col' bgcolor='$unload_color'>&nbsp</td>\n";
  echo "    <td nowrap style='font-size:0.8em'>Passed and stuck</td>\n";
  echo "    <td >&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
  echo "    <td width='$w_leg_col' bgcolor='$incomplete_color'>&nbsp</td>\n";
  echo "    <td nowrap style='font-size:0.8em'>Incomplete/Loaded</td>\n";
  echo "    <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>\n";
  echo "  </tr>\n";
  echo "</table>\n";
  echo "</td></tr>\n";
  echo "</table>\n";


  echo "<br/>\n";

//Start displaying status tables for burnin testers
display_burnin_testers($burnin_tester_names, $burnin_summary_array);

display_ibcburnin_testers($ibcburnin_tester_names, $ibcburnin_summary_array);

display_acburnin_testers($acburnin_tester_names, $acburnin_summary_array);

// hide loading message when finish
    echo "<SCRIPT LANGUAGE='javascript'>";
    echo "hideLoadingMessage();";
    echo "</SCRIPT>";



?>