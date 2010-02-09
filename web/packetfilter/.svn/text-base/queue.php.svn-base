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
	$_SESSION['pf']->queue->addRule($_POST['addrulenumber']);
	header ("Location: queuedit.php?rulenumber=". $_POST['addrulenumber']);
}

if (isset($_POST['comment']) && ($_POST['comment'] == 'add')) {
	$_SESSION['pf']->macro->addRule($_POST['addcomment']);
	header ("Location: commentedit.php?action=queue&rulenumber=". $_POST['addcomment']);
}

if (isset($_POST['altqaddrulenumber']) && ($_POST['altqaddrulenumber'] != "")) {
	$_SESSION['pf']->altq->addRule($_POST['altqaddrulenumber']);
	header ("Location: altqedit.php?rulenumber=". $_POST['altqaddrulenumber']);
}

if (isset($_POST['altqcomment']) && ($_POST['altqcomment'] == 'add')) {
	$_SESSION['pf']->altq->addRule($_POST['altqaddcomment']);
	header ("Location: commentedit.php?action=altq&rulenumber=". $_POST['altqaddcomment']);
}

if (isset($_GET['up'])) {
	$_SESSION['pf']->queue->up($_GET['up']);
	reload();
}

if (isset($_GET['down'])) {
	$_SESSION['pf']->queue->down($_GET['down']);
	reload();
}

if (isset($_GET['del'])) {
	$_SESSION['pf']->queue->del($_GET['del']);
	reload();
}

if (isset($_GET['altqup'])) {
	$_SESSION['pf']->altq->up($_GET['altqup']);
	reload();
}

if (isset($_GET['altqdown'])) {
	$_SESSION['pf']->altq->down($_GET['altqdown']);
	reload();
}

if (isset($_GET['altqdel'])) {
	$_SESSION['pf']->altq->del($_GET['altqdel']);
	reload();
}

$active = "queue";
page_header("Traffic Prioritization");
?>

<div id="main">
<h2>Traffic Prioritization</h2>

<table>
<caption style="margin-top: 0">Interfaces</caption>
<thead>
<tr>
	<th>No</th>
	<th>Interface</th>
	<th>Bandwidth</th>
	<th>Scheduler</th>
	<th>Qlimit</th>
	<th>Tbrsize</th>
	<th>Queues</th>
	<th>Comment</th>
</tr>
</thead>

<tbody>
<?php
$rulenumber = '0';
foreach ($_SESSION['pf']->altq->rules as $rule) {
	if ($rule['type'] == 'comment') {
		print "<tr>\n\t<td>". $rulenumber. "</td>\n";
		print "\t<td class=\"comment\" colspan=\"";
		print ('7'). "\">". nl2br(stripslashes($rule['comment'])). "</td>\n";
	} else {
		if ($rulenumber%2) {
			print "<tr class=\"even\">\n";
		} else {
			print "<tr>\n";
		}
		print "\t<td>". $rulenumber. "</td>\n";
		print "\t<td>". $rule['interface']. "</td>\n";
		print "\t<td>". $rule['bandwidth']. "</td>\n";
		print "\t<td>". $rule['queuetype']. "</td>\n";
		print "\t<td>". $rule['qlimit']. "</td>\n";
		print "\t<td>". $rule['tbrsize']. "</td>\n";

		print "\t<td>";
		if (!is_array($rule['queue'])) {
			print $rule['queue'];
		} else {
			foreach ($rule['queue'] as $queue) {
				print "\n\t\t". $queue. "<br />";
			}
		}
		print "</td>\n";
		
		print "\t<td class=\"rulecomment\">". stripslashes($rule['comment']). "</td>\n";

	}
	print "\t<td class=\"edit\">";
	if ($rule['type'] != 'comment') {
		print "\n\t\t<a href=\"altqedit.php?rulenumber=$rulenumber\">e</a> ";
	} else {
		print "\n\t\t<a href=\"commentedit.php?action=altq&amp;rulenumber=$rulenumber\">e</a> ";
	}
	if ($rulenumber) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?altqup=$rulenumber\">u</a> ";
	} else {
		print "\n\t\t u ";
	}
	if ($rulenumber != (count($_SESSION['pf']->altq->rules) - '1')) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?altqdown=$rulenumber\">d</a> ";
	} else {
		print "\n\t\t d ";
	}
	print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?altqdel=$rulenumber\" title=\"Delete Rule\" onclick=\"return confirm('Are you sure you want to delete this Rule?')\">x</a> ";
	print "\n\t</td>";

	print "</tr>\n";
	$rulenumber++;
}
?>
</tbody>
</table>
	<fieldset>
		<form action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
			<div class="addrulesform">
				<label for="altqaddrulenumber">add new rule as rule number:</label>
				<input type="text" name="altqaddrulenumber" id="altqaddrulenumber" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" value="add" />

				<label for="altqaddcomment">add new comment as rule number:</label>
				<input type="text" name="altqaddcomment" id="altqaddcomment" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" name="altqcomment" value="add" />
			</div>
		</form>
	</fieldset>


<table>
<caption>Queues</caption>
<thead>
<tr>
	<th>No</th>
	<th>Name</th>
	<th>Bandwidth</th>
	<th>Priority</th>
	<th>Scheduler</th>
	<th>Parameters</th>
	<th>Queues</th>
	<th>Comment</th>
</tr>
</thead>

<tbody>
<?php
$rulenumber = '0';
$colorcount = '0';
for ($rulenumber = '0'; $rulenumber < (count($_SESSION['pf']->queue->rules)); $rulenumber++) {
	$rule = $_SESSION['pf']->queue->rules[$rulenumber];
	if ($rule['type'] == 'comment') {
		print "<tr>\n\t<td>". $rulenumber. "</td>\n";
		print "\t<td class=\"comment\" colspan=\"";
		print ('8'). "\">". nl2br($rule['comment']). "</td>\n";
	} else {
		$has_parent = false;
		foreach ($_SESSION['pf']->queue->rules as $queue_rules) {
			if (is_array($queue_rules['rules']) && in_array($rule['name'], $queue_rules['queue'])) {
				$has_parent = true;
			}
		}
		if (!$has_parent) {
			$colorcount++;
		}
		if ($colorcount%2) {
			print "<tr align=\"center\" class=\"even\">\n";
		} else {
			print "<tr align=\"center\">\n";
		}
		print "\t<td>". $rulenumber. "</td>\n";
		print "\t<td>". $rule['name']. "</td>\n";
		print "\t<td>". $rule['bandwidth']. "</td>\n";
		print "\t<td>". $rule['priority']. "</td>\n";
		print "\t<td>". $rule['scheduler']. "</td>\n";
		print "\t<td>";
		if ($rule['parameters']) {
			foreach ($rule['parameters'] as $parameters) {
				print $parameters. "<br />";
			}
		}
		print "</td>\n";

		print "\t<td>";
		if (!is_array($rule['queue'])) {
			print $rule['queue'];
		} else {
			foreach ($rule['queue'] as $queue) {
				print $queue. "<br />";
			}
		}
		print "</td>\n";
		
		print "\t<td class=\"rulecomment\">". $rule['comment']. "</td>\n";

	}
	print "\t<td class=\"edit\">";
	if ($rule['type'] != 'comment') {
		print "\n\t\t<a href=\"queueedit.php?rulenumber=$rulenumber\">e</a> ";
	} else {
		print "\n\t\t<a href=\"commentedit.php?action=altq&amp;rulenumber=$rulenumber\">e</a> ";
	}
	if ($rulenumber) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?up=$rulenumber\">u</a> ";
	} else {
		print "\n\t\t u ";
	}
	if ($rulenumber != (count($_SESSION['pf']->queue->rules) - '1')) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?down=$rulenumber\">d</a> ";
	} else {
		print "\n\t\t d ";
	}
	if ($rule['type'] == 'comment') {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?del=$rulenumber\" title=\"Delete Rule\" onclick=\"return confirm('Are you sure you want to delete this Rule?')\">x</a> ";
	}
	print "\n\t</td>\n";

	print "</tr>\n";
}

?>
</tbody>
</table>
	<fieldset>
		<form action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
			<div class="addrulesform">
				<label for="addcomment">add new comment as rule number:</label>
				<input type="text" name="addcomment" id="addcomment" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" name="comment" value="add" />
			</div>
		</form>
	</fieldset>
</div>
</body>
</html>