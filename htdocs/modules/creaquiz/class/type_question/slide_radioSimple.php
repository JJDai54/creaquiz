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
class slide_radioSimple extends XoopsModules\Creaquiz\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("radioSimple", 0, 200, "radio");
        $this->optionsDefaults = ['shuffleAnswers'=>1, 'imgHeight'=> 80];
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
      $inputShowCaption = new \XoopsFormRadioYN(_AM_CREAQUIZ_SHUFFLE_ANS . ' : ', "{$optionName}[{$name}]", $tValues[$name]);
      $trayOptions ->addElement($inputShowCaption);     
      
      $trayOptions ->addElement(new XoopsFormLabel('', _AM_CREAQUIZ_SHUFFLE_ANS_DESC));     
      
      //--------------------------------------------------------------------           
      
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
        //element definissant un objet ou un ensemble
  
        $tbl = $this->getNewXoopsTableXtray();

        for ($k = 0; $k < $this->maxPropositions; $k++){
            if (isset($answers[$k])) {
                $propos = $answers[$k]->getVar('answer_proposition');
                $points = $answers[$k]->getVar('answer_points');
            }else{
                $propos = '';
                $points = 0;
            };

            $col = 0;
            $inpLab  = new XoopsFormLabel("", $k+1 . " : ");
            $tbl->addElement($inpLab, $col++, $k, '');
        
            $name = $this->getName($k, 'proposition');
            $inpPropos = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_PROPO, $name, $this->lgProposition, $this->lgProposition, $propos);
            $tbl->addElement($inpPropos, $col++, $k, '');
        
            $name = $this->getName($k, 'points');
            $inpPoints = new \XoopsFormNumber(_AM_CREAQUIZ_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $tbl->addElement($inpPoints, $col++, $k, '');
        
        
       
        }
        $this->trayGlobal->addElement($tbl);
        //----------------------------------------------------------
		return $this->trayGlobal;
	}
/* *************************************************
*
* ************************************************** */
 	public function getForm_old($questId, $quizId)
 	{
        global $utility, $answersHandler;

        $answers = $answersHandler->getListByParent($questId);
        $this->initFormForQuestion();
        //-------------------------------------------------
        //element definissant un objet ou un ensemble
        $trayInput = new XoopsFormElementTray  ('', $delimeter = '<br>');  
        $i=0;
        for ($h = 0; $h < $this->maxPropositions; $h++){
            if (isset($answers[$h])) {
                $propos = $answers[$h]->getVar('answer_proposition');
                $points = $answers[$h]->getVar('answer_points');
            }else{
                $propos = '';
                $points = 0;
            };
        
            $trayAns = new XoopsFormElementTray  ('', $delimeter = ' ');  
        
            $inpLab  = new XoopsFormLabel("", $h+1 . " : ");
            $trayAns->addElement($inpLab);
        
            $name = $this->getName($h, 'proposition');
            $inpPropos = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_PROPO, $name, $this->lgProposition, $this->lgProposition, $propos);
            $trayAns->addElement($inpPropos);
        
            $name = $this->getName($h, 'points');
            $inpPoints = new \XoopsFormNumber(_AM_CREAQUIZ_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);
            $inpPoints->setMinMax(-30, 30);
            $trayAns->addElement($inpPoints);
        
        
            $trayInput->addElement($trayAns);
        
        }
        $this->trayGlobal->addElement($trayInput);
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
        foreach ($answers as $key=>$value){
            if ($value['proposition'] === '') continue;
            $propos = trim($value['proposition']);
            $points = intval(trim($value['points']));
    
        	$ansObj = $answersHandler->create();
        	$ansObj->setVar('answer_quest_id', $questId);
        	$ansObj->setVar('answer_proposition', $propos);
        	$ansObj->setVar('answer_points', $points);
        	$ansObj->setVar('answer_weight', $key * 10);
            
        	$ansObj->setVar('answer_caption', '');
        	$ansObj->setVar('answer_inputs', 0);
            
        	$ret = $answersHandler->insert($ansObj);
        }
//exit;
    
    }


/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;
  /*
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']          = $this->getVar('answer_id');
		$ret['quest_id']    = $this->getVar('answer_quest_id');
		$ret['caption']      = $this->getVar('answer_caption');
		$ret['proposition'] = $this->getVar('answer_proposition');
		$ret['points']      = $this->getVar('answer_points');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['inputs']      = $this->getVar('answer_inputs');
  
  */
    // = "<tr style='color:%5\$s;'><td>%1\$s</td><td>%2\$s</td><td>%3\$s</td><td>%4\$s</td></tr>";
    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";
        
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');

//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $html = array();
    $html[] = "<table class='quizTbl'>";
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        if ($points > 0) {
            if ($scoreMax < $points) $scoreMax = $points;
            $color = CREAQUIZ_POINTS_POSITIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color);
        }elseif ($points < 0) {
            if ($scoreMin > $points) $scoreMin = $points;
            $color = CREAQUIZ_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color);
        }elseif($boolAllSolutions){
            $color = CREAQUIZ_POINTS_NULL;
            $html[] = sprintf($tpl, $ans['proposition'], '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color);
        }

	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMin'] = $scoreMin;
    $ret['scoreMax'] = $scoreMax;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe


