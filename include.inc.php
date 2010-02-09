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

$inst_dir = dirname(__FILE__);
$database = $inst_dir. "/conf/config.db";

/*
* Only display serious errors
*/
error_reporting(E_ERROR | E_PARSE);

/**
* Releads the current page. This is done to clean up the url after some
* variables has been passed and it's undesireble for the user to resubmit
* the changes through a page reload.
*
* @param string $url If any url parameters needs adding when reloading.
* @return void
*/
function reload($url = false)
{
	if (isset($_GET['rulenumber']) && !$url) {
	  $url = "?rulenumber=". $_GET['rulenumber'];
	}
	header("location: ".  $_SERVER['PHP_SELF']. $url);
}

/**
* Displayes the page header
*
* @param string $title The page title.
* @param string $prefix The relative directory level from the web root.
* @param integer $reload_rate The number of seconds to wait before reloading the page.
*                             With a NULL value, the page won't automatically reload.
*/
function page_header ($title, $prefix = "..", $reload_rate = false)
{
  global $inst_dir, $active, $pfhosts, $style, $database;

	print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n";
  print "\t\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	print "<html>\n";
	print "<head>\n";
	if (preg_match("/\| pfw/", $title)) {
		print "\t<title>$title</title>\n";
	} else {
		print "\t<title>$title | pfw</title>\n";	
	}
	if ($reload_rate) {
	  print "\t<meta http-equiv=\"refresh\" content=\"$reload_rate\" />";
	}
	print "\t<meta http-equiv=\"Content-type\" content=\"text/html; charset=utf-8\" />\n";
	print "\t<style type=\"text/css\" media=\"screen\">@import url($prefix/stylesheet/screen.css);</style>\n";
	print "\t<style type=\"text/css\" media=\"screen\">@import url(screen.css);</style>\n";
	print "\t<style type=\"text/css\" media=\"print\">@import url($prefix/stylesheet/print.css);</style>\n";
	print "\t<style type=\"text/css\" media=\"print\">@import url(print.css);</style>\n";
	if ($style) {
	  print "\t<style type=\"text/css\">\n". $style. "\n</style>\n";
	}
	print "</head>\n";

  print "\n<body onload=\"page_init();\">\n";

  ?>
  <div style="float: right;">
    <form method="post" id="hostform" name="hostform" action="<?php print preg_replace("/&/", "&amp;", $_SERVER['REQUEST_URI'], -1);?>">
    <div>
      <label for="pfhost"><strong>pf host</strong></label>
      <select id="pfhost" name="pfhost" onchange="document.hostform.submit()">
      <?php
      $db = sqlite_popen($database);
      $results = sqlite_query("select * from pfhosts where can_connect='true'", $db);

      while ($row = sqlite_fetch_array($results, SQLITE_ASSOC)) {
        print "\t\t<option value=\"". $row[name]. "\"";
        print $_SESSION['pfhost']['name'] == $row[name] ? " selected=\"selected\"" : ""; 
        print ">". $row[name]. "</option>\n";
      }
      sqlite_close($db);
      ?>
      </select>
      </div>
    </form>
  </div>
  <?php

	print "<div id=\"menu\">\n";
	print "<h1>pfw</h1>\n";
	print "<ul id=\"tabs\">\n";

	$dh  = opendir("$inst_dir/web");
	while (false !== ($dirname = readdir($dh))) {
	  if (!preg_match('/^\./', $dirname) && is_dir("$inst_dir/web/$dirname") && $dirname <> "stylesheet") {
	    print "\t<li";
	    if (strpos($_SERVER['PHP_SELF'], $dirname)) {
	      print " class=\"active\"";
	      $active_tab = $dirname;
	    }
	    print ">";
	    print "<a href=\"../$dirname/\">". str_replace("_", " ", $dirname). "</a></li>\n";
	  }
	}
	closedir($dh);
	print "</ul>\n";
	print "</div>";

	if (file_exists("$inst_dir/web/$active_tab/submenu.inc.php")) {
	  include_once("$inst_dir/web/$active_tab/submenu.inc.php");
	}

	if ($_SESSION['flash']['notice']) {
	  print "<div class=\"notice\">". $_SESSION['flash']['notice']. "</div>\n";
	  unset($_SESSION['flash']['notice']);
	}

	if ($_SESSION['flash']['error']) {
	  print "<div class=\"error\">". $_SESSION['flash']['error']. "</div>\n";
	  unset($_SESSION['flash']['error']);
	}

}

/*
 * Require all the files from the include directory
 */
$dh  = opendir("$inst_dir/include");
while (false !== ($filename = readdir($dh))) {
  if (!preg_match('/^\./', $filename)) {
    require_once("$inst_dir/include/$filename");
  }
}
closedir($dh);


session_name("pfw_session");
session_start();

/*
 * Set the $_SESSION['pfhost'] and load the first firewall from the config if none is loaded.
 */
$db = sqlite_popen($database);
if (isset($_POST['pfhost']) || !isset($_SESSION['pfhost'])) {
  if (isset($_POST['pfhost'])) {
    $results = sqlite_query("select * from pfhosts where name='". sqlite_escape_string($_POST['pfhost']). "'", $db);
    $row = sqlite_fetch_array($results, SQLITE_ASSOC);
    
    $_SESSION['pfhost'] = $row;
  } else {
    list($key, $val) = each($pfhosts);
    $results = sqlite_query("select * from pfhosts where can_connect='true'", $db);
    $row = sqlite_fetch_array($results, SQLITE_ASSOC);
    $_SESSION['pfhost'] = $row;
  }

  if (is_array($_SESSION['pfhost']) && array_key_exists('connect', $_SESSION['pfhost'])) {
    $_SESSION['pfhost']['connect'] = "'". $_SESSION['pfhost']['connect']. "'";
  } else {
    $_SESSION['pfhost']['connect'] = "'ssh ". $_SESSION['pfhost']['name']. "'";
  }
  unset($_SESSION['pf']);
}
sqlite_close($db);

if (!isset($_SESSION['pf'])) {
  $_SESSION['pf'] = new pf ();
  $filename = exec ("sudo $inst_dir/bin/packetfilter.sh ". $_SESSION['pfhost']['connect']. " -r");
  $_SESSION['pf']->parseRulebase (file_get_contents($filename));
  unlink ($filename);
  unset($_SESSION['filename']);
}


if (!isset($_SESSION['edit'])) {
	$_SESSION['edit'] = array();
}
?>