<?php 
/*
 * Copyright (c) 2004 Allard Consulting.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this
 *    software must display the following acknowledgement: This
 *    product includes software developed by Allard Consulting
 *    and its contributors.
 * 4. Neither the name of Allard Consulting nor the names of
 *    its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written
 *    permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

include_once("../../include.inc.php");

$rulenumber = $_GET['rulenumber'];
$rule = $_SESSION['pf']->filter->rules[$rulenumber];

if ($_SESSION['edit']['type'] != 'filter') {
	unset ($_SESSION['edit']['save']);
	unset ($_SESSION['edit']['rulenumber']);
	$_SESSION['edit']['type'] = 'filter';
}

if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['rulenumber'] = $rulenumber;
}

if (!isset($_SESSION['edit']['save']) && isset($rule['type'])) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['save'] = $_SESSION['pf']->filter->rules[$rulenumber];
}

/*
* Drop Entities
*/
if (isset($_GET['dropfrom'])) {
	$_SESSION['pf']->filter->delEntity ("from", $_GET['rulenumber'], $_GET['dropfrom']);
	reload();
}

if (isset($_GET['dropfromport'])) {
	$_SESSION['pf']->filter->delEntity ("fromport", $rulenumber, $_GET['dropfromport']);
	reload();
}

if (isset($_GET['dropto'])) {
	$_SESSION['pf']->filter->delEntity ("to", $rulenumber, $_GET['dropto']);
	reload();
}

if (isset($_GET['dropport'])) {
	$_SESSION['pf']->filter->delEntity ("port", $rulenumber, $_GET['dropport']);
	reload();
}

if (isset($_GET['dropinterface'])) {
	$_SESSION['pf']->filter->delEntity ("interface", $rulenumber, $_GET['dropinterface']);
	reload();
}

if (isset($_GET['dropproto'])) {
	$_SESSION['pf']->filter->delEntity ("proto", $rulenumber, $_GET['dropproto']);
	reload();
}

if (isset($_GET['dropuser'])) {
	$_SESSION['pf']->filter->delEntity ("user", $rulenumber, $_GET['dropuser']);
	reload();
}

if (isset($_GET['dropgroup'])) {
	$_SESSION['pf']->filter->delEntity ("group", $rulenumber, $_GET['dropgroup']);
	reload();
}

if (isset($_GET['dropicmptype'])) {
	$_SESSION['pf']->filter->delEntity ("icmp-type", $rulenumber, $_GET['dropicmptype']);
	reload();
}

if (isset($_GET['dropicmp6type'])) {
	$_SESSION['pf']->filter->delEntity ("icmp6-type", $rulenumber, $_GET['dropicmp6type']);
	reload();
}

if (isset($_GET['dropos'])) {
	$_SESSION['pf']->filter->delEntity ("os", $rulenumber, $_GET['dropos']);
	reload();
}

if (isset($_GET['droprouteto'])) {
	$_SESSION['pf']->filter->delEntity ("route-to", $rulenumber, $_GET['droprouteto']);
	reload();
}

if (isset($_GET['dropreplyto'])) {
	$_SESSION['pf']->filter->delEntity ("reply-to", $rulenumber, $_GET['dropreplyto']);
	reload();
}

if (isset($_GET['dropdupto'])) {
	$_SESSION['pf']->filter->delEntity ("dup-to", $rulenumber, $_GET['dropdupto']);
	reload();
}

/*
* Add Entities
*/

if (isset($_POST['addfrom']) && !strpos($_POST['addfrom'], "macro")) {
	$_SESSION['pf']->filter->addEntity("from", $rulenumber, $_POST['addfrom']);
	reload();
}

if (isset($_POST['addfromport']) && !strpos($_POST['addfromport'], "macro")) {
	$_SESSION['pf']->filter->addEntity("fromport", $rulenumber, $_POST['addfromport']);
	reload();
}

if (isset($_POST['addto']) && !strpos($_POST['addto'], "macro")) {
	$_SESSION['pf']->filter->addEntity("to", $rulenumber, $_POST['addto']);
	reload();
}

if (isset($_POST['addport']) && !strpos($_POST['addport'], "macro")) {
	$_SESSION['pf']->filter->addEntity("port", $rulenumber, $_POST['addport']);
	reload();
}

if (isset($_POST['addinterface']) && !strpos($_POST['addinterface'], "macro")) {
	$_SESSION['pf']->filter->addEntity("interface", $rulenumber, $_POST['addinterface']);
	reload();
}

if (isset($_POST['addproto']) && !strpos($_POST['addproto'], "rotocol")) {
	$_SESSION['pf']->filter->addEntity("proto", $rulenumber, $_POST['addproto']);
	reload();
}

if (isset($_POST['adduser']) && !strpos($_POST['adduser'], "sername or")) {
	$_SESSION['pf']->filter->addEntity("user", $rulenumber, $_POST['adduser']);
	reload();
}

if (isset($_POST['addgroup']) && !strpos($_POST['addgroup'], "roupname or")) {
	$_SESSION['pf']->filter->addEntity("group", $rulenumber, $_POST['addgroup']);
	reload();
}

if (isset($_POST['addicmptype']) && !strpos($_POST['addicmptype'], "macro")) {
	$_SESSION['pf']->filter->addEntity("icmp-type", $rulenumber, $_POST['addicmptype']);
	reload();
}

if (isset($_POST['addicmp6type']) && !strpos($_POST['addicmp6type'], "macro")) {
	$_SESSION['pf']->filter->addEntity("icmp6-type", $rulenumber, $_POST['addicmp6type']);
	reload();
}

if (isset($_POST['addos']) && !strpos($_POST['addos'], "macro")) {
	$_SESSION['pf']->filter->addEntity("os", $rulenumber, preg_replace("/\"/", "", $_POST['addos']));
	reload();
}

if (isset($_POST['addrouteto']) && !strpos($_POST['addrouteto'], "macro")) {
	$_SESSION['pf']->filter->addEntity("route-to", $rulenumber, preg_replace("/\"/", "", $_POST['addrouteto']));
	reload();
}

if (isset($_POST['addreplyto']) && !strpos($_POST['addreplyto'], "macro")) {
	$_SESSION['pf']->filter->addEntity("reply-to", $rulenumber, preg_replace("/\"/", "", $_POST['addreplyto']));
	reload();
}

if (isset($_POST['adddupto']) && !strpos($_POST['adddupto'], "macro")) {
	$_SESSION['pf']->filter->addEntity("dup-to", $rulenumber, preg_replace("/\"/", "", $_POST['adddupto']));
	reload();
}

if (count($_POST)) {
	$_SESSION['pf']->filter->rules[$rulenumber]['type'] = $_POST['type'];
	$_SESSION['pf']->filter->rules[$rulenumber]['direction'] = $_POST['direction'];
	$_SESSION['pf']->filter->rules[$rulenumber]['log'] = $_POST['log'];
	$_SESSION['pf']->filter->rules[$rulenumber]['quick'] = ($_POST['quick'] ? true : "");
	$_SESSION['pf']->filter->rules[$rulenumber]['state'] = $_POST['state'];
	$_SESSION['pf']->filter->rules[$rulenumber]['comment'] = $_POST['comment'];
	$_SESSION['pf']->filter->rules[$rulenumber]['label'] = preg_replace("/\"/", "", $_POST['label']);
	$_SESSION['pf']->filter->rules[$rulenumber]['tag'] = preg_replace("/\"/", "", $_POST['tag']);
	$_SESSION['pf']->filter->rules[$rulenumber]['tagged'] = preg_replace("/\"/", "", $_POST['tagged']);
	$_SESSION['pf']->filter->rules[$rulenumber]['icmp-code'] = $_POST['icmp-code'];
	$_SESSION['pf']->filter->rules[$rulenumber]['icmp6-code'] = $_POST['icmp6-code'];
	$_SESSION['pf']->filter->rules[$rulenumber]['family'] = $_POST['family'];
	$_SESSION['pf']->filter->rules[$rulenumber]['fastroute'] = ($_POST['fastroute'] ? true : "");
	$_SESSION['pf']->filter->rules[$rulenumber]['allow-opts'] = ($_POST['allow-opts'] ? true : "");

  if ($_POST['type'] == "block") {
    $_SESSION['pf']->filter->rules[$rulenumber]['blockoption'] = $_POST['blockoption'];
  } else {
    unset($_SESSION['pf']->filter->rules[$rulenumber]['blockoption']);
  }

	if (isset($_POST['flags']) && !strpos($_POST['flags'], "macro")) {
		$_SESSION['pf']->filter->rules[$rulenumber]['flags'] = $_POST['flags'];
	} else {
		unset($_SESSION['pf']->filter->rules[$rulenumber]['flags']);
	}

	if (isset($_POST['probability']) && !strpos($_POST['probability'], "percent")) {
		$_SESSION['pf']->filter->rules[$rulenumber]['probability'] = $_POST['probability'];
	} else {
		unset($_SESSION['pf']->filter->rules[$rulenumber]['probability']);
	}

	if (($_POST['queue-pri'] != "") && ($_POST['queue-sec'] != "")) {
		$_SESSION['pf']->filter->rules[$rulenumber]['queue']['0'] = $_POST['queue-pri'];
	  $_SESSION['pf']->filter->rules[$rulenumber]['queue']['1'] = $_POST['queue-sec'];
	} elseif ($_POST['queue-pri'] != "") {
		$_SESSION['pf']->filter->rules[$rulenumber]['queue'] = $_POST['queue-pri'];
	} else {
		unset ($_SESSION['pf']->filter->rules[$rulenumber]['queue']);
	}

	if (isset($_POST['all'])) {
		$_SESSION['pf']->filter->rules[$rulenumber]['all'] = true;
		unset($_SESSION['pf']->filter->rules[$rulenumber]['from']);
		unset($_SESSION['pf']->filter->rules[$rulenumber]['fromport']);
		unset($_SESSION['pf']->filter->rules[$rulenumber]['to']);
		unset($_SESSION['pf']->filter->rules[$rulenumber]['port']);
	} else {
		unset ($_SESSION['pf']->filter->rules[$rulenumber]['all']);
	}
}

$rule = $_SESSION['pf']->filter->rules[$rulenumber];

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {
	
	$_SESSION['pf']->filter->rules[$rulenumber] = $_SESSION['edit']['save'];
	unset ($_SESSION['edit']);
	if (!isset($_SESSION['pf']->filter->rules[$rulenumber]['type'])) {
		$_SESSION['pf']->filter->del ($rulenumber);
	}
	header ("Location: filter.php");
}

if (isset($_POST['save']) && $_POST['save'] == "save and return") {
	unset ($_SESSION['edit']);
	header ("Location: filter.php");
}

$active = "filter";
page_header("Edit Filter Rule");
?>

<script type="text/javascript">
function hide(obj) {
	document.getElementById(obj).style.display='none';
}
function show(obj) {
	document.getElementById(obj).style.display='';
}
function hideshow(obj) {
	if (document.getElementById(obj).style.display == '') {
		document.getElementById(obj).style.display='none';
	} else {
		document.getElementById(obj).style.display='';
	}
}

function manual_hideall() {
  hide('basic');
  hide('parameters');
  hide('addresses');
  hide('stateinspection');
  hide('osmatch');
  hide('queues');
  hide('examples');
}

function manual_showonly(obj) {
  manual_hideall();
  show(obj);
}

function page_init() {
<?php print  (($rule['user'] || $rule['group']) ? "" : "hide('usertable');" ). "\n" ?>
<?php print  (($rule['label'] || $rule['tag'] || $rule['tagged']) ? "" : "hide('tagtable');" ). "\n" ?>
<?php print  ($rule['os'] ? "" : "hide('ostable');" ). "\n" ?>
<?php print  ($rule['queue'] ? "" : "hide('queuetable');" ). "\n" ?>
<?php print  (($rule['fastroute'] || $rule['route-to'] || $rule['reply-to'] || $rule['dup-to'] ) ? "" : "hide('routetable');" ). "\n" ?>
<?php print  (($rule['allow-opts'] || $rule['probability']) ? "" : "hide('othertable');" ). "\n" ?>

show('hideshowlinks');
manual_hideall();
}
</script>

<div id="main">
<h2>Edit Filter Rule</h2>
<fieldset>
	<form method="post" id="theform" name="theform" action="<?php print $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber";?>">
		<table>
			<tr align="center">
			<td rowspan="2" class="nowrap">
				<label for="type">Type</label>
				<select id="type" name="type" onchange="document.theform.submit()">
					<option value="pass" label="pass" <?php print ($rule['type'] == "pass" ? "selected=\"selected\"" : "");?>>pass</option>
					<option value="block" label="block" <?php print ($rule['type'] == "block" ? "selected=\"selected\"" : "");?>>block</option>
					<option value="antispoof" label="antispoof" <?php print ($rule['type'] == "antispoof" ? "selected=\"selected\"" : "");?>>antispoof</option>
				</select>
			</td>
			<td rowspan="2">
				<label for="direction">Direction</label>
				<select id="direction" name="direction">
					<option value="" label=""></option>
					<option value="in" label="in" <?php print ($rule['direction'] == "in" ? "selected=\"selected\"" : "");?>>in</option>
					<option value="out" label="out" <?php print ($rule['direction'] == "out" ? "selected=\"selected\"" : "");?>>out</option>
				</select>
			</td>

			<td rowspan="2">
				<?php
				if (isset($rule['interface'])) {
					if (is_array($rule['interface'])) {
						foreach ($rule['interface'] as $interface) {
							print "$interface ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropinterface=$interface\">del</a>";
							print "<br />";
						}
					} else {
						print $rule['interface']. " ";
						print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropinterface='". $rule['interface']. "'\">del</a>";
					}
					print "<div class=\"add\">";
				}
				print "<label for=\"addinterface\">add interface</label>";
				print "<input type=\"text\" id=\"addinterface\" name=\"addinterface\" size=\"10\" value=\"if or macro\" 
							onfocus=\"if (addinterface.value=='if or macro') addinterface.value = '' \" 
							onblur=\"if (addinterface.value == '') addinterface.value = 'if or macro'\" />";
				if (isset($rule['interface'])) {
					print "</div>";
				}
				?>
			</td>

			<td rowspan="2">
				<label for="family">address<br />family</label>
				<select id="family" name="family">
					<option value="" label=""></option>
					<option value="inet" label="inet" <?php print ($rule['family'] == "inet" ? "selected=\"selected\"" : "");?>>inet</option>
					<option value="inet6" label="inet6" <?php print ($rule['family'] == "inet6" ? "selected=\"selected\"" : "");?>>inet6</option>
				</select>			
			</td>

			<td rowspan="2">
				<?php
				if (isset($rule['proto'])) {
					if (is_array($rule['proto'])) {
						foreach ($rule['proto'] as $proto) {
							print "$proto ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropproto=$proto\">del</a>";
							print "<br />";
						}
					} else {
						print $rule['proto']. " ";
						print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropproto='". $rule['proto']. "'\">del</a>";
					}
					print "<div class=\"add\">";
				}
				print "<label for=\"addproto\">add protocol</label>";
				print "<input type=\"text\" id=\"addproto\" name=\"addproto\" size=\"10\" value=\"protocol\" 
							onfocus=\"if (addproto.value=='protocol') addproto.value = '' \" 
							onblur=\"if (addproto.value == '') addproto.value = 'protocol'\" />";
				if (isset($rule['proto'])) {
					print "</div>";
				}
				?>
			</td>
			<td>
				<label for="log">logging</label>
				<select id="log" name="log">
					<option value="" label=""></option>
					<option value="log" label="log" <?php print ($rule['log'] == "log" ? "selected=\"selected\"" : "");?>>log</option>
					<option value="log-all" label="log-all" <?php print ($rule['log'] == "log-all" ? "selected=\"selected\"" : "");?>>log-all</option>
				</select>			
			</td>

			<td>
			  <?php if ($rule['type'] == "block") { ?>
			  <label for="state">Block Option</label>
			  <select id="state" name="blockoption">
			  	<option value=""></option>
			  	<option value="drop" <?php print ($rule['blockoption'] == "drop" ? "selected=\"selected\"" : "");?>>drop</option>
			  	<option value="return" <?php print ($rule['blockoption'] == "return" ? "selected=\"selected\"" : "");?>>return</option>
			  	<option value="return-rst" <?php print ($rule['blockoption'] == "return-rst" ? "selected=\"selected\"" : "");?>>return-rst</option>
			  	<option value="return-icmp" <?php print ($rule['blockoption'] == "return-icmp" ? "selected=\"selected\"" : "");?>>return-icmp</option>
			  	<option value="return-icmp6" <?php print ($rule['blockoption'] == "return-icmp6" ? "selected=\"selected\"" : "");?>>return-icmp6</option>
			  </select>
			  <?php } else { ?>
				<label for="state">State</label>
				<select id="state" name="state">
					<option value=""></option>
					<option value="keep" <?php print ($rule['state'] == "keep" ? "selected=\"selected\"" : "");?>>Keep State</option>
					<option value="modulate" <?php print ($rule['state'] == "modulate" ? "selected=\"selected\"" : "");?>>Modulate State</option>
					<option value="synproxy" <?php print ($rule['state'] == "synproxy" ? "selected=\"selected\"" : "");?>>Synproxy</option>
				</select>
				<?php } ?>
			</td>

			</tr>

			<tr align="center">
				<td>
					<label for="quick">Quick</label>
					<input type="checkbox" id="quick" name="quick" value="quick" <?php print ($rule['quick'] ? "checked=\"checked\"" : "");?> />
				</td>
				<td class="last">
					<label for="flags">TCP Flags</label>
					<?php
					print "<input type=\"text\" id=\"flags\" name=\"flags\" ";
					print "value=\"". ($rule['flags'] ? $rule['flags'] : "flags or macro"). "\""; 
					print " onfocus=\"if (flags.value=='flags or macro') flags.value = '' \"";
					print " onblur=\"if (flags.value == '') flags.value = 'flags or macro'\"";
					print " size=\"12\" />";
					?>
				</td>
			</tr>
			</table>
			
			<table style="width: 100%" cellspacing="0" cellpadding="10">
			<tr align="center">
				<td rowspan="2">
					<label for="all">Match All</label>
					<input type="checkbox" id="all" name="all" value="all" <?php print ($rule['all'] ? "checked=\"checked\"" : "");?> onclick="document.theform.submit()" />
				</td>
				<td>From</td>
				<td colspan="2">
				<?php
					if (isset($rule['from'])) {
						if (is_array($rule['from'])) {
							foreach ($rule['from'] as $from) {
								print htmlentities($from). " ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropfrom=". htmlentities($from). "\">del</a>";
								print "<br />";
							}
						} else {
							print htmlentities($rule['from']). " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropfrom='". htmlentities($rule['from']). "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addfrom\">add source</label>";
					print "<input type=\"text\" id=\"addfrom\" name=\"addfrom\" value=\"ip, host or macro\" 
							onfocus=\"if (addfrom.value=='ip, host or macro') addfrom.value = '' \" 
							onblur=\"if (addfrom.value == '') addfrom.value = 'ip, host or macro'\"";
					if ($rule['all']) {
						print " disabled=\"disabled\"";
					}
					print " />";
					if (isset($rule['from'])) {
						print "</div>";
					}
					?>
				</td>
				<td colspan="3" class="last">
				<?php
					if (isset($rule['fromport'])) {
						if (is_array($rule['fromport'])) {
							foreach ($rule['fromport'] as $fromport) {
								print "$fromport ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropfromport=$fromport\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['fromport']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropfromport='". $rule['fromport']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addfromport\">add source port</label>";
					print "<input type=\"text\" id=\"addfromport\" name=\"addfromport\" value=\"number, name, table or macro\" 
							onfocus=\"if (addfromport.value=='number, name, table or macro') addfromport.value = '' \" 
							onblur=\"if (addfromport.value == '') addfromport.value = 'number, name, table or macro'\"";
					if ($rule['all']) {
						print " disabled=\"disabled\"";
					}
					print " />";
					if (isset($rule['fromport'])) {
						print "</div>";
					}
					?>
				</td>
			</tr>
			<tr align="center">
				<td>To</td>
				<td colspan="2">
				<?php
					if (isset($rule['to'])) {
						if (is_array($rule['to'])) {
							foreach ($rule['to'] as $to) {
								print htmlentities($to). " ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropto=". htmlentities($to). "\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['to']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropto='". htmlentities($rule['to']). "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addto\">add dest</label>";
					print "<input type=\"text\" id=\"addto\" name=\"addto\" value=\"ip, host, table or macro\" 
							onfocus=\"if (addto.value=='ip, host, table or macro') addto.value = '' \" 
							onblur=\"if (addto.value == '') addto.value = 'ip, host, table or macro'\"";
					if ($rule['all']) {
							print " disabled=\"disabled\"";
					}
					print " />";
					if (isset($rule['to'])) {
						print "</div>";
					}
				?>
				</td>
				<td colspan="3" class="last">
				<?php
					if (isset($rule['port'])) {
						if (is_array($rule['port'])) {
							foreach ($rule['port'] as $port) {
								print "$port ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropport=$port\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['port']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropport='". $rule['port']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addport\">add dest port</label>\n";
					print "<input type=\"text\" id=\"addport\" name=\"addport\" value=\"number, name or macro\" 
							onfocus=\"if (addport.value=='number, name or macro') addport.value = '' \" 
							onblur=\"if (addport.value == '') addport.value = 'number, name or macro'\"";
					if ($rule['all']) {
						print " disabled=\"disabled\"";
					}
					print " />";
					if (isset($rule['port'])) {
						print "</div>";
					}
				?>
				</td>
			</tr>
			</table>
			
			<?php
			/*
			* Here begins the normally hidden tables
			*/
			if (isset($rule['proto']) && $rule['proto'] == "icmp" || is_array($rule['proto']) && in_array("icmp", $rule['proto'])) { ?>
			<table id="icmptable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td>
					<?php
					if (isset($rule['icmp-type'])) {
						if (is_array($rule['icmp-type'])) {
							foreach ($rule['icmp-type'] as $icmptype) {
								print "$icmptype ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropicmptype=$icmptype\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['icmp-type']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropicmptype='". $rule['icmp-type']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addicmptype\">add icmp-type</label>\n";
					print "<input type=\"text\" id=\"addicmptype\" name=\"addicmptype\" value=\"number, name or macro\" 
							onfocus=\"if (addicmptype.value=='number, name or macro') addicmptype.value = '' \" 
							onblur=\"if (addicmptype.value == '') addicmptype.value = 'number, name or macro'\" />";
					if (isset($rule['icmp-type'])) {
						print "</div>";
					}
					?>
					</td>
					<td class="last">
						<label for="icmp-code">icmp-code</label>
						<input type="text" name="icmp-code" id="icmp-code" value="<?php print $rule['icmp-code'];?>" <?php print (isset($rule['icmp-type']) && !is_array($rule['icmp-type']) ? "" : "disabled=\"disabled\"")?> />
					</td>
				</tr>
			</table>
			<?php }

			if (isset($rule['proto']) && $rule['proto'] == "icmp6" || is_array($rule['proto']) && in_array("icmp6", $rule['proto'])) { ?>
			<table id="icmp6table" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td>
					<?php
					if (isset($rule['icmp6-type'])) {
						if (is_array($rule['icmp6-type'])) {
							foreach ($rule['icmp6-type'] as $icmp6type) {
								print "$icmp6type ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropicmp6type=$icmp6type\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['icmp6-type']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropicmp6type='". $rule['icmp6-type']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addicmp6type\">add icmp6-type</label>\n";
					print "<input type=\"text\" id=\"addicmp6type\" name=\"addicmp6type\" value=\"number, name or macro\" 
							onfocus=\"if (addicmp6type.value=='number, name or macro') addicmp6type.value = '' \" 
							onblur=\"if (addicmp6type.value == '') addicmp6type.value = 'number, name or macro'\" />";
					if (isset($rule['icmp6-type'])) {
						print "</div>";
					}
					?>
					</td>
					<td>
						<label for="icmp6-code">icmp6-code</label>
						<input type="text" name="icmp6-code" id="icmp6-code" value="<?php print $rule['icmp6-code'];?>" <?php print (isset($rule['icmp6-type']) && !is_array($rule['icmp6-type']) ? "" : "disabled=\"disabled\"")?> />
					</td>
				</tr>
			</table>
			<?php } ?>
			
			<table id="tagtable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td style="width: 33%">
						<label for="label">label</label>
						<input type="text" id="label" name="label" style="width: 95%" value="<?php print $rule['label'];?>" />
					</td>
					<td style="width: 33%">
						<label for="tag">tag</label>
						<input type="text" id="tag" name="tag" style="width: 95%" value="<?php print $rule['tag'];?>" />
					</td>
					<td style="width: 33%">
						<label for="tagged">match tagged</label>
						<input type="text" id="tagged" name="tagged" style="width: 95%" value="<?php print $rule['tagged'];?>" />
					</td>
				</tr>
			</table>

			<table id="ostable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td>
					<?php
					if (isset($rule['os'])) {
						if (is_array($rule['os'])) {
							foreach ($rule['os'] as $os) {
								print "$os ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropos=$os\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['os']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropos='". $rule['os']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addos\">add operating system</label>\n";
					print "<input type=\"text\" id=\"addos\" name=\"addos\" value=\"os name or macro\" 
							onfocus=\"if (addos.value=='os name or macro') addos.value = '' \" 
							onblur=\"if (addos.value == '') addos.value = 'os name or macro'\"";
					print " />";
					if (isset($rule['os'])) {
						print "</div>";
					}
					?>
					</td>
				</tr>
			</table>

			<table id="queuetable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td style="width: 50%">
						<label for="queue-pri">Primary Queue</label>
						<select id="queue-pri" name="queue-pri">
						<?php 
						if (!isset($_SESSION['pf']->queue->rules)) {
							print "<option value=\"\" disabled=\"disabled\">No Queues defined</option>";
						} else {
							print "<option value=\"\">none</option>";
							if (!is_array($rule['queue'])) {
								foreach ($_SESSION['pf']->queue->rules as $queue) {
									print "<option value=\"". $queue['name']. "\" ". ($rule['queue'] == $queue['name'] ? "selected=\"selected\"" : ""). ">". $queue['name']. "</option>";
								}
							} else {
								foreach ($_SESSION['pf']->queue->rules as $queue) {
									print "<option value=\"". $queue['name']. "\" ". ($rule['queue']['0'] == $queue['name'] ? "selected=\"selected\"" : ""). ">". $queue['name']. "</option>";
								}
							}
						}
						?>
						</select>
					</td>
					<td style="width: 50%">
						<label for="queue-sec">Secondary Queue</label>
						<select id="queue-sec" name="queue-sec">
						<?php 
						if (!isset($_SESSION['pf']->queue->rules)) {
							print "<option value=\"\" disabled=\"disabled\">No Queues defined</option>";
						} else {
							print "<option value=\"\">none</option>";
							foreach ($_SESSION['pf']->queue->rules as $queue) {
								print "<option value=\"". $queue['name']. "\" ". ($rule['queue']['1'] == $queue['name'] ? "selected=\"selected\"" : ""). ">". $queue['name']. "</option>";
							}
						}
						?>
						</select>	
					</td>
				</tr>
			</table>

			<table id="routetable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td style="width: 15%">
					  <label for="fastroute">Fastroute</label>
					  <input type="checkbox" id="fastroute" name="fastroute" value="fastroute" <?php print ($rule['fastroute'] ? "checked=\"checked\"" : "");?> />
          </td>
          <td>
            <?php
            if (isset($rule['route-to'])) {
            	if (is_array($rule['route-to'])) {
            		foreach ($rule['route-to'] as $route) {
            			print "$route ";
            			print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;droprouteto=$route\">del</a>";
            			print "<br />";
            		}
            	} else {
            		print $rule['route-to']. " ";
            		print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;droprouteto='". $rule['route-to']. "'\">del</a>";
            	}
            	print "<div class=\"add\">";
            }
            print "<label for=\"addrouteto\">add route-to</label>\n";
            print "<input type=\"text\" id=\"addrouteto\" name=\"addrouteto\" value=\"ip, host, table or macro\" 
            		onfocus=\"if (addrouteto.value=='ip, host, table or macro') addrouteto.value = '' \" 
            		onblur=\"if (addrouteto.value == '') addrouteto.value = 'ip, host, table or macro'\"";
            print " />";
            if (isset($rule['route-to'])) {
            	print "</div>";
            }
            ?>
					</td>

					<td>
					  <?php
					  if (isset($rule['reply-to'])) {
					  	if (is_array($rule['reply-to'])) {
					  		foreach ($rule['reply-to'] as $route) {
					  			print "$route ";
					  			print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropreplyto=$route\">del</a>";
					  			print "<br />";
					  		}
					  	} else {
					  		print $rule['reply-to']. " ";
					  		print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropreplyto='". $rule['reply-to']. "'\">del</a>";
					  	}
					  	print "<div class=\"add\">";
					  }
					  print "<label for=\"addreplyto\">add reply-to</label>\n";
					  print "<input type=\"text\" id=\"addreplyto\" name=\"addreplyto\" value=\"ip, host, table or macro\" 
					  		onfocus=\"if (addreplyto.value=='ip, host, table or macro') addreplyto.value = '' \" 
					  		onblur=\"if (addreplyto.value == '') addreplyto.value = 'ip, host, table or macro'\"";
					  print " />";
					  if (isset($rule['reply-to'])) {
					  	print "</div>";
					  }
					  ?>
					</td>

					<td>
					  <?php
					  if (isset($rule['dup-to'])) {
					  	if (is_array($rule['dup-to'])) {
					  		foreach ($rule['dup-to'] as $route) {
					  			print "$route ";
					  			print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropdupto=$route\">del</a>";
					  			print "<br />";
					  		}
					  	} else {
					  		print $rule['dup-to']. " ";
					  		print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropdupto='". $rule['dup-to']. "'\">del</a>";
					  	}
					  	print "<div class=\"add\">";
					  }
					  print "<label for=\"adddupto\">add dup-to</label>\n";
					  print "<input type=\"text\" id=\"adddupto\" name=\"adddupto\" value=\"ip, host, table or macro\" 
					  		onfocus=\"if (adddupto.value=='ip, host, table or macro') adddupto.value = '' \" 
					  		onblur=\"if (adddupto.value == '') adddupto.value = 'ip, host, table or macro'\"";
					  print " />";
					  if (isset($rule['dup-to'])) {
					  	print "</div>";
					  }
					  ?>
					</td>

				</tr>
			</table>


			<table id="othertable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td style="width: 25%">
					  <label for="allow-opts">Allow-opts</label>
					  <input type="checkbox" id="allow-opts" name="allow-opts" value="allow-opts" <?php print ($rule['allow-opts'] ? "checked=\"checked\"" : "");?> />
			    </td>
			    <td>
		      	<label for="probability">Probability</label>
		      	<?php
		      	print "<input type=\"text\" id=\"probability\" name=\"probability\" ";
		      	print "value=\"". ($rule['probability'] ? $rule['probability'] : "probability in percent"). "\""; 
		      	print " onfocus=\"if (probability.value=='probability in percent') probability.value = '' \"";
		      	print " onblur=\"if (probability.value == '') probability.value = 'probability in percent'\"";
		      	print " size=\"20\" />";
		      	?>
		      </td>
				</tr>
			</table>


			<table id="usertable" style="width: 100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td style="width: 50%">
					<?php
					if (isset($rule['user'])) {
						if (is_array($rule['user'])) {
							foreach ($rule['user'] as $user) {
								print "$user ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropuser=$user\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['user']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropuser='". $rule['user']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"adduser\">add user</label>\n";
					print "<input type=\"text\" id=\"adduser\" name=\"adduser\" value=\"username or userid\" 
							onfocus=\"if (adduser.value=='username or userid') adduser.value = '' \" 
							onblur=\"if (adduser.value == '') adduser.value = 'username or userid'\"";
					print " />";
					if (isset($rule['user'])) {
						print "</div>";
					}
					?>
					</td>
					<td style="width: 50%">
					<?php
					if (isset($rule['group'])) {
						if (is_array($rule['group'])) {
							foreach ($rule['group'] as $group) {
								print "$group ";
								print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropgroup=$group\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['group']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropgroup='". $rule['group']. "'\">del</a>";
						}
						print "<div class=\"add\">";
					}
					print "<label for=\"addgroup\">add group</label>\n";
					print "<input type=\"text\" id=\"addgroup\" name=\"addgroup\" value=\"groupname or groupid\" 
							onfocus=\"if (addgroup.value=='groupname or groupid') addgroup.value = '' \" 
							onblur=\"if (addgroup.value == '') addgroup.value = 'groupname or groupid'\"";
					print " />";
					if (isset($rule['group'])) {
						print "</div>";
					}
					?>
					</td>
				</tr>
			</table>

			<table style="width: 100%" cellspacing="0" cellpadding="10">
				<tr>
				<td class="nowrap">
					<label for="comment">Comment</label>
					<input type="text" id="comment" name="comment" value="<?php print stripslashes($rule['comment']);?>" size="80" style="width: 90%" />
				</td>
				</tr>
			</table>

			<p id="hideshowlinks" style="display: none; text-align: left; margin-top: 0; margin-bottom: 0.5em; color: gray; font-size: 80%;">
				Hide/Show: 
				<?php if (!$rule['os']) { ?>
				<a onclick="hideshow('ostable');">os matching</a> |
				<?php } ?>
				<?php if (!$rule['queue']) { ?>
				<a onclick="hideshow('queuetable');">queing</a>	|
				<?php } ?>
				<?php if (!($rule['fastroute'] || $rule['route-to'] || $rule['reply-to'] || $rule['dup-to'])) { ?>
				<a onclick="hideshow('routetable');">routing</a>	|
				<?php } ?>
				<?php if (!($rule['user'] || $rule['group'])) { ?>
				<a onclick="hideshow('usertable');">users &amp; groups</a> |
				<?php } ?>
				<?php if (!($rule['label'] || $rule['tag'] || $rule['tagged'])) { ?>
				<a onclick="hideshow('tagtable');">tags &amp; labels</a> |
				<?php } ?>
				<?php if (!($rule['allow-opts'] || $rule['probability'])) { ?>
				<a onclick="hideshow('othertable');">other</a>
				<?php } ?>
			</p>

			<div class="buttons">
				<input type="submit" id="save" name="save" value="save" />
				<input type="submit" id="return" name="save" value="save and return" />
				<input type="submit" id="cancelme" name="cancelme" value="cancel" />
			</div>
	</form>
</fieldset>
</div>
<?php require('manual/filter.php'); ?>
</body>
</html>
