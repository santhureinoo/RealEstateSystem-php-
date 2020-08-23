<?php
	if(!isset($_SESSION)) {
    ob_start();
		session_start();
	}
	require_once("DB-Connection/property.php");
  require_once("DB-Connection/login.php");
  require_once("DB-Connection/feature.php");
  require_once("DB-Connection/post.php");
  require_once("util/validate.php");

  $edit = true;
  $selectedPost;
  if(isset($_SERVER['QUERY_STRING'])) {
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['id'])) {
      $edit =true;
      $currentid = $queries['id'];
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
       
    ));

    if($main_validate->passed()) {
      if(!isset($_POST["hidden_images"])){
        $_POST["hidden_images"] = [];
      }
      if(!isset( $currentid )){
        addPost($_POST["postType"],$_POST["property"],$_POST["amount"],$_POST["description"],$_SESSION["userid"],$_POST["hidden_images"],$_POST["featureNames"],$_POST["amounts"]);
      }
      else {
        editPost($currentid,$_POST["property"],$_POST["postType"],$_POST["amount"],$_POST["description"],$_SESSION["userid"],$_POST["hidden_images"],$_POST["featureNames"],$_POST["amounts"]);
      }
      header("Location: myposts.php");
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
  if(isset($queries['id'])) {
    $selectedPost = getPostById($currentid);
    $features = getAllPostFeature($selectedPost["id"]);
    $images = getAllPostImage($selectedPost["id"]);
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
                                          if(isset($_SESSION['userid'])){
                                            $result = getPropertiesByUser(isset($selectedPost)?$selectedPost['propertyid']:0,$_SESSION['userid'],true);
                                          }
                                          else {
                                            $result = getPropertiesByUser(isset($selectedPost)?$selectedPost['propertyid']:0,$_SESSION['userid'],true);
                                          }
                                            
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

                        </div>
                        <div class="col-md-6">
                        <div class="form-group">
                                <input type="text" name="amount"  <?php echo !$edit? 'disabled':''; ?> class="form-control" placeholder="Amount *"  value="<?php echo isset($selectedPost)?$selectedPost["initial_amount"]:"";?>"/>
                            </div>
                            </div>
                    </div>
                    <div class="form-group row">
                          <div class="col-md-12 ">
                          <select id="type" name='postType' <?php echo !$edit? 'disabled':''; ?> class="form-control">
                                        <option <?php echo isset($selectedPost) && $selectedPost["postType"] === 'Rent'?"Selected":"";?>>Rent</option>
                                        <option <?php echo isset($selectedPost) && $selectedPost["postType"] === 'Sale'?"Selected":"";?> >Sale</option>
                                </select>
                          </div>
                   
                    </div>
                    <div class="form-group row">
                    <div class="col-md-12 form-group">
                                <textarea name="description" class="form-control" <?php echo !$edit? 'disabled':''; ?> placeholder="Description"><?php echo isset($selectedPost)?$selectedPost["description"]:"";?></textarea>
                    </div>
                    </div>
                   
                    <div class="col-md-12 form-group row">
                      <label for="images" class="col-sm-2 col-form-label text-right">Images :</label>
                      <div class="col-md-10">
                          <input type="file" class="form-control" id="images" name="images[]" <?php echo !$edit? 'hidden':''; ?> onchange="preview_images();" multiple/>
                      </div>
                    </div>
                    <div class="cold-md-12 row p-3" id="image_preview">
                    <?php
     
     if($edit && isset($images)) {
       for($i=0;$i<count($images);$i++){
        
         echo ' 
         <div id="preview_'.$i.'" class="col-md-3">
         <input type="hidden" Id="hidden_image_"+curent_image_preview_index+ name="hidden_images[]" value="data:image/jpeg;base64,'.base64_encode($images[$i]['image']).'">
               <button id="closeIcon" onclick="deletePreviewData(`preview_'.$i.'`)" type="button" class="position-absolute" style="right:0"><span>×</span></button>
                   <img class="img-fluid" src="data:image/jpeg;base64,'.base64_encode($images[$i]['image']).'"></div>;
         ';
       } 
      
     }
     else if(!$edit && isset($images)) {
      for($i=0;$i<count($images);$i++){
       
        echo ' 
        <div id="preview_'.$i.'" class="col-md-3">
        <input type="hidden" Id="hidden_image_"+curent_image_preview_index+ name="hidden_images[]" value="data:image/jpeg;base64,'.base64_encode($images[$i]['image']).'">
             
                  <img class="img-fluid" src="data:image/jpeg;base64,'.base64_encode($images[$i]['image']).'"></div>;
        ';
      } 
     
    }
    ?>
                    </div>
                  
                      <h3> Additional Features </h3>
                        <div id="all_features"  class="form-group col-md-12 ">
                                
                        </div>
                        <div <?php echo !$edit? 'style="display:none;"':''; ?>  class="form-group col-md-12 ">
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
                        <script>
                                  var room = 1;
    function all_features(name,amount) {
        room++;
        var objTo = document.getElementById('all_features')
        var divtest = document.createElement("div");
      divtest.setAttribute("class", "form-group row removeclass"+room);
      var rdiv = 'removeclass'+room;
        var edit = '<?php echo $edit !== true? 'style="display:none;" ': 'style="display:block;" '; ?>';
        divtest.innerHTML = '<div class="col nopadding"><div class="form-group"> <input type="text"  <?php echo !$edit? 'disabled':''; ?> class="form-control" id="featureNames" name="featureNames[]" value="'+name+'" placeholder="Name"></div></div><div class="col nopadding"><div class="form-group"> <input type="text" class="form-control"  <?php echo !$edit? 'disabled':''; ?> id="amounts" name="amounts[]" value="'+amount+'" placeholder="Amount"></div></div><div class="col nopadding"><div class="form-group"><div class="input-group"> <div class="input-group-btn"> <button class="btn btn-danger" type="button" '+edit+' onclick="remove_all_features('+ room +');"> Del </button></div></div></div></div><div class="clear"></div>';
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
    <?php
        foreach($features as $feature){
            echo 'all_features("'.$feature["name"].'","'.$feature["amount"].'");';
        }
    ?>
                          </script>
                        <div class="col nopadding">
                          <div class="form-group">
                            <div class="input-group">
                              <div class="input-group-btn">
                                <button class="btn btn-success" <?php echo !$edit? 'disabled':''; ?> type="button"  onclick="all_features('','');"> Add</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="clear"></div>
                                              </div>
                              </div>
 
                    </div>
                    <button <?php echo $edit!==true?"style='display:none;'":""; ?> type="submit" class="btnSubmit">Submit</button>
                </div>
            </form>
        </div>

<script>
    
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
      function remove_all_features(rid) {
        $('.removeclass'+rid).remove();
      }
</script>

