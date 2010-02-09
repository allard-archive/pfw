<table class="manual" id="basic">
  <caption>macros</caption>
  <tr>
    <th>Statement</th>
    <th>Description</th>
  </tr>
  <tr>
    <td colspan="2">
      <p>Much like cpp(1) or m4(1), macros can be defined that will later be expanded 
         in context.  Macro names must start with a letter, and may contain
         letters, digits and underscores.  Macro names may not be reserved words
         (for example pass, in, out).  Macros are not expanded inside quotes.</p>

      <p>For example,</p>

<pre>
ext_if = "kue0"
all_ifs = "{" $ext_if lo0 "}"
pass out on $ext_if from any to any keep state
pass in  on $ext_if proto tcp from any to any port 25 keep state
</pre>
    </td>
  </tr>
</table>
