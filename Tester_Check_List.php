<?php

/*******************************
*
* TSF Viewer
*
* Jan, 2011
* Ben Wardwell
*
*******************************/

include("../../../scripts/tgamain.php");
include("../../../scripts/common.php");
$date = date('m/d/y');
$tester = "none";
$user = "user";
$title = $hdr = "Tester Check List";
$tags  = "Tester Check List";
$description = "This form is to be filled out everytime a tester is released to manufacturing, calibrated, repaired or moved to a new location.";

$head_scripts = "
<script type='text/javascript'>
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
<script type=\"text/javascript\">
function SelectAll(id)
{
    document.getElementById(id).focus();
    document.getElementById(id).select();
}
</script>
";

COMMON_ShowHeader($title, $hdr, $description, $head_scripts);

?>



<form method="post" action="<?php echo $PHP_self; ?>">
	<?php
	
	if ($action == "dologin") {
    if ($pass) {
		$srv = "tewebdev";
		$usr = "root";
		$pas = "root";
		$db = "teblog";

		$conn = mysql_connect($srv, $usr, $pas);
		$rs = mysql_select_db ($db, $conn);
		
      $passhash = md5($pass);
      $sql = "select * from users where username = '".$author."' and userlevel > 0";
      $rs = mysql_query($sql,$conn);
      while ($row = mysql_fetch_array($rs)) {
        if ($passhash == $row["password"]) {
			$return = "<p align='center'>You have been logged in</p>";
			$loginuserid = $row["id"];
			$loginline = ucwords($row["username"])." is currently logged in";
			$loginstatus = "done";
        }
      }
    }
}
	header_table();
	blog_content();
	software_check();
	if ($_POST['submit']){	$submit_to_database = header_table() . blog_content() .  software_check();
	$time = date ("Y-m-d G:i:s");
	$tags = $title . ", " . $tester;
 	$loginuserid = addslashes($loginuserid);
	$time  = addslashes($time);
	$subject = $title = addslashes($title);
    $message = $T     = $submit_to_database;
    $tags  = addslashes($tags);
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: Ben <bwardwell@vicorpower.com>' . "\r\n";
	//$headers .= 'To: Ben <bwardwell@vicorpower.com>, Ben <bwardwell@vicorpower.com>' . "\r\n";
	$headers .= 'From: teweb <teweb@vicorpower.com>' . "\r\n";

	//$to  = 'bwardwell@vicorpower.com' . ', '; // note the comma
	$to .= 'bwardwell@vicorpower.com';
    
	// Mail it
	mail($to, $subject, $message, $headers);
    //$sql = "insert into posts (User, time, Subject, Text, tags)  values (\"$loginuserid\",\"$time\",\"$title\",\"$T\", \"$tags\")";
    //$rs = mysql_query($sql,$conn);
	echo "<meta HTTP-EQUIV='REFRESH' content='1; url=submit.php'>";
	}
	
    if (!$_POST['submit']){
	echo "<center><input type=\"submit\" value=\"Submit\" name=\"submit\" /> <input type=\"reset\" /></center>";}
echo "</form>";


/*   if ($action == "dologin") {
    if ($pass) {
		$srv = "tewebdev";
		$usr = "root";
		$pas = "root";
		$db = "teblog";

		$conn = mysql_connect($srv, $usr, $pas);
		$rs = mysql_select_db ($db, $conn);
		
      $passhash = md5($pass);
      $sql = "select * from users where username = '".$author."' and userlevel > 0";
      $rs = mysql_query($sql,$conn);
      while ($row = mysql_fetch_array($rs)) {
        if ($passhash == $row["password"]) {
        //setcookie("username", $row["username"], time()+3600);
        //setcookie("passhash", $row["password"], time()+3600);
			$return = "<p align='center'>You have been logged in</p>";
			$loginuserid = $row["id"];
			$loginline = ucwords($row["username"])." is currently logged in";
			//print $loginline;
			$loginstatus = "done";
			//print $loginuserid;
			//print $loginline;
			
			
			//header('Location:submit.php');

          // if(stristr($qstr,'post.php'))
            // $home = "post.php?dept=".$GLOBALS['dept']."&t=".time()."&to=y";
          // elseif(stristr($qstr,'mod.php'))
            // $home = "mod.php?dept=".$GLOBALS['dept']."&action=mod&id=".$query_details['newsid']."&t=".time();
          // else
            // $home = "index.php?dept=".$GLOBALS['dept']."&t=".time();
          // echo "<meta http-equiv='refresh' content='1;URL=${home}'>\n";
        }
      }
    }
 */
	if ($_POST['submit']){$return = "<p align='center'>Your form is being processed</p>"; }
		echo $return;
echo "</body>";
$s = "Last modified on 6/15/2011 by wardwell";
COMMON_EndingTable($s);

function dropdown_tester_names($name)
{
	echo "<select name='$name' value='{$name}' >\n";

	TGACONN_Connect($conn_id);
	$sql = "select id,tester_name from tester_names order by tester_name";
	ODBCIO_Query($conn_id, $title, $sql, $header, $testers, $TRUE);
	//$count= count($testers);
	//$testers[$count]['id'] = "0";
	//$testers[$count]['tester_name'] = "UNKNOWN";
	echo "<option size =30 selected>Select Tester</option>";

	foreach($testers as $key=>$row){
		if($row['tester_name'] == $ate) $select = "selected"; else $select = "";
			echo "<option $select value='".$row['tester_name']."'>".$row['tester_name']."</option>\n";
		}
}
function header_table() 
{
	$table_rows = array('Tester_Name','Date','Reason_for_Check','New_Location','Technician','Notes');
	$date = date('m/d/y');
	$tablewidth = "80";
	$comment_text = "No Note";
	//$dropdown = dropdown_tester_names();
	extract($_POST );
	if ($_POST['submit']){
		global $tester;
		$tester = ucfirst(strtolower($_POST[$table_rows[0]]));
		//print $tester;
		$submit_to_database = '<table border="1" width="' .$tablewidth .'%" >';
		foreach ($table_rows as $table)
		{
		//print $table;
		//print $_POST[$table] ;
		//echo "<br>";
		$submit_to_database = $submit_to_database . '<tr><td width="20%">' . str_replace("_"," ",$table) . '</td><td width="60%">' . $_POST[$table] . '</td></tr>';
		//print $submit_to_database;
		}
		$submit_to_database = $submit_to_database . "</table>";
		?>
		<!-- <SCRIPT LANGUAGE="javascript">alert ("The header information has been saved")</SCRIPT> -->
		<?php
		//print $submit_to_database;
		return $submit_to_database;
		}

	else {
		echo "<table  border=\"1\" cellspacing=\"0\" cellpadding=\"6\" width=\"" .$tablewidth ."%\" >";
		echo '<tr><td><div align="right"><b>' . str_replace("_"," ",$table_rows[0]) . '</b></div></td><td>'; dropdown_tester_names($table_rows[0]); echo'<td></tr>';
		echo '<tr><td><div align="right"><b>' . str_replace("_"," ",$table_rows[1]). '</b></div></td><td><input type="text" name="'.$table_rows[1].'" size="25" value="' . $date . '"/></td></tr>';
		echo '<tr><td><div align="right"><b>' . str_replace("_"," ",$table_rows[2]). '</b></div></td><td><input type="text" name="'.$table_rows[2].'" size="50"></td></tr>';
		echo '<tr><td><div align="right"><b>' . str_replace("_"," ",$table_rows[3]). '</b></div></td><td><input type="text" name="'.$table_rows[3].'" size="50"></td></tr>';
		echo '<tr><td><div align="right"><b>' . str_replace("_"," ",$table_rows[4]). '</b></div></td><td><input type="text" name="'.$table_rows[4].'" size="50"></td></tr>';
		echo '<tr><td><div align="right"><b>' . str_replace("_"," ",$table_rows[5]). '</b></div></td><td><textarea name = "' . $table_rows[5]. '" id = "' . $table . '_comment" onClick="SelectAll(\'' . $table . '_comment\');" cols="50" rows="2">'. $comment_text . '</textarea></td></tr>';
		echo '</table>';
		}
}

function blog_content()
{
	$table_rows = array(
	'All_Front_Panel_Screws_are_installed',
	'All_Rear_panel_Screws_are_installed',
	'All_Doors_are_Locked',
	'Tester_is_Clean_and_all_Panels_are_in_good_shape',
	'Extra_Network_Port_is_plugged',
	'Computer_Filter_is_clean',
	'Ionizer_is_installed_and_working',
	'Scanner_is_installed_and_working',
	'Keyboard_is_installed_and_working',
	'Fans_are_working',
	'All_Ibuttons_are_entered_properly_in_the_Database',
	'Tester_Auto_Starts_when_power_is_turned_on',
	'Tester_logs_in_with_the_proper_name',
	'All_instruments_are_calibrated',
	'All_applicable_equipment_entered_in_Express_Maintenance',
	'All_associated_DUT_cards_are_labled_and_entered_in_Express_Maintenance',
	'All_instruments_are_transfered_to_the_proper_department');
	//Column widths must total no more the 100%
	//Hardware Check column
	$column_1 = "45%";
	//Pass/Fail column
	$column_2 = "15%";
	//Comment column
	$column_3 = "20%";
	$table_width = $column_1+$column_2 + $column_3 + "%";
	$comment_text = "Enter your comment here";

	extract($_POST );
	if ($_POST['submit']){
		$submit_to_database = '<table border="1" width="' .$table_width .'%"><tr bgcolor="#0066FF"> <td width="' .$column_1 .'"><b>Hardware Check</b></td><td width="' .$column_2 .'"><div align="left"><b>Pass / Fail</b></div></td><td width="' .$column_3 .'"><b>Comment</b></td></tr>';  
		//<tr bgcolor='#0066FF'> <td width='" .$column_1 ."'><b>Hardware Check</b></td><td width='" .$column_2 ."'><div align='left'><b>Pass / Fail</b></div></td><td width='" .$column_3 ."'><b>Comment</b></td></tr>'";
		foreach ($table_rows as $table_submit)
		{
		$table_display = str_replace("_"," ",$table_submit);
		//print $table_display;
		$array_check = array($table_submit, 'check');
		$table_check = join("_", $array_check);
		$print_table_check = $_POST[$table_check] ;
		//print $print_table_check;
		$array_comment = array($table_submit, 'comment');
		$table_comment = join("_", $array_comment);
		$print_table_comment = $_POST[$table_comment];
		if ($print_table_comment = $comment_text){
			$print_table_comment = "No Comment";
		}
		//print $print_table_comment;
		//echo "<br>";
		$submit_to_database = $submit_to_database . "<tr><td>" . $table_display . "</td><td>" . $print_table_check . "</td><td>" . $print_table_comment . "</td></tr>";
		}
		$submit_to_database = $submit_to_database . "</table>";	
	?>
	<!--<SCRIPT LANGUAGE="javascript">alert ("The header information has been saved")</SCRIPT> -->
	<?php
		return $submit_to_database;
		//print $submit_to_database;
	}
	Else {
	echo "<table  border=\"1\" width='" .$table_width ."%'>
	<tr bgcolor=\"#0066FF\"> <td width='" .$column_1 ."'><b>Hardware Check</b></td><td width='" .$column_2 ."'><div align=\"left\"><b>Pass / Fail</b></div></td><td width='" .$column_3 ."'><b>Comment</b></td></tr>";
		foreach($table_rows as $table)
		{
		$table_display = str_replace("_"," ",$table);
		echo '<tr><td>' . $table_display . '</td><td><select style="font-size:12px;color:#006699;font-family:verdana;background-color:#ffffff;" name="' . $table . '_check">
			<option value="Pass">Pass</option><option value="Fail">Fail</option><option value="N/A">N/A</option></select></td>
			<td><textarea name = "' . $table . '_comment" id = "' . $table . '_comment" onClick="SelectAll(\'' . $table . '_comment\');" cols="50" rows="">'. $comment_text . '</textarea></td></tr>';
		}
		
	echo '</table>';
	}
}


function software_check()
{
	$table_rows = array(
	'Self_Test',
	'KGU',
	'KGU_1');
	//Column widths must total no more the 100%
	//Hardware Check column
	$column_1 = "20";
	//Pass/Fail column
	$column_2 = "20";
	//Pass/Fail column
	$column_3 = "20";
	//Comment column
	$column_4 = "20";

	$table_width = $column_1 + $column_2 + $column_3 + $column_4 + "%";
	$comment_text = "Enter your comment here";

	if ($_POST['submit']){
		$submit_to_database = $submit_to_database . "<table  border='1' width='" .$table_width ."%'><tr bgcolor='#0066FF'><td width='" .$column_1 ."%'><b>Software Check</b></td><td width='" .$column_2 ."%'><b>Part Number</b></td><td width='" .$column_3 ."%'><b>Link to Test Sheet</b></td><td width='" .$column_4 ."%'><b>Comment</b></td></tr>";
	
		foreach ($table_rows as $table_submit)
		{
		$table_display = str_replace("_"," ",$table_submit);
		//print $table_display;
		$array_number = array($table_submit, 'number');
		$table_number = join("_", $array_number);
		$print_table_number = $_POST[$table_number];
		//print $print_table_number;
		$array_link = array($table_submit, 'link');
		$table_link = join("_", $array_link);
		$print_table_link = $_POST[$table_link];
		//print $print_table_link;
		$array_comment = array($table_submit, 'comment');
		$table_comment = join("_", $array_comment);
		$print_table_comment = $_POST[$table_comment];
		if ($print_table_comment = $comment_text){
			$print_table_comment = "No Comment";
		}
		//print $print_table_comment ;
		//echo "<br>";
		$submit_to_database = $submit_to_database . "<tr><td>" . $table_display . "</td><td>" . $print_table_number . "</td><td><a href=\"" . $print_table_link . "\">Test Sheet</a></td><td>" . $print_table_comment . "</td></tr>";
		}
	?>
	<!-- <SCRIPT LANGUAGE="javascript">alert ("The KGU information has been saved")</SCRIPT>-->
	<?php
	$submit_to_database = $submit_to_database . "</table>";
	return $submit_to_database;
	//print $submit_to_database;
	}
	else {
	echo '<table  border="1" width="' .$table_width .'%">
	<tr bgcolor="#0066FF">
	<td width="' .$column_1 .'"><b>Software Check</b></td>
	<td width="' .$column_2 .'"><b>Part Number/Self Test Card Number</b></div></td>
	<td width="' .$column_3 .'"><b>Link to Test Sheet</b></td>
	<td width="' .$column_4 .'"><b>Comment</b></td></tr>';
	foreach($table_rows as $table)
	{
		echo '<tr><td>' . $table . '</td><td><input type="text" name="' . $table . '_number" size="50"></td>
		<td><input type="text" name="' . $table . '_link" size="50"></td>
		<td><textarea name = "' . $table . '_comment" id = "' . $table . '_comment" onClick="SelectAll(\'' . $table . '_comment\');" cols="50" rows="2">' . $comment_text . '</textarea></td>
	  </tr>';
	}
	echo '</table>';
	}
}