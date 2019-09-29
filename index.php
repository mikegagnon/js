<!doctype html>
<html lang='en'>
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>JavaScript: Overview &amp; Details</title>
        <link rel='stylesheet' href='style/codemirror.css'>
        <link rel='stylesheet' href='style/solarized.css'>
        <link href='style/bootstrap.css' rel='stylesheet'>
        <link href='style/prism.css' rel='stylesheet'>
        <link href='style/book.css' rel='stylesheet'>
        <link href='style/sidenote.css' rel='stylesheet'>
        <script src='js/codemirror.js'></script>
        <script src='js/javascript.js'></script>
        <script src='js/jquery-2.2.4.min.js'></script>
        <script src='js/prism.js'></script>
        <script src='js/popper.min.js'></script>
        <script src='js/bootstrap.js'></script>
        <script src='js/sidenote.js'></script>
        <script src='js/book.js'></script>
    </head>
    <body>
        <main>
<div id="sidenote">
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
        <div class='padded'>
            <table class='snippets-table'>
                <tr>
                    <td>
                        <a href='#note-repl'>Step&nbsp;1</a>
                    </td>
                    <td>
                        <code class='language-javascript'>1 + 2</code> produces <code class='language-javascript'>3</code>, in the repl
                    </td>
                </tr>
            </table>
        </div>

        <h1 class='part-title'><a name='longform'>Long form</a></h1>
        <div class='padded'>
            <p>Step 1</p>
        </div>
    </div>
</div> <!-- end data-column='0' -->
</div> <!-- end #sidenote -->

<div id='staging-area' class='column'>
<div class='padded note' data-note-name='note-repl'>
    <div class='close-button'>×</div>
    <h2>Step 1. Repl</h2>

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
</div>

<div class='padded note' data-note-name='note-values'>
    <div class='close-button'>×</div>
    <h2>Step 2. Numbers and values</h2>

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
</div>
</div> <!-- end #staging-area -->
        </main>
    </body>
    <script type='text/javascript'>
        let SIDENOTE = undefined;
        $(window).on('load', function() {
            SIDENOTE = new Sidenote();

            $('div[data-column="0"] a[href^="#note-"]').click(function() {
                var fromColumnNumber = findColumnNumber(this);
                var fromNoteName = findNoteName(this);
                var toNoteName = $(this).attr('href').substr(1);
                SIDENOTE.clickNoteLink(fromColumnNumber, fromNoteName, toNoteName);
            });

            setupScrollAnchors();
            colorSnippets();
        });
    </script>
</html>