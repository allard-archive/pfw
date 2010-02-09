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

$rule = $_SESSION['pf']->options->rules;

if ($_SESSION['edit']['type'] != 'altq') {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['type'] = 'altq';
}

if ($_SESSION['edit']['rulenumber'] != $rulenumber) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['rulenumber'];
}
	
if (!isset($_SESSION['edit']['save'])) {
	unset ($_SESSION['edit']['save']);
	$_SESSION['edit']['save'] = $_SESSION['pf']->options->rules;
}

if (count($_POST)) {
	$_SESSION['pf']->options->rules['block-policy'] =		$_POST['blockpolicy'];
	$_SESSION['pf']->options->rules['optimization'] =		$_POST['optimization'];
	$_SESSION['pf']->options->rules['statepolicy'] =		$_POST['state-policy'];
	$_SESSION['pf']->options->rules['debug'] =				$_POST['debug'];
	$_SESSION['pf']->options->rules['fingerprints'] =		trim(preg_replace("/\"/", "", $_POST['fingerprints']));
	$_SESSION['pf']->options->rules['states'] =				trim($_POST['states']);
	$_SESSION['pf']->options->rules['frags'] =				trim($_POST['frags']);
	$_SESSION['pf']->options->rules['src-nodes'] =			trim($_POST['srcnodes']);
	if (strlen(trim($_POST['loginterface']))) {
		$_SESSION['pf']->options->rules['loginterface'] =	trim($_POST['loginterface']);
	} else {
		unset($_SESSION['pf']->options->rules['loginterface']);
	}
	if (strlen(trim($_POST['tcp_first']))) {
		$_SESSION['pf']->options->rules['tcp']['first'] = trim($_POST['tcp_first']);
	} else {
		unset($_SESSION['pf']->options->rules['tcp']['first']);
	}
	if (strlen(trim($_POST['tcp_opening']))) {
		$_SESSION['pf']->options->rules['tcp']['opening'] =	trim($_POST['tcp_opening']);
	} else {
		unset($_SESSION['pf']->options->rules['tcp']['opening']);
	}
	if (strlen(trim($_POST['tcp_established']))) {
		$_SESSION['pf']->options->rules['tcp']['established'] =	trim($_POST['tcp_established']);
	} else {
		unset ($_SESSION['pf']->options->rules['tcp']['established']);
	}
	if (strlen(trim($_POST['tcp_closing']))) {
		$_SESSION['pf']->options->rules['tcp']['closing'] =		trim($_POST['tcp_closing']);
	} else {
		unset ($_SESSION['pf']->options->rules['tcp']['closing']);
	}
	if (strlen(trim($_POST['tcp_finwait']))) {
		$_SESSION['pf']->options->rules['tcp']['finwait'] =		trim($_POST['tcp_finwait']);
	} else {
		unset ($_SESSION['pf']->options->rules['tcp']['finwait']);
	}
	if (strlen(trim($_POST['tcp_closed']))) {
		$_SESSION['pf']->options->rules['tcp']['closed'] =		trim($_POST['tcp_closed']);
	} else {
		unset ($_SESSION['pf']->options->rules['tcp']['closed']);
	}
	if (strlen(trim($_POST['udp_first']))) {
		$_SESSION['pf']->options->rules['udp']['first'] =		trim($_POST['udp_first']);
	} else {
		unset ($_SESSION['pf']->options->rules['udp']['first']);
	}
	if (strlen(trim($_POST['udp_single']))) {
		$_SESSION['pf']->options->rules['udp']['single'] =		trim($_POST['udp_single']);
	} else {
		unset ($_SESSION['pf']->options->rules['udp']['single']);
	}
	if (strlen(trim($_POST['udp_multiple']))) {
		$_SESSION['pf']->options->rules['udp']['multiple'] =	trim($_POST['udp_multiple']);
	} else {
		unset ($_SESSION['pf']->options->rules['udp']['multiple']);
	}
	if (strlen(trim($_POST['icmp_first']))) {
		$_SESSION['pf']->options->rules['icmp']['first'] =		trim($_POST['icmp_first']);
	} else {
		unset ($_SESSION['pf']->options->rules['icmp']['first']);
	}
	if (strlen(trim($_POST['icmp_error']))) {
		$_SESSION['pf']->options->rules['icmp']['error'] =		trim($_POST['icmp_error']);
	} else {
		unset ($_SESSION['pf']->options->rules['icmp']['error']);
	}
	if (strlen(trim($_POST['other_first']))) {
		$_SESSION['pf']->options->rules['other']['first'] =		trim($_POST['other_first']);
	} else {
		unset ($_SESSION['pf']->options->rules['other']['first']);
	}
	if (strlen(trim($_POST['other_single']))) {
		$_SESSION['pf']->options->rules['other']['single'] =	trim($_POST['other_single']);
	} else {
		unset ($_SESSION['pf']->options->rules['other']['single']);
	}
	if (strlen(trim($_POST['other_multiple']))) {
		$_SESSION['pf']->options->rules['other']['multiple'] =	trim($_POST['other_multiple']);
	} else {
		unset ($_SESSION['pf']->options->rules['other']['multiple']);
	}
	if (strlen(trim($_POST['adaptive_start']))) {
		$_SESSION['pf']->options->rules['adaptive']['start'] =	trim($_POST['adaptive_start']);
	} else {
		unset ($_SESSION['pf']->options->rules['adaptive']['start']);
	}
	if (strlen(trim($_POST['adaptive_end']))) {
		$_SESSION['pf']->options->rules['adaptive']['end'] =	trim($_POST['adaptive_end']);
	} else {
		unset ($_SESSION['pf']->options->rules['adaptive']['end']);
	}
	if (strlen(trim($_POST['src_track']))) {
		$_SESSION['pf']->options->rules['src_track'] =	trim($_POST['src_track']);
	} else {
		unset ($_SESSION['pf']->options->rules['src_track']);
	}
	if (strlen(trim($_POST['src_track']))) {
		$_SESSION['pf']->options->rules['src_track'] =	trim($_POST['src_track']);
	} else {
		unset ($_SESSION['pf']->options->rules['src_track']);
	}
	if (strlen(trim($_POST['frag']))) {
		$_SESSION['pf']->options->rules['frag'] =	trim($_POST['frag']);
	} else {
		unset ($_SESSION['pf']->options->rules['frag']);
	}
	if (strlen(trim($_POST['interval']))) {
		$_SESSION['pf']->options->rules['interval'] =	trim($_POST['interval']);
	} else {
		unset ($_SESSION['pf']->options->rules['interval']);
	}
	if (strlen(trim($_POST['skip']))) {
	  if (count(preg_split("/[\s,\t]+/", trim($_POST['skip']))) > 1) {
	    unset($_SESSION['pf']->options->rules['skip']);
	    foreach (preg_split("/[\s,\t]+/", trim($_POST['skip'])) as $skip) {
			  $_SESSION['pf']->options->rules['skip'][] = $skip;
		  }
		} else {
		  $_SESSION['pf']->options->rules['skip'] = trim($_POST['skip']);
		}
	} else {
	  unset($_SESSION['pf']->options->rules['skip']);
	}
	$rule = $_SESSION['pf']->options->rules;
}

/*
* go back
*/
if (isset($_POST['cancelme']) && ($_POST['cancelme'] == 'cancel')) {
	$_SESSION['pf']->options->rules = $_SESSION['edit']['save'];
	unset ($_SESSION['edit']);
	reload();
}

/*
* Begin Output
*/

$active = "options";
page_header("Options");
?>

<div id="main">
<h2>Edit Options</h2>
<fieldset>
	<form id="theform" action="<?php print $_SERVER['PHP_SELF'];?>" method="post">
		<table>
		<tr>
			<td class="toplast"><textarea cols="80" rows="5" style="width:100%"><?php print stripslashes($rule['comment']);?></textarea></td>
		</tr>
		</table>

		<table>
		<tr>
			<td>
				<label for="blockpolicy">Block Policy</label>
				<select id="blockpolicy" name="blockpolicy">
					<option value=""></option>
					<option value="drop" label="drop" <?php print ($rule['block-policy'] == "drop" ? "selected=\"selected\"" : "");?>>drop</option>
					<option value="return" label="return" <?php print ($rule['block-policy'] == "return" ? "selected=\"selected\"" : "");?>>return</option>
				</select>
			</td>

			<td>
				<label for="optimization">Optimization</label>
				<select id="optimization" name="optimization">
					<option value=""></option>
					<option value="normal" <?php print ($rule['optimization'] == "normal" ? "selected=\"selected\"" : "");?>>normal</option>
					<option value="high-latency" <?php print ($rule['optimization'] == "high-latency" ? "selected=\"selected\"" : "");?>>high-latency</option>
					<option value="satellite" <?php print ($rule['optimization'] == "satellite" ? "selected=\"selected\"" : "");?>>satellite</option>
					<option value="aggressive" <?php print ($rule['optimization'] == "aggressive" ? "selected=\"selected\"" : "");?>>aggressive</option>
					<option value="conservative" <?php print ($rule['optimization'] == "conservative" ? "selected=\"selected\"" : "");?>>conservative</option>
				</select>
			</td>

			<td>
				<label for="statepolicy">State Policy</label>
				<select id="statepolicy" name="statepolicy">
					<option value=""></option>
					<option value="if-bound" <?php print ($rule['state-policy'] == "if-bound" ? "selected=\"selected\"" : "");?>>if-bound</option>
					<option value="floating" <?php print ($rule['state-policy'] == "floating" ? "selected=\"selected\"" : "");?>>floating</option>
				</select>
			</td>

			<td>
				<label for="fingerprints">Fingerprints file</label>
				<input type="text" size="10" id="fingerprints" name="fingerprints" value="<?php print $rule['fingerprints'];?>" />
			</td>

			<td>
				<label for="loginterface">loginterface</label>
				<input type="text" size="10" id="loginterface" name="loginterface" value="<?php print $rule['loginterface'];?>" />
			</td>

			<td>
				<label for="debug">Debug</label>
				<select id="debug" name="debug">
					<option value=""></option>
					<option value="none" <?php print ($rule['debug'] == "none" ? "selected=\"selected\"" : "");?>>none</option>
					<option value="urgent" <?php print ($rule['debug'] == "urgent" ? "selected=\"selected\"" : "");?>>urgent</option>
					<option value="misc" <?php print ($rule['debug'] == "misc" ? "selected=\"selected\"" : "");?>>misc</option>
					<option value="loud" <?php print ($rule['debug'] == "loud" ? "selected=\"selected\"" : "");?>>loud</option>
				</select>
			</td>
		</tr>
		</table>

		<table>
		<caption>Skip Interfaces</caption>
		<tr>
			<td style="width: 10%" class="top"><label for="skip">set skip on</label></td>
			<td style="text-align: left;">
				<input type="text" size="40" id="skip" name="skip" value="<?php print (is_array($rule['skip']) ? join($rule['skip'], ", ") : $rule['skip']);?>" />
				<em>(comma or space separated list of interfaces)</em>
			</td>
		</tr>
		</table>

		<table>
		<caption>Limits &amp; Timeouts</caption>
		<tr>
			<td style="width: 10%" class="top">Limit</td>
			<td>
				<label for="states">States</label>
				<input type="text" size="10" id="states" name="states" value="<?php print $rule['states'];?>" />
			</td>
			<td>
				<label for="frags">Frags</label>
				<input type="text" size="10" id="frags" name="frags" value="<?php print $rule['frags'];?>" />
			</td>
			<td>
				<label for="srcnodes">Src-Nodes</label>
				<input type="text" size="10" id="srcnodes" name="srcnodes" value="<?php print $rule['src-nodes'];?>" />
			</td>
			<td>
				<label for="tables">Tables</label>
				<input type="text" size="10" id="tables" name="tables" value="<?php print $rule['tables'];?>" />
			</td>
			<td>
				<label for="table_entries">Table-Entries</label>
				<input type="text" size="10" id="table_entries" name="table_entries" value="<?php print $rule['table_entries'];?>" />
			</td>
		</tr>
		</table>

    <table>
    <tr>
      <td style="width: 10%">Timeout</td>
      <td>
        <label for="interval">Interval</label>
        <input type="text" size="10" id="interval" name="interval" value="<?php print $rule['interval'];?>" />
      </td>
      <td>
        <label for="frag">Frag</label>
        <input type="text" size="10" id="frag" name="frag" value="<?php print $rule['frag'];?>" />
      </td>
      <td>
        <label for="src_track">Src.track</label>
        <input type="text" size="10" id="src_track" name="src_track" value="<?php print $rule['src_track'];?>" />
      </td>
    </table>

		<table>
		<tr class="even">
			<td style="width: 10%">TCP</td>
			<td>
				<label for="tcp_first">First</label>
				<input type="text" size="10" id="tcp_first" name="tcp_first" value="<?php print $rule['tcp']['first'];?>" />
			</td>
			<td>
				<label for="tcp_opening">Opening</label>
				<input type="text" size="10" id="tcp_opening" name="tcp_opening" value="<?php print $rule['tcp']['opening'];?>" />
			</td>
			<td>
				<label for="tcp_established">Established</label>
				<input type="text" size="10" id="tcp_established" name="tcp_established" value="<?php print $rule['tcp']['established'];?>" />
			</td>
			<td>
				<label for="tcp_closing">Closing</label>
				<input type="text" size="10" id="tcp_closing" name="tcp_closing" value="<?php print $rule['tcp']['closing'];?>" />
			</td>
			<td>
				<label for="tcp_finwait">Fin Wait</label>
				<input type="text" size="10" id="tcp_finwait" name="tcp_finwait" value="<?php print $rule['tcp']['finwait'];?>" />
			</td>
			<td>
				<label for="tcp_closed">Closed</label>
				<input type="text" size="10" id="tcp_closed" name="tcp_closed" value="<?php print $rule['tcp']['closed'];?>" />
			</td>
		</tr>
		</table>

		<table>
		<tr>
			<td style="width: 10%">UDP</td>
			<td>
				<label for="udp_first">First</label>
				<input type="text" size="10" id="udp_first" name="udp_first" value="<?php print $rule['udp']['first'];?>" />
			</td>
			<td>
				<label for="udp_single">Single</label>
				<input type="text" size="10" id="udp_single" name="udp_single" value="<?php print $rule['udp']['single'];?>" />
			</td>
			<td class="last">
				<label for="udp_multiple">Multiple</label>
				<input type="text" size="10" id="udp_multiple" name="udp_multiple" value="<?php print $rule['udp']['multiple'];?>" />
			</td>
		</tr>
		</table>

		<table>
		<tr class="even">
			<td style="width: 10%">ICMP</td>
			<td>
				<label for="icmp_first">First</label>
				<input type="text" size="10" id="icmp_first" name="icmp_first" value="<?php print $rule['icmp']['first'];?>" />
			</td>
			<td class="last">
				<label for="icmp_error">Error</label>
				<input type="text" size="10" id="icmp_error" name="icmp_error" value="<?php print $rule['icmp']['error'];?>" />
			</td>
		</tr>
		</table>

		<table>
		<tr>
			<td style="width: 10%">Other</td>
			<td>
				<label for="other_first">First</label>
				<input type="text" size="10" id="other_first" name="other_first" value="<?php print $rule['other']['first'];?>" />
			</td>
			<td>
				<label for="other_single">Single</label>
				<input type="text" size="10" id="other_single" name="other_single" value="<?php print $rule['other']['single'];?>" />
			</td>
			<td class="last">
				<label for="other_multiple">Multiple</label>
				<input type="text" size="10" id="other_multiple" name="other_multiple" value="<?php print $rule['other']['multiple'];?>" />
			</td>
		</tr>
		</table>

		<table>
		<tr class="even">
			<td style="width: 10%">Adaptive</td>
			<td>
				<label for="adaptive_start">Start</label>
				<input type="text" size="10" id="adaptive_start" name="adaptive_start" value="<?php print $rule['adaptive']['start'];?>" />
			</td>
			<td class="last">
				<label for="adaptive_end">End</label>
				<input type="text" size="10" id="adaptive_end" name="adaptive_end" value="<?php print $rule['adaptive']['end'];?>" />
			</td>
		</tr>
		</table>
		<div class="buttons">
			<input type="submit" id="save" name="save" value="save" />
			<input type="submit" id="cancelme" name="cancelme" value="cancel" />
		</div>
	</form>
</fieldset>
</div>
<?php require('manual/options.php'); ?>
</body>
</html>