<?php

require_once 'constants.php';

function print_settings_form($ad_array){

?>

<div style="padding:8px;">
<h2><?php echo AD_INSERTER_TITLE . ' (version ' . AD_INSERTER_VERSION . ')' ?></h2>
by Igor Funa - <a href="http://igorfuna.com/contact/contact-igor-funa" target="_blank">Contact Me</a> |
<a href="http://igorfuna.com/contact/contact-igor-funa" target="_blank">Bug Report &amp; Feature Request</a>

</div>

<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="ad_form" name="ad_form">

<div style="padding:16px 16px 16px 560px">
      <input onclick="if (confirm('Are you sure you want to reset all settings?'))return true;return false" name="<?php echo AD_FORM_CLEAR; ?>" value="Reset" type="submit" style="width:100px;"/>
      <input name="<?php echo AD_FORM_SAVE; ?>" value="Save" type="submit" style="width:100px;" />
</div>

<?php
  $ad_number = 0;
	foreach($ad_array as $obj){
    $ad_number ++;
?>
<div style="padding:8px; margin-bottom:16px; width:770px; background-color: white; border: 1px solid rgb(221, 221, 221);">

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
      <textarea name="<?php echo $obj->option_ad_data; ?>" rows="24" cols="90" style="background-color:#F9F9F9;"><?php echo $obj->get_ad_data(); ?></textarea>
	</div>

   <div style="padding:8px 8px 16px 16px;">
      Display:
      <select name="<?php echo $obj->option_append_type; ?>" style="width:200px">
         <option value="<?php echo AD_SELECT_NONE; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_NONE; ?></option>
         <option value="<?php echo AD_SELECT_BEFORE_PARAGRAPH; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_PARAGRAPH) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_PARAGRAPH; ?></option>
         <option value="<?php echo AD_SELECT_BEFORE_CONTENT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_BEFORE_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_BEFORE_CONTENT; ?></option>
         <option value="<?php echo AD_SELECT_AFTER_CONTENT; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_AFTER_CONTENT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_AFTER_CONTENT; ?></option>
         <option value="<?php echo AD_SELECT_WIDGET; ?>" <?php echo ($obj->get_append_type()==AD_SELECT_WIDGET) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_SELECT_WIDGET; ?></option>
      </select>
   </div>
   <div style="border: 1px solid #ddd;">
     <div style="padding:0px 8px 16px 16px;">
        Before Paragraph:  <input type="text" name="<?php echo $obj->option_paragraph_number; ?>" value="<?php echo $obj->get_paragraph_number() ?>" size="2" maxlength="3"/> (0 means random paragraph)
     </div>
     <div style="padding:8px 8px 16px 16px;">
        Direction:
        <select name="<?php echo $obj->option_direction_type; ?>" style="width:120px">
           <option value="<?php echo AD_DIRECTION_FROM_TOP; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_TOP) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_TOP; ?></option>
           <option value="<?php echo AD_DIRECTION_FROM_BOTTOM; ?>" <?php echo ($obj->get_direction_type()==AD_DIRECTION_FROM_BOTTOM) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_DIRECTION_FROM_BOTTOM; ?></option>
        </select>
     </div>
     <div style="padding:8px 8px 16px 16px;">
        Float:
        <select name="<?php echo $obj->option_float_type; ?>" style="width:80px">
           <option value="<?php echo AD_FLOAT_NONE; ?>" <?php echo ($obj->get_float_type()==AD_FLOAT_NONE) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_FLOAT_NONE; ?></option>
           <option value="<?php echo AD_FLOAT_LEFT; ?>" <?php echo ($obj->get_float_type()==AD_FLOAT_LEFT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_FLOAT_LEFT; ?></option>
           <option value="<?php echo AD_FLOAT_RIGHT; ?>" <?php echo ($obj->get_float_type()==AD_FLOAT_RIGHT) ? AD_SELECT_SELECTED : AD_EMPTY_VALUE; ?>><?php echo AD_FLOAT_RIGHT; ?></option>
        </select>
     </div>
   </div>
   <br />
   <div style="padding:0px 8px 16px 16px;">
      General Tag:  <input type="text" name="<?php echo $obj->option_ad_general_tag; ?>" value="<?php echo $obj->get_ad_general_tag() ?>" size="30" maxlength="40"/> (Used for {xxx} tags when no appropriate data is found.)
   </div>
  <div style="padding:0px 8px 16px 16px;">
		Display ads for post published after :  <input type="text" name="<?php echo $obj->option_ad_after_day; ?>" value="<?php echo $obj->get_ad_after_day() ?>" size="2" maxlength="3"/> day(s). (0 = immediately)
	</div>
	<div style="padding:0px 8px 16px 16px;">
      Do not display ads to users from website :  <input type="text" name="<?php echo $obj->option_ad_block_user; ?>" value="<?php echo $obj->get_ad_block_user() ?>" size="30" maxlength="200"/> (e.g. technorati.com, facebook.com)
	</div>
	<div style="padding:0px 8px 16px 16px;">
      Do not display ads to this category :  <input type="text" name="<?php echo $obj->option_ad_block_cat; ?>" value="<?php echo $obj->get_ad_block_cat() ?>" size="30" maxlength="200"/> (e.g. sport, news, science)
	</div>

</div>

<?php
	}
?>

<div style="padding:16px 16px 16px 540px">
      <input onclick="if (confirm('Are you sure you want to reset all settings?'))return true;return false" name="<?php echo AD_FORM_CLEAR; ?>" value="Reset" type="submit" style="width:100px;"/>
		<input name="<?php echo AD_FORM_SAVE; ?>" value="Save" type="submit" style="width:100px;" />
</div>

</form>

<div style="padding:8px 8px 32px 8px; margin-bottom:16px; width:770px; background-color: white; border: 1px solid rgb(221, 221, 221);">

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
?>
