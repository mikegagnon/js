function findNoteAncestor(elem) {
    var ancestor = $(elem).parent()[0];
    var i = 0;
    while (i < 100 && $(ancestor).data('name') === undefined) {
        ancestor = $(ancestor).parent()[0];
        i++;
    }
    return ancestor
}

class Sidenote {}