<?php

global $hr_limit;
$hr_limit = 1;

$model_list = array();

// Burnin Testers
    $burnin_tester_names = array('ALFRED',
                        'BORIS',
                        'BRUCE',
                        'DICK',
                        'GEORGE',
                        'HARRIET',
                        'JANE',
                        'NATASHA',
                        'OHARA',
                        'PEABODY',
                        'SHERMAN',
                        'GORDON');
	
	sort($burnin_tester_names);
// AC Testers
    $acburnin_tester_names = array(
                        'ELDORADO',
                        'SHANGRILA');
  sort($acburnin_tester_names);
// IBC Testers
  $ibcburnin_tester_names = array('CALVIN',
						'HOBBES',
						'WOODSTOCK',
                        'SNOOPY');
  sort($ibcburnin_tester_names);


//functions for burnin_table_view.php
//Reads in the XML file from the Burnin testers
function process_burnin_file($file)
{
  if (!file_exists($file)){
   echo "Can not find " . $file . "<br>";
      return FALSE;
  }
  else
  {
  $array = array();
  $i = 1; //test ordinal

  //create new object
  $doc = new DOMDocument();
  //load xmlfile
  $doc->load($file);

  //reset values
  $tag = "";
  $val = "";
  $loadslot = 1;
  

  //get Starting node
  $tests = $doc->getElementsByTagName("cage");
  foreach($tests as $node){
	$loadslot = 1;
    $cagenum = $node->getElementsByTagName("cagenum")->item(0)->nodeValue;

$optionNode = $node->getElementsByTagName("loadslot");
  foreach($optionNode as $options){
    foreach($options->childNodes as $option){
	  $loadslot = $options->getElementsByTagName("slotnum")->item(0)->nodeValue;
	  if($option->tagName){
        $tag = $option->nodeName;
        $val = $option->nodeValue;
        $array[$cagenum][$loadslot][$tag] = $val;
		if($tag == 'starttime') break;
      }
    }
	 $optionNode = $options->getElementsByTagName("DUT");
  foreach($optionNode as $options){
    foreach($options->childNodes as $option){
      if($option->tagName){
	  $dutnum = $options->getElementsByTagName("DUTnum")->item(0)->nodeValue;
        $tag = $option->nodeName;
        $val = $option->nodeValue;
        $array[$cagenum][$loadslot][$dutnum][$tag] = $val;
      }
    }
  }
  }

   }
  return $array;
   }
}


//Reads in the XML file from the AC Burin testers
function process_acburnin_file($file)
{
  if (!file_exists($file)){
   echo "Can not find " . $file . "<br>";
      return FALSE;
  }
  else
  {
  $array = array();
  $i = 1; //test ordinal

  //create new object
  $doc = new DOMDocument();
  //load xmlfile
  $doc->load($file);

  //reset values
  $tag = "";
  $val = "";
  $loadslot = 1;
  

  //get Starting node
  $tests = $doc->getElementsByTagName("cage");
  foreach($tests as $node){
	$loadslot = 1;
    $cagenum = $node->getElementsByTagName("cagenum")->item(0)->nodeValue;

$optionNode = $node->getElementsByTagName("loadslot");
  foreach($optionNode as $options){
    foreach($options->childNodes as $option){

	  $loadslot = $options->getElementsByTagName("slotnum")->item(0)->nodeValue;
	  if($option->tagName){
        $tag = $option->nodeName;
        $val = $option->nodeValue;
        $array[$cagenum][$loadslot][$tag] = $val;
		if($tag == 'slotnum') break;
      }
    }
	 $optionNode = $options->getElementsByTagName("DUT");
  foreach($optionNode as $options){
    foreach($options->childNodes as $option){
      if($option->tagName){
	  $dutnum = $options->getElementsByTagName("dutnum")->item(0)->nodeValue;
        $tag = $option->nodeName;
        $val = $option->nodeValue;
        $array[$cagenum][$loadslot][$dutnum][$tag] = $val;
      }
    }
  }
  }

   }
  return $array;
   }
}


//Reads in the XML file from the IBC Burin testers
function process_ibcburnin_file($xmlfile)
{
	if (!file_exists($xmlfile)){
		echo "Can not find " . $xmlfile . "<br>";
		return FALSE;
		}
	else
	{
		$array = array();
		$i = 1; //test ordinal

		//create new object
		$doc = new DOMDocument();
		//load xmlfile
		$doc->load($xmlfile);

		//reset values
		$tag = "";
		$val = "";

		//get Starting node
		$tests = $doc->getElementsByTagName("cage");
		foreach($tests as $node){
			$cage_num = $node->getElementsByTagName("cagenum")->item(0)->nodeValue;
			$optionNode = $node->getElementsByTagName("slot");
			foreach($optionNode as $options){
				foreach($options->childNodes as $option){
				$slotnum = $options->getElementsByTagName("slotnum")->item(0)->nodeValue;
					if($option->tagName){
					$tag = $option->nodeName;
					$val = $option->nodeValue;
					$array[$cage_num][$slotnum][$tag] = $val;
					if($tag == 'finishtime') break;
					}
				}
				 $optionNode = $options->getElementsByTagName("DUT");
				foreach($optionNode as $options){
					foreach($options->childNodes as $option){
						if($option->tagName){
						$dutnum = $options->getElementsByTagName("dutnum")->item(0)->nodeValue;
						$tag = $option->nodeName;
						$val = $option->nodeValue;
						$array[$cage_num][$slotnum][$dutnum][$tag] = $val;
						}
					}
				}
			}
		}
	}
	return $array;
}


function display_burnin_testers($burnin_tester_names, $burnin_summary_array)
{
  echo "<table bgcolor = '#E9E9E9'  align='center'  border=1 cellspacing=0 cellpadding=0>\n";
  $tcnt=0;
  foreach($burnin_tester_names as $tester) { //each testers
	//Controls how many tester are displayed on each line
    if ($tcnt == 3){
      echo "  <tr>\n";
	  $tcnt=0;
    }
    echo "      <td>\n";
    echo "        <table border=0>\n";
    $finished_total = 0;


    for ($x=1;$x<=4;$x++) {  // cages
		$link = "<a href = 'http://" . $_SERVER["HTTP_HOST"]."/teweb/support/common/DC_Products_Burnin/burnin.php?file=burnin.xml&tester=".$tester."'>".$tester."</a>";

		for ($s=1;$s<=16;$s++)
		{
			$v = $burnin_summary_array[$tester][$x][$s][1][s_vi];
			if ($v >= 0)
				$v = $burnin_summary_array[$tester][$x][$s][2][s_vi];
		if ($v >= 1) $s = 20;
		}
					
		switch ($x) {
			case "1":  $t = "<td width='80'>${link}</td>";  break; //tester name
			case "2":  $t="<td align='right'><font size=1>$v</font></td>\n";break;
			case "4":  $t="<td align='right'><font size=1>$v</font></td>\n";break;
			default: $t="<td>&nbsp;</td>";
		}

		echo "      <tr rowspan=4> ${t}\n";

		echo "           <td>\n";
		$cage = $x;

       echo "             <table border=1 cellspacing = 0 cellpadding = 0>\n";
       echo "               <tr>\n";

       for ($s = 1; $s <= 16; $s++) {  // each slot

        echo "<td align = center>\n";

        $slot = $s;
        $table_add = array();
        $slot_failed = 0;
        $slot_status = array();
        $slot_info = array();
        for ($d = 1; $d <=6; $d++) {  //each duts possible 6

            $dut = $d;
            $status = $burnin_summary_array[$tester][$cage][$slot][$dut]['teststate'];
            $type   = $burnin_summary_array[$tester][$cage][$slot][$dut]['slot_type'];
            $finish_time = $burnin_summary_array[$tester][$cage][$slot][$dut]['finishtime'];

            //info of the slot
            $pid = $burnin_summary_array[$tester][$cage][$slot][$dut]['pn'];
            $modelnumber = $burnin_summary_array[$tester][$cage][$slot][$dut]['wo'];
            $timeburned = round($burnin_summary_array[$tester][$cage][$slot][$dut]['tburned']/3600, 2);
            $time2end = $burnin_summary_array[$tester][$cage][$slot][$dut]['t2end'];


          if($status >= 1) $slot_status[] = $status;
          if(strlen($finish_time) > 1)
            if(!$slot_status['least_time'] || $slot_status['least_time'] < $finish_time)
              $slot_status['least_time'] = $finish_time;
	          if ($detail[$cage][$slot][$dut]['time2end'] > $slot_status['time2end']) $slot_status['time2end'] =  $detail[$cage][$slot][$dut]['time2end'];

          if($slot_status){
            $slot_info['work_order'] = $slot_info['work_order'] ? $slot_info['work_order'] : $pid;
            $slot_info['model_number'] = $slot_info['model_number'] ? $slot_info['model_number'] : $modelnumber;
            $slot_info['timeburned'] = $slot_info['timeburned'] ? $slot_info['timeburned'] : $timeburned;
            $slot_info['time2end'] = $slot_info['time2end'] ? $slot_info['time2end'] : $time2end;
          }	

        }

        draw_table($table_add,$slot_status,$slot_info);
        echo "              </td>";
       }

      echo "</tr></table>\n";
      echo "</td></tr>\n";
    }
      echo "</td></tr>\n";

      $tcnt++;
      echo "</table>\n";
    }

echo "</table>\n";
echo "<br><br>\n";
}


function display_acburnin_testers($acburnin_tester_names, $acburnin_summary_array)
{  
  echo "<table bgcolor = '#E9E9E9'  align='center'  border=1 cellspacing=0 cellpadding=0>\n";
  $tcnt=0;
  foreach($acburnin_tester_names as $tester) { //each testers
	//Controls how many tester are displayed on each line
    if ($tcnt == 3){
      echo "  <tr>\n";
	  $tcnt=0;
    }
    echo "      <td>\n";
    echo "        <table border=0>\n";
    $finished_total = 0;


    for ($x=1;$x<=4;$x++) {  // four cages


		$link = "<a href = 'http://" . $_SERVER["HTTP_HOST"]."/teweb/support/common/DC_Products_Burnin/burnin.php?file=burnin.xml&tester=".$tester."'>".$tester."</a>";

		switch ($x) {
           case "1":  $v = $acburnin_summary_array[$tester][$x][1][1][s_vi];  break;
           case "2":  $v = $acburnin_summary_array[$tester][$x][1][1][s_vi];  break;
           case "3":  $v = $acburnin_summary_array[$tester][$x][1][1][s_vi];  break;
           case "4":  $v = $acburnin_summary_array[$tester][$x][1][1][s_vi];  break;
        }

       switch ($x) {
         case "1":  $t = "<td width='80'>${link}</td>";  break; //tester name
         case "2":  $t="<td align='right'><font size=1>$v</font></td>\n";break;
         case "4":  $t="<td align='right'><font size=1>$v</font></td>\n";break;
         default: $t="<td>&nbsp;</td>";
       }

          echo "      <tr rowspan=4> ${t}\n";

       echo "           <td>\n";

       	switch ($x) {
			case "1":  $cage = $x;  break;
			case "2":  $cage = $x;  break;
			case "3":  $cage = $x;  break;
			case "4":  $cage = $x;  break;
        }

       echo "             <table border=1 cellspacing = 0 cellpadding = 0>\n";
       echo "               <tr>\n";

       for ($s = 1; $s <= 8; $s++) {  // each slot

        echo "<td align = center>\n";

        $slot = $s;
        $table_add = array();
        $slot_failed = 0;
        $slot_status = array();
        $slot_info = array();
        for ($d = 1; $d <=6; $d++) {  //each duts possible 6

            $dut = $d;
            $status = $acburnin_summary_array[$tester][$cage][$slot][$dut]['teststate'];
            $type   = $acburnin_summary_array[$tester][$cage][$slot][$dut]['slot_type'];
            $finish_time = $acburnin_summary_array[$tester][$cage][$slot][$dut]['finishtime'];

            //info of the slot
            $pid = $acburnin_summary_array[$tester][$cage][$slot][$dut]['pn'];
            $modelnumber = $acburnin_summary_array[$tester][$cage][$slot][$dut]['wo'];
            $timeburned = round($acburnin_summary_array[$tester][$cage][$slot][$dut]['tburned'], 2);
            $time2end = $acburnin_summary_array[$tester][$cage][$slot][$dut]['t2end'];


          if($status >= 1) $slot_status[] = $status;
          if(strlen($finish_time) > 1)
            if(!$slot_status['least_time'] || $slot_status['least_time'] < $finish_time)
              $slot_status['least_time'] = $finish_time;
	          if ($detail[$cage][$slot][$dut]['time2end'] > $slot_status['time2end']) $slot_status['time2end'] =  $detail[$cage][$slot][$dut]['time2end'];

          if($slot_status){
            $slot_info['work_order'] = $slot_info['work_order'] ? $slot_info['work_order'] : $pid;
            $slot_info['model_number'] = $slot_info['model_number'] ? $slot_info['model_number'] : $modelnumber;
            $slot_info['timeburned'] = $slot_info['timeburned'] ? $slot_info['timeburned'] : $timeburned;
            $slot_info['time2end'] = $slot_info['time2end'] ? $slot_info['time2end'] : $time2end;
          }

        }

        draw_table($table_add,$slot_status,$slot_info);
        echo "              </td>";
       }

      echo "</tr></table>\n";
      echo "</td></tr>\n";
    }
      echo "</td></tr>\n";

      $tcnt++;
      echo "</table>\n";
    }

echo "</table>\n";
echo "<br><br>\n";
}



function display_ibcburnin_testers($ibcburnin_tester_names, $ibcburnin_summary_array)
{  
//Start displaying status table
  echo "<table bgcolor = '#E9E9E9'  align='center'  border=1 cellspacing=0 cellpadding=0>\n";
  $tcnt=0;
  foreach ($ibcburnin_tester_names as $tester ) { //each testers
	//Controls how many tester are displayed on each line
    if ($tcnt == 2){
      echo "  <tr>\n";
	  $tcnt=0;
    }
    echo "      <td>\n";
    echo "        <table border=0>\n";
    $finished_total = 0;

    for ($x=1;$x<=3;$x++) {  // three cages

       $link = "<a href = http://" . $_SERVER["HTTP_HOST"]. "/teweb/support/common/DC_Products_Burnin/burnin.php?file=IBC.xml&tester=".$tester." target=\"_blank\">".$tester."</a>";
       $v = $ibcburnin_summary_array[$tester][$x][1]['vi'];

       if (!$v)   // if there is no voltage
        switch ($x) {
           case "1":  $v = $ibcburnin_summary_array[$tester]['voltage 2'];  break;
           case "2":  $v = $ibcburnin_summary_array[$tester]['voltage 1'];  break;
           case "3":  $v = $ibcburnin_summary_array[$tester]['voltage 4'];  break;
        }

       switch ($x) {
         case "1":  $t="<td width='80'>${link}</td>";  break; //tester name
         case "2":  $t="<td align='right'><font size=1>$v</font></td>\n";break;
         case "3":  $t="<td align='right'><font size=1>$v</font></td>\n";break;
         default: $t="<td>&nbsp;</td>";
       }

          echo "      <tr rowspan=4> ${t}\n";

       echo "           <td>\n";

       $cage = $x;

       echo "             <table border=1 cellspacing = 0 cellpadding = 0>\n";
       echo "               <tr>\n";

       for ($s = 1; $s <= 14; $s++) {  // 14 slots are in each cage

        echo "<td align = center>\n";
	//  Array         Tester Cage Slot  DUT  option
	//aa($summary_array[$tester][1][2]);
	
        $slot = $s;
        $table_add = array();
        $slot_failed = 0;
        $slot_status = array();
        $slot_info = array();
        for ($d = 1; $d <=5; $d++) {  //each slot can have up to 5 modules

            $dut = $d;
			$status = $ibcburnin_summary_array[$tester][$cage][$slot]['state'];
            $type   = $detail[$cage][$slot][$dut]['slot_type'];
            $finish_time = $ibcburnin_summary_array[$tester][$cage][$slot]['finishtime'];

            //info of the slot
            $pid = $ibcburnin_summary_array[$tester][$cage][$slot]['pn'];
            $modelnumber = $ibcburnin_summary_array[$tester][$cage][$slot][$dut]['sn'];
            $timeburned = round($ibcburnin_summary_array[$tester][$cage][$slot]['tburned']/3600, 2);
            $time2end = $ibcburnin_summary_array[$tester][$cage][$slot]['t2end'];


          if($status >= 1) $slot_status[] = $status;
          if(strlen($finish_time) > 1)
            if(!$slot_status['least_time'] || $slot_status['least_time'] < $finish_time)
              $slot_status['least_time'] = $finish_time;
	          if ($ibcburnin_summary_array[$tester][$cage][$slot][$dut]['t2end'] > $slot_status['t2end']) $slot_status['t2end'] =  $ibcburnin_summary_array[$tester][$cage][$slot][$dut]['t2end'];

          if($slot_status){
            $slot_info['work_order'] = $slot_info['work_order'] ? $slot_info['work_order'] : $pid;
            $slot_info['model_number'] = $slot_info['model_number'] ? $slot_info['model_number'] : $modelnumber;
            $slot_info['timeburned'] = $slot_info['timeburned'] ? $slot_info['timeburned'] : $timeburned;
            $slot_info['time2end'] = $slot_info['time2end'] ? $slot_info['time2end'] : $time2end;
          }

        }

        draw_table($table_add,$slot_status,$slot_info);
        echo "              </td>";


       }

      echo "</tr></table>\n";
      echo "</td></tr>\n";

    }
      echo "</td></tr>\n";

      $tcnt++;
      echo "</table>\n";
    }

echo "</table>\n";
echo "<br><br>\n";

}


function draw_table($tbl,$slot_status_array,$slot_info) {

  global $hr_limit;
  global $fail_color,$pass_color,$running_color;
  global $incomplete_color, $unload_color;
  //  testing state
  //
  //  NOTPRESENT = 0
  //  LOADED = 1
  //  INITIALIZING = 2
  //  FAILED = 3               -
  //  INITFAIL = 4
  //  INCOMPLETE = 5
  //  IDLE = 6                 =
  //  TESTING = 7              /
  //  PASSED = 8               +

  $color = "";
  $end_delay = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";


  echo "        <table border=0 cellpadding=0 cellspacing=0>\n";


  if(in_array("7",$slot_status_array) || in_array("6",$slot_status_array)|| in_array("2",$slot_status_array)) {
    $color = $running_color;
    $end_delay = sprintf("%2d",ceil($slot_info['time2end']));
  }

  elseif(in_array("8",$slot_status_array)){


    if(!in_array("7",$slot_status_array) && !in_array("6",$slot_status_array)){
      $t = time();
      if($slot_status_array['least_time'])
        $tdelta = $t - $slot_status_array['least_time'];
      if($tdelta <= (3600 * $hr_limit))
        $color = $pass_color;
      else{
        $color = $unload_color;
        $end_delay = sprintf("%d",ceil($tdelta / 3600));
        $end_delay = "<font size=1>$end_delay</font>\n";
      }
    }
  }
  elseif(in_array("3",$slot_status_array)|| in_array("4",$slot_status_array)){
    $t = time();
    if($slot_status_array['least_time'])
      $tdelta = $t - $slot_status_array['least_time'];
    $end_delay = sprintf("%d",ceil($tdelta / 3600));
    $color = $fail_color;
  }
  elseif((in_array("1",$slot_status_array) || (in_array("5",$slot_status_array)))){
    $color = $incomplete_color;
  }
  elseif(count($slot_status_array)>0)
    $color = $running_color;

    echo "                <tr>\n";
    echo "                  <td align='center' width='25' height='15' bgcolor='${color}'>".get_div($color,$end_delay,$slot_info)."</td>\n";
    echo "                </tr>\n";


    echo "         </table>\n";

}

function get_div($color,$delay,$array){

  /*
    This function will return the html tag to pop-up the information on mouse over.
    The color, width, etc. are specified at the beginning of this script

    @ $color - the device color # from get_color id
    @ $delay  - the time to show on the table
    @ $array  - array containing all the information for the tooltip

  */

//    if(!$delay) $delay = "&nbsp;";

  if($color){

    $serial = $array['serials'];
    $pid    = $array['work_order'];
    $model  = $array['model_number'];
    $t_burn = $array['timeburned'];
    $t_2end = $array['time2end'];

    $text = "Model Number"                                         .": ${model}<br>";
    $text .= "Work Order &nbsp;&nbsp;&nbsp;"                        .": ${pid}<br>";
    $text .= "Time Burned &nbsp;&nbsp;"                             .": ${t_burn} hours<br>";
    $text .= "Time Left &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .": ${t_2end} hours";
	//style='line-height:1px; height: 1px;'

    $div = "<div align='center' style='line-height:10px; padding:0' onMouseover=\"tip('${text}')\"; onMouseout=\"hidetip()\"><font size = 1>$delay</font></div>";
  }else
    $div = "&nbsp;";

  return $div;
}

function FileReadToEnd($file, &$content)
{
  $content="";

  if (file_exists($file) && $fp=fopen($file, "r")) {
    flock($fp, LOCK_SH);
    $content=fread($fp, filesize($file));
    fclose($fp);
  }

}














//functions for burnin_table_view.php






//Shows the tables PID Viewer
function show_table($info, $sort_by, $sort_order)
{
    global $family_list;
    global $href;
    for (reset($info);  list($pid, $testers)=each($info);) {

        foreach($testers as $tester_ar){
			$j++;
			foreach($tester_ar as $detail){
			
            $arr[] = array("#" => $j,
						"workorder" => $pid,
                        "tester" => $detail['tester'],
                        "model_number" => $detail['model_number'],
                        "passed" => $detail['passed'],
                        "failed" => $detail['failed'],
                        "testing" => $detail['testing'],
                        "timeburned" => round($detail['timeburned']/3600, 2),
                        "time2end" => $detail['time2end']);

             $total_passed  += $detail['passed'];
             $total_failed  += $detail['failed'];
             $total_testing += $detail['testing'];
			}
        }
  	}

    //group the item families to Fastrak, VI200 and VIC
    $fastrak = array("FTMICRO","FTMINI","FTMAXI");
	$ac_products = array("SMDVIHAM","SMDVIAIM","SMDVIAIL");
    $fam_count = array();
    foreach($family_list as $fam=>$sn){
		if(in_array($fam,$fastrak))
		$fam_count['FASTRAK']['count'] += count($sn);
		elseif(in_array($fam,$ac_products))
		$fam_count['AC SYSTEMS']['count'] += count($sn);
		elseif(strstr($fam,"IBCASSY"))
		$fam_count['IBC']['count'] += count($sn);
		elseif(strstr($fam,"VIC"))
		$fam_count['VI-CHIP']['count'] += count($sn);
		else
		$fam_count['VI200/VIJ00']['count'] += count($sn);
    }

    // flip the polarity of the sort order
    // and figure out the image to show

    if ($sort_order == SORT_DESC)  {
        $m="-";   // make the nunber negative
        $img = "&nbsp;&nbsp;<img src='http://teweb/teweb/graphics/desc.gif'>";
    }
    else {
        $m="";    // dont make the number negative
        $img = "&nbsp;&nbsp;<img src='http://teweb/teweb/graphics/asc.gif'>";
    }


    if($_GET['ts']) $ts = "&ts=".$_GET['ts']; else $ts ="";

    $g = "&t=".time();


    echo "<table align = center border='1' cellpadding = 0>\n";
    echo "<tr><td>\n";
    echo "  <table align = center border='0' width=100% cellpadding =4 cellspacing=0>\n";
    echo "    <tr bgcolor=#CEE8F2 align='center'><td colspan=4> <b>Count Summary by product type<b></td></tr>\n";
    foreach($fam_count as $fam=>$cnt){
		echo "  <tr bgcolor=#CEE8F2 >\n";
		echo "    <td width=25></td><td align='left'> ${fam} :</td><td align=left >".$cnt['count']."</td><td width=25></td>\n";
		echo "  </tr>\n";
    }
    echo "  </table>";
    echo "</tr></td>\n";
    //echo "<tr><td>&nbsp</td></td>\n";
    echo "<tr><td>\n";
    echo "  <table align = center border='1' cellpadding = 4>\n";
    echo "  <tr bgcolor = '#CCCCCC'> \n";

    echo "<td align = center><b>#";
    if ($sort_by == 1) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}2${g}${ts}'>Workorder</a>";
    if ($sort_by == 2) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}3${g}${ts}'>Model</a>";
    if ($sort_by == 3) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}4${g}${ts}'>Tester</a>";
    if ($sort_by == 4) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}5${g}${ts}'>Passed</a>";
    if ($sort_by == 5) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}6${g}${ts}'>Failed</a>";
    if ($sort_by == 6) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}7${g}${ts}'>Testing</a>";
    if ($sort_by == 7) echo $img;    echo  "</b></td>\n";
    echo "<td align = center><b><a href = '${PHP_SELF}?sort=${m}8${g}${ts}'>Time Burned</a>";
    if ($sort_by == 8) echo $img;    echo  "</b></td>\n";
    echo "<td align = center><b><a href = '${PHP_SELF}?sort=${m}9${g}${ts}'>Time to end</a>";
    if ($sort_by == 9) echo $img;    echo  "</b></td>\n";


            switch ($sort_by) {
                case 1:     $ss_by = "#";                   break;
                case 2:     $ss_by = "workorder";           break;
                case 3:     $ss_by = "model_number";        break;
				case 4:     $ss_by = "tester";             	break;
                case 5:     $ss_by = "passed";          	break;
                case 6:     $ss_by = "failed";          	break;
                case 7:     $ss_by = "testing";     		break;
                case 8:     $ss_by = "timeburned";          break;
                case 9:     $ss_by = "time2end";            break;
            }


    // now sort the display array based on the required heading and sort order
    $arr = multisort_array($arr, $ss_by, $sort_order);

    $j=0;
    $cnt = count($arr);
    $total_testing = 0;

    for ($ds = 0; $ds < $cnt; $ds++) {
	$row = $arr[$ds]; //$href="";
		if($row['workorder'] <> "N/A")
        	  $pid_ref = 	"<a href = http://" . $_SERVER["HTTP_HOST"]. "/teweb/developer/dlog/burnin_pid_detail.php?pid=${row['workorder']}>${row['workorder']}</a>";
		else
            $pid_ref = $row['workorder'];

            $j++;
            if (($j/2) == intval($j/2)) $bg = "bgcolor = '#FFFFCC'"; else $bg = "bgcolor = '#CCFFCC'";
            echo "<tr ${bg}>\n";
            echo "<td> ${j}</td>\n";
            echo "<td> ${pid_ref}</td>\n";
            echo "<td> ${row['model_number']} </td>\n";
            echo "<td> ${row['tester']} </td>\n";
            $passed = ($row['passed']) ? $row['passed'] : "&nbsp;";
            echo "<td> $passed </td>\n";
            $failed = ($row['failed']) ? $row['failed'] : "&nbsp;";
            echo "<td> $failed </td>\n";
            $testing = ($row['testing']) ? $row['testing'] : "&nbsp;";
            echo "<td> $testing </td>\n";
            $timeburned = ($row['timeburned']) ? $row['timeburned'] : "&nbsp;";
            echo "<td> $timeburned </td>\n";
            $time2end = ($row['time2end']) ? $row['time2end'] : "&nbsp;";
            echo "<td> $time2end </td>\n";
            echo "</tr>\n";
            $old_id = $id;

            $total_testing += $row['testing'];
    }

    echo "<tr bgcolor = '#CCCCCC'> \n";
    echo "<td colspan = 4>Totals </td><td>${total_passed} </td> <td>${total_failed} </td> <td>${total_testing} </td><td colspan = 2>&nbsp;</td></tr>";
    echo "</table>\n";
    echo "</tr></td>\n";
    echo "</table>\n";
}

//Creates the IBC data for the PID viewer
function add_IBC_to_table($tester_names, $doc_array)
{
global $burnin_details;
	$burnin_details = array();
global $pids;
	$pids = array();
global $family_list;
foreach ($tester_names as $tester) {
		for ($x=1;$x<=3;$x++) {  // cages
			$cage = $x;
			for ($s = 1; $s <= 14; $s++) {  // each slot
				//  testing state
				//	NOTPRESENT = 0
				//	LOADED = 1
				//	INITIALIZING = 2
				//	FAILED = 3
				//	INITFAIL = 4
				//	INCOMPLETE = 5
				//	IDLE = 6
				//	TESTING = 7
				//	PASSED = 8
				$slot = $s;
				for ($d = 1; $d <=5; $d++) {
					$dut = $d;
					$device_slot = $doc_array[$tester][$cage][$slot];
					$device = $doc_array[$tester][$cage][$slot][$dut];
					if($device){
						$wo     = ($device['wo']) ? $device['wo'] : "N/A";
						$sn     = $device['sn'];
						$family = get_family($device_slot['pn']);
						$count = $count + 1;
						//create $pids array for detail view of each pid
		//              if($wo == "N/A") { aa($device); $kl++;}
						$pids[$wo][$sn]['pn']  				= $device_slot['pn'];
						$pids[$wo][$sn]['item_family']   	= $device['item_family'];
						$pids[$wo][$sn]['tester']        	= $tester;
						$pids[$wo][$sn]['serial_number'] 	= $sn;
						$pids[$wo][$sn]['cage']          	= $x;
						$pids[$wo][$sn]['slot']          	= $s;
						$pids[$wo][$sn]['dut']           	= $d;
						$pids[$wo][$sn]['state']         	= $device_slot['state'];
						$pids[$wo][$sn]['t_burn']        	= $device_slot['tburned'];
						$pids[$wo][$sn]['t_end']         	= $device_slot['t2end'];

						//count each item families
						if($family)  $family_list[$family][] = $sn;

						$mn = $device_slot['pn'];
						if ($device_slot['state'] == "8")   $pid_list[$wo][$tester][$mn]['passed']++;
						elseif ($device_slot['state'] == "3")   $pid_list[$wo][$tester][$mn]['failed']++;
						else $pid_list[$wo][$tester][$mn]['testing']++;

						$pid_list[$wo][$tester][$mn]['model_number'] = $device_slot['pn'];
						if ($device_slot['tburned'] > $pid_list[$wo][$tester][$mn]['timeburned'])
							$pid_list[$wo][$tester][$mn]['timeburned'] = $device_slot['tburned'];
						if ($device_slot['t2end'] > $pid_list[$wo][$mn]['time2end'])
							$pid_list[$wo][$tester][$mn]['time2end'] = $device_slot['t2end'];
						$pid_list[$wo][$tester][$mn]['tester'] = $tester;
					}
				}
			}
		}
	}
return $pid_list;
//return $pids;
	}


function add_burnin_to_table($tester_names, $doc_array)
{
global $burnin_details;
	$burnin_details = array();
global $pids;
	$pids = array();
global $family_list;
global $acburnin_tester_names;
global $burnin_tester_names;
foreach ($tester_names as $tester) {

		for ($x=1;$x<=4;$x++) {  // cages
			$cage = $x;
			for ($s = 1; $s <= 16; $s++) {  // each slot
				//  testing state
				//	NOTPRESENT = 0
				//	LOADED = 1
				//	INITIALIZING = 2
				//	FAILED = 3
				//	INITFAIL = 4
				//	INCOMPLETE = 5
				//	IDLE = 6
				//	TESTING = 7
				//	PASSED = 8
				$slot = $s;
				for ($d = 1; $d <=6; $d++) {
					$dut =$d;
					$device = $doc_array[$tester][$cage][$slot][$dut];
					if($device){
					//echo "$x $s $d $tester <br>";
						$wo     = ($device['wo']) ? $device['wo'] : "N/A";
						$sn     = $device['sn'];
						$family = get_family($device['pn']);
						// create $pids array for detail view of each pid
						//if($wo == "N/A") { aa($device); $kl++;}
						$pids[$wo][$sn]['model_number']  = $device['pn'];
						$pids[$wo][$sn]['item_family']   = $device['mod'];
						$pids[$wo][$sn]['tester']        = $tester;
						$pids[$wo][$sn]['serial_number'] = $sn;
						$pids[$wo][$sn]['cage']          = $x;
						$pids[$wo][$sn]['slot']          = $s;
						$pids[$wo][$sn]['dut']           = $d;
						$pids[$wo][$sn]['state']         = $device['teststate'];
						$pids[$wo][$sn]['t_burn']        = $device['tburned'];
						if(in_array($tester,$acburnin_tester_names)) 	$device['tburned'] = $device['tburned'] * 3600;
						$pids[$wo][$sn]['t_end']         = $device['t2end'];

						//count each item families
						if($family){$family_list[$family][] = $sn;}

						$mn = $device['pn'];
						if ($device['teststate'] == "8")   $pid_list[$wo][$tester][$mn]['passed']++;
						elseif ($device['teststate'] == "3")   $pid_list[$wo][$tester][$mn]['failed']++;
						else $pid_list[$wo][$tester][$mn]['testing']++;

						$pid_list[$wo][$tester][$mn]['model_number'] = $device['pn'];
						if ($device['tburned'] > $pid_list[$wo][$tester][$mn]['timeburned'])
							$pid_list[$wo][$tester][$mn]['timeburned'] = $device['tburned'];
						if ($device['t2end'] > $pid_list[$wo][$mn]['time2end'])
							$pid_list[$wo][$tester][$mn]['time2end'] = $device['t2end'];
						$pid_list[$wo][$tester][$mn]['tester'] = $tester;
					}
				}
			}
		}
	}
return $pid_list;
}

//Sorting Array
function multisort_array($x_array, $key, $order)
    {
        // create the sorting array keys

        foreach ($x_array as $val) {
           $sortarray[] = $val[$key];
        }

        // sort based on the required sort order, but only if theres
        // really something to sort


        if (!empty($sortarray))
           array_multisort($sortarray,$order, $x_array);

        return $x_array;

    } // multisort_array


function get_family($item) {

	global $model_list;
	global $conn_id;

	if (!$model_list[$item])  {

		$query = "select
				    INV_PROD_FAM_CD as item
				  from
				    master_item_tbl f
				  where
				    f.inv_item_id = '$item'";

    	$er=TGAMAIN_RunQuery($conn_id, $query, $reply, $er, $sort_key, $sort_order, FALSE);

    	$family = $reply[0]['item'];

    	$model_list[$item] = $family;

	}
	else
		$family = $model_list[$item];

	return $family;

}



















//functions for burnin_table_view.php









function show_table_tofinish(&$info,$sort_by, $sort_order)
{
    global $family_list;
    global $href;
    global $limit,$timelimit;

    $family_list = array();

    for (reset($info);  list($pid, $testers)=each($info); ) {

      if ($pid){
        foreach($testers as $tester=>$tester_ar){
//          $j++;
          foreach($tester_ar as $mn=>$detail){

            $end_time = time() + ($detail['time2end'] * 3600);
            $end_time = date('Y-m-d H:i',$end_time);

            if(strtotime($end_time) <= $limit){
  //          ss("endtime =".strtotime($row['endtime']),"limit = ".$limit);

              $j++;

              $arr[] = array(  "#" => $j,
                               "workorder" => $pid,
                               "tester" => $tester,
                               "model_number" => $detail['model_number'],
                               "passed" => $detail['passed'],
                               "failed" => $detail['failed'],
                               "testing" => $detail['testing'],
                               "timeburned" => round($detail['timeburned']/3600, 2),
                               "time2end" => $detail['time2end'],
                               "endtime" => $end_time);

              $total_passed  += $detail['passed'];
              $total_failed  += $detail['failed'];
              $total_testing += $detail['testing'];

              $family_list[$detail['item_family']]['passed'] += $detail['passed'];
              $family_list[$detail['item_family']]['failed'] += $detail['failed'];
              $family_list[$detail['item_family']]['testing'] += $detail['testing'];
            }
          }
        }
  	  } //if ($pid)
    }//for


    //group the item families to Fastrak, VI200 and VIC
    $fastrak = array("FTMICRO","FTMINI","FTMAXI");
    $vij200 = array("SMD200","SMDVIJ00");
    $fam_count = array();

    foreach($family_list as $fam=>$sn){
      if(in_array($fam,$fastrak))
        $fam_count['FASTRAK']['count'] += array_sum($sn);
      elseif(strstr($fam,"VIC"))
        $fam_count['VI-CHIP']['count'] += count($sn);
      elseif(@in_array($fam,$vij200))
        $fam_count['VI200/VIJ00']['count'] += array_sum($sn);
      else
        $fam_count['Others']['count'] += array_sum($sn);
    }

    // flip the polarity of the sort order
    // and figure out the image to show

    if ($sort_order == SORT_DESC)  {
        $m="-";   // make the nunber negative
        $img = "&nbsp;&nbsp;<img src='http://teweb/teweb/graphics/desc.gif'>";
    }
    else {
        $m="";    // dont make the number negative
        $img = "&nbsp;&nbsp;<img src='http://teweb/teweb/graphics/desc.gif'>";
    }


    if($_GET['ts']) $ts = "&ts=".$_GET['ts']; else $ts ="";
    $g = "&t=".time();
    if($_GET['limit']) $lim = "&limit=".$_GET['limit']; else $lim ="";
    if($_GET['sort']) $srt = "&sort=".$_GET['sort']; else $srt ="";

    //default to endtime sort
    if (!$_GET['sort']) $sort_by = 9;
    echo "<table align = center border='1' cellpadding = 0>\n";
    echo "<tr><td>\n";
    echo "  <table align = center border='0' width=100% cellpadding =4 cellspacing=0>\n";
    echo "    <tr bgcolor=#CEE8F2 align='center'><td colspan=4> <b>Count Summary by product type<b></td></tr>\n";
    foreach($fam_count as $fam=>$cnt){
      echo "  <tr bgcolor=#CEE8F2 >\n";
      echo "    <td width=25%%>&nbsp;</td><td align='left'> ${fam} :</td><td align=left >".$cnt['count']."</td><td width=25%%>&nbsp;</td>\n";
      echo "  </tr>\n";
    }
    echo "  </table>";
    echo "</tr></td>\n";
    echo "<form name='change_limit' method='GET' action='$PHP_SELF' >\n";
//Put text field to enter limit
    echo "<tr align='center'><td>\n";
    echo "<b>Change time limit:</b><select name='limit'>\n";

    $tnow = date(H,time());

    for ($t=1;$t<=24;$t++ ){
      $tlist = $tnow+$t;
      if ($tlist >= 36){
        if($tlist == 36)
          $text = $tlist-24;
        else
          $text= $tlist - 36;
        $text .= " PM (+1)";
      }
      elseif ($tlist >= 24){
        if($tlist == 24)
          $text = $tlist-12;
        else
          $text= $tlist - 24;
        $text .= " AM (+1)";
      }
      elseif ($tlist>= 12){
        if($tlist == 12)
          $text = $tlist;
        else
          $text= $tlist - 12;
        $text .= " PM";
      }else
        $text = $tlist." AM";

      $selected = ($tlist == $timelimit) ? " selected" : "";
      echo "<option  value='$tlist' $selected>$text</option>\n";
    }
    echo "</select>\n";
    echo "          <input type='submit' name='submit' value='Submit' >\n";
    echo "  </td></td>\n";
    echo "</form>\n";
    echo "<tr><td>\n";
    echo "  <table align = center border='1' cellpadding = 4>\n";
    echo "  <tr bgcolor = '#CCCCCC'> \n";

    echo "<td align = center><b>#";
    if ($sort_by == 1) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}2${g}${ts}${lim}'>Workorder</a>";
    if ($sort_by == 2) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}3${g}${ts}${lim}'>Model</a>";
    if ($sort_by == 3) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}10${g}${ts}${lim}'>Tester</a>";
    if ($sort_by == 10) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}4${g}${ts}${lim}'>Passed</a>";
    if ($sort_by == 4) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}5${g}${ts}${lim}'>Failed</a>";
    if ($sort_by == 5) echo $img;    echo  "</b></td>\n";
    echo "<td>               <b><a href = '${PHP_SELF}?sort=${m}6${g}${ts}${lim}'>Testing</a>";
    if ($sort_by == 6) echo $img;    echo  "</b></td>\n";
    echo "<td align = center><b><a href = '${PHP_SELF}?sort=${m}7${g}${ts}${lim}'>Time Burned</a>";
    if ($sort_by == 7) echo $img;    echo  "</b></td>\n";
    echo "<td align = center><b><a href = '${PHP_SELF}?sort=${m}8${g}${ts}${lim}'>Time to end</a>";
    if ($sort_by == 8) echo $img;    echo  "</b></td>\n";
    echo "<td align = center><b><a href = '${PHP_SELF}?sort=${m}9${g}${ts}${lim}'>End time</a>";
    if ($sort_by == 9) echo $img;    echo  "</b></td>\n";

            switch ($sort_by) {
                case 1:     $ss_by = "#";                    break;
                case 2:     $ss_by = "workorder";            break;
                case 3:     $ss_by = "model_number";         break;
                case 4:     $ss_by = "passed";          	   break;
                case 5:     $ss_by = "failed";          	   break;
                case 6:     $ss_by = "testing";     		     break;
                case 7:     $ss_by = "timeburned";           break;
                case 8:     $ss_by = "time2end";             break;
                case 9:     $ss_by = "endtime";              break;
                case 10:    $ss_by = "tester";              break;
            }


    // now sort the display array based on the required heading and sort order
    $arr = multisort_array($arr, $ss_by, $sort_order);

    $j=0;
    $cnt = count($arr);
    $total_testing = 0;


    for ($ds = 0; $ds < $cnt; $ds++) {

        $row = $arr[$ds]; //$href="";

          if($row['workorder'] <> "N/A")
        	  $pid_ref = 	"<a href = burnin_pid_detail.php?pid=${row['workorder']}>${row['workorder']}</a>";
          else
            $pid_ref = $row['workorder'];

            $j++;
            if (($j/2) == intval($j/2)) $bg = "bgcolor = '#FFFFCC'"; else $bg = "bgcolor = '#CCFFCC'";
            echo "<tr ${bg}>\n";
            echo "<td> ${j}</td>\n";
            echo "<td> ${pid_ref}</td>\n";
            echo "<td> ${row['model_number']}&nbsp;</td>\n";
            echo "<td> ${row['tester']} </td>\n";
            $passed = ($row['passed']) ? $row['passed'] : "&nbsp;";
            echo "<td> $passed </td>\n";
            $failed = ($row['failed']) ? $row['failed'] : "&nbsp;";
            echo "<td> $failed </td>\n";
            $testing = ($row['testing']) ? $row['testing'] : "&nbsp;";
            echo "<td> $testing </td>\n";
            $timeburned = ($row['timeburned']) ? $row['timeburned'] : "&nbsp;";
            echo "<td> $timeburned </td>\n";
            $time2end = ($row['time2end']) ? $row['time2end'] : "&nbsp;";
            echo "<td> $time2end </td>\n";
            $endtime = ($row['endtime']) ? $row['endtime'] : "&nbsp;";
            echo "<td> $endtime</td>\n";
            echo "</tr>\n";
            $old_id = $id;

            $total_testing += $row['testing'];

    }

    echo "  <tr bgcolor = '#CCCCCC'> \n";

    echo "    <td colspan = 4>Totals </td><td>${total_passed} </td> <td>${total_failed} </td> <td>${total_testing} </td><td colspan = 3>&nbsp;</td></tr>";
    echo "  </table>\n";
    echo "</tr></td>\n";
    echo "</table>\n";
/*
    $tnow = date(H,time());
    for ($t=1;$t<=24;$t++ ){
      $tlist = $tnow+$t;
ss("tlist =",$tlist);
      if ($tlist > 24){
        $text= $tlist - 24;
        $text .= " AM";
      }
      elseif ($tlist> 12){
        $text= $tlist - 12;
        $text .= " PM";
      }else
        $text = $tlist." AM";

      $selected = ($t == $timelimit) ? " selected" : "";
    }*/

}










?>