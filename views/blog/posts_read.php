<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
	var _grid = 'POSTS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
		controller:'blog/posts',
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
				header: '<i class="fa fa-edit"></i>',
				renderer: function( row ) {
					return Ahref(_BASE_URL + 'blog/posts/create/' + row.id, 'Edit');
				},
				exclude_excel: true,
				sorting: false
			},
			{
				header: '<i class="fa fa-calendar"></i>',
				renderer: function( row ) {
					return A(_form + '.OnEdit(' + row.id + ')', 'Ubah Tanggal Posting', '<i class="fa fa-calendar"></i>');
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
			{ header:'Judul', renderer:'post_title' },
			{ header:'Penulis', renderer:'post_author' },
			{
				header:'Status',
				renderer: function( row ) {
					return row.post_status.charAt(0).toUpperCase() + row.post_status.slice(1);
				},
				sort_field: 'post_status'
			},
			{ header:'Tanggal', renderer:'created_at', type:'date' }
		],
		can_add: false,
		resize_column: 5,
		extra_buttons: '<a class="btn btn-default btn-sm add" href="' + _BASE_URL + 'blog/posts/create'+'" data-toggle="tooltip" data-placement="top" title="Add"><i class="fa fa-plus"></i></a>'
	});

	new FormBuilder( _form , {
		controller:'blog/posts',
		fields: [
			{ label:'Tanggal Posting', name:'created_at', type:'datetime' }
		],
		save_action: 'set_created_at'
	});

	function find_id( id ) {
		$.post(_BASE_URL + 'blog/posts/find_id', {id:id}, function(response) {
			$('.modal-preview').modal({
				show:true
			});
			var post_content = response.post_content;
			$('.modal-preview .modal-body').empty().html('<p>' + post_content + '</p>');
			$('.modal-preview .modal-title').empty().html('<i class="fa fa-search" aria-hidden="true"></i> Tulisan');
		});
	}
</script>
