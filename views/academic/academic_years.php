<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.Semester = {
   'odd': 'Ganjil',
   'even': 'Genap'
};
var _grid = 'ACADEMIC_YEARS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/academic_years',
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
      { header:_ACADEMIC_YEAR, renderer:'academic_year' },
      {
         header:'Semester',
         renderer: function( row ) {
            return row.semester == 'odd' ? 'Ganjil' : 'Genap';
         },
         sort_field: 'semester'
      },
      {
         header:'Semester Aktif ?',
         renderer: function( row ) {
            return row.current_semester == 'true' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-warning"></i>';
         },
         sort_field: 'current_semester'
      },
      {
         header:'PPDB / PMB Aktif ?',
         renderer: function( row ) {
            return row.admission_semester == 'true' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-warning"></i>';
         },
         sort_field: 'admission_semester'
      }
   ]
});

new FormBuilder( _form , {
   controller:'academic/academic_years',
   fields: [
      { label:_ACADEMIC_YEAR, name:'academic_year', placeholder:'Separated by (-). Example: 2016-2017' },
      { label:'Semester', name:'semester', type:'select', datasource:DS.Semester },
      { label:'Semester Aktif ?', name:'current_semester', type:'select', datasource:DS.TrueFalse },
      { label:'PPDB / PMB Aktif ?', name:'admission_semester', type:'select', datasource:DS.TrueFalse }
   ]
});
</script>
