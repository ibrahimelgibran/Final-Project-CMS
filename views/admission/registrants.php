<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php $this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.AdmissionTypes = _H.StrToObject('<?=get_options('admission_types')?>');
DS.Majors = _H.StrToObject('<?=$major_dropdown;?>');
DS.SpecialNeeds = _H.StrToObject('<?=get_options('special_needs')?>');
DS.Religions = _H.StrToObject('<?=get_options('religions')?>');
DS.Residences = _H.StrToObject('<?=get_options('residences')?>');
DS.Transportations = _H.StrToObject('<?=get_options('transportations')?>');
DS.MonthlyIncomes = _H.StrToObject('<?=get_options('monthly_incomes')?>');
DS.StudentStatus = _H.StrToObject('<?=get_options('student_status')?>');
DS.Employments = _H.StrToObject('<?=get_options('employments')?>');
DS.Educations = _H.StrToObject('<?=get_options('educations')?>');
var _grid = 'REGISTRANTS', _form = _grid + '_FORM', _form2 = _grid + '_FORM2';
var grid_fields = [
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
		header: '<i class="fa fa-check-square-o"></i>',
		renderer: function( row ) {
			return A(_form2 + '.OnEdit(' + row.id + ')', 'Daftar Ulang ?', '<i class="fa fa-check-square-o"></i>');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-print"></i>',
		renderer: function( row ) {
			return A('print_admission_form(' + row.id + ', event)', 'Cetak Formulir Pendaftaran', '<i class="fa fa-print"></i>');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-file-image-o"></i>',
		renderer: function( row ) {
			return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Photo');
		},
		exclude_excel: true,
		sorting: false
	},
	{
		header: '<i class="fa fa-search-plus"></i>',
		renderer: function( row ) {
			var image = "'" + row.photo + "'";
			return row.photo ? '<a title="Preview" onclick="preview(' + image + ')"  href="#"><i class="fa fa-search-plus"></i></a>' : '';
		},
		sorting: false
	},
	{
		header: '<i class="fa fa-search"></i>',
		renderer: function( row ) {
			return Ahref(_BASE_URL + 'admission/registrants/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
		},
		exclude_excel: true,
		sorting: false
	},
	{ header:'No. Daftar', renderer:'registration_number' },
	{ header:'Nama Lengkap', renderer:'full_name' },
	{ header:'Tanggal Lahir', renderer:'birth_date' },
	{ header:'Tanggal Daftar', renderer:'created_at' },
	{ header:'Jalur Pendaftaran', renderer:'admission_type' },
	{ header:'Gelombang Pendaftaran', renderer:'phase_name' },
	{
		header:'Daftar Ulang ?',
		renderer: function( row ) {
			var re_registration = row.re_registration;
			return re_registration == 'true' ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-warning"></i>';
		},
		sort_field: 're_registration'
	},
];
if (_MAJOR_COUNT > 0) {
	grid_fields.push(
		{ header:'Pilihan I', renderer:'first_choice' },
		{ header:'Pilihan II', renderer:'second_choice' }
	);
}
new GridBuilder( _grid , {
	controller:'admission/registrants',
	fields: grid_fields,
	resize_column: 8,
	to_excel: false,
	can_add: false,
	extra_buttons: '<a class="btn btn-sm btn-default add" href="javascript:void(0)" onclick="admission_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>'
});

var form_fields = [];
if (_MAJOR_COUNT > 0) {
	form_fields.push(
		{ label:'Pilihan I', name:'first_choice_id', type:'select', datasource:DS.Majors },
		{ label:'Pilihan II', name:'second_choice_id', type:'select', datasource:DS.Majors }
	);
}
form_fields.push(
	{ label:'Jenis Pendaftaran', name:'is_transfer', type:'select', datasource:{false: 'Baru', true:'Pindahan'} },
	{ label:'Jalur Pendaftaran', name:'admission_type_id', type:'select', datasource:DS.AdmissionTypes },
	{ label:'Nomor Peserta UN Sebelumnya', name:'prev_exam_number' },
	{ label:'Apakah Pernah PAUD', name:'paud', type:'select', datasource:{false: 'Tidak', true:'Ya'} },
	{ label:'Apakah Pernah TK', name:'tk', type:'select', datasource:{false: 'Tidak', true:'Ya'} },
	{ label:'Nomor Seri SKHUN Sebelumnya', name:'skhun' },
	{ label:'Nomor Seri Ijazah Sebelumnya', name:'prev_diploma_number' },
	{ label:'Hobi', name:'hobby' },
	{ label:'Cita-Cita', name:'ambition' },
	{ label:'Nama Lengkap', name:'full_name', placeholder:'Nama Lengkap' },
	{ label:'Jenis Kelamin', name:'gender', type:'select', datasource:DS.Gender },
	{ label:'NISN', name:'nisn', placeholder:'Nomor Induk Siswa Nasional' },
	{ label:'NIK', name:'nik', placeholder:'' },
	{ label:'Tempat Lahir', name:'birth_place', placeholder:'Tempat Lahir' },
	{ label:'Tanggal Lahir', name:'birth_date', placeholder:'Tanggal Lahir', type:'date' },
	{ label:'Agama', name:'religion_id', type:'select', datasource:DS.Religions },
	{ label:'Berkebutuhan Khusus', name:'special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Alamat Jalan', name:'street_address', placeholder:'Alamat Jalan' },
	{ label:'RT', name:'rt', placeholder:'Rukun Tetangga' },
	{ label:'RW', name:'rw', placeholder:'Rukun warga' },
	{ label:'Nama Dusun', name:'sub_village', placeholder:'Nama Dusun' },
	{ label:'Nama Kelurahan/Desa', name:'village', placeholder:'Nama Desa' },
	{ label:'Kecamatan', name:'sub_district', placeholder:'Kecamatan' },
	{ label:'Kota/Kabupaten', name:'district', placeholder:'Kota/Kabupaten' },
	{ label:'Kode Pos', name:'postal_code', placeholder:'Kode POS' },
	{ label:'Tempat Tinggal', name:'residence_id', type:'select', datasource:DS.Residences },
	{ label:'Moda Transportasi', name:'transportation_id', type:'select', datasource:DS.Transportations },
	{ label:'Nomor Telepon', name:'phone', placeholder:'Nomor Telepon' },
	{ label:'Nomor Handphone', name:'mobile_phone', placeholder:'Nomor Handphone' },
	{ label:'Email', name:'email', placeholder:'Alamat Email' },
	{ label:'Surat Keterangan Tidak Mampu (SKTM)', name:'sktm' },
	{ label:'Kartu Keluarga Sejahtera (KKS)', name:'kks' },
	{ label:'Kartu Pra Sejahtera (KPS)', name:'kps' },
	{ label:'Kartu Indonesia Pintar (KIP)', name:'kip' },
	{ label:'Kartu Indonesia Sehat (KIS)', name:'kis' },
	{ label:'Kewarganegaraan', name:'citizenship', type:'select', datasource:DS.Citizenship },
	{ label:'Negara', name:'country', placeholder:'Nama Negara. Diisi jika bukan WNI' },
	{ label:'Nama Ayah', name:'father_name', placeholder:'Nama ayah Kandung' },
	{ label:'Tahun Lahir Ayah', name:'father_birth_year', placeholder:'Tahun Lahir' },
	{ label:'Pendidikan Ayah', name:'father_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ayah', name:'father_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Ayah / Bulan', name:'father_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ayah', name:'father_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Nama Ibu', name:'mother_name', placeholder:'Nama Ibu Kandung' },
	{ label:'Tahun Lahir Ibu', name:'mother_birth_year', placeholder:'Tahun Lahir' },
	{ label:'Pendidikan Ibu', name:'mother_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Ibu', name:'mother_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Ibu / Bulan', name:'mother_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Kebutuhan Khusus Ibu', name:'mother_special_need_id', type:'select', datasource:DS.SpecialNeeds },
	{ label:'Nama Wali', name:'guardian_name', placeholder:'Nama Wali' },
	{ label:'Tahun Lahir Wali', name:'guardian_birth_year', placeholder:'Tahun Lahir' },
	{ label:'Pendidikan Wali', name:'guardian_education_id', type:'select', datasource:DS.Educations },
	{ label:'Pekerjaan Wali', name:'guardian_employment_id', type:'select', datasource:DS.Employments },
	{ label:'Penghasilan Wali / Bulan', name:'guardian_monthly_income_id', type:'select', datasource:DS.MonthlyIncomes },
	{ label:'Jarak Tempat Tinggal ke Sekolah', name:'mileage', placeholder:'Jarak tempat tinggal ke sekolah', type:'number' },
	{ label:'Waktu Tempuh', name:'traveling_time', placeholder:'Waktu Tempuh', type:'number' },
	{ label:'Tinggi Badan', name:'height', placeholder:'Tinggi Badan', type:'number' },
	{ label:'Berat Badan', name:'weight', placeholder:'Berat Badan', type:'number' },
	{ label:'Jumlah Saudara Kandung', name:'sibling_number', placeholder:'Jumlah Saudara Kandung', type:'number' }
);
new FormBuilder( _form , {
	controller:'admission/registrants',
	fields: form_fields
});

new FormBuilder( _form2 , {
	controller:'admission/registrants',
	fields: [
		{ label:'Daftar Ulang ?', name:'re_registration', type:'select', datasource:DS.TrueFalse }
	],
	save_action: 'verified'
});

// Cetak Formulir Pendaftaran
function print_admission_form( id ) {
	$.post(_BASE_URL + 'admission/registrants/print_admission_form', {'id':id}, function(response) {
		var res = _H.StrToObject(response);
		if (res.status == 'success') {
			window.open(_BASE_URL + 'media_library/students/' + res.file_name,'_self');
		}
		_H.Notify('error', 'Format data tidak valid.');
	}).fail(function(xhr) {
		console.log(xhr);
	});
}

function preview(image) {
	$.magnificPopup.open({
		items: {
			src: _BASE_URL + 'media_library/students/' + image
		},
		type: 'image'
	});
}

// Export All Field to Excel
function admission_reports() {
	var fields = {
		registration_number: 'Nomor Pendaftaran',
		full_name: 'Nama Lengkap',
		gender: 'L/P',
		birth_place: 'Tempat Lahir',
		birth_date: 'Tanggal Lahir',
		first_choice: 'Pilihan 1',
		second_choice: 'Pilihan 2',
		created_at: 'Tanggal Pendaftaran',
		admission_exam_number: 'Nomor Ujian Tes Tulis',
		prev_exam_number: 'Nomor Ujian Sebelumnya',
		selection_result: 'Hasil Seleksi',
		phase_name: 'Gelombang Pendaftaran',
		admission_type: 'Jalur Pendaftaran',
		is_transfer: 'Pindahan ?',
		achievement: 'Prestasi',
		re_registration: 'Daftar Ulang ?',
		start_date: 'Tanggal Masuk',
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
		sibling_number: 'Jumlah Saudara Kandung'
	};
	if (_MAJOR_COUNT == 0) {
		delete fields['first_choice'];
		delete fields['second_choice'];
	}
	$.post(_BASE_URL + 'admission/registrants/admission_reports', {}, function(response) {
		var results = _H.StrToObject( response );
		var table = '<table>';
		table += '<tr>';
		for (var key in fields) {
			table += '<th>' + fields[ key ] + '</th>';
		}
		table += '</tr>'
		for (var x in results) {
			var res = results[ x ];
			table += '<tr>';
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
		var fileName = 'DATA-CALON-' + _STUDENT.toUpperCase() + '-BARU';
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
	}).fail(function(xhr) {
		console.log(xhr);
	});
}
</script>
