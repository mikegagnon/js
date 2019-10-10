<?
include 'sidenote.php';
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
        <link href='style/vol2.css' rel='stylesheet'>
        <link href='style/sidenote.css' rel='stylesheet'>
        <script src='js/codemirror.js'></script>
        <script src='js/javascript.js'></script>
        <!--<script src='js/jquery-2.2.4.min.js'></script>-->
        <script src='js/jquery-1.12.4.min.js'></script>
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
            <div><? screenshot('owl.png') ?></div>
            <div class='subtitle'>Volume 1</div>
            <div class='author'>&copy; Michael N. Gagnon, 2019</div>
            <div class='author'>This book represents an early, incomplete, rough draft. However, I&rsquo;m posting this draft online now, just to demonstrate the overall approach I&rsquo;m taking with the book.</div>
        </div>
        <div id="spacer" style="width: 1; height: 1px;">
        </div>
        <div class='padded'>
            <h2>Preface</h2>

            <p>This book represents the first volume in a series of three. This series, and this book in particular, is for you if you&rsquo;ve never programmed before and you would like to learn JavaScript. By the conclusion of this first book, you will have learned 100% of the JavaScript you need to know in order to write a chess  AI. And, by the conclusion of the third book, we will have programmed a chess AI, together.</p>

            <h3>Shallow vs. deep learning</h3>

            <p>JavaScript is a gnarly language. There are many nooks and crannies.
            This book skips over all the details, while simultaneously presenting many of the finer points of JavaScript. How?
         </p>

            <p>The main narrative of this book is shallow; it presents only the minimum of what you need to know to write a chess AI. At the same time, this
                book is sprinkled with detailed sidenotes&mdash;providing you
                 the option to dive deep into some of JavaScript&rsquo;s minutiae.</p>

            <h3>Lazy vs. eager learning</h3>

            <p>Broadly speaking, there are three approaches to learning: <i>lazy</i> learning, <i>eager</i> learning, and a hybrid approach&mdash;<i>lazy-eager</i> learning.</p>

            <p>With the lazy approach, you skip straight to the last page of your textbook, and work backwards from there. With the eager approach, you refuse to move on to the next step, until you&rsquo;ve convinced yourself that you&rsquo;ve mastered the current step.</p>

            <p>But, with the hybrid lazy-eager approach, perhaps you skip a few steps and work backwards from there. Or, perhaps you skim the material until you reach a point where you&rsquo;re confused, and then go back and study the material you have already skimmed over.</p>

            <p>I think the lazy-eager approach might be a great way to learn JavaScript, and I designed this book for lazy-eager learning.</p>

        </div>

        <h1 class='part-title'><a name='snippets'>Contents</a></h1>

        <p class='padded'>For each of the <span id='num-steps'></span> steps of this book, the table of contents contains a link to that step, as well as a short snippet of code from that step.</p>

        <div id='snippets' class='padded' >
        </div>

    <p class='book-footer'>Except where otherwise noted, the contents of this book (and related source code) is copyrighted by Michael N. Gagnon, 2019. The owl logo is copyrighted by <a href="https://twemoji.twitter.com/content/twemoji-twitter/en.html">Twitter</a>, and licensed via  <a href="https://creativecommons.org/licenses/by/4.0/">CC-BY 4.0</a>.</p>

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
partheader('Chapter teste'); ###################################################
################################################################################
?>

<? #############################################################################
stepheader('note-test', 'Test step'); ?>
    <iframe src="step1.html"></iframe>
<? stepoverview(); ?>
    <p>Overview</p>
<? stepfooter(); ?>

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