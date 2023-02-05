<?php
//namespace XoopsModules\Creaquiz;

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
include_once CREAQUIZ_PATH . "/class/Type_question.php";

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Answers
 */
class slide_listboxIntruders1 extends XoopsModules\Creaquiz\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("listboxIntruders1", 0, 300, "listbox");
        $this->optionsDefaults = ['minReponses'=>0, 'shuffleAnswers'=>1, 'imgHeight'=>80];
        $this->hasImageMain = true;
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

/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           
      $name = 'imgHeight';  
      $inpHeight1 = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(32, 300);
      $trayHeight1 = new \XoopsFormElementTray(_AM_CREAQUIZ_IMG_HEIGHT1, $delimeter = ' ');  
      $trayHeight1->addElement($inpHeight1);
      $trayHeight1->addElement(new \XoopsFormLabel(' ', _AM_CREAQUIZ_PIXELS));
      $trayOptions->addElement($trayHeight1);     
      
      $name = 'shuffleAnswers';  
      $inputShuffleAnsweres = new \XoopsFormHidden("{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions ->addElement($inputShuffleAnsweres);     

      $name = 'minReponses';  
      $inpMinReponses = new XoopsFormNumber(_AM_CREAQUIZ_QUESTIONS_MINREPONSE,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpMinReponses->setMinMax(0, 12);
      $trayOptions->addElement($inpMinReponses);     
      
      return $trayOptions;
    }


/* *************************************************
*
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');


        $i=0;
        if (isset($answers[$i])){
            $tPropos = explode(",", $answers[$i]->getVar('answer_proposition'));
            $tPoints = explode(",", $answers[$i]->getVar('answer_points'));
        }else{
            $tPropos = array();
            $tPoints = array();
        };

   
        for ($h = 0; $h < $this->maxPropositions; $h++){
            $trayAns = new XoopsFormElementTray  ('', $delimeter = ' ');  
            
            $propos = (isset($tPropos[$h]))  ? $tPropos[$h] : '';
            $points = (isset($tPoints[$h]))  ? $tPoints[$h] : 0;
            
            $inpLab  = new XoopsFormLabel("", $h+1 . " : ");
            $trayAns->addElement($inpLab);
            
            $name = $this->getName($h, 'proposition');
            $inpPropos = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_POINTS, $name, $this->lgProposition, $this->lgProposition, $propos);
            $trayAns->addElement($inpPropos);
            
            $name = $this->getName($h, 'points');
            $inpPoints = new \XoopsFormNumber(_AM_CREAQUIZ_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayAns->addElement($inpPoints);
            
            $trayAllAns->addElement($trayAns);
        }
        $this->trayGlobal->addElement($trayAllAns);
        
        
        //----------------------------------------------------------
		return $this->trayGlobal;
	}
    
/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $type_questionHandler;
        //$this->echoAns ($answers, $questId, $bExit = true);    
        $answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------        
       
    $tPropos = array();
    $tPoints = array();
    foreach ($answers as $key=>$value){
        if ($value['proposition'] === '') continue;
            $tPropos[] = trim($value['proposition']);
            $tPoints[] = intval(trim($value['points']));

    }

	$ansObj = $answersHandler->create();
	$ansObj->setVar('answer_quest_id', $questId);
	$ansObj->setVar('answer_proposition', implode(',', $tPropos));
	$ansObj->setVar('answer_points', implode(',', $tPoints));
	$ansObj->setVar('answer_weight', 10);
    
	$ansObj->setVar('answer_caption', '');
	$ansObj->setVar('answer_inputs', 0);
    
	$ret = $answersHandler->insert($ansObj);
    
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[0]->getValuesAnswers();
    $tp = $this->combineAndSorAnswer($ans);
        
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;

	foreach($tp as $key=>$item) {
        $points = intval($item['points']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = CREAQUIZ_POINTS_POSITIF;
            $html[] = sprintf($tpl, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color);
        }elseif ($points < 0) {
            $scoreMin += intval($points);
            $color = CREAQUIZ_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = CREAQUIZ_POINTS_NULL;
            $html[] = sprintf($tpl, $item['exp'], '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color);
        }
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe

