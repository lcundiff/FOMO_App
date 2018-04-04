<?php require_once('../../private/initialize.php'); ?>
<?php require_login(); ?>

<?php $page_title = 'List of All Events';
if ($_SESSION['type'] == "student"){
  $sql = "SELECT * FROM users.Events ORDER BY EventName ASC";
}else{
  $sql = "SELECT * FROM users.Events WHERE OrganizationID = '" . $_SESSION['id'] . "' ORDER BY EventName ASC";
}
$event_set = mysqli_query($db, $sql);

if ($_SESSION['type'] == "student"){
  $sql = "SELECT * FROM users.Events where users.Events.EventID in ";
  $sql .= "(SELECT users.EventTags.EventID FROM users.EventTags WHERE users.EventTags.TagID  in ";
  $sql .= "(SELECT users.UserTags.TagID FROM users.UserTags WHERE users.UserTags.StudentID = '" . $_SESSION['id'] . "'))";
  $recommendEvent_set = mysqli_query($db, $sql);

  $sql = "SELECT OrganizationName, OrganizationID, ProfilePic FROM users.Organizations";
  $organizations_set = mysqli_query($db, $sql);
}

?>
<?php include(SHARED_PATH . '/user_header.php'); ?>
<!--META-->
<meta name="viewport" content="width=device-width initial-scale=1.0">
<head>
	<title>All Events View</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?=WWW_ROOT?>/css/allEvents.css">
	<link rel="stylesheet" type="text/css" href="<?=WWW_ROOT?>/css/skeleton.css">
</head>
<script>
function contact() {
    document.getElementById("email").innerHTML = "email us -> FOMO@live.com";
}
</script>
<script type="text/javascript">
    $(document).ready(function() {
        var allEventsText = "<div class='col s12 m7'>";
        <?php while($event = mysqli_fetch_assoc($event_set)){ ?>
          allEventsText +=  "<div class='card horizontal'> <div class='card-stacked'> <div class='card-content'> <div class='card-headings'> <h1> <?php echo $event['EventName'] ?> </h1> <h2> <?php echo date('m/d/Y', strtotime($event['Date'])); ?> </h2> <h2> <?php echo date('h:i a', strtotime($event['StartTime'])); ?>- <?php echo date('h:i a', strtotime($event['EndTime'])); ?> </h2> <h3> <?php echo $event['Location'] ?> </h3> </div> <img class='card-image' src='<?php echo substr($event['EventPic'],3) ?>'> </div class='view'> <div class='card-action'> <a href='<?php echo url_for('/users/singleEvent/info.php?id=' . $event['EventID']);?>'><i class='material-icons'>open_in_browser</i>View</a> </div> </div> </div> </div>"
        <?php }
        mysqli_free_result($event_set); ?>
        $("#eventsData").html(allEventsText);

        <?php if ($_SESSION['type'] == "student"){?> // student only
			var recommendText = "<div class='col s12 m7'>";
        <?php while($event = mysqli_fetch_assoc($recommendEvent_set)){ ?>
      recommendText +=  "<div class='card horizontal'> <div class='card-stacked'> <div class='card-content'> <div class='card-headings'> <h1> <?php echo $event['EventName'] ?> </h1> <h2> <?php echo date('m/d/Y', strtotime($event['Date'])); ?> </h2> <h2> <?php echo date('h:i a', strtotime($event['StartTime'])); ?>- <?php echo date('h:i a', strtotime($event['EndTime'])); ?> </h2> <h3> <?php echo $event['Location'] ?> </h3> </div> <img class='card-image' src='<?php echo substr($event['EventPic'],3) ?>'> </div class='view'> <div class='card-action'> <a href='<?php echo url_for('/users/singleEvent/info.php?id=' . $event['EventID']);?>'><i class='material-icons'>open_in_browser</i>View</a> </div> </div> </div> </div>"
        <?php }
        mysqli_free_result($recommendEvent_set); ?>
        $("#recEventsData").html(recommendText);
        <?php while($event = mysqli_fetch_assoc($organizations_set)){?>
		    var dirText = "<div class='testimonials' id='portfolio'><div class='container'><div class='row'><h1 class='os-animation' align='center' data-os-animation='bounceIn' data-os-animation-delay='0.2s'>Organizations @UF</h1><p style='margin-bottom:4em;' align='center' class='para os-animation' data-os-animation='bounceIn' data-os-animation-delay='0.4s'>These are our sponsored organizations</p></div>";
        <?php while($org = mysqli_fetch_assoc($organizations_set)){?>
          <?php }
          mysqli_free_result($organizations_set); ?>

			var pic = "<?php echo $org['ProfilePic']; ?>";
			if (pic != ""){
				dirText += "<div class='row'><div class='three columns reframe os-animation' data-os-animation='slideInLeft' data-os-animation-delay='0.6s'> <a href = '<?php echo url_for('/users/profile/orgInfo.php?id=' . $org['OrganizationID']);?>' > <img class ='reframe'src='profile/<?php echo $org['ProfilePic']; ?>' alt='' /></a></div><div class='nine columns os-animation' data-os-animation='slideInRight' data-os-animation-delay='0.6s'><div class='arrow_box'><h2 style='margin-top:-20px;'> <?php echo $org['OrganizationName']; ?> </h2><p> Click the image to visit their page! </p></div></div></div>"
			}
		<?php } ?>
		dirText += "</div></div>"
        <?php mysqli_free_result($organizations_set); ?>
        $("#directory").html(dirText); 
        <?php }?> // end if student
      });
</script>


<body>
    <?php if ($_SESSION['type'] == 'org'){?>
    <div id="events-content" class="col s12 ">
        <h1>MY HOST EVENTS<a class="btn-floating btn-large waves-effect waves-light orange" href="<?php echo url_for('/users/singleEvent/create.php');?>"><i class="material-icons">add</i></a></h1>
        <div id="eventsData">
        </div>
    </div>
    <?php }else{?>
    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s3"><a class="active" href="#events-content">Events</a></li>
                <li class="tab col s3"><a href="#rec-content">Recommended</a></li>
                <li class="tab col s3"><a href="#dir-content">Directory</a></li>
                <li class="tab col s3"><a href="#map">Map</a></li>
            </ul>
        </div>
    </div>
    <div id="events-content" class="col s12 ">
       <div id="eventsData">
       </div>
   </div>
    <div id="rec-content" class="col s12">
        <div id="recEventsData">
        </div>
    </div>
	<div id="dir-content" class="col s12">
        <div id="directory">
        </div>
		<div class="contactus" id="contact">
			<div class="container">
				<div class="row">
					<h2 class="os-animation" data-os-animation="bounceIn" data-os-animation-delay="0.3s">ready to register your organization?</h2>
					<a onclick='contact()' id="email" style="margin-top:2em;"class="os-animation" data-os-animation="bounceIn" data-os-animation-delay="0.5s">CONTACT US</a>
				</div>
			</div>
		</div>
    </div>
    <div id="map" class="col s12">
        <div id="mapData">
            <iframe height="100%" id="mapIframe" width="100%" src="map.php" style="border:none; margin-top:-5px;" name="target"></iframe>
        </div>
    </div>
    <div id="map" class="col s12">
        <div>
        </div>
    </div>
    <?php }?>
    <footer>
        <div class="footer-bar">
            <center>
                <a href="https://www.studentinvolvement.ufl.edu/Student-Organizations"><img src="../images/soar.png" alt="SOAR" style="height: 30px; margin: 10px"></a>
            </center>
        </div>
    </footer>
</body>

</html>
