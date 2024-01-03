<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'STUDENT_GROUPS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/student_groups',
   fields: [
      {
         header: '<i class="fa fa-edit"></i>',
         renderer: function( row ) {
            return A(_form + '.OnEdit(' + row.id + ')', 'Edit');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:_ACADEMIC_YEAR, renderer:'academic_year' },
      { header:'Kelas', renderer:'class_name' },
      { header:_IDENTITY_NUMBER, renderer:'identity_number' },
      { header:'Nama Lengkap', renderer:'full_name' },
      { header:'Tempat Lahir', renderer:'birth_place' },
      {
         header:'Tanggal Lahir',
         renderer: function( row ) {
            return row.birth_date && row.birth_date !== '0000-00-00' ? _H.ToIndonesianDate(row.birth_date) : '';
         },
         sort_field: 'birth_date'
      },
      { header:'L/P', renderer: 'gender' },
      {
         header:'Ketua Kelas ?',
         renderer: function( row ) {
            return row.is_class_manager == 'true' ? '<i class="fa fa-check"></i>' : '';
         },
         sort_field: 'is_class_manager'
      }
   ],
   resize_column: 2,
   can_add: false,
   can_delete: false,
   can_restore: false
});

new FormBuilder( _form , {
   controller:'academic/student_groups',
   fields: [
      { label:'Ketua Kelas ?', name:'is_class_manager', type:'select', datasource:DS.TrueFalse }
   ]
});
</script>
