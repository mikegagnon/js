 
function setupScrollAnchors(sidenote) {
     //https://stackoverflow.com/questions/8579643/how-to-scroll-up-or-down-the-page-to-an-anchor-using-jquery
    $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        const dest = $(this).attr('href');
        if (!dest.startsWith('#note-')) {
            const aname = `a[name='${dest.substr(1)}']`;
            let top = $(aname).offset().top;
            console.log(aname);
            if (dest.startsWith('#aname-')) {
                top -= sidenote.setup.padVertBetweenNotes;
            }
            $('html,body').animate({ scrollTop: top }, 'slow');
        }
    });
}

function colorSnippets() {
    $(".snippets-table").each(function(i, elem){
        $(elem).find('tr').filter(":even").css('background-color', "#eee");
    })
}

function colorLongform() {
    $("#longform").each(function(i, elem){
        $(elem).find('.ptoc').filter(":even").css('background-color', "#eee");
    })
}

function copyStepsAfterRoot(sidenote, firstStepName) {
    sidenote.cloneNote(firstStepName, 0);
    sidenote.positionNewNoteBelow(firstStepName, 0);
    sidenote.expand(firstStepName, 0);

    const columnSelector = `[data-column='0']`;
    const noteSelector = `${columnSelector} .note`;
    $(noteSelector).each(function(i, elem){
        const noteName = $(elem).data('note-name');
        const html = `<a name='aname-${noteName}'></a>`;
        $(elem).find('h2').wrap(html);
    });
}

function setupLinksToNotes(sidenote) {
    $('div[data-column="0"] a[href^="#note-"]').click(function() {
        var fromColumnNumber = findColumnNumber(this);
        var fromNoteName = findNoteName(this);
        var toNoteName = $(this).attr('href').substr(1);
        sidenote.clickNoteLink(fromColumnNumber, fromNoteName, toNoteName);
    });
}
