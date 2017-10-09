<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<style>
/* -------------------------------------
    GLOBAL
------------------------------------- */
* {
  font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
  font-size: 100%;
  line-height: 1.6em;
  margin: 0;
  padding: 0;
}

img {
  max-width: 600px;
  width: auto;
}

body {
  -webkit-font-smoothing: antialiased;
  height: 100%;
  -webkit-text-size-adjust: none;
  width: 100% !important;
}


/* -------------------------------------
    ELEMENTS
------------------------------------- */
a {
  color: #36c842;
}

.btn-primary {
  margin-top: 30px;
  margin-bottom: 15px;
  width: auto !important;
}

.btn--margin-bottom {
  margin-bottom: 30px;
}

.btn-primary td {
  background-color: #36c842; 
  border-radius: 3px;
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; 
  font-size: 16px; 
  text-align: center;
  vertical-align: top; 
  font-weight: 700;
}

.btn-primary td a {
  background-color: #36c842;
  border: solid 1px #36c842;
  border-radius: 25px;
  border-width: 10px 20px;
  display: inline-block;
  color: #ffffff;
  cursor: pointer;
  font-weight: bold;
  line-height: 2;
  text-decoration: none;
}

.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.padding {
  padding: 10px 0;
}


/* -------------------------------------
    BODY
------------------------------------- */
table.body-wrap {
  padding: 20px;
  width: 100%;
}

table.body-wrap .container {
  border: 1px solid #f0f0f0;
  padding: 50px;
  padding-bottom: 35px;
}


/* -------------------------------------
    FOOTER
------------------------------------- */
table.footer-wrap {
  clear: both !important;
  width: 100%;  
}

.footer-wrap .container p {
  color: #666666;
  font-size: 12px;
  
}

table.footer-wrap a {
  color: #999999;
}


/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, 
h2, 
h3 {
  color: #111111;
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  font-weight: 200;
  line-height: 1.2em;
  margin: 45px 0 30px;
}

h1 {
  font-size: 36px;
}
h2 {
  color: #36c842;
  font-size: 28px;
}
h3 {
  font-size: 22px;
}

p, 
ul, 
ol {
  font-size: 15px;
  font-weight: normal;
  margin-bottom: 15px;
}

ul li, 
ol li {
  margin-left: 5px;
  list-style-position: inside;
}

/* ---------------------------------------------------
    RESPONSIVENESS
------------------------------------------------------ */

/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
  clear: both !important;
  display: block !important;
  margin: 0 auto !important;
  max-width: 600px !important;
}

/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
  padding: 20px;
}

/* This should also be a block element, so that it will fill 100% of the .container */
.content {
  display: block;
  margin: 0 auto;
  max-width: 600px;
}

/* Let's make sure tables in the content area are 100% wide */
.content table {
  width: 100%;
}

@media only screen and (max-width: 600px) {
  table.body-wrap {
    padding: 0px !important;
    width: 100% !important;
    background: #ffffff;
  }

  table.body-wrap .container {
    border: 0px !important;
    padding: 15px !important;
    padding-bottom: 0px !important;
  }

  table.footer-wrap {
    padding-top: 15px !important;
  }
}

</style>
</head>

<body>

<div style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; -webkit-font-smoothing: antialiased; height: 100%; -webkit-text-size-adjust: none; width: 100% !important; margin: 0; padding: 0;">
  <!-- body -->
  <table class="body-wrap" bgcolor="#f6f6f6" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0; padding: 20px;">
    <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
      <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
      <td class="container" bgcolor="#FFFFFF" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; display: block !important; max-width: 600px !important; margin: 0 auto; padding: 50px 50px 35px; border: 1px solid #f0f0f0;">

        <!-- content -->
        <div class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; display: block; max-width: 600px; margin: 0 auto; padding: 0;">
        <table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0; padding: 0;">
          <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
            <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
              <a href="{!! config('app.url') !!}" target="_blank" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; color: #36c842; margin: 0; padding: 0;">
                <img src="{!! config('app.url') !!}/img/tecdonor-logo.png" alt="tecdonor" width="109" height="18" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; max-width: 600px; width: auto; margin: 0; padding: 0;">
              </a>
              <h2 style="font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 28px; line-height: 1.2em; color: #36c842; font-weight: 200; margin: 45px 0 30px; padding: 0;">@yield('title')</h2>
              @yield('content')
            </td>
          </tr>
        </table>
        </div>
        <!-- /content -->
        
      </td>
      <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
    </tr>
  </table>
  <!-- /body -->

  <!-- footer -->
  <table bgcolor="#f6f6f6" class="footer-wrap" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; width: 100%; margin: 0; padding: 0;">
    <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
      <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
      <td class="container" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; clear: both !important; display: block !important; max-width: 600px !important; margin: 0 auto; padding: 0;">
        
        <!-- content -->
        <div class="content" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; display: block; max-width: 600px; margin: 0 auto; padding: 0;">
          <table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; width: 100%; margin: 0; padding: 0;">
            <tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
              <td align="center" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;">
                <p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 12px; line-height: 1.6em; color: #666666; font-weight: normal; margin: 0 0 15px; padding: 0;">Tec Donor LLC, 140 Island Way #210 Clearwater, FL 33767</p>
              </td>
            </tr>
          </table>
        </div>
        <!-- /content -->
        
      </td>
      <td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6em; margin: 0; padding: 0;"></td>
    </tr>
  </table>
  <!-- /footer -->
</div>

</body>
</html>
