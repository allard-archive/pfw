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

include_once("../../include.inc.php");


function parse_info ($infodata)
{
  foreach (preg_split("/\n/", $infodata, '-1', PREG_SPLIT_NO_EMPTY) as $infoline) {
    $infolines[] = $infoline;
  }
  preg_match("/up (.*), \d+ user.*averages: (\d[\d\.,]+)[,\s]+(\d[\d\.,]+)[,\s]+(\d[\d\.,]+)/", $infolines[0], $match);
  $info["uptime"] = $match[1];
  $info["load_1"] = $match[2];
  $info["load_5"] = $match[3];
  $info["load_15"] = $match[4];
  $info["date"] = $infolines[1];
  $info["securelevel"] = $infolines[2];
  $info["ip4forward"] = $infolines[3];
  $info["ip6forward"] = $infolines[4];
  $info["hostname"] = $infolines[5];
  $info["os"] = $infolines[6];
  $info["cpu"] = $infolines[7];

  return $info;
}

$active = "overview";
$style = <<<EOS
.tright { text-align: left;}
EOS;
page_header("System Overview", "..");

$connect = $_SESSION['pfhost']['connect'];

$info = parse_info(`sudo $inst_dir/bin/commandwrapper.sh $connect -info`);

?>
<div id="left" style="float: right; width: 45%;">

<h2>Packet Filter Overview</h2>

<pre>
<?php print  `sudo $inst_dir/bin/commandwrapper.sh $connect -pfinfo`; ?>
<?php print  `sudo $inst_dir/bin/commandwrapper.sh $connect -pfmem`; ?>
</pre>

</div>

<div id="right" style="width: 45%;">

<h2>System Overview</h2>

  <table>
    <tr class="even">
      <td width="15%">Hostname</td>
      <td class="tright"><?php print  $info['hostname']; ?></td>
    </tr>
 
    <tr>
      <td width="15%">Operating System</td>
      <td class="tright"><?php print  $info['os']; ?></td>
    </tr>

    <tr class="even">
      <td width="15%">Processor</td>
      <td class="tright"><?php print  $info['cpu']; ?></td>
    </tr>

    <tr>
      <td width="15%">CPU Load</td>
      <td class="tright">
      &nbsp;&nbsp;1 minute average: <?php print  $info['load_1']; ?><br />
      &nbsp;&nbsp;5 minute average: <?php print  $info['load_5']; ?><br />
      15 minute average: <?php print  $info['load_15'] ;?></td>
    </tr>

    <tr class="even">
      <td width="15%">Date</td>
      <td class="tright"><?php print  $info['date'];?></td>
    </tr>

    <tr>
      <td width="15%">Uptime</td>
      <td class="tright"><?php print  $info['uptime']; ?></td>
    </tr>

    <tr class="even">
      <td width="15%">SecureLevel</td>
      <td class="tright"><?php print  $info['securelevel']; ?></td>
    </tr>

    <tr>
      <td width="15%">Partitions</td>
      <td class="tright">
         <?php
         $output = exec("sudo $inst_dir/bin/commandwrapper.sh $connect -df", $out , $return);
         foreach ($out as $line) {
		
            print "$line<br />";
         }
         ?>
      </td>
    </tr>

    <tr class="even">
      <td width="15%">IP Forwarding</td>
      <td class="tright">
	IP v4: <?php print  $info['ip4forward']; ?><br />
	IP v6: <?php print  $info['ip6forward']; ?>
      </td>

    </tr>
  </table>

</div>

</body>
</html>
