var shSettings = {
  "tab_size":"4",
  "use_soft_tabs":"1",
  "word_wrap":"1",
  "highlight_curr_line":"0",
  "key_bindings":"default",
  "full_line_selection":"1",
  "show_line_numbers":"0"};

function SyntaxHighlight (id, settings) {
  var textarea, editor, form, session, editDiv;

  this.textarea = textarea = jQuery(id);
  this.settings = settings || {};

  if (textarea.length === 0 ) { // Element does not exist
    this.valid = false;
    return;
  }

  this.valid = true;
  editDiv = jQuery('<div>', {
    position: 'absolute',
//    width: textarea.width(),
    width: 719,
//    height: textarea.height(),
    height: 384,
    'class': textarea.attr('class'),
    'id':  'editor_'+block
  }).insertBefore (textarea);

  textarea.css('display', 'none');
  this.editor = editor = ace.edit(editDiv[0]);
  this.form = form = textarea.closest('form');
  this.session = session = editor.getSession();
  session.setValue(textarea.val());

  // copy back to textarea on form submit...
  form.submit (function () {
    textarea.val (session.getValue());
  });

  session.setMode ("ace/mode/html");

  this.applySettings();
}

SyntaxHighlight.prototype.applySettings = function () {
  var editor = this.editor,
    session = this.session,
    settings = this.settings;

  editor.renderer.setShowGutter(settings['show_line_numbers'] == 1);
  editor.setHighlightActiveLine(settings['highlight_curr_line'] == 1);
  editor.setSelectionStyle(settings['full_line_selection'] == 1 ? "line" : "text");
  editor.setTheme("ace/theme/" + settings['theme']);
  session.setUseWrapMode(settings['word_wrap'] == 1);
  session.setTabSize(settings['tab_size']);
  session.setUseSoftTabs(settings['use_soft_tabs'] == 1);
};

jQuery(document).ready(function($) {

  function process_display_elements (block) {

    $("#paragraph-settings-"+block).hide();

    $("#excerpt-settings-"+block).hide();
    $("#enabled-pages-"+block).hide();

    var display_type = '';
    $("select#display-type-"+block+" option:selected").each(function() {
      display_type += $(this).text();
    });
    if (display_type == "Before Paragraph" || display_type == "After Paragraph") {
      $("#paragraph-settings-"+block).show();
    }
    if (display_type == "Before Excerpt" || display_type == "After Excerpt") {
      $("#paragraph-settings-"+block).hide();
      $("#excerpt-settings-"+block).show();
    }
    if (display_type == "Before Excerpt" || display_type == "After Excerpt" || display_type == "Before Title" || display_type == "Widget" || $("#enable-php-call-"+block).is(":checked")) {
      $("#enabled-pages-"+block).show();
    }


    $("#custom-css-"+block).hide();

    var alignment = '';
    $("select#block-alignment-"+block+" option:selected").each(function() {
      alignment += $(this).text();
    });
    if (alignment == "Custom CSS") {
      $("#custom-css-"+block).show();
    }

    var editor = ace.edit ("editor_" + block);

    if ($("input#process-php-"+block).is(":checked")) {
      editor.getSession ().setMode ("ace/mode/php");
    } else editor.getSession ().setMode ("ace/mode/html");
  }

  for (block = 1; block <= 18; block ++) {
    syntax_highlighter = new SyntaxHighlight ('#block_' + block, shSettings);
    syntax_highlighter.editor.setPrintMarginColumn (1000);
  }

  $('#ai-tabs').tabs();

  var active_tab = $("#ai-active-tab").attr ("value");
  var tab_index = $('#ai-tabs a[href="#tab-'+active_tab+'"]').parent().index();
  $("#ai-tabs").tabs("option", "active", tab_index);

  $('.ai-tab').click (function () {
    tab_index = $(this).attr ("id");
    tab_index = tab_index.replace ("ai-tab","");
    $("#ai-active-tab").attr ("value", tab_index);

    var editor = ace.edit ("editor_" + tab_index);
    editor.getSession ().highlightLines (10000000);
  });

  //hover states on the static widgets
  $('#dialog_link, ul#icons li').hover(
    function() {$(this).addClass ('ui-state-hover');},
    function() {$(this).removeClass ('ui-state-hover');}
  );

  $(function() {
    $('#ai-settings').tooltip();
  });

  $('#dummy-tabs').hide();
  $('#ad-tabs').show();

  $(function() {
    $('#ai-settings input[type=submit], #ai-settings button').button();
  });

  for (ad_block = 1; ad_block <= 18; ad_block++) {
    $("select#display-type-"+ad_block).change (function() {
      block = $(this).attr('id').replace ("display-type-", "");
      process_display_elements (block);
    });
    $("select#block-alignment-"+ad_block).change (function() {
      block = $(this).attr('id').replace ("block-alignment-", "");
      process_display_elements (block);
    });
    $("input#process-php-"+ad_block).change (function() {
      block = $(this).attr('id').replace ("process-php-", "");
      process_display_elements (block);
    });
    $("#enable-php-call-"+ad_block).change (function() {
      block = $(this).attr('id').replace ("enable-php-call-", "");
      process_display_elements (block);
    });
    process_display_elements (ad_block);
  }

});

