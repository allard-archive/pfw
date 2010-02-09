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

class nat extends rules
{
	function parse ($rule_string)
	{
		$data = array();
		if (strpos($rule_string, "#")) {
			$data['comment'] = substr($rule_string, strpos($rule_string, "#") + '1');
			$rule_string =  substr($rule_string, '0', strpos($rule_string, "#"));
		}

		/*
		 * Sanitize the rule string so that we can deal with '{foo' as '{ foo' in 
		 * the code further down without any special treatment
		 */
		$rule_string = preg_replace("/! +/", "!",   $rule_string);
		$rule_string = preg_replace("/{/", " { ",   $rule_string);
		$rule_string = preg_replace("/}/", " } ",   $rule_string);
		$rule_string = preg_replace("/\"/", " \" ",   $rule_string);

		foreach (preg_split("/[\s,\t]+/", $rule_string) as $rule) {
			$rules[] = $rule;
		}
		for ($i = '0'; $i < count($rules); $i++) {
			switch ($rules[$i]) {
			case "rdr":
				$data['type'] = "rdr";
				break;
			case "no":
				$data['type'] = "no ". $rules[++$i];
				break;
			case "nat":
				$data['type'] = "nat";
				break;
			case "binat":
				$data['type'] = "binat";
				break;
			case "pass":
				$data['pass'] = true;
				break;
			case "nat-anchor":
			case "rdr-anchor":
			case "binat-anchor":
			  $data['type'] = $rules[$i++];
			  if ($rules[$i] == "\"") {
			    $data['identifier'] = $rules[++$i];
			    if ($rules[$i + 1] == "\"") {
			      $i++;
			    }
			  } else {
			    $data['identifier'] = $rules[$i];
			  }
			  break;
			case "netmask":
			  $data['netmask'] = $rules[++$i];
			  break;
			case "random":
			case "source-hash":
			case "round-robin":
			case "static-port":
  			$data[$i++] = true;
  			break;
			case "on":
			  list($data['interface'], $i) = $this->parseItem($rules, $i);
				break;
			case "proto":
			  list($data['proto'], $i) = $this->parseItem($rules, $i);
				break;
			case "from":
				$data['from'] = $rules[++$i];
				if ($rules[$i+'1'] == "port") {
					$i += '2';
					$data['fromport'] = $rules[$i];
				}
				break;
			case "to":
				$data['to'] = $rules[++$i];
				break;
			case "port":
				$data['port'] = $rules[++$i];
				break;
			case "->":
				$data['natdest'] = $rules[++$i];
				if ($rules[$i+'1'] == 'port') {
					$i += '2';
					$data['natdestport'] = $rules[$i];
				}
			}
		}
		return $data;
	}

	function generate()
	{
		$data = "";
		if ($this->rules) {
			foreach ($this->rules as $rule) {
				if ($rule['type'] == 'comment') {
					$lines = preg_split("/\n/", stripslashes($rule['comment']));
					$data .= "\n";
					foreach ($lines as $line) {
						$data .= "# ". $line. "\n";
					}
					continue;
				}
				$data .= $rule['type'];
				if ($rule['pass']) {
					$data .= " pass";
				}
				if ($rule['identifier']) {
				  # Rule type is anchor, nat-anchor or binat-anchor
				  $data .= " \"". $rule['identifier']. "\"";
				}
				if ($rule['interface']) {
				  $data .= $this->generateItem($rule['interface'], "on");
				}
				if ($rule['proto']) {
					$data .= $this->generateItem($rule['proto'], "proto");
				}
				if ($rule['from']) {
					$data .= " from ". stripslashes($rule['from']);
				} else if ($rule['type'] <> ("nat-anchor" || "binat-anchor" || "rdr-anchor")) {
					$data .= " from any ";
				}
				if ($rule['to']) {
					$data .= " to ". stripslashes($rule['to']);
				} else if ($rule['type'] <> ("nat-anchor" || "binat-anchor" || "rdr-anchor")) {
					$data .= " to any ";
				}
				if ($rule['port']) {
					$data .= " port ". $rule['port'];
				}
				if ($rule['natdest']) {
				  $data .= " -> ". stripslashes($rule['natdest']);
				  if ($rule['natdestport']) {
					  $data .= " port ". $rule['natdestport'];
				  }
				}

        if ($rule['netmask']) {
          $data .= " netmask ". $rule['netmask'];
        }
        if ($rule['random']) {
          $data .= " random";
        }
        if ($rule['source-hash']) {
          $data .= " source-hash";
        }
        if ($rule['static-port']) {
          $data .= " static-port";
        }

				if ($rule['comment']) {
					$data .= " # ". trim(stripslashes($rule['comment']));
				}

				$data .= "\n";
			}
		}
		return $data;
	}
}

?>