<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
	DS.LinkTarget = {
		'_blank':'Blank',
		'_self':'Self',
		'_parent':'Parent',
		'_top':'Top'
	};
	var _grid = 'LINKS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
		controller:'blog/links',
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
					return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
				},
				exclude_excel: true,
				sorting: false
			},
			{ header:'URL', renderer:'link_url' },
			{ header:'Keterangan', renderer:'link_title' },
			{
				header:'Target',
				renderer: function( row ) {
					return DS.LinkTarget[row.link_target];
				},
				sort_field: 'link_target'
			}
		]
	});

	new FormBuilder( _form , {
		controller:'blog/links',
		fields: [
			{ label:'URL', name:'link_url', placeholder:'Add prefix http://' },
			{ label:'Keterangan', name:'link_title' },
			{ label:'Target', name:'link_target', type:'select', datasource:DS.LinkTarget }
		]
	});
</script>
