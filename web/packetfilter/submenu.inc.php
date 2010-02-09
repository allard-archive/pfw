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
?>

<div id="submenu">

  <ul class="links" style="margin-top: 0em; float: right;">
    <li><a href="test.php">Test Ruleset</a></li>
    <li><a href="install.php">Install</a></li>
    <li><a href="write.php">Display Ruleset</a></li>
    <li><a href="files.php">Load &amp; Save</a></li>
  </ul>
  
  <ul class="links">
  	<li<?php print  ($active == 'options' ? " class=\"active\"" : "") ?>><a href="options.php">Options</a></li>
  	<li<?php print  ($active == 'macro' ? " class=\"active\"" : "") ?>><a href="macro.php">Macro</a></li>
  	<li<?php print  ($active == 'table' ? " class=\"active\"" : "") ?>><a href="table.php">Tables</a></li>
  	<li<?php print  ($active == 'scrub' ? " class=\"active\"" : "") ?>><a href="scrub.php">Scrub</a></li>
  	<li<?php print  ($active == 'queue' ? " class=\"active\"" : "") ?>><a href="queue.php">Queue</a></li>
  	<li<?php print  ($active == 'nat' ? " class=\"active\"" : "") ?>><a href="nat.php">Nat</a></li>
  	<li<?php print  ($active == 'filter' ? " class=\"active\"" : "") ?>><a href="filter.php">Filter</a></li>
  </ul>

</div>