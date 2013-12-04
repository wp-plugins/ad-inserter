<?php

require_once 'constants.php';

function print_settings_form ($ad_array){

?>

<div id="ai-settings" style="margin-right: 16px; float: left; ">

  <div style="width: 735px; /*height: 90px; */padding: 8px 8px 8px 8px; margin: 8px 0 8px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
    <div style="float: right; margin: 17px 20px 0 0; text-decoration: none;">
      <a style="text-decoration: none;" href="http://igorfuna.com/contact/contact-igor-funa" target="_blank">Contact Author</a> #
      <a style="text-decoration: none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LHGZEMRTR7WB4" target="_blank">Donate</a> #
      <a style="text-decoration: none;" href="http://tinymonitor.com/" target="_blank">AdSense and Amazon Monitor</a> #
      <a style="text-decoration: none;" href="http://igorfuna.com/wordpress-hosting" target="_blank">Cool Wordpress Hosting</a>
    </div>
    <h1><?php echo AD_INSERTER_TITLE . ' ' . AD_INSERTER_VERSION ?></h1>
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
    echo "
      <li title=\"$ad_name\"><a href=\"#tab-$ad_number\"><span style=\"", $obj->get_append_type() != AD_SELECT_NONE ? "font-weight: bold; color: #e44;" : "", "\">$ad_number</span></a></li>";
  }
?>
  </ul>

<?php
  $ad_number = 0;
  foreach($ad_array as $obj){
    $ad_number ++;
?>

<div id="tab-<?php echo $ad_number; ?>" style="padding: 0;">

    <br />
    <br />
    <br />

  <div style="padding-left:4px;">
    <h3><?php echo $ad_number, ".  ", $obj->get_ad_name(); ?></h3>
  </div>

  <div style="padding:0px 8px 16px 16px;">
     Block Name:  <input type="text" name="<?php echo $obj->option_ad_name; ?>" value="<?php echo $obj->get_ad_name() ?>" size="50" maxlength="50"/> &nbsp;Here you can rename block.
  </div>
  <div style="padding-left:16px;">
      Put ad or any HTML / Javascript / PHP code here (separate rotating versions with |rotate| ):
  </div>
  <div style="padding:8px;">
      <textarea name="<?php echo $obj->option_ad_data; ?>" rows="24" cols="98" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace;"><?php echo str_replace ("&", "&amp;", $obj->get_ad_data()); ?></textarea>
  </div>

  <div style="padding:8px 8px 16px 16px;">
     Display:
     <select name="<?php echo $obj->option_append_type; ?>" style="width:200px">
        <option value="<?php echo AD_SELECT_NONE; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_NONE; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_TITLE; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_TITLE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_TITLE; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_CONTENT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_CONTENT; ?></option>
        <option value="<?php echo AD_SELECT_BEFORE_PARAGRAPH; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_PARAGRAPH) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_PARAGRAPH; ?></option>
        <option value="<?php echo AD_SELECT_AFTER_CONTENT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_AFTER_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_CONTENT; ?></option>
        <option value="<?php echo AD_SELECT_WIDGET; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_WIDGET) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_WIDGET; ?></option>
        <option value="<?php echo AD_SELECT_MANUAL; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_MANUAL) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_MANUAL; ?></option>
     </select>

     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Alignment:
     <select name="<?php echo $obj->option_float_type; ?>" style="width:100px">
        <option value="<?php echo AD_ALIGNMENT_NONE; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_NONE; ?></option>
        <option value="<?php echo AD_ALIGNMENT_LEFT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_RIGHT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_RIGHT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_CENTER; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_CENTER) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_CENTER; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_LEFT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_FLOAT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_FLOAT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?></option>
     </select>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <div style="display: inline-block; vertical-align: middle;">
      <input type="hidden"   name="<?php echo $obj->option_process_php; ?>" value="0" />
      <input type="checkbox" name="<?php echo $obj->option_process_php; ?>" value="1" <?php if ($obj->get_process_php () == AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_process_php; ?>">Process PHP</label>
     </div>
  </div>
  <div style="padding:8px 8px 8px 8px; margin-right: 5px; border: 1px solid #ddd; border-radius: 5px; width: 105px; float: right;">
     Block enabled on:
     <br /><br />
     <input type="hidden" name="<?php echo $obj->option_widget_settings_post; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_post; ?>" value="1" <?php if ($obj->get_widget_settings_post()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_post; ?>">Posts</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_page; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_page; ?>" value="1" <?php if ($obj->get_widget_settings_page()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_page; ?>">Pages</label><br>
     <br />

     <strong>Before Title</strong> and
     <strong>Widget</strong> enabled also on:
     <br /><br />
     <input type="hidden" name="<?php echo $obj->option_widget_settings_home; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_home; ?>" value="1" <?php if ($obj->get_widget_settings_home()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_home; ?>">Homepage</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_category; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_category; ?>" value="1" <?php if ($obj->get_widget_settings_category()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_category; ?>">Category pages</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_search; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_search; ?>" value="1" <?php if ($obj->get_widget_settings_search()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_search; ?>">Search pages</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_archive; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_archive; ?>" value="1" <?php if ($obj->get_widget_settings_archive()==AD_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_archive; ?>">Archive pages</label><br>
  </div>
  <div style="padding:8px 8px 8px 8px; margin-left: 10px; border: 1px solid #ddd; border-radius: 5px; width: 565px;">
       <div style="padding-top: 0px;">
        <strong>Before Paragraph</strong> display settings
        &nbsp;&nbsp;&nbsp;&nbsp;
         Minimum number of paragraphs to show this block <input type="text" name="<?php echo $obj->option_paragraph_number_minimum; ?>" value="<?php echo $obj->get_paragraph_number_minimum() ?>" size="2" maxlength="3"/>
       </div>
       <br />
       Paragraph number <input type="text" name="<?php echo $obj->option_paragraph_number; ?>" value="<?php echo $obj->get_paragraph_number() ?>" size="2" maxlength="3"/> &nbsp;&nbsp;&nbsp;&nbsp;0 means random paragraph.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Counting
       <select name="<?php echo $obj->option_direction_type; ?>" style="width:120px">
          <option value="<?php echo AD_DIRECTION_FROM_TOP; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_TOP; ?></option>
          <option value="<?php echo AD_DIRECTION_FROM_BOTTOM; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_BOTTOM; ?></option>
       </select>
  </div>
  <br />
  <div style="padding:0px 8px 16px 16px;">
    Display for post published after:  <input type="text" name="<?php echo $obj->option_ad_after_day; ?>" value="<?php echo $obj->get_ad_after_day() ?>" size="2" maxlength="3"/> day(s). &nbsp;&nbsp;&nbsp;&nbsp;0 means publish immediately.
  </div>

  <div style="padding:0px 8px 16px 16px;">
     General tag:  <input type="text" name="<?php echo $obj->option_ad_general_tag; ?>" value="<?php echo $obj->get_ad_general_tag() ?>" size="40" maxlength="60"/> &nbsp;&nbsp;&nbsp;&nbsp;Used for {xxx} tags when no data is found.
  </div>

  <div style="padding:0px 8px 16px 16px;">
     Do not display to users from domains:  <input title="Comma separated domains, e.g. technorati.com, facebook.com" type="text" name="<?php echo $obj->option_ad_block_user; ?>" value="<?php echo $obj->get_ad_block_user() ?>" size="58" maxlength="500"/>
  </div>
  <div style="padding:0px 8px 16px 16px;">
     Categories:  <input title="Comma separated category names" type="text" name="<?php echo $obj->option_ad_block_cat; ?>" value="<?php echo $obj->get_ad_block_cat() ?>" size="60" maxlength="500"/>&nbsp;&nbsp;&nbsp;
     <input type="radio" name="<?php echo $obj->option_ad_block_cat_type; ?>" value="<?php echo AD_CATEGORY_BLACK_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_CATEGORY_BLACK_LIST) echo 'checked '; ?>/> <?php echo AD_CATEGORY_BLACK_LIST; ?>&nbsp;&nbsp;
     <input type="radio" name="<?php echo $obj->option_ad_block_cat_type; ?>" value="<?php echo AD_CATEGORY_WHITE_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_CATEGORY_WHITE_LIST) echo 'checked '; ?>/> <?php echo AD_CATEGORY_WHITE_LIST; ?>
  </div>

  <hr />
  <div style="padding:5px; text-align: left;">
    To disable this code block in any post put HTML code
    <pre style= "margin: 0; display: inline; color: blue;">&lt;!-- Ad Inserter Ad <?php echo $ad_number; ?> Disabled --&gt;</pre>
    at the end of post code.
    <br />
    To manually insert this code block in posts use code
    <pre style= "margin: 0; display: inline; color: blue;">{adinserter <?php echo $ad_number; ?>}</pre> or
    <pre style= "margin: 0; display: inline; color: blue;">{adinserter <?php echo $obj->get_ad_name(); ?>}</pre>.
  </div>

</div>

<?php
  }
?>

</div>

<div style="width: 735px; height: 30px; margin: 8px 0 0 0; padding: 3px 3px;">
  <div style="float: left; padding: 1px 1px; color: red;">
        <input onclick="if (confirm('Are you sure you want to reset all settings?')) return true; return false" name="<?php echo AD_FORM_CLEAR; ?>" value="Reset All Settings" type="submit" style="width:120px; font-weight: bold; color: #e44;"/>
  </div>
  <div style="float: right; padding: 1px 1px;">
        <input name="<?php echo AD_FORM_SAVE; ?>" value="Save All Settings" type="submit" style="width:120px; font-weight: bold;" />
  </div>
</div>

</form>

</div>

  <div style="float: left;">
    <div style="width: 735px; padding: 2px 8px 6px 8px; margin: 8px 0 8px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
      <h2>Monitor Google AdSense and Amazon Associates earnings with <a href="http://tinymonitor.com/" target="_blank">Tiny Monitor</a></h2>
      <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tinymonitor-logo.png" alt="Tiny Monitor" /></a>
      <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tiny-monitor.png" alt="Amazon Associates" /></a>
    </div>
  </div>

<?php
  }
?>
