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

class OptionsTest extends UnitTestCase {

  function OptionsTest() {
    $this->UnitTestCase();
  }

  //
  // Basic Testing of Nat, rdr and binat statements
  //
  function test_tcp_timeouts() {
    $pf = new pf();
    $pf->parseRulebase('set timeout { tcp.first 120, tcp.opening 30, tcp.established 86400, tcp.closing 900, tcp.finwait 45, tcp.closed 90 }');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout { tcp.first 120, tcp.opening 30, tcp.established 86400, tcp.closing 900, tcp.finwait 45, tcp.closed 90 }');
  }

  function test_udp_timeouts() {
    $pf = new pf();
    $pf->parseRulebase('set timeout { udp.first 60, udp.single 30, udp.multiple 60 }');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout { udp.first 60, udp.single 30, udp.multiple 60 }');
  }

  function test_icmp_timeouts() {
    $pf = new pf();
    $pf->parseRulebase('set timeout { icmp.first 20, icmp.error 10 }');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout { icmp.first 20, icmp.error 10 }');
  }

  function test_other_timeouts() {
    $pf = new pf();
    $pf->parseRulebase('set timeout { other.first 60, other.single 30, other.multiple 60 }');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout { other.first 60, other.single 30, other.multiple 60 }');
  }

  function test_adaptive_start() {
    $pf = new pf();
    $pf->parseRulebase('set timeout adaptive.start 10000');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout adaptive.start 10000');
  }

  function test_adaptive_end() {
    $pf = new pf();
    $pf->parseRulebase('set timeout adaptive.end 10000');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout adaptive.end 10000');
  }

  function test_timeout_interval() {
    $pf = new pf();
    $pf->parseRulebase('set timeout interval 10');
    $this->assertEqual(trim($pf->generate(false, false)), 'set timeout interval 10');
  }

  function test_loginterface() {
    $pf = new pf();
    $pf->parseRulebase('set loginterface fxp0');
    $this->assertEqual(trim($pf->generate(false, false)), 'set loginterface fxp0');
  }

  function test_optimization() {
    $pf = new pf();
    $pf->parseRulebase('set optimization normal');
    $this->assertEqual(trim($pf->generate(false, false)), 'set optimization normal');
  }

  function test_blockpolicy() {
    $pf = new pf();
    $pf->parseRulebase('set block-policy drop');
    $this->assertEqual(trim($pf->generate(false, false)), 'set block-policy drop');
  }

  function test_limitstates() {
    $pf = new pf();
    $pf->parseRulebase('set limit states 10000');
    $this->assertEqual(trim($pf->generate(false, false)), 'set limit states 10000');
  }

  function test_limitfrags() {
    $pf = new pf();
    $pf->parseRulebase('set limit frags 10000');
    $this->assertEqual(trim($pf->generate(false, false)), 'set limit frags 10000');
  }

  function test_tables_statements() {
    $pf = new pf();
    $pf->parseRulebase('set limit tables 10');
    $this->assertEqual(trim($pf->generate(false, false)), 'set limit tables 10');
  }

  function test_table_entries_statements() {
    $pf = new pf();
    $pf->parseRulebase('set fingerprints "/etc/pf.os"');
    $this->assertEqual(trim($pf->generate(false, false)), 'set fingerprints "/etc/pf.os"');
  }

  function test_set_skip() {
    $pf = new pf();
    $pf->parseRulebase('set skip on lo0');
    $this->assertEqual(trim($pf->generate(false, false)), 'set skip on lo0');
  }

  function test_set_skip_multi() {
    $pf = new pf();
    $pf->parseRulebase('set skip on { lo0, enc0 }');
    $this->assertEqual(trim($pf->generate(false, false)), 'set skip on { lo0 enc0 }');
  }

}

$test = &new OptionsTest();
$test->run(new TextReporter());

?>
