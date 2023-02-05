
/************************************************************************
 *                 _listboxIntruders2
 * **********************************************************************/

class listboxIntruders2 extends quizPrototype{
name = "listboxIntruders2";

/* *************************************
*
* ******** */
build (){

    var  currentQuestion = this.question;
    const tHtml = [];

    var keys = this.shuffleArray(Object.keys(this.data.keyWords));
    var name = this.getName();
    var click = (quiz.onClickSimple) ? "onclick" : "ondblclick";
    var wordsList = keys.join(",");

    if (currentQuestion.options.repartition.toLowerCase() == "m"){
        var arr =  sliceArray(keys);
        var leftArray = arr.arr1; 
        var RightArray = arr.arr2; 
    }else{
        var leftArray = keys; 
        var RightArray = []; 
    }
         
var caption1 = ((currentQuestion.options.title1 != '') ? currentQuestion.options.title1 :  quiz_messages.goodAnswers);
var caption2 = ((currentQuestion.options.title2 != '') ? currentQuestion.options.title2 :  quiz_messages.badAnswers);
    //--------------------------------------------------------------------------------
    var id1 = `${name}-1`;
    var id2 = `${name}-2`;
    tHtml.push(`<table class="question">`);
    //tHtml.push(`<tr><td>${quiz_messages.goodAnswers}</td><td>${quiz_messages.badAnswers}</td><tr><td>`);
    tHtml.push(`<tr><td>${caption1}</td><td>${caption2}</td><tr><td>`);

    var extra = `${click}="quiz_basculeValue('${id1}','${id2}');"`;
    tHtml.push(getHtmlListbox(name, id1, leftArray, keys.length, -1, currentQuestion.numbering, 0, extra));
    
    tHtml.push(`</td><td>`);
    
    var extra = `${click}="quiz_basculeValue('${id2}','${id1}');"`;
    tHtml.push(getHtmlListbox(name, id2, RightArray, keys.length, -1, currentQuestion.numbering, 0, extra));
    
    //-------------------------------------------------------'

   tHtml.push(`</td></tr></table>`);


    return tHtml.join("\n");
 }
/* *************************************
*
* ******** */
prepareData(){
var tKeyWords = [];
    var currentQuestion = this.question;
//alert("caption = " + currentQuestion.answers[0].caption);
        
     for (var k=0; k < 1; k++){
       var tw = currentQuestion.answers[k].proposition.split(",");  
       var tp = padStr2Array(currentQuestion.answers[k].points, tw.length);    
 
       currentQuestion.answers[k].words = tw;  
       
       for (var h=0; h < tw.length; h++){
           tKeyWords[tw[h]] = tp[h]*1;
       }
     }
     
     this.data.keyWords = tKeyWords;  
     currentQuestion.answers[0].keys = Object.keys(tKeyWords);  
    
} 
/* *************************************
*
* ******** */
computeScoresMinMaxByProposition(){

    var currentQuestion = this.question;
    var tKeyWords = this.data.keyWords;
    
    for(var key in tKeyWords)
    {
        if (tKeyWords[key]*1 > 0) this.scoreMaxiBP += tKeyWords[key]*1;
        if (tKeyWords[key]*1 < 0) this.scoreMiniBP += tKeyWords[key]*1;
    }
     return true;
 }


/* *************************************
*
* ******** */

getScore ( answerContainer){
var points = 0;
var bolOk = true;

    var currentQuestion = this.question;

    var id1 = `${this.getName()}-1`;
    var ob1 = getObjectById(id1);
    
    //recupe des items restants
    var tOptions = ob1.options;
    var tSelected = [];
    for(var i = 0; i < tOptions.length; i++) {
        tSelected.push(tOptions[i].value);
   }
    
    //tout a été sélectioné c'est comme si rien avit été sélectioné, renvois 0
    if (tOptions.length === 0) return 0;
    //si aucun element selectionné renvoi 0 point    
    if (tOptions.length === currentQuestion.answers[0].keys.length) return 0;
       //-----------------------------------

    //recheche des élément absent de la list
    var tKeyWords = this.data.keyWords;
      for(var key in tKeyWords)
      {
        if (tSelected.indexOf(key) == -1){
            points += tKeyWords[key]*1;
        }
        
      }
       
      return points;

  }

/* *************************************
*
* ******** */
isInputOk ( answerContainer){
    var currentQuestion = this.question;

var bolOk = true;

    var id1 = `${this.getName()}-1`;
    var ob1 = getObjectById(id1);

       var tOptions = ob1.options;
       var minReponses = currentQuestion.options.minReponses;
       var nbRep = currentQuestion.answers[0].keys.length - tOptions.length;

       if (minReponses == 0 && nbRep > 0){
         bolOk = (nbRep > 0);
       }else{
         bolOk = (nbRep >= minReponses);
       }



      return bolOk;

 }

/* *************************************
*
* ******** */

/* *************************************
*
* ******** */

getAllReponses (flag = 0){
    var currentQuestion = this.question;
    
    var tReponses = [];
    var tKeyWords = this.data.keyWords;
    
    
    //tri desc sur le tableau
    tKeyWords = sortArrayKey(tKeyWords,"d");
    
     
    for(var key in tKeyWords)
    {
        //tReponses.push (`${key} ===> ${tKeyWords[key]} points`) ;       
        tReponses.push ([key, tKeyWords[key]]) ;       
    }

    return formatArray0(sortArrayArray(tReponses, 1, "DESC"), "=>");
 }

//---------------------------------------------------
update(nameId, chrono) {
}


//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 
 
//--------------------------------------------------------------------------------
reloadQuestion() {
    if (this.question.options.repartition.toLowerCase() == "m")
        this.reloadQuestion2();
    else
        this.reloadQuestion1();
}

//--------------------------------------------------------------------------------
reloadQuestion1() {
    var currentQuestion = this.question;
    var name = this.getName();
    var id1 = `${name}-1`;
    var ob1 = document.getElementById(id1);
    ob1.innerHTML = "";

    var tKeyWords = this.shuffleArrayKeys(this.data.keyWords);
    for(var key in tKeyWords)
    {
        // this.blob(key + " = " +  tKeyWords[key]);
        var option = document.createElement("option");
        option.text = key;
        option.value = key;
        ob1.add(option);
    }
    
    //--------------------------------
    //vidage de le 2eme liste
    var id2 = `${name}-2`;
    var ob2 = document.getElementById(id2);
    ob2.innerHTML = "";
}

//--------------------------------------------------------------------------------

reloadQuestion2() {
    var currentQuestion = this.question;
    var name = this.getName();

    var keys = this.shuffleArray(Object.keys(this.data.keyWords));
    var arr =  sliceArray(keys);
    var leftArray = arr.arr1; 
    var RightArray = arr.arr2; 
    
    var id1 = `${name}-1`;
    var ob1 = document.getElementById(id1);
    ob1.innerHTML = "";
    for(var j=0; j<leftArray.length; j++)
    {
        var option = document.createElement("option");
        option.text = leftArray[j];
        option.value = leftArray[j];
        ob1.add(option);
    }
    
    
    var id2 = `${name}-2`;
    var ob2 = document.getElementById(id2);
    ob2.innerHTML = "";
    for(var j in RightArray)
    {
        var option = document.createElement("option");
        option.text = RightArray[j];
        option.value = RightArray[j];
        ob2.add(option);
    }
    
}

  
/* ************************************
*
* **** */
showGoodAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();

    var id1 = `${name}-1`;
    var ob1 = document.getElementById(id1);
    ob1.innerHTML = "";
    
    var id2 = `${name}-2`;
    var ob2 = document.getElementById(id2);
    ob2.innerHTML = "";
    
    
    var tKeyWords = this.data.keyWords;
    for(var key in tKeyWords)
    {
        // this.blob(key + " = " +  tKeyWords[key]);
        var option = document.createElement("option");
        option.text = key;
        option.value = key;
        if (tKeyWords[key] > 0){
          ob2.add(option);
        }else{
          ob1.add(option);
        }
    }

    
}
  
/* ************************************
*
* **** */
showBadAnswers()
  {
    var currentQuestion = this.question;
    var name = this.getName();

    var id1 = `${name}-1`;
    var ob1 = document.getElementById(id1);
    ob1.innerHTML = "";
    
    var id2 = `${name}-2`;
    var ob2 = document.getElementById(id2);
    ob2.innerHTML = "";
    
    
    var tKeyWords = this.data.keyWords;
    for(var key in tKeyWords)
    {
        // this.blob(key + " = " +  tKeyWords[key]);
        var option = document.createElement("option");
        option.text = key;
        option.value = key;
        if (tKeyWords[key] > 0){
          ob1.add(option);
        }else{
          ob2.add(option);
        }
    }

    
}
 
} // ----- fin de la classe ------
