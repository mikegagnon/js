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
    autoExpand: 'jit', // possible values: 'expand-all' and 'jit'. TODO: explain
}

class Sidenote {
    constructor(ordering) {
        this.setup = SidenoteSetup;
        this.ordering = ordering;
        this.linkstack = [];
        this.setupColumn0();
        this.setupLinkId();
        this.setupAutoExpandJit();
    }

    setupAutoExpandJit() {
        if (this.setup.autoExpand === 'jit') {
            const THIS = this;
            $(window).scroll(function() {
                const scrollTop = $(window).scrollTop();
                THIS.scroll(scrollTop);
            });
        } else if (this.setup.autoExpand === 'expand-all') {
            //pass
        } else {
            throw "Bad value for autoExpand";
        }
    }

    scroll(scrollTop) {
        this.scrollUp(scrollTop);
        this.scrollDown(scrollTop);
        //console.log("scroll");
    }

    scrollUp(scrollTop) {
        var THIS = this;
        $('.column').each(function(i, column){
            const notes = $(column).children();

            if (notes.length >= 1) {
                let minNote = notes[0];
                let minTop = parseInt($(minNote).css('top'));;
                for (var i = 1; i < notes.length; i++) {
                    const top = parseInt($(notes[i]).css('top'));
                    if (top < minTop) {
                        minTop = top;
                        minNote = notes[i];
                    }
                }

                if ($(minNote).data('note-type') === 'step') {
                    THIS.perhapsLoadNoteAbove(scrollTop, column, minNote);
                }
            }
            //THIS.perhapsLoadNoteAbove(column)

        })
    }

    perhapsLoadNoteAbove(scrollTop, column, note) {
        //console.log(note);
        const top = parseInt($(column).css('top'));
        const noteName = $(note).data('note-name');
        const columnNumber = parseInt($(column).data('column'));

        if (scrollTop <= top) {
            console.log(top);
            console.log(noteName);
            console.log(columnNumber);
            const index = this.ordering.findIndex(n => n === noteName);
            console.log('index', index);
            this.expandAboveSingle(index - 1, columnNumber);
        }
    }

    scrollDown(scrollTop) {

    }

    setupLinkId() {
        let n = 0;
        $(document)
            .find('[href^="#note-"]')
            .each(function(i, elem){
                $(elem).attr('data-link-id', n++);
            });
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

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName, linkId) {
        this.clearAfter(fromColumnNumber);
        const newColumnNumber = fromColumnNumber + 1;
        this.newColumn(newColumnNumber);
        this.cloneNote(toNoteName, newColumnNumber);
        this.positionNewNote(newColumnNumber, fromNoteName, toNoteName);
        this.linkstack.push(linkId);
        this.updateUrl();
        this.highlightLink(fromColumnNumber, linkId);
        this.spacer(newColumnNumber);
        if (this.setup.autoExpand === 'expand-all') {
            const columnSelector = `[data-column='${newColumnNumber}']`;
            const newNoteSelector = `${columnSelector} [data-note-name='${toNoteName}']`;
            $(newNoteSelector + ' h2').addClass('expanded')
            this.expand(toNoteName, newColumnNumber);
        } else if (this.setup.autoExpand === 'jit') {
            // pass
        } else {
            throw "Bad value for autoExpand";
        }
    }

    columnsWidth(newColumnNumber) {
        const columnSelector = `[data-column='${newColumnNumber}']`;
        const left = parseInt($(columnSelector).css('left'));
        const columnWidth = parseInt($(columnSelector).css('width'));
        return left + columnWidth;
    }

    spacer(newColumnNumber) {
        const colsWidth = this.columnsWidth(newColumnNumber);
        const spacerWidth = parseInt($("#spacer").css('width'));
        const width = Math.max(spacerWidth, colsWidth);
        $("#spacer").css('width', width);
    }

    updateUrl() {
        let url = undefined;
        if (this.linkstack.length == 0) {
            url = window.location.pathname;
        } else {
            url = window.location.pathname + `?stack=` + this.linkstack.join("!");
        }

        history.replaceState({}, "", url);
    }

    highlightLink(columnNumber, linkId) {
        const columnSelector = `[data-column='${columnNumber}']`;

        $(columnSelector).find('a').removeClass('link-clicked');

        const linkSelector = `${columnSelector} [data-link-id='${linkId}']`;
        $(linkSelector).addClass('link-clicked');
    }

    clearAfter(columnNumber) {
        $('.column')
            .filter(function(i){ return i > columnNumber; })
            .remove();
        this.linkstack = this.linkstack.slice(0, columnNumber);
        this.updateUrl();
        const columnSelector = `[data-column='${columnNumber}']`;
        $(columnSelector).find('a').removeClass('link-clicked')

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
            const linkId = parseInt($(this).attr('data-link-id'));
            THIS.clickNoteLink(newColumnNumber, toNoteName, linkToNoteName, linkId);
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
        const sTop = $(window).scrollTop();
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
            $(window).scrollTop(sTop - tallestColumnTop);
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

    loadViewFromUrl(clickLink) {

        // https://stackoverflow.com/questions/4656843/jquery-get-querystring-from-url
        // Read a page's GET URL variables and return them as an associative array.
        function getUrlVars()
        {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        var vars = getUrlVars();
        if (!vars.stack) {
            return;
        }
        
        var stack = vars.stack.split("!");
        if (stack.length == 0) {
            return;
        }

        for (let i = 0; i < stack.length; i++) {
            var linkId = parseInt(stack[i]);
            if (Number.isNaN(linkId)) {
                return;
            }
        }

        for (let i = 0; i < stack.length; i++) {
            const linkId = parseInt(stack[i]);
            const columnSelector = `[data-column='${i}']`;
            const linkSelector = `${columnSelector} [data-link-id='${linkId}']`;
            const link = $(linkSelector)[0];
            const fromNoteName = findNoteName(link);
            const toNoteName = $(linkSelector).attr('href').substr(1);
            const top =  $(linkSelector).offset().top;
            $(window).scrollTop(top);
            this.clickNoteLink(i, fromNoteName, toNoteName, linkId);
        }

        const almostMaxColNumber = stack.length - 1;
        const columnSelector = `[data-column='${almostMaxColNumber}']`;
        const left = parseInt($(columnSelector).css('left'));
        $(window).scrollLeft(left);
    }

}