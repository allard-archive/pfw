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

$reload_url = "?action=". $_GET['action']. "&amp;rulenumber=". $_GET['rulenumber'];
$rulenumber = $_GET['rulenumber'];
$rule = $_SESSION['pf']->filter->rules[$rulenumber];

if ($_SESSION['edit']['type'] != 'anchor') {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['type'] = 'anchor';
}

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
	$_SESSION['edit']['save'] = $_SESSION['pf']->$_GET['action']->rules[$rulenumber];
}

/*
* Drop Entities
*/
if (isset($_GET['dropfrom'])) {
	$_SESSION['pf']->$_GET['action']->delEntity ("from", $_GET['rulenumber'], $_GET['dropfrom']);
	reload($reload_url);
}

if (isset($_GET['dropfromport'])) {
	$_SESSION['pf']->$_GET['action']->delEntity ("fromport", $rulenumber, $_GET['dropfromport']);
	reload($reload_url);
}

if (isset($_GET['dropto'])) {
	$_SESSION['pf']->$_GET['action']->delEntity ("to", $rulenumber, $_GET['dropto']);
	reload($reload_url);
}

if (isset($_GET['dropport'])) {
	$_SESSION['pf']->$_GET['action']->delEntity ("port", $rulenumber, $_GET['dropport']);
	reload($reload_url);
}

if (isset($_GET['dropinterface'])) {
	$_SESSION['pf']->$_GET['action']->delEntity ("interface", $rulenumber, $_GET['dropinterface']);
	reload($reload_url);
}

if (isset($_GET['dropproto'])) {
	$_SESSION['pf']->$_GET['action']->delEntity ("proto", $rulenumber, $_GET['dropproto']);
	reload($reload_url);
}

/*
* Add Entities
*/

if (isset($_POST['addfrom']) && !strpos($_POST['addfrom'], "macro")) {
	$_SESSION['pf']->$_GET['action']->addEntity("from", $rulenumber, $_POST['addfrom']);
	reload($reload_url);
}

if (isset($_POST['addfromport']) && !strpos($_POST['addfromport'], "macro")) {
	$_SESSION['pf']->$_GET['action']->addEntity("fromport", $rulenumber, $_POST['addfromport']);
	reload($reload_url);
}

if (isset($_POST['addto']) && !strpos($_POST['addto'], "macro")) {
	$_SESSION['pf']->$_GET['action']->addEntity("to", $rulenumber, $_POST['addto']);
	reload($reload_url);
}

if (isset($_POST['addport']) && !strpos($_POST['addport'], "macro")) {
	$_SESSION['pf']->$_GET['action']->addEntity("port", $rulenumber, $_POST['addport']);
	reload($reload_url);
}

if (isset($_POST['addinterface']) && !strpos($_POST['addinterface'], "macro")) {
	$_SESSION['pf']->$_GET['action']->addEntity("interface", $rulenumber, $_POST['addinterface']);
	reload($reload_url);
}

if (isset($_POST['addproto']) && !strpos($_POST['addproto'], "rotocol")) {
	$_SESSION['pf']->$_GET['action']->addEntity("proto", $rulenumber, $_POST['addproto']);
	reload($reload_url);
}

/*
 * Save the rest of the POST
 */
if (count($_POST)) {
  if ($_GET['action'] == "nat") {
    $_SESSION['pf']->$_GET['action']->rules[$rulenumber]['type'] = $_POST['type'];
  } else {
    $_SESSION['pf']->$_GET['action']->rules[$rulenumber]['type'] = "anchor";
  }
	$_SESSION['pf']->$_GET['action']->rules[$rulenumber]['identifier'] = preg_replace("/\"/", "", $_POST['identifier']);

	if (isset($_POST['all'])) {
		$_SESSION['pf']->$_GET['action']->rules[$rulenumber]['all'] = true;
		unset($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['from']);
		unset($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['fromport']);
		unset($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['to']);
		unset($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['port']);
	} else {
		unset ($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['all']);
	}
}

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {
  $_SESSION['pf']->$_GET['action']->rules[$rulenumber] = $_SESSION['edit']['save'];
  unset ($_SESSION['edit']);
  if (!isset($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['type'])) {
  	$_SESSION['pf']->$_GET['action']->del ($rulenumber);
  }
  header ("Location: ". $_GET['action']. ".php");
}

if (isset($_POST['save']) && $_POST['save'] == "save and return") {
	unset ($_SESSION['edit']);
	header ("Location: ". $_GET['action']. ".php");
}

/*
* Reload the rule after all the changes has been made.
*/
$rule = $_SESSION['pf']->$_GET['action']->rules[$rulenumber];

/*
* Begin Output
*/
$active = $_GET['action'];
page_header("Edit Anchor");
?>

<div id="main">
<h2>Edit Anchor</h2>
	<fieldset>
		<form id="theform" action="<?php preg_replace("/&/", '&amp;', $_SERVER['REQUEST_URI'], -1); ?>" method="post">
			<table width="100%" cellspacing="0" cellpadding="10">
			<tr align="center">

      <?php if ($_GET['action'] == "nat") { ?>
      <td>
      	<label for="type">Type</label>
      	<select id="type" name="type">
      		<option value="nat-anchor" label="nat-anchor" <?php print ($rule['type'] == "nat-anchor" ? "selected=\"selected\"" : "");?>>nat-anchor</option>
      		<option value="rdr-anchor" label="rdr-anchor" <?php print ($rule['type'] == "rdr-anchor" ? "selected=\"selected\"" : "");?>>rdr-anchor</option>
      		<option value="binat-anchor" label="binat-anchor" <?php print ($rule['type'] == "binat-anchor" ? "selected=\"selected\"" : "");?>>binat-anchor</option>
      	</select>
      </td>
      <?php } ?> 

			<td class="top">
				<label for="identifier">Identifier</label>
				<input type="text" id="identifier" name="identifier" size="20" value="<?php print $rule['identifier'];?>" />
			</td>

			<td>
				<label for="direction">Direction</label>
				<select id="direction" name="direction">
					<option value="" label=""></option>
					<option value="in" label="in" <?php print ($rule['direction'] == "in" ? "selected=\"selected\"" : "");?>>in</option>
					<option value="out" label="out" <?php print ($rule['direction'] == "out" ? "selected=\"selected\"" : "");?>>out</option>
				</select>
			</td>

			<td>
				<?php
				if (isset($rule['interface'])) {
					if (is_array($rule['interface'])) {
						foreach ($rule['interface'] as $interface) {
							print "$interface ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropinterface=$interface\">del</a>";
							print "<br />";
						}
					} else {
						print $rule['interface']. " ";
						print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropinterface='". $rule['interface']. "'\">del</a>";
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

			<td style="white-space: nowrap;">
				<label for="family">address family</label>
				<select id="family" name="family">
					<option value="" label=""></option>
					<option value="inet" label="inet" <?php print ($rule['family'] == "inet" ? "selected=\"selected\"" : "");?>>inet</option>
					<option value="inet6" label="inet6" <?php print ($rule['family'] == "inet6" ? "selected=\"selected\"" : "");?>>inet6</option>
				</select>			
			</td>

			<td>
				<?php
				if (isset($rule['proto'])) {
					if (is_array($rule['proto'])) {
						foreach ($rule['proto'] as $proto) {
							print "$proto ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropproto=$proto\">del</a>";
							print "<br />";
						}
					} else {
						print $rule['proto']. " ";
						print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropproto='". $rule['proto']. "'\">del</a>";
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

			</tr>
			</table>


			<table width="100%" cellspacing="0" cellpadding="10">
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
								print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropfrom=". htmlentities($from). "\">del</a>";
								print "<br />";
							}
						} else {
							print htmlentities($rule['from']). " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropfrom='". htmlentities($rule['from']). "'\">del</a>";
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
								print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropfromport=$fromport\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['fromport']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropfromport='". $rule['fromport']. "'\">del</a>";
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
								print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropto=". htmlentities($to). "\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['to']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropto='". htmlentities($rule['to']). "'\">del</a>";
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
								print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropport=$port\">del</a>";
								print "<br />";
							}
						} else {
							print $rule['port']. " ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "$reload_url&amp;dropport='". $rule['port']. "'\">del</a>";
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


			<table width="100%" cellspacing="0" cellpadding="10">
				<tr>
				<td class="last" style="white-space: nowrap">
					<label for="comment">Comment</label>
					<input type="text" id="comment" name="comment" value="<?php print $rule['comment'];?>" size="80" style="width: 90%" />
				</td>
				</tr>
			</table>

			<div class="buttons">
				<input type="submit" id="save" name="save" value="save" />
				<input type="submit" id="return" name="save" value="save and return" />
				<input type="submit" id="cancelme" name="cancelme" value="cancel" />
			</div>

		</form>
	</fieldset>
</div>
<?php require('manual/anchor.php'); ?>
</body>
</html>

