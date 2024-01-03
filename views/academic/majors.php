<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
   var _grid = 'MAJORS', _form = _grid + '_FORM';
   new GridBuilder( _grid , {
      controller:'academic/majors',
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
         { header:_MAJOR, renderer:'major_name' },
         { header:'Nama Singkat', renderer:'major_short_name' },
         {
            header:'Tampil di PPDB / PMB ?',
            renderer: function( row ) {
               return row.is_active == 'true' ? '<i class="fa fa-check-square-o"></i>' : '';
            },
            sort_field: 'is_active'
         }
      ]
   });

   new FormBuilder( _form , {
      controller:'academic/majors',
      fields: [
         { label:_MAJOR, name:'major_name' },
         { label:'Nama Singkat', name:'major_short_name' },
         { label:'Tampil di PPDB / PMB ?', name:'is_active', type:'select', datasource:DS.TrueFalse }
      ]
   });
</script>
