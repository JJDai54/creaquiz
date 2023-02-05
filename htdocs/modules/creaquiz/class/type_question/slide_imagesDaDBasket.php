﻿<?php
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
class slide_imagesDaDBasket extends XoopsModules\Creaquiz\Type_question
{
     
	/**
	 * Constructor 
	 *
	 */
	public function __construct()
	{
        parent::__construct("imagesDaDBasket", 0, 100, "imagesDaD");
        $this->maxPropositions = 16;	
        $this->optionsDefaults = ['imgHeight1'=>64,'imgHeight2'=>64,'group0'=>'Panier','group1'=>'','group2'=>'','group3'=>'','orientation'=>'V','showCaptions'=>'B'];
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

      //Taille des images à regrouper
      $name = 'imgHeight1';
      $inpHeight0 = new \XoopsFormNumber(_AM_CREAQUIZ_SIZE0,  "{$optionName}[{$name}]", $this->lgPoints, $this->lgPoints, $tValues[$name]);
      $inpHeight0->setMinMax(32, 128);
      $trayHeight0 = new XoopsFormElementTray('', $delimeter = ' ');      
      $trayHeight0->addElement($inpHeight0);
      $trayHeight0->addElement(new \XoopsFormLabel(' ', _AM_CREAQUIZ_PIXELS));
      $trayOptions ->addElement($trayHeight0);     
      
      $name = 'showCaptions';  
      $inputShowCaption = new \XoopsFormRadio(_AM_CREAQUIZ_SHOW_CAPTIONS, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputShowCaption->addOption("N", _AM_CREAQUIZ_SHOW_CAPTIONS_NONE);            
      $inputShowCaption->addOption("T", _AM_CREAQUIZ_SHOW_CAPTIONS_TOP);            
      $inputShowCaption->addOption("B", _AM_CREAQUIZ_SHOW_CAPTIONS_BOTTOM);            
      $trayOptions ->addElement($inputShowCaption);     
      
      $name = 'orientation';  
      $inputOrientation = new \XoopsFormRadio(_AM_CREAQUIZ_ORIENTATION_GROUPS, "{$optionName}[{$name}]", $tValues[$name], ' ');
      $inputOrientation->addOption("H", _AM_CREAQUIZ_ORIENTATION_H);            
      $inputOrientation->addOption("V", _AM_CREAQUIZ_ORIENTATION_V);            
      $trayOptions ->addElement($inputOrientation);     
      
      $name = 'group0';
      $inpGoup0 = new \XoopsFormText(_AM_CREAQUIZ_GROUP_LIB . ' 0',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
      $trayOptions ->addElement($inpGoup0);     
      
      $name = 'group1';
      $inpGoup1 = new \XoopsFormText(_AM_CREAQUIZ_GROUP_LIB . ' 1',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
      $trayOptions ->addElement($inpGoup1);     
      
      $name = 'group2';
      $inpGoup2 = new \XoopsFormText(_AM_CREAQUIZ_GROUP_LIB . ' 2',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
      $trayOptions ->addElement($inpGoup2);     
      
      $name = 'group3';
      $inpGoup3 = new \XoopsFormText(_AM_CREAQUIZ_GROUP_LIB . ' 3',  "{$optionName}[{$name}]", $this->lgMot2, $this->lgMot2, $tValues[$name]);
      $trayOptions ->addElement($inpGoup3);     

      return $trayOptions;
    }

/* *************************************************
* le champ group sert à différencier la suite logique des mauvaises réponses
*  - 0 : suite logique + weight
*  - 1 : mauvaises reponses
*  Le nom de l'image est stocké dans proposition
*  L'image de substitution est stocké dans le champ image
*  Les items a trouvé sont notés avec des points positifs et seront rempacés par l'image de substitution
*  Il peut y avoir plusieurs items a trouver dans la suite (conseillé : 2 ou 3 max )
*  Le nombre d'image de la séquence est de 8 maximum (voir 10 à tester)
*  Le nombre de fausse images est limité aussi à 8 (voir 10 à tester)
* ************************************************** */
 	public function getForm($questId, $quizId)
 	{
        global $utility, $answersHandler, $quizHandler, $questionsHandler;
        //recherche du dossier upload du quiz
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path =  CREAQUIZ_UPLOAD_QUIZ_JS . "/" . $quiz->getVar('quiz_folderJS') . "/images";
        
//echo "<hr>{$path}<hr>";
        $quest =  $questionsHandler->get($questId, 'quest_options');
        $options = json_decode(html_entity_decode($quest->getVar('quest_options')),true);

        $answers = $answersHandler->getListByParent($questId);
        
        //-------------------------------------------------------------
        $trayAllAns = new XoopsFormElementTray  ('', $delimeter = '<br>');  

        //-------------------------------------------------------------
        // affichage de la séquence correcte
        $i = $this->getFormGroup($trayAllAns, 0, $answers, _AM_CREAQUIZ_SEQUENCE, 0, $this->maxPropositions, $path, $options);
        

        
        //----------------------------------------------------------------
        $this->initFormForQuestion();

        //-------------------------------------------------
        //----------------------------------------------------------
        $this->trayGlobal->addElement($trayAllAns);
		return $this->trayGlobal;
	}
/* *************************************************
* meme procedure pour chaque groupe:
* - image de substitution
* - sequence logique
* - mauvaises reponses
* ************************************************** */
public function getFormGroup(&$trayAllAns, $group, $arr,$titleGroup, $firstItem, $maxItems, $path, $options)
{ 
        //suppression des enregistrement en trop
        if(count($arr) > $maxItems) $this->deleteToMuchItems($arr, $maxItems);
//         $lib = "<div style='background:black;color:white;'><center>" . $titleGroup . "</center></div>";        
//         $trayAllAns->addElement(new \XoopsFormLabel('',$lib));
        $weight = 0;




        $imgPath = CREAQUIZ_QUIZ_JS_PATH . '/images/substitut';
        $imgUrl = CREAQUIZ_QUIZ_JS_URL . '/images/substitut';
        $imgList = XoopsLists::getFileListByExtension($imgPath,  array('jpg','png','gif'), '');
//$this->echoAns ($imgList,'{$imgPath}', false);   
      
        $tbl = $this->getNewXoopsTableXtray();
        //----------------------------------------------------------
        for($k = 0 ; $k < $maxItems ; $k++){
            $i = $k + $firstItem;
            $weight += 10;
            if (isset($arr[$k])){
                $answerId    = $arr[$k]->getVar('answer_id');
                $proposition = $arr[$k]->getVar('answer_proposition');
                $points      = $arr[$k]->getVar('answer_points');
                $weight      = $weight; // $arr[$i]->getVar('answer_weight');
                $caption     = $arr[$k]->getVar('answer_caption');
                $group       = $arr[$k]->getVar('answer_group'); // on y met le n° du groupe
/*
*/        //choix d'une image existante:
            }else{
                $answerId = 0;
                $proposition = "";
                $imgName     = '';
                $points      = 1;
                $weight      = $weight;
                $caption     = '';
                $group       = '0';
            }
            
            //recupe des libellés de groupe si ils ont déjà été definis
            $libGroup0 = ($options['group0']) ? $options['group0'] : _AM_CREAQUIZ_GROUP;
            $libGroup1 = ($options['group1']) ? $options['group1'] : _AM_CREAQUIZ_GROUP . ' 1';
            $libGroup2 = ($options['group2']) ? $options['group2'] : _AM_CREAQUIZ_GROUP . ' 2';
            $libGroup3 = ($options['group3']) ? $options['group3'] : _AM_CREAQUIZ_GROUP . ' 3';
            
//$this->echoAns ($options,'options', false);   
            
             
            //if(!$imgName) $imgName     = 'blank-org.jpg';
            //-------------------------------------------------

            $inpAnswerId = new \XoopsFormHidden($this->getName($i,'id'), $answerId);            
            //$inpInput = new \XoopsFormHidden($this->getName($i,'group'), $group);            
            $libChrono = new \XoopsFormLabel('', $i+1); // . "[{$answerId}]"
            $inpChrono = new \XoopsFormHidden($this->getName($i,'chrono'), $i+1);    
            $inpPropositionImg = $this->getXoopsFormImage($proposition, $this->getName()."_proposition_{$i}", $path);
            $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete'),);                        
            $delProposition->addOption(1, _AM_CREAQUIZ_DELETE);
            $inpProposition = new \XoopsFormHidden($this->getName($i,'proposition'), $proposition);            
            $inpCaption = new \XoopsFormText(_AM_CREAQUIZ_CAPTION,  $this->getName($i,'caption'), $this->lgMot1, $this->lgMot1, $caption);
            $inpWeight = new \XoopsFormNumber(_AM_CREAQUIZ_WEIGHT,  $this->getName($i,'weight'), $this->lgPoints, $this->lgPoints, $weight);
            $inpWeight->setMinMax(0, 900);
            $inpPoints = new \XoopsFormNumber(_AM_CREAQUIZ_POINTS,  $this->getName($i,'points'), $this->lgPoints, $this->lgPoints, $points);            
            $inpPoints->setMinMax(1, 30);
            $inpgroup = new \xoopsFormSelect(_AM_CREAQUIZ_GROUP,  $this->getName($i,'group'), $group); //n° du groupe
            $inpgroup->addOptionArray(['0'=>$libGroup0, '1'=>$libGroup1, '2'=>$libGroup2, '3'=>$libGroup3]);
              
            
            //----------------------------------------------------

            $col=0;
            $tbl->addElement(new \XoopsFormLabel('','Proposition : ' . ($i+1)), $col, $k);
            $tbl->addElement($inpChrono, -1, $k, '');
            $tbl->addElement($inpAnswerId,  -1, $k, '');
             $tbl->addElement($delProposition,  $col, $k);
             
             $tbl->addElement($inpPropositionImg,  ++$col, $k);
//             $tbl->addElement($inpProposition,  $col, $k, '');
//             $tbl->addElement($libProposition,  $col, $k);
             
            $tbl->addElement($inpCaption,  ++$col, $k);
            $tbl->addElement($inpWeight,  $col, $k);
            $tbl->addElement($inpPoints,  $col, $k);
            
             
//             $tbl->addElement($inpImage,  ++$col, $k);
//             $tbl->addElement($inpImgSubstitut,  $col, $k);
//             //$tbl->addElement($delSubstitut,  $col, $k);
//             $tbl->addElement($libImage,  $col, $k);
             $tbl->addElement($inpgroup,  ++$col, $k);
            
        }

        $trayAllAns->addElement($tbl);
        return $i+1;  // return le dernier index pour le groupe suivant
}

     
/* *************************************************
*
* ************************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{
        global $utility, $answersHandler, $type_questionHandler, $quizHandler;
        
        $quiz = $quizHandler->get($quizId,"quiz_folderJS");
        $path = CREAQUIZ_UPLOAD_PATH . "/quiz-js/" . $quiz->getVar('quiz_folderJS') . "/images";
/*
echo '<hr>saveAnswers - POST : ';
$this->echoAns ($_POST,'POST', false);    
echo '<hr>saveAnswers - FILES : ';
$this->echoAns ($_FILES,'Fichiers', false);    
echo '<hr>';
exit;
*/
                
        //$this->echoAns ($answers, $questId, $bExit = false);    
        //$answersHandler->deleteAnswersByQuestId($questId); 
        //--------------------------------------------------------       
        
        /*
        */ 
       foreach ($answers as $key=>$v){
            $answerId = $v['id'];
            if($answerId > 0){
                $ansObj = $answersHandler->get($answerId);
                if(!isset($v['delete'])) $v['delete'] = 0;
            }else{
                $ansObj = $answersHandler->create();
                $v['delete'] = 0;
            }
        //$this->echoAns ($v, $questId, $bExit = false);    
                $v['quest_id'] = $questId;
            
        //Suppression de la proposition et de l'image
        if( $v['delete'] == 1) $this->delete_answer_by_image($v,$path);  
        
        
        //enregistrement de l'image
        //if($_FILES['answers'][name] != '') 
        //recuperation de l'image pour le champ proposition
        //le chrono ne correspond pad forcément à la clé dans files
        //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
        //un pour le champ "proposition" qui stocke l'image principale
        //et un pour le champ imge qui stocke l'image de substitution
        $prefix = "quiz-{$questId}-{$v['chrono']}";
        $formName = $this->getName()."_proposition_" . ($v['chrono']-1);
        $newImg = $this->save_img($v, $formName, $path, $quiz->getVar('quiz_folderJS'), $prefix, $nameOrg);
        if($newImg == ''){
            //$ansObj->setVar('answer_proposition', $v['proposition']);        
        }else{
            $ansObj->setVar('answer_proposition', $newImg);        
            if(!$v['caption']) $v['caption'] = $nameOrg;
        }
//         if(!$v['caption']) {
//             //on retire l'extension ou le prefix on met le nom de l'image
//             if(!$nameOrg) $nameOrg = substr($ansObj->getVar('answer_proposition'), strlen($prefix)+1);
//             $h= strrpos($nameOrg,'.');
//             $v['caption'] = str_replace('_', ' ', substr($nameOrg, $i, $h));
// 
//             //echo "<hr>{$nameOrg}-{$i}-{$h}|{$prefix}|{$v['caption']}<hr>";exit;
//         }
        
        //SSi le champ 'points' est plus petit que zéro on le force à 1
        if (intval($v['points']) == 0) $v['points'] = 1;

        //if(isset($v['proposition'])) $ansObj->setVar('answer_proposition', $v['proposition']);        
       // if ($fileImg =! '') $ansObj->setVar('answer_proposition', $fileImg);
        

        $ansObj->setVar('answer_caption', $v['caption']);
        $ansObj->setVar('answer_weight', $v['weight']);
        $ansObj->setVar('answer_points', $v['points']); 
        $ansObj->setVar('answer_quest_id', $questId); 
        $ansObj->setVar('answer_group', $v['group']); 
        
        //if ($v['points'] == 0) $v['image'] = '';
        //else if($v['image'] == '') $v['image'] = 'interrogation-05-bleu.png';
        
        //$ansObj->setVar('answer_image', $v['image']); 
        //$ansObj->setVar('answer_group', $v['group']); 
          
        $answersHandler->insert($ansObj);
     }
//        exit;
     //suppression des propositions qui n'ont pas d'image de definie
     $criteria = new CriteriaCompo(new Criteria('answer_quest_id', $questId, '='));
     $criteria->add(new Criteria('', 0, '=',null,'length(answer_proposition)'),"AND");
     $answersHandler->deleteAll($criteria);
     //exit ("<hr>===>saveAnswers<hr>" . $criteria->renderWhere() ."<hr>");
//     exit('fin de saveAnswer');
    }

/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){
  global $answersHandler, $quizHandler, $questionsHandler;
  /*
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']          = $this->getVar('answer_id');
		$ret['quest_id']    = $this->getVar('answer_quest_id');
		$ret['caption']      = $this->getVar('answer_caption');
		$ret['proposition'] = $this->getVar('answer_proposition');
		$ret['points']      = $this->getVar('answer_points');
		$ret['weight']      = $this->getVar('answer_weight');
		$ret['group']      = $this->getVar('answer_group');
  
  */
    // = "<tr style='color:%5\$s;'><td>%1\$s</td><td>%2\$s</td><td>%3\$s</td><td>%4\$s</td></tr>";
    $html = array();
 
    //-------------------------------------------
    // commençons par la solution
    $answersAll = $answersHandler->getListByParent($questId, 'answer_weight,answer_id');
    $quizId = $questionsHandler->get($questId, ["quest_quiz_id"])->getVar("quest_quiz_id");
//    echo("getSolutions - quizId = <hr><pre>" . print_r($quizId,true) . "</pre><hr>");
    //recherche du dossier upload du quiz
    $quiz = $quizHandler->get($quizId,"quiz_folderJS");
    $path =  CREAQUIZ_UPLOAD_QUIZ_URL . "/" . $quiz->getVar('quiz_folderJS') . "/images";
    $tplImg = "<img src='{$path}/%s' alt='' title='%s' height='64px'>";
    
    $tImg = array();
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        if ($ans['group'] == 0) {
            $tImg[] = sprintf($tplImg, $ans['proposition'], $ans['proposition']);
        }
	}
    $html[] = implode("\n", $tImg);
    $html[] = "<hr>";
    
       
    //-------------------------------------------
    $answersAll = $answersHandler->getListByParent($questId, 'answer_points DESC,answer_weight,answer_id');
//if(!$boolAllSolutions) exit;    
//    echoArray($answersAll);
    $ret = array();
    $scoreMax = 0;
    $scoreMin = 0;
    $tpl = "<tr><td><span style='color:%5\$s;'>%1\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%6\$s</span></td>" 
             . "<td><span style='color:%5\$s;'>%2\$s</span></td>" 
             . "<td style='text-align:right;padding-right:5px;'><span style='color:%5\$s;'>%3\$s</span></td>"
             . "<td><span style='color:%5\$s;'>%4\$s</span></td></tr>";

    $html[] = "<table class='quizTbl'>";
    
    
	foreach(array_keys($answersAll) as $i) {
		$ans = $answersAll[$i]->getValuesAnswers();
        $points = intval($ans['points']);
        $imgUrl = sprintf($tplImg, $ans['proposition'], $ans['proposition']);
        if ($points > 0) {
            $scoreMax += $points;
            $color = CREAQUIZ_POINTS_POSITIF;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color, $ans['caption']);
        }elseif ($points < 0) {
            $scoreMin += $points;
            $color = CREAQUIZ_POINTS_NEGATIF;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color, $ans['caption']);
        }elseif($boolAllSolutions){
            $color = CREAQUIZ_POINTS_NULL;
            $html[] = sprintf($tpl, $imgUrl, '&nbsp;===>&nbsp;', $points, _CO_CREAQUIZ_POINTS, $color, $ans['caption']);
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