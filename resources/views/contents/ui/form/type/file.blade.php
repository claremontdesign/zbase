<?php
// http://plugins.krajee.com/file-basic-usage-demo
$label = $ui->getLabel();
$wrapperAttributes = $ui->renderHtmlAttributes($ui->wrapperAttributes());
$labelAttributes = $ui->renderHtmlAttributes($ui->labelAttributes());
$inputAttributes = $ui->renderHtmlAttributes($ui->inputAttributes());
$multiple = $ui->isMultiple();
$formId = $ui->form()->htmlId();

if(!empty($multiple))
{
	// https://github.com/blueimp/jQuery-File-Upload
	zbase_view_plugin_load('fileupload');
	$onFormSubmit = $ui->uploadOnFormSubmit();
}
?>
<div <?php echo $wrapperAttributes ?>>
	<?php if(!empty($multiple)): ?>
		<?php ob_start()?>
		<script type="text/javascript">
		function <?php echo $ui->getHtmlId()?>UploaderDelete(ele)
		{
			zbase_alerts_remove();
			zbase_ajax_post(jQuery(ele).attr('data-url'), {}, function(r){
				jQuery(ele).closest('.template-download').remove();
				jQuery(jQuery('.' + jQuery(ele).attr('data-id'))).remove();
			}, {loaderTarget: jQuery(ele).closest('.template-download')});
			return false;
		}
		</script>
		<?php
		if(zbase_request_is_ajax())
		{
			zbase()->json()->setVariable('_html_script', [ob_get_clean()], true);
		} else {
			zbase_view_script_add('fileuploadFunctions' . $formId, ob_get_clean(), false);
		}
		?>
		<?php ob_start()?>
		<script type="text/javascript">
			jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').fileupload({
                disableImageResize: false,
                autoUpload: false,
				limitMultiFileUploads: <?php echo empty($onFormSubmit) ? '1' : '99999';?>,
				sequentialUploads: true,
				<?php echo empty($onFormSubmit) ? 'sequentialUploads: true,' : null;?>
                url: jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').attr('action')
				});
				<?php if(!empty($onFormSubmit)):?>
				jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').fileupload().bind('fileuploadadd', function(e, data){
					jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').find('[type="submit"]').attr('type','button');
					jQuery('#<?php echo $ui->getHtmlId()?>UploaderSubmitButton').on("click", function () {
						var failed = false;
						jQuery('input,textarea,select').filter('[required]:visible').each(function(){
							jQuery(this).closest('.form-group').removeClass('has-error');
							if(jQuery(this).val() == '')
							{
								failed = true;
								jQuery(this).closest('.form-group').addClass('has-error');
							}
						});
						if(!failed)
						{
							zbase_ajax_preloader();
							data.submit();
						}
					});
				}).bind('fileuploaddone', function (e, data) {
					jQuery.each(data.files, function (i, file) {
						if(jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').find('[value="'+file.name+'"]').length < 1)
						{
							jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').append('<input type="hidden" name="uploaded[]" value="'+file.name+'" />');
						}
                    });
					var activeUploads = jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').fileupload('active');
					if(activeUploads == 1) {
						jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').submit();
					}
				});
				<?php endif;?>
			<?php if(!empty($onFormSubmit)):?>
				jQuery('#<?php echo $ui->getHtmlId()?>Uploader').closest('form').find('[type="submit"]').attr('id','<?php echo $ui->getHtmlId()?>UploaderSubmitButton');
			<?php endif;?>
		</script>
		<?php
		if(zbase_request_is_ajax())
		{
			zbase()->json()->setVariable('_html_script', [ob_get_clean()], true);
		} else {
			zbase_view_script_add('fileupload' . $formId, ob_get_clean(), true);
		}
		?>
		<script id="template-upload" type="text/x-tmpl">
			{% for (var i=0, file; file=o.files[i]; i++) { %}
				<tr class="template-upload fade">
					<td>
						<span class="preview"></span>
					</td>
					<td>
						<p class="name">{%=file.name%}</p>
						{% if (file.error) { %}
							<div><span class="label label-danger">Error</span> {%=file.error%}</div>
						{% } %}
					</td>
					<td>
						<p class="size">{%=o.formatFileSize(file.size)%}</p>
						{% if (!o.files.error) { %}
							<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
							<div class="progress-bar progress-bar-success" style="width:0%;"></div>
							</div>
						{% } %}
					</td>
					<td>
						<?php if(empty($onFormSubmit)):?>
						{% if (!o.files.error && !i && !o.options.autoUpload) { %}
							<button class="btn blue start btn-sm">
								<i class="fa fa-upload"></i>
								<span>Start</span>
							</button>
						{% } %}
						<?php endif;?>
						{% if (!i) { %}
							<button class="btn red cancel btn-sm">
								<i class="fa fa-ban"></i>
								<span>Remove</span>
							</button>
						{% } %}
					</td>
				</tr>
			{% } %}
		</script>
		<script id="template-download" type="text/x-tmpl">
        {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade {%=file.id%}">
                <td>
                    <span class="preview">
                        {% if (file.thumbnailUrl) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" class="fancybox-button" data-rel="fancybox-button" download="{%=file.name%}"><img style="width:80px !important;" class="thumbnail" src="{%=file.thumbnailUrl%}"></a>
                        {% } %}
                    </span>
                </td>
                <td>
                    <p class="name">
                        {% if (file.url) { %}
                            <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                        {% } else { %}
                            <span>{%=file.name%}</span>
                        {% } %}
                    </p>
                    {% if (file.error) { %}
                        <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                    {% } %}
                </td>
                <td>
                    <span class="size">{%=o.formatFileSize(file.size)%}</span>
                </td>
                <td>
                    {% if (file.deleteUrl) { %}
                        <button onclick="<?php echo $ui->getHtmlId()?>UploaderDelete(this);" class="btn red delete btn-sm" data-id="{%=file.id%}" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                            <i class="fa fa-trash-o"></i>
                            <span>Delete</span>
                        </button>
                    {% } else { %}
                        <button class="btn yellow cancel btn-sm">
                            <i class="fa fa-ban"></i>
                            <span>Cancel</span>
                        </button>
                    {% } %}
                </td>
            </tr>
        {% } %}
    </script>
		<div id="<?php echo $ui->getHtmlId()?>Uploader" class="row fileupload-buttonbar">
			<div class="col-lg-7">
				<span class="btn blue fileinput-button">
					<i class="fa fa-plus"></i>
					<span>
						Add files...
					</span>
					<input type="file" name="files[]" multiple="">
				</span>
				<?php if(empty($onFormSubmit)):?>
					<button type="submit" class="btn blue start">
						<i class="fa fa-upload"></i>
						<span>
							Start upload
						</span>
					</button>
					<button type="reset" class="btn warning cancel">
						<i class="fa fa-ban-circle"></i>
						<span>
							Cancel upload
						</span>
					</button>
				<?php endif;?>
				<span class="fileupload-process">
				</span>
			</div>
			<div class="col-lg-5 fileupload-progress fade">
				<!-- The global progress bar -->
				<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
					<div class="progress-bar progress-bar-success" style="width:0%;">
					</div>
				</div>
				<div class="progress-extended">
					&nbsp;
				</div>
			</div>
		</div>
		<!-- The table listing the files available for upload/download -->
		<table role="presentation" class="table table-striped clearfix">
			<tbody class="files">
			</tbody>
		</table>
	<?php else: ?>
		<?php if(zbase_is_angular_template()): ?>
			<span class="btn btn-primary" flow-btn><?php echo $label ?></span>
		<?php else: ?>
			<label <?php echo $labelAttributes ?>><?php echo $label ?></label>
			<input <?php echo $inputAttributes ?> />
		<?php endif; ?>
		{!! view(zbase_view_file_contents('ui.form.helpblock'), compact('ui')) !!}
	<?php endif; ?>
</div>