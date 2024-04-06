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
            <div class="card-box">
                <h3 class="card-title">Basic Informations</h3>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Restaurant Name</label>
                            <input class="form-control" type="text" value="<?=$rs_restaurant->full_name;?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>NGO Name</label>
                            <input class="form-control" type="text" value="<?=$ngo_name;?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" type="text" value="<?=$rs_restaurant->address;?>" readonly>
                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <label>Location</label>
                            <input class="form-control" type="text" value="<?=$rs_restaurant->location;?>" readonly>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="card-box">
                <h3 class="card-title">Order Details</h3>
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
            </div>
        </div>
    </div>

    <div class="m-t-20 text-center">
        <a href="<?= base_url('rider/orderHistory');?>" class="btn btn-primary submit-btn">Back</a>
    </div>
</div>

<?php $this->load->view('layout/footer.php');?>