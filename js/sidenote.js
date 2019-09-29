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
    stagingId: 'staging-area',
    padBetweenColumns: 20,
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
        this.positionNewNote(newColumnNumber, fromNoteName, toNoteName);
        this.setupNoteLinks(newColumnNumber, toNoteName);
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

    positionNewNote(newColumnNumber, fromNoteName, toNoteName) {
        const fromNoteSelector =`[data-note-name='${fromNoteName}']`;
        let top = $(fromNoteSelector).css('top');
        //top -= parseInt($(selector).css('margin-top'));
        //console.log($(selector).css('margin-top'));

        const oldColumnNumber = newColumnNumber - 1;
        const oldColumnSelector = `[data-column='${oldColumnNumber}']`;
        const oldColumnWidth = parseInt($(oldColumnSelector).css('width'));
        const oldColumnLeft = parseInt($(oldColumnSelector).css('left'));
        
        const newColumnLeft = oldColumnLeft + oldColumnWidth + this.setup.padBetweenColumns;
        const newColumnSelector = `[data-column='${newColumnNumber}']`;
        $(newColumnSelector).css('left', newColumnLeft);

        const newNoteSelector =`[data-note-name='${toNoteName}']`;
        $(newNoteSelector).css('top', top);
    }

    setupNoteLinks(newColumnNumber, newNoteName) {
        $(`[data-column='${newColumnNumber}'] div[data-note-name='${newNoteName}'] a[href^="#note-"]`).click(function() {
            var toNoteName = $(this).attr('href').substr(1);
            SIDENOTE.clickNoteLink(newColumnNumber, newNoteName, toNoteName);
        });
    }
}