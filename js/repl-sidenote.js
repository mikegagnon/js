class ReplSidenote extends Sidenote {
    constructor(ordering) {
        super(ordering);
        this.replConsoleId = "repl-console";
        this.positionRepl();
    }

    positionRepl() {
        const column = $('.column').filter(':last');
        const left = parseInt(column.css('left')) + parseInt(column.css('width')) + this.setup.padVertBetweenNotes;
        $(`#${this.replConsoleId}`).css('left', left);

    }
}


