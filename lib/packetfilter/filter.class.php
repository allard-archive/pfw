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
**/

class filter extends rules
{
	function parse ($rule_string)
	{
		$data = array();
		if (strpos($rule_string, "#")) {
			$data['comment'] = substr($rule_string, strpos($rule_string, "#") + '1');
			$rule_string =  substr($rule_string, 0, strpos($rule_string, "#"));
		}

		/*
		 * Sanitize the rule string so that we can deal with '{foo' as '{ foo' in 
		 * the code further down without any special treatment
		 */
		$rule_string = preg_replace("/! +/", "!",   $rule_string);
		$rule_string = preg_replace("/{/", " { ",   $rule_string);
		$rule_string = preg_replace("/}/", " } ",   $rule_string);
		$rule_string = preg_replace("/\(/", " \( ", $rule_string);
		$rule_string = preg_replace("/\)/", " \) ", $rule_string);
		$rule_string = preg_replace("/,/", " , ",   $rule_string);
		$rule_string = preg_replace("/\"/", " \" ", $rule_string);

		foreach (preg_split("/[\s,\t]+/", $rule_string, '-1', PREG_SPLIT_NO_EMPTY) as $rule) {
			$rules[] = $rule;
		}
		for ($i = '0'; $i < count($rules); $i++) {
			switch ($rules[$i]) {
			case "anchor":
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
			case "antispoof":
			case "pass":
			case "block":
				$data['type'] = $rules[$i];
				break;
			case "quick":
				$data['quick'] = true;
				break;
			case "inet":
			case "inet6":
				$data['family'] = $rules[$i];
				break;
			case "in":
				$data['direction'] = "in";
				break;
			case "out":
				$data['direction'] = "out";
				break;
			case "log":
			case "log-all":
				$data['log'] = $rules[$i];
				break;
			case "all":
				$data['all'] = true;
				break;
			case "allow-opts":
				$data['allow-opts'] = true;
				break;
			case "drop":
			  $data['blockoption'] = "drop";
			  break;
		  case "return":
		    $data['blockoption'] = "return";
		    break;
	    case "return-rst":
	      $data['blockoption'] = "return-rst";
	      break;
      case "return-icmp":
        $data['blockoption'] = "return-icmp";
        break;
      case "return-icmp6":
        $data['blockoption'] = "return-icmp6";
        break;
      case "fastroute":
        $data['fastroute'] = true;
        break;
      case "route-to":
        list($data['route-to'], $i) = $this->parseItem($rules, $i);
        break;
      case "reply-to":
        list($data['reply-to'], $i) = $this->parseItem($rules, $i);
        break;
      case "dup-to":
        list($data['dup-to'], $i) = $this->parseItem($rules, $i);
        break;
			case "icmp-type":
			  list($data['icmp-type'], $i) = $this->parseItem($rules, $i);
				if ($rules[$i+'1'] == "code") {
          list($data['icmp-code'], $i) = $this->parseItem($rules, ++$i);
				}
				break;
			case "icmp6-type":
  			list($data['icmp6-type'], $i) = $this->parseItem($rules, $i);
				if ($rules[$i+'1'] == "code") {
				  list($data['icmp6-code'], $i) = $this->parseItem($rules, ++$i);
				}
				break;
			case "for":
			case "on":
			  list($data['interface'], $i) = $this->parseItem($rules, $i);
				break;
			case "proto":
			  list($data['proto'], $i) = $this->parseItem($rules, $i);
				break;
			case "any":
				if (!isset($data['from'])) {
					$data['from'] = "any";
				} else {
					$data['to'] = "any";
				}
				break;
			case "from":
			  list($data['from'], $i) = $this->parseItem($rules, $i);
				if ($rules[$i+1] == "port") {
          list($data['fromport'], $i) = $this->parsePortItem($rules, ++$i);
				}
				break;
			case "to":
			  list($data['to'], $i) = $this->parseItem($rules, $i);
				break;
			case "port":
  			list($data['port'], $i) = $this->parsePortItem($rules, $i);
				break;
			case "flags":
				$i++;
				$data['flags'] = $rules[$i];
				break;
			case "keep":
				$i++;
				$data['state'] = "keep";
				break;
			case "modulate":
				$i++;
				$data['state'] = "modulate";
				break;
			case "synproxy":
				$i++;
				$data['state'] = "synproxy";
				break;
			case "user":
			  list($data['user'], $i) = $this->parseItem($rules, $i);
				break;
			case "group":
        list($data['group'], $i) = $this->parseItem($rules, $i);
				break;
			case "label":
				list ($data['label'], $i) = $this->parseString($rules, $i);
				break;
			case "queue":
  			list($data['queue'], $i) = $this->parseItem($rules, $i, "\(", "\)");
        break;
			case "tag":
				list ($data['tag'], $i) = $this->parseString($rules, $i);
				break;
			case "tagged":
				list ($data['tagged'], $i) = $this->parseString($rules, $i);
				break;
			case "os":
  			$i++;
  			unset($_data);
  			if ($rules[$i] != "{") {
  			  if ($rules[$i] != "\"") {
  			    $_data .= $rules[$i++];
  			  } else {
  				  while ($rules[++$i] != "\"") {
  					  $_data .= " ". $rules[$i];
  				  }
  				}
  				$data['os'] = trim($_data);
  			} else {
  				while (preg_replace("/[\s,]+/", "", $rules[++$i]) != "}") {
  					$_data = "";
  					while ($rules[++$i] != "\"") {
  						$_data .= " ". $rules[$i];
  					}
  					$data['os'][] = trim($_data);
  				}
  			}
  			break;
			case "probability":
			  $data['probability'] = preg_replace("/\"/", "", $rules[++$i]);
			  break;
			default:
				$data[] = $rules[$i];
				;;
			}
		}
		return $data;
	}

	function generate()
	{
		$data = "\n";
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
				if ($rule['type'] == "anchor") {
				  $data .= " \"". $rule['identifier']. "\"";
				}
				if ($rule['blockoption']) {
				  $data .= " ". $rule['blockoption'];
				}
				if ($rule['direction']) {
					$data .= " ". $rule['direction'];
				}
				if ($rule['log']) {
					$data .= " ". $rule['log'];
				}
				if ($rule['quick']) {
					$data .= " quick";
				}
				if ($rule['interface']) {
					if ($rule['type'] == "antispoof") {
						$data .= $this->generateItem($rule['interface'], "for");
					} else {
            $data .= $this->generateItem($rule['interface'], "on");
					}
				}
        if ($rule['fastroute']) {
          $data .= " fastroute";
        }
        if ($rule['route-to']) {
          $data .= $this->generateItem($rule['route-to'], "route-to");
        }
        if ($rule['reply-to']) {
          $data .= $this->generateItem($rule['reply-to'], "reply-to");
        }
        if ($rule['dup-to']) {
          $data .= $this->generateItem($rule['dup-to'], "dup-to");
        }
        if ($rule['family']) {
					$data .= " ". $rule['family'];
				}
				if ($rule['proto']) {
          $data .= $this->generateItem($rule['proto'], "proto");
				}
				if ($rule['all']) {
					$data .= " all";
				} else {
					if ($rule['from']) {
					  $data .= $this->generateItem($rule['from'], "from");
					} else if ($rule['type'] != "antispoof" && $rule['type'] != "anchor") {
						$data .= " from any";
					}
					if ($rule['fromport']) {
					  $data .= $this->generateItem($rule['fromport'], "port");
					}

					if ($rule['os']) {
						if (!is_array($rule['os'])) {
							$data .= " os \"". $rule['os']. "\"";
						} else {
							$data .= " os { \"". join($rule['os'], "\" \""). "\" }";
						}
					}
					
					if ($rule['to']) {
					  $data .= $this->generateItem($rule['to'], "to");
					} else if ($rule['type'] != "antispoof" && $rule['type'] != "anchor") {
						$data .= " to any";
					}
					if ($rule['port']) {
					  $data .= $this->generateItem($rule['port'], "port");
					}
				}
				if ($rule['icmp-type'] && ($rule['proto'] == 'icmp') && ($rule['family'] <> "inet6")) {
				  $data .= $this->generateItem($rule['icmp-type'], "icmp-type");
					if (isset($rule['icmp-code'])) {
  				  $data .= $this->generateItem($rule['icmp-code'], "code");
					}
				}
				if ($rule['icmp6-type'] && ($rule['proto'] == 'icmp6') && ($rule['family'] == "inet6")) {
				  $data .= $this->generateItem($rule['icmp6-type'], "icmp6-type");
					if (isset($rule['icmp6-code'])) {
  				  $data .= $this->generateItem($rule['icmp6-code'], "code");
					}
				}
				if ($rule['allow-opts']) {
					$data .= " allow-opts";
				}
				if ($rule['flags']) {
					$data .= " flags ". $rule['flags'];
				}
				if ($rule['state']) {
					$data .= " ". $rule['state']. " state";
				}

				if ($rule['user']) {
				  $data .= $this->generateItem($rule['user'], "user");
				}

				if ($rule['group']) {
				  $data .= $this->generateItem($rule['group'], "group");
				}

				if ($rule['label']) {
					$data .= " label \"". $rule['label']. "\"";
				}

				if ($rule['tag']) {
					$data .= " tag \"". $rule['tag']. "\"";
				}

				if ($rule['tagged']) {
					$data .= " tagged \"". $rule['tagged']. "\"";
				}

				if ($rule['queue']) {
					if (!is_array($rule['queue'])) {
						$data .= " queue ". $rule['queue'];
					} else {
						$data .= " queue (". $rule['queue']['0']. ", ". $rule['queue']['1']. ")";
					}
				}

        if ($rule['probability']) {
          $data .= " probability ". $rule['probability'];
        }

				if ($rule['comment']) {
					$data .= " # ". trim(stripslashes($rule['comment']));
				}

				$data .= "\n";
			}
		}
		return $data;
	}

  function parseAnchor ($rule_string)
  {
    $data = array();

    foreach (preg_split("/[\s,\t]+/", $rule_string, '-1', PREG_SPLIT_NO_EMPTY) as $rule) {
    	$rules[] = $rule;
    }
    $data['type'] = "anchor";
    $data['identifier'] = $rules[1];
    return $data;
  }

  function generateAnchor ($rule_array)
  {
    $data = "anchor ". $rule_array['identifier'];
		return $data. "\n";
  }

}

?>
