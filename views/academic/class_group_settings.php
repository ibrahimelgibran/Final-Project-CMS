<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.AcademicYears = _H.StrToObject('<?=$academic_year_dropdown;?>');
DS.ClassGroups = _H.StrToObject('<?=$class_group_dropdown;?>');
DS.Employees = _H.StrToObject('<?=$employee_dropdown;?>');
var _grid = 'CLASS_GROUP_SETTINGS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/class_group_settings',
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
      { header: _ACADEMIC_YEAR, renderer:'academic_year' },
      { header:'Kelas', renderer:'class_name' },
      { header:(_SCHOOL_LEVEL >= 5 ? 'Dosen Wali' : 'Wali Kelas'), renderer:'employee_name' },
      { header:'JUMLAH ROMBEL', renderer:'total' }
   ]
});

new FormBuilder( _form , {
   controller:'academic/class_group_settings',
   fields: [
      { label: _ACADEMIC_YEAR, name:'academic_year_id', type:'select', datasource:DS.AcademicYears },
      { label:'Kelas', name:'class_group_id', type:'select', datasource:DS.ClassGroups },
      { label:(_SCHOOL_LEVEL == 5 ? 'Dosen Wali' : 'Wali Kelas'), name:'employee_id', type:'select', datasource:DS.Employees }
   ]
});
</script>
