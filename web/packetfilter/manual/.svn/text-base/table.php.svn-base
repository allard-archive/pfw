<table class="manual" id="basic">
  <caption>tables</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      <p>Tables are named structures which can hold a collection of addresses and
         networks. Lookups against tables in pf(4) are relatively fast, making a
         single rule with tables much more efficient, in terms of processor usage
         and memory consumption, than a large number of rules which differ only in
         IP address (either created explicitly or automatically by rule expansion).</p>

      <p>Tables can be used as the source or destination of filter rules, scrub
         rules or translation rules such as nat or rdr (see below for details on
         the various rule types).  Tables can also be used for the redirect address 
         of nat and rdr rules and in the routing options of filter rules,
         but only for round-robin pools.</p>

      <p>Tables can be defined with any of the following pfctl(8) mechanisms.  As
         with macros, reserved words may not be used as table names.</p>
    </td>
  </tr>
  <tr>
    <td>manually</td>
    <td>Persistent tables can be manually created with the add or
        replace option of pfctl(8), before or after the ruleset has
        been loaded.</td>
  </tr>
  <tr>
    <td>pf.conf</td>
    <td>Table definitions can be placed directly in this file, and
        loaded at the same time as other rules are loaded, atomically.
        Table definitions inside pf.conf use the table statement, and
        are especially useful to define non-persistent tables.  The
        contents of a pre-existing table defined without a list of addresses 
        to initialize it is not altered when pf.conf is loaded.
        A table initialized with the empty list, { }, will be cleared
        on load.</td>
  </tr>
  <tr>
    <td colspan="2">
      <p>Tables may be defined with the following two attributes:</p>
      
      <table>
        <tr>
          <td class="key">persist</td>
          <td>The persist flag forces the kernel to keep the table even when
              no rules refer to it.  If the flag is not set, the kernel will
              automatically remove the table when the last rule referring to
              it is flushed.
          </td>
        </tr>
        <tr>
          <td class="key">const</td>
          <td>The const flag prevents the user from altering the contents of
              the table once it has been created.  Without that flag, pfctl(8)
              can be used to add or remove addresses from the table at any
              time, even when running with securelevel(7) = 2.</td>
        </tr>
      </table>

      <p>For example,</p>

<pre>
table &lt;private&gt; const { 10/8, 172.16/12, 192.168/16 }
table &lt;badhosts&gt; persist
block on fxp0 from { &lt;private&gt;, &lt;badhosts&gt; } to any
</pre>

      <p>creates a table called private, to hold RFC 1918 private network blocks,
         and a table called badhosts, which is initially empty.  A filter rule is
         set up to block all traffic coming from addresses listed in either table.
         The private table cannot have its contents changed and the badhosts table
         will exist even when no active filter rules reference it.  Addresses may
         later be added to the badhosts table, so that traffic from these hosts
         can be blocked by using</p>

        <code># pfctl -t badhosts -Tadd 204.92.77.111</code>

      <p>A table can also be initialized with an address list specified in one or
         more external files, using the following syntax:</p>

<pre>
table &lt;spam&gt; persist file "/etc/spammers" file "/etc/openrelays"
block on fxp0 from &lt;spam&gt; to any
</pre>

      <p>The files /etc/spammers and /etc/openrelays list IP addresses, one per
         line.  Any lines beginning with a # are treated as comments and ignored.
         In addition to being specified by IP address, hosts may also be specified
         by their hostname.  When the resolver is called to add a hostname to a
         table, all resulting IPv4 and IPv6 addresses are placed into the table.
         IP addresses can also be entered in a table by specifying a valid interface 
         name or the self keyword, in which case all addresses assigned to
         the interface(s) will be added to the table.
      </p>
    </td>
  </tr>
</table>

