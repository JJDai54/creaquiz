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

require __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'creaquiz_quiz_solutions.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$op     = Request::getCmd('op', 'list');
$quizId = Request::getInt('quiz_id', 0);
$start = 0; //Request::getInt('start', 0);
$limit = 0; //Request::getInt('limit', $creaquizHelper->getConfig('adminpager'));
$resultId = Request::getInt('result_id', 0);

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet( $style, null );

$GLOBALS['xoopsTpl']->assign('xoops_icons32_url', XOOPS_ICONS32_URL);
$GLOBALS['xoopsTpl']->assign('creaquiz_url', CREAQUIZ_URL);



// Check permissions
// if (!$permissionsHandler->getPermGlobalSubmit()) {
// 	redirect_header('quiz.php?op=list', 3, _NOPERM);
//}
// Check params
if (0 == $quizId) {
	redirect_header('categories.php?op=list', 3, _MA_CREAQUIZ_INVALID_PARAM);
}
///////////////////////////////////////////////////
//     $rootApp = CREAQUIZ_QUIZ_JS_PATH . "/quiz-js";
//     $urlApp  = CREAQUIZ_QUIZ_JS_URL  . "/quiz-js";
// 
//     //insertion des CSS
//     $tCss = \JJD\FSO\getFilePrefixedBy($rootApp.'/css', array('css'), '', false, false,false);
//     $urlCss = CREAQUIZ_QUIZ_JS_URL. "/quiz-js/css";
//     foreach($tCss as $css){
// 		$GLOBALS['xoTheme']->addStylesheet($urlCss .'/'. $css , null );    
//     }
//     //----------------------------------------------
//     //insertion du prototype des tpl
//     $xoTheme->addScript($urlApp . '/' . 'slide__prototype.js');    


		$quizObj = $quizHandler->get($quizId);
        $quiz = $quizObj->getValuesQuiz();
        $catId = $quizObj->getVar('quiz_cat_id');
        
        //Recupe des questions du quiz
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('quest_quiz_id',$quizId, "="));
        
        $questionsCount = $questionsHandler->getCountQuestions($criteria);
        //�a ne devrait pas arriv� mais au cas ou, pas de question retour aux categories
		if ($questionsCount == 0) 
	           redirect_header('categories.php?op=list', 3, _MA_CREAQUIZ_INVALID_PARAM);
        $questionsAll = $questionsHandler->getAllQuestions($criteria, $start, $limit, 'quest_weight ASC, quest_question');
        
        //chargement des donn�es des questions
        $questions = array();
        $numQuestion = 0;
		foreach(array_keys($questionsAll) as $i) {
			$question = $questionsAll[$i]->getValuesQuestions();
            $question['solutions'] = $questionsAll[$i]->getSolutions($quiz['showAllSolutions']);
            $question['solutions']['libScoreMax'] = sprintf(_CO_CREAQUIZ_POINTS_FOR_ANSWER2, $question['solutions']['scoreMin'], $question['solutions']['scoreMax']);
            if($question['isQuestion']) 
                $question['numQuestion'] = ++$numQuestion;
            else
                $question['numQuestion'] = 0;
            
            //chargement des r�ponses correctes   pour chaque question
            $questions[] = $question;
		}
        
		//	$GLOBALS['xoopsTpl']->assign('error', _AM_CREAQUIZ_THEREARENT_QUESTIONS);        
// Define Stylesheet
\JJD\load_css('', false);

		$xoopsTpl->assign('quiz', $quiz);        
		$xoopsTpl->assign('questions', $questions);        
		$xoopsTpl->assign('admin', true);        
		$xoopsTpl->assign('modPathIcon16', $modPathIcon16);        
		$xoopsTpl->assign('modPathArrows', $modPathIcon16 . "/arrows/blue");        

$GLOBALS['xoTheme']->addStylesheet($GLOBALS['xoops']->url("modules/creaquiz/assets/css/style.css"));
// Breadcrumbs
$xoBreadcrumbs[] = ['title' => _MA_CREAQUIZ_SOLUTIONS];

//---------------------------------------------------
//  ajout du resultat du participant le cas �ch�ant
//----------------------------------------------------
    //$tResult['result_id'] = $resultId;
    
    if($resultId > 0){
        $tResult = array();
        $resultsObj = $resultsHandler->get($resultId);
        $tResult = $resultsObj->getValuesResults();
//echo "<hr>Result <pre>" . print_r($tResult, true) . "</pre><hr>";
        
    }else{$tResult = null;}
	$xoopsTpl->assign('result', $tResult);        
//echo "<hr>Result <pre>" . print_r($tResult, true) . "</pre><hr>";
//exit;
//----------------------------------------------------



require __DIR__ . '/footer.php';
