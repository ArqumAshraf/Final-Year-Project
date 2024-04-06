<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>


<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-sm-4 col-3">
            <h4 class="page-title">User List</h4>
        </div>
        <div class="col-sm-8 col-9 text-right m-b-20">
            <a href="<?=base_url('admin/users/add');?>" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Add User</a>
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
                            <th>Type</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($rs_manage)){
                                foreach($rs_manage as $rec){
                                    if($user_id != $rec->id){
                        ?>
                        <tr>
                            <td>
                                <?php
                                    if(!empty($rec->profile_pic) && file_exists('./assets/profile/'.$rec->role.'/'.$rec->profile_pic)){
                                ?>
                                    <img width="28" height="28" src="<?=base_url('assets/profile/'.$this->session->userdata('role').'/'.$user_profile->profile_pic);?>" class="rounded-circle m-r-5" alt="<?=$rec->full_name;?>"> 
                                <?php
                                    }else{
                                ?>
                                    <img width="28" height="28" src="<?=base_url();?>assets/img/user.jpg" class="rounded-circle m-r-5" alt="<?=$rec->full_name;?>"> 
                                <?php
                                    }
                                ?>
                                <?= ucwords($rec->full_name);?>
                            </td>
                            <td><?= ucfirst($rec->role);?></td>
                            <td><?= ucfirst($rec->address);?></td>
                            <td><?= $rec->user_phone;?></td>
                            <td><?= $rec->user_email;?></td>
                            <td>
                                <?php
                                    if($rec->status == 1){
                                ?>
                                    <span class="custom-badge status-green">Active</span>
                                <?php        
                                    }else{
                                ?>
                                    <span class="custom-badge status-orange">In Active</span>
                                <?php        
                                    }
                                ?>
                            </td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="<?= base_url('admin/users/edit').'/'.$rec->id;?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php   
                                    }
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