<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'THEMES', _form = _grid + '_FORM';
    new GridBuilder( _grid , {
        controller:'appearance/themes',
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
            { 
                header: '<i class="fa fa-upload"></i>', 
                renderer: function( row ) {
                    return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'file', 'Upload File');
                },
                exclude_excel: true,
                sorting: false
            },
            { header:'Nama Tema', renderer:'theme_name' },
            { header:'Folder', renderer:'theme_folder' },
            { header:'Pembuat', renderer:'theme_author' },
            { 
                header:'Aktif ?', 
                renderer: function( row ) {
                    return row.is_active == 'true' ? '<i class="fa fa-check-square-o"></i>' : '';
                },
                exclude_excel: true,
                sort_field: 'is_active'
            }
        ],
        resize_column: 4
    });

    new FormBuilder( _form , {
        controller:'appearance/themes',
        fields: [
          { label:'Nama Tema', name:'theme_name' },
          { label:'Folder', name:'theme_folder' },
          { label:'Pembuat', name:'theme_author' },
          { label:'Aktif ?', name:'is_active', type:'select', datasource:DS.TrueFalse }
        ]
    });
</script>