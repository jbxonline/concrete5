<? defined('C5_EXECUTE') or die("Access Denied."); ?>

<? $included = $permissionKey->getAssignmentList(); ?>
<? $excluded = $permissionKey->getAssignmentList(PermissionKey::ACCESS_TYPE_EXCLUDE); ?>
<? $pageTypes = CollectionType::getList(); ?>
<? $form = Loader::helper('form'); ?>

<form id="ccm-page-permissions-add-subpage-form" method="post" action="<?=$permissionKey->getPermissionKeyToolsURL()?>">

<? if (count($included) > 0) { ?>

<h3><?=t('Who can add what?')?></h3>

<? foreach($included as $assignment) {
	$entity = $assignment->getAccessEntityObject(); 
?>


<div class="clearfix">
	<label><?=$entity->getAccessEntityLabel()?></label>
	<div class="input">
	<?=$form->select('pageTypesIncluded[' . $entity->getAccessEntityID() . ']', array('1' => t('All Page Types'), 'C' => t('Custom')), $assignment->getPageTypesAllowedPermission())?>
	<ul class="inputs-list" <? if ($assignment->getPageTypesAllowedPermission() != 'C') { ?>style="display: none"<? } ?>>
		<br/><br/>
		<? foreach($pageTypes as $ct) { ?>
			<li><label><input type="checkbox" name="ctIDInclude[<?=$entity->getAccessEntityID()?>][]" value="<?=$ct->getCollectionTypeID()?>" <? if (in_array($ct->getCollectionTypeID(), $assignment->getPageTypesAllowedArray())) { ?> checked="checked" <? } ?> /> <span><?=$ct->getCollectionTypeName()?></span></label></li>
		<? } ?>
	</ul>
	</div>
</div>

<? }

} ?>


<? if (count($excluded) > 0) { ?>

<h3><?=t('Who can\'t add what?')?></h3>

<? foreach($excluded as $assignment) {
	$entity = $assignment->getAccessEntityObject(); 
?>


<div class="clearfix">
	<label><?=$entity->getAccessEntityLabel()?></label>
	<div class="input">
	<?=$form->select('pageTypesExcluded[' . $entity->getAccessEntityID() . ']', array('0' => t('No Page Types'), 'C' => t('Custom')), $assignment->getPageTypesAllowedPermission())?>
	<ul class="inputs-list" <? if ($assignment->getPageTypesAllowedPermission() != 'C') { ?>style="display: none"<? } ?>>
		<br/><br/>
		<? foreach($pageTypes as $ct) { ?>
			<li><label><input type="checkbox" name="ctIDExclude[<?=$entity->getAccessEntityID()?>][]" value="<?=$ct->getCollectionTypeID()?>" <? if (in_array($ct->getCollectionTypeID(), $assignment->getPageTypesAllowedArray())) { ?> checked="checked" <? } ?> /> <span><?=$ct->getCollectionTypeName()?></span></label></li>
		<? } ?>
	</ul>
	</div>
</div>



<? }

} ?>

<div class="dialog-buttons">
	<input type="button" class="btn" onclick="jQuery.fn.dialog.closeTop()" value="<?=t('Cancel')?>" />
	<input type="submit" class="btn primary ccm-button-right" onclick="$('#ccm-page-permissions-add-subpage-form').submit()" value="<?=t('Save')?>" />
</div>

</form>

<script type="text/javascript">
$(function() {
	$("#ccm-page-permissions-add-subpage-form select").change(function() {
		if ($(this).val() == 'C') {
			$(this).parent().find('ul.inputs-list').show();
		} else {
			$(this).parent().find('ul.inputs-list').hide();
		}
	});
	
	$("#ccm-page-permissions-add-subpage-form").ajaxForm({
		beforeSubmit: function() {
			jQuery.fn.dialog.showLoader();
		},
		success: function(r) {
			jQuery.fn.dialog.hideLoader();
			jQuery.fn.dialog.closeTop();
		}
	});
});
</script>