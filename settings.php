<?php

require_once 'constants.php';

if (file_exists (AD_INSERTER_PLUGIN_DIR.'functions.php')) {
  include_once 'functions.php';
}

function print_settings_form (){
  global $ai_db_options;

  if (isset ($_GET ['subpage'])) $subpage = $_GET ['subpage']; else $subpage = 'main';

  $ad_array = array ();
  for ($block = 1; $block <= AD_INSERTER_BLOCKS; $block ++) {
    $ad = new ai_Block ($block);
    $ad->load_options ($block);
    $ad_array [$block] = $ad;
  }

  $adH  = new ai_AdH();
  $adF  = new ai_AdF();

  $adH->load_options (AI_HEADER_OPTION_NAME);
  $adF->load_options (AI_FOOTER_OPTION_NAME);

  $syntax_highlighter_theme = get_syntax_highlighter_theme ();
  $block_class_name         = get_block_class_name ();

?>
<style>
</style>

<div id="ai-settings" style="margin-right: 16px; float: left; ">

  <div style="width: 735px; padding: 2px 8px 2px 8px; margin: 8px 0 8px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
<?php
  if (function_exists ('ai_settings_header')) ai_settings_header (); else { ?>
    <div style="float: right; margin: 7px 20px 0 0; text-decoration: none;">
      <a style="text-decoration: none;" href="http://igorfuna.com/contact/contact-igor-funa" target="_blank">Contact Author</a> #
      <a style="text-decoration: none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LHGZEMRTR7WB4" target="_blank">Donate</a> #
      <a style="text-decoration: none;" href="http://tinymonitor.com/" target="_blank">AdSense and Amazon Monitor</a> #
      <a style="text-decoration: none;" href="http://igorfuna.com/wordpress-hosting" target="_blank">Cool Wordpress Hosting</a>
    </div>
    <div style="float: right; text-align: right; margin: 2px 18px 2px 0;">
        If you find the <span style="font-weight: bold;"><?php echo AD_INSERTER_TITLE; ?></span> plugin useful, <span style="font-weight: bold;color: #0a0;">please rate it</span> on the <a style="text-decoration: none;" href="http://wordpress.org/plugins/ad-inserter/" target="_blank">plugin directory page</a>.
    </div>
    <h2><?php echo AD_INSERTER_TITLE . ' ' . AD_INSERTER_VERSION ?></h2>
<?php
  }
?>
  </div>
<?php
  if ($subpage == 'exceptions') {
?>
<?php
  } else {
?>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="ad_form" name="ad_form" style="float: left;" blocks="<?php echo AD_INSERTER_BLOCKS; ?>">
<div id="ai-tabs" style="width: 735px; padding: 8px 8px 8px 8px; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
  <div id="dummy-tabs" style="height: <?php echo intval ((AD_INSERTER_BLOCKS + 1) / 17 + 0.9) * 29; ?>px; padding: .2em .2em 0; border: 1px solid white;"></div>
  <ul id="ad-tabs" style="display: none;">
<?php
  for ($ad_number = 1; $ad_number <= AD_INSERTER_BLOCKS; $ad_number ++){
    $obj = $ad_array [$ad_number];

    $ad_name = $obj->get_ad_name();
    if ($obj->get_display_type() != AD_SELECT_NONE) $ad_name .= ": ".$obj->get_display_type();

    $style = "";
    if ($obj->get_display_type() != AD_SELECT_NONE) $style = "font-weight: bold; color: #e44;";
    elseif ($obj->get_enable_manual()) $style = "font-weight: bold; color: #66e;";
    elseif ($obj->get_enable_php_call()) $style = "font-weight: bold; color: #66e;";

    echo "
      <li id=\"ai-tab$ad_number\" class=\"ai-tab\" title=\"$ad_name\"><a href=\"#tab-$ad_number\"><span style=\"", $style, "\">$ad_number</span></a></li>";

  }

  if ($adH->get_enable_manual () && $adH->get_ad_data() != "") $style_h = "font-weight: bold; color: #66e;"; else $style_h = "";
  if ($adF->get_enable_manual () && $adF->get_ad_data() != "") $style_f = "font-weight: bold; color: #66e;"; else $style_f = "";
?>
      <li id="ai-tab0" class="ai-tab" title="Ad Inserter Settings"><a href="#tab-0"><span style="<?php echo $style_f ?>">#</span></a></li>
  </ul>

<?php
  for ($ad_number = 1; $ad_number <= AD_INSERTER_BLOCKS; $ad_number ++){
    $obj = $ad_array [$ad_number];
?>

<div id="tab-<?php echo $ad_number; ?>" style="padding: 0;">
  <div style="padding: 10px 0 0 4px;">
    <h3><?php echo $ad_number, ".  ", $obj->get_ad_name(); ?></h3>
  </div>

  <div style="float: right; padding: 1px 5px;">
        <button class="import-export-button" id="export_switch_<?php echo $ad_number; ?>" type="button" style="display: none; margin-right: 20px;">&nbsp;</button>
        <input style="display: none; border-radius: 5px; width:120px; font-weight: bold;" name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" />
  </div>

  <div style="padding:0px 8px 16px 16px;">
     Block Name:  <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_NAME, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_name() ?>" size="50" maxlength="70" />
  </div>

  <div id="export_container_<?php echo $ad_number; ?>" style="display: none; padding:8px;">
      <div style="display: inline-block; padding: 2px 10px; float: right;">
        <input type="hidden"   style="border-radius: 5px;" name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
        <input type="checkbox" style="border-radius: 5px;" name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" id="import-<?php echo $ad_number; ?>" />
        <label for="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" title="Import settings when saving - if checked, the encoded settings below will be imported for this block">Import Settings for block <?php echo $ad_number; ?></label>
      </div>

      <div style="float: left; padding-left:10px;">
        Saved settings for block <?php echo $ad_number, " (", $obj->get_ad_name(); ?>)
      </div>
      <textarea id="export_settings_<?php echo $ad_number; ?>" name="export_settings_<?php echo $ad_number; ?>" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 324px;"><?php echo base64_encode (serialize ($obj->wp_options)); ?></textarea>
  </div>

  <div style="display: inline-block; padding: 1px 10px; float: right;">
   <input type="hidden"   style="border-radius: 5px;" name="<?php echo AI_OPTION_PROCESS_PHP, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
   <input type="checkbox" style="border-radius: 5px;" name="<?php echo AI_OPTION_PROCESS_PHP, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" id="process-php-<?php echo $ad_number; ?>" <?php if ($obj->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?> /> <label for="<?php echo AI_OPTION_PROCESS_PHP, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" title="Process PHP code in block">Process PHP</label>
  </div>

  <div style="display: inline-block; padding: 1px 10px; float: right;">
    <input type="checkbox" style="border-radius: 5px;" name="simple-editor-<?php echo $ad_number; ?>" value="0" id="simple-editor-<?php echo $ad_number; ?>" /> <label for="simple-editor-<?php echo $ad_number; ?>" title="Toggle Syntax Highlighting / Simple editor">Simple editor</label>
  </div>

  <div style="padding-left:16px;">
      HTML / Javascript / PHP code (separate rotating versions with |rotate| )
  </div>

  <div style="padding:8px;">
      <textarea id="block_<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_CODE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 384px;"><?php echo str_replace ("&", "&amp;", $obj->get_ad_data()); ?></textarea>
  </div>

  <div style="padding:8px 8px 8px 16px;">
     Automatic Display:
     <select style="border-radius: 5px; margin-bottom: 3px;" id="display-type-<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_DISPLAY_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="width:200px">
        <option value="<?php echo AD_SELECT_NONE; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_NONE; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_TITLE; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_BEFORE_TITLE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_TITLE; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_CONTENT; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_BEFORE_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_CONTENT; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_PARAGRAPH; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_BEFORE_PARAGRAPH) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_PARAGRAPH; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_PARAGRAPH; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_AFTER_PARAGRAPH) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_PARAGRAPH; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_CONTENT; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_AFTER_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_CONTENT; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_EXCERPT; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_BEFORE_EXCERPT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_EXCERPT; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_EXCERPT; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_AFTER_EXCERPT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_EXCERPT; ?></option>
        <option value="<?php echo AD_SELECT_WIDGET; ?>" <?php echo ($obj->get_display_type()==AD_SELECT_WIDGET) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_WIDGET; ?></option>
     </select>

     <div style="float: right;">
        For
        <select style="border-radius: 5px; margin-bottom: 3px;" id="display-for-users-<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_DISPLAY_FOR_USERS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="width:160px">
        <option value="<?php echo AD_DISPLAY_ALL_USERS; ?>" <?php echo ($obj->get_display_for_users()==AD_DISPLAY_ALL_USERS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_ALL_USERS; ?></option>
        <option value="<?php echo AD_DISPLAY_LOGGED_IN_USERS; ?>" <?php echo ($obj->get_display_for_users()==AD_DISPLAY_LOGGED_IN_USERS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_LOGGED_IN_USERS; ?></option>
        <option value="<?php echo AD_DISPLAY_NOT_LOGGED_IN_USERS; ?>" <?php echo ($obj->get_display_for_users()==AD_DISPLAY_NOT_LOGGED_IN_USERS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_NOT_LOGGED_IN_USERS; ?></option>
     </select>
     users on
        <select style="border-radius: 5px; margin-bottom: 3px;" id="display-for-devices-<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_DISPLAY_FOR_DEVICES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="width:160px">
        <option value="<?php echo AD_DISPLAY_ALL_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_ALL_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_ALL_DEVICES; ?></option>
        <option value="<?php echo AD_DISPLAY_DESKTOP_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_DESKTOP_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_DESKTOP_DEVICES; ?></option>
        <option value="<?php echo AD_DISPLAY_MOBILE_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_MOBILE_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_MOBILE_DEVICES; ?></option>
        <option value="<?php echo AD_DISPLAY_TABLET_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_TABLET_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_TABLET_DEVICES; ?></option>
        <option value="<?php echo AD_DISPLAY_PHONE_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_PHONE_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_PHONE_DEVICES; ?></option>
        <option value="<?php echo AD_DISPLAY_DESKTOP_TABLET_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_DESKTOP_TABLET_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_DESKTOP_TABLET_DEVICES; ?></option>
        <option value="<?php echo AD_DISPLAY_DESKTOP_PHONE_DEVICES; ?>" <?php echo ($obj->get_display_for_devices() == AD_DISPLAY_DESKTOP_PHONE_DEVICES) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_DESKTOP_PHONE_DEVICES; ?></option>
     </select>
     devices
  </div>
  </div>
  <div style="clear: both;"></div>
  <div style="padding:0px 0px 8px 16px;">
     Block Alignment and Style:&nbsp;&nbsp;&nbsp;
     <select style="border-radius: 5px;" id="block-alignment-<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_ALIGNMENT_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="width:120px">
        <option value="<?php echo AD_ALIGNMENT_NO_WRAPPING; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_NO_WRAPPING) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_NO_WRAPPING; ?></option>
        <option value="<?php echo AD_ALIGNMENT_CUSTOM_CSS; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_CUSTOM_CSS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_CUSTOM_CSS; ?></option>
        <option value="<?php echo AD_ALIGNMENT_NONE; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_NONE; ?></option>
        <option value="<?php echo AD_ALIGNMENT_LEFT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_RIGHT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_RIGHT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_CENTER; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_CENTER) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_CENTER; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_LEFT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_FLOAT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_FLOAT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?></option>
     </select>
     <span id="custom-css-<?php echo $ad_number; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CSS code: <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_CUSTOM_CSS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_custom_css() ?>" size="52" maxlength="160" title="Custom CSS code for wrapping div" /></span>
  </div>

  <div style="padding:8px 8px 8px 8px; margin-left: 10px; border: 1px solid #ddd; border-radius: 5px;">
     <div id="enabled-pages-<?php echo $ad_number; ?>" style="display: inline-block; vertical-align: top; float: right;">
       <div style="display: inline-block; margin-right: 20px; margin-top: 4px;">
          <input style="border-radius: 5px; margin-left: 10px;" type="hidden" name="<?php echo AI_OPTION_DISPLAY_ON_HOMEPAGE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
          <input style="border-radius: 5px; margin-left: 10px;" type="checkbox" name="<?php echo AI_OPTION_DISPLAY_ON_HOMEPAGE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_display_settings_home()==AD_SETTINGS_CHECKED) echo 'checked '; ?> /> <label for="<?php echo AI_OPTION_DISPLAY_ON_HOMEPAGE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">Homepage</label>

          <input style="border-radius: 5px; margin-left: 25px;" type="hidden" name="<?php echo AI_OPTION_DISPLAY_ON_CATEGORY_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
          <input style="border-radius: 5px; margin-left: 25px;" type="checkbox" name="<?php echo AI_OPTION_DISPLAY_ON_CATEGORY_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_display_settings_category()==AD_SETTINGS_CHECKED) echo 'checked '; ?> /> <label for="<?php echo AI_OPTION_DISPLAY_ON_CATEGORY_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">Category pages</label>

          <br /><br />

          <input style="border-radius: 5px; margin-left: 10px;" type="hidden" name="<?php echo AI_OPTION_DISPLAY_ON_SEARCH_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
          <input style="border-radius: 5px; margin-left: 10px;" type="checkbox" name="<?php echo AI_OPTION_DISPLAY_ON_SEARCH_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_display_settings_search()==AD_SETTINGS_CHECKED) echo 'checked '; ?> /> <label for="<?php echo AI_OPTION_DISPLAY_ON_SEARCH_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">Search pages</label>

          <input style="border-radius: 5px; margin-left: 10px;" type="hidden" name="<?php echo AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
          <input style="border-radius: 5px; margin-left: 10px;" type="checkbox" name="<?php echo AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_display_settings_archive()==AD_SETTINGS_CHECKED) echo 'checked '; ?> /> <label for="<?php echo AI_OPTION_DISPLAY_ON_ARCHIVE_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">Archive pages</label>
       </div>
     </div>

     <div style="display: inline-block; vertical-align: top; margin-right: 10px;">

      <input style="border-radius: 5px;" type="hidden" name="<?php echo AI_OPTION_DISPLAY_ON_POSTS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
      <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_DISPLAY_ON_POSTS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_display_settings_post()==AD_SETTINGS_CHECKED) echo 'checked '; ?> />

      <select style="border-radius: 5px; margin: 0 0 3px 10px;" title="Default display for posts and pages, exceptions can be configured on individual Post or Page editor pages" id="enabled-on-which-posts-<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_ENABLED_ON_WHICH_POSTS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="width:160px">
         <option value="<?php echo AD_ENABLED_ON_ALL; ?>" <?php echo ($obj->get_ad_enabled_on_which_posts()==AD_ENABLED_ON_ALL) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ENABLED_ON_ALL; ?></option>
         <option value="<?php echo AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED; ?>" <?php echo ($obj->get_ad_enabled_on_which_posts()==AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED; ?></option>
         <option value="<?php echo AD_ENABLED_ONLY_ON_SELECTED; ?>" <?php echo ($obj->get_ad_enabled_on_which_posts()==AD_ENABLED_ONLY_ON_SELECTED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ENABLED_ONLY_ON_SELECTED; ?></option>
      </select>

      &nbsp;&nbsp;<label for="<?php echo AI_OPTION_DISPLAY_ON_POSTS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">Posts</label>
      <br />

      <input style="border-radius: 5px;" type="hidden" name="<?php echo AI_OPTION_DISPLAY_ON_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
      <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_DISPLAY_ON_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_display_settings_page()==AD_SETTINGS_CHECKED) echo 'checked '; ?> />

      <select style="border-radius: 5px; margin: 0 0 3px 10px;" title="Default display for posts and pages, exceptions can be configured on individual Post or Page editor pages" id="enabled-on-which-pages-<?php echo $ad_number; ?>" name="<?php echo AI_OPTION_ENABLED_ON_WHICH_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" style="width:160px">
         <option value="<?php echo AD_ENABLED_ON_ALL; ?>" <?php echo ($obj->get_ad_enabled_on_which_pages()==AD_ENABLED_ON_ALL) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ENABLED_ON_ALL; ?></option>
         <option value="<?php echo AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED; ?>" <?php echo ($obj->get_ad_enabled_on_which_pages()==AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ENABLED_ON_ALL_EXCEPT_ON_SELECTED; ?></option>
         <option value="<?php echo AD_ENABLED_ONLY_ON_SELECTED; ?>" <?php echo ($obj->get_ad_enabled_on_which_pages()==AD_ENABLED_ONLY_ON_SELECTED) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ENABLED_ONLY_ON_SELECTED; ?></option>
      </select>

      &nbsp;&nbsp;<label for="<?php echo AI_OPTION_DISPLAY_ON_PAGES, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">Pages</label>

     </div>
  </div>

  <table style="padding:16px 8px 10px 16px;">
    <tbody>
      <tr>
        <td style="padding-right: 10px;">
          Categories:
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" title="Comma separated category names - if category name contains commas use category slug instead" type="text" name="<?php echo AI_OPTION_CATEGORY_LIST, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_block_cat() ?>" size="58" maxlength="500" />
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_CATEGORY_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_BLACK_LIST) echo 'checked '; ?> /> <?php echo AD_BLACK_LIST; ?>
        </td>
        <td>
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_CATEGORY_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_WHITE_LIST) echo 'checked '; ?> /> <?php echo AD_WHITE_LIST; ?>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 10px;">
          Tags:
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" title="Comma separated tags" type="text" name="<?php echo AI_OPTION_TAG_LIST, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_block_tag() ?>" size="58" maxlength="500"/>
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_TAG_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_block_tag_type() == AD_BLACK_LIST) echo 'checked '; ?> /> <?php echo AD_BLACK_LIST; ?>
        </td>
        <td>
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_TAG_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_block_tag_type() == AD_WHITE_LIST) echo 'checked '; ?> /> <?php echo AD_WHITE_LIST; ?>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 10px;">
          Urls:
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" title="SPACE separated urls starting with / (use only when you need to taget a specific url not accessible by other means)" type="text" name="<?php echo AI_OPTION_URL_LIST, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_url_list() ?>" size="58" maxlength="500"/>
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_URL_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_url_list_type() == AD_BLACK_LIST) echo 'checked '; ?> /> <?php echo AD_BLACK_LIST; ?>
        </td>
        <td>
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_URL_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_url_list_type() == AD_WHITE_LIST) echo 'checked '; ?> /> <?php echo AD_WHITE_LIST; ?>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 10px;">
          Referers:
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" title="Comma separated domains" type="text" name="<?php echo AI_OPTION_DOMAIN_LIST, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_domain_list() ?>" size="58" maxlength="500"/>
        </td>
        <td style="padding-right: 10px;">
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_DOMAIN_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_domain_list_type() == AD_BLACK_LIST) echo 'checked '; ?> /> <?php echo AD_BLACK_LIST; ?>
        </td>
        <td>
          <input style="border-radius: 5px;" type="radio" name="<?php echo AI_OPTION_DOMAIN_LIST_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_domain_list_type() == AD_WHITE_LIST) echo 'checked '; ?> /> <?php echo AD_WHITE_LIST; ?>
        </td>
      </tr>
    </tbody>
  </table>

  <div id="after-published-<?php echo $ad_number; ?>" style="padding: 0px 38px 8px 16px; margin-top: 10px; float: right;">
    Display <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_AFTER_DAYS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_after_day() ?>" size="2" maxlength="3" title="0 means publish immediately" /> days after post is published
  </div>
  <div style="padding:0px 0px 8px 16px; margin-top: 10px;">
     General tag:  <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_GENERAL_TAG, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_ad_general_tag() ?>" size="40" maxlength="60" title="Used for {tags} when no page data is found" />
  </div>

  <div id="paragraph-settings-<?php echo $ad_number; ?>" style="padding:8px 8px 0 8px; margin: 0 0 10px 10px; border: 1px solid #ddd; border-radius: 5px;">
    <div style="display: inline-block; vertical-align: ">
      <div style="margin: 4px 0 4px 0; ">
        Paragraph number <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_PARAGRAPH_NUMBER, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_paragraph_number() ?>" size="2" maxlength="4" /> &nbsp; 0 means random, value between 0 and 1 means relative position.
      </div>
      <div style="margin: 4px 0 4px 0; ">
        Counting
        <select style="border-radius: 5px;" name="<?php echo AI_OPTION_DIRECTION_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">
          <option value="<?php echo AD_DIRECTION_FROM_TOP; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_TOP; ?></option>
          <option value="<?php echo AD_DIRECTION_FROM_BOTTOM; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_BOTTOM; ?></option>
        </select>&nbsp;&nbsp;&nbsp;
        Minimum number of paragraphs <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_MIN_PARAGRAPHS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_paragraph_number_minimum() ?>" size="2" maxlength="3" />&nbsp;&nbsp;&nbsp;
        Minimum number of words <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_MIN_WORDS, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_minimum_words() ?>" size="4" maxlength="5" />&nbsp;&nbsp;&nbsp;
      </div>
      <div style="margin: 4px 0 4px 0; ">
        Count only paragraphs that
        <select style="border-radius: 5px; margin-bottom: 3px;" name="<?php echo AI_OPTION_PARAGRAPH_TEXT_TYPE, WP_FORM_FIELD_POSTFIX, $ad_number; ?>">
          <option value="<?php echo AD_CONTAIN; ?>" <?php echo ($obj->get_paragraph_text_type() == AD_CONTAIN) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_CONTAIN; ?></option>
          <option value="<?php echo AD_DO_NOT_CONTAIN; ?>" <?php echo ($obj->get_paragraph_text_type() == AD_DO_NOT_CONTAIN) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DO_NOT_CONTAIN; ?></option>
        </select>
        <input style="border-radius: 5px;" title="Comma separated text" type="text" name="<?php echo AI_OPTION_PARAGRAPH_TEXT, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_paragraph_text() ?>" size="60" maxlength="200" />
      </div>
    </div>
  </div>

  <div id="excerpt-settings-<?php echo $ad_number; ?>" style="padding:8px 8px 8px 8px; margin: 0 0 10px 10px; border: 1px solid #ddd; border-radius: 5px;">
    Excerpt number(s) <input style="border-radius: 5px;" type="text" name="<?php echo AI_OPTION_EXCERPT_NUMBER, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="<?php echo $obj->get_excerpt_number() ?>" title= "Excerpt number or comma separated excerpt numbers, 0 means all excerpts" size="10" maxlength="20" />
  </div>

  <div style="padding:7px; text-align: left; margin-left: 10px; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
    <input style="border-radius: 5px;" type="hidden"   name="<?php echo AI_OPTION_ENABLE_MANUAL, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
    <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_ENABLE_MANUAL, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_enable_manual () == AD_SETTINGS_CHECKED) echo 'checked '; ?> />
    <label for="<?php echo AI_OPTION_ENABLE_MANUAL, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" title="Enable or disable manual insertion of this code block in posts and pages">
      Enable manual insertion with shortcode
    </label>
    &nbsp;&nbsp;
    <pre style= "margin: 0; display: inline; color: blue;">[adinserter block="<?php echo $ad_number; ?>"]</pre> or
    <pre style= "margin: 0; display: inline; color: blue;">[adinserter name="<?php echo $obj->get_ad_name(); ?>"]</pre>.
    <hr />
    <input style="border-radius: 5px;" type="hidden"   name="<?php echo AI_OPTION_ENABLE_PHP_CALL, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="0" />
    <input style="border-radius: 5px;" id="enable-php-call-<?php echo $ad_number; ?>" type="checkbox" name="<?php echo AI_OPTION_ENABLE_PHP_CALL, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" value="1" <?php if ($obj->get_enable_php_call () == AD_SETTINGS_CHECKED) echo 'checked '; ?> />
    <label for="<?php echo AI_OPTION_ENABLE_PHP_CALL, WP_FORM_FIELD_POSTFIX, $ad_number; ?>" title="Enable or disable PHP function call to insert this code block at any position in template file. If function is disabled for block it will return empty string.">
      Enable PHP function adinserter for block <?php echo $ad_number; ?>
    </label>
    &nbsp;&nbsp;
    <pre style= "margin: 0; display: inline; color: blue;">&lt;?php if (function_exists ('adinserter')) echo adinserter (<?php echo $ad_number; ?>); ?&gt;</pre>
  </div>
</div>

<?php
  }
?>

<div id="tab-0" style="padding: 0 0 30px 0;">
  <div style="padding: 10px 0 0 4px;">
    <h3>Ad Inserter Settings</h3>
  </div>

  <div style="float: right; padding: 1px 5px;">
    <button class="import-export-button" id="export_switch_0" type="button" style="display: none; margin-right: 20px;">&nbsp;</button>
    <input style="display: none; border-radius: 5px; width:120px; font-weight: bold;" name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>

  <div style="clear: both;"></div>

  <div id="export_container_0" style="display: none; padding: 8px;">
      <div style="display: inline-block; padding: 2px 10px; float: right;">
        <input type="hidden"   style="border-radius: 5px;" name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, '0'; ?>" value="0" />
        <input type="checkbox" style="border-radius: 5px;" name="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, '0'; ?>" value="1" id="import-0" />
        <label for="<?php echo AI_OPTION_IMPORT, WP_FORM_FIELD_POSTFIX, '0'; ?>" title="Import Ad Inserter settings when saving - if checked, the encoded settings below will be imported for all blocks and settings">Import Settings for Ad Inserter</label>
      </div>

      <div style="float: left; padding-left:10px;">
        Saved settings for Ad Inserter
      </div>
      <textarea id="export_settings_0" name="export_settings_0" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 324px;"><?php echo base64_encode (serialize ($ai_db_options)); ?></textarea>
  </div>

  <div style="padding:0px 0px 8px 16px;">
     Syntax Highlighter Theme:&nbsp;&nbsp;&nbsp;

      <select
          style="border-radius: 5px; width:220px"
          id="syntax-highlighter-theme"
          name="syntax-highlighter-theme"
          value="Value">
          <optgroup label="Light">
              <option value="disabled" <?php echo ($syntax_highlighter_theme == 'disabled') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>No Syntax Highlighting</option>
              <option value="chrome" <?php echo ($syntax_highlighter_theme == 'chrome') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Chrome</option>
              <option value="clouds" <?php echo ($syntax_highlighter_theme == 'clouds') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Clouds</option>
              <option value="crimson_editor" <?php echo ($syntax_highlighter_theme == 'crimson_editor') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Crimson Editor</option>
              <option value="dawn" <?php echo ($syntax_highlighter_theme == 'dawn') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Dawn</option>
              <option value="dreamweaver" <?php echo ($syntax_highlighter_theme == 'dreamweaver') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Dreamweaver</option>
              <option value="eclipse" <?php echo ($syntax_highlighter_theme == 'eclipse') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Eclipse</option>
              <option value="github" <?php echo ($syntax_highlighter_theme == 'github') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>GitHub</option>
              <option value="katzenmilch" <?php echo ($syntax_highlighter_theme == 'katzenmilch') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Katzenmilch</option>
              <option value="kuroir" <?php echo ($syntax_highlighter_theme == 'kuroir') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Kuroir</option>
              <option value="solarized_light" <?php echo ($syntax_highlighter_theme == 'solarized_light') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Solarized Light</option>
              <option value="textmate" <?php echo ($syntax_highlighter_theme == 'textmate') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Textmate</option>
              <option value="tomorrow" <?php echo ($syntax_highlighter_theme == 'tomorrow') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Tomorrow</option>
              <option value="xcode" <?php echo ($syntax_highlighter_theme == 'xcode') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>XCode</option>
          </optgroup>
          <optgroup label="Dark">
              <option value="ad_inserter" <?php echo ($syntax_highlighter_theme == 'ad_inserter') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Ad Inserter</option>
              <option value="chaos" <?php echo ($syntax_highlighter_theme == 'chaos') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Chaos</option>
              <option value="clouds_midnight" <?php echo ($syntax_highlighter_theme == 'clouds_midnight') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Clouds Midnight</option>
              <option value="cobalt" <?php echo ($syntax_highlighter_theme == 'cobalt') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Cobalt</option>
              <option value="idle_fingers" <?php echo ($syntax_highlighter_theme == 'idle_fingers') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Idle Fingers</option>
              <option value="kr_theme" <?php echo ($syntax_highlighter_theme == 'kr_theme') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>krTheme</option>
              <option value="merbivore" <?php echo ($syntax_highlighter_theme == 'merbivore') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Merbivore</option>
              <option value="merbivore_soft" <?php echo ($syntax_highlighter_theme == 'merbivore_soft') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Merbivore Soft</option>
              <option value="mono_industrial" <?php echo ($syntax_highlighter_theme == 'mono_industrial') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Mono Industrial</option>
              <option value="monokai" <?php echo ($syntax_highlighter_theme == 'monokai') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Monokai</option>
              <option value="pastel_on_dark" <?php echo ($syntax_highlighter_theme == 'pastel_on_dark') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Pastel on Dark</option>
              <option value="solarized_dark" <?php echo ($syntax_highlighter_theme == 'solarized_dark') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Solarized Dark</option>
              <option value="terminal" <?php echo ($syntax_highlighter_theme == 'terminal') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Terminal</option>
              <option value="tomorrow_night" <?php echo ($syntax_highlighter_theme == 'tomorrow_night') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Tomorrow Night</option>
              <option value="tomorrow_night_blue" <?php echo ($syntax_highlighter_theme == 'tomorrow_night_blue') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Tomorrow Night Blue</option>
              <option value="tomorrow_night_bright" <?php echo ($syntax_highlighter_theme == 'tomorrow_night_bright') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Tomorrow Night Bright</option>
              <option value="tomorrow_night_eighties" <?php echo ($syntax_highlighter_theme == 'tomorrow_night_eighties') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Tomorrow Night 80s</option>
              <option value="twilight" <?php echo ($syntax_highlighter_theme == 'twilight') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Twilight</option>
              <option value="vibrant_ink" <?php echo ($syntax_highlighter_theme == 'vibrant_ink') ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>>Vibrant Ink</option>
          </optgroup>
      </select>
  </div>

  <div style="padding:0px 0px 8px 16px;">
    Block Class Name:&nbsp;&nbsp;&nbsp;
      <input style="border-radius: 5px; margin-left: 0px;" title="CSS Class Name" type="text" id="block-class-name" name="block-class-name" value="<?php echo $block_class_name; ?>" size="15" maxlength="40" /> <span style= "margin: 3px 10px 0 0; display: inline; float: right;">Example: &nbsp;&nbsp; <pre style= "display: inline; color: blue;">&lt;div class="<?php echo $block_class_name; ?> <?php echo $block_class_name; ?>-n"&gt;<span style= "color: black;">BlockCode</span>&lt;/div&gt;</pre></span>
  </div>

  <hr />

  <div style="padding: 0;">
    <div style="float: right; padding-top: 5px; margin-right: 15px;">
      <input style="border-radius: 5px;" type="hidden"   name="<?php echo AI_OPTION_ENABLE_MANUAL, '_block_h'; ?>" value="0" />
      <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_ENABLE_MANUAL, '_block_h'; ?>" value="1" <?php if ($adH->get_enable_manual () == AD_SETTINGS_CHECKED) echo 'checked '; ?> />
      <label for="<?php echo AI_OPTION_ENABLE_MANUAL, '_block_h'; ?>" title="Enable or disable insertion of this code into header">Enable</label>
    </div>

    <div style="padding-left:4px;">
      <h3>Header Code</h3>
    </div>

    <div style="padding:0px 8px 22px 16px;">
      Code will be placed within the <pre style="display: inline; color: blue;">&lt;head&gt;&lt;/head&gt;</pre> section of the theme (theme-dependent)
    </div>

    <div style="display: inline-block; padding: 1px 10px; float: right;">
     <input style="border-radius: 5px;" type="hidden"   name="<?php echo AI_OPTION_PROCESS_PHP, '_block_h'; ?>" value="0" />
     <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_PROCESS_PHP, '_block_h'; ?>" value="1" id="process-php-h" <?php if ($adH->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?> />
     <label for="<?php echo AI_OPTION_PROCESS_PHP, '_block_h'; ?>" title="Process PHP code">Process PHP</label>
    </div>

    <div style="display: inline-block; padding: 1px 10px; float: right;">
      <input type="checkbox" style="border-radius: 5px;" name="simple-editor-h" value="0" id="simple-editor-h" /> <label for="simple-editor-h" title="Simple editor">Simple editor</label>
    </div>

    <div style="padding-left:16px;">
        HTML / Javascript / CSS / PHP code
    </div>
    <div style="padding: 8px;">
        <textarea id="block_h" name="<?php echo AI_OPTION_CODE, '_block_h'; ?>" rows="36" cols="98" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 384px;"><?php echo str_replace ("&", "&amp;", $adH->get_ad_data()); ?></textarea>
    </div>
  </div>

  <hr />

  <div style="padding: 0;">
    <div style="float: right; padding-top: 5px; margin-right: 15px;">
      <input style="border-radius: 5px;" type="hidden"   name="<?php echo AI_OPTION_ENABLE_MANUAL, '_block_f'; ?>" value="0" />
      <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_ENABLE_MANUAL, '_block_f'; ?>" value="1" <?php if ($adF->get_enable_manual () == AD_SETTINGS_CHECKED) echo 'checked '; ?> />
      <label for="<?php echo AI_OPTION_ENABLE_MANUAL, '_block_f'; ?>" title="Enable or disable insertion of this code into footer">Enable</label>
    </div>

    <div style="padding-left:4px;">
      <h3>Footer Code</h3>
    </div>

    <div style="padding:0px 8px 22px 16px;">
      Code will be placed near the <pre style="display: inline; color: blue;">&lt;/body&gt;</pre> tag of the theme (theme-dependent)
    </div>

    <div style="display: inline-block; padding: 1px 10px; float: right;">
     <input style="border-radius: 5px;" type="hidden"   name="<?php echo AI_OPTION_PROCESS_PHP, '_block_f'; ?>" value="0" />
     <input style="border-radius: 5px;" type="checkbox" name="<?php echo AI_OPTION_PROCESS_PHP, '_block_f'; ?>" value="1" id="process-php-f" <?php if ($adF->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?> />
     <label for="<?php echo AI_OPTION_PROCESS_PHP, '_block_f'; ?>" title="Process PHP code">Process PHP</label>
    </div>

    <div style="display: inline-block; padding: 1px 10px; float: right;">
      <input type="checkbox" style="border-radius: 5px;" name="simple-editor-f" value="0" id="simple-editor-f" /> <label for="simple-editor-f" title="Simple editor">Simple editor</label>
    </div>

    <div style="padding-left:16px;">
        HTML / Javascript / PHP code
    </div>
    <div style="padding:8px;">
        <textarea id="block_f" name="<?php echo AI_OPTION_CODE, '_block_f'; ?>" rows="36" cols="98" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 384px;"><?php echo str_replace ("&", "&amp;", $adF->get_ad_data()); ?></textarea>
    </div>
  </div>

</div>


</div>

<div style="height: 30px; margin: 8px 0 0 0;">
  <div style="float: left; padding: 1px 1px; color: red;">
        <input onclick="if (confirm('Are you sure you want to reset all settings?')) return true; return false" name="<?php echo AD_FORM_CLEAR; ?>" value="Reset All Settings" type="submit" style="display: none; width:120px; font-weight: bold; color: #e44;" />
  </div>
  <div style="float: right; padding: 1px 1px;">
        <input name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="display: none; border-radius: 5px; width:120px; font-weight: bold;" />
  </div>
</div>

<input id="ai-active-tab" type="hidden" name="ai-active-tab" value="<?php echo isset ($_POST ['ai-active-tab']) ? $_POST ['ai-active-tab'] : 1; ?>" />

<?php wp_nonce_field ('save_adinserter_settings'); ?>

</form>

<?php
    }  // Main subpage
?>

</div>

<?php
  if ($subpage == 'main') {
  if (function_exists ('ai_settings_side')) ai_settings_side (); else { ?>
  <div style="float: left;">
    <div style="width: 735px; padding: 2px 8px 6px 8px; margin: 8px 0 8px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px; background: #fff;">
      <h2>Monitor Google AdSense and Amazon Associates earnings with <a href="http://tinymonitor.com/" target="_blank">Tiny Monitor</a></h2>
      <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tinymonitor-logo.png" alt="Tiny Monitor" /></a>
      <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tiny-monitor.png" alt="Amazon Associates" /></a>
      <p style="text-align: justify;">TinyMonitor is a PHP application that can montor your Google AdSense earnings, Amazon Associates earnings and PayPal transactions.
         The purpose of TinyMonitor is to download data from original sources and present them in a compact way on a single web page.
         With TinyMonitor you have all the data at one place so you dont have to log in to various pages just to check earnings.
         TinyMonitor displays some data also in the page title and favicon so you still have simple access to current monitor status while you work with other applications.</p>
    </div>
  </div>
<?php
  }
  }
?>

<script type="text/javascript">
  shSettings ['theme'] = '<?php echo $syntax_highlighter_theme; ?>';
</script>

<?php
  }
