function findColumnNumber(elem) {
    var ancestor = $(elem).parent()[0];
    var i = 0;
    while (i < 100 && $(ancestor).data('column') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return parseInt($(ancestor).data('column'));
}

function findNoteName(elem) {
    var ancestor = $(elem).parent()[0];
    var i = 0;
    while (i < 100 && $(ancestor).data('note-name') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return $(ancestor).data('note-name');
}


class Sidenote {
    constructor() {}

    clickNoteLink(fromColumnNumber, fromNoteName) {
        console.log(fromColumnNumber, fromNoteName);
    }
}