
<div class="container">
    <div class="row mt-3">
  		<div class="col-sm-3"><!--left col-->
          <form enctype='multipart/form-data'   class="form" action="" method="post" id="registrationForm">
      <div class="text-center">
        <img src="data:image/jpeg;base64,<?php echo $encodedImage; ?>" class="avatar img-circle img-thumbnail" alt="avatar">
        <!-- <h6>Upload a different photo...</h6> -->
        <?php
        if(!$readonly){
            echo '<input type="file" name="phot o" class="text-center center-block file-upload">';
        }
        ?>
      </div></hr><br>
          <ul class="list-group">
            <li class="list-group-item text-muted"><h4>Record</h4> <i class="fa fa-dashboard fa-1x"></i></li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Rent Properties </strong></span> 125</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Properties Renting</strong></span> 13</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Contracts</strong></span> 37</li>
            <li class="list-group-item text-right"><span class="pull-left"><strong>Posts</strong></span> 78</li>
          </ul> 
          
        </div><!--/col-3-->
    	<div class="col-sm-9">
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                 
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Name</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":" id='username'"?> class="form-control" name="username" placeholder="User Name" title="enter your name if any." value="<?php echo $profile_data["username"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>Email</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":" id='email'"?> class="form-control" name="email" placeholder="Enter your E-Mail" title="Enter your Email." value="<?php echo $profile_data["email"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name" <?php echo $readonly?"style='display:none';":""?>><h4>Password</h4></label>
                              <input type="password" <?php echo $readonly?"style='display:none';":"id='password'"?> class="form-control" name="password" placeholder="Enter Your Password" title="Enter your Email." value="<?php echo $profile_data["password"]?>">
                          </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name" <?php echo $readonly?"style='display:none';":""?>><h4>Confirm Password</h4></label>
                              <input type="password" <?php echo $readonly?"style='display:none';":" id='confirm'"?> class="form-control" name="confirm" placeholder="Enter Your Confirm Password" title="Enter your Confirm Password." value="">
                          </div>
                      </div>
          
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="phone"><h4>Phone</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":"id='phone'"?> class="form-control" name="phone"placeholder="Enter phone" title="enter your phone number" value="<?php echo $profile_data["ph_no"]?>">
                          </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>NRC</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":"id='nrc'"?> class="form-control" name="nrc" placeholder="Enter NRC" title="enter your NRC." value="<?php echo $profile_data["nrc"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>Address</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":" id='address'"?> class="form-control" name="address" placeholder="Enter Your Address" title="enter your address." value="<?php echo $profile_data["address"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="email"><h4>City</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":" id='city'"?> class="form-control" name="city" placeholder="Enter City" title="enter a city" title="<?php echo $profile_data["city"] ?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="password"><h4>Job</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":"id='job'"?> class="form-control" name="job" placeholder="Enter Job" title="enter your job." value="<?php echo $profile_data["job"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="income"><h4>Income</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":" id='income'"?> class="form-control" name="income" placeholder="Enter Income." title="enter your income." value="<?php echo $profile_data["income"] ?>">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="income"><h4>Relationship</h4></label>
                            <select class="form-control" <?php echo $readonly?"disabled":" id='relationship'"?> name="relationship">
                            
                                    <option <?php echo $profile_data["relationship"] == 1? "selected":"";?> value='1'>Married</option>
                                    <option <?php echo $profile_data["relationship"] == 2? "selected":"";?> value='2'>Single</option>
                                    <option <?php echo $profile_data["relationship"] == 3? "selected":"";?> value='3'>Divorced</option>
                                    <option <?php echo $profile_data["relationship"] == 4? "selected":"";?> value='4'>Unknown</option>
                                </select>
                          </div>
                      </div>
                      <div class="form-group">
                      <div class="col-xs-6">
                            <label for="income"><h4>Religion</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":"id='religion'"?> class="form-control" name="religion" placeholder="Enter Religion." title="enter your Religion." value="<?php echo $profile_data["religion"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                      <div class="col-xs-6">
                            <label for="income"><h4>Family Member</h4></label>
                              <input type="text" <?php echo $readonly?"readonly":"id='family_members'"?> class="form-control" name="family_members" placeholder="Enter Family Members." title="enter your family members." value="<?php echo $profile_data["family_members"]?>">
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                                <?php
                                    if(!$readonly){
                                        echo '<button class="btn btn-lg btn-success" type="submit" name="save"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                        <button class="btn btn-lg" type="submit" name="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>';
                                    }
                           
                                ?>
                              	
                            </div>
                      </div>
                      
              	</form>
              
              <hr>
              
             </div><!--/tab-pane-->
     
         
        </div><!--/col-9-->
    </div>