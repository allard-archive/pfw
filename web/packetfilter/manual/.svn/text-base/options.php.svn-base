<table class="manual" id="basic">
  <caption>options</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>set timeout</td>
    <td>
      <table>
        <tr>
          <td class="key">interval</td>
          <td>Interval between purging expired states and fragments.</td>
        </tr>
        <tr>
          <td class="key">frag</td>
          <td>Seconds before an unassembled fragment is expired.</td>
        </tr>
        <tr>
          <td class="key">src.track</td>
          <td>Length of time to retain a source tracking entry after
              the last state expires.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>When a packet matches a stateful connection, the seconds to live
         for the connection will be updated to that of the proto.modifier
         which corresponds to the connection state.  Each packet which
         matches this state will reset the TTL.  Tuning these values may 
         improve the performance of the firewall at the risk of dropping valid
         idle connections.</p>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <table>
        <tr>
          <td class="key">tcp.first</td>
          <td>The state after the first packet.</td>
        </tr>
        <tr>
          <td class="key">tcp.opening</td>
          <td>The state before the destination host ever sends a packet.</td>
        </tr>
        <tr>
          <td class="key">tcp.established</td>
          <td>The fully established state.</td>
        </tr>
        <tr>
          <td class="key">tcp.closing</td>
          <td>The state after the first FIN has been sent.</td>
        </tr>
        <tr>
          <td class="key">tcp.finwait</td>
          <td>The state after both FINs have been exchanged and the connection 
              is closed.  Some hosts (notably web servers on Solaris)
              send TCP packets even after closing the connection.  Increasing 
              tcp.finwait (and possibly tcp.closing) can prevent blocking 
              of such packets.</td>
        </tr>
        <tr>
          <td class="key">tcp.closed</td>
          <td>The state after one endpoint sends an RST.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>ICMP and UDP are handled in a fashion similar to TCP, but with a
          much more limited set of states:</p>

      <table>
        <tr>
          <td class="key">udp.first</td>
          <td>The state after the first packet.</td>
        </tr>
        <tr>
          <td class="key">udp.single</td>
          <td>The state if the source host sends more than one packet but
              the destination host has never sent one back.</td>
        </tr>
        <tr>
          <td class="key">udp.multiple</td>
          <td>The state if both hosts have sent packets.</td>
        </tr>
        <tr>
          <td class="key">icmp.first</td>
          <td>The state after the first packet.</td>
        </tr>
        <tr>
          <td class="key">icmp.error</td>
          <td>The state after an ICMP error came back in response to an
              ICMP packet.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>Other protocols are handled similarly to UDP:</p>
      <p>other.first<br />
       other.single<br />
       other.multiple</p>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>Timeout values can be reduced adaptively as the number of state
         table entries grows.</p>
      <table>
        <tr>
          <td class="key">adaptive.start</td>
          <td>When the number of state entries exceeds this value, adaptive
              scaling begins.  All timeout values are scaled linearly with
              factor (adaptive.end - number of states) / (adaptive.end -
              adaptive.start).</td>
        </tr>
        <tr>
          <td class="key">adaptive.end</td>
          <td>When reaching this number of state entries, all timeout values 
              become zero, effectively purging all state entries immediately.
              This value is used to define the scale factor, it should not 
              actually be reached (set a lower state limit, see below).</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2">
      <p>These values can be defined both globally and for each rule.  When
         used on a per-rule basis, the values relate to the number of states
         created by the rule, otherwise to the total number of states.</p>

      <p>For example:</p>
<pre>
set timeout tcp.first 120
set timeout tcp.established 86400
set timeout { adaptive.start 6000, adaptive.end 12000 }
set limit states 10000
</pre>

      <p>With 9000 state table entries, the timeout values are scaled to 50%>
       (tcp.first 60, tcp.established 43200).</p>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set loginterface</td>
    <td>
      <p>Enable collection of packet and byte count statistics for the given
         interface.  These statistics can be viewed using</p>

        <code># pfctl -s info</code>

      <p>In this example pf(4) collects statistics on the interface named
         dc0:</p>

        <code>set loginterface dc0</code>

      <p>One can disable the loginterface using:</p>

        <code>set loginterface none</code>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set limit</td>
    <td>
      <p>Sets hard limits on the memory pools used by the packet filter.
         See pool(9) for an explanation of memory pools.</p>

      <p>For example,</p>

        <code>set limit states 20000</code>

      <p>sets the maximum number of entries in the memory pool used by state
         table entries (generated by keep state rules) to 20000.  Using</p>

        <code>set limit frags 20000</code>

      <p>sets the maximum number of entries in the memory pool used for
         fragment reassembly (generated by scrub rules) to 20000.  Finally,</p>

        <code>set limit src-nodes 2000</code>

      <p>sets the maximum number of entries in the memory pool used for
         tracking source IP addresses (generated by the sticky-address and
         source-track options) to 2000.</p>

      <p>These can be combined:</p>

        <code>set limit { states 20000, frags 20000, src-nodes 2000 }</code>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set optimization</td>
    <td>
      <p>Optimize the engine for one of the following network environments:</p>

      <table>
        <tr>
          <td class="key">normal</td>
          <td>A normal network environment.  Suitable for almost all networks.</td>
        </tr>
        <tr>
          <td class="key">high-latency</td>
          <td>A high-latency environment (such as a satellite connection).</td>
        </tr>
        <tr>
          <td class="key">satellite</td>
          <td>Alias for high-latency.</td>
        </tr>
        <tr>
          <td class="key">aggressive</td>
          <td>Aggressively expire connections.  This can greatly reduce the
              memory usage of the firewall at the cost of dropping idle
              connections early.<td>
        </tr>
        <tr>
          <td class="key">conservative</td>
          <td>Extremely conservative settings.  Avoid dropping legitimate
              connections at the expense of greater memory utilization
              (possibly much greater on a busy network) and slightly 
              increased processor utilization.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set block-policy</td>
    <td>
      <p>The block-policy option sets the default behaviour for the packet
         block action:</p>
      
      <table>
        <tr>
          <td class="key">drop</td>
          <td>Packet is silently dropped.</td>
        </tr>
        <tr>
          <td class="key">return</td>
          <td>A TCP RST is returned for blocked TCP packets, an ICMP
              UNREACHABLE is returned for blocked UDP packets, and all
              other packets are silently dropped.</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set state-policy</td>
    <td>
      <p>The state-policy option sets the default behaviour for states:</p>
      
      <table>
        <tr>
          <td class="key">if-bound</td>
          <td>States are bound to interface.</td>
        </tr>
        <tr>
          <td class="key">group-bound</td>
          <td>States are bound to interface group (i.e. ppp)</td>
        </tr>
        <tr>
          <td class="key">floating</td>
          <td>States can match packets on any interfaces (the default).</td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td nowrap="nowrap">set require-order</td>
    <td>By default pfctl(8) enforces an ordering of the statement types in
        the ruleset to: options, normalization, queueing, translation,
        filtering.  Setting this option to no disables this enforcement.
        There may be non-trivial and non-obvious implications to an out of
        order ruleset.  Consider carefully before disabling the order enforcement.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set fingerprints</td>
    <td>Load fingerprints of known operating systems from the given filename.  
       By default fingerprints of known operating systems are automatically 
       loaded from pf.os(5) in /etc but can be overridden via
       this option.  Setting this option may leave a small period of time
       where the fingerprints referenced by the currently active ruleset
       are inconsistent until the new ruleset finishes loading.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">set debug</td>
    <td>
      <p>Set the debug level to one of the following:</p>
      
      <table>
        <tr>
          <td class="key">none</td>
          <td>Don't generate debug messages.</td>
        </tr>
        <tr>
          <td class="key">urgent</td>
          <td>Generate debug messages only for serious errors.</td>
        </tr>
        <tr>
          <td class="key">misc</td>
          <td>Generate debug messages for various errors.</td>
        </tr>
        <tr>
          <td class="key">loud</td>
          <td>Generate debug messages for common conditions.</td>
        </tr>
      </table>
    </td>
  </tr>
</table>

