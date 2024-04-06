<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>


<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-sm-4 col-3">
            <h4 class="page-title">Item List</h4>
        </div>
        <div class="col-sm-8 col-9 text-right m-b-20">
            <a href="<?=base_url('panel/foodItem/add');?>" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add Item</a>
        </div>
    </div>

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
    <?php } ?>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-border table-striped custom-table datatable mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($rs_manage)){
                                foreach($rs_manage as $rec){
                                    
                        ?>
                        <tr>
                            <td>
                                <?php
                                    if(!empty($rec->item_image) && file_exists('./assets/food_item/'.$rec->item_image)){
                                ?>
                                    <img width="50" height="50" src="<?=base_url('assets/food_item/'.$rec->item_image);?>" class="rounded-circle m-r-5" alt="<?=$rec->item_name;?>"> 
                                <?php
                                    }else{
                                ?>
                                    <img width="50" height="50" src="<?=base_url();?>assets/img/user.jpg" class="rounded-circle m-r-5" alt="<?=$rec->item_name;?>"> 
                                <?php
                                    }
                                ?>
                                <?= ucwords($rec->item_name);?>
                            </td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <?php
                                        $user_access = 'title="You can not change this product" style="pointer-events:none;"';
                                        if($rec->user_id == $user_id || $current_role == 'admin'){
                                            $user_access = '';
                                        }
                                    ?>
                                        <a class="dropdown-item" <?=$user_access;?> href="<?= base_url('panel/foodItem/edit').'/'.$rec->id;?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php   
                                    
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer.php');?>


<script src="<?=base_url();?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>assets/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url();?>assets/js/select2.min.js"></script>