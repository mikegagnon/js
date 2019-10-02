<?

$partNum = null;
$stepNum = null;
$subStepNum = null;
$currentStepName = null;
$currentStepTitle = null;

function stepheader($stepName, $stepTitle) {
    global $stepNum;
    global $currentStepName;
    global $currentStepTitle;
    global $subStepNum;

    $currentStepName = $stepName;
    $currentStepTitle = $stepTitle;
    $stepNum++;
    $subStepNum = 0;
    $text = <<<html
<div class='padded note' data-note-name='$stepName'>
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
    <h2>Step $stepNum. $stepTitle</h2>
html;

    echo $text;
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
    $n = ++$partNum;
    $text = <<<html
<script>
SNIPPETS_TABLE = '#snippets-table-part-$n';

$(SNIPPETS_TABLE_WRAPPER).append(`<p class='snippets-part-number'>Part $n. $title </p>`);

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

            <p>JavaScript is a gnarly language. There are many nooks and crannies
            that book authors can either explore or ignore. Consequently,
             JavaScript books tend to either skip over the nitty-gritty details of the language, or they tediously expound upon the minutiae.</p>

            <p>This book presents the big picture, along with the option to ignore, skim, or scrutinize many of the finer details of JavaScript. The way I&rsquo;ve managed to pull this off, is that I&rsquo;ve made extensive use of sidenotes.</p>

            <p>
                If you&rsquo;re inclined, feel free to enrich your understanding of JavaScript by diving deep into the sidenotes. Or, feel free to stay shallow, which is all you need for us to build a chess AI together.
            </p>

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

<?
################################################################################
partheader('Variables and if statements'); #####################################
################################################################################
?>

<?
################################################################################
# END ##########################################################################
################################################################################
?>

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