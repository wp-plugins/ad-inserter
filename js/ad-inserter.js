jQuery(document).ready(function($) {

  function process_display_elements (block) {

    $("#paragraph-settings-"+block).hide();

    $("#excerpt-settings-"+block).hide();
    $("#enabled-pages-"+block).hide();


    var display_type = '';
    $("select#display-type-"+block+" option:selected").each(function() {
      display_type += $(this).text();
    });
    if (display_type == "Before Paragraph") {

      $("#paragraph-settings-"+block).show();
    }
    if (display_type == "Before Excerpt" || display_type == "After Excerpt") {
      $("#paragraph-settings-"+block).hide();
      $("#excerpt-settings-"+block).show();
    }
    if (display_type == "Before Excerpt" || display_type == "After Excerpt" || display_type == "Before Title" || display_type == "Widget" || $("#enable-php-call-"+block).is(":checked")) {
      $("#enabled-pages-"+block).show();
    }
  }

  $('#ai-tabs').tabs();

  var active_tab = $("#ai-active-tab").attr ("value");
  var tab_index = $('#ai-tabs a[href="#tab-'+active_tab+'"]').parent().index();
  $("#ai-tabs").tabs("option", "active", tab_index);

  $('.ai-tab').click (function () {
    tab_index = $(this).attr ("id");
    tab_index = tab_index.replace ("ai-tab","");
    $("#ai-active-tab").attr ("value", tab_index);
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

  for (ad_block = 1; ad_block <= 16; ad_block++) {
    $("select#display-type-"+ad_block).change (function() {
      block = $(this).attr('id').replace ("display-type-", "");
      process_display_elements (block);
    }).trigger ("change");
    $("#enable-php-call-"+ad_block).change (function() {
      block = $(this).attr('id').replace ("enable-php-call-", "");
      process_display_elements (block);
    }).trigger ("change");
  }

});
