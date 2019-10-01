"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _get(target, property, receiver) { if (typeof Reflect !== "undefined" && Reflect.get) { _get = Reflect.get; } else { _get = function _get(target, property, receiver) { var base = _superPropBase(target, property); if (!base) return; var desc = Object.getOwnPropertyDescriptor(base, property); if (desc.get) { return desc.get.call(receiver); } return desc.value; }; } return _get(target, property, receiver || target); }

function _superPropBase(object, property) { while (!Object.prototype.hasOwnProperty.call(object, property)) { object = _getPrototypeOf(object); if (object === null) break; } return object; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

var ReplSidenote =
/*#__PURE__*/
function (_Sidenote) {
  _inherits(ReplSidenote, _Sidenote);

  function ReplSidenote(ordering) {
    var _this;

    _classCallCheck(this, ReplSidenote);

    _this = _possibleConstructorReturn(this, _getPrototypeOf(ReplSidenote).call(this, ordering));
    _this.replConsoleWrapperId = 'repl-console-wrapper';

    _this.newRepl();

    _this.positionRepl();

    _this.setupScrollMonitor();

    return _this;
  }

  _createClass(ReplSidenote, [{
    key: "newRepl",
    value: function newRepl() {
      var replSetup = {
        shellDivId: 'repl'
      };
      this.repl = new ZDJS_Shell(replSetup); // log is declared in index.html

      var THIS = this;

      log = function log() {
        var _THIS$repl;

        (_THIS$repl = THIS.repl).consoleLog.apply(_THIS$repl, arguments);
      };

      var top = $(window).scrollTop();
      $("#".concat(this.replConsoleWrapperId)).css('top', top);
    }
  }, {
    key: "positionRepl",
    value: function positionRepl() {
      var column = $('.column').filter(':last');
      var left = parseInt(column.css('left')) + parseInt(column.css('width')) + this.setup.padBetweenColumns;
      $("#".concat(this.replConsoleWrapperId)).css('left', left);
    }
  }, {
    key: "clickNoteLink",
    value: function clickNoteLink(fromColumnNumber, fromNoteName, toNoteName, linkId) {
      _get(_getPrototypeOf(ReplSidenote.prototype), "clickNoteLink", this).call(this, fromColumnNumber, fromNoteName, toNoteName, linkId);

      this.positionRepl();
    }
  }, {
    key: "clearAfter",
    value: function clearAfter(columnNumber) {
      _get(_getPrototypeOf(ReplSidenote.prototype), "clearAfter", this).call(this, columnNumber);

      this.positionRepl();
    }
  }, {
    key: "setupScrollMonitor",
    value: function setupScrollMonitor() {
      var THIS = this;
      $(window).scroll(function () {
        var top = $(window).scrollTop();
        $("#".concat(THIS.replConsoleWrapperId)).css('top', top);
      });
    }
  }]);

  return ReplSidenote;
}(Sidenote);