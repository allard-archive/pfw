<table class="manual" id="basic">
  <caption>anchor</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      <p>Besides the main ruleset, pfctl(8) can load rulesets into anchor attachment points.  
         An anchor is a container that can hold rules, address tables, and other anchors.</p>

      <p>An anchor has a name which specifies the path where pfctl(8) can be used
         to access the anchor to perform operations on it, such as attaching child
         anchors to it or loading rules into it.  Anchors may be nested, with 
         components separated by `/' characters, similar to how file system hierarchies 
         are laid out. The main ruleset is actually the default anchor, so
         filter and translation rules, for example, may also be contained in any
         anchor.</p>
      
      <p>An anchor can reference another anchor attachment point using the following kinds of rules:</p>

      <table>
        <tr>
          <td class="key">anchor <strong>name</strong></td>
          <td>Evaluates the filter rules in the specified anchor.</td>
        </tr>
        <tr>
          <td class="key">nat-anchor <strong>name</strong></td>
          <td>Evaluates the nat rules in the specified anchor.</td>
        </tr>
        <tr>
          <td class="key">rdr-anchor <strong>name</strong></td>
          <td>Evaluates the rdr rules in the specified anchor.</td>
        </tr>
        <tr>
          <td class="key">binat-anchor <strong>name</strong></td>
          <td>Evaluates the binat-anchor rules in the specified anchor.</td>
        </tr>
        <tr>
          <td class="key">load anchor <strong>name</strong> from <strong>file</strong></td>
          <td>Loads the rules from the specified file into the anchor name. In pfw, this counfiguration
              can be found in the <a href="table.php">table</a> menu.
          </td>
        </tr>
      </table>


      <p>When evaluation of the main ruleset reaches an anchor rule, pf(4) will
         proceed to evaluate all rules specified in that anchor.</p>

      <p>Matching filter and translation rules in anchors with the quick option
         are final and abort the evaluation of the rules in other anchors and the
         main ruleset.</p>

      <p>anchor rules are evaluated relative to the anchor in which they are contained. 
         For example, all anchor rules specified in the main ruleset will
         reference anchor attachment points underneath the main ruleset, and
         anchor rules specified in a file loaded from a load anchor rule will be
         attached under that anchor point.</p>
      
      <p>Rules may be contained in anchor attachment points which do not contain
         any rules when the main ruleset is loaded, and later such anchors can be
         manipulated through pfctl(8) without reloading the main ruleset or other
         anchors.  For example,</p>

<pre>
ext_if = "kue0"
block on $ext_if all
anchor spam
pass out on $ext_if all keep state
pass in on $ext_if proto tcp from any to $ext_if port smtp keep state
</pre>

      <p>blocks all packets on the external interface by default, then evaluates
         all rules in the anchor named "spam", and finally passes all outgoing
         connections and incoming connections to port 25.</p>

        <code># echo "block in quick from 1.2.3.4 to any" | pfctl -a spam -f -</code>

      <p>This loads a single rule into the anchor, which blocks all packets from a
         specific address.</p>

      <p>The anchor can also be populated by adding a load anchor rule after the
         anchor rule:</p>

<pre>
anchor spam
load anchor spam from "/etc/pf-spam.conf"
</pre>

      <p>When pfctl(8) loads pf.conf, it will also load all the rules from the
         file /etc/pf-spam.conf into the anchor.</p>

      <p>Optionally, anchor rules can specify the parameter's direction, interface, 
         address family, protocol and source/destination address/port using
         the same syntax as filter rules.  When parameters are used, the anchor
         rule is only evaluated for matching packets. This allows conditional
         evaluation of anchors, like:</p>

<pre>
block on $ext_if all
anchor spam proto tcp from any to any port smtp
pass out on $ext_if all keep state
pass in on $ext_if proto tcp from any to $ext_if port smtp keep state
</pre>

      <p>The rules inside anchor spam are only evaluated for tcp packets with 
         destination port 25.  Hence,</p>

        <code># echo "block in quick from 1.2.3.4 to any" | pfctl -a spam -f -</code>

      <p>will only block connections from 1.2.3.4 to port 25.</p>

      <p>Anchors may end with the asterisk (`*') character, which signifies that
         all anchors attached at that point should be evaluated in the alphabetical 
         ordering of their anchor name.  For example,</p>

        <code>anchor "spam/*"</code>

      <p>will evaluate each rule in each anchor attached to the spam anchor.  Note
         that it will only evaluate anchors that are directly attached to the spam
         anchor, and will not descend to evaluate anchors recursively.</p>

      <p>Since anchors are evaluated relative to the anchor in which they are contained, 
         there is a mechanism for accessing the parent and ancestor anchors of a given 
         anchor.  Similar to file system path name resolution, if the sequence ``..'' 
         appears as an anchor path component, the parent anchor of the current anchor 
         in the path evaluation at that point will become the new current anchor. As an 
         example, consider the following:</p>

<pre>
# echo ' anchor "spam/allowed" ' | pfctl -f -
# echo -e ' anchor "../banned" \n pass' | pfctl -a spam/allowed -f -
</pre>

      <p>Evaluation of the main ruleset will lead into the spam/allowed anchor,
         which will evaluate the rules in the spam/banned anchor, if any, before
         finally evaluating the pass rule.</p>

      <p>Since the parser specification for anchor names is a string, any reference 
         to an anchor name containing solidus (`/') characters will require double 
         quote (`"') characters around the anchor name.</p>
    </td>
  </tr>
</table>

