<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.AchievementTypes = {
   '1': 'Sains',
   '2': 'Seni',
   '3': 'Olahraga',
   '4': 'Lain-lain'
};

DS.AchievementLevels = {
   '1': 'Sekolah',
   '2': 'Kecamatan',
   '3': 'Kota/Kabupaten',
   '4': 'Propinsi',
   '5': 'Nasional',
   '6': 'Internasional'
};

var _grid = 'ACHIEVEMENTS', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'achievements',
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
      { header:'Nama Prestasi', renderer:'achievement_description' },
      {
         header:'Jenis',
         renderer: function( row ) {
            return DS.AchievementTypes[row.achievement_type];
         },
         sort_field: 'achievement_type'
      },
      {
         header:'Tingkat',
         renderer: function( row ) {
            return DS.AchievementLevels[row.achievement_level];
         },
         sort_field: 'achievement_level'
      },
      { header:'Tahun', renderer:'achievement_year' },
      { header:'Penyelenggara', renderer:'achievement_organizer' }
   ]
});

new FormBuilder( _form , {
   controller:'achievements',
   fields: [
      { label:'Jenis', name:'achievement_type', type:'select', datasource:DS.AchievementTypes },
      { label:'Tingkat', name:'achievement_level', type:'select', datasource:DS.AchievementLevels },
      { label:'Tahun', name:'achievement_year' },
      { label:'Penyelenggara', name:'achievement_organizer' },
      { label:'Nama Prestasi', name:'achievement_description', type:'textarea' }
   ]
});
</script>
