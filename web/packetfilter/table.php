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
	$_SESSION['pf']->table->addRule($_POST['addrulenumber']);
	header ("Location: tableedit.php?rulenumber=". $_POST['addrulenumber']);
}

if (isset($_POST['addanchorumber']) && ($_POST['addanchorumber'] != "")) {
	$_SESSION['pf']->anchor->addRule($_POST['addanchorumber']);
	header ("Location: anchorloadedit.php?rulenumber=". $_POST['addanchorumber']);
}

if (isset($_POST['comment']) && ($_POST['comment'] == 'add')) {
	$_SESSION['pf']->table->addRule($_POST['addcomment']);
	header ("Location: commentedit.php?action=table&rulenumber=". $_POST['addcomment']);
}

if (isset($_POST['anchorcomment']) && ($_POST['anchorcomment'] == 'add')) {
	$_SESSION['pf']->anchor->addRule($_POST['addanchorcomment']);
	header ("Location: commentedit.php?action=anchor&rulenumber=". $_POST['addanchorcomment']);
}

if (isset($_GET['up'])) {
	$_SESSION['pf']->table->up($_GET['up']);
	reload();
}

if (isset($_GET['down'])) {
	$_SESSION['pf']->table->down($_GET['down']);
	reload();
}

if (isset($_GET['del'])) {
	$_SESSION['pf']->table->del($_GET['del']);
	reload();
}

if (isset($_GET['aup'])) {
	$_SESSION['pf']->anchor->up($_GET['aup']);
	reload();
}

if (isset($_GET['adown'])) {
	$_SESSION['pf']->anchor->down($_GET['adown']);
	reload();
}

if (isset($_GET['adel'])) {
	$_SESSION['pf']->anchor->del($_GET['adel']);
	reload();
}


$active = "table";
page_header("Tables");
?>

<div id="main">
<h2>Tables</h2>

<table>
<thead>
<tr>
	<th>No</th>
	<th>Identifier</th>
	<th>Flags</th>
	<th>Value</th>
	<th>Comment</th>
</tr>
</thead>

<tbody>
<?php
$rulenumber = '0';
foreach ($_SESSION['pf']->table->rules as $rule) {
	if ($rule['type'] == 'comment') {
		print "<tr>\n\t<td>". $rulenumber. "</td>\n";
		print "\t<td class=\"comment\" colspan=\"";
		print ('4'). "\">". nl2br(stripslashes($rule['comment'])). "</td>\n";
	} else {
		if ($rulenumber%2) {
			print "<tr class=\"even\">\n";
		} else {
			print "<tr>\n";
		}
		print "\t<td>". $rulenumber. "</td>\n";

		print "\t<td>". htmlentities($rule['identifier']). "</td>\n";

		print "\t<td>";
		if ($rule['const']) {
			print "const<br />";
		}
		if ($rule['persist']) {
			print "persist";
		}
		print "</td>\n";

		print "\t<td>";
		if ($rule['data']) {
			$i = '0';
			foreach ($rule['data'] as $value) {
				print "\n\t\t". $value. "<br />";
				if (++$i > '10') {
					break;
				}
			}
			if ($i > '10') {
				print "+". (count($rule['data']) - '11'). " more entries (not displayed)";
			}
		}
		if ($rule['file']) {
			if (!is_array($rule['file'])) {
				print " file \"". $rule['file']. "\"";
			} else {
				foreach ($rule['file'] as $file) {
					print "\n\t\t file \"". $file. "\"<br />";
				}
			}
		}
		print "\n\t</td>\n";

		print "\t<td class=\"rulecomment\">". stripslashes($rule['comment']). "</td>\n";
	}
	print "\t<td class=\"edit\">";
	if ($rule['type'] != 'comment') {
		print "\n\t\t<a href=\"tableedit.php?rulenumber=$rulenumber\">e</a> ";
	} else {
		print "\n\t\t<a href=\"commentedit.php?action=table&amp;rulenumber=$rulenumber\">e</a> ";
	}
	if ($rulenumber) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?up=$rulenumber\">u</a> ";
	} else {
		print "\n\t\t u ";
	}
	if ($rulenumber != (count($_SESSION['pf']->table->rules) - 1)) {
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
			<div class="addrulesform">
				<label for="addrulenumber">add new rule as rule number:</label>
				<input type="text" name="addrulenumber" id="addrulenumber" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" value="add" />

				<label for="addcomment">add new comment as rule number:</label>
				<input type="text" name="addcomment" id="addcomment" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" name="comment" value="add" />
			</div>
		</form>
	</fieldset>


<h2>Loaded Anchors</h2>
<table>
<thead>
	<tr>
		<th>No</th>
		<th>Anchor</th>
		<th>Load from File</th>
		<th>Comment</th>
	</tr>
	</thead>

	<tbody>
	<?php
	$rulenumber = '0';
	$colorcount = '0';
	for ($rulenumber = '0'; $rulenumber < (count($_SESSION['pf']->anchor->rules)); $rulenumber++) {
		$rule = $_SESSION['pf']->anchor->rules[$rulenumber];
		if ($rule['type'] == 'comment') {
			print "<tr>\n\t<td>". $rulenumber. "</td>\n";
			print "\t<td class=\"comment\" colspan=\"3\">". nl2br(stripslashes($rule['comment'])). "</td>\n";
		} else {
			if ($colorcount%2) {
				print "<tr align=\"center\" class=\"even\">\n";
			} else {
				print "<tr align=\"center\">\n";
			}
			print "\t<td>". $rulenumber. "</td>\n";
			print "\t<td>". $rule['anchor']. "</td>\n";
			print "\t<td>". $rule['filename']. "</td>\n";
			print "\t<td class=\"rulecomment\">". stripslashes($rule['comment']). "</td>\n";

		}
		print "\t<td class=\"edit\">";
		if ($rule['type'] == 'comment') {
		  print "\n\t\t<a href=\"commentedit.php?action=anchor&amp;rulenumber=$rulenumber\">e</a> ";
		} else {
		  print "\n\t\t<a href=\"anchorloadedit.php?rulenumber=$rulenumber\">e</a> ";
		}
		if ($rulenumber) {
			print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?aup=$rulenumber\">u</a> ";
		} else {
			print "\n\t\t u ";
		}
		if ($rulenumber != (count($_SESSION['pf']->anchor->rules) - '1')) {
			print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?adown=$rulenumber\">d</a> ";
		} else {
			print "\n\t\t d ";
		}
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?adel=$rulenumber\" title=\"Delete Rule\" onclick=\"return confirm('Are you sure you want to delete this Rule?')\">x</a> ";
		print "\n\t</td>\n";

		print "</tr>\n";
	}

	?>
	</tbody>
	</table>
		<fieldset>
			<form action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
				<div class="addrulesform">
				  <label for="addanchornumber">add new load anchor as rule number:</label>
				  <input type="text" name="addanchorumber" id="addanchornumber" size="5" value="<?php print $rulenumber;?>" />
				  <input type="submit" value="add" />

					<label for="addanchorcomment">add new comment as rule number:</label>
					<input type="text" name="addanchorcomment" id="addanchorcomment" size="5" value="<?php print $rulenumber;?>" />
					<input type="submit" name="anchorcomment" value="add" />
				</div>
			</form>
		</fieldset>

</div>
</body>
</html>