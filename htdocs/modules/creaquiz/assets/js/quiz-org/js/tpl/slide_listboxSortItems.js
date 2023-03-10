

 /*******************************************************************
  *                     _listboxSortItems
  * *****************************************************************/

class listboxSortItems extends quizPrototype{
name = "listboxSortItems";

/* *************************************
*
* ******** */
build (){
    return this.getInnerHTML();
 }
 
/* *************************************
*
* ******** */
getInnerHTML(){
    var  currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;

var prefix = this.getName() + '_';   
var tpl=`<style>
.${prefix}quizBtn01{
    height:${currentQuestion.options.btnHeight}px;
	border:none;
    margin-top:10px;
}
.${prefix}quizBtn01:hover{
    -moz-transform: translate(+3px, +3px);
    -ms-transform: translate(+3px, +3px);
    -o-transform: translate(+3px, +3px);
    -webkit-transform: translate(+3px, +3px);
    transform: translate(+3px, +3px);
    -webkit-filter: brightness(1.1);
    -moz-filter: brightness(1.1);
    -o-filter: brightness(1.1);
    -ms-filter: brightness(1.1);    
}
</style><center>
<table style="text-align: right; width: 50%;border-style:none;" border="0" cellpadding="2"
cellspacing="2">
<tbody>
<tr>
<td colspan="1" rowspan="3" style="vertical-align: top;width:40px;">{listeItems}</td>
<td style="vertical-align: middle;width:180px;">{btn0}<br>{btn1}<br>{btn2}<br>{btn3}<br></td>
</tr>
</tbody>
</table></center>`;

//alert ("listboxSortItems");
//var tplButton = `<input type="button" class="${prefix}quizBtn01" style="background:url('../images/arrows/blue/btn_{moveTo}.png');" onclick="quiz_MoveItemTo('{id}','{moveTo}');">`;
var tplButton = `<img class="${prefix}quizBtn01" src="../images/buttons/${currentQuestion.options.btnColor}/btn_{moveTo}.png" onclick="quiz_MoveItemTo('{id}','{moveTo}');">`;

    var btn0 = tplButton.replaceAll('{moveTo}','top').replace('{id}',id); 
    var btn1 = tplButton.replaceAll('{moveTo}','up').replace('{id}',id); 
    var btn2 = tplButton.replaceAll('{moveTo}','down').replace('{id}',id); 
    var btn3 = tplButton.replaceAll('{moveTo}','bottom').replace('{id}',id); 
    
    var tblHtml = tpl.replace('{btn0}', btn0).replace('{btn1}', btn1).replace('{btn2}', btn2).replace('{btn3}', btn3);
     
    const answers = [];
    answers.push(tblHtml);
//alert(tblHtml);    
    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }
    
    //alert(tItems);
    var click = (quiz.onClickSimple) ? "onclick" : "ondblclick";
    //var wordsList = tItems.join(",");
    //-------------------------------------
    var extra = ''; //`${click}="quiz_deleteValue('${id}');"`;
    var listItems = getHtmlListbox(name, id, tItems, tItems.length, -1, currentQuestion.numbering, 0, extra);
    var html = tblHtml.replace('{listeItems}', listItems);
    return html;
}  
/* *************************************
*
* ******** */
prepareData(){
var tItems = [];
    var currentQuestion = this.question;
/*
    if(!currentQuestion.options){
        currentQuestion.options.btnHeight}px;
    }
*/    
    
    //on force l'option de mélange des options sinon aucun intéret
    //currentQuestion.shuffleAnswers = 1;

    var k = 0;
    //alert(currentQuestion.answers[k].proposition);
    this.data.words = currentQuestion.answers[k].proposition.split(",");  
} 

/* *************************************
*
* ******** */
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
    
    this.scoreMaxiBP = currentQuestion.points;
    this.scoreMiniBP = 0;
    
     return true;
 }
 
/* *************************************
*
* ******** */

getScore ( answerContainer){
var bolOk = true;

    var currentQuestion = this.question;
    var id = `${this.getName()}-1`;
    
    var listObj = document.getElementById(id);
    var tItems = this.data.words;

    var options = listObj.getElementsByTagName("OPTION");

    var tRep = [];
    for (var i = 0; i < options.length ; i++) {
        this.blob("===> getScore-listSortItems : " + options[i].text + " == " + i + " => " + tItems[i]);
        tRep.push(options[i].text) 
    }
    var strRep = tRep.join(',');
    bolOk = strRep == currentQuestion.answers[0].proposition;
    
//        alert(currentQuestion.options.toUpperCase() );

    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == currentQuestion.answers[0].proposition);
    }

    return (bolOk) ? currentQuestion.points : 0;
  }

 
/* *************************************
*
* ******** */

getAllReponses (flag = 0){
      var  currentQuestion = this.question;


    var tReponses = [];
    var k = 0; 
    var t = [];
    for(var k in this.data.words){
        t.push ([k*1+1, this.data.words[k]]);
    }

    return formatArray0(t,"-","");
 }

/* *************************************
*
* ******** */
isListSorted(tRep){
    var  currentQuestion = this.question;
    
    var strRep = tRep.join(',');
    var bolOk = (strRep == currentQuestion.answers[0].proposition);
    
    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == currentQuestion.answers[0].proposition);
    }
    return bolOk;
    
}

/* *************************************
*
* ******** */

incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 

//--------------------------------------------------------------------------------
reloadQuestion() {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";

    var tItems = this.shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }
    for(var key in tItems){
        this.blob(key + " = " +  tItems[key]);
        var option = document.createElement("option");
        //alert(tItems[key]);
        option.text = tItems[key];
        option.value = tItems[key];
        ob.add(option);
    }
    ob.selectedIndex = 0;
}
 
/* ************************************
*
* **** */
showGoodAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();
    var id = `${name}-1`;
    var ob = document.getElementById(id);
    ob.innerHTML = "";


    var tItems = this.data.words;
    for(var key in tItems)
    {
    //alert(`showGoodAnswers - ${key} = ${tItems[key]}`);
        this.blob(key + " = " +  tItems[key]);

          var option = document.createElement("option");
          option.text = tItems[key];
          option.value = tItems[key];
          ob.add(option);

    }
}

/* ************************************
*
* **** */
showBadAnswers()
  {
    this.reloadQuestion();
}
  
 
  
 
} // ----- fin de la classe ------
