<?
error_reporting(0);

/* Variables */
$database="zenrobin";
$rr_table="robintable"; /* round robin main table */
$rrh_table="awarded_loot"; /* round robin history table of awarded loot */
$user="zenrobin";
$pw="Welcome01";
$server = "localhost";
$default_view="Druid";
$adminpw="Passw0rd01";

/* globals */
global $adminflag, $submit, $conn, $db, $rr_table, $rrh_table, $viewselect, $adminpw, $pwd;
global $cname, $class, $order, $lastlooted, $firstpass, $secondpass, $added, $sort;
global $todelete,$editmode;
global $secondpass, $lootname, $loottype, $dkppaid,$dateawarded;

/* check admin */
if($rrcookie){
  /* ensure the cookie value = the adminpw */
  if($rrcookie==md5($adminpw))  $adminflag=1;
  else $adminflag=0;
}

$conn = mysql_connect($server,$user,$pw) or die ("Error connecting to Database");
$db=mysql_select_db($database);


/* IF STATEMENTS FOR SUB FUNCTION CALLS FOR PROCESSES */
if ($submit != "alogin") {
?>
<html>
<head>
<title>ZenRobin: Round Robin Loot Tool v1.00</title>
<?php if ($adminflag) { ?><script src="js/rr.js" type="text/javascript"></script><?php } ?>

<link rel="stylesheet" href="rr.css" type="text/css" media="screen">
<link href="http://www.ocguild.org/menu.css" rel="stylesheet" type="text/css">
<script src="http://www.ocguild.org/javascript/menu.js" type="text/javascript"></script>
</head>
<body <?php if ($submit == "award") { ?>onLoad="dkp_item_names(document.forms['award'].lootname)" <?php } ?>>
<div id="dkp_menu" class="dropmenudiv">
	<a href="http://www.ocguild.org/dkp.htm">DKP Rules</a>
	<a href="http://www.ocguild.org/dkp/">Current DKP Totals</a>
	<a href="http://www.ocguild.org/rr/">Round Robin Standings</a>
</div>
<div id="raid_menu" class="dropmenudiv">
	<a href="http://www.ocguild.org/raidrules.htm">Raid Rules</a>
	<a href="http://www.ocguild.org/signups/">Raid Signups
	<a href="http://www.ocguild.org/5man/" target="_top">5man Signups</a></a>
</div>
<table width="100%" border="0" cellspacing="1" cellpadding="10" class="borderless">
  <tr>
   <td width="206" height="171"><span class="oc_header"><a href="http://www.ocguild.org/index.htm"><img src="http://www.ocguild.org/images/newlogo.gif" width="206" height="171" border="0" alt="Oceania Main Page"/></a></span> </td>
   <td>
	<span class="menuitem1"><a href="http://www.ocguild.org/forums/"><img src="http://www.ocguild.org/images/forumsbutton.gif" border="0"></a></span>
	<span class="menuitem2"><a href="#" onMouseover="cssdropdown.dropit(this,event,'dkp_menu')"><img src="http://www.ocguild.org/images/dkpbutton.gif" border="0"></a></span>
	<span class="menuitem3"><a href="#" onMouseover="cssdropdown.dropit(this,event,'raid_menu')"><img src="http://www.ocguild.org/images/raidsbutton.gif" border="0"></a></span>
    <span class="menuitem4"><a href="http://www.ocguild.org/roster.htm"><img src="http://www.ocguild.org/images/rosterbutton.gif" border="0"></a></span>
    <span class="menuitem5"><a href="http://www.ocguild.org/links.htm"><img src="http://www.ocguild.org/images/linksbutton.gif" border="0"></a></span>
	<table cellspacing="0" cellpadding="2" border="0" class="borderless">
	 <tr>
       <td align="center" valign="top" nowrap="nowrap"><span class="menu">This spot looks so lonely and barren if there's no text here...</span></td>
     </tr>
    </table>
   </td>
  </tr>
</table>
<center>
<h2>Round Robin Order</h2>
<?php if (!$viewselect) { $viewselect = $default_view; } ?>
<form name="selectform" method="post" <?php if ($submit == "award") { echo "action=\"index.php?submit=award\""; } if ($submit == "history") { echo "action=\"index.php?submit=history\""; } ?>>
	<p>Limit view to [<strong><?php echo $viewselect; ?></strong>] class only:
	<?php if ($submit == "award") { create_dropdown("viewselect","onChange=\"JavaScript:change_action()\""); } else { create_dropdown("viewselect","onChange=\"JavaScript:submit()\""); }?>
	</p>
</form>

<? }
if ($submit=="Add") { add_function(); }
elseif ($submit=="update") { update_function(); }
elseif ($submit=="update_history") { update_history_function(); }
elseif ($submit=="stats") { showstats_function(); }
elseif ($submit=="history") { history_function(); }
elseif ($submit=="editmodeh") { history_function(); }
elseif ($submit=="award") { award_function(); }
elseif ($submit=="alogin") { adminlogin_function(); }
else {
/* Main Display Area with Add form etc */
?>

<h3><?php echo $viewselect ?> Listing</h3>
<?php
/* work out edit mode stuff for form over listed table */
if (!$editmode) { $editmode = 0; }
elseif ($editmode == "Off") { $editmode = 0; }
elseif ($editmode == "On") { $editmode = 1; }
if ($editmode == 1) { echo "<form method=\"post\">\n"; }
?>
<table width="*" border="0" cellspacing="1" cellpadding="2">
<tr>
  <th>Order</th>
  <th>Name</th>
  <th>Last Looted</th>
  <th>First Pass</th>
  <th>Second Pass</td>
  <th>Added</th>
  <?php if ($editmode == 1) { echo "<th>Delete?</th>"; } ?>
</tr>
<?
/* Display table contents */
$query = "SELECT * FROM $rr_table WHERE class='$viewselect' ORDER BY rr_order ASC";
$result = mysql_query($query,$conn);
if (!$result || !mysql_num_rows($result)) { $rows = 0; } else { $rows = mysql_num_rows($result); }

if ($editmode == 0) {
  for ($i=0; $i<$rows; $i++) {
  	$row_style = ($i % 2) ? "row1" : "row2";
  ?>
<tr class="<?php echo $row_style; ?>">
	<td align="center"><?php echo mysql_result($result, $i, "rr_order") ?></td>
	<td><a href="../dkp/viewmember.php?s=&name=<?php echo mysql_result($result, $i, "name"); ?>"><?php echo mysql_result($result, $i, "name"); ?></a></td>
	<td align="center"><?php if (mysql_result($result, $i, "lastlooted") == "0000-00-00") { echo "n/a"; } else { echo ustonzdate(mysql_result($result, $i, "lastlooted")); } ?></td>
	<td align="center"><?php if (mysql_result($result, $i, "firstpass") == "0000-00-00") { echo "n/a"; } else { echo ustonzdate(mysql_result($result, $i, "firstpass")); } ?></td>
	<td align="center"><?php if (mysql_result($result, $i, "secondpass") == "0000-00-00") { echo "n/a"; } else { echo ustonzdate(mysql_result($result, $i, "secondpass")); } ?></td>
	<td align="center"><?php if (mysql_result($result, $i, "added") == "0000-00-00") { echo "n/a"; } else { echo ustonzdate(mysql_result($result,$i,"added")); } ?></td>
</tr>
  <?php
  } //end for ()
} elseif ($editmode==1) { /* display with input boxes */
  for($i=0;$i<$rows;$i++) {
   	$row_style = ($i % 2) ? "row1" : "row2";
  ?>
<tr class="<?php echo $row_style; ?>">
    <td><center><input type="text" size="2" maxlength="2" value="<?php echo mysql_result($result,$i,"rr_order"); ?>" name="order[]"></center></td>
	<td><input type="hidden" name="class[]" value="<?php echo mysql_result($result,$i,"class"); ?>"><input type="text" size="15" maxlength="15" value="<?php echo mysql_result($result,$i,"name"); ?>" name="cname[]"></td>
    <td align="center"><input type="text" size="9" maxlength="10" value="<?php echo ustonzdate(mysql_result($result,$i,"lastlooted")); ?>" name="lastlooted[]"></td>
    <td align="center"><input type="text" size="9" maxlength="10" value="<?php echo ustonzdate(mysql_result($result,$i,"firstpass")); ?>" name="firstpass[]"></td>
    <td align="center"><input type="text" size="9" maxlength="10" value="<?php echo ustonzdate(mysql_result($result,$i,"secondpass")); ?>" name="secondpass[]"></td>
    <td align="center"><input type="text" size="9" maxlength="10" value="<?php echo ustonzdate(mysql_result($result,$i,"added")); ?>" name="added[]"></td>
    <td align="center"><input type="checkbox" name="todelete[]" value="<?php echo $i; ?>"></td>
</tr>
<?php } // end for() ?>
<tr>
	<td colspan="6"><input type="hidden" value="<?php echo $viewselect; ?>" name="viewselect"><center><input type="submit" name="submit" value="update"></center></td>
</tr>
<?php
} ?>
</table>
<?php
if ($editmode == 1) { echo "</form>\n"; }
?>
<p>View [<a href="index.php?submit=history&viewselect=<?php echo $viewselect; ?>">Awarded Loot History</a>] for [<b><?php echo $viewselect; ?></b>] class<?php if ($adminflag) { ?> or [<a href="index.php?submit=award&viewselect=<?php echo $viewselect; ?>">Award</a>] a [<b><?php echo $viewselect; ?></b>] a loot item for the record <?php } ?></p>

<?php
if ($editmode == 0) { $editmodetext = "Off"; $editmodetextopposite = "On";}
	else { $editmodetext = "On"; $editmodetextopposite = "Off";}
if ($adminflag) {
  echo "<br />Edit mode is [<strong>$editmodetext</strong>] - Turn it [<a href=\"index.php?submit=editmode&editmode=$editmodetextopposite&viewselect=$viewselect
  \">$editmodetextopposite</a>]";
}
echo"<br />[<a href='index.php?submit=stats&viewselect=$viewselect'>Show Overall Stats</a>] for [<strong>everyone</strong>].\n";

if($adminflag){
?>
<hr>
<p>Add new player to RR List:</p>
<form name="addentry" method="post">
<table width="*" border="0">
<tr>
  <th>Name</th>
  <th>Class</th>
  <th>Order</th>
  <th>Last Looted</th>
  <th>First Pass</th>
  <th>Second Pass</th>
  <?php if ($editmode == 1) { echo "<th>Delete?</th>"; } ?>
</tr>
<tr>
  <td><input type="text" size="15" maxlength="15" name="cname"></td>
  <td>
  	<?php create_dropdown("class"); ?>
  </td>
  <td><input type="text" size="2" maxlength="2" name="order"></td>
  <td><input type="text" size="15" maxlength="15" name="lastlooted"></td>
  <td><input type="text" size="15" maxlength="15" name="firstpass"></td>
  <td><input type="text" size="15" maxlength="15" name="secondpass"></td>
  <td><input type="submit" name="submit" value="Add"></td>
</tr>
</table>
<input type="hidden" name=viewselect value="<?php echo $viewselect; ?>">
</form>
<?php
} /* adminflag if */
  if(!$adminflag) ?>
	<br />
	<p>
	<form name="lf" method="post">
	  <input type="hidden" name="submit" value="alogin"><input type="hidden" name="viewselect" value="<?php echo $viewselect; ?>"><input style="font-size: 3pt;" type="password" onChange="JavaScript:submit();" name="pwd" size="10">
	</form></p>
<?php } /* end if() */
?>
</center>
</body>
</html>

<?php
/************************************/
/* 			 Functions 				*/
/************************************/

function create_dropdown($name, $options) {
	$classes = array("Druid", "Hunter", "Mage", "Paladin", "Priest", "Rogue", "Warlock", "Warrior");

	echo "<select name=\"$name\" $options>\n";
	echo "<option selected></option>\n";
	foreach ($classes as $class) {
		echo "<option value=\"$class\">$class</option>\n";
	}
	echo "</select>\n";
}

function add_function(){
  global $adminflag,$submit, $conn, $db, $rr_table, $rrh_table, $viewselect;
  global $cname, $class, $order, $lastlooted, $firstpass, $secondpass, $added;

/* Debugging
  echo"Variables:\n";
  echo"<br>$cname, $order, $class, $lastlooted, $firstpass, $secondpass, $added";
*/
  if (!$order) {
  	// Get highest rr_order value
	$query = "select max(rr_order) as top_rr from $rr_table where class = '$class'";
	$result = mysql_query($query, $conn);
	$top_order = mysql_fetch_array($result);
	// Assign $order a new value
	$order = $top_order['top_rr']+1;
  }

  // Bump everyone below $order down by one to accomodate for new person if they're going to be inserted mid-order
  $query = "update $rr_table set rr_order = rr_order+1 where rr_order >= $order AND class = '$class'";
  mysql_query($query, $conn) or die(mysql_error());

  $added=date("Y-m-d");
  $query="INSERT INTO $rr_table VALUES ('$cname','$class','$order','$lastlooted','$firstpass','$secondpass','$added')";
#  echo"$query<br>";
  mysql_query($query,$conn);

  echo"<p align=\"center\"><br /><br /><br /><br /><br />New player added... Returning in 1 second...</p>";
/* make the viewselect change if adding a class different to the one viewing */
  if($viewselect != $class) $viewselect=$class;
  echo"<meta HTTP-EQUIV='Refresh' CONTENT='1;URL=index.php?viewselect=$viewselect'>";
  exit;
}


function update_function(){
  global $adminflag,$submit, $conn, $db, $rr_table, $rrh_table, $viewselect;
  global $cname, $class, $order, $lastlooted, $firstpass, $secondpass, $added;
  global $todelete;
/* DEBUG
  for($r=0;$r<count($cname);$r++)
  {
    echo" Line $r is : ";
    $delete=check_delete($r,$todelete);
    if(!$delete){
    echo $cname[$r]." ";
    echo $order[$r]." ";
    echo $lastlooted[$r]." ";
    echo $secondpass[$r]." ";
    echo $added[$r]."<br> ";
    }
    else echo $cname[$r]." deleted.<br>\n";
  }
*/
/* Update to delete all entries for the viewselect (class) and then re-add the information. */
/* all info required therefore, cnames classes added also.. delete flag */
  $query="DELETE FROM $rr_table WHERE class='$viewselect'";
  #echo"$query<br>\n";
  $result=mysql_query($query,$conn);
  for($r=0;$r<count($cname);$r++)
  {
    $delete=check_delete($r,$todelete);
    if(!$delete){
      $query="INSERT INTO $rr_table VALUES ('$cname[$r]','$viewselect','$order[$r]','".nztousdate($lastlooted[$r])."','".nztousdate($firstpass[$r])."','".nztousdate($secondpass[$r])."','".nztousdate($added[$r])."')";
      #echo"$query<br>\n";
      $result=mysql_query($query,$conn);
    }
  }
  echo"<center><br /><br /><br /><br /><br /><p>Round Robin order updated... Returning to $viewselect listing...</p></center>";
  echo"<meta HTTP-EQUIV='Refresh' CONTENT='1;URL=index.php?viewselect=$viewselect'>";
  exit;
}

function update_history_function(){
  global $adminflag,$submit, $conn, $db, $rr_table, $rrh_table, $viewselect;
  global $cname, $class, $order, $lastlooted, $firstpass, $secondpass, $added;
  global $todelete,$editmode;
  global $secondpass, $lootname, $loottype, $dkppaid, $dateawarded;

/* DEBUG
  for($r=0;$r<count($cname);$r++)
  {
    echo" Line $r is : ";
    $delete=check_delete($r,$todelete);
    if(!$delete){
    echo $cname[$r]." ";
    echo $order[$r]." ";
    echo $lastlooted[$r]." ";
    echo $secondpass[$r]." ";
    echo $added[$r]."<br> ";
    }
    else echo $cname[$r]." deleted.<br>\n";
  } */


/* Update to delete all entries for the viewselect (class) and then re-add the information. */
/* all info required therefore, cnames classes added also.. delete flag */
  $query="DELETE FROM $rrh_table WHERE class='$viewselect'";
// echo"$query<br>\n";
  $result=mysql_query($query,$conn);
  for($r=0;$r<count($cname);$r++)
  {
    $delete = check_delete($r,$todelete);
    if (!$delete) {
      $query = "INSERT INTO $rrh_table VALUES ('$cname[$r]','$viewselect','$lootname[$r]','$loottype[$r]','".nztousdate($dateawarded[$r])."','$dkppaid[$r]')";
//      echo"$query<br>\n";
      $result = mysql_query($query,$conn);
    }
  }
  echo"<center><br /><br /><br /><br / ><br /><p>History records updated... Returning to $viewselect history...</p></center>";
  echo"<meta HTTP-EQUIV='Refresh' CONTENT='1;URL=index.php?submit=history&viewselect=$viewselect'>";
  exit;
}


function check_delete($value,$todelete) {
  $flag = 0;
  for ($o = 0; $o<count($todelete); $o++){
    if ($value == $todelete[$o]) { $flag = 1; }
  }
  return $flag;
}

function award_function() {
  global $adminflag,$submit, $conn, $db, $rr_table, $rrh_table, $viewselect;
  global $cname, $class, $order, $lastlooted, $firstpass, $secondpass, $added;
  global $secondpass, $lootname, $loottype, $dkppaid, $other_loot, $rr_update_toggle;

  if ($secondpass != "true") {
    /* List the Class Members */
    $query = "SELECT * FROM $rr_table WHERE class='$viewselect' ORDER BY rr_order ASC";
    $result = mysql_query($query,$conn);

    if (!$viewselect) { $viewselect = $default_view; }
?>
	<center>
	<p>Class to award loot to is currently: <strong><?php echo $viewselect; ?></strong>.</p>
	<form id="award" name="award" action="index.php" onSubmit="return check_award_form(this);">
	<table border="0" cellspacing="1" cellpadding="2">
	 <tr>
	  <td>Award:
	   <select name="cname">
	    <option value="null"></option>
		<?php for ($i=0; $i<mysql_num_rows($result); $i++) { echo"<option value=\"" .mysql_result($result,$i,"name") ."\">" .mysql_result($result,$i,"name") ."</option>\n"; } ?>
	   </select>
	  </td>
	  <td>Loot Type:
	   <select name="lootname" onChange="dkp_item_names(this)">
	    <option value="null" selected></option>
		<option value="Tier 1">Tier 1</option>
		<option value="Tier 2">Tier 2</option>
		<?php if (($viewselect == "Hunter") || ($viewselect == "Priest")) { ?><option value="Epic Quest">Epic Quest</option> <?php } ?>
		<option value="Other">Other Loot</option>
	   </select>
	  </td>
	  <td>Name:
	   <select name="loottype" >
	    <option value="null">Select a Loot Type...</option>
	   </select>
	   <input name="other_loot" type="text" maxlength="30" size="20" style="display: none;">
	  </td>
	  <td>DKP Cost of: <input type="text" name="dkppaid" id="dkppaid" size="4" maxlength="4">
	  <label id="no_rr_update" style="display: none;">No RR update?&nbsp;<input type="checkbox" id="rr_update_toggle" name="rr_update_toggle"></label>
	  </td>
	 </tr>
	 <tr>
	  <td colspan="4" align="center">
	   <input type="submit" name="submit" value="award">
	   <input type="hidden" name="secondpass" value="true">
	   <input type="hidden" name="viewselect" value="<?php echo $viewselect; ?>">
	  </td>
	 </tr>
	</table>
	</form>
	<table width="80%">
	 <tr>
	  <td>
	   <p><center><h3>Information about the award process</h3></center>
	   <ul>
	   <li>Order of names for the class is in their respective round robin orders. Top of the list = 1st.</li>
       <li>Awarding a person a loot will make them go to the bottom of the list and everyone else move up.</li>
       <li>If you award an item to someone not in 1st position (ie 3rd on the list) then the positions before the awardee will stay the same, only the people after the person awarded will go up a step, and the person awarded will go to the bottom.</li>
       <li>Awarded loot is recorded in the history table.</li>
       <li>The person who has been awarded loot will be tagged as Last Looted as of the date you awarded the item in here. If you wish to change this use the global edit feature from the mainpage.</li>
	   <li>If you select <em>Other</em> as the Loot Type, the Round Robin order <strong>will not</strong> be updated - the item will be recorded in the loot history</li>
	   <li>Ticking the box <em>No RR update?</em> will enable you to enter a Tier 1 or Tier 2 item but <strong>not</strong> have the Round Robin order changed</li>
       <br />
	   </ul>
	   </p>
	  </td>
	 </tr>
	</table>
	<p><center><a href='index.php?viewselect=<?php echo $viewselect; ?>'>Back to Main Page</a></center></p>
<?php
  }
  else
  {
    /* Do the awarding process */
    /* if the person who has been awarded the loot is not at the top of the list this has to be taken into account */
    /* log a note about the last awarded loot for the class, then put the person at the bottom of the list */
    /* count the array for total number - work out last number. move bottom to top - current to bottom. */
    /* everyone else looses 1, to move up the list  */
#    echo "$cname, $lootname, $loottype, $dkppaid<br>\n";

	if (($rr_update_toggle != "on") && ($lootname != "Other")) {

		$query = "select rr_order from $rr_table where name = '$cname'";
		$result = mysql_query($query, $conn);
		$player = mysql_fetch_array($result);

		$query = "update rr_order set rr_order = -2 where name = '$cname'";
		mysql_query($query, $conn); // Knock the current person back down the bottom
		//echo $player['rr_order'] ."<br />";

		// Move everyone who was below player down the order by 1
		$query = "update $rr_table set rr_order = rr_order-1 where rr_order > " .$player['rr_order'] ." AND class = '$viewselect'";
		mysql_query($query, $conn);
		//echo $query ."<br><br>";

		// Get the top rr_order value for the next update
		$query = "select MAX(rr_order) as top_rr from $rr_table where class = '$viewselect'";
		$result = mysql_query($query, $conn);
		$top_order = mysql_fetch_array($result);

		// Update member's position to bottom
		$query = "update $rr_table set rr_order = " .$top_order['top_rr'] ."+1 where name = '$cname'";
		//echo $query ."<br />";
		mysql_query($query, $conn) or die (mysql_error());

    } // end if ($lootname == "Other") {}
    /* Update the Last looted for the $name */
    $query = "UPDATE $rr_table SET lastlooted='".date("Y-m-d")."' WHERE name='$cname'";
    $result=mysql_query($query,$conn);
    //echo"$query<br><br>\n";

    /* Insert into the history table */
	if ($lootname == "Other") {
	 $query="INSERT INTO $rrh_table (name, class, awarded, loot_type, date_awarded, dkp_used) VALUES ('$cname','$viewselect','$other_loot','Other','".date("Y-m-d")."','$dkppaid')";
	} else {
	 $loot_info = preg_split('/-/', $loottype);
     $query="INSERT INTO $rrh_table (name, class, awarded, loot_type, date_awarded, dkp_used) VALUES ('$cname','$viewselect','$loot_info[0]','$loot_info[1]','".date("Y-m-d")."','$dkppaid')";
	}
    $result=mysql_query($query,$conn);
    //echo"$query<br>\n";
    echo"<center><br /><br /><br /><br /><p>Loot awarded... Redirecting in 1 second...</p>";
    echo"<meta HTTP-EQUIV='Refresh' CONTENT='1;URL=index.php?viewselect=$viewselect'>";
    exit;
  }
}

function history_function() {
  global $adminflag,$submit, $conn, $db, $rr_table, $rrh_table, $viewselect;
  global $cname, $class, $order, $lastlooted, $firstpass, $secondpass, $added, $sort;
  global $todelete,$editmode;
  global $secondpass, $lootname, $loottype, $dkppaid, $dateawarded;

  if (!$sort) { $sort = "date_awarded"; }
  $query = "SELECT * FROM $rrh_table WHERE class='$viewselect' ORDER BY $sort";
  if ($sort == "date_awarded") { $query = $query ." DESC;"; } else { $query = $query ." ASC;"; }
  $history = mysql_query($query,$conn);

  if (!$viewselect) { $viewselect = $default_view; }
?>
 <p>Viewing <strong><?php echo $viewselect; ?></strong> loot history</p>
<?php
  if (!$editmode) { $editmode = 0; }
  elseif ($editmode == "Off") { $editmode = 0; }
  elseif ($editmode == "On") { $editmode = 1; }
  if ($editmode == 0) { $editmodetext = "Off"; $editmodetextopposite = "On"; }
    else { $editmodetext = "On"; $editmodetextopposite = "Off"; }
  if ($adminflag) { ?>
  <p>Edit mode is currently [<strong><?php echo $editmodetext; ?></strong>] - Turn it [<a href="index.php?submit=editmodeh&editmode=<?php echo $editmodetextopposite; ?>&viewselect=<?php echo $viewselect; ?>"><?php echo $editmodetextopposite; ?></a>]</p><?php } ?>
<?php
  if ($editmode == 1) { echo "<form method=\"post\">\n"; }
?>
 <table width="60%" border="0" cellspacing="1" cellpadding="2">
  <tr>
   <th><a href="index.php?submit=history&viewselect=<?php echo $viewselect; ?>&sort=name">Name</a></th>
   <th><a href="index.php?submit=history&viewselect=<?php echo $viewselect; ?>&sort=date_awarded">Date Awarded</a></th>
   <th><a href="index.php?submit=history&viewselect=<?php echo $viewselect; ?>&sort=awarded">Item Looted</a></th>
   <th><a href="index.php?submit=history&viewselect=<?php echo $viewselect; ?>&sort=loot_type">Item Type</a></th>
   <th><a href="index.php?submit=history&viewselect=<?php echo $viewselect; ?>&sort=dkp_used">DKP Cost</a></th>
   <?php if ($editmode == 1) { echo "<th>Delete?</th>\n"; } ?>
  </tr>
<?php
  if ($editmode == 1) {
  /* display with input boxes */
    for ($i=0; $i<mysql_num_rows($history); $i++) {
	$row_style = ($i % 2) ? "row1" : "row2";
?>
	<tr class="<?php echo $row_style; ?>">
	 <td><input type="hidden" name="class[]" value="<?php echo $viewselect; ?>"><input type="hidden" name="cname[]" value="<?php echo mysql_result($history,$i,"name"); ?>"><?php echo mysql_result($history,$i,"name"); ?></td>
     <td align="center"><input type="text" size="9" maxlength="10" value="<?php echo ustonzdate(mysql_result($history,$i,"date_awarded")); ?>" name="dateawarded[]"></td>
     <td align="center"><input type="text" size="25" maxlength="50" value="<?php echo mysql_result($history,$i,"awarded"); ?>" name="lootname[]"></td>
     <td align="center"><input type="text" size="10" maxlength="15" value="<?php echo mysql_result($history,$i,"loot_type"); ?>" name="loottype[]"></td>
     <td align="center"><input type="text" size="4" maxlength="4" value="<?php echo mysql_result($history,$i,"dkp_used"); ?>" name="dkppaid[]"></td>
     <td align="center"><input type="checkbox" name="todelete[]" value="<?php echo $i; ?>">&nbsp;</td>
	</tr>
<?php
    } // end for()
  }
  else { // Not in edit mode, normal display
    for ($i=0; $i<mysql_num_rows($history); $i++) {
	$row_style = ($i % 2) ? "row1" : "row2";
?>
	<tr class="<?php echo $row_style; ?>">
	 <td><?php echo mysql_result($history,$i,"name"); ?></td>
	 <td align="center"><?php echo ustonzdate(mysql_result($history,$i,"date_awarded")); ?></td>
	 <td><?php echo mysql_result($history,$i,"awarded"); ?></td>
	 <td><?php echo mysql_result($history,$i,"loot_type"); ?></td>
	 <td align="center"><?php echo mysql_result($history,$i,"dkp_used"); ?></td>
	</tr>
<?php
    } // end for()
  } // end else
  if ($editmode == 1) {
?>
	<tr>
	 <td colspan="6"><input type="hidden" name="viewselect" value="<?php echo $viewselect; ?>"><center><input type="submit" name="submit" value="update_history"></center></td>
	</tr>
<?php } // end if ($editmode == 1) {} ?>
  </table>
<?php
  if ($editmode == 1) { echo "</form>\n"; }
?>
  <p><center><a href="index.php?viewselect=<?php echo $viewselect; ?>">Back to Mainpage</a></center></p>
<?php
} // end history_function()

function adminlogin_function(){
  global $adminflag, $submit, $conn, $db, $rr_table, $rrh_table, $viewselect, $adminpw, $pwd;

  if($pwd==$adminpw){
     $contents=md5($adminpw);
     setcookie("rrcookie",$contents);
     echo"<center><br><br><br><Br><Br>Welcome Administrator. Redirecting to main page..";
     echo"<meta HTTP-EQUIV='Refresh' CONTENT='1;URL=index.php?viewselect=$viewselect'>";
  }
  else{
     echo"<center><br><br><br><Br><Br>Incorrect... Try again";
     echo"<meta HTTP-EQUIV='Refresh' CONTENT='2;URL=index.php?viewselect=$viewselect'>";
  }
  exit;
}

function showstats_function(){
  global $adminflag, $submit, $conn, $db, $rr_table, $rrh_table, $viewselect, $adminpw, $pwd, $sort;
  echo"<br>\n";

  echo"<center><p><a href=\"index.php?viewselect=$viewselect\">Back to Main Page</a></p></center>\n";

  /* class stats */
	$classes = array("Druid", "Hunter", "Mage", "Paladin", "Priest", "Rogue", "Warlock", "Warrior");
?>
	<center>
	 <table border="0" cellspacing="1" cellpadding="2">
	  <tr>
	   <td colspan="2" align="center"><strong>Class Stats</strong></td>
	  </tr>
	  <tr>
	   <th>Class</th>
	   <th>Total Loots</th>
	  </tr>
<?php
	$i = 0;
	foreach ($classes as $class) {
	 $query = "SELECT * FROM $rrh_table WHERE class='$class'";
	 $classstat = mysql_query($query, $conn);
	 $row_style = ($i % 2) ? "row1" : "row2";
	 $i++;
?>
	<tr class="<?php echo $row_style; ?>">
	 <td><?php echo $class; ?></td>
	 <td align="center"><?php echo mysql_num_rows($classstat); ?></td>
	</tr>
<?php
	} // end foreach ($classes as $class) {}
?>
    </table>
</center>
<?php
  /* individual stats */
  if (!$sort) { $sort = "class"; }
  $query = "SELECT * FROM $rr_table ORDER BY $sort ASC";
  $names = mysql_query($query,$conn);
?>
  <hr width="70%">
  <center>
   <table width="80%" border="0" cellspacing="1" cellpadding="2">
    <tr>
	 <td colspan="5" align="center">Player Stats</td>
	</tr>
	<tr>
	 <th><a href='index.php?submit=stats&sort=name'>Name</a></th>
	 <th align="center"><a href="index.php?submit=stats&sort=class">Class</a></th>
	 <th align="center"><a href="index.php?submit=stats&sort=lastlooted">Last Looted</a></th>
	 <th align="center">Total Loots</th>
	 <th align="center">Last Item</th>
	</tr>
<?php
  for ($i=0; $i<mysql_num_rows($names); $i++){
    $query = "SELECT * FROM $rrh_table WHERE name='".mysql_result($names,$i,"name")."' ORDER BY date_awarded DESC";
    $loots = mysql_query($query,$conn);
    $total = mysql_num_rows($loots);
    $row_style = ($i % 2) ? "row1" : "row2";

?>
 	<tr class="<?php echo $row_style; ?>">
	 <td><a href="http://www.ocguild.org/dkp/viewmember.php?name=<?php echo mysql_result($names, $i, "name"); ?>"><?php echo mysql_result($names, $i, "name"); ?></a></td>
	 <td align="center"><?php echo mysql_result($names,$i,"class"); ?></td>
<?php
    if (mysql_result($names,$i,"lastlooted")=="0000-00-00") {
?>
     <td align="center">n/a</td>
<?php
    } else {
?>
     <td align="center"><?php echo ustonzdate(mysql_result($names,$i,"lastlooted")); } // end if/else ?></td>
	 <td align="center"><?php echo $total; ?></td>
	 <td><?php echo mysql_result($loots,0,"awarded")." - ".mysql_result($loots,0,"loot_type"); ?></td>
    </tr>
<?php
  	}
?>
  </table>
 </center>
  <center><a href="index.php?viewselect=<?php echo $viewselect; ?>">Back to Main Page</a></center>
<?php
//  exit;
} // end showstats_function()

####################################################################
# NAME        : NZtoUSdate                                         #
# Description : Converts NZ date to US Date                        #
# Parameters  : send in date from mysql Database                   #
# Returns     : returns date in format of yyyy-mm-dd               #
####################################################################
function nztousdate($date_work)
{
  $year=substr($date_work,6,4);
  $month=substr($date_work,3,2);
  $day=substr($date_work,0,2);
  $date_new="$year-$month-$day";
  return $date_new;
}

#####################################################################
# NAME        : UStoNZdate                                          #
# Description : Converts Us date to NZ Date                         #
# Parameters  : send in date from mysql Database                    #
# Returns     : returns date in format of dd/mm/yy                  #
#####################################################################
function ustonzdate($date_work)
{
  $day=substr($date_work,8,2);
  $month=substr($date_work,5,2);
  $year=substr($date_work,0,4);
  $new_date="$day/$month/$year";
  return $new_date;
}
?>