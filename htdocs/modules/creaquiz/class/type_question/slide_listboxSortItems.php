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
class slide_listboxSortItems extends XoopsModules\Creaquiz\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("listboxSortItems", 0, 330, "listbox");
        $this->optionsDefaults = ['ordre'=>'N', 'title'=>'', 'btnColor'=>'blue', 'btnHeight'=>28];
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
*  toto : ghanger le mode de gestion par une proposition par item
* *********************************************************** */

/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $optionName, $jsonValues = null)
 	{      
      $tValues = $this->getOptions($jsonValues, $this->optionsDefaults);
      $trayOptions = new XoopsFormElementTray($caption, $delimeter = '<br>');  
      //--------------------------------------------------------------------           

      $name = 'orderStrict';  
      $inputOrder = new \XoopsFormRadio(_AM_CREAQUIZ_ORDER_ALLOWED . ' : ', "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrder->addOption("N", _AM_CREAQUIZ_ONLY_ORDER_NAT);            
      $inputOrder->addOption("R", _AM_CREAQUIZ_ALLOW_ALL_ORDER);            
      $trayOptions->addElement($inputOrder);     
      
      $name = 'title';  
      $inpTitle = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_CAPTION0, "{$optionName}[{$name}]", $this->lgProposition, $this->lgProposition, $tValues[$name]);
      $trayOptions->addElement($inpTitle);     

      $name = 'btnColor';  
      $btnColors = XoopsLists::getDirListAsArray(CREAQUIZ_QUIZ_JS_ORG . '/images/buttons', '');
      $impBtnColors = new XoopsFormSelect(_AM_CREAQUIZ_BUTTONS_COLOR, "{$optionName}[{$name}]",$tValues[$name]) ;
      $impBtnColors->addOptionArray($btnColors);
      $trayOptions->addElement($impBtnColors);   
        
      $name = 'btnHeight';  
      $inpHeight1 = new \XoopsFormNumber('',  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight1->setMinMax(22, 96);
      $trayHeight1 = new \XoopsFormElementTray(_AM_CREAQUIZ_BTN_HEIGHT, $delimeter = ' ');  
      $trayHeight1->addElement($inpHeight1);
      $trayHeight1->addElement(new \XoopsFormLabel(' ', _AM_CREAQUIZ_PIXELS));
      $trayOptions->addElement($trayHeight1);     

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


//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
        //-------------------------------------------------
        //element definissat un objet ou un ensemble
        $i=0;
        for ($h = 0; $h < 1; $h++){
            $trayAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  
            if (isset($answers[$h])) {
                $tMots = explode(',', $answers[$h]->getVar('answer_proposition'));
                $points = $answers[$h]->getVar('answer_points');
                $caption = $answers[$h]->getVar('answer_caption'); 
                $weight = $answers[$h]->getVar('answer_weight'); 
            }else{
                $tMots = array();
                $points = 0;
                $caption = ''; 
                $weight = 0; 
            };
            //------------------------------------------------------------
//             $trayCaption = new XoopsFormElementTray  ('', $delimeter = '<br>');
//             
//             $name = $this->getName($h, 'caption');
//             $inpCaption = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_CAPTION0, $name, $this->lgProposition, $this->lgProposition, $caption);
//             $trayCaption->addElement($inpCaption);
//             
//             $name = $this->getName($h, 'points');
//             $inpPoints = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_POINTS, $name, $this->lgPoints, $this->lgPoints, $points);            
//             $trayCaption->addElement($inpPoints);
//             
// //             $name = $this->getName($h, 'weight');
// //             $inpWeight = new \XoopsFormText(_AM_CREAQUIZ_SLIDE_WEIGHT, $name, $this->lgPoints, $this->lgPoints, $weight);            
// //             $trayCaption->addElement($inpWeight);
//             
//             $this->trayGlobal->addElement($trayCaption);
            //---------------------------------------------------------
            
            $trayMots = new XoopsFormElementTray  ('', $delimeter = '<br>');  
           
            for ($i = 0; $i < $this->maxPropositions; $i++){
            $trayItem = new XoopsFormElementTray  ('', $delimeter = ' ');  
            
            
                $inpLab  = new XoopsFormLabel("", $i+1 . " : ");  
                $trayItem->addElement($inpLab);
                
                $mot = (isset($tMots[$i])) ? $tMots[$i] : '';          
                $name = $this->getName($h, 'mots', $i);
                $inpMot = new \XoopsFormText("", $name, $this->lgMot1, $this->lgMot2, $mot);
                $trayItem->addElement($inpMot);
                
                $trayMots->addElement($trayItem);
            }            
            $trayAns->addElement($trayMots);
            
            $this->trayGlobal->addElement($trayAns);
        
        }
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
        foreach ($answers as $ansKey=>$ansValue){
            if ($ansValue['caption'] === '' && $ansValue['points'] === '' ) continue;
            $caption = trim($ansValue['caption']);
            $points = intval(trim($ansValue['points']));
    
        	$ansObj = $answersHandler->create();
        	$ansObj->setVar('answer_quest_id', $questId);
        	$ansObj->setVar('answer_caption', $caption);
        	$ansObj->setVar('answer_points', $points);
//        	$ansObj->setVar('answer_weight', intval(trim($ansValue['weight'])));
            
            $tMots = array();
            foreach ($ansValue['mots'] as $keyMot=>$motValue){
                if ($motValue === '' ) continue;
                $tMots[] = $motValue;
            }
        	$ansObj->setVar('answer_proposition', implode(',', $tMots));
            
        	$ret = $answersHandler->insert($ansObj);
        }
//exit;    
    }
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler;

    $tpl = "<tr><td><span style='color:%2\$s;'>%1\$s</span></td></tr>";

    $answersAll = $answersHandler->getListByParent($questId);
    $ans = $answersAll[0]->getValuesAnswers();
    $tExp = explode(',', $ans['proposition']);
    $points = intval($ans['points']);
    
    $html = array();
    $html[] = "<table class='quizTbl'>";
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = $points;
    $scoreMin = 0;
    $color = 'blue';
	foreach($tExp as $key=>$exp) {
        $html[] = sprintf($tpl, $exp, $color);
	}
    $html[] = "</table>";
 
    $ret['answers'] = implode("\n", $html);
    $ret['scoreMax'] = $scoreMax;
    $ret['scoreMin'] = $scoreMin;
    //echoArray($ret);
    return $ret;
     }

} // fin de la classe

