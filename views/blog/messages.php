<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
	var _grid = 'INBOX', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
		controller:'blog/messages',
		fields: [
			{
				header: '<input type="checkbox" class="check-all">',
				renderer: function( row ) {
					return CHECKBOX(row.id, 'id');
				},
				exclude_excel: true,
				sorting: false
			},
			{
				header: '<i class="fa fa-search"></i>',
				renderer: function( row ) {
					return A('find_id(' + row.id +')', 'Lihat Pesan', '<i class="fa fa-search"></i>');
				},
				exclude_excel: true,
				sorting: false
			},
			{
				header: '<i class="fa fa-envelope-o"></i>',
				renderer: function( row ) {
					return A(_form + '.OnEdit(' + row.id + ')', 'Reply', '<i class="fa fa-envelope-o"></i>');
				},
				exclude_excel : true,
				sorting: false
			},
			{ header:'Pengirim', renderer:'comment_author' },
			{ header:'Email', renderer:'comment_email' },
			{ header:'URL', renderer:'comment_url' },
			{ header:'Tanggal Kirim', renderer:'created_at' }
		],
		resize_column:4,
		can_add:false
	});

	new FormBuilder( _form , {
		controller:'blog/messages',
		fields: [
			{ label:'Subject', name:'comment_subject' },
			{ label:'Reply', name:'comment_reply', type: 'textarea' }
		]
	});

	function find_id( id ) {
		$.post(_BASE_URL + 'blog/messages/find_id', {id:id}, function(response) {
			$('.modal-preview').modal({
				show:true
			});
			var message = response.comment_content;
			$('.modal-preview .modal-body').empty().html('<p>' + message + '</p>');
			$('.modal-preview .modal-title').empty().html('<i class="fa fa-envelope-open-o" aria-hidden="true"></i> Pesan Masuk');
		});
	}
</script>
