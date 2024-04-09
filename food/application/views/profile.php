<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view('layout/header.php');
?>
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">My Profile</h4>
        </div>
    </div>
    <form action="<?= base_url($this->session->userdata('role').'/profile');?>" enctype="multipart/form-data" method="post">
        <div class="card-box">
            <h3 class="card-title">Basic Informations</h3>
            <?php if($feedback = $this->session->flashdata('feedback')){ ?>
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Well done!</strong> <?php echo $feedback;?>
                </div>
            <?php } if($error = $this->session->flashdata('error')){ ?>
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Warning!</strong> <?php echo $error;?>
                </div>
            <?php } 
            echo validation_errors();
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-img-wrap">
                        <?php
                            if(!empty($user_profile->profile_pic) && file_exists('./assets/profile/'.$this->session->userdata('role').'/'.$user_profile->profile_pic)){
                        ?>
                            <img class="inline-block" src="<?=base_url('assets/profile/'.$this->session->userdata('role').'/'.$user_profile->profile_pic);?>" alt="user">
                        <?php
                            }else{
                        ?>
                            <img class="inline-block" src="<?=base_url();?>assets/img/user.jpg" alt="user">
                        <?php
                            }
                        ?>
                        
                        <div class="fileupload btn">
                            <span class="btn-text">edit</span>
                            <input class="upload" type="file" name="profile_pic">
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Full Name</label>
                                    <input type="text" class="form-control floating" value="<?= $user_profile->full_name;?>" name="full_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Email</label>
                                    <input type="text" class="form-control floating" value="<?= $user_profile->user_email;?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group form-focus">
                                    <label class="focus-label">Password</label>
                                    <input type="password" class="form-control floating" name="user_pass" autocomplete="off">
                                </div>
                            </div>
                            <?php
                                if($this->session->userdata('role') == 'rider'){
                            ?>
                                    <div class="col-md-12">
                                        <ul class="list-group notification-list">
                                            <li class="list-group-item">
                                                Online - Offline
                                                <div class="material-switch float-right">
                                                    <input id="staff_module" type="checkbox" value="1" name="available" <?php if($user_profile->available == 1){echo "checked='checked'";}?>>
                                                    <label for="staff_module" class="badge-primary"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
            if($this->session->userdata('role') != 'admin'){
        ?>
        <div class="card-box">
            <h3 class="card-title">Contact Informations</h3>
            <div class="row">
                <?php
                    if($this->session->userdata('role') == 'rider'){
                ?>
                        <div class="col-md-12">
                            <div class="form-group form-focus">
                                <label class="focus-label">Phone Number</label>
                                <input type="text" class="form-control floating" name="user_phone" value="<?= $user_profile->user_phone;?>" required>
                            </div>
                        </div>
                <?php
                    }
                    if(in_array($this->session->userdata('role'), ['ngo', 'restaurant'])){
                ?>
                        <div class="col-md-6">
                            <div class="form-group form-focus">
                                <label class="focus-label">Address</label>
                                <input type="text" class="form-control floating" name="address" value="<?= $user_profile->address;?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-focus">
                                <label class="focus-label">Phone Number</label>
                                <input type="text" class="form-control floating" name="user_phone" value="<?= $user_profile->user_phone;?>" required>
                            </div>
                        </div>
                <?php
                    }
                    if($this->session->userdata('role') == 'restaurant'){
                        $button='style="pointer-events: none;';
                        if(!empty($user_profile->location) && !empty($user_profile->latitude) && !empty($user_profile->longitude)){
                            $button='';
                        }
                ?>
                        <div class="col-md-10">
                            <div class="form-group form-focus">
                                <label class="focus-label">Seacrh Location</label>
                                <input type="text" class="form-control floating" id="search_input" value="<?= $user_profile->location;?>" name="location" required>
                                <input type="hidden" id="latitude" value="<?= $user_profile->latitude;?>" name="latitude" />
                                <input type="hidden" id="longitude" value="<?= $user_profile->longitude;?>" name="longitude" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-primary" href="https://www.google.com/maps/place/<?=str_replace(" ", "+", $user_profile->location);?>/@<?=$user_profile->latitude;?>,<?=$user_profile->longitude;?>,12z" target="_blank" <?=$button;?>><i class="fa fa-map-marker" aria-hidden="true"></i> MAP</a>
                        </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <?php
            }
        ?>

        <div class="text-center m-t-20">
            <input type="hidden" value="<?= $user_profile->profile_pic;?>" name="img_id">
            <button class="btn btn-primary submit-btn" type="submit">Save</button>
        </div>
    </form>
</div>
<?php $this->load->view('layout/footer.php');?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=googlekey"></script>
<script>
    var searchInput = 'search_input';
    $(document).ready(function () {
        var autocomplete;
        autocomplete = new google.maps.places.Autocomplete((document.getElementById(searchInput)), {
            types: ['geocode'],
            // componentRestrictions: {
            //     country: "USA"
            // }   
        });
        
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var near_place = autocomplete.getPlace();
            document.getElementById('latitude').value = near_place.geometry.location.lat();
            document.getElementById('longitude').value = near_place.geometry.location.lng();
            
            // document.getElementById('latitude_view').innerHTML = near_place.geometry.location.lat();
            // document.getElementById('longitude_view').innerHTML = near_place.geometry.location.lng();
        });
    });

    $(document).on('change', '#'+searchInput, function () {
        // document.getElementById('latitude_input').value = '';
        // document.getElementById('longitude_input').value = '';
        
        // document.getElementById('latitude_view').innerHTML = '';
        // document.getElementById('longitude_view').innerHTML = '';
    });
</script>