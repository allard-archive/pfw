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
$rule = $_SESSION['pf']->scrub->rules[$rulenumber];

if ($_SESSION['edit']['type'] != 'scrub') {
	unset ($_SESSION['editsave']);
	$_SESSION['edit']['type'] = 'scrub';
}

if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
	unset ($_SESSION['editsave']);
	$_SESSION['edit']['rulenumber'];
}

if (!isset($_SESSION['edit']['save'])) {
	$_SESSION['edit']['save'] = $_SESSION['pf']->scrub->rules[$rulenumber];
}

if (isset($_GET['dropinterface'])) {
	$_SESSION['pf']->scrub->delEntity ("interface", $rulenumber, $_GET['dropinterface']);
	reload();
}

if (isset($_GET['dropto'])) {
	$_SESSION['pf']->scrub->delEntity ("to", $rulenumber, $_GET['dropto']);
	reload();
}

if (isset($_GET['dropfrom'])) {
	$_SESSION['pf']->scrub->delEntity ("from", $rulenumber, $_GET['dropfrom']);
	reload();
}

if (isset($_POST['addfrom']) && !strpos($_POST['addfrom'], "macro")) {
	$_SESSION['pf']->scrub->addEntity("from", $rulenumber, $_POST['addfrom']);
	reload();
}

if (isset($_POST['addto']) && !strpos($_POST['addto'], "macro")) {
	$_SESSION['pf']->scrub->addEntity("to", $rulenumber, $_POST['addto']);
	reload();
}

if (isset($_POST['addinterface']) && !strpos($_POST['addinterface'], "macro")) {
	$_SESSION['pf']->scrub->addEntity("interface", $rulenumber, $_POST['addinterface']);
	reload();
}

if (count($_POST)) {
	$_SESSION['pf']->scrub->rules[$rulenumber]['direction'] =	$_POST['direction'];
	$_SESSION['pf']->scrub->rules[$rulenumber]['no-df'] = 		($_POST['no-df'] ? true : false);
	$_SESSION['pf']->scrub->rules[$rulenumber]['random-id'] =	($_POST['random-id'] ? true : false);
	$_SESSION['pf']->scrub->rules[$rulenumber]['min-ttl'] = 	$_POST['min-ttl'];
	$_SESSION['pf']->scrub->rules[$rulenumber]['max-mss'] =		$_POST['max-mss'];
	$_SESSION['pf']->scrub->rules[$rulenumber]['fragment'] =	$_POST['fragment'];
	$_SESSION['pf']->scrub->rules[$rulenumber]['reassemble'] =	($_POST['reassemble'] && !$_POST['direction'] ? $_POST['reassemble'] : "");
	$_SESSION['pf']->scrub->rules[$rulenumber]['all'] =			($_POST['all'] ? true : false);
	$_SESSION['pf']->scrub->rules[$rulenumber]['comment'] =		$_POST['comment'];

	if ($_POST['all']) {
		unset($_SESSION['pf']->scrub->rules[$rulenumber]['from']);
		unset($_SESSION['pf']->scrub->rules[$rulenumber]['to']);
	}

	$rule = $_SESSION['pf']->scrub->rules[$rulenumber];
}

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {

	$_SESSION['pf']->scrub->rules[$rulenumber] = $_SESSION['edit']['save'];
	unset ($_SESSION['edit']);
	if (!(isset($_SESSION['pf']->scrub->rules[$rulenumber]['all']) || isset($_SESSION['pf']->scrub->rules[$rulenumber]['from']))) {
		$_SESSION['pf']->scrub->del ($rulenumber);
	}
	header ("Location: scrub.php");
}

if (isset($_POST['save']) && $_POST['save'] == "save and return") {
	unset ($_SESSION['edit']);
	header ("Location: scrub.php");
}

/*
* Begin Output
*/
$active = "scrub";
page_header("Edit Scrub Rule");
?>

<div id="main">
<h2>Edit Scrub Rule</h2>
	<fieldset>
		<form id="theform" name="theform" action="<?php print $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber";?>" method="post">
			<table width="100%" cellspacing="0" cellpadding="10">

			<tr align="center">
			<td class="top" rowspan="2">
				<label for="direction">Direction</label>
				<select id="direction" name="direction" onchange="document.theform.submit()">
					<option value="" label=""></option>
					<option value="in" label="in" <?php print ($rule['direction'] == "in" ? "selected=\"selected\"" : "")?>>in</option>
					<option value="out" label="out" <?php print ($rule['direction'] == "out" ? "selected=\"selected\"" : "")?>>out</option>					
				</select>
			</td>

			<td class="top" rowspan="2">
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

			<td class="top" nowrap="nowrap">
				<label for="no-df">no-df</label>
				<input type="checkbox" id="no-df" name="no-df" value="no-df" <?php print ($rule['no-df'] ? "checked=\"checked\"" : "")?> />
			</td>

			<td class="top">
				<label for="min-ttl">min-ttl</label>
				<input type="text" id="min-ttl" name="min-ttl" size="4" value="<?php print $rule['min-ttl'];?>" />
			</td>

			<td class="toplast">
				<label for="fragment">Fragment</label>
				<select id="fragment" name="fragment">
					<option value="" label=""></option>
					<option value="reassemble" label="reassemble" <?php print ($rule['fragment'] == "reassemble" ? "selected=\"selected\"" : "")?>>reassemble</option>
					<option value="crop" label="crop" <?php print ($rule['fragment'] == "crop" ? "selected=\"selected\"" : "")?>>crop</option>
					<option value="drop-ovl" label="drop-ovl" <?php print ($rule['fragment'] == "drop-ovl" ? "selected=\"selected\"" : "")?>>drop-ovl</option>					
				</select>
			</td>

			</tr>
			<tr align="center">
			<td nowrap="nowrap">
				<label for="random-id">random-id</label>
				<input type="checkbox" id="random-id" name="random-id" value="random-id" <?php print ($rule['random-id'] ? "checked=\"checked\"" : "")?> />
			</td>

			<td>
				<label for="max-mss">max-mss</label>
				<input type="text" id="max-mss" name="max-mss" size="4" value="<?php print $rule['max-mss'];?>" />
			</td>
			
			<td class="last" nowrap="nowrap">
				<label for="reassemble">reassemble tcp</label>
				<input type="checkbox" id="reassemble" name="reassemble" value="tcp" <?php print ($rule['reassemble'] == "tcp" ? "checked=\"checked\"" : "")?> <?php print ($rule['direction'] ? "disabled=\"disabled\"" : ""); ?> />
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
				<td colspan="2" class="last">
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
			</tr>
			<tr align="center">
				<td>To</td>
				<td colspan="2" class="last">
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
					print "<input type=\"text\" id=\"addto\" name=\"addto\" value=\"ip, host or macro\" 
							onfocus=\"if (addto.value=='ip, host or macro') addto.value = '' \" 
							onblur=\"if (addto.value == '') addto.value = 'ip, host or macro'\"";
					if ($rule['all']) {
							print " disabled=\"disabled\"";
					}
					print " />";
					if (isset($rule['to'])) {
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
					<input type="text" id="comment" name="comment" value="<?php print stripslashes($rule['comment']);?>" size="80" style="width: 90%" />
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
<?php require('manual/scrub.php'); ?>
</body>
</html>