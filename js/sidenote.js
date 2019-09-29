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
    padVertBetweenNotes: 0,

}

class Sidenote {
    constructor(ordering) {
        this.setup = SidenoteSetup;
        this.ordering = ordering;
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName) {
        console.log(fromColumnNumber, fromNoteName, toNoteName);
        this.clearAfter(fromColumnNumber);
        //console.log();
        const newColumnNumber = fromColumnNumber + 1;
        this.newColumn(newColumnNumber);
        this.cloneNote(toNoteName, newColumnNumber);
        this.positionNewNote(newColumnNumber, fromNoteName, toNoteName);
        //this.setupNoteLinks(newColumnNumber, toNoteName);
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

        const THIS = this;
        clone.find(`a[href^="#note-"]`).click(function() {
            const linkToNoteName = $(this).attr('href').substr(1);
            THIS.clickNoteLink(newColumnNumber, toNoteName, linkToNoteName);
        });

        clone.find('.expand-button').click(function() {
            THIS.expand(toNoteName, newColumnNumber);
        });
    }

    expand(noteName, columnNumber) {
        if (this.ordering.includes(noteName)) {
            const index = this.ordering.findIndex(n => n === noteName);
            if (index > 0) {
                this.expandAbove(index, columnNumber);
            }
            if (index < this.ordering.length - 1) {
                this.expandBelow(index, columnNumber);
            }
        }
    }

    expandAbove(index, columnNumber) {
        for (var i = index - 1; i >= 0; i--) {
            this.expandAboveSingle(i, columnNumber);
        }
    }

    expandAboveSingle(index, columnNumber) {
        const newNoteName = this.ordering[index];
        const belowNoteName = this.ordering[index + 1];
        this.cloneNote(newNoteName, columnNumber);
        this.positionNewNoteAbove(newNoteName, columnNumber, belowNoteName);
   
        //console.log("expandAboveSingle", index, columnNumber);
    }

    expandBelow(index, columnNumber) {
        console.log("expandBelow", index, columnNumber);
    }

    positionNewNoteAbove(newNoteName, columnNumber, belowNoteName) {
        const columnSelector = `[data-column='${columnNumber}']`;
        const columnWidth = parseInt($(columnSelector).css('width'));
        const columnLeft = parseInt($(columnSelector).css('left'));

        const belowNoteSelector = `${columnSelector} [data-note-name='${belowNoteName}']`;
        const belowNoteTop = parseInt($(belowNoteSelector).css('top'));

        const newNoteSelector = `${columnSelector} [data-note-name='${newNoteName}']`;
        const newNoteHeight = $(newNoteSelector).height();

        const top = belowNoteTop - this.setup.padVertBetweenNotes - newNoteHeight;
        $(newNoteSelector).css('top', top);
        console.log($(newNoteSelector).css('top')); 
    }

    positionNewNote(newColumnNumber, fromNoteName, toNoteName) {
        const oldColumnNumber = newColumnNumber - 1;
        const oldColumnSelector = `[data-column='${oldColumnNumber}']`;
        const oldColumnWidth = parseInt($(oldColumnSelector).css('width'));
        const oldColumnLeft = parseInt($(oldColumnSelector).css('left'));
        
        const fromNoteSelector =`${oldColumnSelector} [data-note-name='${fromNoteName}']`;
        let top = parseInt($(fromNoteSelector).css('top'));
        const scrollTop = $(window).scrollTop();
        console.log("top", top);
        console.log("scrollTop", scrollTop);

        if (top < scrollTop) {
            top = scrollTop;
        }
        
        const newColumnLeft = oldColumnLeft + oldColumnWidth + this.setup.padBetweenColumns;
        const newColumnSelector = `[data-column='${newColumnNumber}']`;
        $(newColumnSelector).css('left', newColumnLeft);

        const newNoteSelector = `${newColumnSelector} [data-note-name='${toNoteName}']`;
        $(newNoteSelector).css('top', top);
        console.log($(newNoteSelector).css('top'));
    }

}