'use strict';

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

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

var SidenoteSetup = {
  sidenoteId: 'sidenote',
  stagingId: 'staging-area',
  padBetweenColumns: 20,
  padVertBetweenNotes: 0,
  autoExpand: true
};

var Sidenote =
/*#__PURE__*/
function () {
  function Sidenote(ordering) {
    _classCallCheck(this, Sidenote);

    this.setup = SidenoteSetup;
    this.ordering = ordering;
    this.linkstack = [];
    this.setupColumn0();
    this.setupLinkId();
  }

  _createClass(Sidenote, [{
    key: "setupLinkId",
    value: function setupLinkId() {
      var n = 0;
      $(document).find('[href^="#note-"]').each(function (i, elem) {
        $(elem).attr('data-link-id', n++);
      });
    }
  }, {
    key: "getColumnHeight",
    value: function getColumnHeight(columnNumber) {
      var columnSelector = "[data-column='".concat(columnNumber, "']");
      var noteSelector = "".concat(columnSelector, " .note");
      var height = -this.setup.padVertBetweenNotes;
      var THIS = this;
      $(noteSelector).each(function (i, elem) {
        height += $(elem).outerHeight(true) + THIS.setup.padVertBetweenNotes;
      });
      return height;
    }
  }, {
    key: "setupColumn0",
    value: function setupColumn0() {
      var columnSelector = '[data-column="0"]';
      $(columnSelector).css('top', 0);
      $(columnSelector).css('left', 0);
      var height = this.getColumnHeight(0);
      $(columnSelector).css('height', height);
    }
  }, {
    key: "clickNoteLink",
    value: function clickNoteLink(fromColumnNumber, fromNoteName, toNoteName, linkId) {
      this.clearAfter(fromColumnNumber);
      var newColumnNumber = fromColumnNumber + 1;
      this.newColumn(newColumnNumber);
      this.cloneNote(toNoteName, newColumnNumber);
      this.positionNewNote(newColumnNumber, fromNoteName, toNoteName);
      this.linkstack.push(linkId);
      this.updateUrl();
      this.highlightLink(fromColumnNumber, linkId);
      this.spacer(newColumnNumber);

      if (this.setup.autoExpand) {
        var columnSelector = "[data-column='".concat(newColumnNumber, "']");
        var newNoteSelector = "".concat(columnSelector, " [data-note-name='").concat(toNoteName, "']");
        $(newNoteSelector + ' h2').addClass('expanded');
        this.expand(toNoteName, newColumnNumber);
      }
    }
  }, {
    key: "columnsWidth",
    value: function columnsWidth(newColumnNumber) {
      var columnSelector = "[data-column='".concat(newColumnNumber, "']");
      var left = parseInt($(columnSelector).css('left'));
      var columnWidth = parseInt($(columnSelector).css('width'));
      return left + columnWidth;
    }
  }, {
    key: "spacer",
    value: function spacer(newColumnNumber) {
      var colsWidth = this.columnsWidth(newColumnNumber);
      var spacerWidth = parseInt($("#spacer").css('width'));
      var width = Math.max(spacerWidth, colsWidth);
      $("#spacer").css('width', width);
    }
  }, {
    key: "updateUrl",
    value: function updateUrl() {
      var url = undefined;

      if (this.linkstack.length == 0) {
        url = window.location.pathname;
      } else {
        url = window.location.pathname + "?stack=" + this.linkstack.join("!");
      }

      history.replaceState({}, "", url);
    }
  }, {
    key: "highlightLink",
    value: function highlightLink(columnNumber, linkId) {
      var columnSelector = "[data-column='".concat(columnNumber, "']");
      $(columnSelector).find('a').removeClass('link-clicked');
      var linkSelector = "".concat(columnSelector, " [data-link-id='").concat(linkId, "']");
      $(linkSelector).addClass('link-clicked');
    }
  }, {
    key: "clearAfter",
    value: function clearAfter(columnNumber) {
      $('.column').filter(function (i) {
        return i > columnNumber;
      }).remove();
      this.linkstack = this.linkstack.slice(0, columnNumber);
      this.updateUrl();
      var columnSelector = "[data-column='".concat(columnNumber, "']");
      $(columnSelector).find('a').removeClass('link-clicked');
    }
  }, {
    key: "newColumn",
    value: function newColumn(newColumnNumber) {
      var html = "<div data-column='".concat(newColumnNumber, "' class='column'></div>");
      $('#' + this.setup.sidenoteId).append(html);
    }
  }, {
    key: "cloneNote",
    value: function cloneNote(toNoteName, newColumnNumber) {
      var stagedNote = $("#".concat(this.setup.stagingId, " [data-note-name='").concat(toNoteName, "']"));
      var clone = stagedNote.clone();
      clone.appendTo("[data-column='".concat(newColumnNumber, "']"));
      var THIS = this;
      clone.find("a[href^=\"#note-\"]").click(function () {
        var linkToNoteName = $(this).attr('href').substr(1);
        var linkId = parseInt($(this).attr('data-link-id'));
        THIS.clickNoteLink(newColumnNumber, toNoteName, linkToNoteName, linkId);
      });
      clone.find('.expand-button').click(function () {
        THIS.expand(toNoteName, newColumnNumber);
      });
      clone.find('.close-button').click(function () {
        THIS.close(newColumnNumber);
      });
    }
  }, {
    key: "close",
    value: function close(columnNumber) {
      this.clearAfter(columnNumber - 1);
      var sTop = $(window).scrollTop();
      var tallestColumnTop = undefined;
      $('.column').each(function (i, elem) {
        if (tallestColumnTop === undefined) {
          tallestColumnTop = parseInt($(elem).css('top'));
        } else {
          var thisTop = parseInt($(elem).css('top'));
          tallestColumnTop = Math.min(tallestColumnTop, thisTop);
        }
      });

      if (tallestColumnTop > 0) {
        $('.column').each(function (i, elem) {
          var thisTop = parseInt($(elem).css('top'));
          var newTop = thisTop - tallestColumnTop;
          $(elem).css('top', newTop);
        });
        $(window).scrollTop(sTop - tallestColumnTop);
      }
    }
  }, {
    key: "expand",
    value: function expand(noteName, columnNumber) {
      if (this.ordering.includes(noteName)) {
        var index = this.ordering.findIndex(function (n) {
          return n === noteName;
        });

        if (index > 0) {
          this.expandAbove(index, columnNumber);
        }

        if (index < this.ordering.length - 1) {
          this.expandBelow(index, columnNumber);
        }
      }

      var columnSelector = "[data-column='".concat(columnNumber, "']");
      var expandButtonSelector = "".concat(columnSelector, " .expand-button");
      $(expandButtonSelector).remove();
    }
  }, {
    key: "expandAbove",
    value: function expandAbove(index, columnNumber) {
      for (var i = index - 1; i >= 0; i--) {
        this.expandAboveSingle(i, columnNumber);
      }
    }
  }, {
    key: "expandAboveSingle",
    value: function expandAboveSingle(index, columnNumber) {
      var newNoteName = this.ordering[index];
      this.cloneNote(newNoteName, columnNumber);
      this.positionNewNoteAbove(newNoteName, columnNumber);
    }
  }, {
    key: "expandBelow",
    value: function expandBelow(index, columnNumber) {
      for (var i = index + 1; i < this.ordering.length; i++) {
        this.expandBelowSingle(i, columnNumber);
      }
    }
  }, {
    key: "expandBelowSingle",
    value: function expandBelowSingle(index, columnNumber) {
      var newNoteName = this.ordering[index];
      this.cloneNote(newNoteName, columnNumber);
      this.positionNewNoteBelow(newNoteName, columnNumber);
    }
  }, {
    key: "positionNewNoteAbove",
    value: function positionNewNoteAbove(newNoteName, columnNumber) {
      var columnSelector = "[data-column='".concat(columnNumber, "']");
      var newNoteSelector = "".concat(columnSelector, " [data-note-name='").concat(newNoteName, "']");
      var newNoteHeight = $(newNoteSelector).outerHeight(true);
      var top = parseInt($(columnSelector).css('top')) - this.setup.padVertBetweenNotes - newNoteHeight;
      $(columnSelector).css('top', top);
      $(newNoteSelector).css('top', -newNoteHeight - this.setup.padVertBetweenNotes);
      var columnHeight = this.getColumnHeight(columnNumber);
      $(columnSelector).css('height', columnHeight);
      var noteSelector = "".concat(columnSelector, " .note");
      var THIS = this;
      $(noteSelector).each(function (i, elem) {
        var top = parseInt($(elem).css('top'));
        var newTop = top + newNoteHeight + THIS.setup.padVertBetweenNotes;
        $(elem).css('top', newTop);
      });
      var columnTop = parseInt($(columnSelector).css('top'));

      if (columnTop < 0) {
        var scrollTop = $(window).scrollTop();
        $('.column').each(function (i, elem) {
          var currentColumnTop = parseInt($(elem).css('top'));
          currentColumnTop -= columnTop;
          $(elem).css('top', currentColumnTop);
        });
        var newScrollTop = scrollTop - columnTop;
        $(window).scrollTop(newScrollTop);
      }
    }
  }, {
    key: "positionNewNoteBelow",
    value: function positionNewNoteBelow(newNoteName, columnNumber) {
      var columnSelector = "[data-column='".concat(columnNumber, "']");
      var newNoteSelector = "".concat(columnSelector, " [data-note-name='").concat(newNoteName, "']");
      var columnHeight = this.getColumnHeight(columnNumber);
      var newNoteHeight = $(newNoteSelector).outerHeight(true);
      var columnTop = parseInt($(columnSelector).css('top'));
      var newTop = columnHeight - newNoteHeight;
      $(newNoteSelector).css('top', newTop);
      $(columnSelector).css('height', columnHeight);
    }
  }, {
    key: "positionNewNote",
    value: function positionNewNote(newColumnNumber, fromNoteName, toNoteName) {
      var oldColumnNumber = newColumnNumber - 1;
      var oldColumnSelector = "[data-column='".concat(oldColumnNumber, "']");
      var oldColumnWidth = parseInt($(oldColumnSelector).css('width'));
      var oldColumnLeft = parseInt($(oldColumnSelector).css('left'));
      var fromNoteSelector = "".concat(oldColumnSelector, " [data-note-name='").concat(fromNoteName, "']");
      var top = parseInt($(oldColumnSelector).css('top'));
      var scrollTop = $(window).scrollTop();

      if (top < scrollTop) {
        top = scrollTop;
      }

      var newColumnLeft = oldColumnLeft + oldColumnWidth + this.setup.padBetweenColumns;
      var newColumnSelector = "[data-column='".concat(newColumnNumber, "']");
      $(newColumnSelector).css('left', newColumnLeft);
      var newNoteSelector = "".concat(newColumnSelector, " [data-note-name='").concat(toNoteName, "']");
      $(newNoteSelector).css('top', 0);
      $(newColumnSelector).css('top', top);
      var height = this.getColumnHeight(newColumnNumber);
      $(newColumnSelector).css('height', height);
    }
  }, {
    key: "loadViewFromUrl",
    value: function loadViewFromUrl(clickLink) {
      // https://stackoverflow.com/questions/4656843/jquery-get-querystring-from-url
      // Read a page's GET URL variables and return them as an associative array.
      function getUrlVars() {
        var vars = [],
            hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');

        for (var i = 0; i < hashes.length; i++) {
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

      for (var i = 0; i < stack.length; i++) {
        var linkId = parseInt(stack[i]);

        if (Number.isNaN(linkId)) {
          return;
        }
      }

      for (var _i = 0; _i < stack.length; _i++) {
        var _linkId = parseInt(stack[_i]);

        var _columnSelector = "[data-column='".concat(_i, "']");

        var linkSelector = "".concat(_columnSelector, " [data-link-id='").concat(_linkId, "']");
        var link = $(linkSelector)[0];
        var fromNoteName = findNoteName(link);
        var toNoteName = $(linkSelector).attr('href').substr(1);
        var top = $(linkSelector).offset().top;
        $(window).scrollTop(top);
        this.clickNoteLink(_i, fromNoteName, toNoteName, _linkId);
      }

      var almostMaxColNumber = stack.length - 1;
      var columnSelector = "[data-column='".concat(almostMaxColNumber, "']");
      var left = parseInt($(columnSelector).css('left'));
      $(window).scrollLeft(left);
    }
  }]);

  return Sidenote;
}();