<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
    <link rel="stylesheet" href="<?php echo base_url("assets/css/style.css"); ?>" />
    <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.1.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript"  src="<?php echo base_url(); ?>assets/js/AjaxFileUpload.js"></script>
    <meta charset="utf-8">
    <meta name="description" content="Social Feed">
    <meta name="keywords" content="social network">
    <meta name="author" content="Mansoor Khan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
    <title>Welcome to Social Feed</title>
<body>
<div id="wrapper">
<div id="header" class="navbar navbar-default">
    <div class="navbar-header">
        <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-collapse">
            <i class="icon-reorder">Menu</i>
        </button>
        <a class="navbar-brand" href="<?php echo base_url(); ?>">
            <img src="<?php echo base_url(); ?>/images/logolargetransparentdark.png" width="137">
        </a>
    </div>
    <nav class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <!-----
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Navbar Item 2<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Navbar Item2 - Sub Item 1</a></li>
                </ul>
            </li>
            <li>
                <a href="#">Navbar Item 3</a>
            </li>-->
        </ul>
        <ul class="nav navbar-nav pull-right">
            <li class="dropdown">
                <a href="#" id="nbAcctDD" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user"></i>Welcome <strong><?php $userdata = $this->session->userdata('userdata'); echo $userdata['first_name']; ?></strong><i class="icon-sort-down"></i></a>
                <ul class="dropdown-menu pull-right">
                    <li><?php echo anchor('welcome/logout', 'Logout'); ?></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>