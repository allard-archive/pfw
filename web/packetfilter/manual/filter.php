<h3 style="text-align: center;">Display manual for:</h3>
<p style="text-align: center;">&lt;
  <a onclick="manual_showonly('basic');">basic</a>
| <a onclick="manual_showonly('parameters');">parameters</a>
| <a onclick="manual_showonly('addresses');">addresses</a>
| <a onclick="manual_showonly('stateinspection');">stateful inspection</a>
| <a onclick="manual_showonly('osmatch');">os matching</a>
| <a onclick="manual_showonly('queues');">other</a>
| <a onclick="manual_showonly('examples');">examples</a>
&gt;</p>

<table class="manual" id="basic">
  <caption>basic</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>pass</td>
    <td>The packet is passed.</td>
  </tr>
  <tr>
    <td>block</td>
    <td>The packet is blocked.  There are a number of ways in which a <strong>block</strong>
           rule can behave when blocking a packet.  The default behaviour is
           to <strong>drop</strong> packets silently, however this can be overridden or made
           explicit either globally, by setting the <strong>block-policy option</strong>, or on
           a per-rule basis with one of the following options:
      <table>
        <tr>
          <td class="key">drop</td>
          <td>The packet is silently dropped.</td>
        </tr>
        <tr>
          <td class="key">return-rst</td>
          <td>This applies only to tcp(4) packets, and issues a TCP RST
             which closes the connection.</td>
        </tr>
        <tr>
         <td class="key" style="white-space: nowrap">return-icmp<br />return-icmp6</td>
         <td>
           This causes ICMP messages to be returned for packets which
           match the rule.  By default this is an ICMP UNREACHABLE message,
           however this can be overridden by specifying a message
           as a code or number.</td>
        </tr>
        <tr>
          <td class="key">return</td>
          <td>This causes a TCP RST to be returned for tcp(4) packets and
             an ICMP UNREACHABLE for UDP and other packets.</td>
        </tr>
        <tr>
          <td colspan="2">
          Options returning packets have no effect if pf(4) operates on a
           bridge(4).</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>antispoof</td>
    <td><p>"Spoofing" is the faking of IP addresses, typically for malicious purposes.
    The antispoof directive expands to a set of filter rules which will
     block all traffic with a source IP from the network(s) directly connected
     to the specified interface(s) from entering the system through any other
     interface.</p>
    <p>For example, the line</p>

           <code>antispoof for lo0</code>

    <p>expands to</p>

<pre>
block drop in on ! lo0 inet from 127.0.0.1/8 to any
block drop in on ! lo0 inet6 from ::1 to any
</pre>

     <p>For non-loopback interfaces, there are additional rules to block incoming
     packets with a source IP address identical to the interfaces IP(s).  For
     example, assuming the interface wi0 had an IP address of 10.0.0.1 and a
     netmask of 255.255.255.0, the line</p>

           <code>antispoof for wi0 inet</code>

    <p>expands to</p>

<pre>
block drop in on ! wi0 inet from 10.0.0.0/24 to any
block drop in inet from 10.0.0.1 to any
</pre>

     <p>Caveat: Rules created by the antispoof directive interfere with packets
     sent over loopback interfaces to local addresses.  One should pass these
     explicitly.</p>

    </td>
  </tr>
</table>   

<table class="manual" id="parameters">
  <caption>parameters</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      The rule parameters specify the packets to which a rule applies.  A packet 
      always comes in on, or goes out through, one interface.  Most parameters 
      are optional.  If a parameter is specified, the rule only applies to
      packets with matching attributes.  Certain parameters can be expressed as
      lists, in which case pfctl(8) generates all needed rule combinations.
     </td>
    </tr>
    <tr>
      <td><strong>in</strong> or <strong>out</strong></td>
      <td> This rule applies to incoming or outgoing packets.  If neither in
           nor out are specified, the rule will match packets in both directions.
      </td>
    </tr>
    <tr>
      <td>log</td>
      <td> In addition to the action specified, a log message is generated.
           All packets for that connection are logged, unless the keep state,
           modulate state or synproxy state options are specified, in which
           case only the packet that establishes the state is logged.  (See
           keep state, modulate state and synproxy state below).  The logged
           packets are sent to the pflog(4) interface.  This interface is monitored
           by the pflogd(8) logging daemon, which dumps the logged
           packets to the file /var/log/pflog in pcap(3) binary format.
      </td>
    </tr>
    <tr>
      <td>log-all</td>
      <td> Used with keep state, modulate state or synproxy state rules to
           force logging of all packets for a connection.  As with log, packets 
           are logged to pflog(4).</td>
    </tr>
    <tr>
      <td>quick</td>
      <td> If a packet matches a rule which has the quick option set, this
           rule is considered the last matching rule, and evaluation of subsequent 
           rules is skipped.</td>
    </tr>
    <tr>
      <td>interface</td>
      <td> This rule applies only to packets coming in on, or going out
           through, this particular interface.  It is also possible to simply
           give the interface driver name, like ppp or fxp, to make the rule
           match packets flowing through a group of interfaces.</td>
    </tr>
    <tr>
      <td style="white-space: nowrap">address family</td>
      <td> This rule applies only to packets of this address family.  Supported 
        values are inet and inet6.</td>
    </tr>
    <tr>
      <td>protocol</td>
      <td>This rule applies only to packets of this protocol.  Common protocols
          are icmp(4), icmp6(4), tcp(4), and udp(4).  For a list of all
          the protocol name to number mappings used by pfctl(8), see the file
          /etc/protocols.</td>
    </tr>

    <tr>
      <td>flags <strong>a</strong>/<strong>b</strong> | /<strong>b</strong></td>
      <td>  <p>This rule only applies to TCP packets that have the flags <strong>a</strong> set
               out of set <strong>b</strong>.  Flags not specified in <strong>b</strong> are ignored.  The flags
               are: (F)IN, (S)YN, (R)ST, (P)USH, (A)CK, (U)RG, (E)CE, and C(W)R.</p>

        <table>
          <tr>
            <td class="key" style="white-space: nowrap">flags S/S</td>
            <td>Flag SYN is set.  The other flags are ignored.</td>
          </tr>
          <tr>
            <td class="key" style="white-space: nowrap">flags S/SA</td>
            <td>Out of SYN and ACK, exactly SYN may be set.  SYN,
                SYN+PSH and SYN+RST match, but SYN+ACK, ACK and ACK+RST
                do not.  This is more restrictive than the previous example.</td>
          </tr>
          <tr>
            <td class="key" style="white-space: nowrap">flags /SFRA</td>
            <td>If the first set is not specified, it defaults to none.
                All of SYN, FIN, RST and ACK must be unset.</td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td style="white-space: nowrap">probability <strong>number</strong></td>
      <td>
        <p>A probability attribute can be attached to a rule, with a value set
           between 0 and 1, bounds not included.  In that case, the rule will
           be honoured using the given probability value only.  For example,
           the following rule will drop 20% of incoming ICMP packets:</p>

            <code>block in proto icmp probability 20%</code>
      </td>
    </tr>


</table>

<table class="manual" id="addresses">
  <caption>addresses</caption>
  <tr>
     <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
    from <strong>source</strong> port <strong>source</strong>  to <strong>dest</strong> port <strong>dest</strong>
    </td>
  </tr>
  <tr>
    <td style="white-space: nowrap">Match All</td>
    <td>Match All source and destination addresses, all source and destination ports.</td>
  </tr>
  <tr>
    <td colspan="2">
      <p>This rule applies only to packets with the specified source and
           destination addresses and ports.</p>

        <p>Addresses can be specified in CIDR notation (matching netblocks),
           as symbolic host names or interface names, or as any of the following 
           keywords:</p>
        <table>
          <tr>
            <td class="key">any</td>
            <td>Any address.</td>
          </tr>
          <tr>
            <td class="key">no-route</td>
            <td>Any address which is not currently routable.</td>
          </tr>
          <tr>
            <td class="key">&lt;table&gt;</td>
            <td>Any address that matches the given table.</td>
          </tr>
          <tr>
            <td colspan="2">Interface names can have modifiers appended:</td>
          </tr>
          <tr>
            <td class="key">:network</td>
            <td>Translates to the network(s) attached to the interface.</td>
          </tr>
          <tr>
            <td class="key">:broadcast</td>
            <td>Translates to the interfaces broadcast address(es).</td>
          </tr>
          <tr>
            <td class="key">:peer</td>
            <td>Translates to the point to point interfaces peer address(es).</td>
          </tr>
          <tr>
            <td class="key">:0</td>
            <td>Do not include interface aliases.</td>
          </tr>
        </table>
        <p>Host names may also have the :0 option appended to restrict the
           name resolution to the first of each v4 and v6 address found.</p>

        <p>Host name resolution and interface to address translation are done
           at ruleset load-time.  When the address of an interface (or host
           name) changes (under DHCP or PPP, for instance), the ruleset must
           be reloaded for the change to be reflected in the kernel.  Sur-
           rounding the interface name (and optional modifiers) in parentheses
           changes this behaviour.  When the interface name is surrounded by
           parentheses, the rule is automatically updated whenever the inter-
           face changes its address.  The ruleset does not need to be reload-
           ed.  This is especially useful with nat.</p>

        <p>Ports can be specified either by number or by name.  For example,
           port 80 can be specified as www.  For a list of all port name to
           number mappings used by pfctl(8), see the file /etc/services.</p>

        <p>Ports and ranges of ports are specified by using these operators:</p>


        <table>
          <tr><td class="key">=</td>        <td>(equal)</td></tr>
          <tr><td class="key">!=</td>       <td>(unequal)</td></tr>
          <tr><td class="key">&lt;</td>     <td>(less than)</td></tr>
          <tr><td class="key">&lt;=</td>    <td>(less than or equal)</td></tr>
          <tr><td class="key">&gt;</td>     <td>(greater than)</td></tr>
          <tr><td class="key">&gt;=</td>    <td>(greater than or equal)</td></tr>
          <tr><td class="key">:</td>        <td>(range including boundaries)</td></tr>
          <tr><td class="key">&gt;&lt;</td> <td>(range excluding boundaries)</td></tr>
          <tr><td class="key">&lt;&gt;</td> <td>(except range)</td></tr>
          
          <tr><td colspan="2">
          &gt;&lt;, &lt;&gt; and : are binary operators (they take two arguments).  For
           instance:</td></tr>

           <tr><td class="key">port 2000:2004</td><td>
                       means `all ports &gt;= 2000 and &lt;= 2004', hence ports
                       2000, 2001, 2002, 2003 and 2004.</td></tr>

           <tr><td class="key" style="white-space: nowrap">port 2000 &gt;&lt; 2004</td><td>
                       means `all ports &gt; 2000 and &lt; 2004', hence ports 2001,
                       2002 and 2003.</td></tr>

           <tr><td class="key">port 2000 &lt;&gt; 2004</td><td>
                       means `all ports &lt; 2000 or &gt; 2004', hence ports 1-1999
                       and 2005-65535.</td></tr>
        </table>
    </td>
  </tr>
</table>



<table class="manual" id="stateinspection">
  <caption>stateful inspection</caption>
  <tr>
     <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
<h3>Stateful Inspection</h3>
     <p>pf(4) is a stateful packet filter, which means it can track the state of
     a connection.  Instead of passing all traffic to port 25, for instance,
     it is possible to pass only the initial packet, and then begin to keep
     state.  Subsequent traffic will flow because the filter is aware of the
     connection.</p>

     <p>If a packet matches a pass ... keep state rule, the filter creates a
     state for this connection and automatically lets pass all subsequent
     packets of that connection.</p>

     <p>Before any rules are evaluated, the filter checks whether the packet
     matches any state.  If it does, the packet is passed without evaluation
     of any rules.</p>

     <p>States are removed after the connection is closed or has timed out.</p>

     <p>This has several advantages.  Comparing a packet to a state involves
     checking its sequence numbers.  If the sequence numbers are outside the
     narrow windows of expected values, the packet is dropped.  This prevents
     spoofing attacks, such as when an attacker sends packets with a fake
     source address/port but does not know the connection's sequence numbers.</p>

     <p>Also, looking up states is usually faster than evaluating rules.  If
     there are 50 rules, all of them are evaluated sequentially in O(n).  Even
     with 50000 states, only 16 comparisons are needed to match a state, since
     states are stored in a binary search tree that allows searches in O(log2
     n).</p>

     <p>For instance:</p>

<pre>
block all
pass out proto tcp from any to any flags S/SA keep state
pass in  proto tcp from any to any port 25 flags S/SA keep state
</pre>

     <p>This ruleset blocks everything by default.  Only outgoing connections and
     incoming connections to port 25 are allowed.  The initial packet of each
     connection has the SYN flag set, will be passed and creates state.  All
     further packets of these connections are passed if they match a state.</p>

     <p>By default, packets coming in and out of any interface can match a state,
     but it is also possible to change that behaviour by assigning states to a
     single interface or a group of interfaces.</p>

     <p>The default policy is specified by the state-policy global option, but
     this can be adjusted on a per-rule basis by adding one of the if-bound,
     group-bound or floating keywords to the keep state option.  For example,
     if a rule is defined as:</p>

           <code>pass out on ppp from any to 10.12/16 keep state (group-bound)</code>

     <p>A state created on ppp0 would match packets an all PPP interfaces, but
     not packets flowing through fxp0 or any other interface.</p>

     <p>Keeping rules floating is the more flexible option when the firewall is
     in a dynamic routing environment.  However, this has some security implications
     since a state created by one trusted network could allow potentially hostile 
     packets coming in from other interfaces.</p>

     <p>Specifying flags S/SA restricts state creation to the initial SYN packet
     of the TCP handshake.  One can also be less restrictive, and allow state
     creation from intermediate (non-SYN) packets.  This will cause pf(4) to
     synchronize to existing connections, for instance if one flushes the
     state table.</p>

     <p>For UDP, which is stateless by nature, keep state will create state as
     well. UDP packets are matched to states using only host addresses and
     ports.</p>

     <p>ICMP messages fall into two categories: ICMP error messages, which always
     refer to a TCP or UDP packet, are matched against the referred to connection.
     If one keeps state on a TCP connection, and an ICMP source quench
     message referring to this TCP connection arrives, it will be matched to
     the right state and get passed.</p>

     <p>For ICMP queries, keep state creates an ICMP state, and pf(4) knows how
     to match ICMP replies to states.  For example,</p>

           <code>pass out inet proto icmp all icmp-type echoreq keep state</code>

     <p>allows echo requests (such as those created by ping(8)) out, creates
     state, and matches incoming echo replies correctly to states.</p>

     <p>Note: nat, binat and rdr rules implicitly create state for connections.</p>

<h3>STATE MODULATION</h3>
     <p>Much of the security derived from TCP is attributable to how well the
     initial sequence numbers (ISNs) are chosen.  Some popular stack implementations 
     choose very poor ISNs and thus are normally susceptible to ISN
     prediction exploits.  By applying a modulate state rule to a TCP connection, 
     pf(4) will create a high quality random sequence number for each
     connection endpoint.</p>

     <p>The modulate state directive implicitly keeps state on the rule and is
     only applicable to TCP connections.</p>

     <p>For instance:</p>

<pre>
block all
pass out proto tcp from any to any modulate state
pass in  proto tcp from any to any port 25 flags S/SA modulate state
</pre>

     <p>There are two caveats associated with state modulation: A modulate state
     rule can not be applied to a pre-existing but unmodulated connection.
     Such an application would desynchronize TCP's strict sequencing between
     the two endpoints.  Instead, pf(4) will treat the modulate state modifier
     as a keep state modifier and the pre-existing connection will be inferred
     without the protection conferred by modulation.</p>

     <p>The other caveat affects currently modulated states when the state table
     is lost (firewall reboot, flushing the state table, etc...).  pf(4) will
     not be able to infer a connection again after the state table flushes the
     connection's modulator.  When the state is lost, the connection may be
     left dangling until the respective endpoints time out the connection.  It
     is possible on a fast local network for the endpoints to start an ACK
     storm while trying to resynchronize after the loss of the modulator.  Using 
     a flags S/SA modifier on modulate state rules between fast networks
     is suggested to prevent ACK storms.</p>

<h3>SYN PROXY</h3>
     <p>By default, pf(4) passes packets that are part of a tcp(4) handshake between 
     the endpoints.  The synproxy state option can be used to cause
     pf(4) itself to complete the handshake with the active endpoint, perform
     a handshake with the passive endpoint, and then forward packets between
     the endpoints.</p>

     <p>No packets are sent to the passive endpoint before the active endpoint
     has completed the handshake, hence so-called SYN floods with spoofed
     source addresses will not reach the passive endpoint, as the sender can't
     complete the handshake.</p>

     <p>The proxy is transparent to both endpoints, they each see a single connection 
     from/to the other endpoint.  pf(4) chooses random initial sequence numbers 
     for both handshakes.  Once the handshakes are completed,
     the sequence number modulators (see previous section) are used to translate 
     further packets of the connection.  Hence, synproxy state includes
     modulate state and keep state.</p>

     <p>Rules with synproxy will not work if pf(4) operates on a bridge(4).</p>

     <p>Example:</p>

           <code>pass in proto tcp from any to any port www flags S/SA synproxy state</code>
</td>
</tr>
</table>




<table class="manual" id="osmatch">
  <caption>os matching</caption>
  <tr>
     <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">

    <p>Passive OS Fingerprinting is a mechanism to inspect nuances of a TCP connection's
    initial SYN packet and guess at the host's operating system.
    Unfortunately these nuances are easily spoofed by an attacker so the fingerprint
     is not useful in making security decisions.  But the fingerprint
    is typically accurate enough to make policy decisions upon.</p>

    <p>The fingerprints may be specified by operating system class, by version,
    or by subtype/patchlevel.  The class of an operating system is typically
    the vendor or genre and would be OpenBSD for the pf(4) firewall itself.
    The version of the oldest available OpenBSD release on the main ftp site
    would be 2.6 and the fingerprint would be written</p>

          <code>"OpenBSD 2.6"</code>

    <p>The subtype of an operating system is typically used to describe the
    patchlevel if that patch led to changes in the TCP stack behavior.  In
    the case of OpenBSD, the only subtype is for a fingerprint that was normalized
    by the no-df scrub option and would be specified as</p>

          <code>"OpenBSD 3.3 no-df"</code>

    <p>Fingerprints for most popular operating systems are provided by pf.os(5).
    Once pf(4) is running, a complete list of known operating system fingerprints
    may be listed by running:</p>

          <code># pfctl -so</code>

    <p>Filter rules can enforce policy at any level of operating system specification
    assuming a fingerprint is present.  Policy could limit traffic to
    approved operating systems or even ban traffic from hosts that aren't at
    the latest service pack.</p>

    <p>The unknown class can also be used as the fingerprint which will match
    packets for which no operating system fingerprint is known.</p>

    <p>Examples:</p>

<pre>    
pass  out proto tcp from any os OpenBSD keep state
block out proto tcp from any os Doors
block out proto tcp from any os "Doors PT"
block out proto tcp from any os "Doors PT SP3"
block out from any os "unknown"
pass on lo0 proto tcp from any os "OpenBSD 3.3 lo0" keep state
</pre>

    <p>Operating system fingerprinting is limited only to the TCP SYN packet.
    This means that it will not work on other protocols and will not match a
    currently established connection.</p>

    <p>Caveat: operating system fingerprints are occasionally wrong.  There are
    three problems: an attacker can trivially craft his packets to appear as
    any operating system he chooses; an operating system patch could change
    the stack behavior and no fingerprints will match it until the database
    is updated; and multiple operating systems may have the same fingerprint.</p>
  </td>

  </tr>
  <tr>
    <td colspan="2">
      <p>This is the list of known Operating systems for a default OpenBSD 3.6 system:</p>
      <table style="margin: 0;" id="osmatchlist">
        <tr>
          <th>Class</th>
          <th>Version</th>
          <th>Subtype(subversion)</th>
        </tr>
        <tr><td>AIX</td></tr>
        <tr><td>AIX</td><td>4.3</td></tr>
        <tr><td>AIX</td><td>4.3</td><td>2</td></tr>
				<tr><td>AIX</td><td>4.3</td><td>2-3</td></tr>     
				<tr><td>AIX</td><td>4.3</td><td>3</td></tr>       
				<tr><td>AIX</td><td>5.1</td></tr>     
				<tr><td>AIX</td><td>5.1-5.2</td></tr> 
				<tr><td>AIX</td><td>5.2</td></tr>     
				<tr><td>AIX</td><td>5.3</td></tr>     
				<tr><td>AIX</td><td>5.3</td><td>ML1</td></tr>     
				<tr><td>Alteon</td></tr>  
				<tr><td>Alteon</td><td>ACEswitch</td></tr>       
				<tr><td>AMIGAOS</td></tr>
				<tr><td>AMIGAOS</td><td>3.9</td></tr>     
				<tr><td>AOL</td></tr>
				<tr><td>AOL</td><td>web</td><td>cache</td></tr>       
				<tr><td>AXIS</td></tr>
				<tr><td>AXIS</td><td>5600</td></tr>    
				<tr><td>AXIS</td><td>5600</td><td>v5.64</td></tr>   
				<tr><td>BeOS</td></tr>
				<tr><td>BeOS</td><td>5.0</td></tr>     
				<tr><td>BeOS</td><td>5.0-5.1</td></tr> 
				<tr><td>BeOS</td><td>5.1</td></tr>     
				<tr><td>BSD/OS</td></tr>
				<tr><td>BSD/OS</td><td>3.1</td></tr>     
				<tr><td>BSD/OS</td><td>4.0</td></tr>     
				<tr><td>BSD/OS</td><td>4.0-4.3</td></tr> 
				<tr><td>BSD/OS</td><td>4.1</td></tr>     
				<tr><td>BSD/OS</td><td>4.2</td></tr>     
				<tr><td>BSD/OS</td><td>4.3</td></tr>     
				<tr><td>CacheFlow</td></tr>
				<tr><td>CacheFlow</td><td>1.1</td></tr>     
				<tr><td>CacheFlow</td><td>4.1</td></tr>     
				<tr><td>Checkpoint</td></tr>
				<tr><td>Cisco</td></tr>
				<tr><td>Cisco</td><td>12008</td></tr>   
				<tr><td>Cisco</td><td>Content</td><td>Engine</td></tr>  
				<tr><td>Clavister</td></tr>
				<tr><td>Clavister</td><td>7</td></tr>       
				<tr><td>Contiki</td></tr>
				<tr><td>Contiki</td><td>1.1</td></tr>     
				<tr><td>Contiki</td><td>1.1</td><td>rc0</td></tr>     
				<tr><td>Dell</td></tr>
				<tr><td>Dell</td><td>PowerApp</td><td>cache</td></tr>  
				<tr><td>DOS</td></tr>
				<tr><td>DOS</td><td>WATTCP</td></tr>  
				<tr><td>DOS</td><td>WATTCP</td><td>1.05</td></tr>    
				<tr><td>Eagle</td></tr>
				<tr><td>ExtremeWare</td></tr>
				<tr><td>ExtremeWare</td><td>4.x</td></tr>     
				<tr><td>FortiNet</td></tr>
				<tr><td>FortiNet</td><td>FortiGate</td></tr>       
				<tr><td>FortiNet</td><td>FortiGate</td><td>50</td></tr>      
				<tr><td>FreeBSD</td></tr>
				<tr><td>FreeBSD</td><td>2.0</td></tr>     
				<tr><td>FreeBSD</td><td>2.0-2.2</td></tr> 
				<tr><td>FreeBSD</td><td>2.1</td></tr>     
				<tr><td>FreeBSD</td><td>2.2</td></tr>     
				<tr><td>FreeBSD</td><td>3.0</td></tr>     
				<tr><td>FreeBSD</td><td>3.0-3.5</td></tr> 
				<tr><td>FreeBSD</td><td>3.1</td></tr>     
				<tr><td>FreeBSD</td><td>3.2</td></tr>     
				<tr><td>FreeBSD</td><td>3.3</td></tr>     
				<tr><td>FreeBSD</td><td>3.4</td></tr>     
				<tr><td>FreeBSD</td><td>3.5</td></tr>     
				<tr><td>FreeBSD</td><td>4.0</td></tr>     
				<tr><td>FreeBSD</td><td>4.0-4.1</td></tr> 
				<tr><td>FreeBSD</td><td>4.1</td></tr>     
				<tr><td>FreeBSD</td><td>4.4</td></tr>     
				<tr><td>FreeBSD</td><td>4.6</td></tr>     
				<tr><td>FreeBSD</td><td>4.6</td><td>noRFC1323</td></tr>       
				<tr><td>FreeBSD</td><td>4.6-4.8</td></tr> 
				<tr><td>FreeBSD</td><td>4.6-4.8</td><td>noRFC1323</td></tr>       
				<tr><td>FreeBSD</td><td>4.7</td></tr>     
				<tr><td>FreeBSD</td><td>4.7</td><td>noRFC1323</td></tr>       
				<tr><td>FreeBSD</td><td>4.7-4.9</td></tr> 
				<tr><td>FreeBSD</td><td>4.8</td></tr>     
				<tr><td>FreeBSD</td><td>4.8</td><td>noRFC1323</td></tr>       
				<tr><td>FreeBSD</td><td>4.8-4.9</td></tr> 
				<tr><td>FreeBSD</td><td>4.9</td></tr>     
				<tr><td>FreeBSD</td><td>5.0</td></tr>     
				<tr><td>FreeBSD</td><td>5.0-5.1</td></tr> 
				<tr><td>FreeBSD</td><td>5.1</td></tr>     
				<tr><td>HP-UX</td></tr>
				<tr><td>HP-UX</td><td>11.0</td></tr>    
				<tr><td>HP-UX</td><td>11.10</td></tr>   
				<tr><td>HP-UX</td><td>11.11</td></tr>   
				<tr><td>HP-UX</td><td>B.10.20</td></tr> 
				<tr><td>HP-UX</td><td>B.11.00</td></tr> 
				<tr><td>HP-UX</td><td>B.11.00 A</td></tr>   
				<tr><td>Inktomi</td></tr>
				<tr><td>Inktomi</td><td>crawler</td></tr> 
				<tr><td>IRIX</td></tr>
				<tr><td>IRIX</td><td>6.2</td></tr>     
				<tr><td>IRIX</td><td>6.2-6.5</td></tr> 
				<tr><td>IRIX</td><td>6.3</td></tr>     
				<tr><td>IRIX</td><td>6.4</td></tr>     
				<tr><td>IRIX</td><td>6.5</td></tr>     
				<tr><td>IRIX</td><td>6.5</td><td>12</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>12-21</td></tr>   
				<tr><td>IRIX</td><td>6.5</td><td>13</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>14</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>15</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>15-21</td></tr>   
				<tr><td>IRIX</td><td>6.5</td><td>16</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>17</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>18</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>19</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>20</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>21</td></tr>      
				<tr><td>IRIX</td><td>6.5</td><td>RFC1323</td></tr> 
				<tr><td>Linux</td></tr>
				<tr><td>Linux</td><td>2.0</td></tr>     
				<tr><td>Linux</td><td>2.0</td><td>3x</td></tr>      
				<tr><td>Linux</td><td>2.2</td></tr>     
				<tr><td>Linux</td><td>2.2</td><td>20</td></tr>      
				<tr><td>Linux</td><td>2.2</td><td>20-25</td></tr>   
				<tr><td>Linux</td><td>2.2</td><td>21</td></tr>      
				<tr><td>Linux</td><td>2.2</td><td>22</td></tr>      
				<tr><td>Linux</td><td>2.2</td><td>23</td></tr>      
				<tr><td>Linux</td><td>2.2</td><td>24</td></tr>      
				<tr><td>Linux</td><td>2.2</td><td>25</td></tr>      
				<tr><td>Linux</td><td>2.2</td><td>lo0</td></tr>     
				<tr><td>Linux</td><td>2.2</td><td>Opera</td></tr>   
				<tr><td>Linux</td><td>2.2</td><td>ts</td></tr>      
				<tr><td>Linux</td><td>2.4</td></tr>     
				<tr><td>Linux</td><td>2.4</td><td>18</td></tr>      
				<tr><td>Linux</td><td>2.4</td><td>18-21</td></tr>   
				<tr><td>Linux</td><td>2.4</td><td>19</td></tr>      
				<tr><td>Linux</td><td>2.4</td><td>20</td></tr>      
				<tr><td>Linux</td><td>2.4</td><td>21</td></tr>      
				<tr><td>Linux</td><td>2.4</td><td>cluster</td></tr> 
				<tr><td>Linux</td><td>2.4</td><td>lo0</td></tr>     
				<tr><td>Linux</td><td>2.4</td><td>Opera</td></tr>   
				<tr><td>Linux</td><td>2.4</td><td>ts</td></tr>      
				<tr><td>Linux</td><td>2.5</td></tr>     
				<tr><td>Linux</td><td>2.5-2.6</td></tr> 
				<tr><td>Linux</td><td>2.6</td></tr>     
				<tr><td>Linux</td><td>google</td></tr>  
				<tr><td>LookSmart</td></tr>
				<tr><td>LookSmart</td><td>ZyBorg</td></tr>  
				<tr><td>MacOS</td></tr>
				<tr><td>MacOS</td><td>7.3</td></tr>     
				<tr><td>MacOS</td><td>7.3</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>7.3-7.6</td></tr> 
				<tr><td>MacOS</td><td>7.3-7.6</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>7.4</td></tr>     
				<tr><td>MacOS</td><td>7.4</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>7.5</td></tr>     
				<tr><td>MacOS</td><td>7.5</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>7.6</td></tr>     
				<tr><td>MacOS</td><td>7.6</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.0</td></tr>     
				<tr><td>MacOS</td><td>8.0</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.0-8.6</td></tr> 
				<tr><td>MacOS</td><td>8.0-8.6</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.1</td></tr>     
				<tr><td>MacOS</td><td>8.1</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.1-8.6</td></tr> 
				<tr><td>MacOS</td><td>8.1-8.6</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.2</td></tr>     
				<tr><td>MacOS</td><td>8.2</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.3</td></tr>     
				<tr><td>MacOS</td><td>8.3</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.4</td></tr>     
				<tr><td>MacOS</td><td>8.4</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.5</td></tr>     
				<tr><td>MacOS</td><td>8.5</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>8.6</td></tr>     
				<tr><td>MacOS</td><td>8.6</td><td>OTTCP</td></tr>   
				<tr><td>MacOS</td><td>9.0</td></tr>     
				<tr><td>MacOS</td><td>9.0-9.2</td></tr> 
				<tr><td>MacOS</td><td>9.1</td></tr>     
				<tr><td>MacOS</td><td>9.2</td></tr>     
				<tr><td>NetApp</td></tr>
				<tr><td>NetApp</td><td>4.1</td></tr>     
				<tr><td>NetApp</td><td>5.2</td></tr>     
				<tr><td>NetApp</td><td>5.2</td><td>1</td></tr>       
				<tr><td>NetApp</td><td>5.3</td></tr>     
				<tr><td>NetApp</td><td>5.3</td><td>1</td></tr>       
				<tr><td>NetApp</td><td>5.3-5.5</td></tr> 
				<tr><td>NetApp</td><td>5.4</td></tr>     
				<tr><td>NetApp</td><td>5.5</td></tr>     
				<tr><td>NetApp</td><td>5.x</td></tr>     
				<tr><td>NetApp</td><td>CacheFlow</td></tr>       
				<tr><td>NetBSD</td></tr>
				<tr><td>NetBSD</td><td>1.3</td></tr>     
				<tr><td>NetBSD</td><td>1.6</td></tr>     
				<tr><td>NetBSD</td><td>1.6</td><td>df</td></tr>      
				<tr><td>NetBSD</td><td>1.6</td><td>opera</td></tr>   
				<tr><td>NetBSD</td><td>1.6</td><td>randomization</td></tr>   
				<tr><td>NewtonOS</td></tr>
				<tr><td>NewtonOS</td><td>2.1</td></tr>     
				<tr><td>NeXTSTEP</td></tr>
				<tr><td>NeXTSTEP</td><td>3.3</td></tr>     
				<tr><td>NMAP</td></tr>
				<tr><td>NMAP</td><td>OS</td></tr>      
				<tr><td>NMAP</td><td>OS</td><td>1</td></tr>       
				<tr><td>NMAP</td><td>OS</td><td>2</td></tr>       
				<tr><td>NMAP</td><td>OS</td><td>3</td></tr>       
				<tr><td>NMAP</td><td>OS</td><td>4</td></tr>       
				<tr><td>NMAP</td><td>syn</td><td>scan</td></tr>        
				<tr><td>NMAP</td><td>syn</td><td>scan 1</td></tr>
				<tr><td>NMAP</td><td>syn</td><td>scan 2</td></tr>
				<tr><td>NMAP</td><td>syn</td><td>scan 3</td></tr>
				<tr><td>NMAP</td><td>syn</td><td>scan 4</td></tr>
				<tr><td>Nortel</td></tr>
				<tr><td>Nortel</td><td>Contivity</td><td>Client</td></tr>        
				<tr><td>Novell</td></tr>
				<tr><td>Novell</td><td>BorderManager</td></tr>   
				<tr><td>Novell</td><td>IntranetWare</td></tr>    
				<tr><td>Novell</td><td>IntranetWare</td><td>4.11</td></tr>    
				<tr><td>Novell</td><td>NetWare</td></tr> 
				<tr><td>Novell</td><td>NetWare</td><td>5.0</td></tr>     
				<tr><td>Novell</td><td>NetWare</td><td>6</td></tr>       
				<tr><td>OpenBSD</td></tr>
				<tr><td>OpenBSD</td><td>2.6</td></tr>     
				<tr><td>OpenBSD</td><td>3.0</td></tr>     
				<tr><td>OpenBSD</td><td>3.0</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.0</td><td>opera</td></tr>   
				<tr><td>OpenBSD</td><td>3.0-3.5</td></tr> 
				<tr><td>OpenBSD</td><td>3.0-3.5</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.0-3.5</td><td>opera</td></tr>   
				<tr><td>OpenBSD</td><td>3.1</td></tr>     
				<tr><td>OpenBSD</td><td>3.1</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.1</td><td>opera</td></tr>   
				<tr><td>OpenBSD</td><td>3.2</td></tr>     
				<tr><td>OpenBSD</td><td>3.2</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.2</td><td>opera</td></tr>   
				<tr><td>OpenBSD</td><td>3.3</td></tr>     
				<tr><td>OpenBSD</td><td>3.3</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.3</td><td>opera</td></tr>   
				<tr><td>OpenBSD</td><td>3.3-3.5</td></tr> 
				<tr><td>OpenBSD</td><td>3.3-3.5</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.4</td></tr>     
				<tr><td>OpenBSD</td><td>3.4</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.4</td><td>opera</td></tr>   
				<tr><td>OpenBSD</td><td>3.5</td></tr>     
				<tr><td>OpenBSD</td><td>3.5</td><td>no-df</td></tr>   
				<tr><td>OpenBSD</td><td>3.5</td><td>opera</td></tr>   
				<tr><td>OpenVMS</td></tr>
				<tr><td>OpenVMS</td><td>7.2</td></tr>     
				<tr><td>OS/2</td></tr>
				<tr><td>OS/2</td><td>4</td></tr>       
				<tr><td>OS/400</td></tr>
				<tr><td>OS/400</td><td>V4R5</td></tr>    
				<tr><td>OS/400</td><td>V4R5</td><td>CF67032</td></tr> 
				<tr><td>OS/400</td><td>VR4</td></tr>     
				<tr><td>OS/400</td><td>VR5</td></tr>     
				<tr><td>PalmOS</td></tr>
				<tr><td>PalmOS</td><td>3</td></tr>       
				<tr><td>PalmOS</td><td>3</td><td>5</td></tr>       
				<tr><td>PalmOS</td><td>4</td></tr>       
				<tr><td>PalmOS</td><td>5</td></tr>       
				<tr><td>PalmOS</td><td>Tungsten</td></tr>        
				<tr><td>PalmOS</td><td>Tungsten</td><td>C</td></tr>       
				<tr><td>Plan9</td></tr>
				<tr><td>Plan9</td><td>4</td></tr>       
				<tr><td>PocketPC</td></tr>
				<tr><td>PocketPC</td><td>2002</td></tr>    
				<tr><td>Proxyblocker</td></tr>
				<tr><td>QNX</td></tr>
				<tr><td>RISC</td><td>OS</td></tr> 
				<tr><td>RISC</td><td>OS</td><td>3.70</td></tr>    
				<tr><td>RISC</td><td>OS</td><td>3.70 4.10</td></tr>    
				<tr><td>SCO</td></tr>
        <tr><td>SCO</td><td>OpenServer</td></tr>
				<tr><td>SCO</td><td>OpenServer</td><td>5.0</td></tr>     
        <tr><td>SCO</td><td>UnixWare</td></tr>      
				<tr><td>SCO</td><td>UnixWare</td><td>7.1</td></tr>     
				<tr><td>Sega</td></tr>
        <tr><td>Sega</td><td>Dreamcast</td></tr>
				<tr><td>Sega</td><td>Dreamcast</td><td>3.0</td></tr>     
				<tr><td>Sega</td><td>Dreamcast</td><td>HKT-3020</td></tr>        
				<tr><td>Solaris</td></tr>
				<tr><td>Solaris</td><td>10</td></tr>      
				<tr><td>Solaris</td><td>2.5</td></tr>     
				<tr><td>Solaris</td><td>2.5</td><td>1</td></tr>       
				<tr><td>Solaris</td><td>2.5-2.7</td></tr> 
				<tr><td>Solaris</td><td>2.6</td></tr>     
				<tr><td>Solaris</td><td>2.6-2.7</td></tr> 
				<tr><td>Solaris</td><td>2.7</td></tr>     
				<tr><td>Solaris</td><td>2.9</td></tr>     
				<tr><td>Solaris</td><td>8</td></tr>       
				<tr><td>Solaris</td><td>8</td><td>RFC1323</td></tr> 
				<tr><td>Sony</td></tr>
				<tr><td>Sony</td><td>PS2</td></tr>     
				<tr><td>SunOS</td></tr>
				<tr><td>SunOS</td><td>4.1</td></tr>     
				<tr><td>SymbianOS</td></tr>
				<tr><td>SymbianOS</td><td>6048</td></tr>    
				<tr><td>SymbianOS</td><td>7</td></tr>       
				<tr><td>TOPS-20</td></tr>
				<tr><td>TOPS-20</td><td>7</td></tr>       
				<tr><td>Tru64</td></tr>
				<tr><td>Tru64</td><td>4.0</td></tr>     
				<tr><td>Tru64</td><td>5.0</td></tr>     
				<tr><td>Tru64</td><td>5.1</td></tr>     
				<tr><td>Tru64</td><td>5.1</td><td>noRFC1323</td></tr>       
				<tr><td>Tru64</td><td>5.1a</td></tr>    
				<tr><td>Tru64</td><td>5.1a</td><td>JP4</td></tr>     
				<tr><td>ULTRIX</td></tr>
				<tr><td>ULTRIX</td><td>4.5</td></tr>     
				<tr><td>Windows</td></tr>
				<tr><td>Windows</td><td>.NET</td></tr>    
				<tr><td>Windows</td><td>2000</td></tr>    
				<tr><td>Windows</td><td>2000</td><td>cisco</td></tr>   
				<tr><td>Windows</td><td>2000</td><td>RFC1323</td></tr> 
				<tr><td>Windows</td><td>2000</td><td>SP2</td></tr>     
				<tr><td>Windows</td><td>2000</td><td>SP2 +</td></tr>  
				<tr><td>Windows</td><td>2000</td><td>SP3</td></tr>     
				<tr><td>Windows</td><td>2000</td><td>SP4</td></tr>     
				<tr><td>Windows</td><td>2000</td><td>ZoneAlarm</td></tr>       
				<tr><td>Windows</td><td>3.11</td></tr>    
				<tr><td>Windows</td><td>95</td></tr>      
				<tr><td>Windows</td><td>95</td><td>b</td></tr>       
				<tr><td>Windows</td><td>98</td></tr>      
				<tr><td>Windows</td><td>98</td><td>lowTTL</td></tr>  
				<tr><td>Windows</td><td>98</td><td>noSack</td></tr>  
				<tr><td>Windows</td><td>98</td><td>RFC1323</td></tr> 
				<tr><td>Windows</td><td>NT</td></tr>      
				<tr><td>Windows</td><td>NT</td><td>4.0</td></tr>     
				<tr><td>Windows</td><td>XP</td></tr>      
				<tr><td>Windows</td><td>XP</td><td>cisco</td></tr>   
				<tr><td>Windows</td><td>XP</td><td>RFC1323</td></tr> 
				<tr><td>Windows</td><td>XP</td><td>SP1</td></tr>     
				<tr><td>Windows</td><td>XP</td><td>SP3</td></tr>     
				<tr><td>Zaurus</td></tr>
				<tr><td>Zaurus</td><td>3.10</td></tr>
			</table>  
</td>
</tr>
</table>


<table class="manual" id="queues">
  <caption>queues, routing, users &amp; groups, labels &amp; tags</caption>
  <tr>
     <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td style="white-space: nowrap" class="key"><strong>queue</strong><br />(<strong>queue</strong>, <strong>queue</strong>)</td>
    <td>
       <p>Packets matching this rule will be assigned to the specified queue.
         If two queues are given, packets which have a tos of lowdelay and
         TCP ACKs with no data payload will be assigned to the second one.
         See QUEUEING for setup details.</p>

       <p>For example:</p>

<pre>
pass in proto tcp to port 25 queue mail
pass in proto tcp to port 22 queue(ssh_bulk, ssh_prio)
</pre>

    </td>
  </tr>

  <tr>
    <td colspan="2">
      <h3>Routing</h3>
      <p>If a packet matches a rule with a route option set, the packet filter
         will route the packet according to the type of route option.  When such a
         rule creates state, the route option is also applied to all packets
         matching the same connection.</p>
    </td>
  </tr>
  <tr>
    <td>fastroute</td>
    <td>The fastroute option does a normal route lookup to find the next
        hop for the packet.</td>
  </tr>
  <tr>
    <td>route-to</td>
    <td>The route-to option routes the packet to the specified interface
        with an optional address for the next hop.  When a route-to rule
        creates state, only packets that pass in the same direction as the
        filter rule specifies will be routed in this way.  Packets passing
        in the opposite direction (replies) are not affected and are routed
        normally.</td>
  </tr>
  <tr>
    <td>reply-to</td>
    <td>The reply-to option is similar to route-to, but routes packets that
        pass in the opposite direction (replies) to the specified interface.
        Opposite direction is only defined in the context of a state
        entry, and reply-to is useful only in rules that create state. It
        can be used on systems with multiple external connections to route
        all outgoing packets of a connection through the interface the 
        incoming connection arrived through (symmetric routing enforcement).
    </td>
  </tr>
  <tr>
    <td>dup-to</td>
    <td>The dup-to option creates a duplicate of the packet and routes it
        like route-to.  The original packet gets routed as it normally
        would.</td>
  </tr>

  <tr>
    <td>user <strong>user</strong></td>
    <td>
        <p>This rule only applies to packets of sockets owned by the specified
           user.  For outgoing connections initiated from the firewall, this
           is the user that opened the connection.  For incoming connections
           to the firewall itself, this is the user that listens on the destination 
           port.  For forwarded connections, where the firewall is not
           a connection endpoint, the user and group are unknown.</p>

        <p>All packets, both outgoing and incoming, of one connection are 
           associated with the same user and group.  Only TCP and UDP packets
           can be associated with users; for other protocols these parameters
           are ignored.</p>

        <p>User and group refer to the effective (as opposed to the real) IDs,
           in case the socket is created by a setuid/setgid process.  User and
           group IDs are stored when a socket is created; when a process creates 
           a listening socket as root (for instance, by binding to a
           privileged port) and subsequently changes to another user ID (to
           drop privileges), the credentials will remain root.</p>

        <p>User and group IDs can be specified as either numbers or names.
           The syntax is similar to the one for ports.  The value unknown
           matches packets of forwarded connections.  unknown can only be used
           with the operators = and !=.  Other constructs like user >= unknown
           are invalid.  Forwarded packets with unknown user and group ID
           match only rules that explicitly compare against unknown with the
           operators = or !=.  For instance user >= 0 does not match forwarded
           packets.  The following example allows only selected users to open
           outgoing connections:</p>

<pre>
block out proto { tcp, udp } all
pass  out proto { tcp, udp } all user { &lt; 1000, dhartmei } keep state
</pre>

      </td>
    </tr>
    <tr>
      <td>group <strong>group</strong></td>
      <td>Similar to user, this rule only applies to packets of sockets owned
          by the specified group.</td>
    </tr>

    <tr>
      <td>label <strong>string</strong></td>
      <td>
        <p>Adds a label (name) to the rule, which can be used to identify the
           rule.  For instance, pfctl -s labels shows per-rule statistics for
           rules that have labels.</p>

        <p>The following macros can be used in labels:</p>
          <table>
            <tr><td class="key">$if</td><td>The interface.</td></tr>
            <tr><td class="key">$srcaddr</td><td>The source IP address.</td></tr>
            <tr><td class="key">$dstaddr</td><td>The destination IP address.</td></tr>
            <tr><td class="key">$srcport</td><td>The source port specification.</td></tr>
            <tr><td class="key">$dstport</td><td>The destination port specification.</td></tr>
            <tr><td class="key">$proto</td><td>The protocol name.</td></tr>
            <tr><td class="key">$nr</td><td>The rule number.</td></tr>
          </table>

          <p>For example:</p>

<pre>
ips = "{ 1.2.3.4, 1.2.3.5 }"
pass in proto tcp from any to $ips port > 1023 label "$dstaddr:$dstport"
</pre>

          <p>expands to</p>

<pre>
pass in inet proto tcp from any to 1.2.3.4 port > 1023 label "1.2.3.4:>1023"
pass in inet proto tcp from any to 1.2.3.5 port > 1023 label "1.2.3.5:>1023"
</pre>

          <p>The macro expansion for the label directive occurs only at 
          configuration file parse time, not during runtime.</p>
      </td>
    </tr>

    <tr>
      <td>tag <strong>string</strong></td>
      <td>Packets matching this rule will be tagged with the specified
           string.  The tag acts as an internal marker that can be used to
           identify these packets later on.  This can be used, for example, to
           provide trust between interfaces and to determine if packets have
           been processed by translation rules.  Tags are "sticky", meaning
           that the packet will be tagged even if the rule is not the last
           matching rule.  Further matching rules can replace the tag with a
           new one but will not remove a previously applied tag.  A packet is
           only ever assigned one tag at a time.  pass rules that use the tag
           keyword must also use keep state, modulate state or synproxy state.
           Packet tagging can be done during nat, rdr, or binat rules in addition 
           to filter rules.  Tags take the same macros as labels (see
           above).
      </td>
    </tr>
    <tr>
      <td>tagged <strong>string</strong></td>
      <td>Used with filter rules to specify that packets must already be
           tagged with the given tag in order to match the rule.  Inverse tag
           matching can also be done by specifying the ! operator before the
           tagged keyword.</td>
    </tr>
</table>


<table class="manual" id="examples">
  <caption>examples</caption>
  <tr>
     <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
<pre>
# The external interface is kue0
# (157.161.48.183, the only routable address)
# and the private network is 10.0.0.0/8, for which we are doing NAT.

# use a macro for the interface name, so it can be changed easily
ext_if = "kue0"

# normalize all incoming traffic
scrub in on $ext_if all fragment reassemble

# block and log everything by default
block return log on $ext_if all

# block anything coming from source we have no back routes for
block in from no-route to any

# block and log outgoing packets that do not have our address as source,
# they are either spoofed or something is misconfigured (NAT disabled,
# for instance), we want to be nice and do not send out garbage.
block out log quick on $ext_if from ! 157.161.48.183 to any

# silently drop broadcasts (cable modem noise)
block in quick on $ext_if from any to 255.255.255.255

# block and log incoming packets from reserved address space and invalid
# addresses, they are either spoofed or misconfigured, we cannot reply to
# them anyway (hence, no return-rst).
block in log quick on $ext_if from { 10.0.0.0/8, 172.16.0.0/12, \
      192.168.0.0/16, 255.255.255.255/32 } to any

# ICMP

# pass out/in certain ICMP queries and keep state (ping)
# state matching is done on host addresses and ICMP id (not type/code),
# so replies (like 0/0 for 8/0) will match queries
# ICMP error messages (which always refer to a TCP/UDP packet) are
# handled by the TCP/UDP states
pass on $ext_if inet proto icmp all icmp-type 8 code 0 keep state

# UDP

# pass out all UDP connections and keep state
pass out on $ext_if proto udp all keep state

# pass in certain UDP connections and keep state (DNS)
pass in on $ext_if proto udp from any to any port domain keep state

# TCP

# pass out all TCP connections and modulate state
pass out on $ext_if proto tcp all modulate state

# pass in certain TCP connections and keep state (SSH, SMTP, DNS, IDENT)
pass in on $ext_if proto tcp from any to any port { ssh, smtp, domain, \
      auth } flags S/SA keep state

# pass in data mode connections for ftp-proxy running on this host.
# (see ftp-proxy(8) for details)
pass in on $ext_if proto tcp from any to 157.161.48.183 port >= 49152 \
      flags S/SA keep state

# Do not allow Windows 9x SMTP connections since they are typically
# a viral worm. Alternately we could limit these OSes to 1 connection each.
block in on $ext_if proto tcp from any os {"Windows 95", "Windows 98"} \
      to any port smtp

# Packet Tagging

# three interfaces: $int_if, $ext_if, and $wifi_if (wireless). NAT is
# being done on $ext_if for all outgoing packets. tag packets in on
# $int_if and pass those tagged packets out on $ext_if.  all other
# outgoing packets (i.e., packets from the wireless network) are only
# permitted to access port 80.

pass in on $int_if from any to any tag INTNET keep state
pass in on $wifi_if from any to any keep state

block out on $ext_if from any to any
pass out quick on $ext_if tagged INTNET keep state
pass out on $ext_if from any to any port 80 keep state

# tag incoming packets as they are redirected to spamd(8). use the tag
# to pass those packets through the packet filter.

rdr on $ext_if inet proto tcp from &lt;spammers&gt; to port smtp \
        tag SPAMD -> 127.0.0.1 port spamd

block in on $ext_if
pass in on $ext_if inet proto tcp tagged SPAMD keep state
</pre>
    </td>
  </tr>
</table>
