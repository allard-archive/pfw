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

$active = "write";
$style = <<<EOS
.errline {
  padding: 1em;
  border-bottom: 2px solid gray;
  font-size: 120%;
  line-height: 1.5em;
}
EOS;
page_header("Test Rulebase");

?>
<div id="main">

<?php
	$tempfname = tempnam("/tmp", "pf.conf.");
	$_SESSION['pf']->writeFile($tempfname);

  $last_line = exec ("sudo $inst_dir/bin/packetfilter.sh ". $_SESSION['pfhost']['connect']. " -t $tempfname", $out, $return);

	if (!$return) {
		print "<h3>The ruleset has no errors</h3>";
	} else {
		print "<h3>The Ruleset has the following errors</h3>";
#		print $last_line. " return: ". $return;
    $ruleset = $_SESSION['pf']->generate();
    $ruleset_a = preg_split('/\n/', $ruleset, -1);
		foreach ($out as $line) {
		  $line_a = preg_split('/:/', $line, -1, PREG_SPLIT_NO_EMPTY);
		  print "<div class=\"errline\">\n";
			print $line_a[2]. " on line ". $line_a[1]. ": <br />\n";
			print $ruleset_a[$line_a[1] - 1]. "<br />\n";
			print "</div>\n";
		}
	}
?>	
</div>

</body>
</html>
