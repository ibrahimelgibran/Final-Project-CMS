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
      toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link print preview forecolor backcolor emoticons",
      image_advtab: true,
      plugins: [
         "advlist autolink lists link image charmap print preview hr anchor pagebreak",
         "searchreplace wordcount visualblocks visualchars code fullscreen",
         "insertdatetime nonbreaking save table contextmenu directionality",
         "emoticons template paste textcolor colorpicker textpattern"
      ]
   });

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
		dataset.append('post_image', $('input[type=file]')[ 0 ].files[ 0 ]);
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
					$('input[type="text"], input[type="file"]').val('');
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
		// Image Preview
		$('#post_image').on('change', function() {
			$('#preview').show();
			_H.Preview (this);
		});

		// Image Preview
		$('#preview').on('dblclick', function() {
			$('#preview').hide().removeAttr('src');
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
							$post_categories = $this->uri->segment(3) ? explode(',', str_replace('+', '', $query->post_categories)) : [];
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
				</div>
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-send-o"></i> Publikasi</h3>
					</div>
					<div class="box-body">
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
							<button type="reset" class="btn btn-info btn-sm"><i class="fa fa-retweet"></i> ATUR ULANG</button>
							<button type="button" onclick="submit_posts(); return false;" class="btn btn-primary btn-sm"><i class="fa fa-send-o"></i> SIMPAN</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</section>
