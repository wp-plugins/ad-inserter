var shSettings = {
  "tab_size":"4",
  "use_soft_tabs":"1",
  "word_wrap":"1",
  "highlight_curr_line":"0",
  "key_bindings":"default",
  "full_line_selection":"1",
  "show_line_numbers":"0"};

function SyntaxHighlight (id, block, settings) {
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
    'id':  'editor_' + block
  }).insertBefore (textarea);

  textarea.css('display', 'none');
  this.editor = editor = ace.edit(editDiv[0]);
  this.form = form = textarea.closest('form');
  this.session = session = editor.getSession();
  session.setValue(textarea.val());

  // copy back to textarea on form submit...
  form.submit (function () {
    var block = textarea.attr ("id").replace ("block_","");
    var editor_disabled = jQuery("#simple-editor-" + block).is(":checked");
    if (!editor_disabled) {
      textarea.val (session.getValue());
    }
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

  function configure_editor_language (block) {

    var editor = ace.edit ("editor_" + block);

    if ($("input#process-php-"+block).is(":checked")) {
      editor.getSession ().setMode ("ace/mode/php");
    } else editor.getSession ().setMode ("ace/mode/html");
  }

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

    configure_editor_language (block);
  }

  function configure_editor (block) {
    syntax_highlighter = new SyntaxHighlight ('#block_' + block, block, shSettings);
    syntax_highlighter.editor.setPrintMarginColumn (1000);

    $('input#simple-editor-' + block).change (function () {

      block = $(this).attr ("id").replace ("simple-editor-","");
      var editor_disabled = $(this).is(":checked");
      var editor = ace.edit ("editor_" + block);
      var textarea = $("#block_" + block);
      var ace_editor = $("#editor_" + block);

      if (editor_disabled) {
        textarea.val (editor.session.getValue());
        textarea.css ('display', 'block');
        ace_editor.css ('display', 'none');
      } else {
          editor.session.setValue (textarea.val ())
          editor.renderer.updateFull();
          ace_editor.css ('display', 'block');
          textarea.css ('display', 'none');
        }
    });
  }

  blocks = $('#ad_form').attr('blocks');

  for (block = 1; block <= blocks; block ++) {
    configure_editor (block);
  }
  configure_editor ('h');
  configure_editor ('f');

  $('#ai-tabs').tabs();

  $('#ai-tabs a').css ("width", "14px").css ("text-align", "center");
  $('#ad-tabs').css ("padding", ".2em 0 0 .6em");

  var active_tab = $("#ai-active-tab").attr ("value");
  var tab_index = $('#ai-tabs a[href="#tab-'+active_tab+'"]').parent().index();
  $("#ai-tabs").tabs("option", "active", tab_index);

  $('.ai-tab').click (function () {
    tab_index = $(this).attr ("id");
    tab_index = tab_index.replace ("ai-tab","");
    $("#ai-active-tab").attr ("value", tab_index);

    if (tab_index != 0) {
      var editor = ace.edit ("editor_" + tab_index);
      editor.getSession ().highlightLines (10000000);
    }
  });

  //hover states on the static widgets
  $('#dialog_link, ul#icons li').hover(
    function() {$(this).addClass ('ui-state-hover');},
    function() {$(this).removeClass ('ui-state-hover');}
  );

  $('#ai-settings').tooltip();

  $('#dummy-tabs').hide();
  $('#ad-tabs').show();

  $('#ai-settings input[type=submit], #ai-settings button').button().show ();

  for (ad_block = 1; ad_block <= blocks; ad_block++) {
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

    $("#export_switch_"+ad_block).button ({
      icons: {
        primary: "ui-icon-gear",
        secondary: "ui-icon-triangle-1-s"
      },
      text: false
    }).show ().click (function () {
      tab_index = $(this).attr ("id");
      tab_index = tab_index.replace ("export_switch_","");
      $("#export_container_" + tab_index).toggle ();
    });
  }

  $("#export_switch_0").button ({
    icons: {
      primary: "ui-icon-gear",
      secondary: "ui-icon-triangle-1-s"
    },
    text: false
  }).show ().click (function () {
    $("#export_container_0").toggle ();
  });

  $("input#process-php-h").change (function() {
    configure_editor_language ('h')
  });

  $("input#process-php-f").change (function() {
    configure_editor_language ('f')
  });

});
