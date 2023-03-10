<?php
   /**
 * Name: modinfo.php
 * Description:
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package : XOOPS
 * @Module : 
 * @subpackage : Menu Language
 * @since 2.5.7
 * @author Jean-Jacques DELALANDRE (jjdelalandre@orange.fr)
 * @version {version}
 * Traduction:  
 */
 
defined( 'XOOPS_ROOT_PATH' ) or die( 'Accès restreint' );

//------------------------------------------------------------------
//noms des catégories de questions par objets utilisés
define('_CO_CREAQUIZ_TYPE_CAT_CHECKBOX', "Cases à cocher");
define('_CO_CREAQUIZ_TYPE_CAT_COMBOBOX', "Listes déroulantes");
define('_CO_CREAQUIZ_TYPE_CAT_IMAGESDAD', "Images Drag and Drop");
define('_CO_CREAQUIZ_TYPE_CAT_IMAGES', "Images");
define('_CO_CREAQUIZ_TYPE_CAT_LISTBOX', "Listes multi lignes");
define('_CO_CREAQUIZ_TYPE_CAT_PAGE', "Pages de début, de fin et de regroupement");
define('_CO_CREAQUIZ_TYPE_CAT_RADIO', "Boutons radios");
define('_CO_CREAQUIZ_TYPE_CAT_TEXTAREA', "Textes à comppléter");
define('_CO_CREAQUIZ_TYPE_CAT_TEXTBOX', "Zones de saisie");
define('_CO_CREAQUIZ_TYPE_CAT_UL', "Liste de libellés");

//------------------------------------------------------------------

// define('_CO_CREAQUIZ_TYPE_PNN', "<br>Chaque option est associée un nombre de points positif null ou négatif.");
// define('_CO_CREAQUIZ_TYPE_PAC', "<br>Ni la ponctuation, ni l'accentuation ni la casse ne sont prises en compte pour comparer le résultat");

define('_CO_CREAQUIZ_TYPE_PAGEINFO', "Page d'infomation");
define('_CO_CREAQUIZ_TYPE_PAGEINFO_DESC', "Ce slide a plusieurs fonctionalité: Page d'introduction, encart et résultats.<br>Le type de page est défini dans les options du slide.\"
. \"<br><b>Introduction</b> : à placer impérativement en premier, il permet de présenter le quiz.\"
. \"<br><b>Encart</b> : placé n'importe ou il permet de regroupper les questions par thème. Il faut définir dans les questions l'encart de regrouprement.<br>Le changement de poids (ordre) entraine toues les questions enfants.<br>Il peut également afficher des résultats intermédiaires\"
. \"<br><b>Résultat</b> : Obligatoirement placé à la fin, il permet d'afficher le résultat du quiz et de l'enregistrer.");
define('_CO_CREAQUIZ_TYPE_PAGEINFO_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_PAGEBEGIN', "Page de présentation du quiz");
define('_CO_CREAQUIZ_TYPE_PAGEBEGIN_DESC', "Ce slide permet de présenter le quiz; Il est obligatoire et doit être placé en premier.<br>Il est automatiquement ajouté lors de la création d'un nouveau quiz");
define('_CO_CREAQUIZ_TYPE_PAGEBEGIN_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_PAGEEND', "Page de résultat du quiz");
define('_CO_CREAQUIZ_TYPE_PAGEEND_DESC', "Ce slide permet d'afficher les résultats du quiz. il est obligatoire et doit être le dernier slide car il active le bouton de validation qui permet d'enregistrer le résultat dans la base.Il est automatiquement ajouté lors de la création d'un nouveau quiz");
define('_CO_CREAQUIZ_TYPE_PAGEEND_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_PAGEGROUP', "Page de regroupement de questions");
define('_CO_CREAQUIZ_TYPE_PAGEGROUP_DESC', "Ce slide permet de regrouper plusieur questions. Il peut être invisible. Il permet notamment de déplacer un groupe de question. s'il est visible il permet de présenter le groupe de questions.");
define('_CO_CREAQUIZ_TYPE_PAGEGROUP_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_CHECKBOXSIMPLE', "Question à réponses multiples");
define('_CO_CREAQUIZ_TYPE_CHECKBOXSIMPLE_DESC', "Ce slide est composé d'une question et de plusieurs cases à cocher.");
define('_CO_CREAQUIZ_TYPE_CHECKBOXSIMPLE_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_CHECKBOXLOGICAL', "Question de logique à réponses multiples");
define('_CO_CREAQUIZ_TYPE_CHECKBOXLOGICAL_DESC', "Ce slide est composé de deux listes :<br>- une liste de termes ayant des points communs<br>- une deuxième liste dont un seul terme possède les mêmes points communs<br>Il faut cocher toutes les options qui ont également ces points communs.");
define('_CO_CREAQUIZ_TYPE_CHECKBOXLOGICAL_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_CHECKBOXLOGICAL2', "Question de logique à réponses multiples");
define('_CO_CREAQUIZ_TYPE_CHECKBOXLOGICAL2_DESC', "Ce slide est composé de deux listes :<br>- une liste de termes ayant des points communs<br>- une deuxième liste dont un seul terme possède les mêmes points communs<br>Il faut cocher toutes les options qui ont également ces points communs.");
define('_CO_CREAQUIZ_TYPE_CHECKBOXLOGICAL2_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_LISTBOXINTRUDERS1', "Chasser les intrus");
define('_CO_CREAQUIZ_TYPE_LISTBOXINTRUDERS1_DESC', "Ce slide est composé d'une liste dans la quelle il faut supprimer les intrus.<br>Pas de retour arrière.");
define('_CO_CREAQUIZ_TYPE_LISTBOXINTRUDERS1_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_LISTBOXINTRUDERS2', "Déplacer les intrus (double liste)");
define('_CO_CREAQUIZ_TYPE_LISTBOXINTRUDERS2_DESC', "Ce slide est composé de deux listes.<br>Il faut écarter les intrus dans la deuxième liste.<br>Il est possible de corriger et de réintégrer les termes écartés.");
define('_CO_CREAQUIZ_TYPE_LISTBOXINTRUDERS2_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_COMBOBOXMATCHITEMS', "Associer les éléments deux à deux");
define('_CO_CREAQUIZ_TYPE_COMBOBOXMATCHITEMS_DESC', "Ce slide es composé d'une liste de termes à la quelle il faut associer les termes d'une autre liste<br>Chaque teme de la deuxième liste est une liste mélangée des termes de la première liste.");
define('_CO_CREAQUIZ_TYPE_COMBOBOXMATCHITEMS_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_TEXTBOXMULTIPLE', "Questions multiples à réponses multiples.");
define('_CO_CREAQUIZ_TYPE_TEXTBOXMULTIPLE_DESC', "Ce slide est composé d'une ou plusieurs questions.<br>Chaque question peut avoir plusieurs réponses saisissables.");
define('_CO_CREAQUIZ_TYPE_TEXTBOXMULTIPLE_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_TEXTBOXMATCHITEMS', "Associer les éléments deux à deux");
define('_CO_CREAQUIZ_TYPE_TEXTBOXMATCHITEMS_DESC', "Ce slide es composé d'une liste de termes à la quelle il faut faire correspondre un terme à saisir.");
define('_CO_CREAQUIZ_TYPE_TEXTBOXMATCHITEMS_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_RADIOLOGICAL', "Question de logique à réponse unique");
define('_CO_CREAQUIZ_TYPE_RADIOLOGICAL_DESC', "Ce slide est composé de deux listes :<br>- une liste de termes ayant des points communs<br>- une deuxième liste dont un seul terme possède les mêmes points communs");
define('_CO_CREAQUIZ_TYPE_RADIOLOGICAL_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_RADIOMULTIPLE2', "Retrouver les termes qui vont ensemble");
define('_CO_CREAQUIZ_TYPE_RADIOMULTIPLE2_DESC', "Ce slide est composé de plusieurs liste avec un nombre identique de termes.<br>Il faut retouver les termes qui vont ensemble.<br>Plusieurs solutions peuvent être proposées avec un nombre de points différent.");
define('_CO_CREAQUIZ_TYPE_RADIOMULTIPLE2_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_RADIOSIMPLE', "Question à réponse unique");
define('_CO_CREAQUIZ_TYPE_RADIOSIMPLE_DESC', "Ce slide est composé de plusieurs réponses dont une seule peut être choisie.");
define('_CO_CREAQUIZ_TYPE_RADIOSIMPLE_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_COMBOBOXSORTLIST', "Trier une liste multiple");
define('_CO_CREAQUIZ_TYPE_COMBOBOXSORTLIST_DESC', "Ce slide est composé de plusieurs liste constitué des même termes.<br>Il faut indiquer l'ordre précicé dans la question<br>L'ordre peut être inverse si l'option a été définie.");
define('_CO_CREAQUIZ_TYPE_COMBOBOXSORTLIST_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_TEXTAREASIMPLE', "Corriger le texte");
define('_CO_CREAQUIZ_TYPE_TEXTAREASIMPLE_DESC', "Ce slide est composé d'une zone de texte qu'il faut corriger directement.");
define('_CO_CREAQUIZ_TYPE_TEXTAREASIMPLE_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_TEXTAREAINPUT', "Saisir les mots manquants");
define('_CO_CREAQUIZ_TYPE_TEXTAREAINPUT_DESC', "Ce slide est composé d'une zone de texte non saisissable et de plusieurs zones saisissables.<br>Il faut saisir les mots à remplacer dans le texte.<br>Les termes dans le texte ont été remplacé par des numéros entre accolades : {1} {2} {3} ... ");
define('_CO_CREAQUIZ_TYPE_TEXTAREAINPUT_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_TEXTAREALISTBOX', "Retrouver les mots manquants");
define('_CO_CREAQUIZ_TYPE_TEXTAREALISTBOX_DESC', "Ce slide est composé d'un texte et de pluseurs listes de mots.<br>Les mots manquants on été remplacéer par des numéros entre accolades : {1} {2} {3} ... <br>Chaque liste est constituées des mots a retrouver.<br>Il est possible d'ajouter des mots étrangés au texte");
define('_CO_CREAQUIZ_TYPE_TEXTAREALISTBOX_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_LISTBOXSORTITEMS', "Trier une listbox");
define('_CO_CREAQUIZ_TYPE_LISTBOXSORTITEMS_DESC', "Ce slide est composé d'une liste et de boutons de déplacement.<br>Le tri peut être inverse.");
define('_CO_CREAQUIZ_TYPE_LISTBOXSORTITEMS_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESLOGICAL', "Trouver l'image manquante");
define('_CO_CREAQUIZ_TYPE_IMAGESLOGICAL_DESC', "Ce slide est composé d'une séquence d'images dont une ou plusieurs sont masquées.<br>Il faut reconstituer la séquence correcte.");
define('_CO_CREAQUIZ_TYPE_IMAGESLOGICAL_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESSIMPLE', "Trouver les images de la même famille");
define('_CO_CREAQUIZ_TYPE_IMAGESSIMPLE_DESC', "Ce slide est composé d'images dont une ou plusieurs sont masquées.<br>Il faut retrouver les images de la même famille.");
define('_CO_CREAQUIZ_TYPE_IMAGESSIMPLE_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESDADSORTITEMS', "Ordonner les images");
define('_CO_CREAQUIZ_TYPE_IMAGESDADSORTITEMS_DESC', "Ce slide est composé d'images qu'il faut classer dans le bon ordre.");
define('_CO_CREAQUIZ_TYPE_IMAGESDADSORTITEMS_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESDADLOGICAL', "Trouver les images manquantes");
define('_CO_CREAQUIZ_TYPE_IMAGESDADLOGICAL_DESC', "Ce slide est composé d'une séquence d'images dont une ou plusieurs sont masquées.<br>Il faut reconstituer la séquence correcte en déplaçant celles-ci avec la souris");
define('_CO_CREAQUIZ_TYPE_IMAGESDADLOGICAL_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESDADMATCHITEMS', "Replacer les images à leure place");
define('_CO_CREAQUIZ_TYPE_IMAGESDADMATCHITEMS_DESC', "Ce slide est composé d'images et de titres. Il faut replacer les images sur lrs bon titres.");
define('_CO_CREAQUIZ_TYPE_IMAGESDADMATCHITEMS_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESDADGROUPS', "Regrouper les images");
define('_CO_CREAQUIZ_TYPE_IMAGESDADGROUPS_DESC', "Ce slide est composé de deux à quatre groupes d'images mélangées qu'il faut placer au bon endroit.");
define('_CO_CREAQUIZ_TYPE_IMAGESDADGROUPS_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_IMAGESDADBASKET', "Distribuer les images");
define('_CO_CREAQUIZ_TYPE_IMAGESDADBASKET_DESC', "Ce slide est composé de deux à quatre groupes d'images.<br>toutes les image sont dans le groupe 0 qu'il faut replacer au bon endroit.");
define('_CO_CREAQUIZ_TYPE_IMAGESDADBASKET_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_ULSORTLIST', "Trier une liste de termes");
define('_CO_CREAQUIZ_TYPE_ULSORTLIST_DESC', "Ce slide est composé d'une liste qu'il faut trier.<br>Le tri peut être inverse.");
define('_CO_CREAQUIZ_TYPE_ULSORTLIST_EXAMPLE', "");

define('_CO_CREAQUIZ_TYPE_ULDADGROUPS', "Répartir les propositions dans les groupes");
define('_CO_CREAQUIZ_TYPE_ULDADGROUPS_DESC', "Ce slide est composé de trois groupes dans lesquels il faut répartir les propositions.");
define('_CO_CREAQUIZ_TYPE_ULDADGROUPS_EXAMPLE', "");



?>