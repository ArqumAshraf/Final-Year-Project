<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>


<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/css/select2.min.css">

<div class="content">
    <div class="row">
        <div class="col-sm-4 col-3">
            <h4 class="page-title">Item Stock</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-border table-striped custom-table datatable mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>QTY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($rs_stock)){
                                foreach($rs_stock as $rec){
                                    
                        ?>
                        <tr>
                            <td>
                                <?php
                                    if(!empty($rec->item_image) && file_exists('./assets/food_item/'.$rec->item_image)){
                                ?>
                                    <img width="70" height="70" src="<?=base_url('assets/food_item/'.$rec->item_image);?>" class="rounded-circle m-r-5" alt="<?=$rec->item_name;?>"> 
                                <?php
                                    }else{
                                ?>
                                    <img width="70" height="70" src="<?=base_url();?>assets/img/user.jpg" class="rounded-circle m-r-5" alt="<?=$rec->item_name;?>"> 
                                <?php
                                    }
                                ?>
                                <?= ucwords($rec->item_name);?>
                            </td>
                            <td><?= $rec->qty;?></td>
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