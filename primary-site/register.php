<!DOCTYPE html>
<html>
<head>
    <title></title>
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/jquery.validate.min.js" ></script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3" style="padding: 50px 0;">
                <form id="registerForm" method="POST" action="http://app.test.dev/register/"  accept-charset="UTF-8" autocomplete="off" novalidate="novalidate" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Your Full Name</label>
                        <div class=" col-sm-9 field-wrapper field--text">
                            <input name="name" minlength="2" type="text" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Email</label>
                        <div class=" col-sm-9 field-wrapper field--text">
                            <input name="email" type="email" value="" class="form-control" required>
                            <p></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Password</label>
                        <div class=" col-sm-9 field-wrapper field--text">
                            <input autocomplete="new-password" name="password" type="password" value="" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-field">
                        <div class="row">
                            <div class="col-sm-9 col-sm-offset-3">
                                <button id="btn-submit-create" type="submit" class="btn btn--primary btn--large btn--block">
                                    <i class="fa fa-spinner btn__loader" aria-hidden="true"></i> Get Started!
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $("#registerForm").validate({
            rules: {
                name: {required: true},
                email: {
                    required: true,
                    email: true,
                    remote: 'http://app.test.dev/checkemail'
                },
                password: {
                    required: true,
                    minlength: 8
                }
            },
            messages: {
                email: {
                    remote: "Email address already in use."
                }
            }
        });
    </script>
    <style type="text/css">
        input.error {
            border-color: #d34a56;
        }
        .field-wrapper label.error {
            color: #d34a56;
        }
    </style>
</body>
</html>
