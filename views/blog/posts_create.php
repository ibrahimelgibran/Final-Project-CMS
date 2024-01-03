<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/jquery-ui/jquery-ui.min.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/jquery-ui/jquery-ui.min.js');?>"></script>
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
	      xhr.open('POST', _BASE_URL + 'blog/posts/images_upload_handler');
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

	/* Save Post Category */
	function save_post_categories() {
		var values = {
			category_name: $('#category_name').val()
		}
		$.post(_BASE_URL + 'blog/post_categories/save', values, function(response) {
			var res = _H.StrToObject( response );
			if (res.status == 'error') {
				_H.Notify(res.status, _H.Message(res.message));
			}
			if (res.status == 'success') {
				var str = '<div class="checkbox">'
				+ '<label>'
				+ '<input type="checkbox" class="checkbox" name="post_categories[]" value="'+res.insert_id+'">'
				+ values.category_name
				+ '</label>'
				+ '</div>';
				var el = $("div.checkbox:last");
				$( str ).insertAfter(el);
				$('.category-form').modal('hide');
			}
		});
	}

	function submit_posts() {
		_H.Loading( true );
		var categories = $("input.checkbox:checked");
		var post_categories = [];
		categories.each( function() {
			var val = '+' + $(this).val() + '+';
		  post_categories.push( val );
		});

		var dataset = new FormData();
		dataset.append('post_title', $('#post_title').val());
		dataset.append('post_content', tinyMCE.get('post_content').getContent());
		dataset.append('post_categories', post_categories.join(','));
		dataset.append('post_status', $('#post_status').val());
		dataset.append('post_visibility', $('#post_visibility').val());
		dataset.append('post_comment_status', $('#post_comment_status').val());
		dataset.append('post_image', $('input[type=file]')[ 0 ].files[ 0 ]);
		dataset.append('post_tags', $('#post_tags').val());
		// send data
		$.ajax({
			url: '<?=$action;?>',
			type: 'POST',
			data: dataset,
			contentType: false,
			processData: false,
			success : function( response ) {
				var res = _H.StrToObject( response );
				_H.Notify(res.status, _H.Message(res.message));
				if (res.action == 'save')  {
					$('#post_tags').importTags('');
					$('input[type="text"], input[type="file"]').val('');
					$('#post_status').val('publish');
					$('#post_visibility').val('public');
					$('#post_status').val('open');
					tinyMCE.get('post_content').setContent('');
					$("input.checkbox").prop('checked', false);
					$('#post_title').focus();
					$('#preview').removeAttr('src').hide();
				}
				_H.Loading( false );
			}
		});
	}

	/** @namespace posts */
	$( document ).ready( function() {
		/* Show Form Add New category */
		$('.add-new-category').on('click', function(e) {
			e.preventDefault();
			$('#category_name').val('');
			$('.category-form').modal('show');
		});

		// Image Preview
		$('#post_image').on('change', function() {
			$('#preview').show();
			_H.Preview (this);
		});

		// remove Image Preview
		$('#preview').on('dblclick', function() {
			$('#preview').hide().removeAttr('src');
		});

		/* Tags */
		$('#post_tags').tagsInput({
			'width': 'auto',
			'autocomplete_url': _BASE_URL + 'blog/tags/autocomplete',
		   'interactive':true,
		   'defaultText':'Add New',
		   'delimiter': [', '],   // Or a string with a single delimiter. Ex: ';'
		   'removeWithBackspace' : true,
		   'minChars' : 0,
		   'maxChars' : 0, // if not provided there is no limit
		   'placeholderColor' : '#666666'
		});
	});
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
	<form>
		<div class="row">
			<div class="col-lg-8">
				<div class="box">
					<div class="box-body">
						<div class="form-group" style="margin-bottom: 10px;">
							<input id="post_title" name="post_title" value="<?=($query ? $query->post_title : '');?>" autofocus required="true" type="text" class="form-control input-lg" placeholder="Add Title" style="font-size: 16px">
						</div>
						<div class="form-group">
							<textarea rows="25" id="post_content" name="content" class="form-control ckeditor"><?=($query ? $query->post_content : '');?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-tasks"></i> Kategori</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<?php
							$post_categories = $this->uri->segment(4) ? explode(',', str_replace('+', '', $query->post_categories)) : [];
							foreach($option_categories->result() as $row) {
								$checked = $this->uri->segment(3) ? (in_array($row->id, $post_categories) ? 'checked="checked"' : '') : '';
								echo '<div class="checkbox">';
								echo '<label>';
								echo '<input type="checkbox"'.$checked.' class="checkbox" name="category_name[]" value="'.$row->id.'">'.$row->category_name;
								echo '</label>';
								echo '</div>';
							}
							?>
						</div>
					</div>
					<div class="box-footer">
						<button class="btn btn-sm btn-default add-new-category pull-right"><i class="fa fa-plus"></i> Tambah Kategori</button>
					</div>
				</div>
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-send-o"></i> Publikasi</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label class="control-label" for="post_status">Status</label>
									<?=form_dropdown('post_status', ['publish' => 'Diterbitkan', 'draft' => 'Konsep'], ($query ? $query->post_status : ''), 'class="form-control input-sm" id="post_status"');?>
								</div>
								<div class="col-lg-6">
									<label class="control-label" for="post_visibility">Akses</label>
									<?=form_dropdown('post_visibility', ['public' => 'Publik', 'private' => 'Private'], ($query ? $query->post_visibility : ''), 'class="form-control input-sm" id="post_visibility"');?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="post_comment_status">Komentar</label>
							<?=form_dropdown('post_comment_status', ['open' => 'Diizinkan', 'close' => 'Tidak Diizinkan'], ($query ? $query->post_comment_status : ''), 'class="form-control input-sm" id="post_comment_status"');?>
						</div>
						<div class="form-group">
							<label class="control-label">Gambar</label>
							<div class="input-group">
								<input type="file" name="post_image" class="form-control" id="post_image">
								<img <?=(isset($post_image) && $post_image) ? ('src="'.$post_image.'"') : '' ?> id="preview" width="293px" style="margin-top: 50px; <?=(isset($post_image) && $post_image) ? '': 'display:none;'?>" class="img-responsive" title="Double klik untuk menghapus gambar">
							</div>
						</div>
					</div>
					<div class="box-footer">
						<div class="btn-group pull-right">
							<button type="reset" class="btn btn-warning btn-sm"><i class="fa fa-retweet"></i> ATUR ULANG</button>
							<button type="button" onclick="submit_posts(); return false;" class="btn btn-primary btn-sm"><i class="fa fa-send-o"></i> SIMPAN</button>
						</div>
					</div>
				</div>
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-tags"></i> Tags</h3>
					</div>
					<div class="box-body">
						<div class="form-group">
							<input type="text" name="post_tags" id="post_tags" placeholder="Tags" class="form-control tm-input-info tm-tag-mini" value="<?=$query ? $query->post_tags : ''?>"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>
<div class="modal modal-form category-form">
   <div class="modal-dialog">
      <form class="form-horizontal form-dialog" role="form" method="post">
         <div class="modal-content">
            <div class="modal-header">
               <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title"><i class="fa fa-edit"></i> Tambah Kategori</h4>
            </div>
            <div class="modal-body">
               <div class="box-body">
               	<div class="form-group category">
               		<label class="col-sm-3 control-label" for="category">Nama Kategori</label>
               		<div class="col-sm-9">
               			<input type="text" class="form-control input-sm" id="category_name" name="category_name">
            			</div>
         			</div>
               </div>
               <div class="form-group" style="margin-top: 10px;padding: 10px 0;">
                  <div class="btn-group col-md-9 col-md-offset-3" id="container_upload">
                     <button onclick="save_post_categories(); return false;" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> SIMPAN</button>
                     <button type="reset" id="reset" class="btn btn-info btn-sm reset"><i class="fa fa-refresh"></i> ATUR ULANG</button>
                     <button class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-mail-forward"></i> KEMBALI</button>
                  </div>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
