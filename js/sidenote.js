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
    padVertBetweenNotes: 0, //20,
    autoExpand: false,
}

class Sidenote {
    constructor(ordering) {
        this.setup = SidenoteSetup;
        this.ordering = ordering;
        this.setupColumn0();
    }

    getColumnHeight(columnNumber) {
        const columnSelector = `[data-column='${columnNumber}']`;
        const noteSelector = `${columnSelector} .note`;
        let height = -this.setup.padVertBetweenNotes;
        const THIS = this;
        $(noteSelector).each(function(i, elem){
            height += $(elem).outerHeight(true) + THIS.setup.padVertBetweenNotes;
        });
        return height;
    }

    setupColumn0() {
        const columnSelector = '[data-column="0"]';
        $(columnSelector).css('top', 0);
        $(columnSelector).css('left', 0);
        const height = this.getColumnHeight(0)
        $(columnSelector).css('height', height);
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName) {
        this.clearAfter(fromColumnNumber);
        const newColumnNumber = fromColumnNumber + 1;
        this.newColumn(newColumnNumber);
        this.cloneNote(toNoteName, newColumnNumber);
        this.positionNewNote(newColumnNumber, fromNoteName, toNoteName);
        if (this.setup.autoExpand) {
            console.log(toNoteName, newColumnNumber);
            const columnSelector = `[data-column='${newColumnNumber}']`;
            const newNoteSelector = `${columnSelector} [data-note-name='${toNoteName}']`;
            $(newNoteSelector).addClass('expanded')

            this.expand(toNoteName, newColumnNumber);

        
        }
    }

    clearAfter(columnNumber) {
        $('.column')
            .filter(function(i){ console.log(i, i > columnNumber); return i > columnNumber; })
            .remove();
    }

    newColumn(newColumnNumber) {
        const html = `<div data-column='${newColumnNumber}' class='column'></div>`
        $('#' + this.setup.sidenoteId).append(html);
    }

    cloneNote(toNoteName, newColumnNumber) {
        const stagedNote = $(`#${this.setup.stagingId} [data-note-name='${toNoteName}']`);
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

        clone.find('.close-button').click(function() {
            THIS.close(newColumnNumber);
        });
    }

    close(columnNumber) {
        this.clearAfter(columnNumber - 1);

        let tallestColumnTop = undefined;
        $('.column').each(function(i, elem){
            if (tallestColumnTop === undefined) {
                tallestColumnTop = parseInt($(elem).css('top'));
            } else {
                const thisTop = parseInt($(elem).css('top'))
                tallestColumnTop = Math.min(tallestColumnTop, thisTop);
            }
        });

        if (tallestColumnTop > 0) {
            $('.column').each(function(i, elem){
                const thisTop = parseInt($(elem).css('top'))
                const newTop = thisTop - tallestColumnTop;
                $(elem).css('top', newTop);
            });
            $(window).scrollTop(0);
        }


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

        const columnSelector = `[data-column='${columnNumber}']`;
        const expandButtonSelector = `${columnSelector} .expand-button`;
        $(expandButtonSelector).remove();
    }

    expandAbove(index, columnNumber) {
        for (let i = index - 1; i >= 0; i--) {
            this.expandAboveSingle(i, columnNumber);
        }
    }

    expandAboveSingle(index, columnNumber) {
        const newNoteName = this.ordering[index];
        this.cloneNote(newNoteName, columnNumber);
        this.positionNewNoteAbove(newNoteName, columnNumber);
    }

    expandBelow(index, columnNumber) {
        for (let i = index + 1; i < this.ordering.length; i++) {
            this.expandBelowSingle(i, columnNumber);
        }
    }

    expandBelowSingle(index, columnNumber) {
        const newNoteName = this.ordering[index];
        this.cloneNote(newNoteName, columnNumber);
        this.positionNewNoteBelow(newNoteName, columnNumber);
    }

    positionNewNoteAbove(newNoteName, columnNumber) {
        const columnSelector = `[data-column='${columnNumber}']`;
        const newNoteSelector = `${columnSelector} [data-note-name='${newNoteName}']`;
        const newNoteHeight = $(newNoteSelector).outerHeight(true);
        const top = parseInt($(columnSelector).css('top')) - this.setup.padVertBetweenNotes - newNoteHeight;
        $(columnSelector).css('top', top);
        $(newNoteSelector).css('top', -newNoteHeight - this.setup.padVertBetweenNotes);
        const columnHeight = this.getColumnHeight(columnNumber);
        $(columnSelector).css('height', columnHeight);
        const noteSelector = `${columnSelector} .note`;
        const THIS = this;
        $(noteSelector).each(function(i, elem){
            const top = parseInt($(elem).css('top'));
            const newTop = top + newNoteHeight + THIS.setup.padVertBetweenNotes;
            $(elem).css('top', newTop);
        });

        const columnTop = parseInt($(columnSelector).css('top'));

        if (columnTop < 0) {
            const scrollTop = $(window).scrollTop();
            $('.column').each(function(i, elem){
                let currentColumnTop = parseInt($(elem).css('top'));
                currentColumnTop -= columnTop;
                $(elem).css('top', currentColumnTop);
            });
            const newScrollTop = scrollTop - columnTop;
            $(window).scrollTop(newScrollTop);
        }

    }

    positionNewNoteBelow(newNoteName, columnNumber) {
        const columnSelector = `[data-column='${columnNumber}']`;
        const newNoteSelector = `${columnSelector} [data-note-name='${newNoteName}']`;
        const columnHeight = this.getColumnHeight(columnNumber);
        const newNoteHeight = $(newNoteSelector).outerHeight(true);
        const columnTop = parseInt($(columnSelector).css('top'));
        const newTop = columnHeight - newNoteHeight;
        $(newNoteSelector).css('top', newTop);
        $(columnSelector).css('height', columnHeight);
    }

    positionNewNote(newColumnNumber, fromNoteName, toNoteName) {
        const oldColumnNumber = newColumnNumber - 1;
        const oldColumnSelector = `[data-column='${oldColumnNumber}']`;
        const oldColumnWidth = parseInt($(oldColumnSelector).css('width'));
        const oldColumnLeft = parseInt($(oldColumnSelector).css('left'));
        const fromNoteSelector =`${oldColumnSelector} [data-note-name='${fromNoteName}']`;
        
        let top = parseInt($(oldColumnSelector).css('top'));
        const scrollTop = $(window).scrollTop();
        if (top < scrollTop) {
            top = scrollTop;
        }
        
        const newColumnLeft = oldColumnLeft + oldColumnWidth + this.setup.padBetweenColumns;
        const newColumnSelector = `[data-column='${newColumnNumber}']`;
        $(newColumnSelector).css('left', newColumnLeft);
        const newNoteSelector = `${newColumnSelector} [data-note-name='${toNoteName}']`;
        $(newNoteSelector).css('top', 0);
        $(newColumnSelector).css('top', top);
        const height = this.getColumnHeight(newColumnNumber);
        $(newColumnSelector).css('height', height);
    }

}