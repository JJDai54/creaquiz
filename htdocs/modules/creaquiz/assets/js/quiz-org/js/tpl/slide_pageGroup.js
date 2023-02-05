
 /*******************************************************************
  *                     _Group
  * *****************************************************************/

class pageGroup extends quizPrototype{
name = "pageGroup";

//---------------------------------------------------
build (){
var currentQuestion=this.question;
var name = this.getName();

      const answers = [];

    if(currentQuestion.image){
        var imageMain = `<div><img src="${ quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></div>`;
        answers.push(imageMain);
    }

      for(var k in currentQuestion.answers){
        var id = this.getId(k);
        if(currentQuestion.answers[k].proposition == '') continue;
            var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition);
            answers.push(
                `<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>${exp}</div>
                `);
          
      }
      
      //pour que l'ombre du bas du dernuer texte ne soit pas oupé, un padding serait peut être mieux
      answers.push('<br>'); 
//       if(this.typeForm == 3){
//           answers.push(this.buildFormSubmitAnswers());
//       }
//alert(answers);
      return answers.join("\n");

  }

//---------------------------------------------------
/*
buildFormSubmitAnswers(){
    var tNamesId = ['quiz_id', 'uid', 'answers_total', 'answers_achieved', 
                    'score_achieved', 'score_max', 'score_min', 'duration'];
                 
    var tHtml = []
    
    tHtml.push(`<form name="form_submit_creaquiz" id="form" action="/modules/creaquiz/results_submit.php?op=submit_answers" method="post">`);
    
    for (var h = 0; h < tNamesId.length; h++){
        tHtml.push(`<input type="hidden" name="${tNamesId[h]}" id="${tNamesId[h]}" value="0" />`);
    }
    tHtml.push(`</form>`);
    
    
    return "\n" + tHtml.join("\n") + "\n";
}  
*/

//---------------------------------------------------
isQuestion (){
              
    return false;         
}

//---------------------------------------------------
  getScoreByProposition (answerContainer){
    return 0;
  }
  
//---------------------------------------------------
  isInputOk(currentQuestion, answerContainer,chrono){
    return false;
  }
  
//---------------------------------------------------
  getAllReponses  (currentQuestion){
      return "";
  }
  
//---------------------------------------------------
  getGoodReponses (currentQuestion){
      return '';
  }
  
  
 
//---------------------------------------------------
  update(nameId, chrono) {
  }
  
//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions;
  } 
  
//---------------------------------------------------
reloadQuestion()
  {
    var currentQuestion = this.question;
    for(var k in currentQuestion.answers){
      //if(currentQuestion.answers[k].proposition == '') continue;
      var id = this.getId(k);
      var obDiv = document.getElementById(id);
      if(!obDiv) continue;
      obDiv.innerHTML = replaceBalisesByValues(currentQuestion.answers[k].proposition);
    }
  } 
  


} // ----- fin de la classe ------
