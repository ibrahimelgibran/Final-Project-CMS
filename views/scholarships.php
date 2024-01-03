<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.ScholarshipTypes = {
   '1': 'Anak Berprestasi',
   '2': 'Anak Miskin',
   '3': 'Pendidikan',
   '4': 'Unggulan',
   '5': 'Lain-lain'
};

var _grid = 'SCHOLARSHIPS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'scholarships',
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
      { header:'Nama Beasiswa', renderer:'scholarship_description' },
      {
         header:'Jenis Beasiswa',
         renderer: function( row ) {
            return DS.ScholarshipTypes[row.scholarship_type];
         },
         sort_field: 'scholarship_type'
      },
      { header:'Tahun Mulai', renderer:'scholarship_start_year' },
      { header:'Tahun Selesai', renderer:'scholarship_end_year' }
   ]
});

new FormBuilder( _form , {
   controller:'scholarships',
   fields: [
      { label:'Jenis Beasiswa', name:'scholarship_type', type:'select', datasource:DS.ScholarshipTypes },
      { label:'Nama Beasiswa', name:'scholarship_description', type:'textarea' },
      { label:'Tahun Mulai', name:'scholarship_start_year' },
      { label:'Tahun Selesai', name:'scholarship_end_year' }
   ]
});
</script>
