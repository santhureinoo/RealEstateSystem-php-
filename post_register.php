<?php
	if(!isset($_SESSION)) {
		session_start();
	}
	require_once("DB-Connection/property.php");
  require_once("DB-Connection/login.php");
  require_once("DB-Connection/feature.php");
  require_once("DB-Connection/post.php");
  require_once("util/validate.php");

  $edit = false;
  $selectedPost;
  if(isset($_SERVER['QUERY_STRING'])) {
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['id'])) {
      $edit =true;
      $currentid = $queries['id'];
      $selectedPost = getPostById($currentid);
      $features = getAllPostFeature($selectedPost["id"]);
      $images = getAllPostImage($selectedPost["id"]);
    }
    if(isset($queries['view'])){
      $edit =false;
    }

	}
  if(!empty($_POST)) {
  $validate = new Validate();
    $main_validate = $validate->check($_POST, array(
        'property' => array(
            'required' => true
        ),
        'amount'=>array(
          'required' =>true
        ),
        'description'=>array(
          'required' =>true
        ),
        'password'=>array(
          'required' =>true
        )
    ));

    if($main_validate->passed()) {
      addPost($_POST["property"],$_POST["amount"],$_POST["description"],$_SESSION["userid"],$_POST["hidden_images"],$_POST["featureNames"],$_POST["amounts"]);
    }
    else {
        echo 'Validation errors:';
        echo '<ul>';
        foreach($main_validate->errors() as $error)
        {
            echo '<li>'.ucfirst($error).'</li>';
        }
        echo '</ul>';
    }
  }
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/typeahead.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="https://rawgithub.com/hayageek/jquery-upload-file/master/js/jquery.uploadfile.min.js"></script>
<script>
    var curent_image_preview_index = 1;
    var substringMatcher = function(strs) {
      return function findMatches(q, cb) {
        var matches, substringRegex;

        // an array that will be populated with substring matches
        matches = [];

        // regex used to determine if a string contains the substring `q`
        substrRegex = new RegExp(q, 'i');

        // iterate through the pool of strings and for any string that
        // contains the substring `q`, add it to the `matches` array
        $.each(strs, function(i, str) {
           if (substrRegex.test(str.name)) {
            matches.push(str.name);
          }
        });
        cb(matches);
      };
    };
    function deletePreviewData(name) {
          $('#'+name).remove();
        }
        function getBase64(file,selector) {
          var reader = new FileReader();
          reader.addEventListener("load", function assignImageSrc(evt) {
                $(selector).val(evt.target.result);
                // image.src = evt.target.result;
                // this.removeEventListener("load", assignImageSrc);
            }, false);
          reader.readAsDataURL(file);
        }

        var current_features = <?php echo json_encode(getAllCurrentFeature()); ?>
      
       function preview_images() 
        {

            var total_file=document.getElementById("images").files.length;
            for(var i=0;i<total_file;i++)
            {
              $('#image_preview').append("<div id='preview_"+i+"' class='col-md-3'><input type='hidden' id='hidden_image_"+curent_image_preview_index+"' name='hidden_images[]'><button id='closeIcon' onclick='deletePreviewData(`preview_"+i+"`)' type='button' class='position-absolute' style='right:0;'><span>×</span></button><img class='img-fluid' src='"+URL.createObjectURL(event.target.files[i])+"'></div>");
              getBase64(event.target.files[i],"#hidden_image_"+curent_image_preview_index);
              curent_image_preview_index++;
            }
        }
</script>
<!------ Include the above in your HEAD tag ---------->
<link href="css/post_register.css" rel="stylesheet">
<div class="container register-form">
            <form class="form" action="" method="post">
                <div class="note">
                    <p>Create Post For Your Properties Here</p>
                </div>
              
                <div class="form-content">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <div class="form-group">
                            <select id="type" name='rentOrSale' class="form-control">
                                    <option selected>Select Rent or Sale</option>
                                    <option value='Rent'> Rent </option>
                                    <option value='Sale'>Sale</option>
                            </select>
                            </div> -->
                            <div class="form-group">
                                <select id="type" name='property' <?php echo !$edit? 'disabled':''; ?> class="form-control">
                                        <option selected>Select Your Property</option>
                                        <?php
                                            $result = getPropertiesByUser($selectedPost['propertyid'],$_SESSION['userid']);
                                            foreach($result as $res) {
                                                  $id = $res["id"];
                                                  $selected = "";
                                                 
                                                  if($selectedPost['propertyid'] == $id) {
                                                    $selected = "selected";
                                                  }
                                                
                                                  $name = $res["name"];
                                                  echo "<option value=$id $selected>$name</option>";
                                            } 
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="password" <?php echo !$edit? 'hidden':''; ?> class="form-control" placeholder="Password *" value=""/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="amount"  <?php echo !$edit? 'disabled':''; ?> class="form-control" placeholder="Amount *"  value="<?php echo isset($selectedPost)?$selectedPost["initial_amount"]:"";?>"/>
                            </div>
                            <div class="form-group">
                                <input type="text" name="confirm_password" <?php echo !$edit? 'hidden':''; ?> class="form-control" placeholder="Confirm Password *" value=""/>
                            </div>                         
                        </div>
                    </div>
                    <div class="col-md-12 form-group row">
                                <textarea name="description" class="form-control" <?php echo !$edit? 'disabled':''; ?> placeholder="Description"><?php echo isset($selectedPost)?$selectedPost["description"]:"";?></textarea>
                    </div>
                    <div class="col-md-12 form-group row">
                      <label for="images" class="col-sm-2 col-form-label text-right">Images :</label>
                      <div class="col-md-10">
                          <input type="file" class="form-control" id="images" name="images[]" <?php echo !$edit? 'hidden':''; ?> onchange="preview_images();" multiple/>
                      </div>
                    </div>
                    <div class="cold-md-12 row p-3" id="image_preview"></div>
                  
                      <h3> Additional Features </h3>
                        <div id="education_fields"  class="form-group col-md-12 ">
                                
                        </div>
                        <div class="form-group col-md-12 ">
                              <div class='row'>
                              <div class="col nopadding">
                          <div class="form-group">
                              <input type="text" <?php echo !$edit? 'disabled':''; ?> class="form-control" id="featureNames" name="featureNames[]" value="" placeholder="Name">
                          </div>
                        </div>
                        <div class="col nopadding">
                          <div class="form-group">
                            <input type="text" <?php echo !$edit? 'disabled':''; ?> class="form-control"  id="amounts" name="amounts[]" value="" placeholder="Amount">
                          </div>
                        </div>
                        <!-- <div class="col nopadding">
                          <div class="form-group">
                            <input type="text" class="form-control" id="descriptions" name="descriptions[]" value="" placeholder="Description">
                          </div>
                        </div> -->
                        
                        <div class="col nopadding">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-btn">
                                <button class="btn btn-success" <?php echo !$edit? 'disabled':''; ?> type="button"  onclick="education_fields();"> Add</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="clear"></div>
                                              </div>
                              </div>
 
                    </div>
                    <button type="submit" class="btnSubmit">Submit</button>
                </div>
            </form>
        </div>

<script>
    <?php
                      if($edit) {
                        for($i=0;$i<count($images);$i++){
                          echo ' 
                          $("#image_preview").append("<div id="preview_'.$i.'" class="col-md-3">
                          <input type="hidden" Id="hidden_image_"+curent_image_preview_index+ name="hidden_images[]">
                                <button id="closeIcon" onclick="deletePreviewData(`preview_'.$i.'`)" type="button" class="position-absolute" style="right:0"><span>×</span></button>
                                    <img class="img-fluid" src="'.$images[$i]['image'].'"></div>");
                            //  getBase64(event.target.files[i],"#hidden_image_"+curent_image_preview_index);
                              curent_image_preview_index++;
                          ';
                        } 
                       
                      }
                     ?>
      $('#featureNames').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    },
    {
      name: 'current_features',
      source: substringMatcher(current_features)
    });
    // $('#the-basics .typeahead').typeahead({
    //   hint: true,
    //   highlight: true,
    //   minLength: 1
    // },
    // {
    //   name: 'states',
    //   source: substringMatcher(states)
    // });
    $("#startbutton").click(function()
      {
        // extraObj.startUpload();
        
      });
      $.getJSON( "mm.json", function( data ) {
      var items = [];
      $('#city').append("<option>Select a City </option>")
      $.each( data, function( key, val ) {
        $('#city').append("<option value='"+val.city+"'>"+val.city+"</option>");
      });
      });
      var room = 1;
    function education_fields() {
        room++;
        var objTo = document.getElementById('education_fields')
        var divtest = document.createElement("div");
      divtest.setAttribute("class", "form-group row removeclass"+room);
      var rdiv = 'removeclass'+room;
        divtest.innerHTML = '<div class="col nopadding"><div class="form-group"> <input type="text" class="form-control" id="featureNames" name="featureNames[]" value="" placeholder="Name"></div></div><div class="col nopadding"><div class="form-group"> <input type="text" class="form-control" id="amounts" name="amounts[]" value="" placeholder="Amount"></div></div><div class="col nopadding"><div class="form-group"><div class="input-group"> <div class="input-group-btn"> <button class="btn btn-danger" type="button" onclick="remove_education_fields('+ room +');"> Del </button></div></div></div></div><div class="clear"></div>';
        objTo.appendChild(divtest); 
           $('#featureNames').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
          },
          {
            name: 'current_features',
            source: substringMatcher(current_features)
          });
        
    }
      function remove_education_fields(rid) {
        $('.removeclass'+rid).remove();
      }
</script>