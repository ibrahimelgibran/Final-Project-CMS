<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?=link_tag('assets/plugins/magnific-popup/magnific-popup.css');?>
<script type="text/javascript" src="<?=base_url('assets/plugins/magnific-popup/magnific-popup.js')?>"></script>
<?php $this->load->view('backend/grid_index');?>
<script type="text/javascript">
DS.Employments = _H.StrToObject('<?=get_options('employments')?>');
DS.EmploymentStatus = _H.StrToObject('<?=get_options('employment_status')?>');
DS.EmploymentTypes = _H.StrToObject('<?=get_options('employment_types')?>');
DS.InstitutionLifters = _H.StrToObject('<?=get_options('institution_lifters')?>');
DS.Ranks = _H.StrToObject('<?=get_options('ranks')?>');
DS.SalarySources = _H.StrToObject('<?=get_options('salary_sources')?>');
DS.LaboratorySkills = _H.StrToObject('<?=get_options('laboratory_skills')?>');
DS.SpecialNeeds = _H.StrToObject('<?=get_options('special_needs')?>');
DS.Religions = _H.StrToObject('<?=get_options('religions')?>');
DS.MarriageStatus = _H.StrToObject('<?=get_options('marriage_status')?>');
var _grid = 'EMPLOYEES', _form = _grid + '_FORM';
new GridBuilder( _grid , {
   controller:'employees/employees',
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
            return UPLOAD(_form + '.OnUpload(' + row.id + ')', 'image', 'Upload Photo');
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
         renderer: function( row ) {
            return A('create_account(' + row.nik + ', ' + row.id +')', 'Aktivasi Akun', '<i class="fa fa-key"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      {
         header: '<i class="fa fa-search"></i>',
         renderer: function( row ) {
            return Ahref(_BASE_URL + 'employees/employees/profile/' + row.id, 'Preview', '<i class="fa fa-search"></i>');
         },
         exclude_excel: true,
         sorting: false
      },
      { header:'NIK', renderer:'nik' },
      { header:'Nama Lengkap', renderer:'full_name' },
      {
         header:'Jenis GTK',
         renderer: function( row ) {
            return row.employment_type ? row.employment_type : '';
         },
         sort_field: 'employment_type'
      },
      { header:'Tempat Lahir', renderer:'birth_place' },
      {
         header:'Tanggal Lahir',
         renderer: function( row ) {
            return row.birth_date && row.birth_date !== '0000-00-00' ? _H.ToIndonesianDate(row.birth_date) : '';
         },
         sort_field: 'birth_date'
      },
      {
         header:'L/P',
         renderer: function( row ) {
            return row.gender == 'M' ? 'L' : 'P';
         },
         sort_field: 'gender'
      }
   ],
   resize_column: 7,
   to_excel: false,
   extra_buttons: '<a class="btn btn-sm btn-default add" href="javascript:void(0)" onclick="employee_reports()" data-toggle="tooltip" data-placement="top" title="Export to Excel"><i class="fa fa-file-excel-o"></i></a>' +
   '<button title="Aktivasi Semua Akun ?" onclick="create_accounts()" class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top"><i class="fa fa-key"></i></button>'
});

new FormBuilder( _form , {
   controller:'employees/employees',
   fields: [
      { label:'Nomor Surat Tugas', name:'assignment_letter_number' },
      { label:'Tanggal Surat Tugas', name:'assignment_letter_date', type:'date' },
      { label:'TMT Tugas', name:'assignment_start_date', type:'date' },
      { label:'Status Sekolah Induk', name:'parent_school_status', type:'select', datasource:DS.TrueFalse },
      { label:'Nama Lengkap', name:'full_name', },
      { label:'Jenis Kelamin', name:'gender', type:'select', datasource:DS.Gender },
      { label:'NIK', name:'nik' },
      { label:'Tempat Lahir', name:'birth_place' },
      { label:'Tanggal Lahir', name:'birth_date', type:'date' },
      { label:'Nama Ibu Kandung', name:'mother_name' },
      { label:'Alamat Jalan', name:'street_address' },
      { label:'RT', name:'rt' },
      { label:'RW', name:'rw' },
      { label:'Nama Dusun', name:'sub_village' },
      { label:'Nama Kelurahan/Desa', name:'village' },
      { label:'Nama Kecamatan', name:'sub_district' },
      { label:'Nama Kota/Kabupaten', name:'district' },
      { label:'Kode Pos', name:'postal_code' },
      { label:'Agama', name:'religion_id', type:'select', datasource:DS.Religions },
      { label:'Status Perkawinan', name:'marriage_status_id', type:'select', datasource:DS.MarriageStatus },
      { label:'Nama Suami/Istri', name:'spouse_name' },
      { label:'Pekerjaan Suami/Istri', name:'spouse_employment_id', type:'select', datasource:DS.Employments },
      { label:'Kewarganegaraan', name:'citizenship', type:'select', datasource:DS.Citizenship },
      { label:'Nama Negara', name:'country' },
      { label:'NPWP', name:'npwp' },
      { label:'Status Kepegawaian', name:'employment_status_id', type:'select', datasource:DS.EmploymentStatus },
      { label:'NIP', name:'nip' },
      { label:'NIY/NIGK', name:'niy' },
      { label:'NUPTK', name:'nuptk' },
      { label:'Jabatan', name:'employment_type_id', type:'select', datasource:DS.EmploymentTypes },
      { label:'SK Pengangkatan', name:'decree_appointment' },
      { label:'TMT Pengangkatan', name:'appointment_start_date', type:'date' },
      { label:'Lembaga Pengangkat', name:'institution_lifter_id', type:'select', datasource:DS.InstitutionLifters },
      { label:'SK CPNS', name:'decree_cpns' },
      { label:'TMT PNS', name:'pns_start_date', type:'date' },
      { label:'Pangkat/Golongan', name:'rank_id', type:'select', datasource:DS.Ranks },
      { label:'Sumber Gaji', name:'salary_source_id', type:'select', datasource:DS.SalarySources },
      { label:'Punya Lisensi Kepala Sekolah', name:'headmaster_license', type:'select', datasource:DS.TrueFalse },
      { label:'Keahlian Laboratorium', name:'laboratory_skill_id', type:'select', datasource:DS.LaboratorySkills },
      { label:'Mampu Menangani Kebutuhan Khusus', name:'special_need_id', type:'select', datasource:DS.SpecialNeeds },
      { label:'Keahlian Braile', name:'braille_skills', type:'select', datasource:DS.TrueFalse },
      { label:'Keahlian Bahasa Isyarat', name:'sign_language_skills', type:'select', datasource:DS.TrueFalse },
      { label:'No. Telepon', name:'phone' },
      { label:'No. Handphone', name:'mobile_phone' },
      { label:'Email', name:'email' },
      { label: 'Guru Mapel', name: 'guru_mapel'},
      { label: 'NBM', name: 'nbm'},
      { label: 'Motto', name: 'motto'}
   ]
});

function create_account( nik, id ) {
   eModal.confirm('Apakah anda yakin akan mengaktifkan akun dengan NIK ' + nik + '?', 'Konfirmasi').then(function() {
      $.post(_BASE_URL + 'employees/employees/create_account', {'id':id}, function(response) {
         var res = _H.StrToObject( response );
         _H.Notify(res.status, _H.Message(res.message));
      });
   });
}

function create_accounts() {
   eModal.confirm('Nama Pengguna dan Kata Sandi akan digenerate dengan menggunakan NIK. Apakah anda yakin akan mengaktifkan seluruh akun Guru Dan Tenaga Kependidikan?', 'Konfirmasi').then(function() {
      _H.Loading( true );
      $.post(_BASE_URL + 'employees/employees/create_accounts', {}, function( response ) {
         _H.Loading( false );
         var res = _H.StrToObject( response );
         _H.Notify(res.status, _H.Message(res.message));
      });
   });
}

function preview( image ) {
   $.magnificPopup.open({
      items: {
         src: _BASE_URL + 'media_library/employees/' + image
      },
      type: 'image'
   });
}

function employee_reports() {
   var fields = {
      nik:'NIK',
      full_name:'Nama Lengkap',
      gender:'L/P',
      birth_place:'Tempat Lahir',
      birth_date:'Tanggal Lahir',
      assignment_letter_number:'Nomor Surat Tugas',
      assignment_letter_date:'Tanggal Surat Tugas',
      assignment_start_date:'TMT Tugas',
      parent_school_status:'Status Sekolah Induk',
      mother_name:'Nama Ibu Kandung',
      street_address:'Alamat Jalan',
      rt:'RT',
      rw:'RW',
      sub_village:'Dusun',
      village:'Kelurahan',
      sub_district:'Kecamatan',
      district:'Kota/Kabupaten',
      postal_code:'Kode Pos',
      religion: 'Agama',
      marriage_status: 'Status Pernikahan',
      spouse_name:'Nama Pasangan',
      spouse_employment: 'Pekerjaan Pasangan',
      citizenship:'Kewarganegaraan',
      country:'Nama Negara',
      npwp:'NPWP',
      employment_status: 'Status Kepegawaian',
      nip:'NIP',
      niy:'NIY',
      nuptk:'NUPTK',
      employment_type: 'Jenis Guru dan Tenaga Kependidikan (GTK)',
      decree_appointment:'SK Pengangkatan',
      appointment_start_date:'TMT Pengangkatan',
      institution_lifter: 'Lembaga Pengangkat',
      decree_cpns:'SK CPNS',
      pns_start_date:'TMT CPNS',
      rank: 'Pangkat/Golongan',
      salary_source: 'Sumber Gaji',
      headmaster_license:'Punya Lisensi Kepala Sekolah?',
      laboratory_skill: 'Keahlian Laboratorium',
      special_need: 'Mampu Menangani Kebutuhan Khusus ?',
      braille_skills:'Keahlian Braile ?',
      sign_language_skills:'Keahlian Bahasa Isyarat ?',
      phone:'Telepon',
      mobile_phone:'Handphone',
      email:'Email'
	};
   $.post(_BASE_URL + 'employees/employees/employee_reports', {}, function(response) {
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
		var fileName = 'DATA-' + __EMPLOYEE.toUpperCase();
		_H.ExportToExcel( elementId, fileName ); // Export to Excel
   }).fail(function(xhr) {
      console.log(xhr);
   });
}
</script>
