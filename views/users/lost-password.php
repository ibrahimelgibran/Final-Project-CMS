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
   <script src="<?=base_url('assets/js/login.min.js');?>"></script>
   <script type="text/javascript">
   function get_new_password() {
      var values = {
         email: $('#email').val()
      };
      _H.Loading( true );
      $.post(_BASE_URL + 'lost-password/process', values, function( response ) {
         _H.Loading( false );
         var res = _H.StrToObject( response );
         _H.Notify(res.status, res.message);
         $('#email').val('');
      });
   }
   </script>
</head>
<body class="text-center">
   <noscript>
      You need to enable javaScript to run this app.
   </noscript>
   <form class="form-signin">
      <img class="mb-4" src="<?=base_url('media_library/images/' . __session('logo'))?>">
      <h2 class="font-weight-bold"><font style="color:#343a40;">Lost Password</font></h2>
      <h6 class="text-dark mb-4">You will receive a link to create a new password by email</h6>
      <label for="email" class="sr-only">Email Address</label>
      <input autofocus autocomplete="off" type="text" id="email" name="email" placeholder="Please enter your email account" class="form-control rounded-0 border border-secondary border-bottom-0">
      <button onclick="get_new_password(); return false;" class="btn btn-lg btn-primary btn-block rounded-0" id="submit">Get New Password</button>
      <p class="pt-3 text-muted">
         <a href="<?=site_url('login')?>">Log In ?</a> | Back to <a href="<?=base_url()?>"><?=__session('school_name')?></a>
      </p>
   </form>
</body>
</html>
