<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>

<style>
    .map-iframe-css{width: 100%; height: 300px;}
</style>
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title"><?=$current_slug;?></h4>
        </div>
    </div>

    
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <a href="<?= base_url('rider/orderDeliver').'/'.$order_id;?>" style="color:#fff;" class="btn btn-success btn-block">Mark Deliver</a>
            </div>
            <div class="col-sm-12">
                <small>When You deliver the order than click on this button to mark delivery.</small>
            </div>
            <br />
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
                    <div class="col-sm-10">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" type="text" value="<?=$rs_restaurant->address;?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a style="margin-top: 30px;" class="btn btn-primary" href="https://www.google.com/maps/place/<?=str_replace(" ", "+", $rs_restaurant->location);?>/@<?=$rs_restaurant->latitude;?>,<?=$rs_restaurant->longitude;?>,12z" target="_blank"><i class="fa fa-map-marker" aria-hidden="true"></i> MAP</a>
                    </div>
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
</div>

<?php $this->load->view('layout/footer.php');?>

<script>
    $(document).ready(function(){
        $('.map-iframe iframe').addClass('map-iframe-css');
    });
</script>