<?php

namespace XoopsModules\Creaquiz;

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

use XoopsModules\Creaquiz;
use JJD;

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Quiz
 */
class Quiz extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('quiz_flag', XOBJ_DTYPE_INT);
		$this->initVar('quiz_cat_id', XOBJ_DTYPE_INT);
		$this->initVar('quiz_name', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_author', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_folderJS', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_description', XOBJ_DTYPE_OTHER);
		$this->initVar('quiz_weight', XOBJ_DTYPE_INT);
		$this->initVar('quiz_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_dateBegin', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_dateEnd', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quiz_publishQuiz', XOBJ_DTYPE_INT);
		$this->initVar('quiz_publishResults', XOBJ_DTYPE_INT);
		$this->initVar('quiz_publishAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quiz_ShowAllSolutions', XOBJ_DTYPE_INT);
		$this->initVar('quiz_onClickSimple', XOBJ_DTYPE_INT);
		$this->initVar('quiz_theme', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quiz_answerBeforeNext', XOBJ_DTYPE_INT);
		$this->initVar('quiz_allowedPrevious', XOBJ_DTYPE_INT);
		$this->initVar('quiz_shuffleQuestions', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showGoodAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showBadAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showReloadAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showGoToSlide', XOBJ_DTYPE_INT);
		$this->initVar('quiz_minusOnShowGoodAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quiz_allowedSubmit', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showScoreMinMax', XOBJ_DTYPE_INT);
		$this->initVar('quiz_useTimer', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showResultAllways', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showReponsesBottom', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showResultPopup', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showTypeQuestion', XOBJ_DTYPE_INT);
		$this->initVar('quiz_showLog', XOBJ_DTYPE_INT);
		$this->initVar('quiz_legend', XOBJ_DTYPE_OTHER);
		$this->initVar('quiz_dateBeginOk', XOBJ_DTYPE_INT);
		$this->initVar('quiz_dateEndOk', XOBJ_DTYPE_INT);
		$this->initVar('quiz_build', XOBJ_DTYPE_INT);
		$this->initVar('quiz_actif', XOBJ_DTYPE_INT);
	}

	/**
	 * @static function &getInstance
	 *
	 * @param null
	 */
	public static function getInstance()
	{
		static $instance = false;
		if (!$instance) {
			$instance = new self();
		}
	}

	/**
	 * The new inserted $Id
	 * @return inserted id
	 */
	public function getNewInsertedIdQuiz()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getFormQuiz($action = false)
	{global $utility, $categoriesHandler, $quizUtility;
		$creaquizHelper = \XoopsModules\Creaquiz\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}
		$isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
        $quiId = $this->getVar('quiz_id');
        
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		// Title
		$title = $this->isNew() ? sprintf(_AM_CREAQUIZ_QUIZ_ADD) : sprintf(_AM_CREAQUIZ_QUIZ_EDIT);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title . " (#{$quiId})", 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Quiz Handler
		$quizHandler = $creaquizHelper->getHandler('Quiz');
        $form->addElement(new \XoopsFormHidden('quiz_id', $quiId));
		
        // Form Select quizCat_id
		$quizCat_idSelect = new \XoopsFormSelect( _AM_CREAQUIZ_CATEGORY, 'quiz_cat_id', $this->getVar('quiz_cat_id'));
		$quizCat_idSelect->addOptionArray($categoriesHandler->getListKeyName());
		$form->addElement($quizCat_idSelect, true);
        
		
        // Form Text quizName
		$form->addElement(new \XoopsFormText( _AM_CREAQUIZ_QUIZ_NAME, 'quiz_name', 50, 255, $this->getVar('quiz_name') ), true);
        
        // Form Text quiz_author
		$form->addElement(new \XoopsFormText( _AM_CREAQUIZ_QUIZ_AUTHOR, 'quiz_author', 50, 255, $this->getVar('quiz_author') ), false);

        //----------------------------------------------------------
        $fileNameTray = new \XoopsFormElementTray(_AM_CREAQUIZ_FILE_NAME_JS, ' ');        
		// Form Text quiz_folderJS
        $inpFileName = new \XoopsFormText('' , 'quiz_folderJS', 50, 255, $this->getVar('quiz_folderJS'));
        $inpFileName->setDescription(_AM_CREAQUIZ_FILE_NAME_JS_DESC);
		$fileNameTray->addElement($inpFileName, true);
        
		// Form number quiz_build
		$build = $this->isNew() ? 0 : $this->getVar('quiz_build');
        $inpBuild = new \XoopsFormNumber(_AM_CREAQUIZ_QUIZ_BUILD, 'quiz_build', 5, 5, $build);
        $inpBuild->setMinMax(0, 500);
		$fileNameTray->addElement($inpBuild);
        
		$form->addElement($fileNameTray);
        
        // Form Text quiz_weight
		$form->addElement(new \XoopsFormText( _AM_CREAQUIZ_WEIGHT, 'quiz_weight', 50, 255, $this->getVar('quiz_weight') ), false);
        //----------------------------------------------------------
        
		// Form Editor DhtmlTextArea quizDescription
        /* champ a supprimer fait double emploi avec les champs du premier slide "page_info/intro"
        $editDescription = $quizUtility->getEditor2(_AM_CREAQUIZ_DESCRIPTION, 'quiz_description', $this->getVar('quiz_description', 'e'),  _AM_CREAQUIZ_DESCRIPTION_DESC, null, $creaquizHelper);
		$form->addElement($editDescription, true);
        */
            
            
        

		// Form Check Box quizDateBegin
        $quizDateBegin = \JJD\xoopsformDateOkTray(_AM_CREAQUIZ_DATEBEGIN, 'quiz_dateBeginOk', $this->getVar('quiz_dateBeginOk'), 'quiz_dateBegin', $this->getVar('quiz_dateBegin'));
		$form->addElement($quizDateBegin);
        
		// Form Check Box quizDateEnd
        $quizDateEnd = \JJD\xoopsformDateOkTray(_AM_CREAQUIZ_DATEEND, 'quiz_dateEndOk', $this->getVar('quiz_dateEndOk'), 'quiz_dateEnd', $this->getVar('quiz_dateEnd'));
		$form->addElement($quizDateEnd);
        
		// Form Check Box quiz_publishQuiz
		$quizExecution = $this->isNew() ? 0 : $this->getVar('quiz_publishQuiz');
		$inpExecution = new \XoopsFormRadio( _CO_CREAQUIZ_PUBLISH_QUIZ, 'quiz_publishQuiz', $quizExecution);
        $inpExecution->setDescription(_AM_CREAQUIZ_PUBLISH_QUIZ_DESC);
		$inpExecution->addOption(0, _CO_CREAQUIZ_PUBLISH_NONE);
		$inpExecution->addOption(1, _CO_CREAQUIZ_PUBLISH_INLINE);
		$inpExecution->addOption(2, _CO_CREAQUIZ_PUBLISH_OUTLINE);
		$form->addElement($inpExecution);
        
		// Form Check Box quiz_actif
		$quizActif = $this->isNew() ? 1 : $this->getVar('quiz_actif');
		$inpActif = new \XoopsFormRadioYN( _AM_CREAQUIZ_ACTIF, 'quiz_actif', $quizActif);
		$form->addElement($inpActif);

        $publishArr = array(1=>_YES, 0=>_NO, 2=>_AM_CREAQUIZ_AUTO);
        $inpPublishResults = new \XoopsFormRadio(_AM_CREAQUIZ_PUBLISH_RESULTS , 'quiz_publishResults', $this->getVar('quiz_publishResults'));
        $inpPublishResults->addOptionArray($publishArr);
        $inpPublishResults->setDescription(_AM_CREAQUIZ_PUBLISH_AUTO_DESC);
		$form->addElement($inpPublishResults);
        
        $inpPublishAnswers = new \XoopsFormRadio(_AM_CREAQUIZ_PUBLISH_ANSWERS , 'quiz_publishAnswers', $this->getVar('quiz_publishAnswers'));
        $inpPublishAnswers->addOptionArray($publishArr);
        $inpPublishAnswers->setDescription(_AM_CREAQUIZ_PUBLISH_AUTO_DESC);
		$form->addElement($inpPublishAnswers);

        $inpShowAllSolutions = new \XoopsFormRadioYN(_AM_CREAQUIZ_VIEW_ALL_SOLUTIONS , 'quiz_ShowAllSolutions', $this->getVar('quiz_ShowAllSolutions'));
        //$inpShowAllSolutions->addOptionArray($publishArr);
        $inpShowAllSolutions->setDescription(_AM_CREAQUIZ_SHOW_ALL_SOLUTIONS_DESC);
		$form->addElement($inpShowAllSolutions);
        
        /* JJDai - Pas vraiment utile, mais je garde des fois que ?a puisse servir a autre chose
        oui : ce bouton est activer sur le dernier slide
        non :  ce bouton esst d?sactiver sur le dernier slide (utilisation en dehors du site a verifier)
        */
/* Inutilise et a virer - le bouton est d?fini sur pageEnd quelque soit le choix
		// Form Check Box quizAllowedSubmit
		$quizAllowedSubmit = $this->isNew() ? 0 : $this->getVar('quiz_allowedSubmit');
		$checkQuizAllowedSubmit = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_ALLOWEDSUBMIT, 'quiz_allowedSubmit', $quizAllowedSubmit);
		$checkQuizAllowedSubmit->setDescription(_AM_CREAQUIZ_QUIZ_ALLOWEDSUBMIT_DESC);
		$form->addElement($checkQuizAllowedSubmit );
*/        
        
		// Form Check Box quizAllowedSubmit
		$quizShowScoreMinMax = $this->isNew() ? 0 : $this->getVar('quiz_showScoreMinMax');
		$inpShowScoreMinMax = new \XoopsFormRadioYN(_AM_CREAQUIZ_QUIZ_SHOW_SCORE_MIN_MAX, 'quiz_showScoreMinMax', $quizShowScoreMinMax);
		$inpShowScoreMinMax->setDescription(_AM_CREAQUIZ_QUIZ_SHOW_SCORE_MIN_MAX_DESC);
		$form->addElement($inpShowScoreMinMax);
        //========================================================
        $form->insertBreak('<center><div style="background:black;color:white;">' . _AM_CREAQUIZ_OPTIONS_FOR_QUIZ . '</div></center>');
        //========================================================
     
        $inpTheme = new \XoopsFormSelect(_AM_CREAQUIZ_THEME, 'quiz_theme', $this->getVar('quiz_theme'));
		$inpTheme->setDescription(_AM_CREAQUIZ_THEME_DESC);
        $inpTheme->addOptionArray($quizUtility::get_css_color(true));
		$form->addElement($inpTheme, false);

/*
        // Form Editor DhtmlTextArea quizLegend
        $editLegend = \JJD\getformTextarea(_AM_CREAQUIZ_LEGEND, 'quiz_legend', $this->getVar('quiz_legend', 'e'), _AM_CREAQUIZ_LEGEND_DESC);
		$form->addElement($editLegend, false);
*/		
        
		// Form Check Box quizOnClick
		$quizOnClick = $this->isNew() ? 0 : $this->getVar('quiz_onClickSimple');
		$checkQuizOnClick = new \XoopsFormRadio( _AM_CREAQUIZ_QUIZ_ONCLICK, 'quiz_onClickSimple', $quizOnClick);
		$checkQuizOnClick->addOption(0, _AM_CREAQUIZ_CLICK_DOUBLE);
		$checkQuizOnClick->addOption(1, _AM_CREAQUIZ_CLICK_SIMPLE);
		$form->addElement($checkQuizOnClick );
        
		// Form Check Box quizAnswerBeforeNext
		$quizAnswerBeforeNext = $this->isNew() ? 0 : $this->getVar('quiz_answerBeforeNext');
		$checkQuizAnswerBeforeNext = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_ANSWERBEFORENEXT, 'quiz_answerBeforeNext', $quizAnswerBeforeNext);
		$checkQuizAnswerBeforeNext->setDescription(_AM_CREAQUIZ_QUIZ_ANSWERBEFORENEXT_DESC);
		$form->addElement($checkQuizAnswerBeforeNext );
        
		// Form Check Box quizAllowedPrevious
		$quizAllowedPrevious = $this->isNew() ? 0 : $this->getVar('quiz_allowedPrevious');
		$checkQuizAllowedPrevious = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_ALLOWEDPREVIOUS, 'quiz_allowedPrevious', $quizAllowedPrevious);
		$checkQuizAllowedPrevious->setDescription(_AM_CREAQUIZ_QUIZ_ALLOWEDPREVIOUS_DESC);
		$form->addElement($checkQuizAllowedPrevious );
        
		// Form Check Box quizTimer
		$quizTimer = $this->isNew() ? 0 : $this->getVar('quiz_useTimer');
		$checkQuizTimer = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_USE_TIMER, 'quiz_useTimer', $quizTimer);
		$checkQuizTimer->setDescription(_AM_CREAQUIZ_QUIZ_USE_TIMER_DESC);
		$form->addElement($checkQuizTimer );
        
		// Form Check Box shuffleQuestions
		$quizShuffleQuestions = $this->isNew() ? 0 : $this->getVar('quiz_shuffleQuestions');
		$inpShuffleQuestions = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHUFFLE_QUESTION, 'quiz_shuffleQuestions', $quizShuffleQuestions);
		$inpShuffleQuestions->setDescription(_AM_CREAQUIZ_QUIZ_SHUFFLE_QUESTION_DESC);
		$form->addElement($inpShuffleQuestions);
        
		// Form Check Box showResultPopup
		$ShowResultPopup = $this->isNew() ? 0 : $this->getVar('quiz_showResultPopup');
		$inpShowResultPopup = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_RESULT_POPUP, 'quiz_showResultPopup', $ShowResultPopup);
		$inpShowResultPopup->setDescription(_AM_CREAQUIZ_QUIZ_RESULT_POPUP_DESC);
		$form->addElement($inpShowResultPopup);
        
        /* JJDai : A virer fonctionalit? pas tr?s utiles a voir dans temps
        pour l'instant juste d?sactiv? dans le formulaire
		// Form Check Box minusOnShowGoodAnswers
		$minusOnShowGoodAnswers = $this->isNew() ? 0 : $this->getVar('quiz_minusOnShowGoodAnswers');
        $inpMinusOnShowGoodAnswers = new \XoopsFormNumber(_AM_CREAQUIZ_QUIZ_MINUS_OSGA, 'quiz_minusOnShowGoodAnswers', 5, 5, $minusOnShowGoodAnswers);
        $inpMinusOnShowGoodAnswers->setDescription(_AM_CREAQUIZ_QUIZ_MINUS_OSGA_DESC);
        $inpMinusOnShowGoodAnswers->setMinMax(0, 50);
		$form->addElement($inpMinusOnShowGoodAnswers);
        */
        
        
        //========================================================
        $form->insertBreak('<center><div style="background:black;color:white;">' . _AM_CREAQUIZ_OPTIONS_FOR_DEV . '</div></center>');
        //========================================================
		// Form Check Box quiz_showTypeQuestion
		$quizShowTypeQuestion = $this->isNew() ? 0 : $this->getVar('quiz_showTypeQuestion');
		$checkQuizShowTypeQuestion = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOW_TYPE_QUESTION, 'quiz_showTypeQuestion', $quizShowTypeQuestion);
		$checkQuizShowTypeQuestion->setDescription(_AM_CREAQUIZ_QUIZ_SHOW_TYPE_QUESTION_DESC);
		$form->addElement($checkQuizShowTypeQuestion);

		// Form radio quiz_showReloadAnswers
		$showReloadAnswers = $this->isNew() ? 0 : $this->getVar('quiz_showReloadAnswers');
		$inpReloadGoodAnswers = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOW_BTN_RELOAD_ANSWERS, 'quiz_showReloadAnswers', $showReloadAnswers);
		$inpReloadGoodAnswers->setDescription(_AM_CREAQUIZ_QUIZ_SHOW_BTN_RELOAD_ANSWERS_DESC);
		$form->addElement($inpReloadGoodAnswers);
        
		// Form radio quiz_showGoToSlide
		$showGoToSlide = $this->isNew() ? 0 : $this->getVar('quiz_showGoToSlide');
		$inpGoToSlide = new \XoopsFormRadioYN( _AM_CREAQUIZ_SHOW_BTN_GOTO_SLIDE, 'quiz_showGoToSlide', $showGoToSlide);
		$inpGoToSlide->setDescription(_AM_CREAQUIZ_SHOW_BTN_GOTO_SLIDE_DESC);
		$form->addElement($inpGoToSlide);
        
		// Form Check Box quizShowGoodAnswers
		$showGoodAnswers = $this->isNew() ? 0 : $this->getVar('quiz_showGoodAnswers');
		$inpShowGoodAnswers = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOW_GOOD_ANSWERS, 'quiz_showGoodAnswers', $showGoodAnswers);
		$inpShowGoodAnswers->setDescription(_AM_CREAQUIZ_QUIZ_SHOW_GOOD_ANSWERS_DESC);
		$form->addElement($inpShowGoodAnswers);
        
		// Form Check Box quizShowBadAnswers
		$showBadAnswers = $this->isNew() ? 0 : $this->getVar('quiz_showBadAnswers');
		$inpShowBadAnswers = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOW_BAD_ANSWERS, 'quiz_showBadAnswers', $showGoodAnswers);
		$inpShowBadAnswers->setDescription(_AM_CREAQUIZ_QUIZ_SHOW_BAD_ANSWERS_DESC);
		$form->addElement($inpShowBadAnswers);
        
		// Form Check Box quizShowLog
		$quizShowLog = $this->isNew() ? 0 : $this->getVar('quiz_showLog');
		$checkQuizShowLog = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOWLOG, 'quiz_showLog', $quizShowLog);
		$checkQuizShowLog->setDescription(_AM_CREAQUIZ_QUIZ_SHOWLOG_DESC);
		$form->addElement($checkQuizShowLog);
        
		// Form Check Box quizShowResultAllways
		$quizShowResultAllways = $this->isNew() ? 0 : $this->getVar('quiz_showResultAllways');
		$checkQuizShowResultAllways = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOWRESULTALLWAYS, 'quiz_showResultAllways', $quizShowResultAllways);
		$checkQuizShowResultAllways->setDescription(_AM_CREAQUIZ_QUIZ_SHOWRESULTALLWAYS_DESC);
		$form->addElement($checkQuizShowResultAllways );
        
        
		// Form Check Box ShowReponses
		$quizShowReponses = $this->isNew() ? 0 : $this->getVar('quiz_showReponsesBottom');
		$checkQuizShowReponses = new \XoopsFormRadioYN( _AM_CREAQUIZ_QUIZ_SHOWREPONSES_BOTTOM, 'quiz_showReponsesBottom', $quizShowReponses);
		$checkQuizShowReponses->setDescription(_AM_CREAQUIZ_QUIZ_SHOWREPONSES_BOTTOM_DESC);
		$form->addElement($checkQuizShowReponses );
        
        //========================================================
        $form->insertBreak('<center><div style="background:black;color:white;">' . _AM_CREAQUIZ_PERMISSIONS . '</div></center>');
        //========================================================
        
		// Permissions
		$memberHandler = xoops_getHandler('member');
		$groupList = $memberHandler->getGroupList();
		$grouppermHandler = xoops_getHandler('groupperm');
		$fullList[] = array_keys($groupList);
		if (!$this->isNew()) {
			$groupsIdsApprove = $grouppermHandler->getGroupIds('creaquiz_approve_quiz', $this->getVar('quiz_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsApprove[] = array_values($groupsIdsApprove);
			$groupsCanApproveCheckbox = new \XoopsFormCheckBox( _AM_CREAQUIZ_PERMISSIONS_APPROVE, 'groups_approve_quiz[]', $groupsIdsApprove);
			$groupsIdsSubmit = $grouppermHandler->getGroupIds('creaquiz_submit_quiz', $this->getVar('quiz_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsSubmit[] = array_values($groupsIdsSubmit);
			$groupsCanSubmitCheckbox = new \XoopsFormCheckBox( _AM_CREAQUIZ_PERMISSIONS_SUBMIT, 'groups_submit_quiz[]', $groupsIdsSubmit);
			$groupsIdsView = $grouppermHandler->getGroupIds('creaquiz_view_quiz', $this->getVar('quiz_id'), $GLOBALS['xoopsModule']->getVar('mid'));
			$groupsIdsView[] = array_values($groupsIdsView);
			$groupsCanViewCheckbox = new \XoopsFormCheckBox( _AM_CREAQUIZ_PERMISSIONS_VIEW, 'groups_view_quiz[]', $groupsIdsView);
		} else {
			$groupsCanApproveCheckbox = new \XoopsFormCheckBox( _AM_CREAQUIZ_PERMISSIONS_APPROVE, 'groups_approve_quiz[]', $fullList);
			$groupsCanSubmitCheckbox = new \XoopsFormCheckBox( _AM_CREAQUIZ_PERMISSIONS_SUBMIT, 'groups_submit_quiz[]', $fullList);
			$groupsCanViewCheckbox = new \XoopsFormCheckBox( _AM_CREAQUIZ_PERMISSIONS_VIEW, 'groups_view_quiz[]', $fullList);
		}
		// To Approve
		$groupsCanApproveCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanApproveCheckbox);
		// To Submit
		$groupsCanSubmitCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanSubmitCheckbox);
		// To View
		$groupsCanViewCheckbox->addOptionArray($groupList);
		$form->addElement($groupsCanViewCheckbox);
		// To Save
		$form->addElement(new \XoopsFormHidden('op', 'save'));
		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
		return $form;
	}

	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesQuiz($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility, $categoriesHandler;
		$creaquizHelper  = \XoopsModules\Creaquiz\Helper::getInstance();
		$utility = new \XoopsModules\Creaquiz\Utility();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']                = $this->getVar('quiz_id');
		$ret['cat_id']            = $this->getVar('quiz_cat_id');
		$ret['name']              = $this->getVar('quiz_name');
		$ret['author']            = $this->getVar('quiz_author');
		$ret['folderJS']          = $this->getVar('quiz_folderJS');
		$ret['description']       = $this->getVar('quiz_description', 'e');
		$ret['weight']            = $this->getVar('quiz_weight');
		$editorMaxchar = $creaquizHelper->getConfig('editor_maxchar');
		$ret['description_short'] = $utility::truncateHtml($ret['description'], $editorMaxchar);
		$ret['creation']          = \JJD\getDateSql2Str($this->getVar('quiz_creation'));
		$ret['update']            = \JJD\getDateSql2Str($this->getVar('quiz_update'));

// 		$ret['dateBegin']         = formatTimeStamp($this->getVar('quiz_dateBegin'), 'm');
// 		$ret['dateEnd']           = formatTimeStamp($this->getVar('quiz_dateEnd'), 'm');
        
		$ret['dateBegin']          = \JJD\getDateSql2Str($this->getVar('quiz_dateBegin'));
		$ret['dateEnd']            = \JJD\getDateSql2Str($this->getVar('quiz_dateEnd'));
		$ret['periodeOK']          = \JJD\isDateBetween($this->getVar('quiz_dateBegin'), $this->getVar('quiz_dateEnd'), $this->getVar('quiz_dateBeginOk'), $this->getVar('quiz_dateEndOk'));
         
		$ret['publishQuiz']         = $this->getVar('quiz_publishQuiz');
		$ret['publishQuiz_lib']     = Array(_CO_CREAQUIZ_PUBLISH_NONE,_CO_CREAQUIZ_PUBLISH_INLINE,_CO_CREAQUIZ_PUBLISH_OUTLINE)[$ret['publishQuiz']];
		
        
        $ret['publishResults']      = $this->getVar('quiz_publishResults');
        $ret['publishResultsOk']    = (($ret['periodeOK']==0 && $ret['publishQuiz']>0 && $ret['publishResults']==2) || $ret['publishResults']==1) ? 1 : 0;

		$ret['publishAnswers']      = $this->getVar('quiz_publishAnswers');
        $ret['publishAnswersOk']    = (($ret['periodeOK']==0 && $ret['publishQuiz']>0 && $ret['publishAnswers']==2) || $ret['publishAnswers']==1) ? 1 : 0;

		$ret['ShowAllSolutions']      = $this->getVar('quiz_ShowAllSolutions');
        
		$ret['onClickSimple']     = $this->getVar('quiz_onClickSimple');
		$ret['theme']             = $this->getVar('quiz_theme');
        $ret['theme_ok'] = ($ret['theme'] == '') ? $categoriesHandler->getValue($ret['cat_id'],'cat_theme','default') : $ret['theme'];
		$ret['answerBeforeNext']  = $this->getVar('quiz_answerBeforeNext');
		$ret['allowedPrevious']   = $this->getVar('quiz_allowedPrevious');
		$ret['allowedSubmit']     = $this->getVar('quiz_allowedSubmit');
		$ret['showScoreMinMax']   = $this->getVar('quiz_showScoreMinMax');
		$ret['shuffleQuestions']  = $this->getVar('quiz_shuffleQuestions');
		$ret['showGoodAnswers']   = $this->getVar('quiz_showGoodAnswers');
		$ret['showBadAnswers']    = $this->getVar('quiz_showBadAnswers');
		$ret['showReloadAnswers'] = $this->getVar('quiz_showReloadAnswers');
		$ret['showGoToSlide']     = $this->getVar('quiz_showGoToSlide');
		$ret['minusOnShowGoodAnswers'] = $this->getVar('quiz_minusOnShowGoodAnswers');
		$ret['useTimer']          = $this->getVar('quiz_useTimer');
		$ret['showResultAllways'] = $this->getVar('quiz_showResultAllways');
		$ret['showReponsesBottom']= $this->getVar('quiz_showReponsesBottom');
		$ret['showResultPopup']   = $this->getVar('quiz_showResultPopup');
		$ret['showTypeQuestion']  = $this->getVar('quiz_showTypeQuestion');
		$ret['showLog']           = $this->getVar('quiz_showLog');
		$ret['legend']            = $this->getVar('quiz_legend', 'e');
		$ret['legend_short']      = $utility::truncateHtml($ret['legend'], $editorMaxchar);
		$ret['dateBeginOk']       = $this->getVar('quiz_dateBeginOk');
		$ret['dateEndOk']         = $this->getVar('quiz_dateEndOk');
		$ret['build']             = $this->getVar('quiz_build');
		$ret['actif']             = $this->getVar('quiz_actif');
        //verifie que le quiz a ?t? g?n?r?
        $quiz_html = CREAQUIZ_UPLOAD_QUIZ_PATH . "/{$ret['folderJS']}/index.html"; 
        $ret['quiz_html'] = (file_exists($quiz_html)) ?  CREAQUIZ_UPLOAD_QUIZ_URL . "/{$ret['folderJS']}/index.html" : '';
        $ret['quiz_html_path'] = (file_exists($quiz_html)) ?  $quiz_html : '';
        
        $quiz_tpl = CREAQUIZ_UPLOAD_QUIZ_PATH . "/{$ret['folderJS']}/index.tpl"; 
        $ret['quiz_tpl'] = (file_exists($quiz_tpl)) ?  CREAQUIZ_UPLOAD_QUIZ_URL . "/{$ret['folderJS']}/index.tpl" : '';
        $ret['quiz_tpl_path'] = (file_exists($quiz_tpl)) ?  $quiz_tpl : '';
        $ret['flags'] = $this->getFlags($ret);
        
        $ret['countQuestions'] = $this->countQuestions();
        
		return $ret;
	}
	
    public function getFlags(&$ret){
        $flags = array();
        $flags['actif']             = quizFlagAscii($ret['actif'], "A");
        $flags['publishResults']    = quizFlagAscii($ret['actif'], "R");
        $flags['publishAnswers']    = quizFlagAscii($ret['publishAnswers'], "S");
        $flags['showResultPopup']   = quizFlagAscii($ret['showResultPopup'], "Popup");
        $flags['useTimer']          = quizFlagAscii($ret['useTimer'], "T");        
        $flags['allowedSubmit']     = quizFlagAscii($ret['allowedSubmit'], "Sb");                                
        $flags['showReloadAnswers'] = quizFlagAscii($ret['showReloadAnswers'], "Rl");
        $flags['showGoToSlide']     = quizFlagAscii($ret['showGoToSlide'], "Go");
        $flags['allowedPrevious']   = quizFlagAscii($ret['allowedPrevious'], "Pr"); 
        $flags['shuffleQuestions']  = quizFlagAscii($ret['shuffleQuestions'], "M"); 
        $flags['showGoodAnswers']   = quizFlagAscii($ret['showGoodAnswers'], "Ga"); 
        $flags['showBadAnswers']    = quizFlagAscii($ret['showBadAnswers'], "Ba"); 
        $flags['showScoreMinMax']   = quizFlagAscii($ret['showScoreMinMax'], "Smm"); 
        $flags['ShowAllSolutions']  = quizFlagAscii($ret['ShowAllSolutions'], "Vas"); 
               
        $flags['answerBeforeNext']  = quizFlagAlpha($ret['answerBeforeNext'], "Ro|Ro");
        $flags['onClickSimple']     = quizFlagAlpha($ret['onClickSimple'], "Dk|Sk");

        return $flags;
}                                      

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayQuiz()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function getParentId($quizId)

    {
        $ob = $this->get('quest_id', $questId);
        return $ob->GetVar('quest_quiz_id');
    }
    
/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function countQuestions()

    {
    global $questionsHandler;
    
    $criteria = new \CriteriaCompo();
    $criteria->add( new \Criteria("quest_quiz_id",  $this->getVar('quiz_id'), "="));
    $criteria->add( new \Criteria("quest_type_question",  'pageBegin', "<>"));
    $criteria->add( new \Criteria("quest_type_question",  'pageEnd', "<>"));
    $criteria->add( new \Criteria("quest_type_question",  'pageGroup', "<>"));
    $count = $questionsHandler->getCount($criteria);
    return $count;
    }
    
/* ******************************
 * renvoie l'id parent pour l'idEnfant
 * *********************** */
    public function countGroups()

    {
    global $questionsHandler;
    
    $criteria = new \CriteriaCompo();
    $criteria->add( new \Criteria("quest_quiz_id",  $this->getVar('quiz_id'), "="));
    $criteria->add( new \Criteria("quest_type_question",  'pageGroup', "="));
    $count = $questionsHandler->getCount($criteria);
    return $count;
    }
}
