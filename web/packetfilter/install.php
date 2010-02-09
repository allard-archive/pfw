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

$db = sqlite_popen($database);
$results = sqlite_query("select name from pfhosts where master='". sqlite_escape_string($_SESSION['pfhost']['name']). "'", $db);
unset($pfhosts);
while ($row = sqlite_fetch_single($results)) {
  $pfhosts[] = $row;
}
sqlite_close($db);

if (count($_POST)) {
  unset($errors);
  if (is_array($_POST[install_on])) {
    foreach ($_POST[install_on] as $pfhost) {
      $tempfname = tempnam ("/tmp", "pf.conf.");
    	$_SESSION['pf']->writeFile ($tempfname);

      $db = sqlite_popen($database);
      $results = sqlite_query("select connect from pfhosts where name='". sqlite_escape_string($pfhost). "'", $db);
      $connect = sqlite_fetch_single($results);
      sqlite_close($db);
      exec("sudo $inst_dir/bin/packetfilter.sh '". $connect. "' -t $tempfname", $out, $retval);
      if ($retval) {
    	  $errors[] = "\t<li>Ruleset has errors when tested on ". $pfhost .", please test the ruleset and install again</li>\n";
    	}
    }
    if (!$errors) {
      foreach ($_POST[install_on] as $pfhost) {
      	$tempfname = tempnam ("/tmp", "pf.conf.");
      	$_SESSION['pf']->writeFile ($tempfname);
      	
      	$db = sqlite_popen($database);
        $results = sqlite_query("select connect from pfhosts where name='". sqlite_escape_string($pfhost). "'", $db);
        $connect = sqlite_fetch_single($results);
        sqlite_close($db);
        exec("sudo $inst_dir/bin/packetfilter.sh '". $connect. "' -i $tempfname", $out, $retval);
        if ($retval) {
      	  $errors[] = "\t<li>There was an error when installing on ". $pfhost .": ". $out. "</li>\n";
      	}
      }
    }
    if (!isset($errors)) {
      $messages[] = "\t<li>Installed successfully</li>\n";
    }
  } else {
    $errors[] = "\t<li>You need to select at least one firewall to install on</li>\n";
  }
}

$active = "write";
page_header("Install Ruleset");
?>
<div id="main">

<?php

if ($errors) {
  print "<div class=\"error\">\n";
  print "<ul>\n";
  foreach ($errors as $error) {
    print $error;
  }
  print "</ul>\n";
  print "</div>";
}

if ($messages) {
  print "<div class=\"notice\">\n";
  print "<ul>\n";
  foreach ($messages as $message) {
    print $message;
  }
  print "</ul>\n";
  print "</div>";
}

?>

<fieldset>
<form action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
<p>
  <label for="install_on_<?php print $_SESSION['pfhost']['name'];?>">
  <input type="checkbox" id="install_on_<?php print $_SESSION['pfhost']['name'];?>" name="install_on[0]" value="<?php print $_SESSION['pfhost']['name'];?>" checked="checked" />Install on <?php print $_SESSION['pfhost']['name'];?></label>

</p>

<?php $i = 1; foreach ($pfhosts as $pfhost) { ?>
<p>
  <label for="install_on_<?php print $pfhost;?>">
  <input type="checkbox" id="install_on_<?php print $pfhost;?>" name="install_on[]" value="<?php print $pfhost;?>" checked="checked" />Install on <?php print $pfhost;?></label>
</p>
<?php } ?>

<p style="padding-top: 1em;"><input type="submit" id="submit" value="install" /></p>

</form>

</div>
</body>
</html>
