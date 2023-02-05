
 /*******************************************************************
  *                     _imagesDaDBasket
  * *****************************************************************/
class imagesDaDBasket extends quizPrototype{
name = 'imagesDaDBasket';

//---------------------------------------------------
build (){
    var currentQuestion = this.question;
    var name = this.getName();
    
    
    const answers = [];
    answers.push(`<div id="${name}" style='width:100%;'>`);
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
getInnerHTML(){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';    

    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
var divHeight = currentQuestion.options.imgHeight1*1+12;  
var posCaption = currentQuestion.options.showCaptions;    
var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;
var ImgStyle=`style="height:${divHeight}px;"`;

var eventImgToEvent=`
onDragStart="dad_start(event);"
onDragOver="return dad_over(event);" 
onDrop="return dad_drop(event,${quiz_config.dad_move_img});"
onDragLeave="dad_leave(event);"`;
    
//------------------------------------------------------
    //definition du template selon le nombre de groupes 2 ou 3 en tenant compte du groupe 0
    var nbGroups = this.data.groupsLib.length;

var tpl = this.getTpl(nbGroups, currentQuestion.options.orientation);
    //----------------------------------------------------------------------------------------
 
     var tHtmlImgs = [];
    
    var answers = duplicateArray(currentQuestion.answers);
    for(var k in answers){
        var ans =  answers[k];
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
        switch (posCaption){
            case 'T': captionTop = ans.caption.replace('/','<br>') + '<br>' ; break;
            case 'B': captionBottom = '<br>' + ans.caption.replace('/','<br>'); break;
            default: break;
        }
        
//        alert('image = ' + ans.proposition);
        //img = `<img id="${ans.id}" src="${src}" title="${ans.caption}" alt="" ${ImgStyle} draggable='true'>`;        

        tHtmlImgs.push(`
            <div id="${ans.id}-div" ${divStyle} draggable='true' >${captionTop}
            <img id="${ans.id}-img" src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);


    }
    
    //--------------------------------------------------------------
    

    
    
    
    //---------------------------------------------------------------------
    for(var k = 0 ; k < this.data.groupsLib.length; k++){
        tpl=tpl.replace(`{group-${k}}`, this.data.groupsLib[k]);
    }
    tpl=tpl.replace('{imgGgroup-0}', tHtmlImgs.join("\n"));
    return tpl;
}


/* ************************************
*
* **** */
getInnerHTML_old(){
    var currentQuestion = this.question;
    var tWords = [];
    var tPoints = [];
    var tItems = new Object;
    var img = '';
    var src = '';
    var caption = '';

    //var tpl = "<table style='border: none;text-align:left;'><tr><td>{sequence}</td></tr><tr><td>{suggestion}</td></tr></table>";
    
var eventImgToStyle=`
style="height:${currentQuestion.options.imgHeight1}px;"
`;
var eventImgToEvent=`
onDragStart="dad_start(event);"
onDragOver="return dad_over(event);" 
onDrop="return dad_drop(event,${quiz_config.dad_move_img});"
onDragLeave="dad_leave(event);"
`;
    
//------------------------------------------------------
    //definition du template selon le nombre de groupes 2 ou 3 en tenant compte du groupe 0
    var nbGroups = this.data.groupsLib.length;

var tpl = this.getTpl(nbGroups, currentQuestion.options.orientation);
    //----------------------------------------------------------------------------------------
//     var tpl = "<div style='border: none;text-align:left;'>"
//             + `<div class='imagesLogical'>{sequence}</div><hr>${quiz_messages.message02}<hr><div class='imagesLogical'>{suggestion}</div></div>`;
// //     var tpl = "<style>.imagesLogical{border: none;text-align:left;}</style>"
// //             + "<div class='imagesLogical'>{sequence}</div><div class='imagesLogical'>{suggestion}</div>";
//     //var imgHeight = 64;   
//     var tHtmlSuggestion = [];
//     var tHtmlSubstitut = [];
//     
//     var tImgSequence = [];
//     var img = '';
// //alert('imagesLocal : execution = ' + quiz_execution + ' - quiz.url = ' + quiz.url);   
 
     var tHtmlImgs = [];
    
    var answers = duplicateArray(currentQuestion.answers);
    for(var k in answers){
        var ans =  answers[k];
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`;
        caption = (ans.caption) ? '<br>' + ans.caption : ''; 
//        alert('image = ' + ans.proposition);
        img = `<img id="${ans.id}" src="${src}" title="${ans.caption}" alt="" ${eventImgToStyle} draggable='true'>`;        
        
        tHtmlImgs.push(img);
    }
    
    //--------------------------------------------------------------
    

    
    
    
    //---------------------------------------------------------------------
    for(var k = 0 ; k < this.data.groupsLib.length; k++){
        tpl=tpl.replace(`{group-${k}}`, this.data.groupsLib[k]);
    }
    tpl=tpl.replace('{imgGgroup-0}', tHtmlImgs.join("\n"));
    return tpl;
}

/* *********************************************************
*
* ********************************************************** */
 prepareData(){
    
    var currentQuestion = this.question;
    var groups = [];
    groups[0] = [];
   
   //repartir les proposition par group
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        ans.id = this.getId('img', k);
        if(ans.points == 0) {ans.points = 1;}
        if(!groups[ans.group*1]) groups[ans.group*1] = [];
        groups[ans.group*1].push(ans);
    }   
    
    this.data.groups = groups;
    
    this.data.groupsLib=[];
    for(var k = 0; k <= 3; k++){
        var key = 'group' + k;
        if(currentQuestion.options[key]) {this.data.groupsLib.push(currentQuestion.options[key]);}
    }
    
    
    this.data.urlCommonImg = quiz_config.urlCommonImg;
}

/* **************************************************
calcul le nombre de points obtenus d'une question/slide
**************************************************** */ 
getScoreByProposition (answerContainer){
var points = 0;
var ans;
var obImg;
var idDivGood;

    var currentQuestion = this.question;
//alert('showGoodAnswers - sequence.length: ' + this.data.sequence.length);
      for(var k = 0; k < currentQuestion.answers.length; k++){
        ans =  currentQuestion.answers[k];
        obImg = document.getElementById(ans.id + "-div");
        idDivGood =  this.getId('group', ans.group);
        if (idDivGood == obImg.parentNode.id){
            points += ans.points*1;
        }else{
            //points -= ans.points*1;
        }            
                    
    }
    //return ((currentQuestion.points > 0) ? currentQuestion.points : points);
    return points;
}

//---------------------------------------------------
computeScoresMinMaxByProposition(){
    var currentQuestion = this.question;
    var score = {min:0, max:0};

      for(var k in currentQuestion.answers){
          var points = currentQuestion.answers[k].points;  
          if (points == 0) {points = 1;}        // force les points a une valeur supérieure à zéro
          if (points > 0){
            this.scoreMaxiBP += parseInt(points)*1;
          } 
          if (points < 0){
            this.scoreMiniBP += parseInt(points)*1;
          } 
      }

     return true;
}

/* **************************************************

***************************************************** */
getAllReponses (flag = 0){
    var currentQuestion = this.question;
    var img = '';
    var src = '';
    var captionTop='';
    var captionBottom = '';    


var divHeight = currentQuestion.options.imgHeight1*1+12;  
var posCaption = currentQuestion.options.showCaptions;    
var divStyle=`style="float:left;margin:5px;font-size:0.8em;text-align:center;"`;


var ImgStyle=`style="height:${divHeight}px;"`;

    
//------------------------------------------------------
    var nbGroups = this.data.groupsLib.length;
    var groups = [];
    var ans;
    var index;
    for(var k = 0; k < nbGroups; k++){
        groups[k] = [];
    }
    
   //repartir les propositions par group
    for(var k in currentQuestion.answers){
        var ans = currentQuestion.answers[k];
        var g = ans.group;
        src = `${quiz_config.urlQuizImg}/${ans.proposition}`; 
        switch (posCaption){
            case 'T': captionTop = ans.caption.replace(' ','<br>') + '<br>' ; break;
            case 'B': captionBottom = '<br>' + ans.caption.replace(' ','<br>'); break;
            default: break;
        }

        
        groups[g].push(`
            <div id="${ans.id}-div" ${divStyle} >${captionTop}
            <img id="${ans.id}-img" src="${src}" title="${ans.caption}" ${ImgStyle} alt="" >
            ${captionBottom}</div>`);
    }
        
var tHtml = [];   
    for(var k = 0; k < nbGroups; k++){
        tHtml.push('<div style="clear:both;"><hr>' + this.data.groupsLib[k] + '</div><br>');
        tHtml.push(groups[k].join(' '));
    }

    //---------------------------------------------------------------------
    return tHtml.join("\n");

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
    var currentQuestion = this.question;
    var obGroups= [];
    var obGroup;
    
    for(k = 0; k < this.data.groupsLib.length; k++){
        obGroups[k] = document.getElementById(this.getId('group',k));
        //alert(k + " : " + obGroups[k].id);
    }

    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        obGroup = obGroups[ans.group];
        //alert(ans.id);
        obGroup.appendChild(document.getElementById(ans.id + "-div")); 

    }

     return true;
  } 
/* ***************************************
*
* *** */

 showBadAnswers()
  {
    var currentQuestion = this.question;
    var obGroups= [];
    var obGroup;
    var nbGroups = this.data.groupsLib.length;
    var index; //groupe de destination aleatoire
    
    for(k = 0; k < this.data.groupsLib.length; k++){
        obGroups[k] = document.getElementById(this.getId('group',k));
        //alert(k + " : " + obGroups[k].id);
    }
    
    for(var k in currentQuestion.answers){
        var ans =  currentQuestion.answers[k];
        index = rnd(nbGroups-1);
        //alert ('index : ' + index);
        obGroup = obGroups[index];
        //alert(ans.id);
        obGroup.appendChild(document.getElementById(ans.id + "-div")); 

    }

     return true;
  } 
  /* *********************************************
  
  ************************************************ */
getTpl(nbGroups, orientation){
var eventImgToEvent=`
onDragStart="dad_start(event);"
onDragOver="return dad_over(event);" 
onDrop="return imagesDaDBasket_drop(event,${quiz_config.dad_move_img});"
onDragLeave="dad_leave(event);"`;

//var divStyle=`style="flex-direction:column;display:flex;"`;
var divStyle=`style="overflow-y: scroll;overflow: hidden;"`;

    var modele= orientation + nbGroups;
    switch(modele){
    //-------------------------------------------------------------------
    case 'H2' :
var tpl = 
`<table class='tbl_dad'>    
  <tr>
      <td></td><td style='width:100%;'>{group-1}</td>
  </tr>
  <tr>
      <td style='width:50%;'><div id='${this.getId('group',0)}' class='group0 myimg0 myimg1' ${divStyle} ${eventImgToEvent}>{imgGgroup-0}</div></td>
      <td style='width:50%;'><div id='${this.getId('group',1)}' class='group0 myimg0 myimg1' ${divStyle}  ${eventImgToEvent}></div></td>
  </tr>
</table>`;     
    break;
    //-------------------------------------------------------------------
    case 'H3' :
var tpl = 
`<table class='tbl_dad'>    
  <tr>
      <td style='width:50%;'><div id='${this.getId('group',0)}' class='group0 myimg0 myimg1'  ${divStyle} ${eventImgToEvent}><{imgGgroup-0}/div></td>
      <td>
        <div>{group-1}</div>
        <div id='${this.getId('group',1)}' class='group0 myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div>
        <div>{group-1}</div>
        <div id='${this.getId('group',2)}' class='group0 myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div>
      </td>
  </tr>
</table>`;     
    break;
    //-------------------------------------------------------------------
    case 'H4' :
var tpl = 
`<table class='tbl_dad'>    
  <tr>
      <td style='width:50%;'><div id='${this.getId('group',0)}' class='group0 myimg0 myimg1'  ${divStyle} ${eventImgToEvent}>{imgGgroup-0}</div></td>
      <td>
        <div>{group-1}</div>
        <div id='${this.getId('group',1)}' class='group0 myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div>
        <div>{group-2}</div>
        <div id='${this.getId('group',2)}' class='group0 myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div>
        <div>{group-3}</div>
        <div id='${this.getId('group',3)}' class='group0 myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div>
      </td>
  </tr>
</table>`;     
    break;
    //-------------------------------------------------------------------
    case 'V2' :
var tpl = 
`<table class='tbl_dad'>      
  <tr>
      <td tyle='width:100%;'><div id='${this.getId('group',0)}' class='group0 myimg0 myimg1' ${divStyle}  ${divStyle} ${eventImgToEvent}>{imgGgroup-0}</div></td>
  </tr>
      <td style='width:100%;'>{group-1}</td>  <tr>
  </tr>
  <tr>
      <td style='width:100%;'><div id='${this.getId('group',1)}' class='groupX myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div></td>
  </tr>
</table>`;     
    break;
    //-------------------------------------------------------------------
    case 'V3' :
var tpl = 
`<table class='tbl_dad'>      
  <tr>
      <td colspan='2' style='width:100%;'><div id='${this.getId('group',0)}' class='group0 myimg0 myimg1'  ${divStyle} ${eventImgToEvent}>{imgGgroup-0}</div></td>
  </tr>
  <tr>
      <td style='width:50%;'>{group-1}</td><td style='width:50%;'>{group-2}</td>
  </tr>
  <tr>
      <td style='width:50%;'><div id='${this.getId('group',1)}' class='groupX myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div></td>
      <td style='width:50%;'><div id='${this.getId('group',2)}' class='groupX myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div></td>
  </tr>
</table>`;     
    break;
    //-------------------------------------------------------------------
    case 'V4' :
var tpl = 
`<table class='tbl_dad'>      
  <tr>
      <td colspan='3' style='width:100%;'><div id='${this.getId('group',0)}' class='group0 myimg0 myimg1' ${divStyle}  ${divStyle} ${eventImgToEvent}>{imgGgroup-0}</div></td>
  </tr>
  <tr>
      <td>{group-1}</td><td>{group-2}</td><td>{group-3}</td>
  </tr>
  <tr>
    <td style='width:33%;'><div id='${this.getId('group',1)}' class='groupX myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div></td>
    <td style='width:33%;'><div id='${this.getId('group',2)}' class='groupX myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div></td>
    <td style='width:33%;'><div id='${this.getId('group',3)}' class='groupX myimg0 myimg1' ${divStyle} ${eventImgToEvent}></div></td>
  </tr>
</table>`;     
    break;
    }
    //-------------------------------------------------------------------
    return tpl;
}


}  // FIN DE LA CLASSE

/* ************************************************************* */
function imagesDaDBasket_drop(e, mode=0){
//alert('dad_drop')
    idFrom = e.dataTransfer.getData("text");
    // e.currentTarget.className="myimg1";
    e.currentTarget.classList.remove('myimg2');
    e.currentTarget.classList.add('myimg1');
    
    
    var obSource = document.getElementById(idFrom).parentNode;
    var obDest = document.getElementById(e.currentTarget.getAttribute("id"));
    //alert(`idFrom : ${obSource.id}\nidDest : ${obDest.id}`);
    obDest.appendChild(obSource);

    computeAllScoreEvent();
    //-----------------------------------------------
    
    e.stopPropagation();
    return false;
}

