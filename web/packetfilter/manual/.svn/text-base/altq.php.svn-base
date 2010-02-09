<table class="manual" id="basic">
  <caption>queueing</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      Packets can be assigned to queues for the purpose of bandwidth control.
      At least two declarations are required to configure queues, and later any
      packet filtering rule can reference the defined queues by name.  During
      the filtering component of pf.conf, the last referenced queue name is
      where any packets from pass rules will be queued, while for block rules
      it specifies where any resulting ICMP or TCP RST packets should be
      queued.  The scheduler defines the algorithm used to decide which packets
      get delayed, dropped, or sent out immediately.  There are three
      schedulers currently supported.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">cbq</td>
    <td>Class Based Queueing.  Queues attached to an interface build a
        tree, thus each queue can have further child queues.  Each queue
        can have a priority and a bandwidth assigned.  Priority mainly controls 
        the time packets take to get sent out, while bandwidth has
        primarily effects on throughput.  cbq achieves both partitioning
        and sharing of link bandwidth by hierarchically structured classes.
        Each class has its own queue and is assigned its share of
        bandwidth.  A child class can borrow bandwidth from its parent
        class as long as excess bandwidth is available (see the option
        borrow, below).
    </td>
  </tr>
  <tr>
    <td class="nowrap">priq</td>
    <td>Priority Queueing.  Queues are flat attached to the interface,
        thus, queues cannot have further child queues.  Each queue has a
        unique priority assigned, ranging from 0 to 15.  Packets in the
        queue with the highest priority are processed first.
    </td>
  </tr>
  <tr>
    <td class="nowrap">hfsc</td>
    <td>Hierarchical Fair Service Curve.  Queues attached to an interface
        build a tree, thus each queue can have further child queues.  Each
        queue can have a priority and a bandwidth assigned.  Priority mainly 
        controls the time packets take to get sent out, while bandwidth
        has primarily effects on throughput.  hfsc supports both link-sharing 
        and guaranteed real-time services.  It employs a service curve
        based QoS model, and its unique feature is an ability to decouple
        delay and bandwidth allocation.
    </td>
  </tr>
  <tr>
    <td colspan="2">
      The interfaces on which queueing should be activated are declared using
      the altq on declaration. altq on has the following keywords:
    </td>
  </tr>
  <tr>
    <td class="nowrap"><strong>interface</strong></td>
    <td>Queueing is enabled on the named interface.</td>
  </tr>
  <tr>
    <td class="nowrap"><strong>scheduler</strong></td>
    <td>Specifies which queueing scheduler to use. Currently supported
        values are cbq for Class Based Queueing, priq for Priority Queueing
        and hfsc for the Hierarchical Fair Service Curve scheduler.
    </td>
  </tr>
  <tr>
    <td class="nowrap">bandwidth <strong>bw</strong></td>
    <td>The maximum bitrate for all queues on an interface may be specified
        using the bandwidth keyword. The value can be specified as an ab-
        solute value or as a percentage of the interface bandwidth.  When
        using an absolute value, the suffixes b, Kb, Mb, and Gb are used to
        represent bits, kilobits, megabits, and gigabits per second, respectively.  
        The value must not exceed the interface bandwidth. If
        bandwidth is not specified, the interface bandwidth is used.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">qlimit <strong>limit</strong></td>
    <td>The maximum number of packets held in the queue. The default is  50.</td>
  </tr>
  <tr>
    <td nowrap="nowrap">tbrsize <strong>size</strong></td>
    <td>Adjusts the size, in bytes, of the token bucket regulator. If not
        specified, heuristics based on the interface bandwidth are used to
        determine the size.
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap">queue <strong>list</strong></td>
    <td>Defines a list of subqueues to create on an interface.</td>
  </tr>
  <tr>
    <td colspan="2">
      <p>In the following example, the interface dc0 should queue up to 5 Mbit/s
         in four second-level queues using Class Based Queueing. Those four
         queues will be shown in a later example.</p>

        <code>altq on dc0 cbq bandwidth 5Mb queue { std, http, mail, ssh }</code>
    </td>
  </tr>
</table>
