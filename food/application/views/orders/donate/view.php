<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>


<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-sm-4 col-3">
            <h4 class="page-title">Donate Order</h4>
        </div>
        <?php
            if($current_role == 'restaurant'){
        ?>
            <div class="col-sm-8 col-9 text-right m-b-20">
                <a href="<?=base_url('restaurant/donateOrder');?>" class="btn btn btn-primary btn-rounded float-right"><i class="fa fa-plus"></i> Donate New Order</a>
            </div>
        <?php
            }
        ?>
    </div>

    <div class="row">
        <div class="col-sm-12">
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

    <form action="<?= base_url('panel/donateOrder');?>" method="post">
        <?php
            if(isset($current_order_id)){
        ?>
                <div class="row">
                    <div class="col-sm-12">
                        <a href="<?= base_url('panel/donateOrder');?>" style="color:red;">Clear Search</a>
                    </div>
                </div>
        <?php
            }
        ?>
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <label class="focus-label">Order ID</label>
                    <input type="number" name="order_id" value="<?php if(isset($current_order_id) && $current_order_id){echo $current_order_id;}else{set_value('order_id');}?>" class="form-control floating" required>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <button class="btn btn-success btn-block">Search</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-border table-striped custom-table datatable mb-0" data-order='[[ 0, "desc" ]]'>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>From Restaurant</th>
                            <th>To NGO</th>
                            <th>Rider Name</th>
                            <th>Order Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($rs_orders)){
                                foreach($rs_orders as $rec){
                                    
                        ?>
                        <tr>
                            <td>
                                <?= ucwords($rec->id);?>
                            </td>
                            <td>
                                <?= $all_users[$rec->restaurant_id];?>
                            </td>
                            <td>
                                <?= $all_users[$rec->ngo_id];?>
                            </td>
                            <td>
                                <?php
                                    if(!empty($rec->rider_id)){
                                        echo $all_users[$rec->rider_id];
                                    }else{
                                        echo 'Rider not available.';
                                    }
                                ?>
                            </td>
                            <td>
                                <?= str_replace("_", " ", $rec->status);?>
                            </td>
                            <td class="text-right">
                                <div class="dropdown dropdown-action">
                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="<?= base_url('panel/viewDonationOrder').'/'.$rec->id;?>"><i class="fa fa-pencil m-r-5"></i> View Order</a>

                                    <?php
                                        if($rec->status == 'Pending' && $current_role == 'restaurant'){
                                    ?>
                                            <a class="dropdown-item" href="<?= base_url('restaurant/donateEditOrder').'/'.$rec->id;?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <?php
                                        }

                                        if(in_array($rec->status, ['Pending', 'Accepted']) && empty($rec->rider_id) && $current_role == 'ngo'){
                                    ?>
                                            <a class="dropdown-item" href="<?= base_url('ngo/editDonateOrder').'/'.$rec->id;?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <?php
                                        }
                                    ?>
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