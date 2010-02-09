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

function parse_states_info ($states_data)
{
  foreach (preg_split("/^([^\s]+)/m", $states_data, '-1', PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE) as $state) {
    $states_pre[] = $state;
  }
  for ($i = 0; $i < count($states_pre); $i++) {
    $states[] = $states_pre[$i++]. $states_pre[$i];
  }
  $state_a = array();
  foreach ($states as $state) {
    $atom = array();
    foreach (preg_split("/[\s\t\n]+/", $state, '-1', PREG_SPLIT_NO_EMPTY) as $state_atom) {
      $atom[] = $state_atom;
    }

    $state_a['origin'] = $atom[0];
    $state_a['proto'] = $atom[1];

    if ($atom[3] == "->") {
      $state_a['dir'] = "out";
      $state_a['src'] = $atom[2];
      if ($atom[5] == "->") {
        $state_a['dst'] = $atom[6];
        $state_a['gw'] = $atom[4];
        $current = 7;
      } else {
        $state_a['dst'] = $atom[4];
        $state_a['gw'] = "";
        $current = 5;
      }
    } else {
      $state_a['dir'] = "in";
      $state_a['dst'] = $atom[2];
      if ($atom[5] == "<-") {
        $state_a['src'] = $atom[6];
        $state_a['gw'] = $atom[4];
        $current = 7;
      } else {
        $state_a['src'] = $atom[4];
        $state_a['gw'] = "";
        $current = 5;
      }
    }

    $state_a['state_state'] = $atom[$current++];

    for ($i = $current; $i < count($atom); $i++) {
      switch ($atom[$i]) {
      case "age":
      case "rule":
        $state_a[$atom[$i]] = chop($atom[++$i], ",");
        break;
      case "expires":
        $i += 2;
        $state_a['expires'] = chop($atom[$i++], ",");
        $packets_a = preg_split("/:/", $atom[$i]);
        $state_a['packets'] = bytes_to_s($packets_a[0] + $packets_a[1]);
        $i += 2;
        $bytes_a = preg_split("/:/", $atom[$i]);
        $state_a['bytes'] = bytes_to_s($bytes_a[0] + $bytes_a[1]). "b";
        break;
      }
    }

    $states_a[] = $state_a;
  }

  return $states_a;
}

if (trim($_GET['regexp'])) {
  $regexp = "'". stripslashes($_GET['regexp']). "'";
}
if (trim($_GET['count'])) {
  $count = trim($_GET['count']);
} else {
  $count = '200';
}

$active = "states";
$style = <<<EOS
td { padding: 0.2em;}
EOS;
page_header("Active States", "..", $_GET['reload']);

$connect = $_SESSION['pfhost']['connect'];
$count_adjusted = $count * 3; # each stateline has 3 lines of output
$states = parse_states_info (`sudo $inst_dir/bin/commandwrapper.sh $connect -pfstates $count_adjusted $regexp`);

?>
<h2>Packet Filter states</h2>

<form action="<?php print $_SERVER['PHP_SELF']; ?>">
<p>
  <label for="count">Display the first</label>
  <input type="text" id="count" name="count" value="<?php print $count;?>" style="text-align: right;" size="6" /> states. 

  <label for="reload">Reload page every</label>
  <input type="text" id="reload" name="reload" value="<?php print  $_GET['reload'];?>" size="4" style="text-align: right" />s

  <input type="submit" id="submit" value="go" />
</p>
<p>
  <label for="regexp">Regexp Search</label>
  <input type="text" id="regexp" name="regexp" value="<?php print stripslashes($_GET['regexp']);?>" size="100" />
</p>
</form>

<table>
  <tr>
    <th>no</th>
    <th>origin</th>
    <th>proto</th>
    <th>dir</th>
    <th>source</th>
    <th>gateway</th>
    <th>destination</th>
    <th>state</th>
    <th>age</th>
    <th>expires</th>
    <th>packets</th>
    <th>bytes</th>
    <th>rule</th>
  </tr>

<?php $count = 1; foreach ($states as $state) { ?>
  <tr>
    <td><?php print  $count++; ?></td>
    <td><?php print  $state['origin']; ?></td>
    <td><?php print  $state['proto']; ?></td>
    <td><?php print  $state['dir']; ?></td>
    <td><?php print  $state['src']; ?></td>
    <td><?php print  $state['gw']; ?></td>
    <td><?php print  $state['dst']; ?></td>
    <td><?php print  $state['state_state']; ?></td>
    <td><?php print  $state['age']; ?></td>
    <td><?php print  $state['expires']; ?></td>
    <td><?php print  $state['packets']; ?></td>
    <td><?php print  $state['bytes']; ?></td>
    <td><?php print  $state['rule']; ?></td>
  </tr>
<?php } ?>
</table>

</body>
</html>
