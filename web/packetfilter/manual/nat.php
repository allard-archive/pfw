<h3 style="text-align: center;">Display manual for:</h3>
<p style="text-align: center;">&lt;
  <a onclick="manual_showonly('basic');">translation</a>
| <a onclick="manual_showonly('pool');">pool options</a>
| <a onclick="manual_showonly('examples');">examples</a>
&gt;</p>

<table class="manual" id="basic">
  <caption>translation</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      <p>Translation rules modify either the source or destination address of the
          packets associated with a stateful connection.  A stateful connection is
          automatically created to track packets matching such a rule as long as
          they are not blocked by the filtering section of pf.conf.  The translation 
          engine modifies the specified address and/or port in the packet, 
          recalculates IP, TCP and UDP checksums as necessary, and passes it to the
          packet filter for evaluation.</p>

      <p>Since translation occurs before filtering the filter engine will see
          packets as they look after any addresses and ports have been translated.
          Filter rules will therefore have to filter based on the translated ad-
          dress and port number.  Packets that match a translation rule are only
          automatically passed if the pass modifier is given, otherwise they are
          still subject to block and pass rules.</p>

      <p>The state entry created permits pf(4) to keep track of the original address 
          for traffic associated with that state and correctly direct return
          traffic for that connection.</p>

      <p>Various types of translation are possible with pf:</p>
    </td>
  </tr>
  <tr>
    <td>binat</td>
    <td>A binat rule specifies a bidirectional mapping between an external
        IP netblock and an internal IP netblock.</td>
  </tr>
  <tr>
    <td>nat</td>
    <td>
      <p>A nat rule specifies that IP addresses are to be changed as the
         packet traverses the given interface.  This technique allows one or
         more IP addresses on the translating host to support network traffic 
         for a larger range of machines on an "inside" network.  Although 
         in theory any IP address can be used on the inside, it is
         strongly recommended that one of the address ranges defined by RFC
         1918 be used.  These netblocks are:</p>

           <code>10.0.0.0 - 10.255.255.255 (all of net 10, i.e., 10/8)</code><br />
           <code>172.16.0.0 - 172.31.255.255 (i.e., 172.16/12)</code><br />
           <code>192.168.0.0 - 192.168.255.255 (i.e., 192.168/16)</code>
    </td>
  </tr>
  <tr>
    <td>rdr</td>
    <td>The packet is redirected to another destination and possibly a different 
        port. rdr rules can optionally specify port ranges instead
        of single ports.  rdr ... port 2000:2999 -&gt; ... port 4000 redirects
        ports 2000 to 2999 (inclusive) to port 4000.  rdr ... port
        2000:2999 -&gt; ... port 4000:* redirects port 2000 to 4000, 2001 to
        4001, ..., 2999 to 4999.</td>
  </tr>
  <tr>
    <td colspan="2">

      <p>In addition to modifying the address, some translation rules may modify
         source or destination ports for tcp(4) or udp(4) connections; implicitly
         in the case of nat rules and explicitly in the case of rdr rules.  Port
         numbers are never translated with a binat rule.</p>

      <p>For each packet processed by the translator, the translation rules are
         evaluated in sequential order, from first to last.  The first matching
         rule decides what action is taken.</p>

      <p>The no option prefixed to a translation rule causes packets to remain 
         untranslated, much in the same way as drop quick works in the packet filter
         (see below).  If no rule matches the packet it is passed to the filter
         engine unmodified.</p>

      <p>Translation rules apply only to packets that pass through the specified
         interface, and if no interface is specified, translation is applied to
         packets on all interfaces.  For instance, redirecting port 80 on an 
         external interface to an internal web server will only work for connections
         originating from the outside.  Connections to the address of the external
         interface from local hosts will not be redirected, since such packets do
         not actually pass through the external interface.  Redirections cannot
         reflect packets back through the interface they arrive on, they can only
         be redirected to hosts connected to different interfaces or to the 
         firewall itself.</p>

      <p>Note that redirecting external incoming connections to the loopback 
         address, as in</p>

        <code>rdr on ne3 inet proto tcp to port 8025 -&gt; 127.0.0.1 port 25</code>

      <p>will effectively allow an external host to connect to daemons bound 
         solely to the loopback address, circumventing the traditional blocking of
         such connections on a real interface.  Unless this effect is desired, any
         of the local non-loopback addresses should be used as redirection target
         instead, which allows external connections only to daemons bound to this
         address or not bound to any address.</p>
    </td>
  </tr>
</table>


<table class="manual" id="pool">
  <caption>pool options</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      For nat and rdr rules, (as well as for the route-to, reply-to and dup-to
      rule options) for which there is a single redirection address which has a
      subnet mask smaller than 32 for IPv4 or 128 for IPv6 (more than one IP
      address), a variety of different methods for assigning this address can
      be used:
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">bitmask</td>
    <td>The bitmask option applies the network portion of the redirection
        address to the address to be modified (source with nat, destination
        with rdr).
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">random</td>
    <td>The random option selects an address at random within the defined
        block of addresses.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">source-hash</td>
    <td>The source-hash option uses a hash of the source address to determine 
        the redirection address, ensuring that the redirection address
        is always the same for a given source.  An optional key can be
        specified after this keyword either in hex or as a string; by default 
        pfctl(8) randomly generates a key for source-hash every time
        the ruleset is reloaded.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">round-robin</td>
    <td><p>The round-robin option loops through the redirection address(es).</p>

        <p>When more than one redirection address is specified, round-robin is
           the only permitted pool type.</p>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">static-port</td>
    <td>With nat rules, the static-port option prevents pf(4) from modifying
        the source port on TCP and UDP packets.</td>
  </tr>
  <tr>
    <td colspan="2">
     Additionally, the sticky-address option can be specified to help ensure
     that multiple connections from the same source are mapped to the same
     redirection address.  This option can be used with the random and round-
     robin pool options.  Note that by default these associations are de-
     stroyed as soon as there are no longer states which refer to them; in or-
     der to make the mappings last beyond the lifetime of the states, increase
     the global options with set timeout source-track See STATEFUL TRACKING
     OPTIONS for more ways to control the source tracking.
    </td>
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

      <p>This example maps incoming requests on port 80 to port 8080, on which a
         daemon is running (because, for example, it is not run as root, and
         therefore lacks permission to bind to port 80).</p>

        <code># use a macro for the interface name, so it can be changed easily</code><br />
        <code>ext_if = "ne3"</code><br /><br />

        <code># map daemon on 8080 to appear to be on 80</code><br />
        <code>rdr on $ext_if proto tcp from any to any port 80 -&gt; 127.0.0.1 port 8080</code>

    <p>If the pass modifier is given, packets matching the translation rule are
       passed without inspecting the filter rules:</p>

    <code>rdr pass on $ext_if proto tcp from any to any port 80 -&gt; 127.0.0.1 \</code><br />
    <code>&nbsp;&nbsp;&nbsp;port 8080</code>

    <p>In the example below, vlan12 is configured as 192.168.168.1; the machine
       translates all packets coming from 192.168.168.0/24 to 204.92.77.111 when
       they are going out any interface except vlan12.  This has the net effect
       of making traffic from the 192.168.168.0/24 network appear as though it
       is the Internet routable address 204.92.77.111 to nodes behind any interface 
       on the router except for the nodes on vlan12.  (Thus, 192.168.168.1
       can talk to the 192.168.168.0/24 nodes.)</p>

      <code>nat on ! vlan12 from 192.168.168.0/24 to any -&gt; 204.92.77.111</code>

    <p>In the example below, the machine sits between a fake internal
       144.19.74.*  network, and a routable external IP of 204.92.77.100.  The
       no nat rule excludes protocol AH from being translated.</p>

<pre>
# NO NAT
no nat on $ext_if proto ah from 144.19.74.0/24 to any
nat on $ext_if from 144.19.74.0/24 to any -> 204.92.77.100
</pre>

<p>In the example below, packets bound for one specific server, as well as
those generated by the sysadmins are not proxied; all other connections
are.</p>

<pre>
# NO RDR
no rdr on $int_if proto { tcp, udp } from any to $server port 80
no rdr on $int_if proto { tcp, udp } from $sysadmins to any port 80
rdr on $int_if proto { tcp, udp } from any to any port 80 -> 127.0.0.1 \
      port 80
</pre>

<p>This longer example uses both a NAT and a redirection.  The external interface 
has the address 157.161.48.183.  On the internal interface, we
are running ftp-proxy(8), listening for outbound ftp sessions captured to
port 8021.</p>

<pre>
# NAT
# Translate outgoing packets' source addresses (any protocol).
# In this case, any address but the gateway's external address is mapped.
nat on $ext_if inet from ! ($ext_if) to any -> ($ext_if)

# NAT PROXYING
# Map outgoing packets' source port to an assigned proxy port instead of
# an arbitrary port.
# In this case, proxy outgoing isakmp with port 500 on the gateway.
nat on $ext_if inet proto udp from any port = isakmp to any -> ($ext_if) \
      port 500

# BINAT
# Translate outgoing packets' source address (any protocol).
# Translate incoming packets' destination address to an internal machine
# (bidirectional).
binat on $ext_if from 10.1.2.150 to any -> ($ext_if)

# RDR
# Translate incoming packets' destination addresses.
# As an example, redirect a TCP and UDP port to an internal machine.
rdr on $ext_if inet proto tcp from any to ($ext_if) port 8080 \
      -> 10.1.2.151 port 22
rdr on $ext_if inet proto udp from any to ($ext_if) port 8080 \
      -> 10.1.2.151 port 53

# RDR
# Translate outgoing ftp control connections to send them to localhost
# for proxying with ftp-proxy(8) running on port 8021.
rdr on $int_if proto tcp from any to any port 21 -> 127.0.0.1 port 8021
</pre>

<p>In this example, a NAT gateway is set up to translate internal addresses
using a pool of public addresses (192.0.2.16/28) and to redirect incoming
web server connections to a group of web servers on the internal network.</p>

<pre>
# NAT LOAD BALANCE
# Translate outgoing packets' source addresses using an address pool.
# A given source address is always translated to the same pool address by
# using the source-hash keyword.
nat on $ext_if inet from any to any -> 192.0.2.16/28 source-hash

# RDR ROUND ROBIN
# Translate incoming web server connections to a group of web servers on
# the internal network.
rdr on $ext_if proto tcp from any to any port 80 \
      -> { 10.1.2.155, 10.1.2.160, 10.1.2.161 } round-robin
</pre>
</td>
</tr>
</table>