<?php

require_once 'constants.php';

function print_settings_form (){

  $ad1  = new Block (1);
  $ad2  = new Block (2);
  $ad3  = new Block (3);
  $ad4  = new Block (4);
  $ad5  = new Block (5);
  $ad6  = new Block (6);
  $ad7  = new Block (7);
  $ad8  = new Block (8);
  $ad9  = new Block (9);
  $ad10 = new Block (10);
  $ad11 = new Block (11);
  $ad12 = new Block (12);
  $ad13 = new Block (13);
  $ad14 = new Block (14);
  $ad15 = new Block (15);
  $ad16 = new Block (16);
  $adH  = new AdH();
  $adF  = new AdF();

  $ad_array = array($ad1,$ad2,$ad3,$ad4,$ad5,$ad6,$ad7,$ad8,$ad9,$ad10,$ad11,$ad12,$ad13,$ad14,$ad15,$ad16);

  // Load options from db
  foreach ($ad_array as $key => $obj){
    $obj->load_options ("AdInserter".($key + 1)."Options");
  }

  $adH->load_options (AD_HEADER_OPTIONS);
  $adF->load_options (AD_FOOTER_OPTIONS);

  $syntax_highlighter_theme = get_syntax_highlighter_theme ();

?>
<div id="ai-settings" style="margin-right: 16px; float: left; ">

  <div style="width: 735px; padding: 2px 8px 2px 8px; margin: 8px 0 8px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
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
  </div>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="ad_form" name="ad_form" style="float: left;">

<div id="ai-tabs" style="width: 735px; padding: 8px 8px 8px 8px; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
  <div id="dummy-tabs" style="height: 29px; padding: .2em .2em 0; border: 1px solid white;"></div>
  <ul id="ad-tabs" style="display: none;">
<?php
  $ad_number = 0;
  foreach($ad_array as $obj){
    $ad_number ++;
    $ad_name = $obj->get_ad_name();
    if ($obj->get_append_type() != AD_SELECT_NONE) $ad_name .= ": ".$obj->get_append_type();

    $style = "";
    if ($obj->get_append_type() != AD_SELECT_NONE) $style = "font-weight: bold; color: #e44;";
    elseif ($obj->get_enable_manual()) $style = "font-weight: bold; color: #66e;";
    elseif ($obj->get_enable_php_call()) $style = "font-weight: bold; color: #66e;";

    echo "
      <li id=\"ai-tab$ad_number\" class=\"ai-tab\" title=\"$ad_name\"><a href=\"#tab-$ad_number\"><span style=\"", $style, "\">$ad_number</span></a></li>";

  }

  if ($adH->get_enable_manual () && $adH->get_ad_data() != "") $style_h = "font-weight: bold; color: #66e;"; else $style_h = "";
  if ($adF->get_enable_manual () && $adF->get_ad_data() != "") $style_f = "font-weight: bold; color: #66e;"; else $style_f = "";
?>
      <li id="ai-tab17" class="ai-tab" title="Header Scripts"><a href="#tab-17"><span style="<?php echo $style_h ?>">H</span></a></li>
      <li id="ai-tab18" class="ai-tab" title="Footer Scripts"><a href="#tab-18"><span style="<?php echo $style_f ?>">F</span></a></li>
      <li id="ai-tab19" class="ai-tab" title="Ad Inserter Settings"><a href="#tab-19"><span style="<?php echo $style_f ?>">#</span></a></li>
  </ul>

<?php
  $ad_number = 0;
  foreach($ad_array as $obj){
    $ad_number ++;
?>

<div id="tab-<?php echo $ad_number; ?>" style="padding: 0;">
  <br />

  <div style="padding-left:4px;">
    <h3><?php echo $ad_number, ".  ", $obj->get_ad_name(); ?></h3>
  </div>

  <div style="float: right; padding: 1px 5px;">
        <input style="border-radius: 5px;" name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>

  <div style="padding:0px 8px 16px 16px;">
     Block Name:  <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_ad_name; ?>" value="<?php echo $obj->get_ad_name() ?>" size="50" maxlength="50"/>
  </div>

  <div style="padding-left:16px;">
      HTML / Javascript / PHP code (separate rotating versions with |rotate| )
  </div>

  <div style="padding:8px;">
      <textarea id="block_<?php echo $ad_number; ?>" name="<?php echo $obj->option_ad_data; ?>" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 384px;"><?php echo str_replace ("&", "&amp;", $obj->get_ad_data()); ?></textarea>
  </div>

  <div style="padding:8px 8px 8px 16px;">
     Automatic Display:
     <select style="border-radius: 5px; margin-bottom: 3px;" id="display-type-<?php echo $ad_number; ?>" name="<?php echo $obj->option_append_type; ?>" style="width:200px">
        <option value="<?php echo AD_SELECT_NONE; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_NONE; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_TITLE; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_TITLE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_TITLE; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_CONTENT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_CONTENT; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_PARAGRAPH; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_PARAGRAPH) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_PARAGRAPH; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_PARAGRAPH; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_AFTER_PARAGRAPH) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_PARAGRAPH; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_CONTENT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_AFTER_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_CONTENT; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_EXCERPT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_EXCERPT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_EXCERPT; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_EXCERPT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_AFTER_EXCERPT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_EXCERPT; ?></option>
        <option value="<?php echo AD_SELECT_WIDGET; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_WIDGET) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_WIDGET; ?></option>
     </select>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     Display block for
     <select style="border-radius: 5px; margin-bottom: 3px;" id="display-for-users-<?php echo $ad_number; ?>" name="<?php echo $obj->option_display_for_users; ?>" style="width:160px">
        <option value="<?php echo AD_DISPLAY_ALL_USERS; ?>" <?php echo ($obj->get_display_for_users()==AD_DISPLAY_ALL_USERS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_ALL_USERS; ?></option>
        <option value="<?php echo AD_DISPLAY_LOGGED_IN_USERS; ?>" <?php echo ($obj->get_display_for_users()==AD_DISPLAY_LOGGED_IN_USERS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_LOGGED_IN_USERS; ?></option>
        <option value="<?php echo AD_DISPLAY_NOT_LOGGED_IN_USERS; ?>" <?php echo ($obj->get_display_for_users()==AD_DISPLAY_NOT_LOGGED_IN_USERS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DISPLAY_NOT_LOGGED_IN_USERS; ?></option>
     </select>
     users

     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <div style="display: inline-block; padding-top: 5px; float: right;">
      <input type="hidden"   style="border-radius: 5px;" name="<?php echo $obj->option_process_php; ?>" value="0" />
      <input type="checkbox" style="border-radius: 5px;" name="<?php echo $obj->option_process_php; ?>" value="1" id="process-php-<?php echo $ad_number; ?>" <?php if ($obj->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_process_php; ?>" title="Process PHP code in block">Process PHP</label>
     </div>
  </div>

  <div style="padding:0px 0px 8px 16px;">
     Block Style:&nbsp;&nbsp;&nbsp;
     <select style="border-radius: 5px;" id="block-alignment-<?php echo $ad_number; ?>" name="<?php echo $obj->option_float_type; ?>" style="width:120px">
        <option value="<?php echo AD_ALIGNMENT_NO_WRAPPING; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_NO_WRAPPING) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_NO_WRAPPING; ?></option>
        <option value="<?php echo AD_ALIGNMENT_CUSTOM_CSS; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_CUSTOM_CSS) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_CUSTOM_CSS; ?></option>
        <option value="<?php echo AD_ALIGNMENT_NONE; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_NONE; ?></option>
        <option value="<?php echo AD_ALIGNMENT_LEFT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_RIGHT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_RIGHT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_CENTER; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_CENTER) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_CENTER; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_LEFT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_FLOAT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?>" <?php echo ($obj->get_alignment_type()==AD_ALIGNMENT_FLOAT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?></option>
     </select>
     <span id="custom-css-<?php echo $ad_number; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Custom CSS: <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_custom_css; ?>" value="<?php echo $obj->get_custom_css() ?>" size="67" maxlength="160" title="Custom CSS code for wrapping div" /></span>
  </div>

  <div style="padding:8px 8px 8px 8px; margin-left: 10px; border: 1px solid #ddd; border-radius: 5px;">
     <div style="display: inline-block; vertical-align: top; margin-right: 20px;">
       Block enabled on:
     </div>
     <div style="display: inline-block; vertical-align: top; margin-right: 40px;">
      <input style="border-radius: 5px;" type="hidden" name="<?php echo $obj->option_widget_settings_post; ?>" value="0" />
      <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_widget_settings_post; ?>" value="1" <?php if ($obj->get_display_settings_post()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_post; ?>">Posts</label><br>

      <input style="border-radius: 5px;" type="hidden" name="<?php echo $obj->option_widget_settings_page; ?>" value="0" />
      <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_widget_settings_page; ?>" value="1" <?php if ($obj->get_display_settings_page()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_page; ?>">Pages</label><br>
     </div>

     <div id="enabled-pages-<?php echo $ad_number; ?>" style="display: inline-block; vertical-align: top; margin-right: 20px;">
       <div style="display: inline-block; vertical-align: top; margin-right: 20px; width: 180px;">
         Excerpt, Before Title, Widget and PHP call enabled also on:
       </div>
       <div style="display: inline-block; margin-right: 20px;">
          <input style="border-radius: 5px;" type="hidden" name="<?php echo $obj->option_widget_settings_home; ?>" value="0" />
          <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_widget_settings_home; ?>" value="1" <?php if ($obj->get_display_settings_home()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_home; ?>">Homepage</label><br>

          <input style="border-radius: 5px;" type="hidden" name="<?php echo $obj->option_widget_settings_category; ?>" value="0" />
          <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_widget_settings_category; ?>" value="1" <?php if ($obj->get_display_settings_category()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_category; ?>">Category pages</label><br>
       </div>
       <div style="display: inline-block;">
          <input style="border-radius: 5px;" type="hidden" name="<?php echo $obj->option_widget_settings_search; ?>" value="0" />
          <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_widget_settings_search; ?>" value="1" <?php if ($obj->get_display_settings_search()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_search; ?>">Search pages</label><br>

          <input style="border-radius: 5px;" type="hidden" name="<?php echo $obj->option_widget_settings_archive; ?>" value="0" />
          <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_widget_settings_archive; ?>" value="1" <?php if ($obj->get_display_settings_archive()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_archive; ?>">Archive pages</label><br>
       </div>
     </div>
  </div>

  <div id="after-published-<?php echo $ad_number; ?>" style="padding:0px 38px 16px 16px; margin-top: 10px; float: right;">
    Display <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_ad_after_day; ?>" value="<?php echo $obj->get_ad_after_day() ?>" size="2" maxlength="3" title="0 means publish immediately" /> days after post is published
  </div>
  <div style="padding:0px 0px 16px 16px; margin-top: 10px;">
     General tag:  <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_ad_general_tag; ?>" value="<?php echo $obj->get_ad_general_tag() ?>" size="40" maxlength="60" title="Used for {tags} when no page data is found" />
  </div>

  <div style="padding:0px 8px 16px 16px;">
     Referrers: &nbsp;&nbsp;&nbsp;
     <input style="border-radius: 5px;" title="Comma separated domains" type="text" name="<?php echo $obj->option_ad_domain_list; ?>" value="<?php echo $obj->get_ad_domain_list() ?>" size="60" maxlength="500"/>&nbsp;&nbsp;&nbsp;
     <input style="border-radius: 5px;" type="radio" name="<?php echo $obj->option_ad_domain_list_type; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_domain_list_type() == AD_BLACK_LIST) echo 'checked '; ?>/> <?php echo AD_BLACK_LIST; ?>&nbsp;&nbsp;
     <input style="border-radius: 5px;" type="radio" name="<?php echo $obj->option_ad_domain_list_type; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_domain_list_type() == AD_WHITE_LIST) echo 'checked '; ?>/> <?php echo AD_WHITE_LIST; ?>
  </div>
  <div style="padding:0px 8px 16px 16px;">
     Categories:&nbsp;
     <input style="border-radius: 5px;" title="Comma separated category names" type="text" name="<?php echo $obj->option_ad_category_list; ?>" value="<?php echo $obj->get_ad_block_cat() ?>" size="60" maxlength="500"/>&nbsp;&nbsp;&nbsp;
     <input style="border-radius: 5px;" type="radio" name="<?php echo $obj->option_ad_category_list_type; ?>" value="<?php echo AD_BLACK_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_BLACK_LIST) echo 'checked '; ?>/> <?php echo AD_BLACK_LIST; ?>&nbsp;&nbsp;
     <input style="border-radius: 5px;" type="radio" name="<?php echo $obj->option_ad_category_list_type; ?>" value="<?php echo AD_WHITE_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_WHITE_LIST) echo 'checked '; ?>/> <?php echo AD_WHITE_LIST; ?>
  </div>

  <div id="paragraph-settings-<?php echo $ad_number; ?>" style="padding:8px 8px 0 8px; margin: 0 0 10px 10px; border: 1px solid #ddd; border-radius: 5px; height: 65px;">
    <div style="display: inline-block; vertical-align: ">
      <div style="margin: 3px 0 3px 0; ">
        Minimum number of paragraphs to show this block <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_paragraph_number_minimum; ?>" value="<?php echo $obj->get_paragraph_number_minimum() ?>" size="2" maxlength="3"/>&nbsp;&nbsp;&nbsp;
        Skip paragraphs that contain <input style="border-radius: 5px;" title="Comma separated text" type="text" name="<?php echo $obj->option_paragraph_text; ?>" value="<?php echo $obj->get_paragraph_text() ?>" size="28" maxlength="100"/>
      </div>
      <div style="margin: 3px 0 3px 0; ">
        Paragraph number <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_paragraph_number; ?>" value="<?php echo $obj->get_paragraph_number() ?>" size="2" maxlength="3"/> &nbsp;&nbsp;&nbsp;&nbsp;0 means random paragraph.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Counting
        <select style="border-radius: 5px;" name="<?php echo $obj->option_direction_type; ?>" style="width:120px">
          <option value="<?php echo AD_DIRECTION_FROM_TOP; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_TOP; ?></option>
          <option value="<?php echo AD_DIRECTION_FROM_BOTTOM; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_BOTTOM; ?></option>
        </select>
      </div>
    </div>
  </div>

  <div id="excerpt-settings-<?php echo $ad_number; ?>" style="padding:8px 8px 8px 8px; margin: 0 0 10px 10px; border: 1px solid #ddd; border-radius: 5px; height: 35px;">
    Excerpt number <input style="border-radius: 5px;" type="text" name="<?php echo $obj->option_excerpt_number; ?>" value="<?php echo $obj->get_excerpt_number() ?>" size="2" maxlength="2"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0 means all excerpts.
  </div>

  <div style="padding:5px; text-align: left; margin-left: 10px;">
    <input style="border-radius: 5px;" type="hidden"   name="<?php echo $obj->option_enable_manual; ?>" value="0" />
    <input style="border-radius: 5px;" type="checkbox" name="<?php echo $obj->option_enable_manual; ?>" value="1" <?php if ($obj->get_enable_manual () == AD_SETTINGS_CHECKED) echo 'checked '; ?>>
    <label for="<?php echo $obj->option_enable_manual; ?>" title="Enable or disable manual insertion of this code block in posts and pages">
      Enable manual insertion with shortcode
    </label>
    <pre style= "margin: 0; display: inline; color: blue;">[adinserter block="<?php echo $ad_number; ?>"]</pre> or
    <pre style= "margin: 0; display: inline; color: blue;">[adinserter name="<?php echo $obj->get_ad_name(); ?>"]</pre>.
    <hr />
    <input style="border-radius: 5px;" type="hidden"   name="<?php echo $obj->option_enable_php_call; ?>" value="0" />
    <input style="border-radius: 5px;" id="enable-php-call-<?php echo $ad_number; ?>" type="checkbox" name="<?php echo $obj->option_enable_php_call; ?>" value="1" <?php if ($obj->get_enable_php_call () == AD_SETTINGS_CHECKED) echo 'checked '; ?>>
    <label for="<?php echo $obj->option_enable_php_call; ?>" title="Enable or disable PHP function call to insert this code block at any position in template file. If function is disabled for block it will return empty string.">
      Enable PHP function adinserter for block <?php echo $ad_number; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Code example:
    </label>
    <pre style= "margin: 0; display: inline; color: blue;">&lt;?php echo adinserter (<?php echo $ad_number; ?>); ?&gt;</pre>
    <hr />
    To disable automatic insertion of this code block on specific pages for <span style="font-weight: bold;">Content and Paragraph</span> display modes put HTML code
    <pre style= "margin: 0; display: inline; color: blue;">&lt;!-- disable adinserter <?php echo $ad_number; ?> --&gt;</pre> or
    <pre style= "margin: 0; display: inline; color: blue;">&lt;!-- disable adinserter <?php echo $obj->get_ad_name(); ?> --&gt;</pre>
    at the end of post code.
  </div>

</div>

<?php
  }
?>


<div id="tab-17" style="padding: 0 0 30px 0;">
  <br />

  <div style="padding-left:4px;">
    <h3>Header Code</h3>
  </div>

  <div style="float: right; padding: 1px 5px;">
        <input style="border-radius: 5px;" name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>

  <div style="padding:0px 8px 16px 16px;">
    Code will be placed within the &lt;head&gt;&lt;/head&gt; section of the theme (theme-dependent)
  </div>

  <div style="padding-left:16px;">
      HTML / Javascript / CSS / PHP code
  </div>
  <div style="padding:8px;">
      <textarea id="block_17" name="<?php echo $adH->option_ad_data; ?>" rows="36" cols="98" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 384px;"><?php echo str_replace ("&", "&amp;", $adH->get_ad_data()); ?></textarea>
  </div>

  <div style="display: inline-block; padding-top: 5px; float: left; margin-left: 10px;">
    <input style="border-radius: 5px;" type="hidden"   name="<?php echo $adH->option_enable_manual; ?>" value="0" />
    <input style="border-radius: 5px;" type="checkbox" name="<?php echo $adH->option_enable_manual; ?>" value="1" <?php if ($adH->get_enable_manual () == AD_SETTINGS_CHECKED) echo 'checked '; ?>>
    <label for="<?php echo $adH->option_enable_manual; ?>" title="Enable or disable insertion of this code into header">Enable</label>
  </div>
  <div style="display: inline-block; padding-top: 5px; float: right; margin-right: 5px;">
   <input style="border-radius: 5px;" type="hidden"   name="<?php echo $adH->option_process_php; ?>" value="0" />
   <input style="border-radius: 5px;" type="checkbox" name="<?php echo $adH->option_process_php; ?>" value="1" id="process-php-17" <?php if ($adH->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?>>
   <label for="<?php echo $adH->option_process_php; ?>" title="Process PHP code">Process PHP</label>
  </div>
</div>

<div id="tab-18" style="padding: 0 0 30px 0;">
  <br />

  <div style="padding-left:4px;">
    <h3>Footer Code</h3>
  </div>

  <div style="float: right; padding: 1px 5px;">
        <input style="border-radius: 5px;" name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>

  <div style="padding:0px 8px 16px 16px;">
    Code will be placed near the &lt;/body&gt; tag of the theme (theme-dependent)
  </div>

  <div style="padding-left:16px;">
      HTML / Javascript / PHP code
  </div>
  <div style="padding:8px;">
      <textarea id="block_18" name="<?php echo $adF->option_ad_data; ?>" rows="36" cols="98" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace; font-weight: bold; width: 719px; height: 384px;"><?php echo str_replace ("&", "&amp;", $adF->get_ad_data()); ?></textarea>
  </div>

  <div style="display: inline-block; padding-top: 5px; float: left; margin-left: 10px;">
    <input style="border-radius: 5px;" type="hidden"   name="<?php echo $adF->option_enable_manual; ?>" value="0" />
    <input style="border-radius: 5px;" type="checkbox" name="<?php echo $adF->option_enable_manual; ?>" value="1" <?php if ($adF->get_enable_manual () == AD_SETTINGS_CHECKED) echo 'checked '; ?>>
    <label for="<?php echo $adF->option_enable_manual; ?>" title="Enable or disable insertion of this code into footer">Enable</label>
  </div>
  <div style="display: inline-block; padding-top: 5px; float: right; margin-right: 5px;">
   <input style="border-radius: 5px;" type="hidden"   name="<?php echo $adF->option_process_php; ?>" value="0" />
   <input style="border-radius: 5px;" type="checkbox" name="<?php echo $adF->option_process_php; ?>" value="1" id="process-php-18" <?php if ($adF->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?>>
   <label for="<?php echo $adF->option_process_php; ?>" title="Process PHP code">Process PHP</label>
  </div>
</div>


<div id="tab-19" style="padding: 0 0 30px 0;">
  <br />

  <div style="padding-left:4px;">
    <h3>Ad Inserter Settings</h3>
  </div>

  <div style="float: right; padding: 1px 5px;">
        <input style="border-radius: 5px;" name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>

  <div style="clear: both;"></div>

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

</div>


</div>

<div style="height: 30px; margin: 8px 0 0 0;">
  <div style="float: left; padding: 1px 1px; color: red;">
        <input onclick="if (confirm('Are you sure you want to reset all settings?')) return true; return false" name="<?php echo AD_FORM_CLEAR; ?>" value="Reset All Settings" type="submit" style="width:120px; font-weight: bold; color: #e44;"/>
  </div>
  <div style="float: right; padding: 1px 1px;">
        <input name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>
</div>

<input id="ai-active-tab" type="hidden" name="ai-active-tab" value="<?php echo isset ($_POST ['ai-active-tab']) ? $_POST ['ai-active-tab'] : 1; ?>" />

</form>

</div>

  <div style="float: left;">
    <div style="width: 735px; padding: 2px 8px 6px 8px; margin: 8px 0 8px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px; background: #fff;">
      <h2>Monitor Google AdSense and Amazon Associates earnings with <a href="http://tinymonitor.com/" target="_blank">Tiny Monitor</a></h2>
      <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tinymonitor-logo.png" alt="Tiny Monitor" /></a>
      <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tiny-monitor.png" alt="Amazon Associates" /></a>
    </div>
  </div>

<script type="text/javascript">
  shSettings ['theme'] = '<?php echo $syntax_highlighter_theme; ?>';
</script>

<?php
  }
