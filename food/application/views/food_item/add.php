<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>

<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <h4 class="page-title">Add Item</h4>
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
            <form action="<?= base_url('panel/foodItem/add');?>" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="item_name" value="<?= set_value('item_name');?>" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Upload Image <span class="text-danger">*</span></label>
                            <input class="form-control" type="file" name="item_image">
                        </div>
                    </div>
                </div>
                <div class="m-t-20 text-center">
                    <button class="btn btn-primary submit-btn">Create Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer.php');?>


<script src="<?=base_url();?>assets/js/select2.min.js"></script>
