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
**/
?>
<html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
  <title>Check Install | pfw</title>
  <style>
    h1 { margin-top: 1em; }
    h3 { 
      margin-top: 2em;
      margin-bottom: 1.4em;
      border-bottom: 1px solid black;
    }
    p { margin-left: 4em; }
    pre { margin-left: 8em; }
    .success {
      font-weight: bold;
      color: green;
    }
    .error {
      font-weight: bold;
      color: red;
    }
  </style>
</head>

<?php
 
require_once("../include.inc.php");

print "<h1>Checking pfw installation</h1>";

print "<h3>Checking for chrooted Apache installation.</h3>";

if (is_file('/etc/pf.conf')) {
  print "<p>/etc/pf.conf exists. We assume that Apache is not chrooted.</p>";
} else {
  print "<p class=\"error\">/etc/pf.conf does not exist, Apache is probably chrooted. 
  Please restart Apache using 'httpd -u'</p>";
}

print "<h3>Checking if sudo is correctly installed.</h3>";

system("sudo $inst_dir/bin/commandwrapper.sh localhost -df >/dev/null", $retval);

if (!$retval) {
  print "<p class=\"success\">sudo command executed successfully.</p>";
} else {
  print "<p class=\"error\">sudo command unsuccessful. Please add the following using visudo:</p>";
  print "<pre>";
  $processUser = posix_getpwuid(posix_geteuid());
  print $processUser['name']. " ALL = NOPASSWD: $inst_dir/bin/*";
  print "</pre>";
}

print "<h3>Checking file permissions.</h3>";
$value = exec("find ". $inst_dir. "/conf ! -user ". getmyuid(). " | wc -l");

if ($value == 0) {
  print "<p class=\"success\">Success, the conf directory is owned by the web server user.</p>";
} else {
  print "<p class=\"error\">Failure, the conf directory needs to be owned by the web user. Please fix by:</p>";
  print "<pre>";
  print "chown -R ". getmyuid(). " $inst_dir/conf";
  print "</pre>";  
}

print "<h3>Checking for SQLite.</h3>";
$value = exec("php -i | grep SQLite | wc -l");
if ($value != 0) {
  print "<p class=\"success\">Success, the SQLite module in PHP is installed and enabled.</p>";
} else {
  print "<p class=\"error\">Failure, the SQLite module in PHP is not enabled. Please fix by:</p>";
  print "<p>OpenBSD 3.9:</p>";
  print "<pre>pkg_add ftp://ftp.openbsd.org/pub/OpenBSD/3.9/packages/i386/php5-sqlite-5.0.5p0.tgz</pre>";
  print "<p>OpenBSD 3.8:</p>";
  print "<pre>pkg_add ftp://ftp.openbsd.org/pub/OpenBSD/3.8/packages/i386/php5-sqlite-5.0.4.tgz</pre>";
  print "<p>Other platforms:</p>";
  print "<pre>pear install sqlite</pre>";
}

?>
</html>
