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

defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Questions
 */
class Questions extends \XoopsObject
{
	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct()
	{
		$this->initVar('quest_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_parent_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_flag', XOBJ_DTYPE_INT);
		$this->initVar('quest_quiz_id', XOBJ_DTYPE_INT);
		$this->initVar('quest_type_question', XOBJ_DTYPE_TXTBOX);
//		$this->initVar('quest_type_form', XOBJ_DTYPE_INT);
		$this->initVar('quest_question', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_options', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_comment1', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_explanation', XOBJ_DTYPE_OTHER);
		$this->initVar('quest_learn_more', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_see_also', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_image', XOBJ_DTYPE_TXTBOX);
		$this->initVar('quest_minReponse', XOBJ_DTYPE_INT);
		$this->initVar('quest_points', XOBJ_DTYPE_INT);
		$this->initVar('quest_numbering', XOBJ_DTYPE_INT);
		$this->initVar('quest_shuffleAnswers', XOBJ_DTYPE_INT);
		$this->initVar('quest_creation', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quest_update', XOBJ_DTYPE_OTHER); //XOBJ_DTYPE_DATETIME
		$this->initVar('quest_weight', XOBJ_DTYPE_INT);
		$this->initVar('quest_timer', XOBJ_DTYPE_INT);
		$this->initVar('quest_isQuestion', XOBJ_DTYPE_INT);
		$this->initVar('quest_visible', XOBJ_DTYPE_INT);
		$this->initVar('quest_actif', XOBJ_DTYPE_INT);
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
	public function getNewInsertedIdQuestions()
	{
		$newInsertedId = $GLOBALS['xoopsDB']->getInsertId();
		return $newInsertedId;
	}

	/**
	 * @public function getForm
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
 	public function getFormQuestions($action = false, $sender="")
 	{
        global $quizHandler, $utility, $quizUtility, $type_questionHandler, $xoTheme;
        
		// Permissions for uploader
        $isAdmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['xoopsModule']->mid());
		$grouppermHandler = xoops_getHandler('groupperm');
		$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : XOOPS_GROUP_ANONYMOUS;
		$permissionUpload = $grouppermHandler->checkRight('upload_groups', 32, $groups, $GLOBALS['xoopsModule']->getVar('mid')) ? true : false;
		xoops_load('XoopsFormLoader');

        //===========================================================        
		$creaquizHelper = \XoopsModules\Creaquiz\Helper::getInstance();
        // recupe de la classe du type de question
        $typeQuestion = $this->getVar('quest_type_question');
        $clTypeQuestion = $this->getTypeQuestion();
		$questionsHandler = $creaquizHelper->getHandler('Questions'); // Questions Handler
        //=================================================
        
		if (false === $action) {
			$action = $_SERVER['REQUEST_URI'];
		}else{
            $h = strpos( $_SERVER['REQUEST_URI'], "?");
			$action = substr($_SERVER['REQUEST_URI'], 0, $h);
        }
        //---------------------------------------------- 
		// Title
		$title = $this->isNew() ? sprintf(_AM_CREAQUIZ_QUESTIONS_ADD) : sprintf(_AM_CREAQUIZ_QUESTIONS_EDIT);
		// Get Theme Form
		$form = new \XoopsThemeForm($title, 'form', $action, 'post', true);
		$form->setExtra('enctype="multipart/form-data"');
        
        $form->addElement(new \XoopsFormHidden('sender', $sender));
\JJD\include_highslide(null,"creaquiz");     
$xoTheme->addScript(CREAQUIZ_URL . '/assets/js/admin.js');

       //----------------------------------------------------------
		// Form Select questQuiz_id
		$inpQuizId = new \XoopsFormSelect( _AM_CREAQUIZ_QUESTIONS_QUIZ_ID, 'quest_quiz_id', $this->getVar('quest_quiz_id'));
		$inpQuizId->addOption('Empty');
		$inpQuizId->addOptionArray($quizHandler->getListKeyName());
        $saisissable = true;
        if (!$saisissable){ //autorise la selection de quiz_id
            $inpQuizId->setExtra("disabled");
            $form->addElement(new \XoopsFormHidden('quest_quiz_id', $this->getVar('quest_quiz_id')));
        }        
		$form->addElement($inpQuizId);
       //----------------------------------------------------------

        
        if ($clTypeQuestion->isQuestion){
          // Form Select questType_question
          $inpTypeQuestion = new \XoopsFormSelect( '', 'quest_type_question', $typeQuestion);
          $inpTypeQuestion->addOption('Empty');
          $inpTypeQuestion->addOptionArray($type_questionHandler->getListKeyName(null, true));
          $inpTypeQuestion->setExtra("onchange='reloadImgModeles(\"modelesTypeQuestionId\");'");
          
        }else{
            $form->addElement(new \XoopsFormHidden('quest_type_question', $typeQuestion));
            $inpTypeQuestion = new \XoopsFormLabel('', $typeQuestion);
        }		
        
        //----------------------------------------------------------
        $trayTypeQuestion = new \XoopsFormElementTray  (_CO_CREAQUIZ_TYPE_QUESTION, $delimeter = '');  //_AM_CREAQUIZ_QUESTIONS_TYPE_QUESTION
        $trayTypeQuestion->setDescription(_CO_CREAQUIZ_TYPE_QUESTION_DESC);
        $trayTypeQuestion->addElement($inpTypeQuestion);
/*
        $url =  CREAQUIZ_MODELES_IMG . "/slide_" . $typeQuestion . '-00.jpg';
        $img =  <<<___IMG___
            <div id='modelesTypeQuestionId'>
            <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                <img src="{$url}" alt="slides" style="max-width:40px" />
            </a>
            </div>
        ___IMG___;
        //$inpImg = new \XoopsFormLabel  ('', $img);  
*/        
        $imgModelesHtml = new \XoopsFormLabel('', $clTypeQuestion->getHtmlImgModeles());  
        //$imgModelesHtml->setExtra("class='highslide-gallery'");
        $trayTypeQuestion->addElement($imgModelesHtml);
		$form->addElement($trayTypeQuestion);
        //----------------------------------------------------------
		// Form Select quest_parent_id         
        if($clTypeQuestion->isQuestion()){         
            $tParent = $questionsHandler->getParents($this->getVar('quest_quiz_id'), true);         
            $parentId = ($this->getVar('quest_parent_id') == 0) ? array_keys($tParent)[0] : $this->getVar('quest_parent_id');
            $inpParent = new \XoopsFormSelect( _AM_CREAQUIZ_PARENT, 'quest_parent_id', $parentId);
            $inpParent->addOptionArray($tParent);
            $inpWeight = new \XoopsFormText( _AM_CREAQUIZ_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));
            
        }elseif($clTypeQuestion->typeQuestion == 'pageGroup'){
            $inpParent = new \XoopsFormHidden('quest_parent_id', 0);        
            $inpWeight = new \XoopsFormText( _AM_CREAQUIZ_WEIGHT, 'quest_weight', 20, 50,  $this->getVar('quest_weight'));
        }
        else{
            //c'est la page de debut ou de fin on affiche pas le poids et pas de parent;
            $inpParent = new \XoopsFormHidden('quest_parent_id', 0);        
            $inpWeight = new \XoopsFormHidden('quest_weight', $this->getVar('quest_weight'));        
        }   
        $form->addElement($inpParent);


        //----------------------------------------------------------
        $form->insertBreak("<div style='background:black;color:white;'><center>" . _AM_CREAQUIZ_PARAMETRES . "</center></div>");
        
		// Form Text questQuestion
		$form->addElement(new \XoopsFormText( _AM_CREAQUIZ_QUESTIONS_QUESTION, 'quest_question', 120, 255, $this->getVar('quest_question') ), true);
        
		// Form Text questWeight
		$form->addElement($inpWeight);
		
		// Form Editor DhtmlTextArea questComment1
        $inpComment1  = $quizUtility->getEditor2(_AM_CREAQUIZ_QUESTIONS_COMMENT1, 'quest_comment1', $this->getVar('quest_comment1', 'e'), _AM_CREAQUIZ_QUESTIONS_COMMENT1_DESC  , null, $creaquizHelper);        
		$form->addElement($inpComment1);

		// Form Editor DhtmlTextArea quest_explanation
        $inpExplanation  = $quizUtility->getEditor2(_AM_CREAQUIZ_EXPLANATION, 'quest_explanation', $this->getVar('quest_explanation', 'e'), _AM_CREAQUIZ_EXPLANATION_DESC, null, $creaquizHelper);        
		$form->addElement($inpExplanation);
        
		// Form Text learn_more
		$inpLearnMore = new \XoopsFormText( _AM_CREAQUIZ_QUESTIONS_LEARN_MORE, 'quest_learn_more', 120, 255, $this->getVar('quest_learn_more') );
        $inpLearnMore->setDescription(_AM_CREAQUIZ_QUESTIONS_LEARN_MORE_DESC);
		$form->addElement($inpLearnMore);
		// Form Text see_also
		$inpSeeAlso = new \XoopsFormText( _AM_CREAQUIZ_QUESTIONS_SEE_ALSO, 'quest_see_also', 120, 255, $this->getVar('quest_see_also') );
        $inpSeeAlso->setDescription(_AM_CREAQUIZ_QUESTIONS_SEE_ALSO_DESC);
		$form->addElement($inpSeeAlso);
        
        /* ***** Options uniquement pour les questions ***** */
        if($clTypeQuestion->isQuestion()){
/* transf�r� dans les options sp�cifiques
          // Form Text questMinReponse
          //$questMinReponse = $this->isNew() ? '0' : $this->getVar('');
          $inpMinReponse =   new \XoopsFormNumber(_AM_CREAQUIZ_QUESTIONS_MINREPONSE, 'quest_minReponse', 8, 8, $this->getVar('quest_minReponse'));
          $inpMinReponse->setMinMax(0, 5);
          $form->addElement($inpMinReponse);
*/

          //quest_points = $this->isNew() ? '0' : $this->getVar('');
          $inpPoints =   new \XoopsFormNumber('', 'quest_points', 8, 8, $this->getVar('quest_points'));
          //$inpPoints->setDescription(_AM_CREAQUIZ_QUESTIONS_POINTS_DESC);
          $inpPoints->setMinMax(0, 50);
          //$form->addElement($inpPoints);
          $form->addElement($this->TrayMergeFormWithDesc(_AM_CREAQUIZ_QUESTIONS_POINTS, $inpPoints, _AM_CREAQUIZ_QUESTIONS_POINTS_DESC));
          
          
    
          // Form Text questNumbering
          //----------------------------------------------------------
          $tOptNumbering = array(_AM_CREAQUIZ_NUMERIQUE,_AM_CREAQUIZ_UPPERCASE,_AM_CREAQUIZ_LOWERCASE);
          $inpNumbering = new \XoopsFormSelect(_AM_CREAQUIZ_NUMBERING , 'quest_numbering', $this->getVar('quest_numbering'));
          $inpNumbering->addOptionArray($tOptNumbering);
          $form->addElement($inpNumbering);
          
          
          //----------------------------------------------------------
          // Form int quest_shuffleAnswers
/* transf�r� dans les options sp�cifiques
          $inpShuffleAns = new \XoopsFormRadioYN(_AM_CREAQUIZ_SHUFFLE_ANS , 'quest_shuffleAnswers', $this->getVar('quest_shuffleAnswers'));        
          $inpShuffleAns->setDescription(_AM_CREAQUIZ_SHUFFLE_ANS_DESC);
          $form->addElement($inpShuffleAns);
*/          
        }

        // Form Text Select questTimer
        $inpTimer = new \XoopsFormNumber(_AM_CREAQUIZ_TIMER, 'quest_timer', 8, 8, $this->getVar('quest_timer'));
        $inpTimer->setMinMax(0, 30);
        $inpTimer->setDescription(_AM_CREAQUIZ_TIMER_DESC);
		$form->addElement($inpTimer);

        
		//$form->addElement($fileNameTray);
        
        // Form quest_visible
		$inpVisible = new \XoopsFormRadioYN(_AM_CREAQUIZ_VISIBLE, 'quest_visible', $this->getVar('quest_visible'));
        $inpVisible->setDescription(_AM_CREAQUIZ_VISIBLE_DESC);
        $form->addElement($inpVisible);
        
        // Form quest_actif
		$inpActif = new \XoopsFormRadioYN(_AM_CREAQUIZ_ACTIF, 'quest_actif', $this->getVar('quest_actif'));
        $inpActif->setDescription(_AM_CREAQUIZ_ACTIF_DESC);
        $form->addElement($inpActif);
        
        // ===================================================================
        // cette partie insert l'aide, les options et les poropositions propres au type de question
        // ===================================================================

        //ajout de l'aide pour ce slide
        $form->insertBreak("<div style='background:red;color:white;'><center>" . _AM_CREAQUIZ_SLIDE_HELP . "</center></div>");
        $form->addElement($clTypeQuestion->getSlideHelper());

        //====================================================================
        //options globales pour les propositions (height, btnColor, ...)
        $quiz = $quizHandler->get($this->getVar('quest_quiz_id'));
        $folderJS = $quiz->getVar('quiz_folderJS');
        //$idQuiz = $this->getVar('quest_quiz_id');
        //echo "<hr>dossier du quiz : {$idQuiz}-{$folderJS}<hr>";        
        if ($clTypeQuestion){
            $options =  html_entity_decode($this->getVar('quest_options'));
            $inpOptions = $clTypeQuestion->getFormOptions(_AM_CREAQUIZ_SPECIFIC_OPTIONS, 'quest_options',  $options, $folderJS);
            
            if($inpOptions || $clTypeQuestion->hasImageMain) 
                $form->insertBreak("<div style='background:blue;color:white;'><center>" . _AM_CREAQUIZ_SLIDE_OPTIONS . "</center></div>");
            
            if($clTypeQuestion->hasImageMain){
            $image = $this->getVar('quest_image');
            $inpImage = $clTypeQuestion->getFormImage(_AM_CREAQUIZ_IMAGE, 'quest_image', $image, $folderJS);
                $inpImage->setCaption(_AM_CREAQUIZ_IMAGE);
                $form->addElement($inpImage, false);
            }
            
            if($inpOptions){
                $form->addElement($inpOptions, false);
            }
            
        } 
       
        //exit("options = " . $this->getVar('quest_options'));
       
        //================================================
        //ajout des propositions de r�ponnses
        //$titleOptions = new \XoopsFormLabel(null,'Liste des options');
        $form->insertBreak("<div style='background:green;color:white;'><center>" . _AM_CREAQUIZ_PROPOSITIONS_ANSWERS . "</center></div>");
        if ($clTypeQuestion)  $form->addElement($clTypeQuestion->getForm($this->getVar('quest_id'), $this->getVar('quest_quiz_id')));
        
        //================================================
		// To Save
        $form->insertBreak("<div style='background:black;color:white;'><center>-----</center></div>");
		$form->addElement(new \XoopsFormHidden('op', 'save'));
//		$form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        
        $btnTray = new \XoopsFormElementTray  ('', '&nbsp;');
        $btnTray->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));
        $btnAddNew = new \XoopsFormButton('', 'submit_and_addnew', _AM_CREAQUIZ_SUBMIT_AND_ADDNEW,'submit');
        $btnAddNew->setClass('btn btn-success');
        $btnTray->addElement($btnAddNew);
		$form->addElement($btnTray);
		return $form;
	}

     
	/**
	 * TrayMergeFormWithDesc : assemble un form avec une description pour l'avoir dessous et non dans la colonne de titre
	 * @return form
	 */
function TrayMergeFormWithDesc($caption, $form, $desc, $sep="<br>"){
    $tray = new \XoopsFormElementTray($caption, $sep);
    $tray->addelement($form);
    $tray->addelement(new \XoopsFormLabel("",$desc));
    return $tray;
    
}
     
	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesQuestions($keys = null, $format = null, $maxDepth = null)
	{
        global $quizUtility;
        $clTypeQuestion = $this->getTypeQuestion();
        
		$creaquizHelper  = \XoopsModules\Creaquiz\Helper::getInstance();
		$utility = new \XoopsModules\Creaquiz\Utility();
		$ret = $this->getValues($keys, $format, $maxDepth);
		$ret['id']             = $this->getVar('quest_id');
		$ret['parent_id']      = $this->getVar('quest_parent_id');
		$ret['quiz_id']        = $this->getVar('quest_quiz_id');
		$ret['type_question']  = $this->getVar('quest_type_question');
		$ret['question']       = $this->getVar('quest_question');
		$editorMaxchar = $creaquizHelper->getConfig('editor_maxchar');
        
        //getVar genere une transformation facheuse 
		$ret['options']        = html_entity_decode($this->getVar('quest_options')) ;
        //pour palier aux transfert des options sp�cifiques sur des quiz plus anciens,
        //on recup�re les options par �fauts en attenaant de modifier et valider de nouveau la question
        if(!$ret['options']) $ret['options'] = json_encode($clTypeQuestion->optionsDefaults);
      
		$ret['comment1']       = $this->getVar('quest_comment1', 'e');
		$ret['comment1_short'] = $utility::truncateHtml($ret['comment1'], $editorMaxchar);
 		$ret['explanation']    = $this->getVar('quest_explanation', 'e');
 		$ret['explanation_short'] = $utility::truncateHtml($ret['explanation'], $editorMaxchar);
 		$ret['learn_more']     = $this->getVar('quest_learn_more', 'e');
 		$ret['see_also']       = $this->getVar('quest_see_also', 'e');
 		$ret['image']          = $this->getVar('quest_image', 'e');
//		$ret['minReponse']     = $this->getVar('quest_minReponse');
		$ret['points']         = $this->getVar('quest_points');
		$ret['numbering']      = $this->getVar('quest_numbering');
		$ret['shuffleAnswers'] = $this->getVar('quest_shuffleAnswers');
		$ret['creation']       = \JJD\getDateSql2Str($this->getVar('quest_creation'));
		$ret['update']         = \JJD\getDateSql2Str($this->getVar('quest_update'));
        
		$ret['weight']         = $this->getVar('quest_weight');
		$ret['timer']          = $this->getVar('quest_timer');
		$ret['visible']        = $this->getVar('quest_visible');
		$ret['actif']          = $this->getVar('quest_actif');
		$ret['flags']          = $this->getFlags($ret);
        
		$ret['isQuestion']        = $clTypeQuestion->isQuestion;
        if($clTypeQuestion){
    		$ret['isParent']       = $clTypeQuestion->isParent;
    		$ret['isQuestion']     = $clTypeQuestion->isQuestion;
    		$ret['canDelete']      = $clTypeQuestion->canDelete;
    		$ret['typeForm']       = $clTypeQuestion->typeForm;
		    $ret['typeForm_lib']  = $clTypeQuestion->typeForm_lib;
        }else{
    		$ret['isParent']       = false;
    		$ret['isQuestion']     = false;
    		$ret['canDelete']      = false;
    		$ret['typeForm']       = false;
		    $ret['typeForm_lib']  = '???';
        }
        
        
		return $ret;

	}

    public function getFlags(&$ret){
        $flags = array();
        $flags['actif'] = quizFlagAscii($ret['actif'], "A");
        $flags['visible'] = quizFlagAscii($ret['visible'], "V");
        $flags['shuffleAnswers'] = quizFlagAscii($ret['shuffleAnswers'], "M");
        
        $flags['numbering'] = quizFlagAlpha($ret['numbering'], "123|ABC|abc","blue|blue|blue");
                                           
        return $flags;
                                      
    }

	/**
	 * Returns an array representation of the object
	 *
	 * @return array
	 */
	public function toArrayQuestions()
	{
		$ret = [];
		$vars = $this->getVars();
		foreach(array_keys($vars) as $var) {
			$ret[$var] = $this->getVar('"{$var}"');
		}
		return $ret;
	}
    
/* ******************************
 * Change l'etat du champ passer en parametre
 * @$quizId : id du quiz
 * @$field : nom du champ � changer
 * *********************** */
    public function changeEtat($questId)
    {
        $sql = "UPDATE " . $this->table . " SET {$field} = not {$field} WHERE quest_id={$questId};";
        $ret = $this->db->queryf($sql);
        return $ret;
    }

/* ******************************
 *  getTypeQuestion : renvoie la class du type de question
 * @return : classe h�rit�e du type de question
 * *********************** */
    public function getTypeQuestion($default='checkboxSimple')
    {
    //echo "<hr>{$default}<hr>";
        global $quizUtility, $type_questionHandler;
        // recupe de la classe du type de question
        $typeQuestion = $this->getVar('quest_type_question');
        if ($typeQuestion == '') $typeQuestion = $default;
        return $type_questionHandler->getTypeQuestion($typeQuestion);
/*
        $clsName = "slide_" . $typeQuestion;   
        $f = CREAQUIZ_ANSWERS_CLASS . "/slide_" . $typeQuestion . ".php";  
        if (file_exists($f)){
            include_once($f);    
            $cls = new $clsName; 
        }else{$cls = null;}
        return $cls;
*/        
    }
        
/* ********************************************
*
*********************************************** */
  public function getSolutions($boolAllSolutions = true){
  //global $answersHandler;
    $typeQuestion = $this->getTypeQuestion(null);
    if (is_null($typeQuestion)) return "Problemo";

    //return $typeQuestion->getSolutions($this->getVar('quest_id'), $this);
    return $typeQuestion->getSolutions($this->getVar('quest_id'), $boolAllSolutions, $this);

     }
    
}
