<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
   <div class="header-icon">
      <i class="fa fa-sign-out"></i>
   </div>
   <div class="header-title">
      <p class="table-header"><?=isset($title) ? $title : ''?></p>
      <?=isset($sub_title) ? '<p>'.$sub_title.'</p>' : ''?>
   </div>
</section>
<section class="content">
   <div class="row">
      <div class="col-md-2">
         <div class="box box-primary">
            <div class="box-body box-profile">
               <img class="profile-user-img img-responsive" <?=(isset($photo) && $photo) ? ('src="'.$photo.'"') : '' ?> alt="User profile picture">
               <h3 class="profile-username text-center"><?=$query->full_name?></h3>
               <p class="text-muted text-center"><?=$query->street_address?></p>
            </div>
         </div>
      </div>
      <div class="col-md-10">
         <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
               <li class="active"><a href="#tab_1" data-toggle="tab">PENUGASAN</a></li>
               <li><a href="#tab_2" data-toggle="tab">IDENTITAS</a></li>
               <li><a href="#tab_3" data-toggle="tab">ALAMAT</a></li>
               <li><a href="#tab_4" data-toggle="tab">DATA PRIBADI</a></li>
               <li><a href="#tab_5" data-toggle="tab">KEPEGAWAIAN</a></li>
               <li><a href="#tab_6" data-toggle="tab">KOMPETENSI KHUSUS</a></li>
               <li><a href="#tab_7" data-toggle="tab">KONTAK</a></li>
            </ul>
            <div class="tab-content">
               <div class="tab-pane active" id="tab_1">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="assignment_letter_number" class="col-sm-4 control-label">Nomor Surat Tugas</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->assignment_letter_number?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="assignment_letter_date" class="col-sm-4 control-label">Tanggal Surat Tugas</label>
                           <div class="col-sm-8">
                              <div class="input-group">
                                 <p class="form-control-static"><?=indo_date($query->assignment_letter_date)?></p>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="assignment_start_date" class="col-sm-4 control-label">TMT Tugas</label>
                           <div class="col-sm-8">
                              <div class="input-group">
                                 <p class="form-control-static"><?=indo_date($query->assignment_start_date)?></p>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="parent_school_status" class="col-sm-4 control-label">Status Sekolah Induk</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=filter_var($query->parent_school_status, FILTER_VALIDATE_BOOLEAN) ? 'Ya':'Tidak'?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_2">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="full_name" class="col-sm-4 control-label">Nama Lengkap</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->full_name?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="gender" class="col-sm-4 control-label">Jenis Kelamin</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->gender == 'P' ? 'Perempuan' : 'Laki-laki'?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="nik" class="col-sm-4 control-label">NIK</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->nik?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="birth_place" class="col-sm-4 control-label">Tempat Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->birth_place?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="mother_name" class="col-sm-4 control-label">Nama Ibu Kandung</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->mother_name?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="birth_date" class="col-sm-4 control-label">Tanggal Lahir</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=indo_date($query->birth_date)?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_3">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="street_address" class="col-sm-4 control-label">Alamat Jalan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->street_address?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="rt" class="col-sm-4 control-label">RT</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->rt?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="rw" class="col-sm-4 control-label">RW</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->rw?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="sub_village" class="col-sm-4 control-label">Nama Dusun</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->sub_village?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="village" class="col-sm-4 control-label">Nama Kelurahan/Desa</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->village?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="sub_district" class="col-sm-4 control-label">Kecamatan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->sub_district?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="district" class="col-sm-4 control-label">Kota/Kabupaten</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->district?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="postal_code" class="col-sm-4 control-label">Kode Pos</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->postal_code?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_4">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="religion_id" class="col-sm-4 control-label">Agama</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->religion?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="marriage_status_id" class="col-sm-4 control-label">Status Perkawinan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->marriage_status?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="spouse_name" class="col-sm-4 control-label">Nama Suami/Istri</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->spouse_name?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="spouse_employment_id" class="col-sm-4 control-label">Pekerjaan Suami / Istri</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->spouse_employment?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="citizenship" class="col-sm-4 control-label">Kewarganegaraan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->citizenship == 'WNI' ? 'Warga Negara Indonesia' : 'Warga Negara Asing'?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="country" class="col-sm-4 control-label">Nama Negara</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->country?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="npwp" class="col-sm-4 control-label">NPWP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->npwp?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_5">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="employment_status_id" class="col-sm-4 control-label">Status Kepegawaian</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->employment_status?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="nip" class="col-sm-4 control-label">NIP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->nip?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="niy" class="col-sm-4 control-label">NIY</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->niy?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="nuptk" class="col-sm-4 control-label">NUPTK</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->nuptk?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="employment_type_id" class="col-sm-4 control-label">Jenis GTK</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->employment_type?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="decree_appointment" class="col-sm-4 control-label">SK Pengangkatan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->decree_appointment?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="appointment_start_date" class="col-sm-4 control-label">TMT Pengangkatan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=indo_date($query->appointment_start_date)?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="institution_lifter_id" class="col-sm-4 control-label">Lembaga Pengangkat</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->institution_lifter?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="decree_cpns" class="col-sm-4 control-label">SK CPNS</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->decree_cpns?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="pns_start_date" class="col-sm-4 control-label">TMT PNS</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=indo_date($query->pns_start_date)?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="rank_id" class="col-sm-4 control-label">Pangkat/Golongan</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->rank?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="salary_source_id" class="col-sm-4 control-label">Sumber Gaji</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->salary_source?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_6">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="headmaster_license" class="col-sm-4 control-label">Punya Lisensi Kepala Sekolah?</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=filter_var($query->headmaster_license, FILTER_VALIDATE_BOOLEAN) ? 'Ya' : 'Tidak'?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="laboratory_skill_id" class="col-sm-4 control-label">Keahlian Laboratorium</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->laboratory_skill?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="special_need_id" class="col-sm-4 control-label">Mampu Menangani Kebutuhan Khusus</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->special_need?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="braille_skills" class="col-sm-4 control-label">Keahlian Braile</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=filter_var($query->braille_skills, FILTER_VALIDATE_BOOLEAN) ? 'Ya' : 'Tidak'?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="sign_language_skills" class="col-sm-4 control-label">Keahlian Bahasa Isyarat</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=filter_var($query->sign_language_skills, FILTER_VALIDATE_BOOLEAN) ? 'Ya' : 'Tidak'?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="tab-pane" id="tab_7">
                  <form class="form-horizontal">
                     <div class="box-body">
                        <div class="form-group">
                           <label for="phone" class="col-sm-4 control-label">Nomor Telepon Rumah</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->phone?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="mobile_phone" class="col-sm-4 control-label">Nomor HP</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->mobile_phone?></p>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="email" class="col-sm-4 control-label">Email</label>
                           <div class="col-sm-8">
                              <p class="form-control-static"><?=$query->email?></p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
