<?php require_once('../../../private/initialize.php'); ?>
<?php require_login(); ?>

<?php $page_title = 'Single Event Update';
if(!isset($_GET['id'])) {
  redirect_to(url_for('/users/allEvents.php'));
}

$id = $_GET['id'] ;
$sql = "SELECT * FROM users.Events WHERE EventID='" . db_espace($db,$id) . "'";
$single_event_set = mysqli_query($db, $sql);

$event = mysqli_fetch_assoc($single_event_set);
mysqli_free_result($single_event_set);

if(is_post_request()) {
    $target_dir = "../profile/uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
      // Check if image file is a actual image or fake image
          $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
          if($check !== false) {
              $uploadOk = 1;
          } else {
              echo "File is not an image.";
              $uploadOk = 0;
          }
      // Check if file already exists
      if (file_exists($target_file)) {
          echo "Sorry, file already exists.";
          $uploadOk = 0;
      }
      // Check file size
      if ($_FILES["fileToUpload"]["size"] > 5000000) {
          echo "Sorry, your file is too large.";
          $uploadOk = 0;
      }
      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
          echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
      }
      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
          echo "Sorry, your file was not uploaded.";
      // if everything is ok, try to upload file
      } else {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users.Events SET ";
          	$sql .= "EventPic='" . $target_file . "' ";
            $sql .= "WHERE EventID='" . $id . "' ";
            $sql .= "LIMIT 1";
          	$result = mysqli_query($db, $sql);
            if ($result){
              echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";

              $event['EventName'] = $_POST['eventName'] ?? '';
              $event['Location'] = $_POST['location'] ?? '';
              $event['Date'] = $_POST['date'] ?? '';
              $event['StartTime'] = $_POST['startTime'] ?? '';
              $event['EndTime'] = $_POST['endTime'] ?? '';
              $event['Description'] = $_POST['description'] ?? '';
              $event['Latitude'] = $_POST['lat'] ?? '';
              $event['Longitude'] = $_POST['long'] ?? '';

              $sql = "UPDATE users.Events SET ";
              $sql .= "EventName='" . $event['EventName'] . "', ";
              $sql .= "Location='" . $event['Location'] . "', ";
              $sql .= "Date='" . $event['Date'] . "', ";
              $sql .= "StartTime='" . $event['StartTime'] . "', ";
              $sql .= "EndTime='" . $event['EndTime'] . "', ";
              $sql .= "Description='" . $event['Description'] . "', ";
              $sql .= "Longitude='" . $event['Longitude'] . "', ";
              $sql .= "Latitude='" . $event['Latitude'] . "' ";
              $sql .= "WHERE EventID='" . $id . "' ";
              $sql .= "LIMIT 1";

              $result = mysqli_query($db, $sql);
              if ($result){
                redirect_to(url_for('/users/singleEvent/info.php?id=' . $id));
              }else {
                // UPDATE failed
                echo mysqli_error($db);
              }

            }else {
              echo mysqli_error($db);
            }
          } else {
              echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>
<?php include(SHARED_PATH . '/user_header.php'); ?>

<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDSLycBJS9PbQbeqJzrik9q7JQa4blLq-U&libraries=places" type="text/javascript"></script>

<script type="text/javascript">
    function initialize() {
        var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            document.getElementById('location').value = place.name;
            document.getElementById('cityLat').value = place.geometry.location.lat();
            document.getElementById('cityLng').value = place.geometry.location.lng();
        });
    }
    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<form action="<?php echo url_for('/users/singleEvent/update.php?id=' . $id);?>" method="post" enctype="multipart/form-data">
  Event Name:<br />
  <input type="text" name="eventName" value="<?php echo $event['EventName']; ?>" /><br/>
  Select Event Picture:<br/>
  <input type="file" name= "fileToUpload" id="fileToUpload"><br/>
  Location:<br/>
  <input id="searchTextField" type="text" size="50" autocomplete="on" runat="server" placeholder="<?php echo $event['Location']?>"/><br/>
  <input type="hidden" id="cityLat" name="lat" />
  <input type="hidden" id="cityLng" name="long" />
  <input type="hidden" id="location" name="location" />
  Date: (MM/DD/YYYY)<br />
  <input type="date" name="date" value="<?php echo $event['Date']; ?>" /><br/>
  Start Time:<br/>
  <input type="time" name="startTime" value="<?php echo $event['StartTime']; ?>" /><br/>
  End Time:<br/>
  <input type="time" name="endTime" value="<?php echo $event['EndTime']; ?>" /><br/>
  Description<br/>
  <textarea name="description" cols="40" rows="5"><?php echo $event['Description']; ?></textarea><br>
  <input type="submit" name="submitForm" value="Submit"  />
</form>

</html>
