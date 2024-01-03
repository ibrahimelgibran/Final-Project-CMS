<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.Options = _H.StrToObject('<?=$options;?>');
var _grid = 'REGISTRANTS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
	controller:'admission/registrants_approved',
	fields: [
		{
			header: '<i class="fa fa-edit"></i>',
			renderer: function( row ) {
				return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
			},
			exclude_excel: true,
			sorting: false
		},
		{ header:'No. Daftar', renderer:'registration_number' },
		{ header:'Tanggal Daftar', renderer:'created_at' },
		{
			header:'Daftar Ulang ?',
			renderer: function( row ) {
				var re_registration = row.re_registration;
				return re_registration == 'true' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-warning"></i>';
			},
			sort_field: 're_registration'
		},
		{ header:'Hasil Seleksi', renderer:'selection_result' },
		{ header:'Nama Lengkap', renderer:'full_name' },
		{ header:'Tanggal Lahir', renderer:'birth_date' },
		{
			header:'L/P',
			renderer: function( row ) {
				return row.gender == 'M' ? 'L' : 'P';
			},
			sort_field: 'gender'
		},
	],
	resize_column: 2,
	to_excel: true,
	can_add: false,
	can_delete: false,
	can_restore: false
});

new FormBuilder( _form , {
	controller:'admission/registrants_approved',
	fields: [
		{ label:'Hasil Seleksi', name:'selection_result', type:'select', datasource:DS.Options }
	]
});
</script>
