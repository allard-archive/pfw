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
$rule = $_SESSION['pf']->$_GET['action']->rules[$rulenumber];

if ($_SESSION['edit']['type'] != 'comment') {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['type'] = 'comment';
}

if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['rulenumber'];
}

if (!isset($_SESSION['edit']['save'])) {
	$_SESSION['edit']['save'] = $_SESSION['pf']->$_GET['action']->rules[$rulenumber];
}

if (count($_POST)) {
	$_SESSION['pf']->$_GET['action']->rules[$rulenumber]['type'] = "comment";
	$_SESSION['pf']->$_GET['action']->rules[$rulenumber]['comment'] = $_POST['comment'];
	$rule = $_SESSION['pf']->$_GET['action']->rules[$rulenumber];
}

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {
	
	$_SESSION['pf']->$_GET['action']->rules[$rulenumber] = $_SESSION['edit']['save'];
	unset ($_SESSION['edit']);
	if (!isset($_SESSION['pf']->$_GET['action']->rules[$rulenumber]['identifier'])) {
		$_SESSION['pf']->$_GET['action']->del ($rulenumber);
	}
	header ("Location: ". $_GET['action']. ".php");
}

if (isset($_POST['save']) && $_POST['save'] == "save and return") {
	unset ($_SESSION['edit']);
	header ("Location: ". $_GET['action']. ".php");
}

$active = $_GET['action'];
page_header("Edit Comment");

?>

<div id="main">
	<fieldset>
		<form id="theform" action="<?php print $_SERVER['PHP_SELF']. "?action=". $_GET['action']. "&amp;rulenumber=$rulenumber";?>" method="post">
			<table width="100%" cellspacing="0" cellpadding="10">
				<tr>
					<th>Comment</th>
				</tr>
				<tr>
					<td class="last">
						<textarea cols="80" rows="5" style="width: 95%" id="comment" name="comment"><?php print stripslashes($rule['comment']);?></textarea>
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
</body>
</html>