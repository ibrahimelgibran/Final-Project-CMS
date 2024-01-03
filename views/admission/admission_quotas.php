<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
DS.AcademicYears = _H.StrToObject('<?=$academic_year_dropdown;?>');
DS.AdmissionTypes = _H.StrToObject('<?=get_options('admission_types')?>');
var _grid = 'QUOTAS', _form = _grid + '_FORM';
// New Grid Builder
var _grid_fields = [
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
   { header:'Jalur Pendaftaran', renderer:'admission_type' }
];
if (_MAJOR_COUNT > 0) {
   _grid_fields.push(
      { header:_MAJOR, renderer:'major_name', sorting: false }
   );
}
_grid_fields.push(
   { header:'Kuota', renderer:'quota' }
);
new GridBuilder( _grid , {
   controller:'admission/admission_quotas',
   fields: _grid_fields
});

// New Form Builder
var _form_fields = [
   { label:_ACADEMIC_YEAR, name:'academic_year_id', type:'select', datasource:DS.AcademicYears },
   { label:'Jalur Pendaftaran', name:'admission_type_id', type:'select', datasource:DS.AdmissionTypes }
];
if (_MAJOR_COUNT > 0) {
   _form_fields.push(
      { label:_MAJOR, name:'major_id', type:'select', datasource:DS.Majors }
   );
}
_form_fields.push(
   { label:'Kuota', name:'quota', type:'number' }
);
new FormBuilder( _form , {
   controller:'admission/admission_quotas',
   fields: _form_fields
});
</script>
