<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$bg = array('bg-01.jpg', 'bg-02.jpg', 'bg-03.jpg', 'bg-04.jpg' ); // array of filenames

$i = rand(0, count($bg)-1); // generate random number size of the array
$selectedBg = "$bg[$i]"; // set variable equal to which random filename was chosen
?><!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("assets/css/style.css"); ?>" />
    <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.1.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    <style>
        body
        {
            background-image: url(<?php echo base_url();?>/images/<?php echo $selectedBg; ?>);
            background-size: cover;
        }
        @media screen and (max-width: 750px) {
            body {
                background-image: url("<?php echo base_url();?>/images/bgrepat.jpg");
                background-repeat: repeat;
            }
        }
    </style>
    <meta charset="utf-8">
    <meta name="description" content="Social Feed">
    <meta name="keywords" content="social network">
    <meta name="author" content="Mansoor Khan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
    <title>Welcome to Social Feed</title>
<body>
<div class="container">
    <div class="navbar">
        <div class="navbar-inner">
            <a class="brand" href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url(); ?>/images/logolargetransparentdark.png" width="286" alt="">
            </a>
        </div>
    </div>
<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">Sign In</div>
            <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
        </div>

        <div style="padding-top:30px" class="panel-body" >

            <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
            <?php $attributess = array('id' => 'loginform', 'class' => 'form-horizontal'); ?>
            <?php echo form_open("welcome/login",$attributess); ?>

                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    <input id="email" type="text" class="form-control" name="email" value="" placeholder="Email">
                </div>

                <div style="margin-bottom: 25px" class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    <input id="password" type="password" class="form-control" name="password" placeholder="password">
                </div>



                <div class="input-group">
                    <div class="checkbox">
                        <label>
                            <input id="login-remember" type="checkbox" name="remember" value="1"> Remember me
                        </label>
                    </div>
                </div>


                <div style="margin-top:10px" class="form-group">
                    <!-- Button -->

                    <div class="col-sm-12 controls">
                        <input id="btn-login" class="btn btn-success" type="submit" value=" &nbsp Login" />
                        <?php
                        if(!empty($authUrl)) {?>
                        <a id="btn-fblogin" href="<?php echo $authUrl; ?>" class="btn btn-primary">Connect with Facebook</a>
                        <?php }?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-md-12 control">
                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                            Don't have an account!
                            <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                Sign Up Here
                            </a>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>



        </div>
    </div>
</div>
<div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Sign Up</div>
            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
        </div>
        <div class="panel-body" >
            <?php $attributes = array('id' => 'signupform', 'class' => 'form-horizontal'); ?>
            <?php echo form_open("welcome/registration",$attributes); ?>
            <div id="signupalert" style="display:none" class="alert alert-danger">
                <p>Error:</p>
                        <span>
                             <?php echo validation_errors('');?>
                        </span>
            </div>



            <div class="form-group">
                <label for="email" class="col-md-3 control-label">Email</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" id="email_address" name="email_address" placeholder="Email Address" value="<?php echo set_value('email_address'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="firstname" class="col-md-3 control-label">First Name</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo set_value('firstname'); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="lastname" class="col-md-3 control-label">Last Name</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name"value="<?php echo set_value('lastname'); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-md-3 control-label">Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" class="password" id="password" name="password" placeholder="Password"value="<?php echo set_value('password'); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="confirmpassword" class="col-md-3 control-label">Confirm Password</label>
                <div class="col-md-9">
                    <input type="password" class="form-control" name="con_password" id="con_password" placeholder="Password" value="<?php echo set_value('con_password'); ?>">
                </div>
            </div>

            <div class="form-group">
                <!-- Button -->
                <div class="col-md-offset-3 col-md-9">
                    <input id="btn-signup" class="btn btn-info" type="submit" value=" &nbsp Sign Up" />
                    <span style="margin-left:8px;">or</span>
                </div>
            </div>

            <div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">

                <div class="col-md-offset-3 col-md-9">
                    <?php
                    if(!empty($authUrl)) {?>
                  <a id="btn-fbsignup" type="button" class="btn btn-primary" href="<?php echo $authUrl; ?>"><i class="icon-facebook"></i>   Connect with Facebook</a>
                    <?php }?>
                </div>

            </div>



            <?php echo form_close(); ?>
        </div>
    </div>




</div>
</div>
</body>
</html>