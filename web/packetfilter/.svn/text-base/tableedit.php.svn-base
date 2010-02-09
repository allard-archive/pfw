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
$rule = $_SESSION['pf']->table->rules[$rulenumber];

if ($_SESSION['edit']['type'] != 'table') {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['type'] = 'table';
}

if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['rulenumber'];
}

if (!isset($_SESSION['edit']['save']) && isset($rule['identifier'])) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['save'] = $_SESSION['pf']->table->rules[$rulenumber];
}

if (isset($_GET['dropvalue'])) {
	$_SESSION['pf']->table->delEntity ("data", $_GET['rulenumber'], $_GET['dropvalue']);
	reload();
}

if (isset($_GET['dropfile'])) {
	$_SESSION['pf']->table->delEntity ("file", $_GET['rulenumber'], $_GET['dropfile']);
	reload();
}

if (isset($_POST['addvalue']) && !strpos($_POST['addvalue'], "networks")) {
	foreach (preg_split("/[\s,]+/", $_POST['addvalue']) as $value) {
		$_SESSION['pf']->table->addEntity("data", $rulenumber, trim($value));
	}
	reload();
}

if (isset($_POST['addfile']) && ($_POST['addfile'] != "filename")) {
	$_SESSION['pf']->table->addEntity("file", $rulenumber, preg_replace("/\"/", "", $_POST['addfile']));
	reload();
}

if (count($_POST)) {
	$_SESSION['pf']->table->rules[$rulenumber]['identifier'] = 	"<". preg_replace("/[<>]/", "", $_POST['identifier']). ">";
	$_SESSION['pf']->table->rules[$rulenumber]['const'] =		($_POST['const'] ? true : false);
	$_SESSION['pf']->table->rules[$rulenumber]['persist'] =		($_POST['persist'] ? true : false);
	$_SESSION['pf']->table->rules[$rulenumber]['comment'] =		$_POST['comment'];
	$rule = $_SESSION['pf']->table->rules[$rulenumber];
}

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {
	$_SESSION['pf']->table->rules[$rulenumber] = $_SESSION['edit']['save'];
	unset ($_SESSION['edit']);
	if (!isset($_SESSION['pf']->table->rules[$rulenumber]['identifier'])) {
		$_SESSION['pf']->table->del ($rulenumber);
	}
	header ("Location: table.php");
}

if (isset($_POST['save']) && $_POST['save'] == "save and return") {
	unset ($_SESSION['edit']);
	header ("Location: table.php");
}

/*
* Begin Output
*/

$active = "table";
page_header("Edit Table");

?>

<div id="main">
<h2>Edit Table</h2>
	<fieldset>
		<form id="theform" action="<?php print $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber";?>" method="post">
			<table width="100%" cellspacing="0" cellpadding="10">
			<tr>
				<th>Identifier</th>
				<th>Flags</th>
				<th>Values</th>
			</tr>
			<tr align="center">
			<td width="20%">
				<input type="text" id="identifier" name="identifier" size="20" value="<?php print $rule['identifier'];?>" />
			</td>

			<td nowrap="nowrap">
				<label for="const">const</label>
				<input type="checkbox" id="const" name="const" value="const" <?php print ($rule['const'] ? "checked=\"checked\"" : "")?> />

				<label for="persist">persist</label>
				<input type="checkbox" id="persist" name="persist" value="persist" <?php print ($rule['persist'] ? "checked=\"checked\"" : "")?> />
			</td>
			<td class="last">
				<?php
				if (isset($rule['data'])) {
					foreach ($rule['data'] as $data) {
						print "$data ";
						print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropvalue=$data\">del</a>";
						print "<br />";
					}
				}
				if (isset($rule['file'])) {
					if (is_array($rule['file'])) {
						foreach ($rule['file'] as $file) {
							print "file \"$file\" ";
							print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropfile=$file\">del</a>";
							print "<br />";
						}
					} else {
						print "file \"". $rule['file']. "\" ";
						print "<a href=\"". $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber&amp;dropfile='". $rule['file']. "'\">del</a>";
					}
				}
				if (isset($rule['data']) || isset($rule['file'])) {
					print "<div class=\"add\">";
				}
				?>
				<label for="addfile">add file</label><br />
				<input type="text" id="addfile" name="addfile" size="30" value="filename" 
							onfocus="if (addfile.value=='filename') addfile.value = '' " 
							onblur="if (addfile.value == '') addfile.value = 'filename'" />
				<br />
				<label for="addvalue">add hosts or networks</label><br />
				<textarea id="addvalue" name="addvalue" cols="30" rows="5" 
							onfocus="if (addvalue.value=='hosts or networks separated by comma, space or newline') addvalue.value = '' " 
							onblur="if (addvalue.value == '') addvalue.value = 'hosts or networks separated by comma, space or newline'">hosts or networks separated by comma, space or newline</textarea>
				<?php
				if (isset($rule['data'])) {
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
<?php require('manual/table.php'); ?>
</body>
</html>