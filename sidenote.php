<?

$partNum = null;
$stepNum = null;
$subStepNum = null;
$currentStepName = null;
$currentStepTitle = null;
$currentPartTitle = null;

function pre($body) {
    echo prestr($body);
}

function prestr($body) {
    $trimmed = trim($body);
    $text = <<<html
<pre class="language-javascript prejs"><code>$trimmed</code></pre>
html;
    return $text;
}

function prehtml($body) {
    echo prestrhtml($body);
}

function prestrhtml($body) {
    $trimmed = htmlspecialchars(trim($body));
    $text = <<<html
<pre class="language-html prejs prehtml"><code>$trimmed</code></pre>
html;
    return $text;
}

function precss($body) {
    echo prestrcss($body);
}

function prestrcss($body) {
    $trimmed = trim($body);
    $text = <<<html
<pre class="language-css prejs"><code>$trimmed</code></pre>
html;
    return $text;
}

function replshot($filename) {
    $fullFilename = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $filename;  
    $width = getimagesize($fullFilename)[0] / 2;
    $height = getimagesize($fullFilename)[1] / 2;
    $text = <<<html
<div class="screenshot">
    <img class="screenshot-repl" width="$width" height="$height" src="img/$filename">
</div>
html;
    echo $text;
}

function screenshot($filename, $prefix="") {
    $fullFilename = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $filename;  
    $width = getimagesize($fullFilename)[0] / 2;
    $height = getimagesize($fullFilename)[1] / 2;
    $text = <<<html
<div class="screenshot">
    <img width="$width" height="$height" src="{$prefix}img/$filename">
</div>
html;
    echo $text;
}

function stepheader($stepName, $stepTitle) {
    global $stepNum;
    global $currentStepName;
    global $currentStepTitle;
    global $subStepNum;
    global $currentPartTitle;
    global $partNum;

    $currentStepName = $stepName;
    $currentStepTitle = $stepTitle;
    $stepNum++;
    $subStepNum = 0;

    $begin = "<div class='padded note' data-note-name='$stepName' data-note-type='step' >";
    $thisPartHeader = "";
    if ($currentPartTitle != null) {
        $thisPartHeader = "<h2 class='part-title-in-note'>Chapter $partNum. $currentPartTitle</h2>";
        $currentPartTitle = null;
    }
    $text = <<<html
    $begin
    $thisPartHeader
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
    <h2>Step $stepNum. $stepTitle</h2>
html;

    echo $text;
}

// Only works if $noteName is defined later on (not before)
function steplink($noteName) {
    echo "<a href='#$noteName' data-link-to-step-name='$noteName'>Step</a>";
}

function substepref($parent, $noteName, $substepNum) {
    echo "<a href='#$noteName' data-link-to-step-name='$parent' data-substep-num='$substepNum'>SubStep</a>";
}

function substepheader($noteName, $substepTitle) {
    global $stepNum;
    global $subStepNum;
    $subStepNum++;
    $thisSubStepLink = "<li><a href=\"#$noteName\">Step $stepNum.$subStepNum. $substepTitle</a></li>";
    $text = <<<html
<div class='padded note' data-note-name='$noteName'>
    <div class='close-button'>×</div>
    <h2>Step $stepNum.$subStepNum. $substepTitle</h2>

    <script>
        $('[data-step-header="$stepNum"]')
            .append('$thisSubStepLink');
    </script>
html;

    echo $text;
}

function noteheader($noteName) {
    global $stepNum;

    $text = <<<html
<div class='padded note' data-note-name='$noteName'>
    <div class='close-button'>×</div>
    <h2>Note</h2>
html;

    echo $text;
}

function stepoverview() {
    global $stepNum;
    global $currentStepName;
    global $currentStepTitle;

    $text = <<<html
    <ul data-step-header='$stepNum'></ul>
</div>
<script>
ORDERING.push('$currentStepName');

$(SNIPPETS_TABLE).append(`<div class='snippet-div'>
        <div><a href='#$currentStepName'>Step $stepNum. $currentStepTitle</a></div>
        <!--<div>
html;

    echo $text;
}

function stepfooter() {
    $text = <<<html
        </div>-->
    </div>`);
</script>
html;
    echo $text;
}

function substepfooter() {
    $text = <<<html
</div>
html;
    echo $text;
}

function notefooter() {
    $text = <<<html
</div>
html;
    echo $text;
}

function partheader($title) {
    global $partNum;
    global $currentPartTitle;

    $currentPartTitle = $title;
    $n = ++$partNum;
    $text = <<<html
<script>
SNIPPETS_TABLE = '#snippets-table-part-$n';

$(SNIPPETS_TABLE_WRAPPER).append(`<p class='snippets-part-number'>Chapter $n. $title </p>`);

$(SNIPPETS_TABLE_WRAPPER).append(`<div id='\${SNIPPETS_TABLE.substr(1)}' class='snippets-table'>
</div>`);

</script>
html;
    echo $text;
}

function substepLink($noteName, $subStepNum, $noteTitle) {
    global $stepNum;
    echo "<p><a href=\"#$noteName\">Step $stepNum.$subStepNum. $noteTitle</a></p>";
}

function code($line) {
    echo codestr($line);
}

function codestr($line) {
    return "<code class='language-javascript'>$line</code>";
}

function codenone($line) {
    echo codestrnone($line);
}

function codestrnone($line) {
    return "<code class='language-none'>$line</code>";
}


function codehtml($line) {
    echo codestrhtml($line);
}

function codestrhtml($line) {
    $x = htmlspecialchars($line);
    return "<code class='language-html'>$x</code>";
}

function codecss($line) {
    echo codestrhtml($line);
}

function codestrcss($line) {
    return "<code class='language-css'>$line</code>";
}


?>