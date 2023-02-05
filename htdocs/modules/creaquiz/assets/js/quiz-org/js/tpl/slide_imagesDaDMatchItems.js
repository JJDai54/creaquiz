
 /*******************************************************************
  *                     _imagesDaDMatchItems
  * *****************************************************************/
class imagesDaDMatchItems extends quizPrototype{
name = 'imagesDaDMatchItems';
  
/* ***************************************
*
* *** */
build (){
    var currentQuestion = this.question;
    var name = this.getName();
    this.boolDog = true;
    
    
    const answers = [];
    answers.push(`<div id="${name}">`);
    answers.push(this.getInnerHTML());
    answers.push(`</div>`);
    
    
//    this.focusId = name + "-" + "0";
    //alert (this.focusId);

    return answers.join("\n");

 }

/* ************************************
*
* **** */
 reloadQuestion() {
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML();
    return true;
}

/* ************************************
*
* **** */
getInnerHTML(bSolution = false){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
    var tpl = "<div style='border: none;text-align:left;'>"
            + `<div class='imagesLogical'>{sequence}</div><hr>${quiz_messages.message02}</div>`;
    var tpl =`<div id="${this.getId('img')}" class='myimg0' style='border: none;text-align:left;'>\n{sequence}\n</div>`;
                
//     var tpl = "<style>.imagesLogical{border: none;text-align:left;}</style>"
//             + "<div class='imagesLogical'>{sequence}</div><div class='imagesLogical'>{suggestion}</div>";
    //var imgHeight = 64;   
    var tHtmlSequence = [];
    var tHtmlSuggestion = [];
    var tHtmlSubstitut = [];
    
    var tImgSequence = [];
    var img = '';
//alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);   
 
var eventImgToStyle=`
style="height:${currentQuestion.options.imgHeight1}px;"
`;

 var eventImg=`
style="height:${currentQuestion.options.imgHeight1}px;cursor: grab;"
onDragStart="dad_start(event);"
onDragOver="return dad_over(event);" 
onDrop="return dad_drop(event,0);"
onDragLeave="dad_leave(event);"
`;
//onmouseover="testMouseOver(event);"
//onclick="testOnClick(event);"   
var eventImgFrom=`
style="height:${currentQuestion.options.imgHeight1}px;cursor:pointer;"    
onDragStart="dad_start(event);"
`;
var caption;
var src;  
var img;   
tHtmlSequence.push('<table width="100%" style="font-size:0.8em;"><tr>');    
    
    if(bSolution){
      var tCaptions = this.data.solution;
      var tImages   = this.data.solution;
    }else{
      var tCaptions = shuffleArray(currentQuestion.answers);
      // pour etre sur qu'il ny ai pas déjà uneimage à sa place
      do {
          var tImages   = shuffleArray(currentQuestion.answers);
      }
      while (this.compare(tCaptions, tImages));      
    }
    for(var k in tImages){
            src = `${quiz_config.urlQuizImg}/${tImages[k].proposition}`;
            caption = tCaptions[k].caption.replace(' ','<br>'); 
            img = `<td style='text-align:center;'>`
                + `<img id="${tImages[k].id}" src="${src}" class='myimg1' title="" alt="zzz" ${eventImg}>`
                + `<div>${caption}</div></td>`;  
                      
            //alert(`newline : ${k}-${(k*1+1)}-${((k*1+1) % 4)}`);
            if (((k*1+1) % 5) == 0) {
                img += '</tr><tr>';
            }

            tHtmlSequence.push(img);
    }
    this.data.solution = tCaptions;
tHtmlSequence.push('</tr></table>');    
    //--------------------------------------------------------------
    
    
    
    
    
    //---------------------------------------------------------------------
    tpl=tpl.replace('{sequence}', tHtmlSequence.join("\n"));
    return tpl;
}
compare(tCaptions, tImages){
var idem = 0;
     for(var k = 0; k < tCaptions.length; k++)
        {
          if(tCaptions[k].proposition == tImages[k].proposition) {idem++};
        }
    return idem;
}
//---------------------------------------------------

 prepareData(){
    
    var tImages = [];
    var tCaptions = [];
    
  
    var currentQuestion = this.question;
    
    this.data.urlCommonImg = quiz_config.urlCommonImg;
    
    for(var k in currentQuestion.answers){
    //for(var k = 0; k < currentQuestion.answers.length; k++){
        currentQuestion.answers[k].id = this.getId(k);
        blob(currentQuestion.answers[k].proposition + '/prepareData/' + currentQuestion.answers[k].id);
    }   

}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
    this.blob("getScoreByProposition --------------------------");
    var obs = document.querySelectorAll(`#${this.getId("img img")}`);
    //var obs = document.querySelectorAll(this.getId("img") + " img");
        
    var currentQuestion = this.question;
//alert('showGoodAnswers - solution.length: ' + this.data.solution.length);
      for(var k = 0; k < this.data.solution.length; k++){
        var ans = this.data.solution[k];
        if (ans.points*1 > 0){
          //var obImg = document.getElementById(ans.id);
          var obImg = obs[k];
          //this.blob(obImg.getAttribute('src') + "\n" + `${quiz_config.urlQuizImg}/${ans.proposition}`);
          if (obImg.getAttribute('src') == `${quiz_config.urlQuizImg}/${ans.proposition}`)        
                {points += ans.points*1;}
        }      
    }
    //return ((currentQuestion.points > 0) ? currentQuestion.points : points);
    return points;
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var score = {min:0, max:0};


     var nbItemTofind = 0;
     var arrMinus = [];
      for(var k in currentQuestion.answers){
          var points = currentQuestion.answers[k].points;  
          if (points > 0){
            this.scoreMaxiBP += parseInt(points)*1;
            nbItemTofind++;
          } 
          if (points < 0) {arrMinus.push(points);}
      }
      arrMinus.sort().reverse();
      if (nbItemTofind > arrMinus.length) {nbItemTofind = arrMinus.length;}
      //alert ("nbItemTofind = " + nbItemTofind + " -  arrMinus: " + arrMinus.length);
      for(var k = 0; k < nbItemTofind; k++)
        {
          this.scoreMiniBP += parseInt(arrMinus[k])*1;
        }

     return true;
}


//---------------------------------------------------
incremente_question(nbQuestions)
  {
    return nbQuestions+1;
  } 

/* ***************************************
*
* *** */

 showGoodAnswers()
  {
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML(true);
    
     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var name = this.getName();
    var obContenair = document.getElementById(`${name}`);

    obContenair.innerHTML = this.getInnerHTML(false);
    return true;

  } 

} // ----- fin de la classe ------
