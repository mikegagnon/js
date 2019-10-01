"use strict";

var ZDJS_ShellDefaultSetup = {
  maxCommandLen: 100,
  multilineBoxInitHeight: "30px",
  // just a guess for a good value
  showMultiLinkButton: true
};

var ZDJS_Shell = function ZDJS_Shell(setup) {
  this.setup = {};
  $.extend(this.setup, ZDJS_ShellDefaultSetup, setup);
  this.setup.divId = setup.shellDivId;
  this.init();
};

ZDJS_Shell.prototype.init = function () {
  this.appendDiv();
};

ZDJS_Shell.prototype.consoleLog = function () {
  if (this.consoleLogBuffer !== undefined) {
    for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
      args[_key] = arguments[_key];
    }

    this.consoleLogBuffer.push(args);
  }
};

ZDJS_Shell.prototype.appendDiv = function () {
  var html = "\n        <button type=\"button\" class=\"btn zdjs-btn-xs btn-secondary zdjs-btn-clear\">Clear</button>\n        <div class=\"zdjs-terminal\">\n            <div class=\"zdjs-output\"></div>\n            <div class=\"zdjs-input\">\n                <table width=\"100%\">\n                    <tr>\n                        <td valign=\"top\"><span class=\"zdjs-dollar zdjs-dollar-input-marker\">$</span></td>\n                        <td valign=\"top\" width=100%>\n                        <div class=\"zdjs-cm-input-box\"><textarea></textarea></div>\n                        <div class=\"zdjs-cm-multiline-input-box zdjs-multiline-border hidden\"><textarea></textarea></div>\n                        </td>\n                    </tr>\n                </table>\n            </div>\n        </div>\n        <div class=\"zdjs-console\">\n            <button type=\"button\" class=\"btn zdjs-btn-xs btn-secondary zdjs-btn-multi\">Multiline input</button>\n            <button type=\"button\" class=\"btn zdjs-btn-xs btn-secondary zdjs-btn-multi-cancel hidden\">Cancel multiline input</button>\n            <button type=\"button\" class=\"btn zdjs-btn-xs btn-primary zdjs-btn-multi-submit hidden\">Run multiline input</button>\n        </div>\n    ";
  $("#" + this.setup.divId).append(html);
  var cmInputBox = $("#" + this.setup.divId + " .zdjs-cm-input-box textarea")[0];
  this.cm = CodeMirror.fromTextArea(cmInputBox, {
    lineNumbers: false,
    theme: "solarized dark",
    mode: "javascript"
  }); // https://stackoverflow.com/questions/13026285/codemirror-for-just-one-line-textfield

  this.cm.setSize("100%", this.cm.defaultTextHeight() + 8);
  var THIS = this;
  this.cm.setOption("extraKeys", {
    Enter: function Enter(cm) {
      THIS.runCommand(cm.getValue());
    }
  });
  var cmMultilineInputBox = $("#" + this.setup.divId + " .zdjs-cm-multiline-input-box textarea")[0];
  this.multilineCm = CodeMirror.fromTextArea(cmMultilineInputBox, {
    lineNumbers: false,
    theme: "solarized dark",
    mode: "javascript",
    viewportMargin: Infinity
  });
  $("#" + this.setup.divId + " .zdjs-cm-multiline-input-box .CodeMirror").css("height", "auto");

  if (!this.setup.showMultiLinkButton) {
    $("#" + this.setup.divId + " .zdjs-btn-multi").addClass("hidden");
  }

  var THIS = this;
  $("#" + this.setup.divId + " .zdjs-btn-clear").click(function () {
    THIS.clickClearButton();
  });
  var THIS = this;
  $("#" + this.setup.divId + " .zdjs-btn-multi").click(function () {
    THIS.clickMultilineButton();
  });
  $("#" + this.setup.divId + " .zdjs-btn-multi-cancel").click(function () {
    THIS.clickCancelMultilineButton();
  });
  $("#" + this.setup.divId + " .zdjs-btn-multi-submit").click(function () {
    THIS.clickSubmitMultilineButton();
  }); // https://stephanwagner.me/auto-resizing-textarea

  $("#" + this.setup.divId + " .zdjs-multiline-input-box").on("keyup input", function () {
    var offset = this.offsetHeight - this.clientHeight;
    $(this).css('height', 'auto').css('height', this.scrollHeight + offset);
  });
};

ZDJS_Shell.prototype.clickClearButton = function () {
  $("#" + this.setup.divId).empty();
  this.init();
};

ZDJS_Shell.prototype.clickMultilineButton = function () {
  this.tempCommand = this.cm.getValue();
  $("#" + this.setup.divId + " .zdjs-cm-input-box").addClass("hidden");
  $("#" + this.setup.divId + " .zdjs-cm-multiline-input-box").removeClass("hidden");
  $("#" + this.setup.divId + " .zdjs-dollar-input-marker").addClass("zdjs-gold");
  this.multilineCm.setValue(this.tempCommand);
  $("#" + this.setup.divId + " .zdjs-btn-multi").addClass("hidden");
  $("#" + this.setup.divId + " .zdjs-btn-multi-cancel").removeClass("hidden");
  $("#" + this.setup.divId + " .zdjs-btn-multi-submit").removeClass("hidden");
};

ZDJS_Shell.prototype.clickCancelMultilineButton = function () {
  $("#" + this.setup.divId + " .zdjs-cm-input-box").removeClass("hidden");
  $("#" + this.setup.divId + " .zdjs-cm-multiline-input-box").addClass("hidden");
  $("#" + this.setup.divId + " .zdjs-dollar-input-marker").removeClass("zdjs-gold");
  this.cm.setValue(this.tempCommand);
  $("#" + this.setup.divId + " .zdjs-btn-multi").removeClass("hidden");
  $("#" + this.setup.divId + " .zdjs-btn-multi-cancel").addClass("hidden");
  $("#" + this.setup.divId + " .zdjs-btn-multi-submit").addClass("hidden");
  this.tempCommand = undefined;
};

ZDJS_Shell.prototype.clickSubmitMultilineButton = function () {
  this.clickCancelMultilineButton();
  this.runMultilineCommand();
};

ZDJS_Shell.prototype.runMultilineCommand = function () {
  var command = this.multilineCm.getValue();
  this.consoleLogBuffer = [];
  var result;
  var error = false;

  try {
    try {
      // Double try because https://rayfd.wordpress.com/2007/03/28/why-wont-eval-eval-my-json-or-json-object-object-literal/
      result = eval.call(null, "(" + command + ")");
    } catch (e) {
      result = eval.call(null, command);
    }
  } catch (e) {
    result = e.message;
    error = true;
  }

  this.outputOldMultilineCommand(command);

  for (var i = 0; i < this.consoleLogBuffer.length; i++) {
    var logged = this.consoleLogBuffer[i];
    this.outputLog(logged, false);
  }

  this.outputResult(result, error);
  this.freshPrompt();
};

ZDJS_Shell.prototype.outputOldMultilineCommand = function (command) {
  var newOutput = "\n        <div class=\"zdjs-oldcommand zfjs-pad-shell\">\n            <table>\n                <tr>\n                    <td valign=\"top\"><span class=\"zdjs-gold zdjs-dollar\">$</span></td>\n                    <td valign=\"top\" width=100%>\n                        <div class=\"zdjs-cm-disabled-mutliline-input-box zdjs-multiline-border\"><textarea></textarea></div>\n                        <!--<textarea class=\"zdjs-multiline-oldcommand-box\" readonly>".concat(command, "</textarea>-->\n                    </td>\n                </tr>\n            </table>\n        </div>\n        ");
  var selector = "#" + this.setup.divId + " .zdjs-output";
  $(selector).append(newOutput);
  var cmMultilineInputBoxes = $("#" + this.setup.divId + " .zdjs-cm-disabled-mutliline-input-box textarea");
  var cmMultilineInputBox = cmMultilineInputBoxes[cmMultilineInputBoxes.length - 1];
  var cm = CodeMirror.fromTextArea(cmMultilineInputBox, {
    lineNumbers: false,
    theme: "solarized dark",
    mode: "javascript",
    readOnly: "nocursor",
    viewportMargin: Infinity
  });
  $("#" + this.setup.divId + " .zdjs-cm-disabled-mutliline-input-box .CodeMirror").css("height", "auto");
  cm.setValue(command);
}; // TODO: catch syntax errors, and other specific errors for better error-presenation
// to user.


ZDJS_Shell.prototype.runCommand = function (command) {
  this.consoleLogBuffer = [];
  var result;
  var error = false;

  try {
    try {
      // Double try because https://rayfd.wordpress.com/2007/03/28/why-wont-eval-eval-my-json-or-json-object-object-literal/
      result = eval.call(null, "(" + command + ")");
    } catch (e) {
      result = eval.call(null, command);
    }
  } catch (e) {
    result = e.message;
    error = true;
  }

  this.outputOldCommand(command);

  for (var i = 0; i < this.consoleLogBuffer.length; i++) {
    var logged = this.consoleLogBuffer[i];
    this.outputLog(logged, false);
  }

  this.outputResult(result, error);
  this.freshPrompt();
};

ZDJS_Shell.prototype.freshPrompt = function () {
  this.cm.setValue("");
};

ZDJS_Shell.prototype.outputOldCommand = function (command) {
  var newOutput = "\n        <div class=\"zdjs-oldcommand zfjs-pad-shell\">\n            <table>\n                <tr>\n                    <td valign=\"top\"><span class=\"zdjs-dollar\">$</span></td>\n                    <td valign=\"top\" width=100%>\n                        <div class=\"zdjs-cm-disabled-input-box\"\"><textarea></textarea></div>\n                    </td>\n                </tr>\n            </table>\n        </div>\n        ";
  var selector = "#" + this.setup.divId + " .zdjs-output";
  $(selector).append(newOutput);
  var cmInputBoxes = $("#" + this.setup.divId + " .zdjs-cm-disabled-input-box textarea");
  var cmInputBox = cmInputBoxes[cmInputBoxes.length - 1];
  var cm = CodeMirror.fromTextArea(cmInputBox, {
    lineNumbers: false,
    theme: "solarized dark",
    mode: "javascript",
    readOnly: "nocursor"
  });
  cm.setSize("100%", cm.defaultTextHeight() + 8);
  cm.setValue(command);
};

ZDJS_Shell.prototype.htmlEscape = function (text) {
  return $('<div/>').text(text).html();
};

ZDJS_Shell.prototype.outputLog = function (result, error) {
  var html;

  if (error) {
    html = "<div class=\"zdjs-output-error zfjs-pad-shell\">log: ".concat(result, "</div>");
  } else {
    html = "<div class=\"zdjs-output-log zfjs-pad-shell\">log: ".concat(result, "</div>");
  }

  var selector = "#" + this.setup.divId + " .zdjs-output";
  $(selector).append(html);
};

ZDJS_Shell.prototype.outputResult = function (result, error) {
  var html;

  if (error) {
    html = "<div class=\"zdjs-output-error zfjs-pad-shell\">".concat(result, "</div>");
  } else {
    html = "<div class=\"zdjs-output-noerror zfjs-pad-shell\">".concat(result, "</div>");
  }

  var selector = "#" + this.setup.divId + " .zdjs-output";
  $(selector).append(html);
};