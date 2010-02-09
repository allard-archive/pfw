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
	$rule = $_SESSION['pf']->nat->rules[$rulenumber];

	if ($_SESSION['edit']['type'] != 'nat') {
		unset ($_SESSION['editsave']);
		$_SESSION['edit']['type'] = 'nat';
	}

	if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
		unset ($_SESSION['editsave']);
		$_SESSION['edit']['rulenumber'];
	}
	
	if (!isset($_SESSION['edit']['save']) && isset($rule['type'])) {
		unset ($_SESSION['edit']['save']);
		$_SESSION['edit']['save'] = $_SESSION['pf']->nat->rules[$rulenumber];
	}

  if (isset($_GET['dropinterface'])) {
  	$_SESSION['pf']->nat->delEntity ("interface", $rulenumber, $_GET['dropinterface']);
  	reload();
  }

  if (isset($_GET['dropproto'])) {
  	$_SESSION['pf']->nat->delEntity ("proto", $rulenumber, $_GET['dropproto']);
  	reload();
  }

  if (isset($_POST['addinterface']) && !strpos($_POST['addinterface'], "macro")) {
  	$_SESSION['pf']->nat->addEntity("interface", $rulenumber, $_POST['addinterface']);
  	reload();
  }

  if (isset($_POST['addproto']) && !strpos($_POST['addproto'], "rotocol")) {
  	$_SESSION['pf']->nat->addEntity("proto", $rulenumber, $_POST['addproto']);
  	reload();
  }

	if (count($_POST)) {
		$_SESSION['pf']->nat->rules[$rulenumber]['type'] = 			  $_POST['type'];
		$_SESSION['pf']->nat->rules[$rulenumber]['pass'] =        ($_POST['pass'] ? true : "");
		$_SESSION['pf']->nat->rules[$rulenumber]['from'] = 			  $_POST['from'];
		$_SESSION['pf']->nat->rules[$rulenumber]['fromport'] = 	  $_POST['fromport'];
		$_SESSION['pf']->nat->rules[$rulenumber]['to'] = 			    $_POST['to'];
		$_SESSION['pf']->nat->rules[$rulenumber]['port'] = 			  $_POST['port'];
		$_SESSION['pf']->nat->rules[$rulenumber]['natdest'] = 	  $_POST['natdest'];
		$_SESSION['pf']->nat->rules[$rulenumber]['natdestport'] = $_POST['natdestport'];
		$_SESSION['pf']->nat->rules[$rulenumber]['netmask'] =		  $_POST['netmask'];
		$_SESSION['pf']->nat->rules[$rulenumber]['comment'] =		  $_POST['comment'];

		$_SESSION['pf']->nat->rules[$rulenumber]['random']      = ($_POST['random'] ? true : "");
		$_SESSION['pf']->nat->rules[$rulenumber]['source-hash'] = ($_POST['source-hash'] ? true : "");
		$_SESSION['pf']->nat->rules[$rulenumber]['static-port'] = ($_POST['static-port'] ? true : "");

		$rule = $_SESSION['pf']->nat->rules[$rulenumber];
	}

	/*
	* go back
	*/
	if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {

		$_SESSION['pf']->nat->rules[$rulenumber] = $_SESSION['edit']['save'];
		unset ($_SESSION['edit']);
		if (!isset($_SESSION['pf']->nat->rules[$rulenumber]['type'])) {
			$_SESSION['pf']->nat->del ($rulenumber);
		}
		header ("Location: nat.php");
	}

	if (isset($_POST['save']) && $_POST['save'] == "save and return") {
		unset ($_SESSION['edit']);
		header ("Location: nat.php");
	}

	/*
	* Begin Output
	*/

	$nat_selected = "";
	$binat_selected = "";
	$rdr_selected = "";
	
	$tcp_selected = "";
	$udp_selected = "";
	$tcpudp_selected = "";

	switch ($rule['type']) {
	case 'nat':
		$nat_selected = "selected=\"selected\"";
		break;
	case 'binat':
		$binat_selected = "selected=\"selected\"";
		break;
	case 'rdr':
		$rdr_selected = "selected=\"selected\"";
		break;
	}

	switch ($rule['proto']) {
	case 'tcp':
		$tcp_selected = "selected=\"selected\"";
		break;
	case 'udp':
		$udp_selected = "selected=\"selected\"";
		break;
	case 'tcpudp':
		$tcpudp_selected = "selected=\"selected\"";
		break;
	}

$active = "nat";
page_header("Network Address Translations");
/*
* Begin Output
*/
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
  hide('pool');
  hide('examples');
}

function manual_showonly(obj) {
  manual_hideall();
  show(obj);
}

function page_init() {

<?php print  (($rule['netmask'] || $rule['random'] || $rule['source-hash'] || $rule['static-port']) ? "" : "hide('pooltable');" ). "\n" ?>

show('hideshowlinks');
manual_hideall();
}
</script>

<div id="main">
	<fieldset>
		<form id="theform" name="theform" action="<?php print $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber";?>" method="post">
			<table width="100%" cellspacing="0" cellpadding="10">
			<tr>
				<th>type</th>
				<?php if ($rule['type'] == "rdr") { ?>
				<th>Pass</th>
				<?php } ?>
				<th>Interface</th>
				<th>Proto</th>
				<th>From</th>
				<th>Port</th>
				<th>To</th>
				<th>Port</th>
				<th></th>
				<th>Destination</th>
				<th>Port</th>
			</tr>
			<tr>
			<td>
				<select id="type" name="type" onchange="javascript: document.theform.submit()">
					<option label="nat" <?php print $nat_selected;?>>nat</option>
					<option label="binat" <?php print $binat_selected;?>>binat</option>
					<option label="rdr" <?php print $rdr_selected;?>>rdr</option>
				</select>
			</td>

      <?php if ($rule['type'] == "rdr") { ?>
      <td>
        <input type="checkbox" id="pass" name="pass" value="pass" <?php print ($rule['pass'] ? "checked=\"checked\"" : "");?> />
      </td>
      <?php } ?>

			<td>
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

			<?php if ($rule['type'] == "rdr") { ?>
			<td>
				<select id="proto" name="proto">
					<option value="tcp" label="tcp" <?php print $tcp_selected;?>>tcp</option>
					<option value="udp" label="udp" <?php print $udp_selected;?>>udp</option>
					<option value="tcpudp" label="tcp / udp" <?php print $tcpudp_selected;?>>tcp / dump</option>
				</select>
			</td>
			<?php } else { ?>
			  <td>
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
  		<?php } ?>

			<td>
				<input type="text" id="from" name="from" size="10" value="<?php print $rule['from'];?>" />
			</td>

			<td>
				<input type="text" id="fromport" name="fromport" size="4" value="<?php print $rule['fromport'];?>" />
			</td>

			<td>
				<input type="text" id="to" name="to" size="10" value="<?php print $rule['to'];?>" />
			</td>

			<td>
				<input type="text" id="port" name="port" size="4" value="<?php print $rule['port'];?>" />
			</td>

			<td class="nowrap">-&gt;</td>

			<td>
				<input type="text" id="natdest" name="natdest" size="10" value="<?php print $rule['natdest'];?>" />
			</td>

			<td class="last">
				<input type="text" id="natdestport" name="natdestport" size="4" value="<?php print $rule['natdestport'];?>" />
			</td>

			</tr>
			</table>

			<table id="pooltable" width="100%" cellspacing="0" cellpadding="10">
				<tr align="center">
					<td>
					  <label for="netmask">netmask</label>
					  <input type="text" id="netmask" name="netmask" size="15" maxlength="15" value="<?php print $rule['netmask'];?>" />
					</td>
					<td class="nowrap">
						<label for="random">
						<input type="checkbox" id="random" name="random" value="random" <?php print ($rule['random'] ? "checked=\"checked\"" : "");?> />
						Random</label>
					</td>
					<td class="nowrap">
						<label for="source-hash">
						<input type="checkbox" id="source-hash" name="source-hash" value="source-hash" <?php print ($rule['source-hash'] ? "checked=\"checked\"" : "");?> />
						Source-hash</label>
					</td>
					<td class="nowrap">
						<label for="static-port">
						<input type="checkbox" id="static-port" name="static-port" value="static-port" <?php print ($rule['static-port'] ? "checked=\"checked\"" : "");?> />
						Static-port</label>
					</td>
				</tr>
			</table>

			<table width="100%" cellspacing="0" cellpadding="10">
				<tr>
				<td class="last" style="white-space: nowrap">
					<label for="comment">Comment</label>
					<input type="text" id="comment" name="comment" value="<?php print stripslashes($rule['comment']);?>" size="80" style="width: 90%" />
				</td>
				</tr>
			</table>

			<p id="hideshowlinks" style="display: none; text-align: left; margin-top: 0; margin-bottom: 0.5em; color: gray; font-size: 80%;">
				<?php if (!($rule['netmask'] || $rule['random'] || $rule['source-hash'] || $rule['static-port'])) { ?>
				Hide/Show: 
				<a onclick="hideshow('pooltable');">pool options</a>
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
<?php require('manual/nat.php'); ?>
</body>
</html>