class ReplSidenote extends Sidenote {
    constructor(ordering) {
        super(ordering);
        this.replConsoleId = "repl-console";
        this.newRepl();
        this.positionRepl();
        this.setupScrollMonitor();
    }

    newRepl() {
        const replSetup = { shellDivId: "repl" };
        this.repl = new ZDJS_Shell(replSetup);
        var top = $(window).scrollTop();
        $(`#${this.replConsoleId}`).css('top', top);
    }

    positionRepl() {
        const column = $('.column').filter(':last');
        const left = parseInt(column.css('left')) + parseInt(column.css('width')) + this.setup.padVertBetweenNotes;
        $(`#${this.replConsoleId}`).css('left', left);
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName) {
        super.clickNoteLink(fromColumnNumber, fromNoteName, toNoteName);
        this.positionRepl();
    }

    clearAfter(columnNumber) {
        super.clearAfter(columnNumber);
        this.positionRepl();
    }

    setupScrollMonitor() {
        var THIS = this;
        $(window).scroll(function(){
            var top = $(window).scrollTop();
            $(`#${THIS.replConsoleId}`).css("top", top);
        });
    }
}
