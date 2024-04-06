<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>Algizahe Tadweer</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/style.css">
    <!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div class="main-wrapper account-wrapper">
        <div class="account-page">
			<div class="account-center">
				<div class="account-box">
                    <form action="<?= base_url('login/signin');?>" method="post" class="form-signin">
						<div class="account-logo">
                            <a><img src="<?= base_url();?>assets/logo/1.png" alt=""></a>
                            <h3 class="text-center" style="font-size: 1.4em; padding-top: 10px; color: red;">
                            <?php 
                                if($error = $this->session->flashdata('Login_Failed')){ 
                                    echo $error;
                                }
                                echo validation_errors();
                            ?>
                            </h3>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input autofocus="" class="form-control" placeholder="Type your email..." type="email" name="user_email" value="<?= set_value('user_email');?>" required/>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" name="user_pass" placeholder="Type your password" type="password" required/>
                        </div>
                        <div class="form-group text-center">
                            <?php
                                if(isset($_GET['device_id']) && $current_device == 'mobile' && !empty($_GET['device_id'])){
                            ?>
                                    <input type="hidden" name="device_id" id="device_id" value="<?=$_GET['device_id'];?>" required>
                            <?php
                                }
                            ?>
                            <button type="submit" class="btn btn-primary account-btn">Login</button>
                        </div>
                    </form>
                </div>
			</div>
        </div>
    </div>
    <script src="<?= base_url();?>assets/js/jquery-3.2.1.min.js"></script>
	<script src="<?= base_url();?>assets/js/popper.min.js"></script>
    <script src="<?= base_url();?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url();?>assets/js/app.js"></script>
</body>
</html>