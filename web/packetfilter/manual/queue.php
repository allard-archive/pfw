<table class="manual" id="basic">
  <caption>queueing</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
        Once interfaces are activated for queueing using the altq directive, a
        sequence of queue directives may be defined.  The name associated with a
        queue must match a queue defined in the altq directive (e.g. mail), or,
        except for the priq scheduler, in a parent queue declaration.  The following 
        keywords can be used:
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">on <strong>interface</strong></td>
    <td>Specifies the interface the queue operates on.  If not given, it
        operates on all matching interfaces.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">bandwidth <strong>bw</strong></td>
    <td>Specifies the maximum bitrate to be processed by the queue.  This
        value must not exceed the value of the parent queue and can be
        specified as an absolute value or a percentage of the parent
        queue's bandwidth.  The priq scheduler does not support bandwidth
        specification.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">priority <strong>level</strong></td>
    <td>Between queues a priority level can be set.  For cbq and hfsc, the
        range is 0 to 7 and for priq, the range is 0 to 15.  The default
        for all is 1.  Priq queues with a higher priority are always served
        first.  Cbq and Hfsc queues with a higher priority are preferred in
        the case of overload.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">qlimit <strong>limit</strong></td>
    <td>The maximum number of packets held in the queue. The default is 50.</td>
  </tr>
  <tr>
    <td colspan="2">
      <p>The scheduler can get additional parameters with <strong>scheduler</strong>(
        <strong>parameters</strong> ).  Parameters are as follows:</p>

      <table>
        <tr>
          <td class="key">default</td>
          <td>Packets not matched by another queue are assigned to this
              one.  Exactly one default queue is required.
          </td>
        </tr>
        <tr>
          <td class="key">red</td>
          <td>Enable RED (Random Early Detection) on this queue.  RED drops
              packets with a probability proportional to the average queue
              length.
          </td>
        </tr>
        <tr>
          <td class="key">rio</td>
          <td>Enables RIO on this queue.  RIO is RED with IN/OUT, thus running 
              RED two times more than RIO would achieve the same effect.
              RIO is currently not supported in the GENERIC kernel.
          </td>
        </tr>
        <tr>
          <td class="key">ecn</td>
          <td>Enables ECN (Explicit Congestion Notification) on this queue.
              ECN implies RED.
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td colspan="2">
      <p>The cbq scheduler supports an additional option:</p>
      
      <table>
        <tr>
          <td class="key">borrow</td>
          <td>The queue can borrow bandwidth from the parent.</td>
        </tr>
      </table>
      
      <p>The hfsc scheduler supports some additional options:</p>
      
      <table>
        <tr>
          <td class="key">realtime <strong>sc</strong></td>
          <td>The minimum required bandwidth for the queue.</td>
        </tr>
        <tr>
          <td class="key">upperlimit <strong>sc</strong></td>
          <td>The maximum allowed bandwidth for the queue.</td>
        </tr>
        <tr>
          <td class="key">linkshare <strong>sc</strong></td>
          <td>The bandwidth share of a backlogged queue.</td>
        </tr>
      </table>
      
      <p>&lt;sc&gt; is an acronym for service curve.</p>

      <p>The format for service curve specifications is (m1, d, m2).  m2 controls
         the bandwidth assigned to the queue.  m1 and d are optional and can be
         used to control the initial bandwidth assignment.  For the first d 
         milliseconds the queue gets the bandwidth given as m1, afterwards the value
         given in m2.</p>

      <p>Furthermore, with cbq and hfsc, child queues can be specified as in an
         altq declaration, thus building a tree of queues using a part of their
         parent's bandwidth.</p>

      <p>Packets can be assigned to queues based on filter rules by using the
         queue keyword.  Normally only one queue is specified; when a second one
         is specified it will instead be used for packets which have a TOS of
         lowdelay and for TCP ACKs with no data payload.</p>

      <p>To continue the previous example, the examples below would specify the
         four referenced queues, plus a few child queues.  Interactive ssh(1) sessions 
         get priority over bulk transfers like scp(1) and sftp(1).  The
         queues may then be referenced by filtering rules.</p>

<pre>
queue std bandwidth 10% cbq(default)
queue http bandwidth 60% priority 2 cbq(borrow red) { employees, developers }
queue   developers bandwidth 75% cbq(borrow)
queue   employees bandwidth 15%
queue mail bandwidth 10% priority 0 cbq(borrow ecn)
queue ssh bandwidth 20% cbq(borrow) { ssh_interactive, ssh_bulk }
queue   ssh_interactive priority 7
queue   ssh_bulk priority 0

block return out on dc0 inet all queue std
pass out on dc0 inet proto tcp from $developerhosts to any port 80 keep state queue developers
pass out on dc0 inet proto tcp from $employeehosts to any port 80 keep state queue employees
pass out on dc0 inet proto tcp from any to any port 22 keep state queue(ssh_bulk, ssh_interactive)
pass out on dc0 inet proto tcp from any to any port 25 keep state queue mail
</pre>
    </td>
  </tr>
</table>
