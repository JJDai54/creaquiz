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


/**
 * Class Object Handler Type_question
 */
class Type_questionHandler 
{
    var $dirname = CREAQUIZ_PATH . "/quiz/quiz-php/class";
    var $name = '';
    var $description= '';
    
        
	/**
	 * Constructor 
	 *
	 * @param \XoopsDatabase $db
	 */
	public function __construct()
	{

	}


	/**
	 * Get Count Type_question in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCount()
	{
		return Count($this->getListKeyName());
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
	 * Get Count Quiz in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return int
	 */
	public function getCountQuiz()
	{
        
		return count($this->getListKeyName());
	}

	/**
	 * Get All Type_question in the database
	 * @param int    $start 
	 * @param int    $limit 
	 * @param string $sort 
	 * @param string $order 
	 * @return array
	 */
	public function getAll($category=null, $bolKeyType = false)
	{
        $listTQ = $this->getFileListTypeQuestion();
        $ret = array();
        //$utility = new \XoopsModules\Creaquiz\Utility();
        
        foreach ($listTQ as $key=>$v){
        $clsName = "slide_" . $key;        
            $tq = array();
            
            $f = CREAQUIZ_ANSWERS_CLASS . "/slide_" . $key . ".php";
            include_once($f);
            $cls = new $clsName; 
            if (is_null($category) || $cls->category == $category || $category == CREAQUIZ_ALL){
                if($bolKeyType)
                  $ret[$cls->type] = $cls->getValuesType_question();
                else
                  $ret[] = $cls->getValuesType_question();
            }
        }
//echo "<hr>type_questions<pre>" . print_r($ret, true) . "</pre><hr>";   
        return $ret;
	}
    
private function build_sorter($key) {
    return function ($a, $b) use ($key) {
        if($a->weight == $b->weight) return 0;
        return ($a[$key] > $b[$key]) ? 1 : -1;
    };
}

/* ***********************

************************** */
public function getFileListTypeQuestion(){
global $utility;
    $dirname = CREAQUIZ_ANSWERS_CLASS; 
    $extensions = array("php");
    $prefix = "slide_";
    return \JJD\FSO\getFilePrefixedBy($dirname, $extensions, $prefix, false, true);     
}
/* ***********************

************************** */
public function getListKeyName($category = null, $boolCode = false){
        $listTQ = $this->getAll($category);
        $ret = array();
        $weight = array();
        
        foreach ($listTQ as $key=>$arr){

              $ret[$arr['type']] = (($boolCode) ? $arr['type'] . ' : ' : '') . $arr['name'];  
              $weight[$arr['type']] = $arr['weight'];  
        
        }

       //tri sur le poids poids 
       array_multisort($weight, $ret);
       return $ret;

}

public function getListKeyName_old($boolCode = false){
        $listTQ = $this->getFileListTypeQuestion();
        $ret = array();
        $weight = array();
        $utility = new \XoopsModules\Creaquiz\Utility();
        
        foreach ($listTQ as $key=>$v){
        $clsName = "slide_" . $key;        
            $f = CREAQUIZ_ANSWERS_CLASS . "/slide_" . $key . ".php";
            include_once($f);
            $cls = new $clsName; 
            $ret[$cls->type] = (($boolCode) ? $cls->type . ' : ' : '') . $cls->name;  
            $weight[$cls->type] = $cls->weight;  
        }

       //tri sur le poids poids le nom
       array_multisort($weight, $ret);
      
        return $ret;

}




/* ************************

************************** */
public function getListByGroup($boolCode = false){
    $allTQ = $this->getAll(null, true);
    $weight = array();
    $cat = array();
    foreach($allTQ as $key => $arr) {
        $wheight[$key] = $arr['type'];
        $cat[$key]     = $arr['category'];
    }
    sort($wheight);
    sort($cat);
    array_multisort($allTQ, $cat, $weight);
    
    $cat="";
    $ret = array();
    $prefix = '--- ';
    foreach($allTQ as $key => $arr) {
       if($arr['obsolette']) continue;
       if ($cat != $arr['category']){
            $ret['>' . $arr['category']] = $arr['category'] . ' : ' . $arr['categoryLib'];

       }
       $ret[$key] = $prefix . (($boolCode) ? $arr['type'] . ' : ' : '') . $arr['name'];
       $cat = $arr['category'];
    }
    return $ret;    
}
/* ***********************

************************** */
public function getCategories($addAll = false){
    $allTQ = $this->getAll(null, true);
    $cat = array();    
    if($addAll) $cat[CREAQUIZ_ALL] = '(*)';
    foreach($allTQ as $key => $arr) {
        if(!array_key_exists($arr['category'], $cat))
            $cat[$arr['category']] = $arr['category'];
    }
    return $cat;    
}

/****************************************************************************
 * 
 ****************************************************************************/

public function getClassTypeQuestion($typeQuestion){
    $clsName = "slide_" . $typeQuestion;   
    $f = CREAQUIZ_ANSWERS_CLASS . "/slide_" . $typeQuestion . ".php";  
    include_once($f);    
    $cls = new $clsName; 
    return $cls;

}

/* **********************************************************
*
 	public function getFormType_question($typeQuestion, $quizId = 0)
 	{
     global $utility, $categoriesHandler, $quizHandler, $type_questionHandler, $quizUtility;
        //---------------------------------------------- 
		$creaquizHelper = \XoopsModules\Creaquiz\Helper::getInstance();
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}else{
            $h = strpos( $_SERVER['REQUEST_URI'], "?");
			$action = substr($_SERVER['REQUEST_URI'], 0, $h);
			//$action = "questions.php";
			//$action = "modules/creaquiz/admin/questions.php";
        }
//         echo "<br>Action : {$action}<br>";
// 		exit;
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		// Permissions for uploader
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
        //=================================================
        // recupe de la classe du type de question
        $clTypeQuestion = $quizUtility->getTypeQuestion($typeQuestion);
        
        //===========================================================        
		// Title
		$title = sprintf(_AM_CREAQUIZ_QUESTIONS_ADD, $clTypeQuestion->description);
		// Get Theme Form
		xoops_load('XoopsFormLoader');
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
		// Questions Handler
        //----------------------------------------------------------
		// Questions Handler
// 		$questionsHandler = $creaquizHelper->getHandler('Questions');
// 		// Form Select questQuiz_id
// 		$questQuiz_idSelect = new \XoopsFormSelect( _AM_CREAQUIZ_QUESTIONS_QUIZ_ID, 'quest_quiz_id', $this->getVar('quest_quiz_id'));
// 		$questQuiz_idSelect->addOption('Empty');
// 		$questQuiz_idSelect->addOptionArray($quizHandler->getListKeyName());
//         $typeQuestion = $this->getVar('quest_type_question');
        //----------------------------------------------------------
        $catArray = $categoriesHandler->getListKeyName();
        //public function (CriteriaElement $criteria = null, $addAll=false, $addNull=false, $short_permtype = 'view')
        
        if ($quizId > 0){
            $quizObj = $quizHandler->get($quizId);
            $catId = $quizObj->getVar('quiz_cat_id');
            //$criteria = new CriteriaCompo(new \Criteria('quiz_cat_id', $catId,""));
            $quizArray = $quizHandler->getListKeyName($catId);
        }else{
            $keys = array_keys ($catArray);
            $catId = $keys[0];
            $quizArray = $quizHandler->getListKeyName($catId);
            $keys = array_keys ($catArray);
            $quizId = $keys[0];
        }

		
        // Form Select quizCat_id
		$inpCat = new \XoopsFormSelect( _AM_CREAQUIZ_CATEGORY, 'cat_id', $catId);
		$inpCat->addOptionArray($catArray);
		$form->addElement($inpCat, true);
        
        $inpQuiz = new \XoopsFormSelect(_AM_CREAQUIZ_QUIZ, 'quiz_id', $quizId);
        $inpQuiz->addOptionArray($quizHandler->getListKeyName($catId));
  	    //$GLOBALS['xoopsTpl']->assign('inpQuiz', $inpQuiz->render());
		$form->addElement($inpQuiz, true);
        $cls = $type_questionHandler->getClassTypeQuestion($typeQuestion);
        
        //----------------------------------------------------------
		// Form Select questType_question
// 		$questType_questionSelect = new \XoopsFormSelect( _AM_CREAQUIZ_QUESTIONS_TYPE_QUESTION, 'quest_type_question', $typeQuestion);
// 		$questType_questionSelect->addOption('Empty');
// 		//$questType_questionSelect->addOptionArray($questionsHandler->getListKeyName());
// 		$questType_questionSelect->addOptionArray($type_questionHandler->getListKeyName());
        
        //================================================
		// To Save
        $form->insertBreak("<div style='background:black;color:white;'><center>-----</center></div>");
		$form->addElement(new \XoopsFormHidden('op', 'save'));
		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
		return $form;
     

	}
* *********************************************************** */
/* ******************************
 *  getTypeQuestion : renvoie la class du type de question
 * @return : classe héritée du type de question
 * *********************** */
public function getTypeQuestion($typeQuestion, $default=null)
  {
      //------------------------------------------------------
      // pour permettre une correction sans aceder à la base apres un changement nom
      // A virer dès que les noms seront stabilisés
      if ( $typeQuestion == 'imagesSortItems') $typeQuestion = 'imagesDaDSortItems';
      //------------------------------------------------------
      
      // recupe de la classe du type de question
      if ($typeQuestion == '') return $default;
      
      $clsName = "slide_" . $typeQuestion;   
      $f = CREAQUIZ_ANSWERS_CLASS . "/slide_" . $typeQuestion . ".php";  
      //----------------------------------------------
      if (file_exists($f)){
          include_once($f);    
          $cls = new $clsName; 
        return $cls;
      }
      else{
        return null;
      }
  }
        
    



} //Fin de la class
