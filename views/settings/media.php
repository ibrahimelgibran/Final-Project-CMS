<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'OPTIONS',
_form1 = _grid + '_FORM1', // file_allowed_types
_form2 = _grid + '_FORM2', // upload_max_filesize
_form3 = _grid + '_FORM3', // thumbnail_size
_form4 = _grid + '_FORM4', // thumbnail_size
_form5 = _grid + '_FORM5', // medium_size
_form6 = _grid + '_FORM6', // medium_size
_form7 = _grid + '_FORM7', // large_size
_form8 = _grid + '_FORM8', // large_size
_form9 = _grid + '_FORM9', // image slider
_form10 = _grid + '_FORM10', // image slider
_form11 = _grid + '_FORM11', // album_cover
_form12 = _grid + '_FORM12', // album_cover
_form13 = _grid + '_FORM13', // banner
_form14 = _grid + '_FORM14', // banner
_form15 = _grid + '_FORM15', // employee_photo
_form16 = _grid + '_FORM16', // employee_photo
_form17 = _grid + '_FORM17', // student_photo
_form18 = _grid + '_FORM18', // student_photo
_form19 = _grid + '_FORM19', // header_height
_form20 = _grid + '_FORM20', // header_width
_form21 = _grid + '_FORM21', // headmaster_photo_height
_form22 = _grid + '_FORM22', // headmaster_photo_width
_form23 = _grid + '_FORM23', // logo_height
_form24 = _grid + '_FORM24'; // logo_width

new GridBuilder( _grid , {
   controller:'settings/media',
   fields: [
      {
         header: '<i class="fa fa-edit"></i>',
         renderer: function( row ) {
            if (row.setting_variable == 'file_allowed_types') {
               return A(_form1 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'upload_max_filesize') {
               return A(_form2 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'thumbnail_size_height') {
               return A(_form3 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'thumbnail_size_width') {
               return A(_form4 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'medium_size_height') {
               return A(_form5 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'medium_size_width') {
               return A(_form6 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'large_size_height') {
               return A(_form7 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'large_size_width') {
               return A(_form8 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'image_slider_height') {
               return A(_form9 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'image_slider_width') {
               return A(_form10 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'album_cover_height') {
               return A(_form11 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'album_cover_width') {
               return A(_form12 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'banner_height') {
               return A(_form13 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'banner_width') {
               return A(_form14 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'employee_photo_height') {
               return A(_form15 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'employee_photo_width') {
               return A(_form16 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'student_photo_height') {
               return A(_form17 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'student_photo_width') {
               return A(_form18 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'header_height') {
               return A(_form19 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'header_width') {
               return A(_form20 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'headmaster_photo_height') {
               return A(_form21 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'headmaster_photo_width') {
               return A(_form22 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'logo_height') {
               return A(_form23 + '.OnEdit(' + row.id + ')');
            }
            if (row.setting_variable == 'logo_width') {
               return A(_form24 + '.OnEdit(' + row.id + ')');
            }
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'Setting Name', renderer: 'setting_description' },
      { header:'Setting Value', renderer: 'setting_value' }
   ],
   can_add: false,
   can_delete: false,
   can_restore: false,
   resize_column: 2,
   per_page: 50,
   per_page_options: [50, 100]
});

/**
* Tipe file yang diizinkan
*/
new FormBuilder( _form1 , {
   controller:'settings/media',
   fields: [
      { label:'Tipe file yang diizinkan', name:'setting_value', placeholder:'separated by commas (,)' }
   ]
});

/**
* Maksimal ukuran file yang diupload
*/
new FormBuilder( _form2 , {
   controller:'settings/media',
   fields: [
      { label:'Maksimal ukuran file yang diupload (Kb)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Gambar Thumbnail
*/
new FormBuilder( _form3 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Gambar Thumbnail (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Gambar Thumbnail
*/
new FormBuilder( _form4 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Gambar Thumbnail (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Gambar Sedang
*/
new FormBuilder( _form5 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Gambar Sedang (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Gambar Sedang
*/
new FormBuilder( _form6 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Gambar Sedang (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Gambar Besar
*/
new FormBuilder( _form7 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Gambar Besar (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Gambar Besar
*/
new FormBuilder( _form8 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Gambar Besar (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Gambar Slide
*/
new FormBuilder( _form9 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Gambar Slide (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Image Slider
*/
new FormBuilder( _form10 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Gambar Slide (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Cover Album
*/
new FormBuilder( _form11 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Cover Album Foto (px)', name:'setting_value', type:'number' }
   ]
});

/**
* TinggiLebar Cover Album Foto
*/
new FormBuilder( _form12 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Cover Album Foto (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Banner
*/
new FormBuilder( _form13 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Banner (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Banner
*/
new FormBuilder( _form14 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Banner (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Photo Guru dan Tenaga Kependidikan
*/
new FormBuilder( _form15 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Photo Guru dan Tenaga Kependidikan (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Photo Guru dan Tenaga Kependidikan
*/
new FormBuilder( _form16 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Photo Guru dan Tenaga Kependidikan (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Photo Peserta Didik
*/
new FormBuilder( _form17 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Photo Peserta Didik (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Photo Peserta Didik
*/
new FormBuilder( _form18 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Photo Peserta Didik (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Gambar Header (px)
*/
new FormBuilder( _form19 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Gambar Header (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Gambar Header (px)
*/
new FormBuilder( _form20 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Gambar Header (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Photo Kepala Sekolah (px)
*/
new FormBuilder( _form21 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Photo Kepala Sekolah (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Photo Kepala Sekolah (px)
*/
new FormBuilder( _form22 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Photo Kepala Sekolah (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Tinggi Logo (px)
*/
new FormBuilder( _form23 , {
   controller:'settings/media',
   fields: [
      { label:'Tinggi Logo (px)', name:'setting_value', type:'number' }
   ]
});

/**
* Lebar Logo (px)
*/
new FormBuilder( _form24 , {
   controller:'settings/media',
   fields: [
      { label:'Lebar Logo (px)', name:'setting_value', type:'number' }
   ]
});
</script>
