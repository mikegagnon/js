'use strict'

function findColumnNumber(elem) {
    let ancestor = $(elem).parent()[0];
    let i = 0;
    while (i < 100 && $(ancestor).data('column') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return parseInt($(ancestor).data('column'));
}

function findNoteName(elem) {
    let ancestor = $(elem).parent()[0];
    let i = 0;
    while (i < 100 && $(ancestor).data('note-name') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return $(ancestor).data('note-name');
}

const SidenoteSetup = {
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
        const newColumnNumber = fromColumnNumber + 1;
        this.newColumn(newColumnNumber);
    }

    clearAfter(fromColumnNumber) {
    }

    newColumn(newColumnNumber) {
        const html = `<div data-column='${newColumnNumber}' class='column'></div>`
        $('#' + this.setup.sidenoteId).append(html);
    }
}