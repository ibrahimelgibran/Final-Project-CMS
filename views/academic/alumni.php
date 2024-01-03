<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php $this->load->view('backend/grid_index');?>
<script type="text/javascript">
var _grid = 'ALUMNI', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'academic/alumni',
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
         header: '<i class="fa fa-file-image-o"></i>',
         renderer: function( row ) {
            return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Banner');
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-search-plus"></i>',
         renderer: function( row ) {
            var image = "'" + row.photo + "'";
            return row.photo ?
            '<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-key"></i>',
         renderer:function( row ) {
            if (row.is_alumni == 'true') return A('create_account(' + "'" + row.full_name + "'" + ', ' + row.id + ')', 'Aktivasi Akun', '<i class="fa fa-key"></i>');
            return '';
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-search"></i>',
         renderer: function( row ) {
            return Ahref(_BASE_URL + 'academic/alumni/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header: _IDENTITY_NUMBER, renderer:'identity_number' },
      { header:'Nama Lengkap', renderer:'full_name' },
      {
         header:'L/P',
         renderer: function( row ) {
            return row.gender == 'M' ? 'L' : 'P';
         },
         sort_field: 'gender'
      },
      { header:'TGL Masuk', renderer:'start_date' },
      { header:'TGL Keluar', renderer:'end_date' },
      { header:'Alamat', renderer:'street_address' },
      {
         header:'Alumni?',
         renderer: function( row ) {
            var is_alumni = row.is_alumni;
            if (is_alumni == 'true') return 'Ya';
            if (is_alumni == 'false') return 'Tidak';
            if (is_alumni == 'unverified') return 'Belum Diverifikasi';
            return '';
         },
         sort_field: 'is_alumni'
      }
   ],
   resize_column: 7,
   to_excel: false,
   can_add: false,
   extra_buttons: '<a class="btn btn-default btn-sm add" href="javascript:void(0)" onclick="alumni_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>' +
   '<button title="Aktivasi Semua Akun ?" onclick="create_accounts()" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top"><i class="fa fa-key"></i></button>'
});

new FormBuilder( _form , {
   controller:'academic/alumni',
   fields: [
      { label:'Alumni?', name:'is_alumni', type:'select', datasource:DS.IsAlumni },
      { label:'Tanggal Keluar', name:'end_date', placeholder:'Tanggal Keluar', type:'date' },
      { label:'Alasan Keluar', name:'reason', placeholder:'Alasan', type:'textarea' },
      { label:'Nama Lengkap', name:'full_name', placeholder:'Nama Lengkap' },
      { label:'Nomor Induk Siswa', name:'identity_number', placeholder:'Nomor Induk Siswa' },
      { label:'Alamat Jalan', name:'street_address', placeholder:'Alamat Jalan' },
      { label:'RT', name:'rt', placeholder:'Rukun Tetangga' },
      { label:'RW', name:'rw', placeholder:'Rukun warga' },
      { label:'Nama Dusun', name:'sub_village', placeholder:'Nama Dusun' },
      { label:'Nama Kelurahan/Desa', name:'village', placeholder:'Nama Desa' },
      { label:'Nama Kecamatan', name:'sub_district', placeholder:'Kecamatan' },
      { label:'Nama Kota/Kabupaten', name:'district', placeholder:'Kota/Kabupaten' },
      { label:'Kode Pos', name:'postal_code', placeholder:'Kode POS' },
      { label:'No. Telepon', name:'phone', placeholder:'Nomor Telepon' },
      { label:'No. Handphone', name:'mobile_phone', placeholder:'Nomor Hand Phone' },
      { label:'Email', name:'email', placeholder:'Alamat Email' }
   ]
});

function preview(image) {
   $.magnificPopup.open({
      items: {
         src: _BASE_URL + 'media_library/students/' + image
      },
      type: 'image'
   });
}

/**
 * Create Student Account
 * @param String
 * @param Number
 */
function create_account( full_name, id ) {
   eModal.confirm('Apakah anda yakin akan mengaktifkan akun dengan nama ' + full_name + ' ?', 'Konfirmasi').then(function() {
      $.post(_BASE_URL + 'academic/alumni/create_account', {'id':id}, function(response) {
         var res = _H.StrToObject( response );
         _H.Notify(res.status, _H.Message(res.message));
      });
   });
}

/**
 * Create All Accounts
 */
function create_accounts() {
   eModal.confirm('Nama Pengguna dan Kata Sandi Alumni akan digenerate dengan menggunakan ' + _IDENTITY_NUMBER + '. Apakah anda yakin akan mengaktifkan seluruh akun Alumni?', 'Konfirmasi').then(function() {
      _H.Loading( true );
      $.post(_BASE_URL + 'academic/alumni/create_accounts', {}, function( response ) {
         _H.Loading( false );
         var res = _H.StrToObject( response );
         _H.Notify(res.status, _H.Message(res.message));
      });
   });
}

function alumni_reports() {
   var fields = {
      full_name: 'Nama Lengkap',
		gender: 'L/P',
		birth_place: 'Tempat Lahir',
		birth_date: 'Tanggal Lahir',
		major_name: _MAJOR,
		is_transfer: 'Pindahan ?',
		achievement: 'Prestasi',
		start_date: 'Tanggal Masuk',
      end_date: 'Tanggal Keluar',
		identity_number: _IDENTITY_NUMBER,
		nisn: 'NISN',
		nik: 'NIK',
		prev_diploma_number: 'Nomor Ijazah Sebelumnya',
		paud: 'PAUD',
		tk: 'TK',
		skhun: 'SKHUN',
		prev_school_name: 'Nama Sekolah Sebelumnya',
		prev_school_address: 'Alamat Sekolah Sebelumnya',
		hobby: 'Hobi',
		ambition: 'Cita-Cita',
		religion: 'Agama',
		special_need: 'Kebutuhan Khusus',
		street_address: 'Alamat Jalan',
		rt: 'RT',
		rw: 'RW',
		sub_village: 'Dusun',
		village: 'Kelurahan',
		sub_district: 'Kecamatan',
		district: 'Kota/Kabupaten',
		postal_code: 'Kode Pos',
		residence: 'Tempat Tinggal',
		transportation: 'Alat Transportasi',
		phone: 'Telepon',
		mobile_phone: 'Handphone',
		email: 'Email',
		sktm: 'SKTM',
		kks: 'KKS',
		kps: 'KPS',
		kip: 'KIP',
		kis: 'KIS',
		citizenship: 'Kewarganegaraan',
		country: 'Nama Negara',
		father_name: 'Nama Ayah',
		father_birth_year: 'Tahun Lahir Ayah',
		father_education: 'Pendidikan Ayah',
		father_employment: 'Pekerjaan Ayah',
		father_monthly_income: 'Penghasilan Ayah',
		father_special_need: 'Kebutuhan Khusus Ayah',
		mother_name: 'Nama Ibu',
		mother_birth_year: 'Tahun Lahir Ibu',
		mother_education: 'Pendidikan Ibu',
		mother_employment: 'Pekerjaan Ibu',
		mother_monthly_income: 'Penghasilan Ibu',
		mother_special_need: 'Kebutuhan Khusus Ibu',
		guardian_name: 'Nama Wali',
		guardian_birth_year: 'Tahun Lahir Wali',
		guardian_education: 'Pendidikan Wali',
		guardian_employment: 'Pekerjaan Wali',
		guardian_monthly_income: 'Penghasilan Wali',
		mileage: 'Jarak Tempat Tinggal',
		traveling_time: 'Waktu Tempuh',
		height: 'Tinggi Badan',
		weight: 'Berat Badan',
		sibling_number: 'Jumlah Saudara Kandung',
		student_status: 'Status ' + _STUDENT,
		reason: 'Alasan Keluar'
	};
   $.post(_BASE_URL + 'academic/alumni/alumni_reports', {}, function(response) {
      var results = _H.StrToObject( response );
		var table = '<table>';
		table += '<tr>';
		for (var key in fields) {
			if ( _MAJOR_COUNT == 0 && key == 'major_name') continue;
			if ( _MAJOR_COUNT == 0 && key == 'major_name') continue;
			table += '<th>' + fields[ key ] + '</th>';
		}
		table += '</tr>'
		for (var x in results) {
			var res = results[ x ];
			table += '</tr>';
			for (var y in fields) {
				table += '<td>' + (res[ y ] ? res[ y ] : '-') + '</td>';
			}
			table += '</tr>';
		}
		table += '</table>';
		var elementId = 'excel-report';
		var div = '<div id="' + elementId + '" style="display: none;"></div>';
		$( div ).appendTo( document.body );
		$( '#' + elementId ).html( table );
		var fileName = 'LAPORAN-DATA-ALUMNI';
		ExportToExcel( elementId, fileName ); // Export to Excel
   }).fail(function(xhr) {
      console.log(xhr);
   });
}
</script>
