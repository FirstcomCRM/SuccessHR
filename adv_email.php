<?php
    include_once '../connect.php';
    include_once 'system.php';
    include_once '../include_function.php';
    include_once 'class/Radv.php'; 
    $o = new Radv();
    if($radv_id == ""){
        $o->radv_id = escape($_REQUEST['radv_id']);
    }else{
        $o->radv_id = escape($radv_id);
    }
    if($o->fetchRadvDetail(" AND radv_id = '$o->radv_id'","","",1)){
       $radv_email_content = $o->radv_email_content;
       
    }
    $include_webroot = include_webroot;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge"><!--<![endif]-->
    <meta name="viewport" content="width=device-width">
    <title> </title>
    <style type="text/css">
.wrapper a:hover {
  text-decoration: none !important;
}
.btn a:hover,
.footer__links a:hover {
  opacity: 0.8;
}
.wrapper .footer__share-button:hover {
  color: #ffffff !important;
  opacity: 0.8;
}
a[x-apple-data-detectors] {
  color: inherit !important;
  text-decoration: none !important;
  font-size: inherit !important;
  font-family: inherit !important;
  font-weight: inherit !important;
  line-height: inherit !important;
}
.column {
  padding: 0;
  text-align: left;
  vertical-align: top;
}
.mso .font-avenir,
.mso .font-cabin,
.mso .font-open-sans,
.mso .font-ubuntu {
  font-family: sans-serif !important;
}
.mso .font-bitter,
.mso .font-merriweather,
.mso .font-pt-serif {
  font-family: Georgia, serif !important;
}
.mso .font-lato,
.mso .font-roboto {
  font-family: Tahoma, sans-serif !important;
}
.mso .font-pt-sans {
  font-family: "Trebuchet MS", sans-serif !important;
}
.mso .footer p {
  margin: 0;
}
@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
  .fblike {
    background-image: url(https://i7.createsend1.com/static/eb/master/13-the-blueprint-3/images/fblike@2x.png) !important;
  }
  .tweet {
    background-image: url(https://i8.createsend1.com/static/eb/master/13-the-blueprint-3/images/tweet@2x.png) !important;
  }
  .linkedinshare {
    background-image: url(https://i9.createsend1.com/static/eb/master/13-the-blueprint-3/images/lishare@2x.png) !important;
  }
  .forwardtoafriend {
    background-image: url(https://i2.createsend1.com/static/eb/master/13-the-blueprint-3/images/forward@2x.png) !important;
  }
}
@media only screen and (max-width: 620px) {
  .wrapper .size-18,
  .wrapper .size-20 {
    font-size: 17px !important;
    line-height: 26px !important;
  }
  .wrapper .size-22 {
    font-size: 18px !important;
    line-height: 26px !important;
  }
  .wrapper .size-24 {
    font-size: 20px !important;
    line-height: 28px !important;
  }
  .wrapper .size-26 {
    font-size: 22px !important;
    line-height: 31px !important;
  }
  .wrapper .size-28 {
    font-size: 24px !important;
    line-height: 32px !important;
  }
  .wrapper .size-30 {
    font-size: 26px !important;
    line-height: 34px !important;
  }
  .wrapper .size-32 {
    font-size: 28px !important;
    line-height: 36px !important;
  }
  .wrapper .size-34,
  .wrapper .size-36 {
    font-size: 30px !important;
    line-height: 38px !important;
  }
  .wrapper .size-40 {
    font-size: 32px !important;
    line-height: 40px !important;
  }
  .wrapper .size-44 {
    font-size: 34px !important;
    line-height: 43px !important;
  }
  .wrapper .size-48 {
    font-size: 36px !important;
    line-height: 43px !important;
  }
  .wrapper .size-56 {
    font-size: 40px !important;
    line-height: 47px !important;
  }
  .wrapper .size-64 {
    font-size: 44px !important;
    line-height: 50px !important;
  }
  .divider {
    Margin-left: auto !important;
    Margin-right: auto !important;
  }
  .btn a {
    display: block !important;
    font-size: 14px !important;
    line-height: 24px !important;
    padding: 12px 10px !important;
    width: auto !important;
  }
  .btn--shadow a {
    padding: 12px 10px 13px 10px !important;
  }
  .image img {
    height: auto;
    width: 100%;
  }
  .layout,
  .column,
  .preheader__webversion,
  .header td,
  .footer,
  .footer__left,
  .footer__right,
  .footer__inner {
    width: 320px !important;
  }
  .preheader__snippet,
  .layout__edges {
    display: none !important;
  }
  .preheader__webversion {
    text-align: center !important;
  }
  .header__logo {
    Margin-left: 20px;
    Margin-right: 20px;
  }
  .layout--full-width {
    width: 100% !important;
  }
  .layout--full-width tbody,
  .layout--full-width tr {
    display: table;
    Margin-left: auto;
    Margin-right: auto;
    width: 320px;
  }
  .column,
  .layout__gutter,
  .footer__left,
  .footer__right {
    display: block;
    Float: left;
  }
  .footer__inner {
    text-align: center;
  }
  .footer__links {
    Float: none;
    Margin-left: auto;
    Margin-right: auto;
  }
  .footer__right p,
  .footer__share-button {
    display: inline-block;
  }
  .layout__gutter {
    font-size: 20px;
    line-height: 20px;
  }
  .layout--no-gutter.layout--has-border:not(.layout--full-width),
  .layout--has-gutter.layout--has-border .column__background {
    width: 322px !important;
  }
  .layout--has-gutter.layout--has-border {
    left: -1px;
    position: relative;
  }
}
@media only screen and (max-width: 320px) {
  .border {
    display: none;
  }
  .layout--no-gutter.layout--has-border:not(.layout--full-width),
  .layout--has-gutter.layout--has-border .column__background {
    width: 320px !important;
  }
  .layout--has-gutter.layout--has-border {
    left: 0 !important;
  }
}
.bottomlink a{
        background-color:white !important;
        color: #888794 !important;
        font-size: 14px !important;
}
.bottomlink a:hover{

        color: #9ec9df !important;
        font-size: 14px !important;
}
</style>
    
  <!--[if !mso]><!--><style type="text/css">
@import url(https://fonts.googleapis.com/css?family=Bitter:400,700,400italic|Roboto:400,700,400italic,700italic);
</style><link href="https://fonts.googleapis.com/css?family=Bitter:400,700,400italic|Roboto:400,700,400italic,700italic" rel="stylesheet" type="text/css"><!--<![endif]--><style type="text/css">
body,.wrapper{background-color:#eaf7fc}.wrapper h1{color:#345e9e;font-size:26px;line-height:34px}.wrapper h1{}.wrapper h1{font-family:Bitter,Georgia,serif}.mso .wrapper h1{font-family:Georgia,serif !important}.wrapper h2{color:#345e9e;font-size:20px;line-height:28px}.wrapper h2{}.wrapper h2{font-family:Bitter,Georgia,serif}.mso .wrapper h2{font-family:Georgia,serif !important}.wrapper h3{color:#345e9e;font-size:16px;line-height:24px}.wrapper h3{}.wrapper h3{font-family:Bitter,Georgia,serif}.mso .wrapper h3{font-family:Georgia,serif !important}.wrapper a{color:#3797e6}.wrapper a:hover{color:#1462a3}@media only screen and (max-width: 620px){.wrapper h1{}.wrapper h1{font-size:22px;line-height:31px}.wrapper h2{}.wrapper h2{font-size:17px;line-height:26px}.wrapper h3{}.wrapper p{}}.column,.column__background 
td{color:#888794;font-size:14px;line-height:21px}.column,.column__background td{font-family:Roboto,Tahoma,sans-serif}.mso .column,.mso .column__background td{font-family:Tahoma,sans-serif !important}.border{background-color:#91d5ef}.layout--no-gutter.layout--has-border:not(.layout--full-width),.layout--has-gutter.layout--has-border .column__background,.layout--full-width.layout--has-border{border-top:1px solid #91d5ef;border-bottom:1px solid #91d5ef}.wrapper blockquote{border-left:4px solid #91d5ef}.divider{background-color:#91d5ef}.wrapper .btn a{color:#fff}.wrapper .btn a{font-family:Roboto,Tahoma,sans-serif}.mso .wrapper .btn a{font-family:Tahoma,sans-serif !important}.wrapper .btn a:hover{color:#fff }.btn--flat a,.btn--shadow a,.btn--depth a{background-color:#9ec9df}.btn--ghost a{border:1px solid 
#9ec9df}.preheader--inline,.footer__left{color:#799ba8}.preheader--inline,.footer__left{font-family:Roboto,Tahoma,sans-serif}.mso .preheader--inline,.mso .footer__left{font-family:Tahoma,sans-serif !important}.wrapper .preheader--inline a,.wrapper .footer__left a{color:#799ba8}.wrapper .preheader--inline a:hover,.wrapper .footer__left a:hover{color:#799ba8 }.header__logo{color:#c3ced9}.header__logo{font-family:Roboto,Tahoma,sans-serif}.mso .header__logo{font-family:Tahoma,sans-serif !important}.wrapper .header__logo a{color:#c3ced9}.wrapper .header__logo a:hover{color:#859bb1}.footer__share-button{background-color:#757c7e}.footer__share-button{font-family:Roboto,Tahoma,sans-serif}.mso .footer__share-button{font-family:Tahoma,sans-serif !important}.layout__separator--inline{font-size:20px;line-height:20px;mso-line-height-rule:exactly}
</style>
    <meta name="robots" content="noindex,nofollow"></meta>
<meta property="og:title" content="My First Campaign"></meta>
</head>
<!--[if mso]>
  <body class="mso">
<![endif]-->
<!--[if !mso]><!-->
  <body class="half-padding" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #eaf7fc;">
<!--<![endif]-->
    <div class="wrapper" style="background-color: #eaf7fc;">
      <table style="border-collapse: collapse;table-layout: fixed;color: #799ba8;font-family: Roboto,Tahoma,sans-serif;" align="center">
        <tbody><tr>
          <td class="preheader__snippet" style="padding: 10px 0 5px 0;vertical-align: top;width: 280px;">
            
          </td>
          <td class="preheader__webversion" style="text-align: right;padding: 10px 0 5px 0;vertical-align: top;width: 280px;">
            
          </td>
        </tr>
      </tbody></table>
      <table class="header" style="border-collapse: collapse;table-layout: fixed;Margin-left: auto;Margin-right: auto;" align="center">
        <tbody><tr>
          <td style="padding: 0;width: 560px;">
            <div class="header__logo emb-logo-margin-box" style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #c3ced9;font-family: Roboto,Tahoma,sans-serif;">
              <div class="logo-center" style="font-size:0px !important;line-height:0 !important;" align="center" id="emb-email-header"><img style="height: auto;width: 100%;border: 0;max-width: 231px;" src="https://i1.createsend1.com/ei/i/E5/D00/236/180057/csfinal/boxun8.png" alt="" width="231" height="87"></div>
            </div>
          </td>
        </tr>
      </tbody></table>
      <table class="layout layout--no-gutter" style="border-collapse: collapse;table-layout: fixed;Margin-left: auto;Margin-right: auto;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #ffffff;" align="center">
        <tbody><tr>
          <td class="column" style="padding: 0;text-align: left;vertical-align: top;color: #888794;font-size: 14px;line-height: 21px;font-family: Roboto,Tahoma,sans-serif;width: 600px;">
            <div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 12px;">
            <?php echo htmlspecialchars_decode($radv_email_content);?>
           </div>
    <div style="Margin-left: 20px;Margin-right: 20px;">
      <div class="btn btn--flat" style="Margin-bottom: 20px;text-align: center;">
        Copyright © 2016 Boxun8. All rights reserved.
      </div>
    </div>
    <div style="Margin-left: 20px;Margin-right: 20px;">
      <div class="btn btn--flat bottomlink" style="Margin-bottom: 20px;text-align: center;">
          <a href = "<?php echo $include_webroot;?>oc">线上娱乐城</a>  |  <a href = "<?php echo $include_webroot;?>ld">免费彩金</a>  |  <a href = "<?php echo $include_webroot;?>bl">黑莊游戏城</a>  |  <a href = "<?php echo $include_webroot;?>provider">游戏供应商</a>  |  <a href = "<?php echo $include_webroot;?>livescore">体育积分榜</a>
      </div>
    </div>
          </td>
        </tr>
      </tbody></table>
  
      
      
    </div>

</body></html>
