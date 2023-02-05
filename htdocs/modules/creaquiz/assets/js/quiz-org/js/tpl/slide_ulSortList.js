

 /*******************************************************************
  *                     _slide_ulSortList
  * *****************************************************************/

class ulSortList extends quizPrototype{
name = "ulSortList";

/* *************************************
*
* ******** */
build (){
    return this.getInnerHTML();
    this.boolDog = true;
 }
 
/* *************************************
*
* ******** */
getInnerHTML(){
/*
  
<div style='width:50%;text-align:center;font-size:1.2em;'>
    <!-- (B) THE LIST -->
    <ul id="sortlistzzz" class="sortlist">
      <li>First</li>
      <li>Second</li>
      <li>Third</li>
      <li>Forth</li>
      <li>Fifth</li>
    </ul>
</div>  

*/
    var  currentQuestion = this.question;
    var name = this.getName();
    var id = this.getName();
    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }

    const html = [];
    html.push(`<center><div style='width:50%;text-align:center;font-size:1.2em;'>`);
    html.push(`<ul id="${id}" >`);
    
    for (var j=0; j < tItems.length; j++){
        html.push(`<li>${tItems[j]}</li>`);
    }    
    
    html.push(``);
    html.push(`</ul>`);
    html.push(`</div></center>`);
/*
    <!-- (C) CREATE SORTABLE LIST -->
    <script>
    </script>
*/    
    return html.join("\n");
}
//---------------------------------------------------
initSlide (){
    //alert ("===> initSlide : " + this.question.typeQuestion  + " - " + this.question.question + " \n->" + this.getName());
    quiz_init_slist(document.getElementById(this.getName()));
    return true;
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
    var id = this.getName();
    
    var listObj = document.getElementById(id);
    var tItems = this.data.words;

    var options = listObj.getElementsByTagName("li"), current = null;
  
    var tRep = [];
    for (var i = 0; i < options.length ; i++) {
        //alert("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        this.blob("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        tRep.push(options[i].innerHTML) 
    }
    
    /*
    var strRep = tRep.join(',');
    bolOk = (strRep == currentQuestion.answers[0].proposition);
    console.log("getScore\n" + strRep + "\n" + currentQuestion.answers[0].proposition);
//        alert(currentQuestion.options.toUpperCase() );

    if(!bolOk && currentQuestion.options.orderStrict == "R"){
        tRep.reverse();
        var strRep = tRep.join(',');
        //alert("inver : " + strRep);
        bolOk = (strRep == currentQuestion.answers[0].proposition);
    }
    
    */
    var bolOk = this.isListSorted(tRep);
    return (bolOk) ? currentQuestion.points : 0;
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
  
//--------------------------------------------------------------------------------
reloadQuestion() {
    var currentQuestion = this.question;
    var id = this.getName();
    var listObj = document.getElementById(id);
    var tItems = shuffleArray(this.data.words);
    while(this.isListSorted(tItems)){
        tItems = shuffleArray(this.data.words);
    }
    
    var options = listObj.getElementsByTagName("li"), current = null;
    
    for (var i = 0; i < options.length ; i++) {
        //alert("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        this.blob("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        options[i].innerHTML = tItems[i];
    }
}
 
/* ************************************
*
* **** */
showGoodAnswers()
  {
    var currentQuestion = this.question;
    var id = this.getName();
    var listObj = document.getElementById(id);
    var tItems = this.data.words;
    
    var options = listObj.getElementsByTagName("li"), current = null;
    
    for (var i = 0; i < options.length ; i++) {
        //alert("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        this.blob("===> getScore-listSortItems : " + options[i].innerHTML + " == " + i + " => " + tItems[i]);
        options[i].innerHTML = tItems[i];
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
