<!-- Header -->
<{include file='db:creaquiz_admin_header.tpl' }>

<form name='creaquiz_select_filter' id='creaquiz_select_filter' action='questions.php' method='post' onsubmit='return xoopsFormValidate_form();' enctype=''>
<input type="hidden" name="op" value="list" />
<input type="hidden" name="sender" value="0" />
<input type="hidden" name="quest_parent_id" value="0" />

<{$smarty.const._AM_CREAQUIZ_CATEGORIES}> : <{$inpCategory}>
<{$smarty.const._AM_CREAQUIZ_QUIZ}> : <{$inpQuiz}>

</form>

<{if $form}>
	<{$form}>
<{/if}>

<{if $error}>
	<div class="errorMsg"><strong><{$error}></strong></div>
<{/if}>

<!-- Footer -->
<{include file='db:creaquiz_admin_footer.tpl' }>
