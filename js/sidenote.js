'use strict'
function findColumnNumber(elem) {
    var ancestor = $(elem).parent()[0];
    var i = 0;
    while (i < 100 && $(ancestor).data('column') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return parseInt($(ancestor).data('column'));
}

function findNoteName(elem) {
    var ancestor = $(elem).parent()[0];
    var i = 0;
    while (i < 100 && $(ancestor).data('note-name') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return $(ancestor).data('note-name');
}

var SidenoteSetup = {
    sidenoteId: 'sidenote'
}

class Sidenote {
    constructor() {
        this.setup = SidenoteSetup;
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName) {
        console.log(fromColumnNumber, fromNoteName, toNoteName);
        this.clearAfter(fromColumnNumber);
        console.log($(`[data-note-name='${fromNoteName}']`).offset().top);
        var newColumnNumber = fromColumnNumber + 1;
        this.newColumn(newColumnNumber);
    }

    clearAfter(fromColumnNumber) {
    }

    newColumn(newColumnNumber) {
        var html = `<div data-column='${newColumnNumber}' class='column'></div>`
        $('#' + this.setup.sidenoteId).append(html);
    }
}