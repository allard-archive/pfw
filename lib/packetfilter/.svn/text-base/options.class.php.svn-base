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
 
class options extends rules 
{
	var $rules = array();

	function parse ($rule_string)
	{
		$data = array();
		if (strpos($rule_string, "#")) {
			$data['comment'] = substr($rule_string, strpos($rule_string, "#") + '1');
			$rule_string =  substr($rule_string, '0', strpos($rule_string, '#'));
		}

		/*
		 * Sanitize the rule string so that we can deal with '{foo' as '{ foo' in 
		 * the code further down without any special treatment
		 */
		$rule_string = preg_replace("/{/", " { ",   $rule_string);
		$rule_string = preg_replace("/}/", " } ",   $rule_string);
		$rule_string = preg_replace("/\(/", " \( ", $rule_string);
		$rule_string = preg_replace("/\)/", " \) ", $rule_string);
		$rule_string = preg_replace("/,/", " , ",   $rule_string);
		$rule_string = preg_replace("/\"/", " \" ", $rule_string);

		/*
		* Need to handle fingerprints differently since we're
		* expecting a dot (.) in the filename
		*/
		foreach (preg_split("/[\s,\t]+/", $rule_string) as $rule) {
			$rules[] = $rule;
		}

    // Currently, there's some weird thing going on where everything
    // works if there's parsing of this twice (see above) but things
    // break if either is removed.
		foreach (preg_split("/[\s,\t.]+/", $rule_string) as $rule) {
			$rules[] = $rule;
		}

		for ($i = '0'; $i < count($rules); $i++) {
			switch ($rules[$i]) {
			case "timeout":
				break;
			case "loginterface":
				$this->rules['loginterface'] = $rules[++$i];
				break;
			case "block-policy":
				$this->rules['block-policy'] = $rules[++$i];
				break;
			case "state-policy":
				$this->rules['state-policy'] = $rules[++$i];
				break;
			case "require-order":
				// This really does not makes sense for a web based interface
				// Instead, we'll bring order to chaos so don't parse this
				break;
			case "fingerprints":
				$this->rules['fingerprints'] = $rules['3'];
				break;
			case "optimization":
				$this->rules['optimization'] = $rules[++$i];
				break;
			case "debug":
				$this->rules['debug'] = $rules[++$i];
				break;
			case "tcp":
			case "udp":
			case "icmp":
			case "other":
				$this->rules[$rules[$i]][$rules[++$i]] = $rules[++$i];
				break;
			case "states":
				$this->rules['states'] = $rules[++$i];
				break;
			case "frags":
				$this->rules['frags'] = $rules[++$i];
				break;
			case "src-nodes":
				$this->rules['src-nodes'] = $rules[++$i];
				break;
			case "start":
				$this->rules['adaptive']['start'] = $rules[++$i];
				break;
			case "end":
				$this->rules['adaptive']['end'] = $rules[++$i];
				break;
			case "tables":
			  $this->rules['tables'] = $rules[++$i];
			  break;
			case "table-entries":
			  $this->rules['table_entries'] = $rules[++$i];
			  break;
			case "skip":
			  list($this->rules['skip'], $i) = $this->parseItem($rules, ++$i);
			  break;
			}
		}
	}

	function generate ()
	{
		$data = "";
		if ($this->rules) {
			if ($this->rules['comment']) {
				$lines = preg_split("/\n/", stripslashes($this->rules['comment']));
				$data .= "\n";
				foreach ($lines as $line) {
					$data .= "# ". $line. "\n";
				}
			}
			if (count($this->rules['tcp'])) {
				if (count($this->rules['tcp']) == '1') {
					list($key,$val) = each ($this->rules['tcp']);
					$data .= "set timeout tcp.$key $val\n";
				} else {
					$data .= "set timeout {";
					while (list($key,$val) = each ($this->rules['tcp'])) {
						$data .= " tcp.$key $val,";
					}
					$data = rtrim($data, ", ");
					$data .= " }\n";
				}
			}

			if (count($this->rules['udp'])) {
				if (count($this->rules['udp']) == '1') {
					list($key,$val) = each ($this->rules['udp']);
					$data .= "set timeout udp.$key $val\n";
				} else {
					$data .= "set timeout {";
					while (list($key,$val) = each ($this->rules['udp'])) {
						$data .= " udp.$key $val,";
					}
					$data = rtrim($data, ", ");
					$data .= " }\n";
				}
			}

			if (count($this->rules['icmp'])) {
				if (count($this->rules['icmp']) == '1') {
					list($key,$val) = each ($this->rules['icmp']);
					$data .= "set timeout icmp.$key $val\n";
				} else {
					$data .= "set timeout {";
					while (list($key,$val) = each ($this->rules['icmp'])) {
						$data .= " icmp.$key $val,";
					}
					$data = rtrim($data, ", ");
					$data .= " }\n";
				}
			}


			if (count($this->rules['other'])) {
				if (count($this->rules['other']) == '1') {
					list($key,$val) = each ($this->rules['other']);
					$data .= "set timeout other.$key $val\n";
				} else {
					$data .= "set timeout {";
					while (list($key,$val) = each ($this->rules['other'])) {
						$data .= " other.$key $val,";
					}
					$data = rtrim($data, ", ");
					$data .= " }\n";
				}
			}

			if ($this->rules['loginterface']) {
				$data .= "set loginterface ". $this->rules['loginterface']. "\n";
			}

			if ($this->rules['optimization']) {
				$data .= "set optimization ". $this->rules['optimization']. "\n";
			}

			if ($this->rules['block-policy']) {
				$data .= "set block-policy ". $this->rules['block-policy']. "\n";
			}

			if ($this->rules['state-policy']) {
				$data .= "set state-policy ". $this->rules['state-policy']. "\n";
			}

			if ($this->rules['debug']) {
				$data .= "set debug ". $this->rules['debug']. "\n";
			}

			if ($this->rules['fingerprints']) {
				$data .= "set fingerprints \"". preg_replace("/\"/", "", $this->rules['fingerprints']). "\"\n";
			}

			if ($this->rules['states']) {
				$data .= "set limit states ". $this->rules['states']. "\n";
			}

			if ($this->rules['frags']) {
				$data .= "set limit frags ". $this->rules['frags']. "\n";
			}

			if ($this->rules['src-nodes']) {
				$data .= "set limit src-nodes ". $this->rules['src-nodes']. "\n";
			}

			if ($this->rules['adaptive']['start']) {
				$data .= "set adaptive.start ". $this->rules['adaptive']['start']. "\n";
			}

			if ($this->rules['adaptive']['end']) {
				$data .= "set adaptive.end ". $this->rules['adaptive']['end']. "\n";
			}

			if ($this->rules['tables']) {
				$data .= "set limit tables ". $this->rules['tables']. "\n";
			}

			if ($this->rules['table_entries']) {
				$data .= "set limit table-entries ". $this->rules['table_entries']. "\n";
			}

      if ($this->rules['skip']) {
        if (!is_array($this->rules['skip'])) {
        	$data .= "set skip on ". $this->rules['skip']. "\n";
        } else {
        	$data .= "set skip on { ";
        	foreach ($this->rules['skip'] as $skip) {
        		$data .= $skip. " ";
        	}
        	$data = rtrim($data, " ,");
        	$data .= " }\n";
        }          
      }


			// This really does not makes sense for a web based interface
			//
			// if (isset($this->rules['require-order'])) {
			//	$data .= "set require-order "
			//	if ($this->rules['require-order']) {
			//		$data .= "yes\n";
			// } else {
			//		$data .= "no\n";
			// }
			//}		
			$data .= "\n";
		}
		return $data;
	}
	
	function addComment ($comment = false)
	{
		if ($comment) {
			if (isset($this->rules['comment'])) {
				$this->rules['comment'] .= "\n". $comment;
			} else {
				$this->rules['comment'] = $comment;
			}
		}
	}
}

?>