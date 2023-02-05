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
//echo "<hr>class : Type_question<hr>";
defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class Object Type_question   //extends \XoopsObject
 */
class Type_question 
{
var $questId = 0;
var $type = '';
var $typeQuestion = '';//idem type
var $name = '';
var $description = '';
var $example = '';
var $image_fullName = '';
var $lgTitle = 80;
var $lgProposition = 80;
var $lgProposition2 = 80;
var $lgPoints = 5;
var $lgMot0 = 5;
var $lgMot1 = 20;
var $lgMot2 = 50;
var $lgMot3 = 80;
var $lgMot4 = 250;

var $trayGlobal; 
var $maxPropositions = 12; // valeur par default
var $isQuestion = 0; // valeur par default
var $canDelete = 1; // valeur par default
var $weight = 0; // valeur par default
var $category = ''; // valeur par default
var $categoryLib = ''; // valeur par default

var $renameImage = false; // Permet de garder le nom originale d'une image, a utiliser en dev uniquement
var $optionsDefaults = array('test'=>'JJD');
var $hasImageMain = false;

//si true, ne sera plus dans les listes de sélection pour une création, 
//mais permet de garder la compatibilité avec des slides créés avec ce type de question.
var $obsolette = false; 

	/**
	 * Constructor 
	 *
	 * @param null
	 */
	public function __construct($typeQuestion, $parentId = 0, $weight = 0, $cat="")
	{
        $this->type = $typeQuestion;
        $this->typeQuestion = $typeQuestion;
        $this->questId = $parentId;
        $this->category = $cat;
        
        switch($typeQuestion){
        case 'pageBegin' : $this->typeForm = CREAQUIZ_TYPE_FORM_BEGIN;      $this->isParent = true;  $this->isQuestion = 0; $this->canDelete = false; $this->typeForm_lib = _CO_CREAQUIZ_FORM_INTRO;    break;
        case 'pageGroup' : $this->typeForm = CREAQUIZ_TYPE_FORM_GROUP;      $this->isParent = true;  $this->isQuestion = 0; $this->canDelete = true;  $this->typeForm_lib = _CO_CREAQUIZ_FORM_GROUP;    break;
        case 'pageEnd'   : $this->typeForm = CREAQUIZ_TYPE_FORM_END;        $this->isParent = false; $this->isQuestion = 0; $this->canDelete = false; $this->typeForm_lib = _CO_CREAQUIZ_FORM_RESULT;   break;
        default          : $this->typeForm = CREAQUIZ_TYPE_FORM_QUESTION;   $this->isParent = false; $this->isQuestion = 1; $this->canDelete = true;  $this->typeForm_lib = _CO_CREAQUIZ_FORM_QUESTION; break;
        }

        $this->weight = $weight;
        $prefix = '_CO_CREAQUIZ_TYPE_';
        $this->name = constant($prefix . strToUpper($typeQuestion));
        $this->description = constant($prefix . strToUpper($typeQuestion) . '_DESC');
        $this->example = constant($prefix . strToUpper($typeQuestion) . '_EXAMPLE');
        $this->categoryLib = constant($prefix . 'CAT_' . strToUpper($cat));
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
	 * @public function initFormForQuestion
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function initFormForQuestion()
	{
        $this->trayGlobal = new \XoopsFormElementTray  (_AM_CREAQUIZ_PROPOSITIONS, $delimeter = '<hr>');
        
        //l'insertion de l'aide a été déplacée dans le form ded la question pour une meilleurs ergonomie
        //$this->trayGlobal->addElement($this->getSlideHelper());
	}

	/**
	 * @public function getSlideHelper
	 * @param bool $action
	 * @return \XoopsThemeForm
	 */
	public function getSlideHelper()
	{

        $trayHeader = new \XoopsFormElementTray  (_AM_CREAQUIZ_SLIDE_HELP, $delimeter = '<br>'); 
        /* pas vraiment utile tout ça, fait double emploi
        $trayHeader->addElement(new \XoopsFormLabel('', $this->name));
        $trayHeader->addElement(new \XoopsFormLabel('', $this->description));
        $trayHeader->addElement(new \XoopsFormLabel('', '<hr>'));
        */
        $trayHeader->addElement($this->getInpHelp());

        return $trayHeader;
//    echo "<hr>answers<pre>" . print_r($answers, true) . "</pre><hr>";
    
	}


	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
	public function getValuesType_question()
	{
		$creaquizHelper  = \XoopsModules\Creaquiz\Helper::getInstance();
		$utility = new \XoopsModules\Creaquiz\Utility();
        
        $ret = array();
        $ret['type'] = $this->type;
        $ret['name'] = $this->name;
        $ret['category'] = $this->category;
        $ret['obsolette'] = $this->obsolette;
        $ret['categoryLib'] = $this->categoryLib;
        $ret['short_type'] = substr($this->type, strlen($this->category)) ;
        $ret['description'] = $this->description;
        $ret['isQuestion'] = $this->isQuestion;
        $ret['canDelete'] = $this->canDelete;
        $ret['weight'] = $this->weight;
        $ret['image_fullName'] = CREAQUIZ_MODELES_IMG . "/slide_" . $this->type . '-00.jpg';
        $ret['modeles'] = array();
        for ($h = 0; $h < 3; $h++)
        {
            $f = CREAQUIZ_MODELES_IMG_PATH . "/slide_" . $this->type . "-0{$h}.jpg";
            //$ret['modeles'][] = $f;
            if(file_exists($f))
                $ret['modeles'][] = CREAQUIZ_MODELES_IMG . "/slide_" . $this->type . "-0{$h}.jpg";
        }        
        //echo "<hr>Modeles : <pre>" . print_r($ret['modeles'], true) . "</pre><hr>";
        $ret['modelesHtml'] =  $this->getHtmlImgModeles();
        return $ret;
	}
    
	/**
	 * Get Values
	 * @param null $keys 
	 * @param null $format 
	 * @param null$maxDepth 
	 * @return array
	 */
function getHtmlImgModeles($width = 80){
        
        $tImg = array();
        $tImg[] = "<div id='modelesTypeQuestionId'  name='{$this->type}' class='highslide-gallery'>";
        for ($h = 0; $h < 3; $h++)
        {
            $f = CREAQUIZ_MODELES_IMG_PATH . "/slide_" . $this->type . "-0{$h}.jpg";
            //$ret['modeles'][] = $f;
            if(file_exists($f)){
                $url = CREAQUIZ_MODELES_IMG . "/slide_" . $this->type . "-0{$h}.jpg";
                $img =  <<<___IMG___
                <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                    <img src="{$url}" alt="slides" style="max-width:{$width}px" />
                </a>
                ___IMG___;
                $tImg[] = $img;
            }
        }        
        $tImg[] = "</div>";
        //echo "<hr>Modeles : <pre>" . print_r($ret['modeles'], true) . "</pre><hr>";
        return implode("\n", $tImg);
}
    
/* **********************************************************
*
* *********************************************************** */
public function echoAns ($answers, $questId, $bExit = true) {
    
    echo "<hr>Question questId = {$questId}<pre>" . print_r($answers, true) . "</pre><hr>";
    if ($bExit) exit;         
}



/* **********************************************************
*
* *********************************************************** */
	public function getformTextarea($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $creaquizHelper;
        return \JJD\getformTextarea($caption, $name, $value, $description, $rows, $cols);
}       
        
/* **********************************************************
*
* *********************************************************** */
	public function getformAdmin($caption, $name, $value, $description = "", $rows = 5, $cols = 30) {
    global $utility, $creaquizHelper;
        return \JJD\getAdminEditor($creaquizHelper, $caption, $name, $value);
}       
        


/* **********************************************************
*
* *********************************************************** */
 	public function getName()
 	{

    $numargs = func_num_args();
    $arg_list = func_get_args();
    
    switch($numargs){
        case 1: 
            return sprintf("answers[%s]",  $arg_list[0]);
            break;
        case 2: 
            return sprintf("answers[%s][%s]",  $arg_list[0], $arg_list[1]);
            break;
        case 3: 
            return sprintf("answers[%s][%s][%s]",  $arg_list[0], $arg_list[1], $arg_list[2]);
            break;
        case 4: 
            return sprintf("answers[%s][%s][%s][%s]",  $arg_list[0], $arg_list[1], $arg_list[2], $arg_list[3]);
            break;
        default: 
            return "answers";
            break;
    }
    
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormOptions($caption, $name, $value = "")
 	{
        return null;
    }

/* **********************************************************
*
* *********************************************************** */
 	public function getFormImage($caption, $optionName, $image, $folderJS = null)
 	{
      $name = 'image';  
      $path =  CREAQUIZ_UPLOAD_QUIZ_JS . "/{$folderJS}/images";  
      //$fullName =  CREAQUIZ_UPLOAD_QUIZ_JS . "/{$folderJS}/images/" . $tValues[$name];     
      //$inpImage = $this->getXoopsFormImage($tValues[$name], "{$optionName}[{$name}]", $path, 80,'<br>');  
      $inpImage = $this->getXoopsFormImage($image, $optionName, $path, 80,'<br>', 'del_image');  
      
      return $inpImage;
    }

   
/* **********************************************************
*
* *********************************************************** */
 	public function getOptions($jsonValues, $optionsDefaults=null)
 	{
     //echo "<hr>{$jsonValues}<hr>";
       if(is_null($optionsDefaults)) $optionsDefaults = $this->optionsDefaults;
     
       if($jsonValues){
            $tValues = json_decode($jsonValues, true);
            foreach($optionsDefaults as $key=>$default){
                if(!isset($tValues[$key])) $tValues[$key] = $default;
            }
       }else if($optionsDefaults){
            $tValues = $optionsDefaults;
       }else{
            $tValues = $this->optionsDefaults;
       }

       return $tValues;
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function isQuestion()
 	{
        return ($this->isQuestion);
    }
    
/* **********************************************************
*
* *********************************************************** */
 	public function getFormType_question($typeQuestion)
 	{
	}
/* ********************************************
*
*********************************************** */
 	public function saveAnswers($answers, $questId, $quizId)
 	{global $utility, $answersHandler, $type_questionHandler;
   
    }
    
/* ********************************************
*
*********************************************** */
 	public function getInpHelp()
 	{global $xoopsConfig, $utility;
        //conteneur pour l'aide et les images
        $trayHelp = new \XoopsFormElementTray  ('', $delimeter = '');          
        //-------------------------------------------
        $fldSlideHelp = "slide_help";
        $language = $xoopsConfig['language'] ;
        $langDir = CREAQUIZ_LANGUAGE ."/{$language}/{$fldSlideHelp}";
        //echo "<hr>getInpHelp<br>{$langDir}<hr>";       
        if (!is_dir($langDir)) 
            $langDir = CREAQUIZ_LANGUAGE . "/english/{$fldSlideHelp}";
        
        $f = $langDir . "/slide_{$this->type}.html";
        //echo "<hr>getInpHelp<br>{$f}<hr>";       
        $help = \JJD\FSO\loadtextFile($f);
        $help = utf8_encode($help);
        $inpHelp = new \XoopsFormLabel  ('', $help);
        //ajout du texte dans le conteneur
        $trayHelp->addElement($inpHelp);
        
        //----------------------------------------------   
/*
        // --- Ajout de la copie d'écran du slide
        $url =  CREAQUIZ_QUIZ_JS_URL . "/quiz-php/images/slide_" . $this->type . '.jpg';
        $img =  <<<___IMG___
            <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                <img src="{$url}" alt="slides" style="max-width:40px" />
            </a>

        ___IMG___;
        $inpImg = new \XoopsFormLabel  ('', $img);  
        $inpImg->setExtra("class='highslide-gallery'");
//\JJD\include_highslide();       
*/        
        //--------------------------------
        //$inpSnapShoot = new \XoopsFormLabel  ('', 'fgdfhghk');

        $h=0;
        $tHtml = array();
        while (true){
            //$h++;
            $f =  "/modeles/slide_" . $this->type . sprintf("-%02d", $h++) . '.jpg';
            if (!file_exists(CREAQUIZ_IMAGE_PATH . $f)) break;
                $url =  CREAQUIZ_IMAGE_URL . $f;
                $img =  <<<___IMG___
                    <a href='{$url}' class='highslide' onclick='return hs.expand(this);' >
                        <img src="{$url}" alt="slides" style="max-width:40px" />
                    </a>
        
                ___IMG___;
                $inpImg = new \XoopsFormLabel  ('', $img);  
                $inpImg->setExtra("class='highslide-gallery'");
                $trayHelp->addElement($inpImg);
            
        }
        //----------------------------------------------        
        return $trayHelp;
    }
    
/* ********************************************
*
*********************************************** */
  public function color($exp, $color = null){
    if($color)
        return "<span style='color:{$color};'></span>";
    else
        return $exp;

}     
/* ********************************************
*
*********************************************** */
  public function getSolutions($questId, $boolAllSolutions = true){

    $ret = array();
 
    $ret['answers'] =  _CO_CREAQUIZ_POINTS_UNDER_DEV;
    $ret['scoreMin'] = -99999;
    $ret['scoreMax'] = 99999;
    return $ret;

}     
/* ********************************************
*
*********************************************** */
  public function combineAndSorAnswer($ans, $sep=','){
    return $this->mergeAndSortArrays ($ans['points'], $ans['proposition']);
}     
/* ********************************************
*
*********************************************** */
  public function mergeAndSortArrays($exp2sort, $expLib, $sep=','){
    $arr2sort = explode($sep, $exp2sort);
    $arrExp   =  explode($sep, $expLib);
    $ret = array();
    foreach ($arr2sort as $i=>$v){
        $key = 'p=' . str_pad($v, 3, '0', STR_PAD_LEFT);
        $ret[$key]['points'] = $v;
        $ret[$key]['exp'] = $arrExp[$i];
//         $ret[$key] = $arrExp[$i];
    }
    
//    echoArray($ret);
    krsort($ret);
//    echoArray($ret);
    
    return $ret;
}     


/* *************************************************
*
* ************************************************** */
 	public function getXoopsFormImage($imgName, $formName, $path, $maxWidth=80, $delimiter=' ', $delChkName='')
 	{ 
     //echo "getXoopsFormImage - image : {$imgName}<br>";
        global $creaquizHelper;
        $fulName = CREAQUIZ_UPLOAD_PATH . $path . "/" . $imgName;  
        $libImgName = new \XoopsFormLabel('', "[{$imgName}]");

//echo "fullName : {$fulName}<br>";
              
        if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
          $urlImg = "";
        }else{
          $urlImg = CREAQUIZ_UPLOAD_URL . $path . "/" . $imgName;
        }
        
        //affichage de l'image actuelle
        $img = new \XoopsFormLabel('', "<img src='{$urlImg}'  name='{$formName}' id='{$formName}' alt='' style='max-width:{$maxWidth}px' title={$imgName}>");


        //creation du groupe 'traiImg)'
        
        //$imageTray->addElement($img); 
//echo "<hr>urlImg : {$urlImg}<br>"; 
        
        //Selection d'un image locale dans l'explorateur
        //$upload_size = 5000000;
        $upload_size = $creaquizHelper->getConfig('maxsize_image'); 
        $inpLoadImg = new \XoopsFormFile('', $formName, $upload_size);
//echo "===>upload_size : {$upload_size}<br>";
 
        if($delChkName){
            if($urlImg){
              $delImg = new \XoopsFormCheckBox('', $delChkName);                        
              $delImg->addOption(1, _AM_CREAQUIZ_DELETE);
            }else{
              $delImg = new \XoopsFormLabel('', _AM_CREAQUIZ_NEW);                        
            }
                                     
        }else $delImg = new \XoopsFormLabel('', ''); 
 /*
               $delProposition = new \XoopsFormLabel('', _AM_CREAQUIZ_NEW);                        
              $delSubstitut = new \XoopsFormLabel('', _AM_CREAQUIZ_NEW);                        
            }else{
              $delProposition = new \XoopsFormCheckBox('', $this->getName($i,'delete_Proposition'));                        
              $delProposition->addOption(1, _AM_CREAQUIZ_DELETE);

 */       
        //-------------------------------------------------------------
        $imageTray  = new \XoopsFormElementTray('',''); 
        $imageTray->addElement($img);
        $imageTray->addElement($delImg);
        $imageTray->addElement(new \XoopsFormLabel('', '<br>'));
//        $imageTray->addElement($libImgName);// a garder pour le dev
        $imageTray->addElement($inpLoadImg);
        //$imageTray->setDescription(_AM_CREAQUIZ_IMG_DESC . '<br>' . sprintf(_AM_CREAQUIZ_UPLOADSIZE, $upload_size / 1024), '<br>');
        return $imageTray; 
     }


/* *************************************************

*************************************************** */
function save_img(&$answer, $formName, $path, $folderQuiz, $prefix, &$nameOrg=''){
global $creaquizHelper, $quizUtility;
    $nameOrg = '';
    $keyFile = array_search($formName, $_POST['xoops_upload_file']);    
    if(!$_FILES[$formName]['name']) return '';
    $savedFilename = '';
    
//echo "<hr>path : {$path}<hr>";    
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';    
    
//     $filename       = $_FILES[$chrono]['name'];
//     $imgMimetype    = $_FILES[$chrono]['type'];
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader($path , 
                                        $creaquizHelper->getConfig('mimetypes_image'), 
                                        $creaquizHelper->getConfig('maxsize_image'), null, null);
    
//---------------------------------------------------    
 //echo "save_img - chrono : {$chrono}<br>" ;  
    
    
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile])) {
        //$prefix = "quiz-{$answer['quest_id']}-{$answer['id']}";
        $uploader->setPrefix($prefix);
        $uploader->fetchMedia($_POST['xoops_upload_file'][$keyFile]);
        if (!$uploader->upload()) {
            $uploaderErrors = $uploader->getErrors();
        } else {
            $savedFilename = $uploader->getSavedFileName();
            $maxwidth  = (int)$creaquizHelper->getConfig('maxwidth_image');
            $maxheight = (int)$creaquizHelper->getConfig('maxheight_image');


            $nameOrg = $_FILES[$_POST['xoops_upload_file'][$keyFile]]['name'];       
            if($this->renameImage){
                //echo "===>savedFilename : {$savedFilename}<br>";  
                //modification du nom pour les repérer dans le dossier   
                $newName = $prefix . '-' . $quizUtility::sanitiseFileName($nameOrg);
                rename($path.'/'. $savedFilename,  $path.'/' . $newName);
                $savedFilename = $newName;
            }
            //retiir l'extension et remplace les _ par des espaces
            $h= strrpos($nameOrg,'.');
            $i=0;
            $nameOrg = str_replace('_', ' ', substr($nameOrg, $i, $h));

        }


    } else {
        //if ($filename > '') {
            $uploaderErrors = $uploader->getErrors();
        //}
        // il faut garder l'image existante si il n'y a pas eu de nouvelle selection
        // ou l'image sélectionée dans la liste
        //$slidesObj->setVar('sld_image', Request::getString('sld_image'));
        $savedFilename = '';
    }
    return $savedFilename;
}
/* *************************************************

*************************************************** */
function nameOrgParse($nameOrg, &$prefix){

    //on retire l'extension ou le prefix on met le nom de l'image
    $nameOrg = substr($ansObj->getVar('answer_proposition'), strlen($prefix)+1);
    $h= strrpos($nameOrg,'.');
    return str_replace('_', ' ', substr($nameOrg, $i, $h));

    //echo "<hr>{$nameOrg}-{$i}-{$h}|{$prefix}|{$v['caption']}<hr>";exit;

}
/* *************************************************

*************************************************** */
function save_img_old(&$answer, $path, $folderQuiz){
global $creaquizHelper;
    $chrono = $answer['chrono']-1;
    
    //le chrono ne correspond pad forcément à la clé dans files
    //il faut retrouver cette clé à patir du non du form donner dans le formumaire de saisie
    //un pour le champ "proposition" qui stocke l'image principale
    //et un pour le champ imge qui stocke l'image de substitution

    $ketFile = array_search($propositionName, $_FILES);    
    $savedFilename = '';
    
//echo "<hr>path : {$path}<hr>";    
    include_once XOOPS_ROOT_PATH . '/class/uploader.php';    
    
//     $filename       = $_FILES[$chrono]['name'];
//     $imgMimetype    = $_FILES[$chrono]['type'];
    $uploaderErrors = '';
    $uploader = new \XoopsMediaUploader($path , 
                                        $creaquizHelper->getConfig('mimetypes_image'), 
                                        $creaquizHelper->getConfig('maxsize_image'), null, null);
    
//---------------------------------------------------    
 //echo "save_img - chrono : {$chrono}<br>" ;  
    
/*
              $imgNameDef     = Request::getString('sld_short_name');
              
              //si le nom n'est pas renseigné on prend le nom du fichier image
              $shortName = Request::getString('sld_short_name', '');
              if($shortName == '') {
                if ($filename == '') $filename = Request::getString('sld_image', '');
                $posExt = strrpos($filename, '.');
                $shortName = substr($filename, 0, $posExt);
                $shortName = str_replace("_", " ", $shortName);
                $slidesObj->setVar('sld_short_name', $shortName);
              }else{
                $slidesObj->setVar('sld_short_name', $shortName);                
              }
*/
    
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][$ketFile])) {
   
        $prefix = "quiz-{$answer['inputs']}-";
        $uploader->setPrefix($prefix);
        $uploader->fetchMedia($_POST['xoops_upload_file'][$ketFile]);
        if (!$uploader->upload()) {
            $uploaderErrors = $uploader->getErrors();
        } else {
            $savedFilename = $uploader->getSavedFileName();
            $maxwidth  = (int)$creaquizHelper->getConfig('maxwidth_image');
            $maxheight = (int)$creaquizHelper->getConfig('maxheight_image');
//echo "===>savedFilename : {$savedFilename}<br>";            
            /*
            if ($maxwidth > 0 && $maxheight > 0) {
                // Resize image
                $imgHandler                = new creaquiz\Common\Resizer();
                //$endFile = "{$theme}-{$savedFilename}" ;
                
                $imgHandler->sourceFile    = SLIDER_UPLOAD_IMAGE_PATH . "/slides/" . $savedFilename;
                $imgHandler->endFile       = SLIDER_UPLOAD_IMAGE_PATH . "/slides/" . $savedFilename;
                $imgHandler->imageMimetype = $imgMimetype;
                $imgHandler->maxWidth      = $maxwidth;
                $imgHandler->maxHeight     = $maxheight;
                $result                    = $imgHandler->resizeImage();
            }
            */
            $answer['proposition'] = $savedFilename;
        }


    } else {
        //if ($filename > '') {
            $uploaderErrors = $uploader->getErrors();
        //}
        // il faut garder l'image existante si il n'y a pas eu de nouvelle selection
        // ou l'image sélectionée dans la liste
        //$slidesObj->setVar('sld_image', Request::getString('sld_image'));
    
    }
    return $savedFilename;
 } 
/* *************************************************

*************************************************** */
function delete_answer_by_image(&$answer, $path){
global $answersHandler;
//$this->echoAns ($answer,"delete_answer_by_image<br>{$path}", false);    
    if($answer['proposition'] !=''){
        $f = $path . '/' . $answer['proposition'];
        //Suppression de l'image
        //echo "{$f}<br>";
        if (file_exists($f)) {
          unlink ($f);
        }
    }
    $answersHandler->deleteId($answer['id']);
}

/* *************************************************
*
* ************************************************** */
 	public function getFormSelectImageDiv($caption, $imgName, $formName, $url, $listImg, $addNone, $maxWidth=80)
 	{ 
        global $creaquizHelper;
        
//         $fulName = $path . "/" . $imgName;        
//         if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
//           $urlImg = XOOPS_URL . "";
//         }else{
//           $urlImg = CREAQUIZ_UPLOAD_URL . $path . "/" . $imgName;
//         }
        $urlImg = $url . "/" . $imgName;
//        echo "<hr>img : {$urlImg}<hr>";
        $inpImg= new \XoopsFormSelect('', $formName, $imgName);   
        if ($addNone) $inpImg->addOption('', _AM_CREAQUIZ_NONE);
        $inpImg->addOptionArray($listImg);
        
$select = $inpImg->render();
        $libImg = new \XoopsFormLabel('', "<div style='display:inline;'><img src='{$urlImg}'  name='{$formName}-img' id='{$formName}-img' alt='' style='max-width:{$maxWidth}px' title={$imgName}><br>{$select}</div>");
        
        
        return $libImg; 
             
     
     }
     
/* *************************************************
*
* ************************************************** */
 	public function getFormSelectImage($caption, $imgName, $formName, $url, $listImg, $addNone, $maxWidth=80)
 	{ 
        global $creaquizHelper;
        
//         $fulName = $path . "/" . $imgName;        
//         if (is_null($imgName) || $imgName=='' || !is_readable($fulName)) {
//           $urlImg = XOOPS_URL . "";
//         }else{
//           $urlImg = CREAQUIZ_UPLOAD_URL . $path . "/" . $imgName;
//         }
        $urlImg = $url . "/" . $imgName;
//        echo "<hr>img : {$urlImg}<hr>";
        //$libImg = new \XoopsFormLabel('', "<img src='{$urlImg}'  name='{$formName}-img' id='{$formName}-img' alt='' style='max-width:{$maxWidth}px' title={$imgName}>");
        
        $inpImg= new \XoopsFormSelect('', $formName, $imgName);   
        if ($addNone) $inpImg->addOption('', _AM_CREAQUIZ_NONE);
        $inpImg->addOptionArray($listImg);
        
        $imageTray  = new \XoopsFormElementTray($caption,""); 
        $imageTray->addElement($inpImg);
        $imageTray->addElement($libImg);
        return $imageTray; 
             
     
     }
     
/* *************************************************
* Le nombre d'items est limié, si il y en a plus il y a eu un problème de sauvegarde.
* Il faut supprimer les enregistrements en trop
* utilisé en dev pour remettre de l'ordre
* ************************************************** */
 	public function deleteToMuchItems($arr, $maxItems)
 	{ 
        global $answersHandler;

        
        for ($h = $maxItems; $h < count($arr); $h++)
            $answersHandler->delete($arr[$h], true);
        //$answersHandler->deleteId($arr['id']);
     }

/* *************************************************
*  inititlise une table avec xoopsformTableXtray (J°J°D)
*  pour la liste des propositions
* ************************************************** */
 	public function getNewXoopsTableXtray($caption='')
 	{ 
        $tbl = new \XoopsFormTableTray($caption);
        $tbl->addStyle('line-height:2em;');
        $tbl->setOdd('background:#DFDFDF');
        $tbl->setEven('background:#7FE0F0');
        return $tbl;
     }
} // fin de la classe
