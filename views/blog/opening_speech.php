<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/tinymce/tinymce.min.js');?>"></script>
<script type="text/javascript">
	/** @namespace tinymce */
	tinymce.init({
      selector: "#post_content",
      theme: 'modern',
      paste_data_images:true,
      relative_urls: false,
      remove_script_host: false,
      toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
      toolbar2: "print preview forecolor backcolor emoticons",
      image_advtab: true,
      plugins: [
         "advlist autolink lists link image charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code fullscreen",
         "insertdatetime nonbreaking save table contextmenu directionality",
         "emoticons template paste textcolor colorpicker textpattern"
      ],
      automatic_uploads: true,
      file_picker_types: 'image',
      file_picker_callback: function(cb, value, meta) {
         var input = document.createElement('input');
         input.setAttribute('type', 'file');
         input.setAttribute('accept', 'image/*');
         input.onchange = function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function () {
               var id = 'post-image-' + (new Date()).getTime();
               var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
               var blobInfo = blobCache.create(id, file, reader.result);
               blobCache.add(blobInfo);
               cb(blobInfo.blobUri(), { title: file.name });
            };
         };
         input.click();
      },
		images_upload_handler: function (blobInfo, success, failure) {
	      var xhr, formData;
	      xhr = new XMLHttpRequest();
	      xhr.withCredentials = false;
	      xhr.open('POST', _BASE_URL + 'blog/opening_speech/images_upload_handler');
	      xhr.onload = function() {
	         if (xhr.status != 200) {
	            failure('HTTP Error: ' + xhr.status);
	            return;
	         }
	         var res = _H.StrToObject( xhr.responseText );
	         if (res.status == 'error') {
	            failure( res.message );
	            return;
	         }
	         success( res.location );
	      };
	      formData = new FormData();
	      formData.append('file', blobInfo.blob(), blobInfo.filename());
	      xhr.send(formData);
	   }
   });

	// Save
	function save() {
		_H.Loading( true );
		$('#submit').attr('disabled', 'disabled');
		var values = {
			post_content: tinyMCE.get('post_content').getContent()
		};
		$.post(_BASE_URL + 'blog/opening_speech/save', values, function(response) {
			var res = _H.StrToObject( response );
			_H.Notify(res.status, _H.Message(res.message));
			$('#post_content').val('');
			$('#submit').removeAttr('disabled');
			_H.Loading( false );
		});
	}
</script>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header"><?=isset($title) ? $title : ''?></p>
      <?=isset($sub_title) ? '<small>'.$sub_title.'</small>' : ''?>
   </div>
</section>
 <section class="content">
 	<div class="box">
		<div class="box-body p-0">
			<form role="form">
				<div class="box-body">
					<div class="form-group">
               	<textarea rows="25" id="post_content" name="post_content" class="form-control ckeditor"><?=$query?></textarea>
            	</div>
				</div>
         </form>
		</div>
		<div class="box-footer">
			<button type="submit" onclick="save(); return false;" class="btn btn-primary"><i class="fa fa-save"></i> SIMPAN</button>
		</div>
	</div>
 </section>
