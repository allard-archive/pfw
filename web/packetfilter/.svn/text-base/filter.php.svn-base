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
 
require_once("../../include.inc.php");

if (isset($_POST['rule']) && ($_POST['rule'] == 'add')) {
	$_SESSION['pf']->filter->addRule($_POST['addrulenumber']);
	header ("Location: filteredit.php?rulenumber=". $_POST['addrulenumber']);
}

if (isset($_POST['anchor']) && ($_POST['anchor'] == 'add')) {
	$_SESSION['pf']->filter->addRule($_POST['addanchor']);
	header ("Location: anchoredit.php?action=filter&rulenumber=". $_POST['addanchor']);
}

if (isset($_POST['comment']) && ($_POST['comment'] == 'add')) {
	$_SESSION['pf']->filter->addRule($_POST['addcomment']);
	header ("Location: commentedit.php?action=filter&rulenumber=". $_POST['addcomment']);
}

if (isset($_GET['up'])) {
	$_SESSION['pf']->filter->up($_GET['up']);
	reload();
}

if (isset($_GET['down'])) {
	$_SESSION['pf']->filter->down($_GET['down']);
	reload();
}

if (isset($_GET['del'])) {
	$_SESSION['pf']->filter->del($_GET['del']);
	reload();
}


foreach ($_SESSION['pf']->filter->rules as $rule) {
	if (array_key_exists("direction", $rule)) {
		$has_direction = '1';
	}
	if (array_key_exists("comment", $rule) && $rule['type'] != 'comment') {
		$has_comment = '1';
	}
}

$active = "filter";
page_header("filter");

?>

<div id="main">
<h2>Packet Filter</h2>

<table>
<thead>
	<tr>
	<th>No</th>
	<th>Type</th>
	<?php if ($has_direction) { ?>
	<th>Direction</th>
	<?php } ?>
	<th>On</th>
	<th>Log</th>
	<th>Quick</th>
	<th>Proto</th>
	<th>From</th>
	<th>Port</th>
	<th>To</th>
	<th>Port</th>
	<th>State</th>
	<?php if ($has_comment) { ?>
	<th>Comment</th>
	<?php } ?>
</tr>
</thead>
<tbody>

<?php
$rulenumber = '0';
foreach ($_SESSION['pf']->filter->rules as $rule) {
  if ($rulenumber%2) {
  	print "<tr class=\"even\">\n";
  } else {
  	print "<tr>\n";
  }
  print "\t<td>". $rulenumber. "</td>\n";
	if ($rule['type'] == 'comment') {
		print "\t<td class=\"comment\" colspan=\"";
		print ('10' + $has_direction + $has_comment). "\">". nl2br(stripslashes($rule['comment'])). "</td>\n";
	}	else {
		print "\t<td class=\"". $rule['type']. "\">". $rule['type'];
		if ($rule['type'] == 'anchor') {
		  print " ". $rule['identifier'];
		}
		print "</td>\n";
		if ($has_direction) {
			print "\t<td>". $rule['direction']. "</td>\n";
		}

		if (is_array($rule['interface'])) {
			print "\t<td>";
			foreach ($rule['interface'] as $interface) {
				print "\n\t\t". $interface. "<br />";
			}
			print "\n\t</td>\n";
		} else {
			print "\t<td>". $rule['interface']. "</td>\n";
		}

		print "\t<td>". ($rule['log'] ? "log" : ""). "</td>\n";
		print "\t<td>". ($rule['quick'] ? "quick" : ""). "</td>\n";
		print "\t<td>";
		if (!is_array($rule['proto'])) {
			print $rule['proto'];
		} else {
			foreach ($rule['proto'] as $proto) {
				print "\n\t\t". $proto. "<br />";
			}
			print "\n\t";
		}
		print "</td>\n";
		if ($rule['all']) {
			print "\t<td colspan=\"4\" class=\"all\">All</td>\n";
		} else {
			if (is_array($rule['from'])) {
				print "\t<td>";
				foreach ($rule['from'] as $from) {
					print "\n\t\t". htmlentities($from). "<br />";
				}
				print "\n\t</td>\n";
			} else if ($rule['type'] == 'anchor'){
			  print "\t<td>". htmlentities($rule['from']). "</td>\n";
			} else {
				print "\t<td>". ($rule['from'] ? htmlentities($rule['from']) : "any"). "</td>\n";
			}
	
			if (is_array($rule['fromport'])) {
				print "\t<td>";
				foreach ($rule['fromport'] as $port) {
					print "\n\t\t". $port. "<br />";
				}
				print "\n\t</td>\n";
			} else {
				print "\t<td>". $rule['fromport']. "</td>\n";
			}
			
			
			if (is_array($rule['to'])) {
				print "\t<td>";
				foreach ($rule['to'] as $to) {
					print "\n\t\t". htmlentities($to). "<br />";
				}
				print "\n\t</td>\n";
			} else if ($rule['type'] == 'anchor'){
			  print "\t<td>". htmlentities($rule['to']). "</td>\n";
			} else {
				print "\t<td>". ($rule['to'] ? htmlentities($rule['to']) : "any"). "</td>\n";
			}
	
			if (is_array($rule['port'])) {
				print "\t<td>";
				foreach ($rule['port'] as $port) {
					print "\n\t\t". $port. "<br />";
				}
				print "</td>\n";
			} else {
				print "\t<td>". $rule['port']. "</td>\n";
			}
		}

		print "\t<td>". $rule['state']. "</td>\n";
		
		if (isset($has_comment)) {
			print "\t<td style=\"width: 20%\" class=\"rulecomment\">". stripslashes($rule['comment']). "</td>\n";
		}
	}

	print "\t<td class=\"edit\">";
	if ($rule['type'] == 'comment') {
	  print "\n\t\t<a href=\"commentedit.php?action=filter&amp;rulenumber=$rulenumber\" title=\"Edit Rule\">e</a> ";
	} else if ($rule['type'] == 'anchor') {
	  print "\n\t\t<a href=\"anchoredit.php?action=filter&amp;rulenumber=$rulenumber\" title=\"Edit Rule\">e</a> ";
	} else {
		print "\n\t\t<a href=\"filteredit.php?rulenumber=$rulenumber\" title=\"Edit Rule\">e</a> ";
	}
	if ($rulenumber) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?up=$rulenumber\" title=\"Move Rule Up\">u</a> ";
	} else {
		print "\n\t\t u ";
	}
	if ($rulenumber != (count($_SESSION['pf']->filter->rules) - 1)) {
		print "\n\t\t<a href=\"". $_SERVER['PHP_SELF']. "?down=$rulenumber\" title=\"Move Rule Down\">d</a> ";
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
				<input type="text" name="addrulenumber" id="addrulenumber" size="5" value="<?php  print $rulenumber; ?>" />
				<input type="submit" name="rule" value="add" />

				<label for="addanchor">add new anchor as rule number:</label>
				<input type="text" name="addanchor" id="addanchor" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" name="anchor" value="add" />

				<label for="addcomment">add new comment as rule number:</label>
				<input type="text" name="addcomment" id="addcomment" size="5" value="<?php print $rulenumber;?>" />
				<input type="submit" name="comment" value="add" />
			</div>
		</form>
	</fieldset>
</div>
</body>
</html>
