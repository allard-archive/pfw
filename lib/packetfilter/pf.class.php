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

class pf {

	var $filter;
	var $nat;
	var $macro;
	var $options;
	var $scrub;
	var $altq;
	var $queue;
	var $table;
	var $anchor;

	function pf ()
	{
		$this->filter =   new filter();
		$this->nat = 	    new nat();
		$this->macro = 	  new macro();
		$this->scrub =	  new scrub();
		$this->table =	  new table();
		$this->altq =	    new altq();
		$this->queue =	  new queue();
		$this->options =  new options();
		$this->anchor =   new anchor();
	}

	function read_from_file ($filename)
	{
		$this->parseRulebase(file_get_contents ($filename));
	}

	function parseRulebase ($rulebase_string)
	{
		if (preg_match("/pf ruleset generated at/", $rulebase_string)) {
			$rulebase_string = substr($rulebase_string, strpos($rulebase_string, "#\n\n") + '3');
		}
		$rulebase_string = preg_replace  ("/\n#/", "\n# ", $rulebase_string);
		$rulebase_string = str_replace ("\\\n", "", $rulebase_string);
		foreach (preg_split("/\n/", $rulebase_string, '-1', PREG_SPLIT_NO_EMPTY) as $rule) {
			$this->rulebase[] = trim($rule);
		}
		foreach ($this->rulebase as $rule) {
			$keywords = preg_split("/[\s,\t]+/", $rule, '-1', PREG_SPLIT_NO_EMPTY);
			switch ($keywords['0']) {
			case "":
				break;
			case "#":
				if (!isset($_comment)) {
					$_comment = trim(substr($rule, '1'));
				} else {
					$_comment .= "\n". trim(substr($rule, '1'));
				}
				break;
			case "anchor":
			case "pass":
			case "block":
			case "antispoof":
				$this->filter->addComment($_comment);
				unset($_comment);
				$this->filter->rules[] = $this->filter->parse($rule);
				break;
			case "no":
			case "rdr":
			case "nat":
			case "binat":
			case "nat-anchor":
			case "rdr-anchor":
			case "binat-anchor":
				if (isset($_comment)) {
					$data['type'] = "comment";
					$data['comment'] = $_comment;
					$this->nat->rules[] = $data;
					unset ($_comment);
				}
				$this->nat->rules[] = $this->nat->parse($rule);
				break;
			case "set":
				$this->options->addComment($_comment);
				unset ($_comment);
				$this->options->parse($rule);
				break;
			case "scrub":
				if (isset($_comment)) {
					$data['type'] = "comment";
					$data['comment'] = $_comment;
					$this->scrub->rules[] = $data;
					unset ($_comment);
				}
				$this->scrub->rules[] = $this->scrub->parse($rule);
				unset ($_comment);
				break;
			case "table":
				if (isset($_comment)) {
					$data['type'] = "comment";
					$data['comment'] = $_comment;
					$this->table->rules[] = $data;
					unset ($_comment);
				}
				$this->table->rules[] = $this->table->parse($rule);
				unset ($_comment);
				break;
			case "altq":
				if (isset($_comment)) {
					$data['type'] = "comment";
					$data['comment'] = $_comment;
					$this->altq->rules[] = $data;
					unset ($_comment);
				}
				$this->altq->rules[] = $this->altq->parse($rule);
				break;
			case "queue":
				if (isset($_comment)) {
					$data['type'] = "comment";
					$data['comment'] = $_comment;
					$this->queue->rules[] = $data;
					unset ($_comment);
				}
				$this->queue->rules[] = $this->queue->parse($rule);
				break;
			case "load":
  			if (isset($_comment)) {
  				$data['type'] = "comment";
  				$data['comment'] = $_comment;
  				$this->anchor->rules[] = $data;
  				unset ($_comment);
  			}
  			$this->anchor->rules[] = $this->anchor->parse($rule);
  			break;
			default:
				if (isset($_comment)) {
					$data['type'] = "comment";
					$data['comment'] = $_comment;
					$this->macro->rules[] = $data;
					unset ($_comment);
				}
				$this->macro->rules[] = $this->macro->parse($rule);
				break;
			}
		}
		$this->rulebase = $rules;
	}


	function generate ($lines = false, $generate_header = true)
	{
	  if ($generate_header) {
		  $data = "#\n# pf ruleset generated at ". date("Y-m-d H:m:s"). "\n#\n# ruleset automatically generated by pfw\n#\n\n";
		}

		$data .= $this->macro->generate();
		$data .= $this->table->generate();
		$data .= $this->options->generate();
		$data .= $this->scrub->generate();
		$data .= $this->altq->generate();
		$data .= $this->queue->generate();
		$data .= $this->nat->generate();
		$data .= $this->filter->generate();
		$data .= $this->anchor->generate();

    if ($generate_header) {
		  $data .= "\n# End of Ruleset\n";
		}
		if ($lines) {
			$linenumber='1';
			foreach (explode("\n", $data) as $line) {
				$_data .= $linenumber++. ": $line\n";
			}
			$data = $_data;
		}
		return $data;
	}

	function writeFile ($filename)
	{
		if (!$handle = fopen($filename, 'w')) {
			print "Cannot open file: $filename";
			exit;
		}
		
		// Write $somecontent to our opened file.
		if (fwrite($handle, $this->generate()) === FALSE) {
			print "Cannot write to file ($filename)";
			exit;
		}
		
		fclose ($handle);
	}

	function printRules ()
	{
		print_r ($this->rulebase);
	}
}
?>