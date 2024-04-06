<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title"><?=$current_slug;?></h4>
        </div>
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

    <div class="row">
        <div class="col-sm-12">
            <form action="<?= base_url('ngo/finalDonateOrder');?>" enctype="multipart/form-data" method="post">
                <input type="hidden" name="order_id" value="<?=$order_id;?>">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Restaurant Name</label>
                            <input class="form-control" type="text" value="<?=$restaurant_name;?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>NGO Name</label>
                            <input class="form-control" type="text" value="<?=$ngo_name;?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Order Status</label>
                            <?php
                                if($order_status == 'Pending'){
                            ?>
                                <select class="form-control" name="status">
                                    <option value="Pending">Pending</option>
                                    <option value="Accepted">Accept</option>
                                    <option value="Cancelled">Cancel</option>
                                </select>
                            <?php
                                }else{
                            ?>
                                <input class="form-control" type="text" value="<?= str_replace("_", " ", $order_status);?>" readonly>
                            <?php
                                }
                            ?>
                            
                        </div>
                    </div>
                                
                    <?php
                        if(empty($rider_name)){
                    ?>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Assign Rider</label>
                                
                                <select class="form-control" name="rider_id" required>
                                    <option selected="true" disabled="disabled">Select Rider</option>
                                    <?php
                                        if(isset($all_riders)){
                                            foreach($all_riders as $rec_rider){
                                    ?>
                                                <option value="<?=$rec_rider->id;?>"><?=$rec_rider->full_name;?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php
                        }else{
                    ?>
                            <input class="form-control" type="text" value="<?=$rider_name;?>" readonly>
                    <?php
                        }
                    ?>
                </div>

                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-white">
                            <thead>
                                <th>Item</th>
                                <th>QTY</th>
                            </thead>
                            <tbody>
                                <?php
                                    if(isset($rs_order)){
                                        foreach($rs_order as $rec_order){
                                ?>
                                <tr>
                                    <td>
                                        <?=$rec_order->item_name;?>
                                    </td>
                                    <td>
                                        <?=$rec_order->donate_qty;?>
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

                <?php
                    if(empty($rider_name)){
                ?>
                    <div class="m-t-20 text-center">
                        <button class="btn btn-primary submit-btn">Update Order</button>
                    </div>
                <?php
                    }
                ?>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer.php');?>