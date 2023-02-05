<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Creaquiz module for xoops
 *
 * @copyright     2020 XOOPS Project (https://xooops.org)
 * @license        GPL 2.0 or later
 * @package        creaquiz
 * @since          1.0
 * @min_xoops      2.5.9
 * @author         Jean-Jacques Delalandre - Email:<jjdelalandre@orange.fr> - Website:<http://xmodules.jubile.fr>
 */
 
$creaquizHelper = \XoopsModules\Creaquiz\Helper::getInstance();
if(isset($creaquizHelper)){
    define ("CREAQUIZ_SHOW_TPL_NAME", $creaquizHelper->getConfig('displayTemplateName') );
}else{
    define ("CREAQUIZ_SHOW_TPL_NAME", 0);
}
 
if (!defined('XOOPS_ICONS32_PATH')) {
	define('XOOPS_ICONS32_PATH', XOOPS_ROOT_PATH . '/Frameworks/moduleclasses/icons/32');
}
if (!defined('XOOPS_ICONS32_URL')) {
	define('XOOPS_ICONS32_URL', XOOPS_URL . '/Frameworks/moduleclasses/icons/32');
}
define('CREAQUIZ_DIRNAME', 'creaquiz');
define('CREAQUIZ_PATH', XOOPS_ROOT_PATH.'/modules/'.CREAQUIZ_DIRNAME);
define('CREAQUIZ_URL', XOOPS_URL.'/modules/'.CREAQUIZ_DIRNAME);
define('CREAQUIZ_ICONS_PATH', CREAQUIZ_PATH.'/assets/icons');
define('CREAQUIZ_ICONS_URL', CREAQUIZ_URL.'/assets/icons');
define('CREAQUIZ_IMAGE_PATH', CREAQUIZ_PATH.'/assets/images');
define('CREAQUIZ_IMAGE_URL', CREAQUIZ_URL.'/assets/images');
define('CREAQUIZ_UPLOAD_PATH', XOOPS_UPLOAD_PATH.'/'.CREAQUIZ_DIRNAME);
define('CREAQUIZ_UPLOAD_URL', XOOPS_UPLOAD_URL.'/'.CREAQUIZ_DIRNAME);
define('CREAQUIZ_UPLOAD_EXPORT_PATH', CREAQUIZ_UPLOAD_PATH.'/export');
define('CREAQUIZ_UPLOAD_EXPORT_URL', CREAQUIZ_UPLOAD_URL.'/export');
define('CREAQUIZ_UPLOAD_IMPORT_PATH', CREAQUIZ_UPLOAD_PATH.'/import');
define('CREAQUIZ_UPLOAD_IMPORT_URL', CREAQUIZ_UPLOAD_URL.'/import');
define('CREAQUIZ_UPLOAD_IMAGE_PATH', CREAQUIZ_UPLOAD_PATH.'/images');
define('CREAQUIZ_UPLOAD_IMAGE_URL', CREAQUIZ_UPLOAD_URL.'/images');
define('CREAQUIZ_UPLOAD_SHOTS_PATH', CREAQUIZ_UPLOAD_PATH.'/images/shots');
define('CREAQUIZ_UPLOAD_SHOTS_URL', CREAQUIZ_UPLOAD_URL.'/images/shots');
define('CREAQUIZ_ADMIN', CREAQUIZ_URL . '/admin/index.php');


define('CREAQUIZ_QUIZ_JS', '/assets/js');
define('CREAQUIZ_QUIZ_ORG', '/quiz-org');
define('CREAQUIZ_QUIZ_MIN', '/quiz-min');

$useJsMinified = $creaquizHelper->getConfig('use_js_minified');
define('CREAQUIZ_QUIZ_JS_TO_RUN', CREAQUIZ_QUIZ_JS . (($useJsMinified) ? CREAQUIZ_QUIZ_MIN : CREAQUIZ_QUIZ_ORG)) ;

/*
$useJsMinified = $creaquizHelper->getConfig('use_js_minified');
define('CREAQUIZ_QUIZ_JS_TO_RUN', '/assets/js/' . (($useJsMinified) ? 'quiz-min' : 'quiz-org')) ;
*/
define('CREAQUIZ_QUIZ_JS_PATH', CREAQUIZ_PATH . CREAQUIZ_QUIZ_JS_TO_RUN);
define('CREAQUIZ_QUIZ_JS_URL',  CREAQUIZ_URL  .  CREAQUIZ_QUIZ_JS_TO_RUN);

define('CREAQUIZ_QUIZ_JS_ORG', CREAQUIZ_PATH . CREAQUIZ_QUIZ_JS . CREAQUIZ_QUIZ_ORG);
define('CREAQUIZ_QUIZ_JS_MIN', CREAQUIZ_PATH . CREAQUIZ_QUIZ_JS . CREAQUIZ_QUIZ_MIN);



define('CREAQUIZ_UPLOAD_QUIZ_JS', '/quiz-js');
define('CREAQUIZ_UPLOAD_QUIZ_PATH', CREAQUIZ_UPLOAD_PATH . CREAQUIZ_UPLOAD_QUIZ_JS);
define('CREAQUIZ_UPLOAD_QUIZ_URL',  CREAQUIZ_UPLOAD_URL  . CREAQUIZ_UPLOAD_QUIZ_JS);

define('CREAQUIZ_ANSWERS_CLASS', CREAQUIZ_PATH . "/class/type_question");
define('CREAQUIZ_MODELES_IMG', CREAQUIZ_URL . "/assets/images/modeles");
define('CREAQUIZ_LANGUAGE', CREAQUIZ_PATH . "/language");
define('CREAQUIZ_MODELES_IMG_PATH', CREAQUIZ_PATH . "/assets/images/modeles");


define('CREAQUIZ_SELECT_ONCHANGE', 'onchange="document.creaquiz_select_filter.sender.value=this.name;document.creaquiz_select_filter.submit();"');
define('CREAQUIZ_ADD_ID', true);

define('CREAQUIZ_POINTS_POSITIF', 'blue');
define('CREAQUIZ_POINTS_NULL', 'black');
define('CREAQUIZ_POINTS_NEGATIF', 'red');

define('CREAQUIZ_FORMAT_DATE_SQL', 'Y-m-d h:i:s');
define('CREAQUIZ_FORMAT_DATE', 'd-m-Y h:i:s');
//define('XOBJ_DTYPE_DATETIME', 99); //XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME

define('CREAQUIZ_TYPE_FORM_QUESTION',   0);
define('CREAQUIZ_TYPE_FORM_BEGIN',  1);
define('CREAQUIZ_TYPE_FORM_GROUP', 2);
define('CREAQUIZ_TYPE_FORM_END', 3);

define('CREAQUIZ_ALL', '__ALL__');

$localLogo = CREAQUIZ_IMAGE_URL . '/jean-jacquesdelalandre_logo.png';
// Module Information
$copyright = "<a href='http://xmodules.jubile.fr' title='Origami' target='_blank'><img src='".$localLogo."' alt='Origami' /></a>";
include_once XOOPS_ROOT_PATH . '/class/xoopsrequest.php';
include_once CREAQUIZ_PATH . '/include/functions.php';


