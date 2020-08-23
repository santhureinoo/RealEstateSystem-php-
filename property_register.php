<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="css/property-register.css"></link>
<link href="css/fancybox.min.css"></link>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="js/fancybox.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<?php
    if(!isset($_SESSION)) {
        session_start();
    }
    
    include("DB-Connection/property.php");
    include("DB-Connection/login.php");
    if(isset($_SERVER['QUERY_STRING'])) {
      $queries = array();
      $readonly = "readonly";
      parse_str($_SERVER['QUERY_STRING'], $queries);
      if(isset($_POST["cancel"])){
        if($queries["view"] == "1") {
          header("Location: admin/dataTable.php?name=properties");
        }
        else if ($queries["view"] == "2") {
         header("Location: myproperties.php");
        }
       
      }
      if(isset($_SESSION["userid"])) {
        $id = $_SESSION["userid"];
      }
      if(isset($_POST["submit"])) {
        addProperty($_POST['region'],$_POST['township'],$_POST['propertyName'] , $_POST['address'],$_POST['area'], $_POST['city'], $_POST['rooms'], $_POST['propertyType'],$id,$_FILES['ownership'],$_POST['description'],$_FILES["image"]);
      }
      if(isset($_POST["edit"])) {
         if (editProperty($_POST['region'],$_POST['township'],$_POST["propertyid"],$_POST["status"],$_POST['propertyName'] , $_POST['address'],$_POST['area'], $_POST['city'], $_POST['rooms'], $_POST['propertyType'],$id,$_FILES['ownership'],$_POST['description'],$_FILES["image"])){
          
         };
      }
      if(isset($queries["view"])) {
        if($queries["view"] == "2") {
          $readonly = "";
        }
        if(isset($queries["propertyid"]))
          $propertyid = $queries["propertyid"];
          $selectedProperty = getPropertyByID($propertyid);
      }
      
    }
    else {
      if(isset($_POST["cancel"])){
        header("Location: myproperties.php");
      }
    }

  //   if(isset($_POST['propertyName']) && isset($_POST['address']) && isset($_POST['area']) &&
  //    isset($_POST['city']) && isset($_POST['rooms']) &&
  //     isset($_POST['propertyType']) && isset($_FILES['ownership']) && isset($_POST['description']) && isset($_FILES['image'])){
        
  // }

  
?>
<section class="testimonial py-5" id="testimonial">
    <div class="container">
        <div class="row ">
            <div class="col-md-4 py-5 bg-primary text-white text-center ">
                <div class=" ">
                    <div class="card-body">
                        <img src="http://www.ansonika.com/mavia/img/registration_bg.svg" style="width:30%">
                        <h2 class="py-3">Register Your Property</h2>
                        <p>Tation argumentum et usu, dicit viderer evertitur te has. Eu dictas concludaturque usu, facete detracto patrioque an per, lucilius pertinacia eu vel.

</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 py-5 border">
                <h4 class="pb-4"><?php if(isset($selectedProperty)) {
                    echo "Property Details";
                } 
                else {
                  echo "Please Fill Your Property Details";
                } ?></h4>
                <form enctype='multipart/form-data' action="" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <?php if(isset($selectedProperty)) {
                              echo ' <div class="row"><input type="hidden" name="status" value="'.$selectedProperty["status"].'"><label for="propertyName" class="col-sm-2 col-form-label">Name</label><div class="col-sm-10"><input type="text" class="form-control" '.$readonly.' name="propertyName" value="'.$selectedProperty["name"].'" id="name" placeholder="Name/No"></div></div>';
                          }
                          else {
                              echo '<input type="text" class="form-control" name="propertyName" id="name" placeholder="Name/No">';
                          } ?>
                        </div>
                        <div class="form-group col-md-6">
                          <?php if(isset($selectedProperty)) {
                              echo ' <div class="row"><label for="address" class="col-sm-2 col-form-label">Address</label><div class="col-sm-10"><input type="text" class="form-control" '.$readonly.' name="address" value="'.$selectedProperty["address"].'" id="address" placeholder="Address"></div></div>';
                          }
                          else {
                              echo '<input type="text" class="form-control" name="address" id="address" placeholder="Address">';
                          }
                           ?>      
                        </div>
                      </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <?php if(isset($selectedProperty)) {
                            echo '<div class="row"><label for="area" class="col-sm-2 col-form-label">Area</label><div class="col-sm-10"><input id="area" name="area" placeholder="Area (Square Feet)" '.$readonly.' value="'.$selectedProperty["area"].'" class="form-control" required="required" type="text"></div></div>';
                          }
                          else {
                            echo '<input id="area" name="area" placeholder="Area (Square Feet)" class="form-control" required="required" type="text">';
                          }
                        ?>
                        </div>
                        <div class="form-group col-md-6">
                                <?php if(isset($selectedProperty) && $queries["view"] == "1") {
                                    echo '<div class="row"><label for="city" class="col-sm-2 col-form-label">City</label><div class="col-sm-10"><input id="city" name="city" placeholder="City" '.$readonly.' value="'.$selectedProperty["city"].'" class="form-control" required="required" type="text"></div></div>';
                                }else if(isset($selectedProperty) && $queries["view"] == "2") {
                                  echo '<div class="row"><label for="city" class="col-sm-2 col-form-label">City</label><div class="col-sm-10"> <select id="city" name="city" class="form-control"></select></div></div>';
                                } 
                                else {
                                    echo ' <select id="city" name="city" class="form-control"></select>';
                                }
                                  ?>
                                 
                        </div>
                        <div class="form-group col-md-6">
                            <?php if(isset($selectedProperty)) {
                                        echo '<div class="row"><label for="rooms" class="col-sm-2 col-form-label">Rooms</label><div class="col-sm-10"><input id="room" name="rooms" placeholder="City" '.$readonly.' value="'.$selectedProperty["rooms"].'" class="form-control" required="required" type="text"></div></div>';
                                    } else {
                                        echo '<input type="rooms" class="form-control" name="rooms" id="Rooms" placeholder="Rooms">';
                                    }
                            ?>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <?php if(isset($selectedProperty) && $queries["view"] == "1") {
                                            echo '<div class="row"><label for="type" class="col-sm-2 col-form-label">Type</label><div class="col-sm-10"><input id="type" name="propertyType" placeholder="type" '.$readonly.' value="'.$selectedProperty["type"].'" class="form-control" required="required" type="text"></div></div>';
                                        } else if(isset($queries["view"]) && $queries["view"] =="2") {

                                            echo '<div class="row"><label for="type" class="col-sm-2 col-form-label">Type</label><div class="col-sm-10"><select id="type" name="propertyType" class="form-control"> <option selected>Select Type</option>';
                                            if($selectedProperty["type"] === "RC") {
                                                echo '  <option selected value="RC"> RC </option>';
                                            }else {
                                              echo '  <option value="RC"> RC </option>';
                                            }
                                            if($selectedProperty["type"] === "Apartment") {
                                              echo '  <option selected value="Apartment"> Apartment </option>';
                                            }else {
                                              echo '  <option value="Apartment"> Apartment </option>';
                                            }
                                            if($selectedProperty["type"] === "Resort") {
                                              echo '  <option selected value="Resort"> Resort </option>';
                                            }else {
                                              echo '  <option value="Resort"> Resort </option>';
                                            }
                                           echo '</select></div></div>';
                                        
                                        } 
                                        else {
                                            echo '<select id="type" name="propertyType" class="form-control">
                                            <option selected>Select Type</option>
                                              <option value="RC"> RC </option>
                                              <option value="Apartment">Apartment</option>
                                              <option value="Resort">Resort</option></select>';
                                       
                                        }?>
                                  
                        </div>
                          <div class="form-group col-md-6">
                            <?php 
                            $tmpregionList ='';
                            // $row = 1;
                              $regions = array();
                              if (($handle = fopen("township.csv", "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                  array_push($regions,$data[1]);
                                  
                                  // $num = count($data);
                                  // echo "<p> $num fields in line $row: <br /></p>\n";
                                  // $row++;
                                  // for ($c=0; $c < $num; $c++) {
                                  // 	echo $data[$c] . "<br />\n";
                                  // }
                                }
                                foreach (array_unique($regions) as $region) {
                                  if(isset($selectedProperty['region']) && $selectedProperty['region'] === $region){
                                    $tmpregionList = $tmpregionList . "<option selected>$region</option>";
                                  }else{
                                    $tmpregionList = $tmpregionList . "<option>$region</option>";
                                  }
                                 
                                }
                                fclose($handle);
                              } 
                              if(isset($selectedProperty) && $queries["view"] == "1") {
                                echo '<div class="row"><label for="region" class="col-sm-2 col-form-label">Type</label><div class="col-sm-10"><input id="region" name="region"  placeholder="type" '.$readonly.' value="'.$selectedProperty["region"].'" class="form-control" required="required" type="text"></div></div>';
                            } else if(isset($queries["view"]) && $queries["view"] =="2") {
      
                                echo '<div class="row"><label for="type" class="col-sm-2 col-form-label">Type</label><div class="col-sm-10"><select id="region" name="region" class="form-control">
                                <option selected>Select Region</option>
                                 '.
                                 $tmpregionList
                                 .'
                              </select></div></div>';
                            } 
                            else {
                                echo ' <select id="region" name="region" class="form-control"><option selected>Select Region</option>'.$tmpregionList.' </select> ';
                            }
                           ?>
                                  
                        </div>
                        <div class="form-group col-md-6">
                            <?php
                                $tmp = '';
                            		// $row = 1;
                                if (($handle = fopen("township.csv", "r")) !== FALSE) {
                                  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    if($data[3] !== ''){
                                      if(isset($selectedProperty['township']) && $selectedProperty['township'].' ' === $data[3]){
                                        $tmp = $tmp.  "<option selected>".$data[3] . "</option>";
                                      }
                                      else {
                                        $tmp = $tmp.  "<option>".$data[3] . "</option>";
                                      }
                                      
                                    }
                                    
                                    // $num = count($data);
                                    // echo "<p> $num fields in line $row: <br /></p>\n";
                                    // $row++;
                                    // for ($c=0; $c < $num; $c++) {
                                    // 	echo $data[$c] . "<br />\n";
                                    // }
                                  }
                                  fclose($handle);
                                } 
                            if(isset($selectedProperty) && $queries["view"] == "1") {
                                            echo '<div class="row"><label for="township" class="col-sm-2 col-form-label">Township</label><div class="col-sm-10"><input id="township" name="township" placeholder="type" '.$readonly.' value="'.$selectedProperty["township"].'" class="form-control" required="required" type="text"></div></div>';
                                        } else if(isset($queries["view"]) && $queries["view"] =="2") {

                                            echo '<div class="row"><label for="type" class="col-sm-2 col-form-label">Township</label><div class="col-sm-10"><select id="township" name="township" class="form-control">
                                            <option selected>Select Township</option>
                                            '.
                                            $tmp
                                        .'
                                          </select></div></div>';
                                        } 
                                        else {
                                            echo '<select id="township" name="township" class="form-control">
                                            <option selected>Select Township</option>
                                              '.
                                                  $tmp
                                              .'
                                          </select>';
                                        }
                                        
							
							?>

                        </div>
                      
                        <div class="form-group row col-md-12">
                          <label for="ownership" class="col-md-3 col-form-label text-center">OwnerShip:</label>
                          <div class="col-md-9">
                            <?php if(isset($selectedProperty) && $queries["view"] != "2"){
                                  echo ' <div class="thumb">
                                  <a href="data:image/base64,'.base64_encode($selectedProperty["ownership"]).'" class="fancybox" rel="ligthbox">
                                  <img class="ownership_container" src="data:image/jpeg;base64,' . base64_encode($selectedProperty["ownership"]) . '" width="238px" hight="256px" />
                              </a>
                              </div>';
                              }
                              else {
                                echo ' <input type="File" class="form-control" name="ownership" id="ownership" placeholder="ownerShip">';
                            // //     echo ' <div class="thumb">
                            // //     <a href="data:image/base64,'.base64_encode($selectedProperty["ownership"]).'" class="fancybox" rel="ligthbox">
                            // //     <img class="ownership_container" src="data:image/jpeg;base64,' . base64_encode($selectedProperty["ownership"]) . '" width="238px" hight="256px" />
                            // // </a>
                            // </div>';
                              } 
                            ?>
                            </div>
                        </div>
                        <div class="form-group row col-md-12">
                          <label for="image" class="col-md-3 col-form-label text-center">Property Image:</label>
                          <div class="col-md-9">
                          <?php if(isset($selectedProperty) && $queries["view"] != "2"){
                                  echo ' <div class="thumb">
                                  <a href="data:image/base64,'.base64_encode($selectedProperty["image"]).'" class="fancybox" rel="ligthbox">
                                      <img class="image_container" src="data:image/jpeg;base64,' . base64_encode($selectedProperty["image"]) . '" width="238px" hight="256px" />
                                  </a>
                              </div>';
                              }
                              else {
                                //$encodeImage = base64_encode($selectedProperty["image"]);
                                echo ' <input type="File" class="form-control" name="image" id="image" placeholder="image">';
                            //     echo ' <div class="thumb">
                            //     <a href="data:image/gif;base64,'.$encodeImage.'" class="fancybox" rel="ligthbox">
                            //     <img class="image_container"  src="data:image/jpeg;base64,' .$encodeImage. '" width="238px" hight="256px" />
                            //     </a>
                            // </div>';
                              } 
                            ?>
                            
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                        <?php if(isset($selectedProperty)){
                          echo '<div class="row"><label for="type" class="col-sm-2 col-form-label">Description</label><div class="col-sm-10"><textarea '.$readonly.' placeholder="Description" id="description" name="description" cols="40" rows="5" class="form-control">'.$selectedProperty["description"].'</textarea><input type="hidden" name="propertyid" value="'.$selectedProperty["id"].'"></div></div>';
                        } 
                        else {
                          echo '<textarea placeholder="Description" id="description" name="description" cols="40" rows="5" class="form-control"></textarea>';
                        }?>
                                  
                        </div>
                        
                    </div>
             
                    <!-- <div class="form-row">
                        <div class="form-group">
                            <div class="form-group">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                  <label class="form-check-label" for="invalidCheck2">
                                    <small>By clicking Submit, you agree to our Terms & Conditions, Visitor Agreement and Privacy Policy.</small>
                                  </label>
                                </div>
                              </div>
                    
                          </div>
                    </div> -->
                    
                    <div class="form-row">
                        <?php
                          if(isset($queries["view"]) && $queries["view"] == "2") {
                              echo '<button name="edit" type="submit" class="btn btn-danger mr-2">Edit</button><input type="submit" id="cancel" class="btn btn-primary" name="cancel" value="Cancel">';
                          }
                          else  if(isset($queries["view"]) && $queries["view"] == "1") {
                            echo '<input type="submit" id="cancel" name="cancel" class="btn btn-primary" value="Back">';
                        }
                          else {
                            echo ' <button name="submit" type="submit" class="btn btn-danger">Submit</button>';
                          }
                         ?>   
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
  $(".fancybox").fancybox({
        openEffect: "none",
        closeEffect: "none"
    });
    
    $(".zoom").hover(function(){
		
		$(this).addClass('transition');
	}, function(){
		$(this).removeClass('transition');
	});
  $.getJSON( "mm.json", function( data ) {
  var items = [];
  var currentCity = "<?php if(isset($selectedProperty)){echo $selectedProperty["city"];}{ echo "";} ?>"  
  $('#city').append("<option>Select a City </option>")
  $.each( data, function( key, val ) {
    if(currentCity != val.city) {
      $('#city').append("<option value='"+val.city+"'>"+val.city+"</option>");
    }
    else {
      $('#city').append("<option selected value='"+val.city+"'>"+val.city+"</option>");
    }
   
  });
  });
  var room = 1;
function education_fields() {
 
    room++;
    var objTo = document.getElementById('education_fields')
    var divtest = document.createElement("div");
	divtest.setAttribute("class", "form-group row removeclass"+room);
	var rdiv = 'removeclass'+room;
    divtest.innerHTML = '<div class="col nopadding"><div class="form-group"> <input type="text" class="form-control" id="featureNames" name="featureNames[]" value="" placeholder="Name"></div></div><div class="col nopadding"><div class="form-group"> <input type="text" class="form-control" id="amounts" name="amounts[]" value="" placeholder="Amount"></div></div><div class="col nopadding"><div class="form-group"> <input type="text" class="form-control" id="descriptions" name="descriptions[]" value="" placeholder="Description"></div></div><div class="col nopadding"><div class="form-group"><div class="input-group"> <div class="input-group-btn"> <button class="btn btn-danger" type="button" onclick="remove_education_fields('+ room +');"> Del </button></div></div></div></div><div class="clear"></div>';
    
    objTo.appendChild(divtest)
}
   function remove_education_fields(rid) {
	   $('.removeclass'+rid).remove();
   }

  //  $("a[href^='data:image']").each(function(){
  //   $(this).fancybox({
  //       content: $("<img/>").attr("src", this.href)
  //   });
// });

var readURL = function(input,name) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.'+name).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}


$("#image").on('change', function(){
    readURL(this,'image_container');
});
$("#ownership").on('change', function(){
    readURL(this,'ownership_container');
});
</script>