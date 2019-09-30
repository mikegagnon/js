<?

$stepNum = null;
$currentStepName = null;
$currentStepTitle = null;

//$currentTablePartId = null;
//$currentTocPartId = null;
//$currentSubStepName = null;
//$currentFrac = null;
//$currentSubStepTitle = null;

function stepheader($stepName, $stepTitle) {
    global $stepNum;
    global $currentStepName;
    global $currentStepTitle;

    $currentStepName = $stepName;
    $currentStepTitle = $stepTitle;
    $n = ++$stepNum;
    $text = <<<html
<div class='padded note' data-note-name='note-$stepName'>
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
    <h2>Step $stepNum. $stepTitle</h2>
html;

    echo $text;
}

function stepoverview() {
    global $stepNum;
    global $currentStepName;
    global $currentStepTitle;

    $text = <<<html
</div>
<script>
ORDERING.push('note-$currentStepName');

$(LONGFORM_TABLE).append(`<tr>
    <td style='width: 100%'>
        <a href='#aname-note-$currentStepName'>Step $stepNum. $currentStepTitle</a>
    </td>
</tr>`);

$(SNIPPETS_TABLE).append(`<tr>
    <td>
        <a href='#note-$currentStepName'>Step&nbsp;$stepNum</a>
    </td>
    <td>
html;

    echo $text;
}

function stepfooter() {
    $text = <<<html
    </td>
</tr>`);
</script>
html;
    echo $text;
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
        <link href='style/repl-light.css' rel='stylesheet'>
        <link href='style/book.css' rel='stylesheet'>
        <link href='style/sidenote.css' rel='stylesheet'>
        <script src='js/codemirror.js'></script>
        <script src='js/javascript.js'></script>
        <script src='js/jquery-2.2.4.min.js'></script>
        <script src='js/prism.js'></script>
        <script src='js/popper.min.js'></script>
        <script src='js/bootstrap.js'></script>
        <script src='js/repl.js'></script>
        <script src='js/sidenote.js'></script>
        <script src='js/repl-sidenote.js'></script>
        <script src='js/book.js'></script>
    </head>
    <body>
        <main>
<div id='sidenote'>
<div id='repl-console-wrapper'>
<div class='note repl-note' id='repl-console'>
    <h4>Console</h4>
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

        <div class='padded'>
            <h2>Preface</h2>

            <p><a href='#note-expressions2'>Step 4</a></p>

            <p>This book represents the first volume in a series of three. This series, and this book in particular, is for you if you&rsquo;ve never programmed before and you would like to learn JavaScript. By the conclusion of this first book, you will have learned 100% of the JavaScript you need to know in order to write a chess game, complete with an AI. And, by the conclusion of the third book, we will have programmed the game and AI, together.</p>

            <p>JavaScript is a gnarly language. There are many nooks and crannies
            that book authors can either explore or ignore. Consequently,
             JavaScript books tend to either skip over the nitty-gritty details of the language, or they tediously expound upon the minutiae.</p>

            <p>This book presents the big picture, along with the option to ignore, skim, or scrutinize many of the finer details of JavaScript. The way I&rsquo;ve managed to pull this off, is that I&rsquo;ve made extensive use of sidenotes.</p>

            <p>
                If you&rsquo;re inclined, feel free to enrich your understanding of JavaScript by diving deep into the sidenotes. Or, feel free to stay shallow, which is all you need for us to build a chess AI together.
            </p>
        </div>

        <div class='padded'>
            <h2>Contents</h2>

            <p>This left-most column contains three parts:</p>

            <ol>
                <li>Front matter, including the title, preface, and this table of contents</li>
                <li><a href='#snippets'>Snippets</a>, which contains a &ldquo;snippet&rdquo; of code from each step in the book, along with a link from each snippet to its associated step</li>
                <li><a href='#longform'>Long form</a>, which contains each step, one after the other, including a table of contents.  This part is for you, if you would like to read, skim, or scrutinize the book in an old fashioned, page-by-page fashion.</li>
            </ol>

            <p>Lastly, as you can see, there is a &ldquo;repl&rdquo; on the side of your window. We begin our study of JavaScript, by describing and demonstrating the repl in Step 1.</p>
            
        </div>

        <h1 class='part-title'><a name='snippets'>Snippets</a></h1>
        <div id='snippets' class='padded' >
        </div>

        <h1 class='part-title'><a name='longform'>Long form</a></h1>
        <div id='longform' class='padded'>
        </div>
    </div>
</div> <!-- end data-column='0' -->
</div> <!-- end #sidenote -->

<div id='staging-area'>
<script>
const ORDERING = [];
const SNIPPETS = '#snippets';
const SNIPPETS_TABLE_WRAPPER = '#snippets-tables-wrapper';
let SNIPPETS_TABLE = '#snippets-table-part-1';

const LONGFORM = '#longform';
const LONGFORM_TABLE_WRAPPER = '#longform-tables-wrapper';
let LONGFORM_TABLE = '#longform-table-part-1';
</script>
<script>

$(SNIPPETS).append(`<div id='${SNIPPETS_TABLE_WRAPPER.substr(1)}'>
</div>`);

$(SNIPPETS_TABLE_WRAPPER).append(`<p class='snippets-part-number'>Part 1. Expressions</p>`);

$(SNIPPETS_TABLE_WRAPPER).append(`<table id='${SNIPPETS_TABLE.substr(1)}' class='snippets-table'>
</table>`);

$(LONGFORM).append(`<div id='${LONGFORM_TABLE_WRAPPER.substr(1)}'>
</div>`);

$(LONGFORM_TABLE_WRAPPER).append(`<p class='snippets-part-number'>Part 1. Expressions</p>`);

$(LONGFORM_TABLE_WRAPPER).append(`<table id='${LONGFORM_TABLE.substr(1)}' class='snippets-table'>
</table>`);
</script>

<?
    $stepNum = 0;
?>

<? #############################################################################
stepheader('repl', 'Repl'); ?>
    <p><a href='#note-values'>Step 2</a></p>

    <p>On the repl, click the black area, just to the right of the green dollar sign. Then type in <code class='language-javascript'>1 + 2</code>, and press enter. At this point,
    your repl should look like this:</p>

    <p>What happened here? Your browser executed the statement <code class='language-javascript'>1 + 2</code>,
        and then the repl displayed the result <code class='language-javascript'>3</code>.
     </p>

    <p>A <i>repl</i> is a piece of software that allows you
    to experiment with coding. The way it works, is you type in one (or more)
    &ldquo;statements&rdquo; in the repl, and press enter. Then, your browser
    will &ldquo;execute&rdquo; your statement, and then the repl will display the result of executing your statement.
    Repeat.
    </p>

    <p>At any time, you can click the &ldquo;clear&rdquo; button to restart your repl session. And, I will explain the &ldquo;multiline input&rdquo; button later on.</p>
<? stepoverview(); ?>
    <code class='language-javascript'>1 + 2</code> produces <code class='language-javascript'>3</code>, in the repl

<? stepfooter(); ?>

<? #############################################################################
stepheader('values', 'Numbers and values'); ?>
    <p>In JavaScript, the number <code class='language-javascript'>1</code> is a &ldquo;value.&rdquo;
    The number <code class='language-javascript'>2</code> is also a value, and so is the number <code class='language-javascript'>105.72</code>, and so on. Every number is a value, but there are also values other than just numbers, as we&rsquo;ll see quite often in later steps as well. For now, though, we&rsquo;ll just focus on number values.</p>

    <div class='exercise'>
        <h3>Exercises</h3>
        <ol>
        <li>What happens if you execute <code class='language-javascript'>3.7</code>?</li>
        <li>What happens if you execute <code class='language-javascript'>2938</code>?</li>
        <li>What happens if you execute <code class='language-javascript'>7349902384908234</code>?</li>
        </ol>
    </div>
<? stepoverview(); ?>
    <code class='language-javascript'>5 + 2</code> produces <code class='language-javascript'>7</code>, in the repl
<? stepfooter(); ?>


<div class='padded note' data-note-name='note-expressions'>
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
    <h2>Step 3. Expressions</h2>

    <p><code class='language-javascript'>1 + 2</code> is not a value; rather, <code class='language-javascript'>1 + 2</code> is a JavaScript &ldquo;expression.&rdquo; An <i>expression</i>, is anything in JavaScript that &ldquo;resolves&rdquo; to a value. In this case, <code class='language-javascript'>1 + 2</code> <i>resolves</i> to the value <code class='language-javascript'>3</code>.
            </p>

    <p>To be precise, every value is also an expression. To give a concrete example, the number <code class='language-javascript'>4</code> is a value, and the value <code class='language-javascript'>4</code> is also an expression&mdash;albeit, an expression that resolves to the value <code class='language-javascript'>4</code>.</p>

</div>
<script>
ORDERING.push('note-expressions');

$(SNIPPETS_TABLE).append(`<tr>
    <td>
        <a href='#note-expressions'>Step&nbsp;3</a>
    </td>
    <td>
        <code class='language-javascript'>3 + 5</code> produces <code class='language-javascript'>8</code>, in the repl
    </td>
</tr>`);

$(LONGFORM_TABLE).append(`<tr>
    <td style='width: 100%'>
        <a href='#aname-note-expressions'>Step 3. Expressions</a>
    </td>
</tr>`);
</script>


<script>
SNIPPETS_TABLE = '#snippets-table-part-2';

LONGFORM_TABLE = '#longform-table-part-2';

$(SNIPPETS_TABLE_WRAPPER).append(`<p class='snippets-part-number'>Part 2. Expressions2 </p>`);

$(SNIPPETS_TABLE_WRAPPER).append(`<table id='${SNIPPETS_TABLE.substr(1)}' class='snippets-table'>
</table>`);


$(LONGFORM_TABLE_WRAPPER).append(`<p class='snippets-part-number'>Part 2. Expressions 2</p>`);

$(LONGFORM_TABLE_WRAPPER).append(`<table id='${LONGFORM_TABLE.substr(1)}' class='snippets-table'>
</table>`);
</script>
<div class='padded note' data-note-name='note-expressions2'>
    <div class='close-button'>×</div> <div class='expand-button'>⋮</div>
    <h2>Step 4. Expressions2</h2>

    <p><code class='language-javascript'>1 + 2</code> is not a value; rather, <code class='language-javascript'>1 + 2</code> is a JavaScript &ldquo;expression.&rdquo; An <i>expression</i>, is anything in JavaScript that &ldquo;resolves&rdquo; to a value. In this case, <code class='language-javascript'>1 + 2</code> <i>resolves</i> to the value <code class='language-javascript'>3</code>.
            </p>

    <p>To be precise, every value is also an expression. To give a concrete example, the number <code class='language-javascript'>4</code> is a value, and the value <code class='language-javascript'>4</code> is also an expression&mdash;albeit, an expression that resolves to the value <code class='language-javascript'>4</code>.</p>

</div>
<script>
ORDERING.push('note-expressions2');

$(SNIPPETS_TABLE).append(`<tr>
    <td>
        <a href='#note-expressions2'>Step&nbsp;4</a>
    </td>
    <td>
        <code class='language-javascript'>3 + 5</code> produces <code class='language-javascript'>8</code>, in the repl
    </td>
</tr>`);

$(LONGFORM_TABLE).append(`<tr>
    <td style='width: 100%'>
        <a href='#aname-note-expressions2'>Step 4. Expressions 2</a>
    </td>
</tr>`);
</script>

</div> <!-- end #staging-area -->
        </main>
    </body>
    <script type='text/javascript'>
        let SIDENOTE = undefined;
        $(window).on('load', function() {
            SIDENOTE = new ReplSidenote(ORDERING);
            setupLinksToNotes(SIDENOTE);
            setupScrollAnchors(SIDENOTE);
            colorSnippets();
            colorLongform();
            copyStepsAfterRoot(SIDENOTE, 'note-repl');
        });
    </script>
</html>