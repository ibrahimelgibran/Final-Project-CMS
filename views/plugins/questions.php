<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'QUESTIONS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'plugins/questions',
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
      { header:'Pertanyaan', renderer:'question' },
      {
         header:'Aktif ?',
         renderer: function( row ) {
            return row.is_active == 'true' ? '<i class="fa fa-check"></i>' : '';
         },
         sort_field: 'is_active'
      }
   ]
});

new FormBuilder( _form , {
   controller:'plugins/questions',
   fields: [
      { label:'Pertanyaan', name:'question', type:'textarea' },
      { label:'Aktif ?', name:'is_active', type:'select', datasource:DS.TrueFalse }
   ]
});
</script>
