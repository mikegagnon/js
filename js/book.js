 
 function setupScrollAnchors() {
     //https://stackoverflow.com/questions/8579643/how-to-scroll-up-or-down-the-page-to-an-anchor-using-jquery
     $('a[href^="#"]').click(function(e) {
        e.preventDefault();
        var dest = $(this).attr('href');
        if (!dest.startsWith('#note-')) {
            var aname = `a[name='${dest.substr(1)}']`;
            $('html,body').animate({ scrollTop: $(aname).offset().top }, 'slow');
        }
    });
 }

 function colorSnippets() {
    $(".snippets-table").each(function(i, elem){
        $(elem).find('tr').filter(":even").css('background-color', "#eee");
    })
 }

 function copyStepsAfterRoot(sidenote, firstStepName) {
    sidenote.cloneNote(firstStepName, 0);
    sidenote.positionNewNoteBelow(firstStepName, 0);
    sidenote.expand(firstStepName, 0);
 }

