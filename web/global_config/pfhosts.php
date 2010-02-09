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

if ($_GET['del']) {
  $dbw = sqlite_open($database);
  sqlite_query("delete from pfhosts where id='". sqlite_escape_string($_GET['del']). "'", $dbw);
  sqlite_close($dbw);
#  reload();
}

$db = sqlite_popen($database);
$results = sqlite_query("select * from pfhosts", $db);
unset($pfhosts);
while ($row = sqlite_fetch_array($results, SQLITE_ASSOC)) {
  $pfhosts[] = $row;
}
sqlite_close($db);

$active = "pfw";
$style = <<<EOS

th {padding: 0 2em; }

EOS;
page_header("pfw config");

?>
<div>
<h2>Packet Filter Hosts</h2>

<table style="width: auto; margin: 1em 0 0.5em 0;">
<thead>
	<tr>
  	<th>Name</th>
  	<th>Connection</th>
  	<th>Can Connect</th>
  	<th>Master</th>
  </tr>
</thead>
<tbody>

<?php foreach ($pfhosts as $pfhost) { ?>
  <tr>
    <td><?php print  $pfhost[name];?></td>
    <td><?php print  $pfhost[connect];?></td>
    <td><?php print  ($pfhost[can_connect] == "true" ? "Y": ""); ?></td>
    <td><?php print  $pfhost[master];?></td>

    <td class="edit" nowrap="nowrap"><a href="pfhostsedit.php?id=<?php print $pfhost['id'];?>">edit</a></td>
    <td class="edit" nowrap="nowrap"><a href="<?php print  $_SERVER['PHP_SELF']. "?del=". $pfhost['id'];?>" onclick="return confirm('Are you sure you want to delete this pfhost?')">delete</a></td>	
  </tr>

<?php } ?>

</tbody>
</table>

<a href="pfhostsedit.php">add host</a>

</div>
</body>
</html>