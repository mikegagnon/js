"use strict";

function setupScrollAnchors(sidenote) {
  //https://stackoverflow.com/questions/8579643/how-to-scroll-up-or-down-the-page-to-an-anchor-using-jquery
  $('a[href^="#"]').click(function (e) {
    e.preventDefault();
    var dest = $(this).attr('href');

    if (!dest.startsWith('#note-')) {
      var aname = "a[name='".concat(dest.substr(1), "']");
      var top = $(aname).offset().top;
      console.log(aname);

      if (dest.startsWith('#aname-')) {
        top -= sidenote.setup.padVertBetweenNotes;
      }

      $('html,body').animate({
        scrollTop: top
      }, 'slow');
    }
  });
}

function colorSnippets() {
  $(".snippets-table").each(function (i, elem) {
    $(elem).children('div').filter(":even").css('background-color', "#eee");
  });
}

function copyStepsAfterRoot(sidenote, firstStepName) {
  sidenote.cloneNote(firstStepName, 0);
  sidenote.positionNewNoteBelow(firstStepName, 0);
  sidenote.expand(firstStepName, 0);
  var columnSelector = "[data-column='0']";
  var noteSelector = "".concat(columnSelector, " .note");
  $(noteSelector).each(function (i, elem) {
    var noteName = $(elem).data('note-name');
    var html = "<a name='aname-".concat(noteName, "'></a>");
    $(elem).find('h2').wrap(html);
  });
}

function setupLinksToNotes(sidenote) {
  $('div[data-column="0"] a[href^="#note-"]').click(function (event) {
    event.preventDefault(); // TODO: does this do anything?

    var fromColumnNumber = findColumnNumber(this);
    var fromNoteName = findNoteName(this);
    var toNoteName = $(this).attr('href').substr(1);
    var linkId = parseInt($(this).attr('data-link-id'));
    sidenote.clickNoteLink(fromColumnNumber, fromNoteName, toNoteName, linkId);
  });
}