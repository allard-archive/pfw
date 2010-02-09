<?php
/*
 * Copyright (c) 2004 Allard Consulting.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * 3. All advertising materials mentioning features or use of this
 *    software must display the following acknowledgement: This
 *    product includes software developed by Allard Consulting
 *    and its contributors.
 *
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

$inst_dir = dirname(__FILE__). "/../../../";
require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');
require_once('../../../include/packetfilter.inc.php');

error_reporting(E_ERROR | E_PARSE);

class NatTest extends UnitTestCase {

  function NatTest() {
    $this->UnitTestCase();
  }

  //
  // Basic Testing of Nat, rdr and binat statements
  //
  function test_basic_statements() {
    $pf = new pf();
    $pf->parseRulebase('nat on $external_if from $internal to any -> $int_if');
    $this->assertEqual(trim($pf->generate(false, false)), 'nat on $external_if from $internal to any -> $int_if');

    $pf = new pf();
    $pf->parseRulebase('rdr on $external_if from $internal to any -> $int_if');
    $this->assertEqual(trim($pf->generate(false, false)), 'rdr on $external_if from $internal to any -> $int_if');

    $pf = new pf();
    $pf->parseRulebase('binat on $external_if from $internal to any -> $int_if');
    $this->assertEqual(trim($pf->generate(false, false)), 'binat on $external_if from $internal to any -> $int_if');
  }

  //
  // Test Nonat
  //
  function test_nonat_statements() {
    $pf = new pf();
    $pf->parseRulebase('no nat on $external_if from $internal to any -> $int_if');
    $this->assertEqual(trim($pf->generate(false, false)), 'no nat on $external_if from $internal to any -> $int_if');    
  }

  //
  // Multiple interfaces
  //
  function test_nat_from_multiple_interfaces() {
    $pf = new pf();
    $pf->parseRulebase('nat on {$ext_if $int_if} from $internal to any -> $int_if');
    $this->assertEqual(trim($pf->generate(false, false)), 'nat on { $ext_if $int_if } from $internal to any -> $int_if');    
  }

  function test_dynamic_interfaces() {
    $pf = new pf();
    $pf->parseRulebase('nat on $external_if from any to any -> ($int_if)');
    $this->assertEqual(trim($pf->generate(false, false)), 'nat on $external_if from any to any -> ($int_if)');    
  }

  //
  // rdr pass
  //
  function test_rdr_pass() {
    $pf = new pf();
    $pf->parseRulebase('rdr pass on $external_if from $internal to any -> $int_if');
    $this->assertEqual(trim($pf->generate(false, false)), 'rdr pass on $external_if from $internal to any -> $int_if');    
  }

  //
  // Multiple protocols
  //
  function test_multiple_protocols() {
    $pf = new pf();
    $pf->parseRulebase('nat on em0 proto {tcp udp icmp} from 10.10.20.0/24 to any -> 1.2.3.4');
    $this->assertEqual(trim($pf->generate(false, false)), 'nat on em0 proto { tcp udp icmp } from 10.10.20.0/24 to any -> 1.2.3.4');    
  }

  //
  // Anchors
  //
  function test_anchors() {
    $pf = new pf();
    $pf->parseRulebase('nat-anchor "authpf/*"');
    $this->assertEqual(trim($pf->generate(false, false)), 'nat-anchor "authpf/*"');    

    $pf = new pf();
    $pf->parseRulebase('rdr-anchor "authpf/*"');
    $this->assertEqual(trim($pf->generate(false, false)), 'rdr-anchor "authpf/*"');    

    $pf = new pf();
    $pf->parseRulebase('rdr-anchor "authpf/*"');
    $this->assertEqual(trim($pf->generate(false, false)), 'rdr-anchor "authpf/*"');    
  }

}
    
$test = &new NatTest();
$test->run(new TextReporter());

?>
