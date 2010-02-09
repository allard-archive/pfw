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
$rule = $_SESSION['pf']->anchor->rules[$rulenumber];

if ($_SESSION['edit']['type'] != 'table') {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['type'] = 'table';
}

if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['rulenumber'];
}

if (!isset($_SESSION['edit']['save']) && isset($rule['anchor'])) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['save'] = $_SESSION['pf']->anchor->rules[$rulenumber];
}

if (count($_POST)) {
	$_SESSION['pf']->anchor->rules[$rulenumber]['anchor'] = 	$_POST['anchor'];
	$_SESSION['pf']->anchor->rules[$rulenumber]['filename'] =	$_POST['filename'];
	$_SESSION['pf']->anchor->rules[$rulenumber]['comment'] =	$_POST['comment'];
	$rule = $_SESSION['pf']->anchor->rules[$rulenumber];
}

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {
	$_SESSION['pf']->anchor->rules[$rulenumber] = $_SESSION['edit']['save'];
	unset ($_SESSION['edit']);
	if (!isset($_SESSION['pf']->anchor->rules[$rulenumber]['identifier'])) {
		$_SESSION['pf']->anchor->del ($rulenumber);
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
page_header("Edit Loaded Anchor");
?>

<div id="main">
<h2>Edit Load Anchor</h2>
	<fieldset>
		<form id="theform" action="<?php print $_SERVER['PHP_SELF']. "?rulenumber=$rulenumber";?>" method="post">
			<table width="100%" cellspacing="0" cellpadding="10">
  			<tr>
	  			<th>Anchor</th>
		  		<th>Filename</th>
  			</tr>
  			<tr align="center">
	    		<td width="20%"><input type="text" id="anchor" name="anchor" size="20" value="<?php print $rule['anchor'];?>" /></td>
        	<td width="20%"><input type="text" id="filename" name="filename" size="40" value="<?php print $rule['filename'];?>" /></td>
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
<?php require('manual/anchor.php'); ?>
</body>
</html>