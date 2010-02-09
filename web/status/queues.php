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

function bytes_to_s ($bytes)
{
  if (($bytes/1073741824) > 1 ) {
    return intval($bytes/1073741824). "G";
  } else if (($bytes/1048576) > 1) {
    return intval($bytes/1048576). "M";
  } else if (($bytes/1024) > 1) {
    return intval($bytes/1024). "K";
  } else {
    return $bytes;
  }
}

function parse_queue_data ($qdata)
{
  $qdata = preg_replace ("/\n/", "", $qdata);
  foreach (preg_split("/queue/", $qdata, '-1', PREG_SPLIT_NO_EMPTY) as $qline) {

    /*
     * Sanitize the rule string so that we can deal with '{foo' as '{ foo' in 
     * the code further down without any special treatment
     */
    $qline = preg_replace("/{/", " { ",   $qline);
    $qline = preg_replace("/}/", " } ",   $qline);
    $qline = preg_replace("/\(/", " ( ",  $qline);
    $qline = preg_replace("/\)/", " ) ",  $qline);
    $qline = preg_replace("/,/", "",   $qline);
    $qline = preg_replace("/\"/", " \" ", $qline);
    $qline = trim($qline);

    $qs = array();
    foreach (preg_split("/[\s\t]+/", $qline) as $qstatement) {
      $qs[] = $qstatement;
    }

    $qad = array();
    $qad['name'] = $qs[0];
    for ($i = '1'; $i < count($qs); $i++) {
    	switch ($qs[$i]) {
    	case "bandwidth":
    	case "priority":
    	  $qad[$qs[$i]] = $qs[++$i];
    	  break;
    	case "cbq":
    	case "priq":
    	case "hsfs":
    	  $qad["type"] = $qs[$i];
    	  break;
    	case "pkts:":
    	  $qad["packets"] = bytes_to_s($qs[++$i]);
    	  break;
    	case "bytes:":
    	  $qad["bytes"] = bytes_to_s($qs[++$i]). "b";
    	  break;
    	case "borrows:":
    	  $qad["borrows"] = bytes_to_s($qs[++$i]);
    	  break;
    	case "suspends:":
    	  $qad["suspends"] = bytes_to_s($qs[++$i]);
    	  break;
    	case "dropped":
    	  $i += 2;
    	  $qad["dropped_packets"] = bytes_to_s($qs[$i]);
    	  $i += 2;
    	  $qad["dropped_bytes"] = bytes_to_s($qs[$i]). "b";
    	  break;
    	case "qlength:":
    	  $qad["queue_length"] = $qs[++$i]. $qs[++$i];
    	  break;
    	case "{":
    	  while (preg_replace("/[\s,]+/", "", $qs[++$i]) != "}") {
    		  $qad['childqueues'][] = $qs[$i];
    	  }
    	  break;
  	  case "(":
  	    while (preg_replace("/[\s,]+/", "", $qs[++$i]) != ")") {
  	  	  $qad['options'][] = $qs[$i];
  	    }
  	    break;
  	  }
  	}

    $qa[] = $qad;
  }
  return $qa;
}

$pfhost = $_SESSION['pfhost']['connect'];
$queues = parse_queue_data(`sudo $inst_dir/bin/commandwrapper.sh $pfhost -queues`);

$active = "queues";
$style = <<<EOS
td { padding: 0.1em; }
caption {
  margin: 0;
  padding: 0 0 0.3em 0;
  font-weight: bold;
  font-size: 120%;
}
EOS;
page_header("Queue Statistics", "..", $_GET['reload']);
?>
<table>
  <caption>Queue Statistics</caption>
  <tr>
    <th>name</th>
    <th>bandwidth</th>
    <th>type</th>
    <th>priority</th>
    <th>options</th>
    <th>packets</th>
    <th>bytes</th>
    <th>dropped<br />packets</th>
    <th>dropped<br />bytes</th>
    <th>queue<br />length</th>
    <th>borrows</th>
    <th>suspended</th>
    <th>child<br />queues</th>
  </tr>
  
<?php foreach ($queues as $queue) {
  if ($queue['childqueues']) {
    print "<tr style=\"border-top: 1px solid black\">\n";
  } else {
  print "<tr>\n";
  }
  ?>
    <td><?php print  $queue['name']; ?></td>
    <td><?php print  $queue['bandwidth']; ?></td>
    <td><?php print  $queue['priority']; ?></td>
    <td><?php print  $queue['type']; ?></td>
    <td><?php if ($queue['options']) { foreach ($queue['options'] as $option) { print $option. "<br />"; } } ?></td>
    <td><?php print  $queue['packets']; ?></td>
    <td><?php print  $queue['bytes']; ?></td>
    <td><?php print  $queue['dropped_packets']; ?></td>
    <td><?php print  $queue['dropped_bytes']; ?></td>
    <td><?php print  $queue['queue_length']; ?></td>
    <td><?php print  $queue['borrows']; ?></td>
    <td><?php print  $queue['suspends']; ?></td>
    <td><?php if ($queue['childqueues']) { foreach ($queue['childqueues'] as $queue) { print $queue. "<br />"; } } ?></td>
  </tr>
<?php } ?>
</table>

</body>
</html>