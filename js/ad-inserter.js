jQuery(document).ready(function($) {
  $('#ai-tabs').tabs();

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
});
