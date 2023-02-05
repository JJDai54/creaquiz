<!-- Header -->
<{include file='db:creaquiz_admin_header.tpl' }>

<form name='creaquiz_select_filter' id='creaquiz_select_filter' action='answers.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />


<div class="floatleft">
<{$smarty.const._AM_CREAQUIZ_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_CREAQUIZ_QUIZ}> : <{$inpQuiz}>
<{$smarty.const._AM_CREAQUIZ_QUESTION}> : <{$inpQuest}>
</div>

<div class="floatleft">
    <div class="xo-buttons">
        <{$btnNewAnswer}>
    </div>
</div>

<div class="floatright">
    <div class="xo-buttons">
        <{$initWeight}>
    </div>
</div>

</form>


<{if $answers_list}>
	<table class='table table-bordered'>
		<thead>
			<tr class='head'>
				<th class="center"><{$smarty.const._AM_CREAQUIZ_ANSWERS_ID}></th>
				<th class="center"><{$smarty.const._AM_CREAQUIZ_ANSWERS_QUESTION_ID}></th>
				<th class="center"><{$smarty.const._AM_CREAQUIZ_ANSWERS_PROPOSITION}></th>
				<th class="center"><{$smarty.const._CO_CREAQUIZ_GROUP}></th>
				<th class="center"><{$smarty.const._CO_CREAQUIZ_POINTS}></th>
				<th class="center"><{$smarty.const._AM_CREAQUIZ_CAPTION}></th>
				<th class="center"><{$smarty.const._AM_CREAQUIZ_WEIGHT}></th>
				<th class="center width5"><{$smarty.const._AM_CREAQUIZ_ACTION}></th>
			</tr>
		</thead>
		<{if $answers_count}>
		<tbody>
            <{assign var="fldImg" value="blue"}>
			<{foreach item=Answers from=$answers_list name=ans}>
			<tr class='<{cycle values='odd, even'}>'>
				<td class='center'><{$Answers.id}></td>
				<td class='center'><{$Answers.quest_id}></td>
                
				<td class='left'>
					<a href="answers.php?op=edit&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._EDIT}>">
                    <{$Answers.proposition}></a></td>
                    
				<td class='center'>
                    <{$Answers.group}></td>
				
				<td class='center'>
                    <{$Answers.points}></td>
                    
                <td class='left'>
					<a href="answers.php?op=edit&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._EDIT}>">
                    <{$Answers.caption}></td>
                
                <{* ---------------- Arrows -------------------- *}>
                <td class='center'>
                    <{if $smarty.foreach.ans.first}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-0.png" title="<{$smarty.const._AM_CREAQUIZ_FIRST}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-0.png" title="<{$smarty.const._AM_CREAQUIZ_UP}>">
                    <{else}>
                      <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=first&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/first-1.png" title="<{$smarty.const._AM_CREAQUIZ_FIRST}>">
                      </a>
                    
                      <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=up&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/up-1.png" title="<{$smarty.const._AM_CREAQUIZ_UP}>">
                      </a>
                    <{/if}>
                 
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                    <{$Answers.weight}>
                    <img src="<{$modPathIcon16}>/blank-08.png" title="">
                 
                    <{if $smarty.foreach.ans.last}>
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-0.png" title="<{$smarty.const._AM_CREAQUIZ_DOWN}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-0.png" title="<{$smarty.const._AM_CREAQUIZ_LAST}>">
                    <{else}>
                    
                    <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=down&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/down-1.png" title="<{$smarty.const._AM_CREAQUIZ_DOWN}>">
                      </a>
                 
                    <a href="answers.php?op=weight&answer_id=<{$Answers.id}>&sens=last&quest_id=<{$Answers.quest_id}>&answer_weight=<{$Answers.weight}>">
                      <img src="<{$modPathIcon16}>/arrows/<{$fldImg}>/last-1.png" title="<{$smarty.const._AM_CREAQUIZ_LAST}>">
                      </a>
                    <{/if}>
                <{* ---------------- /Arrows -------------------- *}>
                </td>
                
                
				<td class="center  width5">
					<a href="answers.php?op=edit&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._EDIT}>"><img src="<{xoModuleIcons16 edit.png}>" alt="answers" /></a>
					<a href="answers.php?op=delete&amp;answer_id=<{$Answers.id}>" title="<{$smarty.const._DELETE}>"><img src="<{xoModuleIcons16 delete.png}>" alt="answers" /></a>
				</td>
			</tr>
			<{/foreach}>
		</tbody>
		<{/if}>
	</table>
	<div class="clear">&nbsp;</div>
	<{if $pagenav}>
		<div class="xo-pagenav floatright"><{$pagenav}></div>
		<div class="clear spacer"></div>
	<{/if}>
<{/if}>
<{if $form}>
	<{$form}>
<{/if}>
<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:creaquiz_admin_footer.tpl' }>
