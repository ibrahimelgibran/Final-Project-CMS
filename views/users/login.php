<!doctype html>
<html lang="en">
<head>
   <title><?=__session('school_name')?></title>
   <meta charset="utf-8" />
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge" />
   <meta name="keywords" content="<?=__session('meta_keywords');?>"/>
   <meta name="description" content="<?=__session('meta_description');?>"/>
   <meta name="subject" content="Situs Pendidikan">
   <meta name="copyright" content="<?=__session('school_name')?>">
   <meta name="language" content="Indonesia">
   <meta name="robots" content="index,follow" />
   <meta name="revised" content="Sunday, July 18th, 2010, 5:15 pm" />
   <meta name="Classification" content="Education">
   <meta name="author" content="Anton Sofyan, 4ntonsofyan@gmail.com">
   <meta name="designer" content="Anton Sofyan, 4ntonsofyan@gmail.com">
   <meta name="reply-to" content="4ntonsofyan@gmail.com">
   <meta name="owner" content="Anton Sofyan">
   <meta name="url" content="https://www.sekolahku.web.id">
   <meta name="identifier-URL" content="https://www.sekolahku.web.id">
   <meta name="category" content="Admission, Education">
   <meta name="coverage" content="Worldwide">
   <meta name="distribution" content="Global">
   <meta name="rating" content="General">
   <meta name="revisit-after" content="7 days">
   <meta http-equiv="Expires" content="0">
   <meta http-equiv="Pragma" content="no-cache">
   <meta http-equiv="Cache-Control" content="no-cache">
   <meta http-equiv="Copyright" content="<?=__session('school_name');?>" />
   <meta http-equiv="imagetoolbar" content="no" />
   <meta name="revisit-after" content="7" />
   <meta name="webcrawlers" content="all" />
   <meta name="rating" content="general" />
   <meta name="spiders" content="all" />
   <meta itemprop="name" content="<?=__session('school_name');?>" />
   <meta itemprop="description" content="<?=__session('meta_description');?>" />
   <meta itemprop="image" content="<?=base_url('media_library/images/'. __session('logo'));?>" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <link rel="icon" href="<?=base_url('media_library/images/'.__session('favicon'));?>">
   <?=link_tag('assets/plugins/bootstrap-4/bootstrap.min.css');?>
   <?=link_tag('assets/plugins/toastr/toastr.css');?>
   <?=link_tag('assets/css/loading.css');?>
   <?=link_tag('assets/css/login.style.css');?>
   <script type="text/javascript">
   const _BASE_URL = '<?=base_url();?>', _CURRENT_URL = '<?=current_url();?>';
   </script>
   <script src="<?=base_url('assets/js/login.min.js?v=20210227');?>"></script>
   <script type="text/javascript">
   function login() {
      $('#submit, #user_name, #user_password').attr('disabled', 'disabled');
      var values = {
         user_name: $('#user_name').val(),
         user_password: $('#user_password').val()
      };
      _H.Loading( true );
      $.post(_BASE_URL + 'login/verify', values, function(response) {
         _H.Loading( false );
         var res = _H.StrToObject( response );
         _H.Notify(res.status, _H.Message(res.message));
         if (res.status == 'success') {
            window.location.href = _BASE_URL + 'dashboard';
         } else {
            $('#user_name, #user_password').val('');
            if ( res.ip_banned ) {
               $('#submit, #user_name, #user_password').attr('disabled', 'disabled');
               $('#login-info').text('The login page has been blocked for 10 minutes');
               $('#login-info').addClass('text-danger');
            } else {
               $('#submit, #user_name, #user_password').removeAttr('disabled');
            }
         }
      });
   }
   </script>
</head>
<body class="text-center">
   <noscript>You need to enable javaScript to run this app</noscript>
   <form class="form-signin">
      <img class="mb-4" src="<?=base_url('media_library/images/' . __session('logo'))?>">
      <h2 class="font-weight-bold"><font style="color:#343a40;"><?=__session('school_name');?></font></h2>
      <h6 class="font-weight-bold text-dark mb-4"><?=__session('tagline');?></h6>
      <label for="user_name" class="sr-only">Email Address</label>
      <input <?=$ip_banned ? 'disabled="disabled"' : '';?> autofocus autocomplete="off" type="text" id="user_name" name="user_name" placeholder="Username..." class="form-control rounded-0 border border-secondary border-bottom-0">
      <label for="user_password" class="sr-only">Password</label>
      <input <?=$ip_banned ? 'disabled="disabled"' : '';?> type="password" id="user_password" name="user_password" placeholder="Password..." class="form-control rounded-0 border border-secondary">
      <button <?=$ip_banned ? 'disabled="disabled"' : '';?> onclick="login(); return false;" class="btn btn-lg btn-primary btn-block rounded-0" id="submit">Sign in</button>
      <p class="pt-3 text-muted">
         <a href="<?=site_url('lost-password')?>">Lost Password ?</a> | Back to <a href="<?=base_url()?>"><?=__session('school_name')?></a>
      </p>
   </form>
</body>
</html>
