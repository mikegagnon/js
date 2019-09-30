class ReplSidenote extends Sidenote {
    constructor(ordering) {
        super(ordering);
        this.replConsoleId = "repl-console";
        this.newRepl();
        this.positionRepl();
    }

    newRepl() {
        const replSetup = { shellDivId: "repl" };
        this.repl = new ZDJS_Shell(replSetup);
    }

    positionRepl() {
        const column = $('.column').filter(':last');
        const left = parseInt(column.css('left')) + parseInt(column.css('width')) + this.setup.padVertBetweenNotes;
        $(`#${this.replConsoleId}`).css('left', left);
        $(`#${this.replConsoleId}`).css('top', 0);
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName) {
        super.clickNoteLink(fromColumnNumber, fromNoteName, toNoteName);
        this.positionRepl();
    }

    clearAfter(columnNumber) {
        super.clearAfter(columnNumber);
        this.positionRepl();
    }
}


