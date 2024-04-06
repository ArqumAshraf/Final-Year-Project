<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <h4 class="page-title">Edit User</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <?php if($feedback = $this->session->flashdata('feedback')){ ?>
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Well done!</strong> <?= $feedback;?>
            </div>
            <?php } if($error = $this->session->flashdata('error')){ ?>
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Warning!</strong> <?= $error;?>
            </div>
            <?php } 
            echo "<br />";
            echo validation_errors();
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <form action="<?= base_url('admin/users/edit/'.$rs_view->id);?>" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="full_name" value="<?= $rs_view->full_name;?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" value="<?= $rs_view->user_email;?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Role</label>
                            <input class="form-control" type="text" value="<?= $rs_view->role;?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" type="password" name="user_pass">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="display-block">Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="user_active" value="1" <?php if($rs_view->status == 1){echo 'checked';}?>>
                        <label class="form-check-label" for="user_active">
                        Active
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="user_inactive" value="0" <?php if($rs_view->status == 0){echo 'checked';}?>>
                        <label class="form-check-label" for="user_inactive">
                        Inactive
                        </label>
                    </div>
                </div>
                <div class="m-t-20 text-center">
                    <button class="btn btn-primary submit-btn">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer.php');?>


<script src="<?=base_url();?>assets/js/select2.min.js"></script>
