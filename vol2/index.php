<?
include '../sidenote.php';

function iframe($filename) {
    echo "<iframe src='$filename' width='480px' height='300px'></iframe>";
}

function screenshot_up($filename) {
    screenshot($filename, "../");
}

function screenshot_vol2($filename, $border=False, $enlarageable=False) {
    $fullFilename = $_SERVER['DOCUMENT_ROOT'] . '/vol2/img/' . $filename;  
    $width = 480.0;
    $p = $width / getimagesize($fullFilename)[0];
    $height = getimagesize($fullFilename)[1] * $p;

    if ($border) {
        $border = " img_border";
    } else {
        $border = "";
    }

    if ($enlarageable) {
        $text = <<<html
<div class="screenshot $border">
    <a href="/vol2/img/$filename" target="_blank"><img width="$width" height="$height" src="/vol2/img/$filename"></a>
</div>
    <p class="caption">Click to enlarge in another window</p>
html;
    } else {
        $text = <<<html
<div class="screenshot $border">
    <img width="$width" height="$height" src="/vol2/img/$filename">
</div>
html;
    }
    echo $text;
}

?><!doctype html>
<html lang='en'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>JavaScript: Overview &amp; Details</title>
        <link rel='stylesheet' href='../style/codemirror.css'>
        <link rel='stylesheet' href='../style/solarized.css'>
        <link href='../style/bootstrap.css' rel='stylesheet'>
        <link href='../style/prism.css' rel='stylesheet'>
        <link href='../style/repl-dark.css' rel='stylesheet'>
        <link href='../style/book.css' rel='stylesheet'>
        <link href='../vol2/style/vol2.css' rel='stylesheet'>
        <link href='../style/sidenote.css' rel='stylesheet'>
        <script src='../js/codemirror.js'></script>
        <script src='../js/javascript.js'></script>
        <!--<script src='js/jquery-2.2.4.min.js'></script>-->
        <script src='../js/jquery-1.12.4.min.js'></script>
        <script src='../js/prism.js'></script>
        <script src='../js/popper.min.js'></script>
        <script src='../js/bootstrap.js'></script>
        <script src='../js/my/repl.js'></script>
        <script src='../js/my/sidenote.js'></script>
        <script src='../js/my/repl-sidenote.js'></script>
        <script src='../js/my/book.js'></script>
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
            <div><? screenshot_up('owl.png') ?></div>
            <div class='subtitle'>Volume 2</div>
            <div class='author'>&copy; Michael N. Gagnon, 2019</div>
            <div class='author'>This book represents an early, incomplete, rough draft. However, I&rsquo;m posting this draft online now, just to demonstrate the overall approach I&rsquo;m taking with the book.</div>
        </div>
        <div id="spacer" style="width: 1; height: 1px;">
        </div>
        <div class='padded'>
            <h2>Preface</h2>

            <p>This book is the second volume in a series of three. The first volume is located <a target="_blank" href="/">here</a>.</p>

            <p>By the conclusion of Volume 2, this book, we will have programmed a tic-tac-toe AI, together.
            Building a tic-tac-toe AI is a worthy intermediate step towards developing a chess AI, because
            building tic tac toe is simpler, and because our chess AI will leverage many of
            the techniques we will use to build our tic-tac-toe AI. 
        </p>
        </div>

        <h1 class='part-title'><a name='snippets'>Contents</a></h1>

        <!--<p class='padded'>For each of the <span id='num-steps'></span> steps of this book, the table of contents contains a link to that step, as well as a short snippet of code from that step.</p>-->

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
partheader('CodePen'); ##############################
################################################################################
?>

<? #############################################################################
stepheader('note-codepen', 'Introduction to CodePen'); ?>
    
    <p>We are going to write a bunch of code together. To do that, you need
    a <i>development environment</i>, a place where you can type in code,
    test your code, and view the fruits of your labor.</p>

    <p>You have several options. For example, you could download a &ldquo;text
        editor,&rdquo; such as <a target="_blank" href="https://www.sublimetext.com/">Sublime</a>
        and manually organize files and directories on your
        file system, then use your browser to execute your code.
        The problem with that approach, is it&rsquo;s just a little bit more 
        complicated than it needs to be, for our purposes.
    </p>

    <p>Instead, I recommend using <a target="_blank" href="https://codepen.io/pen">CodePen</a>.
        CodePen is a web application that lets you develop JavaScript applications
        without needing to download any software onto your computer. Another benefit
        of CodePen is that you can easily share your creations across the Web.</p>

    <p>
        This feature is particularly helpful while learning JavaScript because 
        when your code is buggy, when it&rsquo;s not working the way you want
        it to, you can share your code with more experience programmers, who
        can help you identify where your bugs are.
    </p>

    <!-- TODO: link to forums -->

<? stepoverview(); ?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-codepen-howto', 'CodePen panes'); ?>
    <p>Visit <a target="_blank" href="https://codepen.io/pen">https://codepen.io/pen</a>.
    You should see something that looks similar to:
    </p>

    <? screenshot_vol2('codepen-blank.png', True, True) ?>

    <p>From this &ldquo;view,&rdquo; you can see three panes:</p>

    <? screenshot_vol2('codepen-panes.png', True, True) ?>

    <p>But, there&rsquo;s a fourth pane that we can&rsquo;t see yet. To view all four panes,
        click the <i>change view</i> button:</p>

    <? screenshot_vol2('codepen-change-view.png', True, True) ?>

    <p>The <i>editor-layout</i> menu should pop up. Then click the left-most option:</p>

    <? screenshot_vol2('codepen-editor-layout.png', True, True) ?>

    <p>Now, your CodePen page should look like this:</p>

    <? screenshot_vol2('codepen-four-panes.png', True, True) ?>





    <p>The way it works is: you will construct a web page by typing in (or pasting) code into the first three panes.
        Then, a web page (your web page) will automatically appear in the fourth pane.</p>

    <p>Let&rsquo;s build our first web page in the next step.</p>


<? stepoverview(); ?>
<? stepfooter(); ?>

<?
################################################################################
partheader('HTML'); ##############################
################################################################################
?>

<? #############################################################################
stepheader('note-intro-html', 'Introduction to HTML'); ?>

    <p>Whereas JavaScript is a programming language, HTML is <i>markup language</i>.
    That is, HTML is a language that allows us to “mark up” documents.
    Let’s look at some examples.</p>

    <p>In the HTML pane for <a target="_blank" href="https://codepen.io/pen">CodePen</a>, type in the following:</p>

<? prehtml("This is just some plain text.") ?>

    <p>The text you entered should automatically appear in the web-page pane:</p>

    <? screenshot_vol2('codepen-plaintext.png', True, True) ?>

<? stepoverview(); ?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-i-b', 'Italics and bold'); ?>

    <p>In the HTML pane, replace the old text with this new text:</p>

<? prehtml("This is just some plain text.
<i>This text is italicized.</i>
<b>This text is bolded.</b>
<i><b>This text is italicized and bolded.</b></i>") ?>

    <? screenshot_vol2('codepen-i-b.png', True, True) ?>

    <p><? codehtml('<i>') ?> is a &ldquo;tag,&rdquo; and <? codehtml('</i>') ?> is a &ldquo;closing tag.&rdquo;
        Similarly, <? codehtml('<b>') ?> is a tag, and <? codehtml('</b>') ?> is a closing tag.</p>

    <p><? codehtml('<i>') ?> tags create italicized text, and <? codehtml('<b>') ?> tags create bold text.</p>

    <p>Notice how in the HTML pane, each sentence is on its own line, yet in the web-page pane each sentence appears one after the other.</p>

<? stepoverview(); ?>
<? stepfooter(); ?>

<? #############################################################################
stepheader('note-p-tag', 'Paragraphs'); ?>

    <p>You can use <? codehtml('<p>') ?> tags to put text into separate paragraphs.</p>

<? prehtml("<p>
  <i>This text is italicized.</i>
</p>
<p>
  <b>This text is bolded.</b>
</p>") ?>

<? stepoverview(); ?>
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