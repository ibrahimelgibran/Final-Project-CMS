<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'OPTIONS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'employees/laboratory_skills',
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
      { header:'Keahlian Laboratorium', renderer:'option_name' }
   ]
});

new FormBuilder( _form , {
   controller:'employees/laboratory_skills',
   fields: [
      { label:'Keahlian Laboratorium', name:'option_name' }
   ]
});
</script>
