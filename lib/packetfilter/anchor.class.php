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
 
class anchor extends rules
{
	function parse ($rule_string)
	{
		$data = array();
		if (strpos($rule_string, "#")) {
			$data['comment'] = substr($rule_string, strpos($rule_string, "#") + '1');
			$rule_string =  substr($rule_string, '0', strpos($rule_string, "#"));
		}

		foreach (preg_split("/[\s,\t]+/", $rule_string, '-1', PREG_SPLIT_NO_EMPTY) as $rule) {
			$rules[] = preg_replace("/\"/", "", $rule);
		}
		
		$data['anchor'] = $rules['1'];
		$data['filename'] = $rules['3'];
		return $data;
	}

	function generate ()
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
				$data .= "load anchor ". $rule['anchor']. " from ". $rule['filename'];

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