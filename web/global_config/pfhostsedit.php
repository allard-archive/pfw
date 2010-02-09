<?php
/*
 * Copyright (c) 2004 Allard Consulting.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * 3. All advertising materials mentioning features or use of this
 *    software must display the following acknowledgement: This
 *    product includes software developed by Allard Consulting
 *    and its contributors.
 *
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

function set_pfhost_writeable ($name)
{
  global $database;
  
  $db  = sqlite_popen($database);
  $results = sqlite_query("select * from pfhosts where name='". sqlite_escape_string($name) ."'", $db);
  $pfhost_check = sqlite_fetch_array($results, SQLITE_ASSOC);
  if ($pfhost_check['can_connect'] == "false") {
    $results = sqlite_query("update pfhosts set can_connect='true' where name='". sqlite_escape_string($name) ."'", $db);
  }
  sqlite_close($db);
}

function set_pfhost_nonwritable ($name)
{
  global $database;
  
  $db  = sqlite_popen($database);
  $results = sqlite_query("select * from pfhosts where name='". sqlite_escape_string($name) ."'", $db);
  $pfhost_check = sqlite_fetch_array($results, SQLITE_ASSOC);
  if ($pfhost_check['can_connect'] == "true") {
    $results = sqlite_query("update pfhosts set can_connect='false' where name='". sqlite_escape_string($name) ."'", $db);
  }
  sqlite_close($db);  
}

if (count($_POST)) {
  $dbw  = sqlite_popen($database);
  if ($_GET['id']) {
    $sql  = "update pfhosts set ";
    $sql .= "name='". sqlite_escape_string($_POST['name']). "', ";
    $sql .= "connect='". sqlite_escape_string($_POST['connect']). "', ";
    $sql .= "master='". sqlite_escape_string($_POST['master']). "' ";
    $sql .= "where id='". sqlite_escape_string($_GET['id']). "'";
  } else {
    $sql  = "insert into pfhosts (name, connect, master) values ";
    $sql .= "('". sqlite_escape_string($_POST['name']). "', '". sqlite_escape_string($_POST['connect']). "', ";
    $sql .= "'". sqlite_escape_string($_POST['master']). "')";
  }

  sqlite_query($sql, $dbw);
  sqlite_close($dbw);
  
  if (!$_GET['id']) {
    $db = sqlite_popen($database);
    $results = sqlite_query("select * from pfhosts where name='". sqlite_escape_string($_POST['name']) ."'", $db);
    $pfhost = sqlite_fetch_array($results, SQLITE_ASSOC);
    sqlite_close($db);
    header("Location: ". $_SERVER['PHP_SELF']. "?id=". $pfhost['id']);
  }
}

if ($_GET['id']) {
  $db = sqlite_popen($database);
  $results = sqlite_query("select * from pfhosts where id='". sqlite_escape_string($_GET['id']) ."'", $db);
  $pfhost = sqlite_fetch_array($results, SQLITE_ASSOC);
  sqlite_close($db);  
}

$db = sqlite_popen($database);
$results = sqlite_query("select * from pfhosts", $db);
while ($row = sqlite_fetch_array($results, SQLITE_ASSOC)) {
  $pfhosts[] = $row;
}
sqlite_close($db);

$active = "pfw";
$style = <<<EOS

tr {text-align: left; }
.right {text-align: right; }

EOS;
page_header("edit pfhost");
?>
<div>
<h2>Edit Packet Filter Host</h2>

<a href="pfhosts.php">&lt;&lt;&lt; Return to pf hosts</a>

<fieldset>
	<form method="post" id="theform" action="<?php print $_SERVER['PHP_SELF']. "?id=". $_GET['id'];?>">
		<table style="width: auto;">
			<tr>
			  <td class="right"><label for="name">Name</label></td>
        <td><input type="text" id="name" name="name" size="32" maxlength="32" value="<?php print  $pfhost['name'];?>" /></td>
        <td>The name needs to be unique.</td>
		  </tr>

			<tr>
			  <td class="right"><label for="connect">Connect</label></td>
			  <td><input type="text" id="connect" name="connect" size="32" maxlength="128" value="<?php print  $pfhost['connect'];?>" /></td>
			  <td>Examples: localhost, ssh pf.example.com, ssh -p 2222 pf1.example.com</td>
		  </tr>

      <tr>
      <td class="right"><label for="master">Master</label></td>
      <td>
				<select id="master" name="master">
					<option value="" label=""></option>
					<?php foreach ($pfhosts as $host) { ?>
					<option value="<?php print $host['name'];?>" label="<?php print $host['name'];?>" <?php print ($pfhost['master'] == $host['name'] ? "selected=\"selected\"" : "");?>><?php print $host['name'];?></option>
					<?php } ?>
				</select>
			</td>
			<td>If a master is defined, the masters ruleset will automatically be installed on this host when the master gets a ruleset installed.</td>
			</tr>
		</table>
		
		<div style="margin-top: 1em;">
			<input type="submit" id="save" name="save" value="save" />
		</div>
	</form>
</fieldset>

<?php
if ($pfhost['connect'] && ($pfhost['connect'] != "localhost")) {
  print "<h2>Connection testing</h2>";
  $result = exec("sudo $inst_dir/bin/commandwrapper.sh '". $pfhost['connect']. "' -conntest");
  if ($result == "connected") {
    print "<h3 style=\"color: green\">Successfully connected to ". $pfhost['name']. " using \"". $pfhost['connect']. "\"</h3>";
    set_pfhost_writeable($pfhost['name']);
  } else {
    print "<h3 style=\"color: red\">Error when connecting: ". $result. "</h3>";
    print "<div style=\"border: 1px solid red; width: 50%; padding: 1em; color: red; font-size: 120%; line-height: 1.5em;\">";
    print "Please login as root and make sure that it's possible to login without using a password and try again.";
    print "</div>";
    set_pfhost_nonwritable($pfhost['name']);
  }
}
?>

</div>
</body>
</html>