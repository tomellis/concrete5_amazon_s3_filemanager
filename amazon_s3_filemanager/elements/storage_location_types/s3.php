<?php
defined('C5_EXECUTE') or die('Access Denied');
?>
<?php $form = Loader::helper('form'); 

if (is_object($configuration)) {
	$accesskey = $configuration->getAccesskey();
	$secretkey = $configuration->getSecretkey();
	$bucketname = $configuration->getBucketname();
	$subfolder = $configuration->getSubfolder();
	$publicPath = $configuration->getPublicPath();
}

?>


<fieldset>
	<div class="form-group">
		<label for="accesskey"><?php echo t('Accesskey')?></label>
		<div class="input-group">
			<?php echo $form->text('fslType[accesskey]', $accesskey, array('placeholder' => t('Accesskey')))?>
			<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
		</div>
	</div>
	<div class="form-group">
		<label for="secretkey"><?php echo t('Secretkey')?></label>
		<div class="input-group">
			<?php echo $form->text('fslType[secretkey]', $secretkey, array('placeholder' => t('Secretkey')))?>
			<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
		</div>
	</div>
	<div class="form-group">
		<label for="bucketname"><?php echo t('Bucketname')?></label>
		<div class="input-group">
			<?php echo $form->text('fslType[bucketname]', $bucketname, array('placeholder' => t('Bucketname')))?>
			<span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
		</div>
	</div>
</fieldset>
<fieldset>
	<legend><?php echo t('Optionale Angaben') ?></legend>
	<div class="form-group">
		<label for="subfolder"><?php echo t('Subfolder in Ihrem Bucket')?></label>
		<?php echo $form->text('fslType[subfolder]', $subfolder, array('placeholder' => t('Subfolder in Ihrem Bucket')))?>
		<span><?php echo t('Ordner wird erstellt wenn er nicht existiert!'); ?></span>
	</div>

	<div class="form-group">
		<label>
			<?php echo $form->checkbox('enablePublicPath', $enablePublicPath, $enablePublicPath ? 'checked' : '') ?>
			Rewrite aktivieren
		</label>
	</div>

	<div class="form-group" id="publicPath">
		<label for="publicPath"><?php echo t('Pfad der auf Ihrer webseite dargestellt werden soll')?></label>
		<?php echo $form->text('fslType[publicPath]', $publicPath, array('placeholder' => t('Pfad der auf Ihrer webseite dargestellt werden soll z.b. /files/s3/')))?>
		<span><?php echo t('Achtung es wird ein htaccess Rewrite angelegt!'); ?></span>
	</div>
</fieldset>


<script type="text/javascript">
	$('#enablePublicPath').on('change',function(){
		console.log('hallo')
	});

</script>