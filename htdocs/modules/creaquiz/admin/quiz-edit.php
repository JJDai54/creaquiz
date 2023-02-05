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


use Xmf\Request;
use XoopsModules\Creaquiz;
use XoopsModules\Creaquiz\Constants;
use XoopsModules\Creaquiz\Utility;
//use JJD;

		$templateMain = 'creaquiz_admin_quiz.tpl';
		$GLOBALS['xoopsTpl']->assign('navigation', $adminObject->displayNavigation('quiz.php'));
		$adminObject->addItemButton(_AM_CREAQUIZ_ADD_QUIZ, 'quiz.php?op=new', 'add');
		$adminObject->addItemButton(_AM_CREAQUIZ_QUIZ_LIST, 'quiz.php', 'list');
		$GLOBALS['xoopsTpl']->assign('buttons', $adminObject->displayButton('left'));
		// Get Form
		$quizObj = $quizHandler->get($quizId);
		$form = $quizObj->getFormQuiz();
		$GLOBALS['xoopsTpl']->assign('form', $form->render());
