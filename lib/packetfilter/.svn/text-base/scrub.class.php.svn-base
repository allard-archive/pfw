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
 
class scrub extends rules
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
		$rule_string = preg_replace("/{/", " { ", $rule_string);
		$rule_string = preg_replace("/}/", " } ", $rule_string);
		$rule_string = preg_replace("/,/", " , ", $rule_string);

		foreach (preg_split("/[\s,\t]+/", $rule_string, '-1', PREG_SPLIT_NO_EMPTY) as $rule) {
			$rules[] = trim($rule);
		}
		for ($i = '0'; $i < count($rules); $i++) {
			switch ($rules[$i]) {
			case "in":
			case "out":
				$data['direction'] = $rules[$i];
				break;
			case "all":
				$data['all'] = true;
				break;
			case "on":
				$i++;
				if ($rules[$i] != "{") {
					$data['interface'] = $rules[$i];
				} else {
					while (preg_replace("/[\s,]+/", "", $rules[++$i]) != "}") {
						$data['interface'][] = $rules[$i];
					}
				}
				break;
			case "from":
				$i++;
				if ($rules[$i] != "{") {
					$data['from'] = $rules[$i];
				} else {
					while (preg_replace("/[\s,]+/", "", $rules[++$i]) != "}") {
						$data['from'][] = $rules[$i];
					}
				}
				break;
			case "to":
				$i++;
				if ($rules[$i] != "{") {
					$data['to'] = $rules[$i];
				} else {
					while (preg_replace("/[\s,]+/", "", $rules[++$i]) != "}") {
						$data['to'][] = $rules[$i];
					}
				}
				break;
			case "no-df":
				$data['no-df'] = true;
				break;
			case "min-ttl":
				$i++;
				$data['min-ttl'] = $rules[$i];
				break;
			case "max-mss":
				$i++;
				$data['max-mss'] = $rules[$i];
				break;
			case "random-id":
				$data['random-id'] = true;
				break;
			case "fragment":
				$i++;
				$data['fragment'] = $rules[$i];
				break;
			case "reassemble":
				$i++;
				$data['reassemble'] = $rules[$i];
				break;
			}
		}
		return $data;
	}

	function generate ()
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
				$data .= "scrub";
				if ($rule['direction']) {
					$data .= " ". $rule['direction'];
				}
				if ($rule['interface']) {
					if (!is_array($rule['interface'])) {
						$data .= " on ". stripslashes($rule['interface']);
					} else {
						$data .= " on { ";
						foreach ($rule['interface'] as $interface) {
							$data .= stripslashes($interface). ", ";
						}
						$data = rtrim($data, " ,");
						$data .= " }";
					}
				}
				if ($rule['all']) {
					$data .= " all";
				} else {
					if ($rule['from']) {
						if (!is_array($rule['from'])) {
							$data .= " from ". $rule['from'];
						} else {
							$data .= " from { ";
							foreach ($rule['from'] as $from) {
								$data .= $from .", ";
							}
							$data = rtrim ($data, " ,");
							$data .= " }";
						}
					} else {
						$data .= " from any";
					}
					if ($rule['to']) {
						if (!is_array($rule['to'])) {
							$data .= " to ". stripslashes($rule['to']);
						} else {
							$data .= " to { ";
							foreach ($rule['to'] as $to) {
								$data .= stripslashes($to). ", ";
							}
							$data = rtrim ($data, ", ");
							$data .= " }";
						}
					} else {
						$data .= " to any";
					}
				}
				if ($rule['no-df']) {
					$data .= " no-df";
				}
				if ($rule['min-ttl']) {
					$data .= " min-ttl ". $rule['min-ttl'];
				}
				if ($rule['max-mss']) {
					$data .= " max-mss ". $rule['max-mss'];
				}
				if ($rule['random-id']) {
					$data .= " random-id";
				}
				if ($rule['fragment']) {
					$data .= " fragment ". $rule['fragment'];
				}
				if ($rule['reassemble']) {
					$data .= " reassemble ". $rule['reassemble'];
				}
				
				if ($rule['comment']) {
					$data .= " # ". trim(stripslashes($rule['comment']));
				}

				$data .= "\n";
			}
			$data .= "\n";
		}
		return $data;
	}
}

?>