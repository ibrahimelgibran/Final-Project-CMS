<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
    var _grid = 'SUBSCRIBERS', _form = _grid + '_FORM';
	new GridBuilder( _grid , {
        controller:'blog/subscribers',
        fields: [
            { 
                header: '<input type="checkbox" class="check-all">', 
                renderer: function( row ) {
                    return CHECKBOX(row.id, 'id');
                },
                exclude_excel: true,
                sorting: false
            },
    		{ header:'Email', renderer:'email' }
    	],
        can_add: false,
        resize_column: 2
    });
</script>