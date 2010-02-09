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

/**
* pfw's parent rule class. All the pfw ruleset classes are extensions 
* of this class.
*/
class rules
{
	/**
	* The ruleset array which holds all the ruleparameters in an associative array.
	* @var array
	*/
	var $rules = array();

	/**
	* The class constructor function.
	*/
	function rules() {}

	/**
	* Moves a rule in a ruleset up.
	*
	* @param int $rulenumber The number of the rule to move up in the ruleset.
	* @return void
	*/
	function up ($rulenumber)
	{
		$rules = array();
		for ($i = '0'; $i < count($this->rules); $i++) {
			if ($i == ($rulenumber - '1')) {
				$rules[] = $this->rules[$i+'1'];
				$rules[] = $this->rules[$i];
			} else if ($i != $rulenumber) {
				$rules[] = $this->rules[$i];
			}
		}
		$this->rules = $rules;
	}

	/**
	* Moves a rule in a ruleset down.
	*
	* @param int $rulenumber The number of the rule to move down in the ruleset.
	* @return void
	*/
	function down ($rulenumber)
	{
		$rules = array();
		for ($i = '0'; $i < count($this->rules); $i++) {
			if ($i == $rulenumber) {
				$rules[] = $this->rules[$i+'1'];
				$rules[] = $this->rules[$i];
			} else if ($i != ($rulenumber + '1')) {
				$rules[] = $this->rules[$i];
			}
		}
		$this->rules = $rules;
	}

	/**
	* Deletes a rule in the ruleset
	*
	* @param int $rulenumber The number of the rule to delete
	* @return void
	*/
	function del ($rulenumber)
	{
		$rules = array();
		for ($i = '0'; $i < count($this->rules); $i++) {
			if ($i != $rulenumber) {
				$rules[] = $this->rules[$i];
			}
		}
		$this->rules = $rules;
	}

	/**
	* Adds a rule to the ruleset
	*
	* @param int $rulenumber The number in the rulebase where you want to insert the rule, if not specified, it will be the last rule.
	* @return int The number of the inserted rule in the ruleset
	*/
	function addRule ($rulenumber = false)
	{
		if (!$rulenumber || ($rulenumber > count($this->rules))) {
			$this->rules[] = array();
			return (count($this->rules) - '1');
		} else {
			for ($i = '0'; $i < count($this->rules); $i++) {
				if ($i == $rulenumber) {
					$_new_rulebase[] = array();
				}
				$_new_rulebase[] = $this->rules[$i];
			}
			$this->rules = $_new_rulebase;
			return $rulenumber;
		}
	}

	/**
	* Adds a comment-rule to the ruleset
	*
	* @param string $comment The comment to be added.
	* @return void
	*/
	function addComment ($comment = false)
	{
		if ($comment) {
			$_data['type'] = "comment";
			$_data['comment'] = $comment;
			$this->rules[] = $_data;
		}
	}

	/**
	* Adds a ruleset entity to a rule, used for things that can hold more than one thing.
	*
	* @param string $type What we are adding the entity to.
	* @param int $rulenumber The rulenumber to which we want to add the entity to.
	* @param string $data The data to add to the $type.
	* @return int The number of the inserted rule in the ruleset
	*/
	function addEntity ($type, $rulenumber, $data)
	{
		if (!isset($this->rules[$rulenumber][$type])) {
			$this->rules[$rulenumber][$type] = $data;
		} else if (!is_array($this->rules[$rulenumber][$type])) {
			$_temp = $this->rules[$rulenumber][$type];
			unset($this->rules[$rulenumber][$type]);
			$this->rules[$rulenumber][$type][] = $_temp;
			$this->rules[$rulenumber][$type][] = $data;
			$this->rules[$rulenumber][$type] = array_unique ($this->rules[$rulenumber][$type]);
		} else {
			$this->rules[$rulenumber][$type][] = $data;
			$this->rules[$rulenumber][$type] = array_unique ($this->rules[$rulenumber][$type]);
		}
	}

	/**
	* Deletes a ruleset entity from a rule, used for things that can hold more than one thing.
	*
	* @param string $type What we are deleting the entity from.
	* @param int $rulenumber The rulenumber to which we want to delete the entity from.
	* @param string $data The data to delete from the $type.
	* @return void
	*/
	function delEntity ($type, $rulenumber, $data)
	{
		if (is_array($this->rules[$rulenumber][$type])) {
			foreach ($this->rules[$rulenumber][$type] as $entity) {
				if ($entity != $data) {
					$_new_data[] = $entity;
				}
			}
			if (count($_new_data) == '1') {
				$this->rules[$rulenumber][$type] = $_new_data['0'];
			} else {
				$this->rules[$rulenumber][$type] = $_new_data;
			}
		} else {
			unset($this->rules[$rulenumber][$type]);
		}
	}

	/**
	* Deletes a ruleset entity from a rule, used for things that can hold more than one thing.
	*
	* @param array $rules The entire ruleset.
	* @param int $i The pointer to where in the ruleset the parsing is at.
	* @return array $data,$i The parsed string and the new pointer.
	*/
	function parseString ($rules, $i)
	{
	  $i++;
	  if ($rules[$i] == "\"") {
	    $data = $rules[++$i];
	    while ($rules[++$i] != "\"") {
	      $data .= " ". $rules[$i];
	    }
	  } else {
	    $data = $rules[$i];
	  }
	  return Array($data, $i);
	}

  function parseItem ($rules, $i, $delimiter_pre = "{", $delimiter_post = "}")
  {
    $i++;
    //
    // Check if the rule is negated (! { $item1, $item2})
    // If it is, replace with ({ !$item1, !$item2})
    //
    if ($rules[$i] == "!") {
		  $ii = ++$i; // Need a new iterator for this loop
  		while (preg_replace("/[\s,]+/", "", $rules[++$ii]) != $delimiter_post) {
  			if (($rules[$ii] != $delimiter_pre) and ($rules[$ii] != $delimiter_post)) {
  			  if ($rules[$ii]{0} == "!") {
  			    //Delete Double negations
  			    $rules[$ii] = substr($rules[$ii], 1);
  			  } else {
  			    $rules[$ii] = "!". $rules[$ii];
  			  }
  	  	}
  		}
  	}
		if (($rules[$i] == $delimiter_pre)) {
		  while (preg_replace("/[\s,]+/", "", $rules[++$i]) != $delimiter_post) {
		  	if (($rules[$i] != $delimiter_pre) and ($rules[$i] != $delimiter_post)) {
          if($rules[$i] == "\("){
              while($rules[++$i] != "\)"){
                  $data2[] = $rules[$i];
              }
              $data[] = "(" . join(" ", $data2) . ")";
              unset($data2);                     
          }
          else{                  
              $data[] = $rules[$i];
          }
		  	}
		  }
		} else {
		  //
		  // Check if we have for instance ($ext_if) that by now has been
		  // translated to \( $ext_if \)
		  //
		  if ($rules[$i] == "\(") {
        while($rules[++$i] != "\)"){
            $data2[] = $rules[$i];
        }
        $data[] = "(" . join(" ", $data2) . ")";
        unset($data2);
		  } else {
		    $data = $rules[$i];
		  }
		}
		return Array($data, $i);
  }

  function parsePortItem ($rules, $i)
  {
    $i++;
    //
    // Check if the rule is negated (! { $item1, $item2})
    // If it is, replace with ({ !$item1, !$item2})
    //
    if ($rules[$i] == "!") {
      $ii = ++$i; // Need a new iterator for this loop
    	while (preg_replace("/[\s,]+/", "", $rules[++$ii]) != "}") {
    		if (($rules[$ii] != "{") and ($rules[$ii] != "}")) {
    		  if ($rules[$ii]{0} == "!") {
    		    //Delete Double negations
    		    $rules[$ii] = substr($rules[$ii], 1);
    		  } else {
    		    $rules[$ii] = "!". $rules[$ii];
    		  }
      	}
    	}
    }

    if ($rules[$i] == "{") {
      while (preg_replace("/[\s,]+/", "", $rules[++$i]) != "}") {
      	$rules[$i] = preg_replace("/[\s,]+/", "", $rules[$i]);
      	switch ($rules[$i]) {
      	case "}":
      		break;
      	case "=":
      		$data[] = $rules[++$i];
      		break;
      	case "!=":
      	case "<":
      	case "<=":
      	case ">":
      	case ">=":
      		$data[] = $rules[$i]. " ". $rules[++$i];
      		break;
      	default:
      		switch (preg_replace("/[\s,]+/", "", $rules[$i+1])) {
      		case "<>":
      		case "><":
      		case ":":
      			$data[] = $rules[$i]. " ". $rules[++$i]. " ". $rules[++$i];
      			break;
      		default:
      			$data[] = $rules[$i];
      			break;
      		}
      		break;
      	}
      }
    } else {
      switch ($rules[$i]) {
      case "=":
      	$data = $rules[++$i];
      	break;
      case "!=":
      case "<":
      case "<=":
      case ">":
      case ">=":
      	$data = $rules[$i]. " ". $rules[++$i];
      	break;
      default:
      	switch (preg_replace("/[\s,]+/", "", $rules[$i+1])) {
      	case "<>":
      	case "><":
      	case ":":
      		$data = $rules[$i]. " ". $rules[++$i]. " ". $rules[++$i];
      		break;
      	default:
      		$data = $rules[$i];
      		break;
      	}
      	break;
    	}
    }
    return Array($data, $i);
  }

	/**
	* Generates output from either an array of items or a single item into a joined output string.
	*
	* @param array $items The item or items that should be generated
	* @param string $heading The heading for this particular item list
	* @return string $data The parsed output with the rulestring.
	*/
	function generateItem ($items, $heading)
	{
    if (is_array($items)) {
      return " ". trim($heading). " { ". join($items, " "). " }";
    } else {
      return " ". trim($heading). " ". $items;
    }
	}

}

?>