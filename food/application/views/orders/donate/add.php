<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>

<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Donate New Order</h4>
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
            <form action="<?= base_url('restaurant/donateOrder');?>" enctype="multipart/form-data" method="post">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Select NGO</label>
                            <select class="form-control" name="ngo_id">
                                <?php
                                    if(isset($rs_ngo)){
                                        foreach($rs_ngo as $rec_ngo){
                                ?>
                                            <option value="<?=$rec_ngo->id;?>"><?=$rec_ngo->full_name;?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-hover table-white">
                            <thead>
                                <th>Item</th>
                                <th>QTY</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control" name="item_id[0]">
                                            <?php
                                                if(isset($rs_foods)){
                                                    foreach($rs_foods as $rec_food){
                                            ?>
                                                        <option value="<?=$rec_food->id;?>"><?=$rec_food->item_name;?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="item_qty[0]" value="1" required></td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control" name="item_id[1]">
                                            <option value="0">Select Item</option>
                                            <?php
                                                if(isset($rs_foods)){
                                                    foreach($rs_foods as $rec_food){
                                            ?>
                                                        <option value="<?=$rec_food->id;?>"><?=$rec_food->item_name;?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="item_qty[1]" value="1"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control" name="item_id[2]">
                                            <option value="0">Select Item</option>
                                            <?php
                                                if(isset($rs_foods)){
                                                    foreach($rs_foods as $rec_food){
                                            ?>
                                                        <option value="<?=$rec_food->id;?>"><?=$rec_food->item_name;?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="item_qty[2]" value="1"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control" name="item_id[3]">
                                            <option value="0">Select Item</option>
                                            <?php
                                                if(isset($rs_foods)){
                                                    foreach($rs_foods as $rec_food){
                                            ?>
                                                        <option value="<?=$rec_food->id;?>"><?=$rec_food->item_name;?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="item_qty[3]" value="1"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control" name="item_id[4]">
                                            <option value="0">Select Item</option>
                                            <?php
                                                if(isset($rs_foods)){
                                                    foreach($rs_foods as $rec_food){
                                            ?>
                                                        <option value="<?=$rec_food->id;?>"><?=$rec_food->item_name;?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td><input class="form-control" type="number" name="item_qty[4]" value="1"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        
                <div class="m-t-20 text-center">
                    <button class="btn btn-primary submit-btn">Create New Donate Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer.php');?>