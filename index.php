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

function screenshot($filename) {
    $fullFilename = $_SERVER['DOCUMENT_ROOT'] . '/img/' . $filename;  
    $width = getimagesize($fullFilename)[0] / 2;
    $height = getimagesize($fullFilename)[1] / 2;
    $text = <<<html
<div class="screenshot">
    <img width="$width" height="$height" src="img/$filename">
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

    $begin = "<div class='padded note' data-note-name='$stepName'>";
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
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
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
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
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
        <div>
html;

    echo $text;
}

function stepfooter() {
    $text = <<<html
        </div>
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

?><!doctype html>
<html lang='en'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>JavaScript: Overview &amp; Details</title>
        <link rel='stylesheet' href='style/codemirror.css'>
        <link rel='stylesheet' href='style/solarized.css'>
        <link href='style/bootstrap.css' rel='stylesheet'>
        <link href='style/prism.css' rel='stylesheet'>
        <link href='style/repl-dark.css' rel='stylesheet'>
        <link href='style/book.css' rel='stylesheet'>
        <link href='style/sidenote.css' rel='stylesheet'>
        <script src='js/codemirror.js'></script>
        <script src='js/javascript.js'></script>
        <script src='js/jquery-2.2.4.min.js'></script>
        <script src='js/prism.js'></script>
        <script src='js/popper.min.js'></script>
        <script src='js/bootstrap.js'></script>
        <script src='js/my/repl.js'></script>
        <script src='js/my/sidenote.js'></script>
        <script src='js/my/repl-sidenote.js'></script>
        <script src='js/my/book.js'></script>
    </head>
    <body>
        <main>
<div id='sidenote'>
<div id='repl-console-wrapper'>
<div class='note repl-note' id='repl-console'>
    <h4>Repl</h4>
    <div id='repl'></div>
</div>
</div>

<div data-column='0' class='column'>
    <div data-note-name='note-root' class='note'>
        <div class='title-page'>
            <h1 class='title'>JavaScript</h1>
            <div class='subtitle'>Overview &amp; Details</div>
            <div class='subtitle'>Volume 1</div>
            <div class='author'>Michael N. Gagnon, 2019</div>
        </div>
        <div id="spacer" style="width: 1; height: 1px;">
        </div>
        <div class='padded'>
            <h2>Preface</h2>

            <p>This book represents the first volume in a series of three. This series, and this book in particular, is for you if you&rsquo;ve never programmed before and you would like to learn JavaScript. By the conclusion of this first book, you will have learned 100% of the JavaScript you need to know in order to write a chess game, complete with an AI. And, by the conclusion of the third book, we will have programmed the game and AI, together.</p>

            <h3>Shallow-deep learning</h3>

            <p>JavaScript is a gnarly language. There are many nooks and crannies
            that book authors can either explore or ignore. Consequently,
             JavaScript books tend to either skip over the nitty-gritty details of the language, or they tediously expound upon the minutiae.</p>

            <p>This book presents the big picture, along with the option to ignore, skim, or scrutinize many of the finer details of JavaScript. The way I&rsquo;ve managed to pull this off, is that I&rsquo;ve made extensive use of sidenotes.</p>

            <p>
                If you&rsquo;re inclined, feel free to enrich your understanding of JavaScript by diving deep into the sidenotes. Or, feel free to stay shallow, which is all you need for us to build a chess AI together.
            </p>

            <h3>Lazy-eager learning</h3>

            <p>Broadly speaking, there are three approaches to learning: <i>lazy</i> learning, <i>eager</i> learning, and a hybrid approach&mdash;<i>lazy-eager</i> learning.</p>

            <p>With the lazy approach, you skip straight to the last page of your textbook, and work backwards from there.</p>

            <p>With the eager approach, you refuse to move on to the next step, until you&rsquo;ve convinced yourself that you&rsquo;ve mastered the current step.</p>

            <p>But, with the hybrid lazy-eager approach, perhaps you skip a few steps and work backwards from there. Or, perhaps you skim the material until you reach a point where you&rsquo;re confused, and then go back and study the material you have already skimmed over.</p>

            <p>I think the lazy-eager approach might be a great way to learn JavaScript, and I wrote this book for lazy-eager learning.</p>

        </div>

        <h1 class='part-title'><a name='snippets'>Contents</a></h1>

        <p class='padded'>For each of the <span id='num-steps'></span> steps of this book, the table of contents contains a link to that step, as well as a short snippet of code from that step.</p>

        <div id='snippets' class='padded' >
        </div>

    </div>
</div> <!-- end data-column='0' -->
</div> <!-- end #sidenote -->

<div id='staging-area'>
<script>
var ORDERING = [];

var SNIPPETS = '#snippets';
var SNIPPETS_TABLE_WRAPPER = '#snippets-tables-wrapper';
var SNIPPETS_TABLE = undefined;

$(SNIPPETS).append(`<div id='${SNIPPETS_TABLE_WRAPPER.substr(1)}'></div>`);

</script>

<?
    $partNum = 0;
    $stepNum = 0;
?>

<?
################################################################################
partheader('Expressions'); #####################################################
################################################################################
?>

<? #############################################################################
stepheader('note-repl', 'Repl'); ?>
<? $stepRepl = $stepNum ?>

    <p>The right side of the window contains a &ldquo;repl.&rdquo;
        Click the dark-colored area, just to the right of the green dollar sign. Then type in <code class='language-javascript'>1 + 2</code>, and press enter. At this point,
    your repl should look like this:</p>

    <p>What happened here? Your browser executed the statement <code class='language-javascript'>1 + 2</code>,
        and then the repl displayed the result <code class='language-javascript'>3</code>.
     </p>

    <p>The <i>repl</i> allows you to experiment with coding. The way it works, is you type in one (or more)
    lines of code in your repl, and press enter. Then, your browser
    will &ldquo;execute&rdquo; your code, and then the repl will display the result of executing your code.
    Repeat.
    </p>

    <p>At any time, you can click the &ldquo;clear&rdquo; button to restart your repl session. And, I will explain the &ldquo;multiline input&rdquo; button later on.</p>
<? stepoverview(); ?>
    <code class='language-javascript no-left-margin'>1 + 2</code> produces <code class='language-javascript'>3</code>, in the repl

<? stepfooter(); ?>

<? #############################################################################
stepheader('note-values', 'Numbers and values'); ?>
    <p>In JavaScript, the number <code class='language-javascript'>1</code> is a &ldquo;value.&rdquo;
    The number <code class='language-javascript'>2</code> is also a value, and so is the number <code class='language-javascript'>105.72</code>, and so on. Every number is a value, but there are also values beyond just numbers, as we&rsquo;ll see quite often in later steps. For now, though, we&rsquo;ll just focus on number values.</p>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">1</code>, <code class="language-javascript">2</code>, and <code class="language-javascript">105.72</code> are values
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-values-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class='language-javascript'>3.7</code>?</li>
        <li>What happens if you execute <code class='language-javascript'>2938</code>?</li>
        <li>What happens if you execute <code class='language-javascript'>7349902384908234</code>?</li>
    </ol>
<? substepfooter(); ?>

<? #############################################################################
substepheader('note-num-oddities', 'Number oddities'); ?>
    <p>JavaScript numbers have several oddities. For example:</p>

    <ol>
        <li><code class="language-javascript">9999999999999999</code> resolves to <code class="language-javascript">10000000000000000</code>. <a href="#note-int-too-big">Note</a></li>
        <li><code class="language-javascript">90837408234902839048290348920</code> resolves to <code class="language-javascript">9.083740823490283e+28</code>. <a href="#note-big-num">Note</a></li>
        <li><code class="language-javascript">1.7e+308</code> resolves to <code class="language-javascript">1.7e+308</code>. <a href="#note-biggest-num">Note</a></li>
        <li><code class="language-javascript">1.8e+308</code> resolves to <code class="language-javascript">Infinity</code>. <a href="#note-num-too-big">Note</a></li>
    </ol>
<? substepfooter(); ?>

<? #############################################################################
noteheader('note-int-too-big'); ?>
    <p>When you enter the number <code class="language-javascript">9999999999999999</code> into the repl,
        the repl responds with <code class="language-javascript">10000000000000000</code>, because <code class="language-javascript">9999999999999999</code> is larger 
        than the largest <a href="#note-int">integer</a> that JavaScript can handle. Once you
        go beyond the max, integers become approximate. Also,
        decimal numbers in JavaScript are generally approximate.
    </p>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-int'); ?>
    <p>An integer is a whole number.</p>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-big-num'); ?>
    <p>When you enter the number <code class="language-javascript">90837408234902839048290348920</code> into the repl,
        the repl responds with <code class="language-javascript">9.083740823490283e+28</code>, which is equivalent to scientific notation 9.083740823490283 &times; 10<sup>28</sup>.
    </p>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-biggest-num'); ?>
    <p>When you enter the number <code class="language-javascript">1.7e+308</code> into the repl,
        the repl responds with <code class="language-javascript">1.7e+308</code>, which is equivalent to scientific notation 1.7 &times; 10<sup>308</sup>. This is a stupefyingly large number. It is far larger than the number of atoms in the universe. This number is pretty close to the largest number JavaScript can handle. The largest number JavaScript can handle is: <code class="language-javascript">1.7976931348623157e+308</code>.
    </p>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-num-too-big'); ?>
    <p>When you enter the number <code class="language-javascript">1.8e+308</code> into the repl,
        the repl responds with <code class="language-javascript">Infinity</code>, because <code class="language-javascript">1.8e+308</code> is larger than JavaScript can handle. The largest number JavaScript can handle is close to <code class="language-javascript">1.7976931348623157e+308</code>.
    </p>
<? notefooter(); ?>

<? #############################################################################
stepheader('note-expressions', 'Expressions'); ?>
    <p><code class='language-javascript'>1 + 2</code> is not a value; rather, <code class='language-javascript'>1 + 2</code> is a JavaScript &ldquo;expression.&rdquo; An <i>expression</i>, is anything in JavaScript that &ldquo;resolves&rdquo; to a value. In this case, <code class='language-javascript'>1 + 2</code> <i>resolves</i> to the value <code class='language-javascript'>3</code>.
            </p>

    <p>To be precise, every value is also an expression. To give a concrete example, the number <code class='language-javascript'>4</code> is a value, and the value <code class='language-javascript'>4</code> is also an expression&mdash;albeit, an expression that resolves to the value <code class='language-javascript'>4</code>.</p>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">1 + 2</code> is an expression that resolves to the value <code class="language-javascript">3</code>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-binary', 'Binary numeric operators'); ?>
<? $stepArithmetic = $stepNum ?>

    <p>A <i>binary numeric operator</i> combines two numeric values to resolve to a new value. For example, <code class="language-javascript">+</code> is a binary numeric operator that combines two values, via addition. We already observed in <a href="#note-repl">Step <? echo $stepRepl ?></a> that the <code class="language-javascript">+</code> operator can combine two numeric values, to resolve to a new value.</p>


    <p>There are several binary numeric operators in JavaScript.
    For instance, besides addition, JavaScript allows you to perform subtraction with the <code class="language-javascript">-</code> operator, multiplication with the <code class="language-javascript">*</code> operator, and division with the <code class="language-javascript">/</code> operator.</p>

<? stepoverview() ?>
    <code class="language-javascript no-left-margin">+</code>, <code class="language-javascript">-</code>, <code class="language-javascript">*</code>, and <code class="language-javascript">/</code>are binary numeric operators
<? stepfooter() ?>

<? #############################################################################
substepheader('note-binary-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class="language-javascript">2 * 3</code>?</li>
        <li>What happens if you execute <code class="language-javascript">1 / 2</code>?</li>
    </ol>
<? substepfooter() ?>

<? #############################################################################
substepheader('note-bonus-arithmetic', 'Arithmetic oddities'); ?>
    <p>JavaScript arithmetic has several oddities.</p>

    <ol>
        <li><code class="language-javascript">0.1 + 0.2</code> resolves to <code class="language-javascript">0.30000000000000004</code>. <a href="#note-zero-point-three">Note</a></li>
        <li><code class="language-javascript">1 / 0</code> resolves to <code class="language-javascript">Infinity</code>. <a href="#note-one-div-zero">Note</a></li>
        <li><code class="language-javascript">0 / 0</code> resolves to <code class="language-javascript">NaN</code>. <a href="#note-zero-div-zero">Note</a></li>
    </ol>
<? substepfooter() ?> 

<? #############################################################################
noteheader('note-zero-point-three'); ?>
    <p>
        In JavaScript, the expression <code class="language-javascript">0.1 + 0.2</code> yields the value <code class="language-javascript">0.30000000000000004</code>, which is merely an approximation of the sum of 0.1 and 0.2. In general, arithmetic with decimal numbers in JavaScript merely produces approximately correct results.
    </p>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-one-div-zero'); ?>
    <p>In JavaScript, <code class="language-javascript">1 / 0</code> yields the value <code class="language-javascript">Infinity</code>, which represents numbers larger than what JavaScript can represent otherwise.</p>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-zero-div-zero'); ?>
    <p>In JavaScript, <code class="language-javascript">0 / 0</code> yields the value <code class="language-javascript">NaN</code>, which represents &ldquo;not a number&rdquo;&mdash;numbers that aren&rsquo;t really numbers.</p>
<? notefooter(); ?>

<? #############################################################################
substepheader('note-more-op', 'More numeric operators'); ?>
    <p>There are more operators beyond just <code class="language-javascript">+</code>, <code class="language-javascript">-</code>, <code class="language-javascript">*</code>, and <code class="language-javascript">/</code>.</p>

    <p>The &ldquo;modulo&rdquo;
    operator, <code class="language-javascript">%</code>, yields
    the <i>remainder</i> of division.  For example, <code class="language-javascript">9 % 5</code> resolves to <code class="language-javascript">4</code>, because 9 divided by 5 equals
    1, with a remainder of 4.

    <p>The &ldquo;unary numeric negation&rdquo; operator will take
        a positive number and make it negative, or take a negative 
    number and make it positive. Consider a few examples:</p>

    <div class="table-format">
    <table>
        <tr>
            <td><code class="language-javascript">-1</code></td>
            <td>resolves to <code class="language-javascript">-1</code></td>
        </tr>
        <tr>
            <td><code class="language-javascript">-(-1)</code></td>
            <td>resolves to <code class="language-javascript">1</code>
            </td>
        </tr>
        <tr>
            <td><code class="language-javascript">-(3 - 5)</code></td>
            <td>resolves to <code class="language-javascript">2</code>
            </td>
        </tr>
    </table>
    </div>

    <p>The <i>unary</i> numeric negation operator is not a <i>binary</i> numeric
    operator, because <i>binary</i> operators operate on two values, whereas
    <i>unary</i> operators operate on single values.
    </p>
<? substepfooter() ?> 

<? #############################################################################
stepheader('note-paren', 'Parentheses'); ?>

    <p>JavaScript allows you to group expressions using parentheses. For example:</p>

    <ol>
        <li><code class="language-javascript">(1 + 2) / 3</code> resolves to <code class="language-javascript">1</code></li>
        <li><code class="language-javascript">2 * (8 - 6)</code> resolves to <code class="language-javascript">4</code></li>
        <li><code class="language-javascript">2 * ((3  - 2) * 4)</code> resolves to <code class="language-javascript">8</code></li>
    </ol>

<? stepoverview() ?>
    <code class="language-javascript no-left-margin">2 * (8 - 6)</code> resolves to <code class="language-javascript">4</code>
<? stepfooter() ?>

<? #############################################################################
stepheader('note-precedence', 'Operator precedence'); ?>

 
    <p>On the repl, try out: <code class="language-javascript">1 + 2 * 4</code>. JavaScript is smart enough to know to perform the multiplication first. Essentially, JavaScript converts <code class="language-javascript">1 + 2 * 4</code> into <code class="language-javascript">1 + (2 * 4)</code>, then your browser executes it.</p>
    
    <p>In general, the JavaScript language specifies which operators have &ldquo;higher precedence&rdquo; over which other operators.
    For example, the <code class="language-javascript">*</code> operator has higher precedence compared to the <code class="language-javascript">+</code> operator.
    When I say that one operator has higher precedence compared to another operator, I am  saying that the JavaScript automatically puts parentheses around the higher-precedence operations.</p>

    <p>Parentheses have the highest precedence;
    that is, parentheses have the final say in the ordering of operations.</p>
<? stepoverview() ?>
    <code class="language-javascript no-left-margin">1 + 2 * 4</code> resolves to <code class="language-javascript">9</code>
<? stepfooter() ?>

<? #############################################################################
substepheader('note-precedence-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class="language-javascript">1 / 2 + 1 / 2</code>?</li>
        <li>What happens if you execute <code class="language-javascript">2 * 3 - 2 * 4</code>?</li>
    </ol>
<? substepfooter() ?>

<? #############################################################################
stepheader('note-ptable', 'Precedence tables'); ?>
    <p>When explaining the precedence of operations for any particular programming language, it is common to present &ldquo;precedence tables&rdquo;&mdash;tables that explain which operators have higher precedence compared to other operators.</p>

    <p>For instance, the precedence table below shows that parentheses have the highest precedence, and multiplication and division have a higher precedence than addition and subtraction.</p>

    <table class="precedence-table">
        <tr class="header-row">
            <th>Precedence</th>
            <th>Operator type</th>
            <th>Operators</th>
        </tr>
        <tr>
            <td>19</td>
            <td>Parentheses</td>
            <td><code class="language-javascript">( ... )</code></td>   
        </tr>
        <tr>
            <td>14</td>
            <td>Multiplication and division</td>
            <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
        </tr>
        <tr>
            <td>13</td>
            <td>Addition and subtraction</td>
            <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
        </tr>
    </table>

    <p>Throughout this book, we will encounter many different types of operators. Whenever I introduce a new operator, I present a new precedence table that shows the precedence of the new operator, relative
    to the operators we have already seen.</p>

    <p>I don&rsquo;t think you will need to explicitly memorize the operator precedence tables. Rather, I suspect you will be able to cultivate an intuitive understanding of operator precedence from studying and understanding examples and exercises.</p>
<? stepoverview(); ?>
    <table class="precedence-table overview-table">
        <tr class="header-row">
            <th>Precedence</th>
            <th>Operator type</th>
            <th>Operators</th>
        </tr>
        <tr>
            <td>19</td>
            <td>Parentheses</td>
            <td><code class="language-javascript">( ... )</code></td>   
        </tr>
        <tr>
            <td>14</td>
            <td>Multiplication and division</td>
            <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
        </tr>
        <tr>
            <td>13</td>
            <td>Addition and subtraction</td>
            <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
        </tr>
    </table>
<? stepfooter(); ?>

<?
################################################################################
partheader('Types'); ###########################################################
################################################################################
?>


<? #############################################################################
stepheader('note-types', 'Types'); ?>

    <p>Every expression has a &ldquo;type.&rdquo; For instance, the value <code class="language-javascript">1</code> has the type &ldquo;number,&rdquo; and the expression <code class="language-javascript">(1 + 2) * 4</code> has the type &ldquo;number&rdquo; as well. In casual conversational programming terminology, we probably wouldn&rsquo;t say: &ldquo;the value <code class="language-javascript">1</code> has the type number.&rdquo; Rather, we would just say: &ldquo;<code class="language-javascript">1</code> is a number.&rdquo;
            </p>

    <p>So far, we have only encountered the number type. In the next step though, <? steplink('note-strings') ?>, we will encounter the &ldquo;string&rdquo; type. Then, in the following step, <? steplink('note-bool') ?>, we will encounter the &ldquo;boolean&rdquo; type.</p>

<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">1</code> has the type number, and <code class="language-javascript">(1 + 2) * 4</code> has type number, as well
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-strings', 'Strings'); ?>
    <p>A <i>string</i> value is a sequence of characters. To define a string, just wrap a sequence of characters in quotes, like so: <code class="language-javascript">'Hello, World!'</code>. You can also use double quotes if you like. For instance: the string <code class="language-javascript">"Hello, World!"</code> is identical to
    <code class="language-javascript">'Hello, World!'</code>.
    </p>

    <p>You can merge two strings together (i.e. <i>concatenate</i> two strings together), with the <code class="language-javascript">+</code> operator. For example, <code class="language-javascript">'Hello' + 'World!'</code> would resolve to <code class="language-javascript">'HelloWorld!'</code> . Try it out on the repl.
    </p>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">'Hello' + '_' + 'World!'</code> resolves to <code class="language-javascript">'Hello_World!'</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-strings-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class="language-javascript">'Hello' + '_' + 'World!'</code>?</li>
        <li>TODO: more exercises</li>
    </ol>
<? substepfooter() ?>

<? #############################################################################
substepheader('note-single-v-double', 'Single quotes vs. double quotes'); ?>
    <p>You can use single quotes, or double quotes when defining strings in JavaScript.
        They work pretty much the same way.
    </p>

    <p>Single quotes are nice because you don&rsquo;t need
    to press the shift key.</p>

    <p>Also, if you use single quotes, then you can include
        double quotes in your string easily. For example, while <code class="language-javascript">'abc Hello 123'</code> resolves to the string <i style='white-space: nowrap;'>abc Hello 123</i>, the string <code class="language-javascript">'abc "Hello" 123'</code> resolves to the string <i style='white-space: nowrap;'>123 "Hello" 123</i>.
    </p>

    <p>Similarly, if you use double quotes, then you can include
        single quotes in your string easily. For example, the string <code class="language-javascript">"abc 'Hello' 123"</code> resolves to the string <i style='white-space: nowrap;'>123 'Hello' 123</i>.
    </p>
<? substepfooter() ?>

<? #############################################################################
substepheader('note-str-num', 'Strings and numbers'); ?>
    <p>When you add a string plus a number, the number gets converted to a string, and then the two strings are concatenated together. For example, <code class="language-javascript">'Hello' + 5</code> resolves to <code class="language-javascript">'Hello5'</code>
    </p>

    <p>When you multiply a string times a number, the result is <code class="language-javascript">NaN</code>. For example,
        <code class="language-javascript">"Hello" * 5</code> resolves to <code class="language-javascript">NaN</code>
    </p>
<? substepfooter() ?>

<? #############################################################################
stepheader('note-bool', 'Boolean values and expressions'); ?>

    <p>A <i>boolean value </i>is a value that is either <code class="language-javascript">true</code> or <code class="language-javascript">false</code> (<code class="language-javascript">true</code> and <code class="language-javascript">false</code> are values of type <code class="language-javascript">boolean</code>).</p>

    <p>Similarly, a <i>boolean expression</i> is an expression that resolves to either <code class="language-javascript">true</code> or <code class="language-javascript">false</code>. For example, <code class="language-javascript">1 &lt; 2</code> is a boolean expression that resolves to <code class="language-javascript">true</code>, because <code class="language-javascript">1</code> is less than <code class="language-javascript">2</code>.</p>

    <p>As another example, <code class="language-javascript">1 &gt; 2</code> is a boolean expression that resolves to <code class="language-javascript">false</code>, because <code class="language-javascript">1</code> is not greater than <code class="language-javascript">2</code>.</p>
<? stepoverview(); ?>
   <code class="language-javascript no-left-margin">1 < 2</code> resolves to <code class="language-javascript">true</code>, and <code class="language-javascript">1 > 2</code> resolves to <code class="language-javascript">false</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-bool-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class="language-javascript">true</code>?</li>
        <li>What happens if you execute <code class="language-javascript">false</code>?</li>
        <li>What happens if you execute <code class="language-javascript">1 &lt; 2</code>?</li>
        <li>What happens if you execute <code class="language-javascript">1 &gt; 2</code>?</li>
    </ol>
<? substepfooter() ?>

<?
################################################################################
partheader('Relational and equality operators'); ###############################
################################################################################
?>



<? #############################################################################
stepheader('note-compare', 'Relational operators'); ?>


            <p>There are several &ldquo;relational operators&rdquo; that compare numeric values, and then resolve to boolean values. For example, the greater-than operator, <code class="language-javascript">&gt;</code>, is a relational operator that compares two numeric values, then resolves to a boolean value. To give a concrete example, when JavaScript executes <code class="language-javascript">1 &gt; 2</code>, it compares <code class="language-javascript">1</code> and <code class="language-javascript">2</code>, determines that <code class="language-javascript">1</code> is not actually greater than <code class="language-javascript">2</code>, so it resolves the expression to the value <code class="language-javascript">false</code>.</p>

            <p>These comparison operators include: less-than, <code class="language-javascript">&lt;</code>, greater-than, <code class="language-javascript">&gt;</code>, less-than-or-equal-to, <code class="language-javascript">&lt;=</code>, and greater-than-or-equal-to<code class="language-javascript">&gt;=</code>.

            <p>The following list explains how each of these operators behaves:</p>

            <div class="table-format">
            <table class="no-wrap">
                <tr>
                    <td>1. </td>
                    <td><code class="language-javascript">a &lt; b</code>
                    </td>
                    <td>resolves to <code class="language-javascript">true</code> </td>
                    <td>if <code class="language-javascript">a</code> is less than <code class="language-javascript">b</code></td>
                </tr>
                <tr>
                    <td>2. </td>
                    <td><code class="language-javascript">a &lt;= b</code></td>
                    <td>resolves to <code class="language-javascript">true</code> </td>
                    <td>if <code class="language-javascript">a</code> is less than or equal to <code class="language-javascript">b</code></td>
                </tr>
                <tr>
                    <td>3. </td>
                    <td><code class="language-javascript">a &gt; b</code> </td>
                    <td> resolves to <code class="language-javascript">true</code> </td>
                    <td>if <code class="language-javascript">a</code> is greater than <code class="language-javascript">b</code></td>
                </tr>
                <tr>
                    <td>4. </td>
                    <td><code class="language-javascript">a &gt;= b</code> </td>
                    <td> resolves to <code class="language-javascript">true</code> </td>
                    <td>if <code class="language-javascript">a</code> is greater than or equal to <code class="language-javascript">b</code></td>
                </tr>
            </table>
            </div>

            <p>In each of the above four behavior descriptions, the expression resolves to <code class="language-javascript">false</code> if the expression does not resolve to <code class="language-javascript">true</code>

            <p>Here are a few examples of relational operators
            in action:</p>

            <div class="table-format">
            <table class="no-wrap">
                <tr>
                    <td>1. </td>
                    <td><code class="language-javascript">5 &lt; 4</code></td>
                    <td>resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td>2. </td>
                    <td><code class="language-javascript">5 &lt; 5</code></td>
                    <td>resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td>3. </td>
                    <td><code class="language-javascript">5 &lt; 6</code></td>
                    <td>resolves to <code class="language-javascript">true</code></td>
                </tr>
                <tr>
                    <td>4. </td>
                    <td><code class="language-javascript">5 &gt; 4</code></td>
                    <td>resolves to <code class="language-javascript">true</code></td>
                </tr>
                <tr>
                    <td>5. </td>
                    <td><code class="language-javascript">5 &gt; 5</code></td>
                    <td>resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td>6. </td>
                    <td><code class="language-javascript">5 &gt; 6</code></td>
                    <td>resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td>7. </td>
                    <td><code class="language-javascript">5 &lt;= 4</code></td>
                    <td>resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td>8. </td>
                    <td><code class="language-javascript">5 &lt;= 5</code></td>
                    <td>resolves to <code class="language-javascript">true</code></td>
                </tr>
                <tr>
                    <td>9. </td>
                    <td><code class="language-javascript">5 &lt;= 6</code></td>
                    <td>resolves to <code class="language-javascript">true</code></td>
                </tr>
                <tr>
                    <td>10. </td>
                    <td><code class="language-javascript">5 &gt;= 4</code></td>
                    <td>resolves to <code class="language-javascript">true</code></td>
                </tr>
                <tr>
                    <td>11. </td>
                    <td><code class="language-javascript">5 &gt;= 5</code></td>
                    <td>resolves to <code class="language-javascript">true</code></td>
                </tr>
                <tr>
                    <td>12. </td>
                    <td><code class="language-javascript">5 &gt;= 6</code></td>
                    <td>resolves to <code class="language-javascript">false</code></td>
                </tr>
            </table>
            </div>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">5 <= 4</code> resolves to <code class="language-javascript">false</code>, and <code class="language-javascript">5 <= 5</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-relational-non-strict', 'Type-converting relational operators'); ?>
    <p>What happens if you were to compare a number to a string, using
        a relational operator? For example, what happens when you execute <code class="language-javascript">6 < '7'</code>using the repl? How about <code class="language-javascript">8 < '7'</code>? If you were to try it out, you would find that <code class="language-javascript">6 < '7'</code> resolves to <code class="language-javascript">true</code>
        and <code class="language-javascript">8 < '7'</code> resolves to <code class="language-javascript">false</code>.
    </p>

    <p>What&rsquo;s going on here is that if you compare a number and a string, using a relational operator, then JavaScript automatically converts the string to a number, and then performs the comparison.</p>

    <p>Because relational operators convert types automatically,
        before comparison, they are also known as <i>type-converting relational operators</i>.</p>
<? substepfooter() ?>

<? #############################################################################
stepheader('note-compare-precedence', 'Relational operator precedence'); ?>
    <p>Numeric operators such as <code class="language-javascript">+</code> and <code class="language-javascript">*</code> have higher precedence than the relational operators. Thus, if JavaScript were to execute <code class="language-javascript">7 <= 5 + 1</code>, then JavaScript would perform the addition first (which resolves to <code class="language-javascript">6</code>), then it would compare <code class="language-javascript">7</code> and <code class="language-javascript">6</code>, which would cause the whole expression to resolve to <code class="language-javascript">false</code>, since <code class="language-javascript">6</code> is not greater than or equal to <code class="language-javascript">7</code>.</p>

    <table class="precedence-table">
        <tr class="header-row">
            <th>Precedence</th>
            <th>Operator type</th>
            <th>Operators</th>
        </tr>
        <tr>
            <td>19</td>
            <td>Parentheses</td>
            <td><code class="language-javascript">( ... )</code></td>   
        </tr>
        <tr>
            <td>14</td>
            <td>Multiplication and division</td>
            <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
        </tr>
        <tr>
            <td>13</td>
            <td>Addition and subtraction</td>
            <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
        </tr>
        <tr>
            <td>11</td>
            <td>Relational operators<br>
            </td>
            <td><code class="language-javascript">&gt;</code>, <code class="language-javascript">&gt;=</code>, <code class="language-javascript">&lt;</code>, <code class="language-javascript">&lt;=</code></td>   
        </tr>
    </table>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">7 <= 5 + 1</code> resolves to <code class="language-javascript">false</code>
<? stepfooter(); ?>
      
<? #############################################################################
substepheader('note-compare-p-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class="language-javascript">1/2 + 1/2 &gt;= 0.2 * 5</code>?</li>
        <li>What happens if you execute <code class="language-javascript">1/2 + 1/2 &lt;= 0.2 * 5 </code>?</li>
        <li>What happens if you execute <code class="language-javascript">(0.5 + 0.5) * 5 > 1 + 100</code>?</li>
        <li>What happens if you execute <code class="language-javascript">(0.5 + 0.5) * 5 < 1 + 100</code>?</li>
    </ol>
<? substepfooter() ?>              

<? #############################################################################
stepheader('note-equality', 'Equality operators'); ?>

    <p>There are several &ldquo;equality operators&rdquo; that compare values (numeric values and other types of values), and then resolve to boolean values. For example, the &ldquo;strict equality operator,&rdquo; <code class="language-javascript">===</code>, compares two values, then resolves to a boolean value. To give a concrete example,
    when JavaScript executes <code class="language-javascript">1 === 2</code> it compares <code class="language-javascript">1</code> and <code class="language-javascript">2</code> and determines that they are not the same value, so it resolves the expression to <code class="language-javascript">false</code>.
    </p>

    <p>The equality operators include strict equality, <code class="language-javascript">===</code>, and strict inequality, <code class="language-javascript">!==</code>.</p>

    <p>The following list explains how each of these operators behaves:</p>

    <div class="table-format">
    <table class="no-wrap">
        <tr>
            <td>1. </td>
            <td><code class="language-javascript">a === b</code> </td>
            <td> resolves to <code class="language-javascript">true</code> </td>
            <td>if <code class="language-javascript">a</code> is equal to <code class="language-javascript">b</code></td>
        </tr>
        <tr>
            <td>2. </td>
            <td><code class="language-javascript">a !== b</code></td>
            <td> resolves to <code class="language-javascript">true</code> </td>
            <td> if <code class="language-javascript">a</code> is NOT  equal to <code class="language-javascript">b</code></td>
        </tr>
    </table>
    </div>

    <p>In each of the above two behavior descriptions, the expression resolves to <code class="language-javascript">false</code> if the expression does not resolve to <code class="language-javascript">true</code>.</p>

    <p>Here are a few examples of equality operators in action, comparing numeric values:</p>
    <div class="table-format">
    <table>
        <tr>
            <td>1. </td>
            <td><code class="language-javascript">5 === 4</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
        <tr>
            <td>2. </td>
            <td><code class="language-javascript">5 === 5</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>3. </td>
            <td><code class="language-javascript">5 === 6</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
        <tr>
            <td>4. </td>
            <td><code class="language-javascript">5 !== 4</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>5. </td>
            <td><code class="language-javascript">5 !== 5</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
        <tr>
            <td>6. </td>
            <td><code class="language-javascript">5 !== 6</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
    </table>
    </div>

    <p>Here are a few more examples of equality operators in action, this time using boolean values:</p>
    <div class="table-format">
    <table>
        <tr>
            <td>1. </td>
            <td><code class="language-javascript">true === true</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>2. </td>
            <td><code class="language-javascript">true === false</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
        <tr>
            <td>3. </td>
            <td><code class="language-javascript">false === false</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>4. </td>
            <td><code class="language-javascript">true !== false</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>5. </td>
            <td><code class="language-javascript">true !== true</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
        <tr>
            <td>6. </td>
            <td><code class="language-javascript">false !== false</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
    </table>
    </div>

    <p>Here are yet some more examples of equality operators in action, this time using string values:</p>
    <div class="table-format">
    <table>
        <tr>
            <td>1. </td>
            <td><code class="language-javascript">'abc' === 'abc'</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>2. </td>
            <td><code class="language-javascript">'abc' === "abc"</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>3. </td>
            <td><code class="language-javascript">'abc' === 'ab'</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
        <tr>
            <td>4. </td>
            <td><code class="language-javascript">'abc' !== 'ab'</code></td>
            <td>resolves to <code class="language-javascript">true</code></td>
        </tr>
        <tr>
            <td>5. </td>
            <td><code class="language-javascript">'abc' !== 'abc'</code></td>
            <td>resolves to <code class="language-javascript">false</code></td>
        </tr>
    </table>
    </div>
<? stepoverview(); ?>
    <code class="language-javascript">5 === 5</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-comparison-oddities', 'Equality oddity'); ?>
    <p>In previous sidenotes (<? substepref('note-values', 'note-num-oddities', 2); ?> and <? substepref('note-binary', 'note-bonus-arithmetic', 2); ?>) we encountered the value of <code class="language-javascript">NaN</code>. You might expect <code class="language-javascript">NaN === NaN</code> to resolve to <code class="language-javascript">true</code>, but it actually resolves to <code class="language-javascript">false</code>.</p>
<? substepfooter() ?>

<? #############################################################################
substepheader('note-equality-non-strict', 'Type-converting equality operators'); ?>

    <p>As you might expect, the expression <code class="language-javascript">8 === '8'</code> resolves to <code class="language-javascript">false</code>, because <code class="language-javascript">8</code> and <code class="language-javascript">'8'</code> are different values: one is a number, and the other is a string.</p>

    <p> The <code class="language-javascript">===</code> operator is known as a &ldquo;strict equality operator,&rdquo; because it is strict in the sense that: the values must be
    of the same type for the values to be considered equal.
    </p>

    <p>But, there are also non-strict equality operators, known
    as &ldquo;type-converting equality operators.&rdquo; These operators convert the left-value and the right-value to the same type, and only then does JavaScript perform the comparison.</p>

    <p>For example, the <code class="language-javascript">==</code> operator is a type-converting equality operator. The expression <code class="language-javascript">8 == '8'</code> resolves to <code class="language-javascript">true</code>, even though <code class="language-javascript">8</code> is a number, and <code class="language-javascript">'8'</code> is a string, because JavaScript converts the string to a number, and only then performs a strict comparison.</p>

    <p>There is only one other type-converting equality operator: the <code class="language-javascript">!=</code> operator. To demonstrate how it works: <code class="language-javascript">1 != '1'</code> resolves to <code class="language-javascript">false</code>, because the <code class="language-javascript">'1'</code> is converted to a number, and then JavaScript uses strict comparison and determines that <code class="language-javascript">1</code> and <code class="language-javascript">1</code> are equal, therefore <code class="language-javascript">1 != '1'</code> resolves to <code class="language-javascript">false</code>. In contrast, <code class="language-javascript">1 !== '1'</code>
        resolves to <code class="language-javascript">true</code>, simply because the values are of different types.</p>
<? substepfooter() ?>

<? #############################################################################
stepheader('note-eq-precedence', 'Equality operator precedence'); ?>
    <p>The equality operators have the lowest precedence we have
    seen so far. Numeric operators such as <code class="language-javascript">+</code> and <code class="language-javascript">*</code> have higher precedence than the equality operators. Thus, if JavaScript where to execute <code class="language-javascript">1 + 3 * 2 === 7</code>, then JavaScript would perform the multiplication first (which resolves to <code class="language-javascript">6</code>), then it would add <code class="language-javascript">1</code> (which resolves to <code class="language-javascript">7</code>) then it would compare <code class="language-javascript">7</code> and <code class="language-javascript">7</code>, which would cause the whole expression to resolve to <code class="language-javascript">true</code>, since <code class="language-javascript">7</code> equals <code class="language-javascript">7</code>.</p>

    <table class="precedence-table">
        <tr class="header-row">
            <th>Precedence</th>
            <th>Operator type</th>
            <th>Operators</th>
        </tr>
        <tr>
            <td>19</td>
            <td>Parentheses</td>
            <td><code class="language-javascript">( ... )</code></td>   
        </tr>
        <tr>
            <td>14</td>
            <td>Multiplication and division</td>
            <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
        </tr>
        <tr>
            <td>13</td>
            <td>Addition and subtraction</td>
            <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
        </tr>
        <tr>
            <td>11</td>
            <td>Relational operators<br>
            </td>
            <td><code class="language-javascript">&gt;</code>, <code class="language-javascript">&gt;=</code>, <code class="language-javascript">&lt;</code>, <code class="language-javascript">&lt;=</code></td>   
        </tr>
        <tr>
            <td>10</td>
            <td>Equality operators</td>
            <td><code class="language-javascript">===</code>, <code class="language-javascript">!==</code> </td>   
        </tr>
    </table>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">1 + 3 * 2 === 7</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>


<? #############################################################################
substepheader('note-eq-precedence-exercises', 'Exercises'); ?>
    <ol>
        <li>What happens if you execute <code class="language-javascript">1/2 + 1/2 === 0.2 * 5</code>?</li>
        <li>What happens if you execute <code class="language-javascript">'abc' + 'def' === 'abcdef'</code>?</li>
        <li>What happens if you execute <code class="language-javascript">(99 + 1) / 100 !== 1</code>?</li>
    </ol>
<? substepfooter() ?>

<?
################################################################################
partheader('Logical operators'); ###############################################
################################################################################
?>


<? #############################################################################
stepheader('note-and', 'The <i>and</i> operator'); ?>
    <p>Imagine someone says: &ldquo;It&rsquo;s raining <i>and</i> it&rsquo;s Thursday.&rdquo; That statement is <i>true,</i> if it is actually raining, <i>and</i> if today is actually Thursday. If it is not raining, and/or today is not Thursday, then the statement is <i>false</i>.</p>

    <p>In JavaScript, the <i>and</i> operator, <code class="language-javascript">&amp;&amp;</code>, operates in the exact same way.
    The <code class="language-javascript">&amp;&amp;</code> operator compares the values of two boolean expressions, and resolves to a boolean value: either <code class="language-javascript">true</code> or  <code class="language-javascript">false</code>.
    </p>

    <p>To be more concrete about how the <code class="language-javascript">&amp;&amp;</code> operator works:</p>

    <ol>
    <li><code class="language-javascript">a &amp;&amp; b</code> resolves to <code class="language-javascript">true</code> if both <code class="language-javascript">a</code> and <code class="language-javascript">b</code> each resolve to <code class="language-javascript">true</code>
    </li>
    <li>But, if either <code class="language-javascript">a</code> and/or <code class="language-javascript">b</code> resolve to <code class="language-javascript">false</code>, then the expression <code class="language-javascript">a &amp;&amp; b</code> resolves to <code class="language-javascript">false</code></li>
    </ol>

    <p>For example:</p>
    <div class="table-format">
    <table>
        <tr>
            <td>
                <code class="language-javascript">true&nbsp;&nbsp;&amp;&amp; true</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">true</code>
             </td>
        </tr>
        <tr>
            <td>
                <code class="language-javascript">false&nbsp;&amp;&amp; true</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">false</code>
             </td>
        </tr>
        <tr>
            <td>
                <code class="language-javascript">true&nbsp;&nbsp;&amp;&amp; false</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">false</code>
             </td>
        </tr>
        <tr>
            <td>
                <code class="language-javascript">false&nbsp;&amp;&amp; false</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">false</code>
             </td>
        </tr>
    </table>
    </div>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">true &amp;&amp; false</code> resolves to <code class="language-javascript">false</code>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-and-precedence', 'And operator precedence'); ?>
        <p>The <i>and</i> operator has the lowest precedence we 
        have seen yet. This means that all other operations we have seen thus
        far have a higher precedence, thus you do not need to put parentheses
        around comparison expressions (etc.) when writing expressions using the <i>and</i> operator.</p>

            <table class="precedence-table">
                    <tr class="header-row">
                        <th>Precedence</th>
                        <th>Operator type</th>
                        <th>Operators</th>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td>Parentheses</td>
                        <td><code class="language-javascript">( ... )</code></td>   
                    </tr>
                    <tr>
                        <td>14</td>
                        <td>Multiplication and division</td>
                        <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>Addition and subtraction</td>
                        <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>Relational operators<br>
                        </td>
                        <td><code class="language-javascript">&gt;</code>, <code class="language-javascript">&gt;=</code>, <code class="language-javascript">&lt;</code>, <code class="language-javascript">&lt;=</code> </td>   
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Equality operators</td>
                        <td><code class="language-javascript">===</code>, <code class="language-javascript">!==</code> </td>   
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>And</td>
                        <td><code class="language-javascript">&amp;&amp;</code></td>   
                    </tr>
            </table>

            <p>All the operators we have seen so far have a higher precedence than the <i>and</i> operator. It is common to combine the <i>and</i> operator with the operators we have previously seen. For example:</p>

            <div class="table-format">
            <table>
                <tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">1 &lt; 2 &amp;&amp; 3 &lt; 4</code></td>
                    <td class="no-wrap" valign="top">resolves to <code class="language-javascript">true</code>
                    </td>
                    <td>
                    <a href="#note-and1">Note</a>
                    </td>
                </tr>
                <tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">1 < 2 &amp;&amp; false</code>
                    </td>
                    <td class="no-wrap" valign="top">
                        resolves to <code class="language-javascript">false</code>
                    </td>
                    <td>
                        <a href="#note-and2">Note</a>
                    </td>
                </tr>
                <tr>
                    <td class="no-wrap" valign="top">
                        <code class="language-javascript">'A' + 'B' === 'AB' &amp;&amp; 1 === 100</code>
                    </td>
                    <td class="no-wrap" valign="top">
                        resolves to <code class="language-javascript">false</code>
                    </td>
                    <td>
                        <a href="#note-and3">Note</a>
                    </td>
                    
                </tr>
            </table>
            </div>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">1 &lt; 2 &amp;&amp; 3 &lt; 4</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-short-circuit', 'Short circuit evaluation with <i>and</i>'); ?>
<p>The expression <code class="language-javascript">false &amp;&amp; 1 &lt; 100</code> resolves to <code class="language-javascript">false</code>, because the left side of the and-operator is <code class="language-javascript">false</code>. Therefore, when executing this expression, JavaScript knows that no matter what is on the right side of the and-operator, the entire expression will resolve to false.</p>

<p>Consequently, JavaScript doesn&rsquo;t even bother to resolve <code class="language-javascript">1 &lt; 100</code>. Rather, as soon as JavaScript sees that the left side of the and-operator is false, it simply resolves the entire expression to <code class="language-javascript">false</code>. The ability for JavaScript to resolve the entire expression while only looking at the left-side, is known as <i>short-circuit evaluation</i>.</p>

<p>Short-circuit evaluation sometimes comes in handy, but we&rsquo;re too early in the book for me to be able to explain it&rsquo;s handiness.</p>

<p>TODO: forward reference</p>

<? substepfooter() ?>


<? #############################################################################
noteheader('note-and1'); ?>
    <p>
    <code class="language-javascript">1 &lt; 2 &amp;&amp; 3 &lt; 4</code>
    resolves to <code class="language-javascript">true</code>, because:</p>
    <ol>
        <li><code class="language-javascript">&lt;</code> has higher precedence than <code class="language-javascript">&amp;&amp;</code></li>
        <li>Therefore, when JavaScript adds parentheses, the expression becomes <code class="language-javascript">(1 &lt; 2) &amp;&amp; (3 &lt; 4)</code></li>
        <li>Next, Javascript resolves <code class="language-javascript">(1 &lt; 2)</code> to <code class="language-javascript">true</code>, and resolves <code class="language-javascript">(3 &lt; 4)</code> to <code class="language-javascript">true</code></li>
        <li>Therefore, the expression becomes <code class="language-javascript">true &amp;&amp; true</code>, which resolves to <code class="language-javascript">true</code>
    </ol>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-and2'); ?>
    <p>
        <code class="language-javascript">1 < 2 &amp;&amp; false</code>
        resolves to <code class="language-javascript">false</code>, because:</p>
    <ol>
        <li><code class="language-javascript">&lt;</code> has higher precedence than <code class="language-javascript">&amp;&amp;</code></li>
        <li>Therefore, when JavaScript adds parentheses, the expression becomes <code class="language-javascript">(1 &lt; 2) &amp;&amp; false</code></li>
        <li>Next, Javascript resolves <code class="language-javascript">(1 &lt; 2)</code> to <code class="language-javascript">true</code></li>
        <li>Therefore, the expression becomes <code class="language-javascript">true &amp;&amp; false</code>, which resolves to <code class="language-javascript">false</code>
    </ol>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-and3'); ?>
    <p>
        <code class="language-javascript">'A' + 'B' === 'AB' &amp;&amp; 1 == 100</code>
        resolves to <code class="language-javascript">false</code>, because:</p>
    </p>

    <ol>
        <li>The <code class="language-javascript">+</code> operator has the highest precedence within this expression</li>
        <li>Therefore, the expression becomes <code class="language-javascript">('A' + 'B') === 'AB' &amp;&amp; 1 == 100</code></li>
        <li>Next, the <code class="language-javascript">===</code> operator has the next highest precedence within this expression</li>
        <li>Therefore, the expression becomes <code class="language-javascript">(('A' + 'B') === 'AB') &amp;&amp; (1 == 100)</code></li>
        <li>Then, JavaScript resolves <code class="language-javascript">('A' + 'B') === 'AB'</code> to <code class="language-javascript">true</code>, and resolves <code class="language-javascript">(1 == 100)</code> to <code class="language-javascript">false</code></li>
        <li>Finally, the expression becomes <code class="language-javascript">true &amp;&amp; false</code>, which resolves to <code class="language-javascript">false</code></li>
    </ol>
<? notefooter(); ?>

<? #############################################################################
stepheader('note-or', 'The <i>or</i> operator'); ?>

    <p>Imagine someone says: &ldquo;It&rsquo;s sunny <i>or</i> it&rsquo;s Friday.&rdquo; Yes, this is a somewhat bizarre statement, but hopefully it&rsquo;s good for demonstrating the logic of &ldquo;or.&rdquo;
    In English, we would consider that statement to be true, if it is actually sunny, <i>or</i> if it is actually Friday. Furthermore, if it is not sunny, and if today is not Friday, then the statement is false.</p>

    <p>But, what if it is sunny, and it is also Friday? In English, the word &ldquo;or&rdquo; is somewhat ill-defined; it&rsquo;s unclear if we should consider the statement true, if it&rsquo;s both sunny and Friday.</p>

    <p>But in JavaScript, there is no ambiguity here: if it&rsquo;s sunny and it&rsquo;s also Friday, then we would consider the statement true.
    It will take a few paragraphs to explain precisely what I mean here.
    </p>

    <p>In JavaScript, &ldquo;or&rdquo; is symbolized by <code class="language-javascript">||</code>, which is simply two adjacent &ldquo;pipe characters.&rdquo; You can type the pipe character by entering <i>shift-backslash</i>; the backslash key is often right above the enter key.</p>

    <p>The general principle of the <i>or</i> operator, is that it compares two boolean expressions (we&rsquo;ll refer to those expressions as expression <code class="language-javascript">a</code> and expression <code class="language-javascript">b</code>), and then resolves to a single boolean value (which is either <code class="language-javascript">true</code> or <code class="language-javascript">false</code>).</p>

    <p>To be more specific, if either <code class="language-javascript">a</code> or <code class="language-javascript">b</code> resolve to <code class="language-javascript">true</code> (or if both resolve to <code class="language-javascript">true</code>), then <code class="language-javascript">a || b</code> resolves to <code class="language-javascript">true</code>. But, if both <code class="language-javascript">a</code> and <code class="language-javascript">b</code> resolve to <code class="language-javascript">false</code>, then <code class="language-javascript">a || b</code> resolves to <code class="language-javascript">false</code>.
    </p>

    <p>For example:</p>

    <div class="table-format">
    <table>
        <tr>
            <td>
                <code class="language-javascript">true&nbsp;&nbsp;|| false</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">true</code>
             </td>
        </tr>
        <tr>
            <td>
                <code class="language-javascript">false&nbsp;|| true</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">true</code>
             </td>
        </tr>
        <tr>
            <td>
                <code class="language-javascript">true&nbsp;&nbsp;|| true</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">true</code>
             </td>
        </tr>
        <tr>
            <td>
                <code class="language-javascript">false&nbsp;|| false</code> 
            </td>
            <td>
                 resolves to <code class="language-javascript">false</code>
             </td>
        </tr>
    </table>
    </div>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">true || false</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-or-precedence', 'Or operator precedence'); ?>
        <p>The <i>or</i> operator has the lowest precedence we 
        have seen yet. Its precedence is even lower than the <i>and</i> operator. This means that all other operations we have seen thus
        far have a higher precedence, thus you do not need to put parentheses
        around comparison expressions (etc.) when writing expressions using the <i>or</i> operator.</p>

            <table class="precedence-table">
                    <tr class="header-row">
                        <th>Precedence</th>
                        <th>Operator type</th>
                        <th>Operators</th>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td>Parentheses</td>
                        <td><code class="language-javascript">( ... )</code></td>   
                    </tr>
                    <tr>
                        <td>14</td>
                        <td>Multiplication and division</td>
                        <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
                    </tr>
                    <tr>
                        <td>13</td>
                        <td>Addition and subtraction</td>
                        <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>Relational operators<br>
                        </td>
                        <td><code class="language-javascript">&gt;</code>, <code class="language-javascript">&gt;=</code>, <code class="language-javascript">&lt;</code>, <code class="language-javascript">&lt;=</code> </td>   
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Equality operators</td>
                        <td><code class="language-javascript">===</code>, <code class="language-javascript">!==</code> </td>   
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>And</td>
                        <td><code class="language-javascript">&amp;&amp;</code></td>   
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Or</td>
                        <td><code class="language-javascript">||</code></td>   
                    </tr>
            </table>

            <p>It is common to combine the <i>or</i> operator with the operators we have previously seen. For example:</p>

            <div class="table-format">
            <table>
                <tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">1 &lt; 2 || false</code></td>
                    <td class="no-wrap" valign="top">resolves to <code class="language-javascript">true</code>
                    </td>
                    <td>
                    <a href="#note-or1">Note</a>
                    </td>
                </tr>
                <tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">false || 1 &gt;= 100</code>
                    </td>
                    <td class="no-wrap" valign="top">
                        resolves to <code class="language-javascript">false</code>
                    </td>
                    <td>
                        <a href="#note-or2">Note</a>
                    </td>
                </tr>
                <tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">'A' + 'B' === 'C' || 1 * 0 > 0</code>
                    </td>
                    <td class="no-wrap" valign="top">
                        resolves to <code class="language-javascript">false</code>
                    </td>
                    <td>
                        <a href="#note-or3">Note</a>
                    </td>
                </tr>
                <!--<tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">3 / 4 < 0 || 8 / 9 >= 1</code>
                    </td>
                    <td class="no-wrap" valign="top">
                        resolves to <code class="language-javascript">false</code>
                    </td>
                    <td>
                        <a href="#note-or4">Note</a>
                    </td>
                </tr>-->
                <!--<tr>
                    <td class="no-wrap" valign="top"><code class="language-javascript">1 &gt; 2 || 2 !== 1</code>
                    </td>
                    <td class="no-wrap" valign="top">
                        resolves to <code class="language-javascript">true</code>
                    </td>
                    <td>
                        <a href="#note-or3">Note</a>
                    </td>
                </tr>-->
            </table>
            </div>
<? stepoverview(); ?>
    <code class="language-javascript">2 * 2 > 4 || 3 * 3 <= 9</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
noteheader('note-or1'); ?>
    <code class="language-javascript">1 &lt; 2 || false</code> resolves to 
    <code class="language-javascript">true</code>, because:

    <ol>
        <li><code class="language-javascript">&lt;</code> has the highest precedence in the expression</li>
        <li>Therefore, when JavaScript adds parentheses, the expression becomes <code class="language-javascript">(1 &lt; 2) || false</code></li>
        <li>Next, JavaScript resolves <code class="language-javascript">(1 &lt; 2)</code> to <code class="language-javascript">true</code></li>
        <li>Therefore, the expression becomes <code class="language-javascript">true || false</code>, which resolves to <code class="language-javascript">true</code>
    </ol>
<? notefooter(); ?>

<? #############################################################################
noteheader('note-or2'); ?>
    <code class="language-javascript">false || 1 >= 100</code> resolves to 
    <code class="language-javascript">false</code>, because:

    <ol>
        <li><code class="language-javascript">&gt;=</code> has the highest precedence in the expression</li>
        <li>Therefore, when JavaScript adds parentheses, the expression becomes <code class="language-javascript">false || (1 >= 100)</code></li>
        <li>Next, JavaScript resolves <code class="language-javascript">(1 >= 100)</code> to <code class="language-javascript">false</code></li>
        <li>Therefore, the expression becomes <code class="language-javascript">false || false</code>, which resolves to <code class="language-javascript">false</code>
    </ol>
<? notefooter(); ?>


<? #############################################################################
noteheader('note-or3'); ?>
    <code class="language-javascript">'A' + 'B' === 'C' || 1 * 0 > 0</code> resolves to 
    <code class="language-javascript">false</code>, because:

    <ol>
        <li><code class="language-javascript">*</code> has the highest precedence in the expression</li>
        <li>Therefore, when JavaScript adds parentheses, the expression becomes <code class="language-javascript">'A' + 'B' === 'C' || (1 * 0) > 0</code></li>
        <li><code class="language-javascript">+</code> has the second highest precedence in the expression</li>
        <li>Therefore, when JavaScript adds more parentheses, the expression becomes <code class="language-javascript">('A' + 'B') === 'C' || (1 * 0) > 0</code></li>
        <li><code class="language-javascript">&gt;</code> has the third highest precedence in the expression</li>
        <li>Therefore, when JavaScript adds more parentheses, the expression becomes <code class="language-javascript">('A' + 'B') === 'C' || ((1 * 0) > 0)</code></li>
        <li><code class="language-javascript">===</code> has the fourth highest precedence in the expression</li>
        <li>Therefore, when JavaScript adds more parentheses, the expression becomes <code class="language-javascript">(('A' + 'B') === 'C') || ((1 * 0) > 0)</code></li>
        <li>JavaScript resolves <code class="language-javascript">(('A' + 'B') === 'C') || ((1 * 0) > 0)</code> to <code class="language-javascript">('AB' === 'C') || (0 > 0)</code></li>
        <li>JavaScript resolves <code class="language-javascript">('AB' === 'C') || (0 > 0)</code> to <code class="language-javascript">false || false</code>, which resolves to <code class="language-javascript">false</code></li>

    </ol>
<? notefooter(); ?>

<? #############################################################################
substepheader('note-short-circuit-or', 'Short circuit evaluation with <i>or</i>'); ?>

<p>In <? substepref('note-and-precedence', 'note-short-circuit', 1) ?>, we learned about short-circuit evaluation with the <i>and</i> operator. 
Similarly, short-circuit evaluation works with the <i>or</i> operator.
</p>

<p>The expression <code class="language-javascript">1 &lt; 2 || 2 !== 1</code> resolves to <code class="language-javascript">true</code>, because the left side of the or-operator (<code class="language-javascript">1 &lt; 2</code>) resolves to <code class="language-javascript">true</code>. Therefore, when executing this expression, JavaScript knows that no matter what is on the right side of the and-operator, the entire expression will resolve to  <code class="language-javascript">true</code>.</p>

<p>Consequently, JavaScript doesn&rsquo;t even bother to resolve <code class="language-javascript">2 !== 1</code>. Rather, as soon as JavaScript sees that the left side of the and-operator is true, it simply resolves the entire expression to <code class="language-javascript">true</code>.</p>
<? substepfooter() ?>

<? #############################################################################
stepheader('note-not', 'The <i>not</i> operator'); ?>
       <p>The <i>not</i> operator is very simple. It takes a boolean value, and flips it to the opposite. The <i>not</i> operator is symbolized by <code class="language-javascript">!</code>.</p>

        <div class="table-format">
            <table>
                <tr>
                    <td><code class="language-javascript">!true</code></td>
                    <td> resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td><code class="language-javascript">!false</code>
                    </td>
                    <td> resolves to <code class="language-javascript">true</code></td>
                </tr>
            </table>
        </div>

        <p>Here are a couple of examples:</p>

        <div class="table-format">
            <table>
                <tr>
                    <td><code class="language-javascript">!(1 &lt; 100)</code></td>
                    <td> resolves to <code class="language-javascript">false</code></td>
                </tr>
                <tr>
                    <td><code class="language-javascript">!(5 === 6)</code>
                    </td>
                    <td> resolves to <code class="language-javascript">true</code></td>
                </tr>
            </table>
        </div>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">!(5 === 6)</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-not-precedence', 'Not operator precedence'); ?>
    <p>The <i>not</i> operator has a relatively high precedence, which is why you need to use parentheses around the boolean expressions that you want to flip.</p>

    <table class="precedence-table">
            <tr class="header-row">
                <th>Precedence</th>
                <th>Operator type</th>
                <th>Operators</th>
            </tr>
            <tr>
                <td>19</td>
                <td>Parentheses</td>
                <td><code class="language-javascript">( ... )</code></td>   
            </tr>
             <tr>
                <td>15</td>
                <td>Not</td>
                <td><code class="language-javascript">!</code> </td>   
            </tr>
            <tr>
                <td>14</td>
                <td>Multiplication and division</td>
                <td><code class="language-javascript">*</code>, <code class="language-javascript">/</code> </td>   
            </tr>
            <tr>
                <td>13</td>
                <td>Addition and subtraction</td>
                <td><code class="language-javascript">+</code>, <code class="language-javascript">-</code> </td>   
            </tr>
            <tr>
                <td>11</td>
                <td>Relational operators<br>
                </td>
                <td><code class="language-javascript">&gt;</code>, <code class="language-javascript">&gt;=</code>, <code class="language-javascript">&lt;</code>, <code class="language-javascript">&lt;=</code> </td>   
            </tr>
            <tr>
                <td>10</td>
                <td>Equality operators</td>
                <td><code class="language-javascript">===</code>, <code class="language-javascript">!==</code> </td>   
            </tr>
            <tr>
                <td>6</td>
                <td>And</td>
                <td><code class="language-javascript">&amp;&amp;</code></td>   
            </tr>
            <tr>
                <td>5</td>
                <td>Or</td>
                <td><code class="language-javascript">||</code></td>   
            </tr>
    </table>
<? stepoverview(); ?>
    The <i>not</i> operator has a relatively high precedence
<? stepfooter(); ?>

<?
################################################################################
partheader('Variables'); #######################################################
################################################################################
?>

<? #############################################################################
stepheader('note-var', 'Variables'); ?>
    <p>A &ldquo;variable&rdquo; is a name that refers to a single value. You can &ldquo;declare&rdquo; a variable  named <code class="language-javascript">x</code> that refers to the value <code class="language-javascript">4</code>like so: <code class="language-javascript">var x = 4</code>.</p>

    <p>Try it out on the repl. Just type in <code class="language-javascript">var x = 4</code> and press enter. The repl will respond with <code class="language-javascript">undefined</code>.</p> 

    <? replshot('varx4.png') ?>

    <p>Why did the repl respond with <code class="language-javascript">undefined</code>? It&rsquo;s because <code class="language-javascript">var x = 4</code> is <i>not</i> an expression. Rather, <code class="language-javascript">var x = 4</code> is a &ldquo;statement.&rdquo;</p>

    <p>While <i>expressions</i> resolve to values, <i>statements</i> do not resolve to values. And, when the repl encounters a statement, it gives you back the special value <code class="language-javascript">undefined</code>. Instead of resolving to values, statements &ldquo;perform actions.&rdquo; <a href="#note-expr-sub">Note</a></p>


    <p>In the case of <code class="language-javascript">var x = 4</code>,
        the action JavaScript performs is: (1) create a new variable named <code class="language-javascript">x</code>, and (2) make the variable <code class="language-javascript">x</code> refer to the value <code class="language-javascript">4</code>.</p>

    <p>Now that we have a variable named <code class="language-javascript">x</code>, the variable <code class="language-javascript">x</code> can now be used as an expression that resolves to the value <code class="language-javascript">4</code>.</p>

    <p>For instance, the expression <code class="language-javascript">x + 1</code> resolves to <code class="language-javascript">5</code>.</p>

    <? replshot('xplus1.png') ?>

    <p>For another example, the expression <code class="language-javascript">x + 1 > 4</code> resolves to <code class="language-javascript">true</code>.</p>

    <? replshot('xrel4.png') ?>
<? stepoverview(); ?>
    If you declare <code class="language-javascript">var x = 4</code>, then <code class="language-javascript">x + 1 > 4</code> resolves to <code class="language-javascript">true</code>
<? stepfooter(); ?>

<? #############################################################################
noteheader('note-expr-sub'); ?>
    <p>As we will see shortly when we discuss (TODO:link) <code class="language-javascript">if</code> statements,
    sometimes JavaScript expects to see a statement somewhere in code. In such places, you can always place an expression instead of a statement.
    However, the reverse is not true. That is, if JavaScript expects to see an expression, you may not place a statement; when JavaScript expects an expression, you must provide an expression.
    </p>
<? notefooter(); ?>

<? #############################################################################
stepheader('note-assignment-expr', 'More on variable declarations'); ?>
    <p>Whenever you declare a new variable, you can assign it the value from any expression. Consider the following examples:</p>

    <ol>
        <li>Executing <code class="language-javascript">var a = 1 + 1</code>, causes <code class="language-javascript">a</code> to resolve to the value of <code class="language-javascript">2</code>. <a href="#note-1-plus-1">Note</a></li>

        <li>Executing <code class="language-javascript">var b = a * 3</code>, causes <code class="language-javascript">b</code> to resolve to the value of <code class="language-javascript">6</code></li>

        <li>Executing <code class="language-javascript">var c = 1 > 2 || 3 < 4</code>, causes <code class="language-javascript">c</code> to resolve to the value of <code class="language-javascript">true</code></li>

    <!-- TODO: earlier show the chaining of binary operators? -->
        <li>Executing <code class="language-javascript">var d = 'Hello' + ', ' + 'World!'</code>, causes <code class="language-javascript">d</code> to resolve to the value of <code class="language-javascript">'Hello, World!'</code>.</li>
    </ol>
<? stepoverview(); ?>
    Executing <code class="language-javascript">var a = 1 + 1</code>, causes <code class="language-javascript">a</code> to resolve to the value of <code class="language-javascript">2</code>
<? stepfooter(); ?>

<? #############################################################################
noteheader('note-1-plus-1'); ?>
    <? replshot('1plus1.png') ?>
<? notefooter(); ?>

<? #############################################################################
stepheader('note-identifiers', 'Valid variable names and identifiers'); ?>
    <p>Although <code class="language-javascript">x</code> is a valid name for a variable, <code class="language-javascript">@</code> is not a valid name for a variable.</p>

    <p>For a variable name to be valid it must be a valid &ldquo;identifier.&rdquo;
    Valid identifiers may only contain letters, numbers, the $ symbol, and the _ symbol. Furthermore, valid identifiers may not begin with a number.</p>

    <p>For example, the following list contains valid identifiers:</p>

    <ol>
        <li><code class="language-javascript">abc</code></li>
        <li><code class="language-javascript">abc123</code></li>
        <li><code class="language-javascript">abc_123</code></li>
        <li><code class="language-javascript">$</code></li>
        <li><code class="language-javascript">$123</code></li>
        <li><code class="language-javascript">_$1$2$3_</code></li>
    </ol>

    <p>Also, a valid identifier name cannot be a word from the JavaScript language. For instance, <code class="language-javascript">var</code> is not a valid identifier. E.g., <code class="language-javascript">var var = 1</code> uses an invalid identifier and therefore the whole statement is invalid.
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">@</code> is not a valid identifier, but <code class="language-javascript">$</code> is a valid identifier
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-assignment-op', 'Assignment operator'); ?>
    <p>In <? steplink('note-var') ?>, we saw how to declare new variables. In this step, though, we show how to make an existing variable refer to a new value. </p>

    <p>It&rsquo;s very simple. Let&rsquo;s say you&rsquo;ve already declared <code class="language-javascript">var x = 1</code>, and now you would like to have <code class="language-javascript">x</code> refer to the value <code class="language-javascript">'Hello'</code>. Simply execute <code class="language-javascript">x = 'Hello'</code>.</p>

    <p>As you might expect, the right-hand side of the <code class="language-javascript">=</code> sign can be any valid expression. For instance: <code class="language-javascript">x = true || false</code>.</p>

    <p>You can even include the variable name on the right-hand side. For instance, if you first declare <code class="language-javascript">var x = 1</code>, then you could follow up with <code class="language-javascript">x = x + 4</code>, and then <code class="language-javascript">x</code> would refer to the value <code class="language-javascript">5</code>. The way it works, is JavaScript first resolves the expression on the right-hand side, and then sets the variable to refer to the resulting value.</p>
<? stepoverview(); ?>
    <code class="language-javascript">x = x + 4</code> adds 4 to the value of <code class="language-javascript">x</code>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-more-assignment-op', 'More about the assignment operator'); ?>
    <p>While variable declarations are statements (and not an expressions), using the assignment operator creates an expression. You can see for yourself by using the repl. Execute <code class="language-javascript">x = 99 + 1</code> in the repl, and it will resolve to the value <code class="language-javascript">100</code>.</p>

    <p>One last thing about the assignment operator: you can actually declare new variables with the assignment operator, without using <code class="language-javascript">var</code>. I.e., <code class="language-javascript">var x = 3</code> works pretty much as well as <code class="language-javascript">x = 3</code>. There are some caveats, however, that inspire us to use <code class="language-javascript">var</code> in variable declarations, which we will see when we encounter &ldquo;functions&rdquo; and &ldquo;recursion,&rdquo; soon enough.</p>

    <p>TODO: forward reference</p>
<? stepoverview(); ?>
    The expression <code class="language-javascript">x = 99 + 1</code> resolves to <code class="language-javascript">100</code>
<? stepfooter(); ?>

<?
################################################################################
partheader('Multiline input and logging'); #####################################
################################################################################
?>

<? #############################################################################
stepheader('note-multiline', 'Multiline input'); ?>
    <p>The <i>multiline input</i> button will come in handy throughout the rest of this book. So, let&rsquo;s see how it works.
    Begin by clicking the multiline input button. An orange box should appear to the right of the green dollar sign.</p>

    <? replshot('multiline1.png') ?>

    <p>Now, when you press enter in the repl, it will not cause the repl
        to execute your line of code. Rather, you can enter as many lines
        as you would like, and then you can execute all the code by clicking the  blue &ldquo;run multiline input&rdquo; button.
    </p>

    <p>So, take the code:</p>

<pre class="language-javascript prejs"><code>var x = 1
var y = 2
x + y</code></pre>
    
    <p>And then type (or paste) it into the orange box in the repl. Then click the blue <i>run multiline input</i> button.</p>

    <p>The repl should respond by saying <code class="language-javascript">3</code>, which is the result of the expression on the last line of input.</p>

    <? replshot('multiline.png') ?>

<? stepoverview(); ?>
    You can use the multiline-input button to execute:
<pre class="language-javascript prejs"><code>var x = 1
var y = 2
x + y</code></pre>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-log', 'Logging'); ?>
    <p>In the repl, enter <code class="language-javascript">log(2 * 3)</code>. This line of code &ldquo;logs&rdquo; the 
        value <code class="language-javascript">6</code> to the repl.
    </p>

    <p>
         You should see the line in the repl that says <code class="language-none">log: 6</code> in yellow font, followed by <code class="language-none">undefined</code> in gray font. In the repl, yellow font indicates a message produced by <code class="language-javascript">log(...)</code>.</p>

    <? replshot('log.png')?>
<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">log(2 * 3)</code> logs <code class="language-none">6</code> to the repl
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-log-function', 'log is a function'); ?>
    <p>    This is the first time we have encountered a function within this book.
        <code class="language-javascript">log</code> is an example of a  &ldquo;function,&rdquo;
        and <code class="language-javascript">log(2 * 3)</code> is an example of a 
    &ldquo;function invocation.&rdquo;</p>

    <p>We cover the topic of functions in Step TODO.</p>
<? substepfooter(); ?>

<? #############################################################################
stepheader('note-log2', 'More about logging'); ?>
    <p>Let&rsquo;s look at another example of using <code class="language-javascript">log(...)</code>. Use the <i>multiline input</i> button to enter the following code into the repl:</p>

<pre class="language-javascript prejs"><code>var x = 1
log(x)
x = x * 2
log(x)
x = x * 2
log(x)
x = x * 2
log(x)
</code></pre>

    <p>Your repl should look like this:</p>

    <? replshot('log2.png') ?>

    <p>Here, we can see that every time <code class="language-javascript">log(x)</code> appears, it logs a new message to the repl, containing the value of <code class="language-javascript">x</code> at that point in the code.</p>

<? stepoverview(); ?>
<pre class="language-javascript prejs"><code>var x = 1
log(x)
x = x * 2
log(x)
x = x * 2
log(x)
x = x * 2
log(x)
</code></pre>
    <p>logs the following values to the repl: <code class="language-javascript">1</code>, <code class="language-javascript">2</code>, <code class="language-javascript">4</code>, <code class="language-javascript">8</code></p>
<? stepfooter(); ?>

<?
################################################################################
partheader('If statements'); ###################################################
################################################################################
?>

<? #############################################################################
stepheader('note-if', 'If statements'); ?>
    <p>An <code class="language-javascript">if</code> statement looks like this:</p>

<? pre("if (conditional) {
    body
}
") ?>


    <p> When JavaScript encounters an <code class="language-javascript">if</code> statement, it resolves the expression in the parentheses (the &ldquo;conditional&rdquo;). If the expression resolves to <code class="language-javascript">true</code>, then JavaScript executes the code inside the curly braces (the &ldquo;body&rdquo;). But, if the expression resolves to <code class="language-javascript">false</code>, then JavaScript skips over the the code inside the curly braces.</p>

    <p>For example, enter the following into the repl:</p>

<? pre("
var x = 9
log('before if')
if (x === 9) {
    log('x equals 9')
}
log('after if')
") ?>
    <p>You should see three logs messages in the repl:</p>

    <? replshot("if-output.png")?>

    <p>Let&rsquo;s look at another example. This time we will modify the <code class="language-javascript">if</code> statement, to see  what happens if we declare <code class="language-javascript">var x == 100</code>:</p>

<? pre("
var x = 100
log('before if')
if (x === 9) {
    log('x equals 9')
}
log('after if')
") ?>

    <p>If you run that code snippet, you should two log messages in the repl:</p>

    <? replshot("if-output-2.png")?>

    <p>JavaScript skipped over the code inside the curly braces, because <code class="language-javascript">x === 9</code> resolved to <code class="language-javascript">false</code>.</p>
<? stepoverview(); ?>
<? pre("var x = 9
if (x === 9) {
    log('x equals 9')
}
")?> <p>logs the string <code class="language-javascript">'x equals 9'</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-ifcast', 'Automatic type casting to boolean values'); ?>
<p>TODO</p>
<? substepfooter() ?>

<? #############################################################################
stepheader('note-if-else', 'If-else statements'); ?>
    <p>An <? code("if") ?>-<? code("else") ?>  statement looks like this:</p>

<? pre("if (conditional) {
    body1
} else {
    body2
}
") ?>

    <p> Similar to an <? code("if") ?> statement, when JavaScript encounters an <? code("if") ?>-<? code("else") ?> statement statement, it resolves the conditional. If the conditional resolves to <? code("true") ?>, then JavaScript executes the first body (&ldquo;body1&rdquo;).</p>

    <p>Furthermore, they key to <? code("if") ?>-<? code("else") ?> statements is that if the conditional resolves to <?code("false")?>, then JavaScript executes &ldquo;body2.&rdquo;</p>

    <p>For example:</p>

<? pre("
var x = 100
if (x === 9) {
    log('x equals 9')
} else {
    log('x does not equal 9')
}
") ?>

<? stepoverview(); ?>
<? pre("
var x = 100
if (x === 9) {
    console.log('x equals 9')
} else {
    console.log('x does not equal 9')
}
")?>  logs the string <? code("'x does not equal 9'") ?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-if-else-stmts', 'Multiple statements within if-else statements'); ?>
    <p>You can include multiple statements, within the body of an <?code("if")?> statement and within the bodies of
     <?code("if")?>-<?code("else")?> statements.</p>

    <p>For example, the following <? code("if") ?>-<? code("else") ?> statement is perfectly valid:</p>

<? pre("
var x = 1
if (x === 1) {
    x = x + 1
    x = x * 2
} else {
    x = 10
    x = x - 5
}
") ?>
<? stepoverview(); ?>
<? pre("var x = 1
if (x === 1) {
    x = x + 1
    x = x * 2
} else {
    x = 10
    x = x - 5
}
") ?> sets the value of <? code(x) ?>  to  <?code(4)?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-if-else-if', 'If-else-if statements'); ?>
    <p>It is possible to include <?code("else if")?> &ldquo;clauses&rdquo; in <?code("if")?> statements, like so:</p>

<? pre("
if (conditional1) {
    body1
} else if (conditional2) {
    body2
} else {
    body3
}
") ?>

    <p>When executing such <?code("if")?> statements, JavaScript will begin by checking <?code("conditional1")?>. If it resolves to <?code("true")?>, then JavaScript will execute <?code("body1")?>. Otherwise, JavaScript will check <?code("conditional2")?>, and if that resolves to <?code("true")?>, then JavaScript will execute <?code("body2")?>. And if none of the above conditionals resolve to <?code("true")?>, then JavaScript will execute <?code("body3")?>.</p>

    <p>For example:</p>

<? pre("
var x = 2
if (x === 1) {
    log('x equals one')
} else if (x === 2) {
    log('x equals two')
} else {
    log('x is neither one, nor two')
}
") ?>
        
    <p>If you execute the above code snippet, then JavaScript will log the string <?code("'x equals two'")?>.</p>

<? stepoverview(); ?>
<? pre("
var x = 2
if (x === 1) {
    console.log('x equals one')
} else if (x === 2) {
    console.log('x equals two')
} else {
    console.log('x is neither one, nor two')
}
")?> logs the string <?code("'x equals two'")?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-if-else-if-multiple', 'Multiple else-if clauses'); ?>
    <p>It is possible to include more than one <?code("else if")?> clause in an <?code("if")?> statements, like so:</p>

<? pre("
if (conditional1) {
    body1
} else if (conditional2) {
    body2
} else if (conditional3) {
    body3
} else {
    body4
}
") ?>

<? stepoverview(); ?>
<? pre("
if (conditional1) {
    body1
} else if (conditional2) {
    body2
} else if (conditional3) {
    body3
} else {
    body4
}
") ?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-if-else-if-exclude', 'Excluding an else clause'); ?>
    <p>It is possible to exclude an <?code("else")?> clause from <?code("if")?> statements, like so:</p>

<? pre("
if (conditional1) {
    body1
} else if (conditional2) {
    body2
} else if (conditional3) {
    body3
}
") ?>
<? stepoverview(); ?>
<? pre("
if (conditional1) {
    body1
} else if (conditional2) {
    body2
} else if (conditional3) {
    body3
}
") ?>
<? stepfooter(); ?>

<?
################################################################################
partheader('Objects'); ################################################################
################################################################################
?>

<? #############################################################################
stepheader('note-references', 'Introduction to references and storage diagrams'); ?>
<? $chapterRef = $chapterNum ?>

        <p>Throughout the rest of this book, we will use &ldquo;storage diagrams&rdquo; to visually represent variables, and other types of entities as well. These diagrams begin very simply, and then become complex as we progress through the steps.</p>

        <p>To start, I will show you a storage diagram for what JavaScript looks like, on the inside, after you&rsquo;ve executed <code class="language-javascript">var zz = 11</code>:</p>

        <? screenshot('zz11.png') ?>

        <p>After having executing <code class="language-javascript">var zz = 11</code>, say we were to execute <code class="language-javascript">var abc = 32</code>. Then, the diagram would look like this:

        <? screenshot('abc32.png') ?>

        <p>But, after that, if we were to execute <code class="language-javascript">var x = 'Hello'</code>, then the storage diagram would look like:</p>

        <? screenshot('xhello.png') ?>

        <p>The reason the diagram looks like this, is because JavaScript does not store string values inside variables. Rather, JavaScript stores &ldquo;references&rdquo; to strings inside variables. Thus, if you were to execute <code class="language-javascript">var q = x</code>, then the storage diagram would look like this:</p>

        <? screenshot('qhello.png') ?>

        <p>Notice how both <code class="language-javascript">x</code> and <code class="language-javascript">q</code> point to (i.e. refer to) the same string. This is because when you assign a string variable, JavaScript doesn't actually make a duplicate of the string; rather, JavaScript just copies the <i>reference</i> to the string. We will see why references are important in the next step, when encounter &ldquo;objects.&rdquo;</p>
<? stepoverview(); ?>
    <? screenshot('qhello.png') ?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-obj-values', 'Defining object values'); ?>
    <p>An <i>object</i> is a value that holds a collection of &ldquo;key-value pairs.&rdquo; Objects are basically containers for variables. Consider the following example:</p>

<pre class="language-javascript prejs"><code>{
    a: 10,
    b: 20,
    c: 30
}</code></pre>

    <p>The above example defines an object value with three key-value pairs:</p>

    <ul>
        <li>The key <code class="language-javascript">a</code> refers to the value <code class="language-javascript">10</code></li>
        <li>The key <code class="language-javascript">b</code> refers to the value <code class="language-javascript">20</code></li>
        <li>The key <code class="language-javascript">c</code> refers to the value <code class="language-javascript">30</code></li>
    </ul>

    <p>Key-value pairs are also known as &ldquo;members.&rdquo; Using this terminology, we could say that the above object has three members: <code class="language-javascript">a</code>, <code class="language-javascript">b</code>, and <code class="language-javascript">c</code>.</p>

    <p>Also, when you define an object this way, the keys must be valid identifiers (See <? steplink('note-identifiers') ?>). For instance, <code class="language-javascript">1c</code> is not valid key, when defining objects this way, because valid identifiers may not begin with a number.</p>

    <p>If you&rsquo;d like, you can use the <i>multiline input</i> button to enter the object definition into the repl. Alternatively, you can enter that object definition in a single line in the repl, by removing &ldquo;whitespace&rdquo; from the object. Simply type it in like this: <code class="language-javascript">{ a: 10, b: 20, c: 30 }</code> (JavaScript doesn&rsquo;t care about the whitespace within object definitions).</p>

    <p>If you were to type in the object definition (with whitespace removed), and hit enter, your repl should look like this:</p>

    <? replshot('obj.png') ?>
    <p>The repl&rsquo;s response, <code class="language-none">[object Object]</code> is the repl&rsquo;s weird way of saying: &ldquo;that&rsquo;s an object.&rdquo;

    <p>In the next full step, we&rsquo;ll use the multiline-input button to enter object definitions another way.</p>

<? stepoverview(); ?>
    <code class="language-javascript no-left-margin">{ a: 10, b: 20, c: 30 }</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-obj-str-keys', 'String keys for object definitions'); ?>
    <p>You can also define objects using quotes around the keys, like so:</p>

<pre class="language-javascript prejs"><code>{
    'a': 10,
    'b': 20,
    'c': 30
}</code></pre>

    <p>When you define key-value pairs this way, the keys may be any valid string, and it&rsquo;s perfectly OK
                if a key is not a valid identifier. For example, the following object definition is perfectly fine:</p>
<pre class="language-javascript prejs"><code>{
    '1': 10,
    '@': 20,
    '#': 30
}</code></pre>

    <p>The only caveat, is that you cannot access such members using the &ldquo;dot operator&rdquo; (see <? steplink('note-obj-dot') ?>). Rather, you can only access such members using &ldquo;bracket operators&rdquo; (see <? substepref('note-obj-dot', 'note-obj-brackets', 1) ?>).
<? substepfooter() ?>

<? #############################################################################
stepheader('note-obj-var', 'Variables can refer to objects'); ?>
 <p>Objects are values, similar to any other type of value, such as number values and string values. Consequently, you can create variables that 
        refer to object values. For example:</p>

<pre class="language-javascript prejs"><code>var x = {
    a: 10,
    b: 20,
    c: 30
}</code></pre>

<? stepoverview(); ?>
<pre class="language-javascript prejs"><code>var x = {
    a: 10,
    b: 20,
    c: 30
}</code></pre>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-obj-types', 'Object member types'); ?>
    <p>The members within any object may be of any type.</p>

    <p>For example, the following object definition is perfectly valid:</p>

<pre class="language-javascript prejs"><code>var x = {
    abc: -1,
    hello: true,
    world: '1, 2, 3!'
}</code></pre>
<? stepoverview(); ?>
<pre class="language-javascript prejs"><code>var x = {
    abc: -1,
    hello: true,
    world: '1, 2, 3!'
}</code></pre>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-obj-dot', 'Accessing members via the dot operator'); ?>
    <p>Recall: we can create variables that 
        refer to object values. For example:</p>

<pre class="language-javascript prejs"><code>var x = {
    a: 10,
    b: 20,
    c: 30
}</code></pre>

    <p>You can &ldquo;access&rdquo; the members of the object, referred to by <code class="language-javascript">x</code> by using the &ldquo;dot operator.&rdquo; For example, to access the member <code class="language-javascript">a</code> you would simply type <code class="language-javascript">x.a</code>. The expression <code class="language-javascript">x.a</code> resolves to the value <code class="language-javascript">10</code>, and the expression <code class="language-javascript">x.b</code> resolves to <code class="language-javascript">20</code>.</p>
<? stepoverview(); ?>
    If you declare <code class="language-javascript">var x = { a: 10 }</code>, then the expression <code class="language-javascript">x.a</code> resolves to the value <code class="language-javascript">10</code>
<? stepfooter(); ?>

<? #############################################################################
substepheader('note-obj-brackets', 'Accessing members via bracket operators'); ?>
<p> <? substepref('note-obj-values', 'note-obj-str-keys', 1) ?> shows how we can define objects using quotes around the keys. For example:</p>

<pre class="language-javascript prejs"><code>var y = {
    '1': 10,
    '@': 20,
    '#': 30
}</code></pre>

        <p>This feature comes in handy, when we want to use a key that is not  a valid identifier (see <? steplink('note-identifiers') ?>). But there&rsquo;s a catch: if a key is not a valid identifier, then we cannot use the dot operator to access members. Rather, we must use &ldquo;bracket operators.&rdquo;</p>

        <p>For example, we can use bracket operators, like so, <code class="language-javascript">y['@']</code>, to access the <code class="language-javascript">'@'</code> member. In this case, the expression <code class="language-javascript">y['@']</code> resolves to <code class="language-javascript">20</code>.</p>

        <p>Bracket operators also come in handy, if you want to access a member via an expression. For example, consider the following object definition:</p>

<pre class="language-javascript prejs"><code>var z = {
    abc: 99
}</code></pre>

        <p>Here, it is possible, via bracket operators, to access the member 
            <code class="language-javascript">abc</code> via the following expression: <code class="language-javascript">z['a' + 'b' + 'c']</code>. The expression <code class="language-javascript">z['a' + 'b' + 'c']</code> resolves to the value <code class="language-javascript">99</code>.</p>


<? substepfooter() ?>

<? ########################################################################## ?>
<? ########################################################################## ?>
<? ############################ COPY AREA ################################### ?>
<? ########################################################################## ?>
<? ########################################################################## ?>

<? #############################################################################
stepheader('note-x', ''); ?>

<? stepoverview(); ?>

<? stepfooter(); ?>

<? #############################################################################
substepheader('note-y', ''); ?>

<? substepfooter() ?>

<? #############################################################################
noteheader('note-z'); ?>

<? notefooter(); ?>

<?
################################################################################
partheader(''); ################################################################
################################################################################
?>

<?
################################################################################
# END ##########################################################################
################################################################################
?>

<script>
// For each steplink(), fill in the step number
for (var i = 0; i < ORDERING.length; i++) {
    var stepName = ORDERING[i];
    var stepNum = i + 1;
    $('[data-link-to-step-name="' + stepName + '"]')
        .each(function(i, elem){
            var substepNum = $(elem).data('substep-num');
            if (substepNum) {
                $(elem).html('Step ' + stepNum + '.' + substepNum);
            } else {
                $(elem).html('Step ' + stepNum);
            }
        })
}
</script>

</div> <!-- end #staging-area -->
        </main>
    </body>
    <script type='text/javascript'>
        var SIDENOTE = undefined;
        var log = undefined;
        $(window).on('load', function() {
            SIDENOTE = new ReplSidenote(ORDERING);
            setupLinksToNotes(SIDENOTE);
            setupScrollAnchors(SIDENOTE);
            colorSnippets();

            // Note sure why this timeout is needed,
            // but makes loadViewFromUrl in my dev
            // environment
            setTimeout(function(){
                SIDENOTE.loadViewFromUrl();
            }, 0);

            $("#num-steps").append(ORDERING.length);
        });
    </script>
</html>