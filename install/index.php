<?php
$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$base_url .= "://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$base_url = str_replace('install/','',$base_url);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Installasi CMS Sekolahku</title>
        <link rel="icon" href="favicon.png">
    <link rel="stylesheet" type="text/css" href="<?=$base_url?>install/style.css">
    <script type="text/javascript" src="<?=$base_url?>install/jquery.min.js"></script>
    <script type="text/javascript" src="<?=$base_url?>install/FormWizard.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#install").FormWizard({ submitButton: 'save' });
            $('#save').on('click', function() {
                $('#loading').show();
                $('#steps, #install').slideUp();
                var values = {
                    database_name: $('#database_name').val(),
                    database_username: $('#database_username').val(),
                    database_password: $('#database_password').val(),
                    database_hostname: $('#database_hostname').val(),
                    school_level: $('#school_level').val(),
                    school_name: $('#school_name').val(),
                    street_address: $('#street_address').val(),
                    tagline: $('#tagline').val(),
                    user_name: $('#user_name').val(),
                    user_full_name: $('#user_full_name').val(),
                    user_email: $('#user_email').val(),
                    user_password: $('#user_password').val()
                };
                $.post('<?=$base_url;?>/install/process.php', values, function(response) {
                    $('#loading').hide();
                    var errors = typeof response == 'string' ? JSON.parse(response) : response;
                    if (!errors.length) {
                        var info = '<h2 style="color:green;">Proses Installasi Selesai</h2>'
                            + '<p>Silahkan hapus folder <i><b>INSTALL</b></i> di <b>Root Direktori CMS</b></p>';
                        $('#info').html(info);
                    } else {
                        var info = '<h2 style="color:red;">Proses Installasi Gagal</h2>';
                            info += '<ul>';
                            for(var z in errors) {
                                info += '<li>' + errors[ z ] + '</li>';
                            }
                            info += '</ul>';
                        $('#info').html(info);
                        setTimeout(function() {
                            $('#info').empty();
                            $('#steps, #install').slideDown();
                        }, (3000 * errors.length));
                    }
                });
            });
        });
    </script>
</head>
<body>
    <h2>Proses Installasi CMS Sekolahku</h2>
    <div id="main">
        <img id="loading" style="display: none;" src="<?=$base_url?>install/loading.gif">
        <div id="info"></div>
        <form id="install">
            <fieldset>
                <legend>Konfigurasi Database</legend>
                <label for="database_name">Nama Database</label>
                <input id="database_name" type="text" placeholder="Nama database yang kaan digunakan" />
                <label for="database_username">Nama Akun</label>
                <input id="database_username" type="text" placeholder="Nama Akun Database" />
                <label for="database_password">Kata Sandi</label>
                <input id="database_password" type="password" placeholder="Kata Sandi Database" />
                <label for="database_hostname">Host</label>
                <input id="database_hostname" type="text" placeholder="Nama Host Database. Cth : localhost" />
            </fieldset>
            <fieldset>
                <legend>Informasi Situs</legend>
                <label for="school_level">Jenjang Sekolah</label>
                <select id="school_level">
                    <option value="1">Sekolah Dasar (SD / Sederajat)</option>
                    <option value="2">Sekolah Menengah Pertama (SMP / Sederajat)</option>
                    <option value="3">Sekolah Menengah Atas (SMA / Sederajat)</option>
                    <option value="4">Sekolah Menengah Kejuruan (SMK)</option>
                    <option value="5">Universitas</option>
                    <option value="6">Sekolah Tinggi</option>
                    <option value="7">Politeknik</option>
                </select>
                <label for="school_name">Nama Sekolah / Kampus</label>
                <input id="school_name" type="text" />
                <label for="street_address">Alamat</label>
                <input id="street_address" type="text" />
                <label for="tagline">Slogan</label>
                <input id="tagline" type="text" />
            </fieldset>
            <fieldset>
                <legend>Konfigurasi Akun</legend>
                <label for="user_name">Nama Akun</label>
                <input id="user_name" type="text" placeholder="Digunakan untuk login website" />
                <label for="user_full_name">Nama Lengkap</label>
                <input id="user_full_name" type="text" />
                <label for="user_email">Email</label>
                <input id="user_email" type="text" placeholder="Masukan email dengan format yang valid" />
                <label for="user_password">Kata Sandi</label>
                <input id="user_password" type="password"  placeholder="Digunakan untuk login website" />
            </fieldset>
            <input id="save" type="button" value="INSTALL" />
        </form>
    </div>
    <p>Copyright &copy; 2014 - <?=date('Y');?> CMS Sekolahku. All Rights Reserved.</p>
    <p>Powered by <a href="https://ibrahimelgibran.com">ibrahimelgibran</a></p>
</body>
</html>
