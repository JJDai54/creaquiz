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
include dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
$creaquizHelper = \XoopsModules\Creaquiz\Helper::getInstance();
include_once dirname(__DIR__) . '/include/common.php';
//global $smarty;
//$smarty->register_compiler_function("quizFlagAscii", "../smarty/function.quizFlagAscii.php", false);
//$xoopsTpl->register_compiler_function("quizFlagAscii", "../smarty/function.quizFlagAscii.php", false);
xoops_load('XoopsFormLoader');

$sysPathIcon16   = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons16');
$sysPathIcon32   = '../' . $GLOBALS['xoopsModule']->getInfo('sysicons32');
$pathModuleAdmin = $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin');
$modPathIcon16   = CREAQUIZ_URL . '/' . $GLOBALS['xoopsModule']->getInfo('modicons16') . '/';
$modPathIcon32   = CREAQUIZ_URL . '/' . $GLOBALS['xoopsModule']->getInfo('modicons32') . '/';

// Get instance of module
// $mid = $GLOBALS['xoopsModule']->getVar('mid');
// echo "<hr>===>mid = {$mid}<hr>";
$quizHandler = $creaquizHelper->getHandler('Quiz');
$questionsHandler = $creaquizHelper->getHandler('Questions');
$categoriesHandler = $creaquizHelper->getHandler('Categories');
$type_questionHandler = $creaquizHelper->getHandler('Type_question');
$answersHandler = $creaquizHelper->getHandler('Answers');
$resultsHandler = $creaquizHelper->getHandler('Results');
$messagesHandler = $creaquizHelper->getHandler('Messages');
$myts = MyTextSanitizer::getInstance();
// 
if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
	include_once XOOPS_ROOT_PATH . '/class/template.php';
	$xoopsTpl = new \XoopsTpl();
}

// Load languages
xoops_loadLanguage('admin');
xoops_loadLanguage('modinfo');

// Local admin menu class
if (file_exists($GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php'))) {
	include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
} else {
	redirect_header('../../../admin.php', 5, _AM_MODULEADMIN_MISSING);
}

xoops_cp_header();

// System icons path
$GLOBALS['xoopsTpl']->assign('sysPathIcon16', $sysPathIcon16);
$GLOBALS['xoopsTpl']->assign('sysPathIcon32', $sysPathIcon32);
$GLOBALS['xoopsTpl']->assign('modPathIcon16', $modPathIcon16);
$GLOBALS['xoopsTpl']->assign('modPathIcon32', $modPathIcon32);

$adminObject = \Xmf\Module\Admin::getInstance();
$style = CREAQUIZ_URL . '/assets/css/admin/style.css';

xoops_load('XoopsLists', 'core');
$utility = new \XoopsModules\Creaquiz\Utility();
$quizUtility = new \XoopsModules\Creaquiz\QuizUtility();

$xoTheme->addScript(XOOPS_URL . '/Frameworks/trierTableauHTML/trierTableau.js');
include_once (XOOPS_ROOT_PATH . "/Frameworks/JJD-Framework/load.php");
include_once(JJD_PATH_XFORMS . '/formtabletray.php');        
   
// include_once dirname(__DIR__) . '/class/xoopsform/formnumber.php';
// include_once dirname(__DIR__) . '/class/xoopsform/formimg.php';

$moduleDirName      = $GLOBALS['xoopsModule']->getVar('dirname');
xoops_loadLanguage('slide', $moduleDirName);
xoops_loadLanguage('common', $moduleDirName);
xoops_loadLanguage('quiz_JS', $moduleDirName);
xoops_loadLanguage('type_questions', $moduleDirName);

/*
*/
require_once("../include/quizFlagAscii.php");
/* remplacer pa les fonction getflags dans les classes des tables
$xoopsTpl->register_function("quizFlagAscii", "smarty_function_quizFlagAscii", false);
$xoopsTpl->register_function("quizFlagAlpha", "smarty_function_quizFlagAlpha", false);
*/

//$xoopsTpl->register_compiler_function("quizFlagAscii2", "quizFlagAscii2", false);