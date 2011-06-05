<?php

//define constant variable Ad Inserter
define('AD_INSERTER_TITLE','Ad Inserter');
define('AD_INSERTER_VERSION','1.0');

define('AD_DISABLED_1', '<!-- Ad Inserter Ad 1 Disabled -->');
define('AD_DISABLED_2', '<!-- Ad Inserter Ad 2 Disabled -->');
define('AD_DISABLED_3', '<!-- Ad Inserter Ad 3 Disabled -->');
define('AD_DISABLED_4', '<!-- Ad Inserter Ad 4 Disabled -->');
define('AD_DISABLED_5', '<!-- Ad Inserter Ad 5 Disabled -->');
define('AD_DISABLED_6', '<!-- Ad Inserter Ad 6 Disabled -->');
define('AD_DISABLED_7', '<!-- Ad Inserter Ad 7 Disabled -->');
define('AD_DISABLED_8', '<!-- Ad Inserter Ad 8 Disabled -->');

define('AD_EMPTY_DATA', '');
define('AD_ZERO_DATA', '0');
define('AD_GENERAL_TAG', 'gadgets');
define('AD_NAME', 'Ad Block');

//options
define('AD_AD1_OPTIONS','AdInserter1Options');
define('AD_AD2_OPTIONS','AdInserter2Options');
define('AD_AD3_OPTIONS','AdInserter3Options');
define('AD_AD4_OPTIONS','AdInserter4Options');
define('AD_AD5_OPTIONS','AdInserter5Options');
define('AD_AD6_OPTIONS','AdInserter6Options');
define('AD_AD7_OPTIONS','AdInserter7Options');
define('AD_AD8_OPTIONS','AdInserter8Options');

//misc
define('AD_EMPTY_VALUE','');
define('AD_FUNC_TYPE_RESET','reset');
define('AD_FUNC_TYPE_INITIAL','initial');

//define constant variable form
define('AD_FORM_SAVE','ad_save');
define('AD_FORM_CLEAR','ad_clear');

define('AD_AUTHOR_SITE', '<!-- Powered by Ad Inserter Wordpress Plugin, Author: Igor Funa -->');

//form select options
define('AD_SELECT_SELECTED','selected');
define('AD_SELECT_NONE','None');
define('AD_SELECT_BEFORE_PARAGRAPH','Before Paragraph');
define('AD_SELECT_BEFORE_CONTENT','Before Content');
define('AD_SELECT_AFTER_CONTENT','After Content');
define('AD_SELECT_WIDGET','Widget');

//Direction options
define('AD_DIRECTION_FROM_TOP','From Top');
define('AD_DIRECTION_FROM_BOTTOM','From Bottom');

//Float options
define('AD_FLOAT_NONE','None');
define('AD_FLOAT_LEFT','Left');
define('AD_FLOAT_RIGHT','Right');

// Check for location modifications in wp-config
if ( !defined('WP_CONTENT_URL') ) {
  define('AD_PLUGPATH',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} else {
	define('AD_PLUGPATH',WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}

?>