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

class altq extends rules
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
		$rule_string = preg_replace("/{/", " { ",   $rule_string);
		$rule_string = preg_replace("/}/", " } ",   $rule_string);
		$rule_string = preg_replace("/\(/", " \( ", $rule_string);
		$rule_string = preg_replace("/\)/", " \) ", $rule_string);
		$rule_string = preg_replace("/,/", " , ",   $rule_string);
		$rule_string = preg_replace("/\"/", " \" ", $rule_string);

		foreach (preg_split("/[\s,\t]+/", $rule_string) as $rule) {
			$rules[] = $rule;
		}
		for ($i = '1'; $i < count($rules); $i++) {
			switch ($rules[$i]) {
			case "on":
				$data['interface'] = $rules[++$i];
				break;
			case "bandwidth":
				$data['bandwidth'] = $rules[++$i];
				break;
			case "cbq":
			case "priq":
			case "hfsc":
				$data['queuetype'] = $rules[$i];
				break;
			case "qlimit":
				$data['qlimit'] = $rules[++$i];
				break;
			case "tbrsize":
				$data['tbrsize'] = $rules[++$i];
				break;
			case "queue":
				$i++;
				if ($rules[$i] != "{") {
					$data['queue'] = $rules[$i];
				} else {
					while (preg_replace("/[\s,]+/", "", $rules[++$i]) != "}") {
						$data['queue'][] = $rules[$i];
					}
				}
				break;
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
				$data .= "altq on ". stripslashes($rule['interface']);
				if ($rule['bandwidth']) {
					$data .= " bandwidth ". $rule['bandwidth'];
				}
				if ($rule['queuetype']) {
					$data .= " ". $rule['queuetype'];
				}
				if ($rule['qlimit']) {
					$data .= " qlimit ". $rule['qlimit'];
				}
				if ($rule['tbrsize']) {
					$data .= " tbrsize ". $rule['tbrsize'];
				}
				if ($rule['queue']) {
					if (!is_array($rule['queue'])) {
						$data .= " queue ". $rule['queue'];
					} else {
						$data .= " queue { ";
						foreach ($rule['queue'] as $queue) {
							$data .= $queue. ", ";
						}
						$data = rtrim($data, ", ");
						$data .= " }";
					}
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