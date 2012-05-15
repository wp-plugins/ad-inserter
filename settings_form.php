<?php

require_once 'constants.php';

function print_settings_form($ad_array){

?>

<div style="padding:8px;">
<a style="float: right; position: absolute; left: 560px;" href="http://hostgator.si/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>hostgator.gif" alt="HostGator" /></a>
<h2><?php echo AD_INSERTER_TITLE . ' (version ' . AD_INSERTER_VERSION . ')' ?></h2>
by Igor Funa - <a href="http://igorfuna.com/contact/contact-igor-funa" target="_blank">Contact Me</a> |
<a href="http://igorfuna.com/contact/contact-igor-funa" target="_blank">Bug Report &amp; Feature Request</a> |
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LHGZEMRTR7WB4" target="_blank">Donate</a>
<br />
<hr />

<div style="width: 770px; padding: 0 8px 8px 8px; margin: 12px 0 12px 0; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">
  <h3>Monitor Google AdSense and Amazon Associates earnings with <a href="http://tinymonitor.com/" target="_blank">Tiny Monitor</a></h3>
  <a href="http://tinymonitor.com/" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>tinymonitor-logo.png" alt="Tiny Monitor" /></a>
  <br />
  <a href="http://tinymonitor.com/adsense-monitor" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>adsense-header.png" alt="Google AdSense" /></a>
  <br />
  <a href="http://tinymonitor.com/amazon-monitor" target="_blank"><img src="<?php echo AD_INSERTER_PLUGIN_IMAGES_URL; ?>amazon-header.png" alt="Amazon Associates" /></a>
  <br />
</div>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="ad_form" name="ad_form">

<div style="padding:16px 16px 16px 580px">
      Reset all settings <input onclick="if (confirm('Are you sure you want to reset all settings?'))return true;return false" name="<?php echo AD_FORM_CLEAR; ?>" value="Reset" type="submit" style="width:100px;"/>
</div>

<?php
  $ad_number = 0;
  foreach($ad_array as $obj){
    $ad_number ++;
?>
<div style="padding:16px 16px 16px 680px">
      <input name="<?php echo AD_FORM_SAVE; ?>" value="Save" type="submit" style="width:100px;" />
</div>

<div style="padding:8px; margin-bottom:16px; width:770px; background-color: white; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">

  <div style="padding-left:4px;">
    <h3><?php echo $ad_number, ".  ", $obj->get_ad_name(); ?></h3>
  </div>

   <div style="padding:0px 8px 16px 16px;">
      Ad Name:  <input type="text" name="<?php echo $obj->option_ad_name; ?>" value="<?php echo $obj->get_ad_name() ?>" size="40" maxlength="50"/> (Here you can rename ad block.)
   </div>
  <div style="padding-left:16px;">
      Put ad or any HTML code here (separate rotating versions with ||):
  </div>
  <div style="padding:8px;">
      <textarea name="<?php echo $obj->option_ad_data; ?>" rows="24" cols="92" style="background-color:#F9F9F9; font-family: Courier, 'Courier New', monospace;"><?php echo $obj->get_ad_data(); ?></textarea>
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
     &nbsp;&nbsp;&nbsp; Alignment:
     <select name="<?php echo $obj->option_float_type; ?>" style="width:100px">
        <option value="<?php echo AD_ALIGNMENT_NONE; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_NONE; ?></option>
        <option value="<?php echo AD_ALIGNMENT_LEFT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_RIGHT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_RIGHT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_CENTER; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_CENTER) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_CENTER; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_LEFT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_FLOAT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_LEFT; ?></option>
        <option value="<?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?>" <?php echo ($obj->get_float_type()==AD_ALIGNMENT_FLOAT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_ALIGNMENT_FLOAT_RIGHT; ?></option>
     </select>
  </div>
  <div style="padding:8px 8px 8px 8px;; border: 1px solid #ddd; border-radius: 5px; width: 90px; float: right;">
     <strong>Before Title</strong> and
     <strong>Widget</strong>
     <br />
     enabled on
     <br /><br />
     <input type="hidden" name="<?php echo $obj->option_widget_settings_home; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_home; ?>" value="1" <?php if ($obj->get_widget_settings_home()==AD_WIDGET_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_home; ?>">Home</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_page; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_page; ?>" value="1" <?php if ($obj->get_widget_settings_page()==AD_WIDGET_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_page; ?>">Page</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_post; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_post; ?>" value="1" <?php if ($obj->get_widget_settings_post()==AD_WIDGET_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_post; ?>">Post</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_category; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_category; ?>" value="1" <?php if ($obj->get_widget_settings_category()==AD_WIDGET_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_category; ?>">Category</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_search; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_search; ?>" value="1" <?php if ($obj->get_widget_settings_search()==AD_WIDGET_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_search; ?>">Search</label><br>

     <input type="hidden" name="<?php echo $obj->option_widget_settings_archive; ?>" value="0" />
     <input type="checkbox" name="<?php echo $obj->option_widget_settings_archive; ?>" value="1" <?php if ($obj->get_widget_settings_archive()==AD_WIDGET_SETTINGS_CHECKED) echo 'checked '; ?>> <label for="<?php echo $obj->option_widget_settings_archive; ?>">Archive</label><br>
  </div>
  <div style="padding:8px 8px 8px 8px;; border: 1px solid #ddd; border-radius: 5px; width: 620px;">
       <strong>Before Paragraph</strong> display settings
       <br /><br />
       Minimum number of paragraphs <input type="text" name="<?php echo $obj->option_paragraph_number_minimum; ?>" value="<?php echo $obj->get_paragraph_number_minimum() ?>" size="2" maxlength="3"/> &nbsp;&nbsp;&nbsp; Ad will not be shown if the number of paragraphs is below this limit.
       <br />
       Paragraph number (0 = random) <input type="text" name="<?php echo $obj->option_paragraph_number; ?>" value="<?php echo $obj->get_paragraph_number() ?>" size="2" maxlength="3"/> &nbsp;&nbsp;&nbsp; Counting
       <select name="<?php echo $obj->option_direction_type; ?>" style="width:120px">
          <option value="<?php echo AD_DIRECTION_FROM_TOP; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_TOP; ?></option>
          <option value="<?php echo AD_DIRECTION_FROM_BOTTOM; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_BOTTOM; ?></option>
       </select>
  </div>
  <br />
  <div style="padding:0px 8px 16px 16px;">
     General Tag:  <input type="text" name="<?php echo $obj->option_ad_general_tag; ?>" value="<?php echo $obj->get_ad_general_tag() ?>" size="30" maxlength="40"/> Used for {xxx} tags when no appropriate data is found.
  </div>
  <div style="padding:0px 8px 16px 16px;">
    Display ads for post published after:  <input type="text" name="<?php echo $obj->option_ad_after_day; ?>" value="<?php echo $obj->get_ad_after_day() ?>" size="2" maxlength="3"/> day(s). (0 = immediately)
  </div>
  <div style="padding:0px 8px 16px 16px;">
     Do not display ads to users from domains:  <input type="text" name="<?php echo $obj->option_ad_block_user; ?>" value="<?php echo $obj->get_ad_block_user() ?>" size="30" maxlength="200"/> (e.g. technorati.com, facebook.com)
  </div>
  <div style="padding:0px 8px 16px 16px;">
     Categories:  <input type="text" name="<?php echo $obj->option_ad_block_cat; ?>" value="<?php echo $obj->get_ad_block_cat() ?>" size="60" maxlength="200"/> (comma separated)&nbsp;&nbsp;&nbsp;
     <input type="radio" name="<?php echo $obj->option_ad_block_cat_type; ?>" value="<?php echo AD_CATEGORY_BLACK_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_CATEGORY_BLACK_LIST) echo 'checked '; ?>/><?php echo AD_CATEGORY_BLACK_LIST; ?>&nbsp;&nbsp;
     <input type="radio" name="<?php echo $obj->option_ad_block_cat_type; ?>" value="<?php echo AD_CATEGORY_WHITE_LIST; ?>" <?php if ($obj->get_ad_block_cat_type() == AD_CATEGORY_WHITE_LIST) echo 'checked '; ?>/><?php echo AD_CATEGORY_WHITE_LIST; ?>

  </div>
</div>

<?php
  }
?>

<div style="padding:16px 16px 16px 680px">
      <input name="<?php echo AD_FORM_SAVE; ?>" value="Save" type="submit" style="width:100px;" />
</div>

</form>

<div style="padding:8px 8px 32px 8px; margin-bottom:16px; width:770px; background-color: white; border: 1px solid rgb(221, 221, 221); border-radius: 5px;">

<h3>Advanced Configuration</h3>

<div style="padding:16px;">
You can disable displaying advertisements in certain post.<br/><br/>
1. Put &lt;!-- Ad Inserter Ad 1 Disabled --&gt; within post to disable the Ad #1 code.<br/>
2. Put &lt;!-- Ad Inserter Ad 2 Disabled --&gt; within post to disable the Ad #2 code.<br/>
3. Put &lt;!-- Ad Inserter Ad 3 Disabled --&gt; within post to disable the Ad #3 code.<br/>
4. Put &lt;!-- Ad Inserter Ad 4 Disabled --&gt; within post to disable the Ad #4 code.<br/>
5. Put &lt;!-- Ad Inserter Ad 5 Disabled --&gt; within post to disable the Ad #5 code.<br/>
6. Put &lt;!-- Ad Inserter Ad 6 Disabled --&gt; within post to disable the Ad #6 code.<br/>
7. Put &lt;!-- Ad Inserter Ad 7 Disabled --&gt; within post to disable the Ad #7 code.<br/>
8. Put &lt;!-- Ad Inserter Ad 8 Disabled --&gt; within post to disable the Ad #8 code.<br/>
</ul>
</div>

</div>

<?php
}
