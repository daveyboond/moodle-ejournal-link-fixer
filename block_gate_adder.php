<?php

class block_gate_adder extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_gate_adder');
    }
	
    public function preferred_width() {
        return 210;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function get_content() {

        global $COURSE;

        // Use the manageactivities capability to restrict block to editors only
        $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
        if (!has_capability('moodle/course:manageactivities', $context)) {
            return '';
        }

        if ($this->content !== null) {
            return $this->content;
        }

        // initalise block content object
        $this->content = new stdClass();

        $this->content->text = <<<EOT
<script>
function addDOI () {
  // Replace function at end trims whitespace
  var doiurl = document.getElementById('doiurl').value.replace(/^\s+|\s+$/g,"");
  var outfield = document.getElementById('output');
  var gate = "https://gate2.library.lse.ac.uk/login?url=";
  var resolver = "http://dx.doi.org/";
  var regate = /gate2\.library\.lse\.ac\.uk/;
  var redoi = /10\..+\/.+/;
  var reurl = /^http/;
  if (doiurl.match(regate)) {
    outfield.value = "Gate already present";
  } else if (doiurl.match(reurl)) {
    outfield.value = gate + doiurl;
  } else if (doiurl.match(redoi)) {
    outfield.value = gate + resolver + doiurl;
  } else {
    outfield.value = "Not recognised";
  }
  outfield.focus();
  outfield.select();
  return;
}
</script>
<p>Only visible to editors.</p>
<p>Enter the stable URL or the DOI for a journal article,
and click <em>Add</em> to add the required prefixes.</p>
<p>DOI/URL:<br /><input type="text" name="doiurl" id="doiurl" size="15" /> <input type="button" value="Add" 
onClick="javascript:addDOI();" /></p>
<p>Result: <input type="text" name="output" id="output" size="15" readonly="readonly" /></p>
<p><a href="http://clt.lse.ac.uk/digital-and-information-literacy/ejournal-articles.php" target="_blank">Help on linking to e-journals</a></p>
<hr />
<p><a href="http://www.crossref.org/SimpleTextQuery/" target="_blank">Citation-to-DOI lookup</a><br /><strong>Note:</strong> Paste the DOI returned by this lookup into the field above.</p>
EOT;


        $this->content->footer = '';

        return $this->content;

    }

}