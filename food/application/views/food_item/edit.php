<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <h4 class="page-title">Edit Item</h4>
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
            <form action="<?= base_url('panel/foodItem/edit/'.$rs_view->id);?>" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="item_name" value="<?= $rs_view->item_name;?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Image <span class="text-danger">*</span></label>
                            <?php
                                if(!empty($rs_view->item_image) && file_exists('./assets/food_item/'.$rs_view->item_image)){
                            ?>
                                <img width="100" height="100" src="<?=base_url('assets/food_item/'.$rs_view->item_image);?>" class="rounded-circle m-r-5" alt="<?=$rs_view->item_name;?>"> 
                            <?php
                                }else{
                            ?>
                                <img width="100" height="100" src="<?=base_url();?>assets/img/user.jpg" class="rounded-circle m-r-5" alt="<?=$rs_view->item_name;?>"> 
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="hidden" value="<?= $rs_view->item_image;?>" name="img_id">
                            <label>Upload Image <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="item_image">
                        </div>
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
