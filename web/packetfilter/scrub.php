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

if (isset($_POST['addrulenumber']) && ($_POST['addrulenumber'] != "")) {
	$_SESSION['pf']->scrub->addRule($_POST['addrulenumber']);
	header ("Location: scrubedit.php?rulenumber=". $_POST['addrulenumber']);
}

if (isset($_GET['up'])) {
	$_SESSION['pf']->scrub->up($_GET['up']);
	reload();
}

if (isset($_GET['down'])) {
	$_SESSION['pf']->scrub->down($_GET['down']);
	reload();
}

if (isset($_GET['del'])) {
	$_SESSION['pf']->scrub->del($_GET['del']);
	reload();
}

$active = "scrub";
page_header("Normalization");
?>

<div id="main">
<h2>Traffic Normalization</h2>

<table>
<thead>
<tr>
	<th>No</th>
	<th>Direction</th>
	<th>On</th>
	<th>From</th>
	<th>To</th>
	<th>min-ttl</th>
	<th>max-mss</th>
	<th>Fragment</th>
	<th>Options</th>
	<th>Comment</th>
</tr>
</thead>

<tbody>
<?php
$rulenumber = '0';
foreach ($_SESSION['pf']->scrub->rules as $rule) {
	if ($rule['type'] == 'comment') {
		print "<tr>\n\t<td>". $rulenumber. "</td>\n";
		print "\t<td class=\"comment\" colspan=\"";
		print ('9'). "\">". nl2br(stripslashes($rule['comment'])). "</td>\n";
	} else {
		if ($rulenumber%2) {
			print "<tr class=\"even\">\n";
		} else {
			print "<tr>\n";
		}
		print "\t<td>". $rulenumber. "</td>\n";
		print "\t<td>". $rule['direction']. "</td>\n";

		print "\t<td>". $rule['interface']. "</td>\n";
		if ($rule['all']) {
			print "\t<td colspan=\"2\">All</td>\n";
		} else {
			print "\t<td>". ($rule['from'] ? $rule['from'] : "any"). "</td>\n";
			print "\t<td>". ($rule['to'] ? $rule['to'] : "any"). "</td>\n";
		}

		print "\t<td>". $rule['min-ttl']. "</td>\n";
		print "\t<td>". $rule['max-mss']. "</td>\n";
		print "\t<td>". $rule['fragment']. "</td>\n";

		print "\t<td>";

		if ($rule['no-df']) {
			print " no-df";
		}
		if ($rule['random-id']) {
			print " random-id";
		}
		if ($rule['reassemble']) {
			print " reassemble ". $rule['reassemble'];
		}

		print "\t</td>\n";

		print "\t<td class=\"rulecomment\">". stripslashes($rule['comment']). "</td>\n";
	}
	print "\t<td class=\"edit\">";
	if ($rule['type'] != 'comment') {
		print "\n\t\t<a href=\"scrubedit.php?rulenumber=$rulenumber\">e</a> ";
	} else {
		print "\n\t\t<a href=\"commentedit.php?action=scrub&amp;rulenumber=$rulenumber\" title=\"Edit Rule\">e</a> ";
	}
	if ($rulenumber) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?up=$rulenumber\">u</a> ";
	} else {
		print "\n\t\t u ";
	}
	if ($rulenumber != (count($_SESSION['pf']->scrub->rules) - 1)) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?down=$rulenumber\">d</a> ";
	} else {
		print "\n\t\t d ";
	}
	print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?del=$rulenumber\" title=\"Delete Rule\" onclick=\"return confirm('Are you sure you want to delete this Rule?')\">x</a> ";
	print "\n\t</td>\n";

	print "</tr>\n";
	$rulenumber++;
}
?>
</tbody>
</table>
	<fieldset>
		<form action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
			<div id="addrulesform">
				<label for="addrulenumber">add new rule as rule number:</label>
				<input type="text" name="addrulenumber" id="addrulenumber" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" value="add" />

				<label for="addcomment">add new comment as rule number:</label>
				<input type="text" name="addcomment" id="addcomment" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" name="comment" value="add" />
			</div>
		</form>
	</fieldset>
</div>
</body>
</html>