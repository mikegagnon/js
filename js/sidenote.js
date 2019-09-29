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
    sidenoteId: 'sidenote',
    stagingId: 'staging-area'
}

class Sidenote {
    constructor() {
        this.setup = SidenoteSetup;
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName) {
        console.log(fromColumnNumber, fromNoteName, toNoteName);
        this.clearAfter(fromColumnNumber);
        //console.log();
        const newColumnNumber = fromColumnNumber + 1;
        this.newColumn(newColumnNumber);
        this.cloneNote(toNoteName, newColumnNumber);

        const top = $(`[data-note-name='${fromNoteName}']`).offset().top;
        this.positionNewColumn(newColumnNumber, top);
    }

    clearAfter(fromColumnNumber) {
    }

    newColumn(newColumnNumber) {
        const html = `<div data-column='${newColumnNumber}' class='column'></div>`
        $('#' + this.setup.sidenoteId).append(html);
    }

    cloneNote(toNoteName, newColumnNumber) {
        const stagedNote = $(`#${this.setup.stagingId} [data-note-name='${toNoteName}']`);
        console.log(stagedNote);
        const clone = stagedNote.clone();
        clone.appendTo(`[data-column='${newColumnNumber}']`);
    }

    positionNewColumn(newColumnNumber, top) {
        const selector = `[data-column='${newColumnNumber}']`;
        $(selector).css('top', top);
        $(selector).css('left', 100);

    }
}