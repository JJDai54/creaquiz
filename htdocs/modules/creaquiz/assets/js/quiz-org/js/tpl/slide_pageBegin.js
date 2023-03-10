
 /*******************************************************************
  *                     _Begin
  * *****************************************************************/

class pageBegin extends quizPrototype{
name = "pageBegin";

//---------------------------------------------------
build (){
var currentQuestion=this.question;
var name = this.getName();

      const answers = [];
      
    if(currentQuestion.image){
        var imageMain = `<div><img src="${ quiz_config.urlQuizImg}/${currentQuestion.image}" alt="" title="" height="${currentQuestion.options.imgHeight}px"></div>`;
        answers.push(imageMain);
    }
      
      //si l'utilisateur n'est pas connecté on lui demande de saisir un pseudo
      if ( quiz_rgp.isAnonymous){
          var id = this.getId('pseudo');
          answers.push(
            `<div class="quiz-shadowbox "  style='width:90%;' disabled>
            <center>${quiz_messages.notConnected}</center><br> 
            ${quiz_messages.inputYourPseudo} : <input type="text" id="quiz_pseudo" name="quiz_pseudo" oninput="quiz_input_pseudo_event(event, '${id}');">
            </div>`);
      }
      
      for(var k in currentQuestion.answers){
        var id = this.getId(k);
        if(currentQuestion.answers[k].proposition == '') continue;
          var exp = replaceBalisesByValues(currentQuestion.answers[k].proposition);
          answers.push(
            `<div id="${id}" name="${name}" class="quiz-shadowbox "  style='width:90%;' disabled>${exp}</div>
        `);
          
      }

      answers.push(`<br><button id="quiz_btn_startQuiz"  name="quiz_btn_startQuiz" class="${quiz_css.buttons}" style="font-size:1.8em;visibility: visible; display: inline-block;z-index:9999;">${quiz_messages.btnStartQuiz}</button>`);
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


function quiz_input_pseudo_event(ev, id) {
//alert("quiz_input_pseudo_event : " + id + "\n" + ev.currentTarget.id + "\n" + ev.currentTarget.value);
    var pseudo = ev.currentTarget.value;
    var btn = document.getElementById('quiz_btn_startQuiz');
    
    if (pseudo == 'Anonymous' || pseudo.length < 5){    
        //alert('pas ok - pseudo = ' + pseudo);    
        //theQuiz.enableButton (btnStartQuiz, 0, true);
        quiz_rgp.uname = pseudo;        
        
        btn.style.display = 'inline-block';
        btn.style.visibility="visible";
        btn.disabled = 'disabled';
        
    }else{
        //alert('ok - pseudo = ' + pseudo);    
        //enableButton (btnStartQuiz, 1, true);
        quiz_rgp.uname = pseudo;   
        quiz_rgp.isAnonymous = false;       
              
        btn.style.visibility="visible";
        btn.style.display = 'inline-block';
        btn.disabled = '';
    }

}


