class ReplSidenote extends Sidenote {
    constructor(ordering) {
        super(ordering);
        this.replConsoleWrapperId = 'repl-console-wrapper';
        this.newRepl();
        this.positionRepl();
        this.setupScrollMonitor();
    }

    newRepl() {
        const replSetup = { shellDivId: 'repl' };
        this.repl = new ZDJS_Shell(replSetup);
        var top = $(window).scrollTop();
        $(`#${this.replConsoleWrapperId}`).css('top', top);
    }

    positionRepl() {
        const column = $('.column').filter(':last');
        const left = parseInt(column.css('left')) + parseInt(column.css('width')) + this.setup.padBetweenColumns;
        $(`#${this.replConsoleWrapperId}`).css('left', left);
    }

    clickNoteLink(fromColumnNumber, fromNoteName, toNoteName, linkId) {
        super.clickNoteLink(fromColumnNumber, fromNoteName, toNoteName, linkId);
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
            $(`#${THIS.replConsoleWrapperId}`).css('top', top);
        });
    }
}
