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

  $sql = "SELECT OrganizationName, ProfilePic FROM users.Organizations";
  $organizations_set = mysqli_query($db, $sql);
}

?>
<?php include(SHARED_PATH . '/user_header.php'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var allEventsText = "<div class='col s12 m7'>";
        <?php while($event = mysqli_fetch_assoc($event_set)){ ?>
          allEventsText += "<div class='card horizontal'><div class='card-image'> <img src='<?php echo substr($event['EventPic'],3) ?>'> </div> <div class='card-stacked'><div class='card-content'><h4><?php echo date('m/d/Y', strtotime($event['Date'])); ?></h4><h2><?php echo $event['EventName'] ?></h2><h4><?php echo date('h:i a', strtotime($event['StartTime'])); ?>-<?php echo date('h:i a', strtotime($event['EndTime'])); ?></h4><h3><?php echo $event['Location'] ?></h3></div><div class='card-action'> <a href='<?php echo url_for('/users/singleEvent/info.php?id=' . $event['EventID']);?>'>View</a></div></div></div></div>"
        <?php }
        mysqli_free_result($event_set); ?>
        $("#eventsData").html(allEventsText);

        <?php if ($_SESSION['type'] == "student"){?> // student only
			var recommendText = "<div class='col s12 m7'>";
        <?php while($event = mysqli_fetch_assoc($recommendEvent_set)){ ?>
			recommendText += "<div class='card horizontal'> <div class='card-image'> <img src='<?php echo substr($event['EventPic'],3) ?>'> </div> <div class='card-stacked'> <div class='card-content'><h4><?php echo date('m/d/Y', strtotime($event['Date'])); ?></h4><h2><?php echo $event['EventName'] ?></h2><h4><?php echo date('h:i a', strtotime($event['StartTime'])); ?>-<?php echo date('h:i a', strtotime($event['EndTime'])); ?></h4><h3><?php echo $event['Location'] ?></h3> </div> <div class='card-action'> <a href='<?php echo url_for('/users/singleEvent/info.php?id=' . $event['EventID']);?>'>View</a> </div> </div> </div> </div>"
        <?php }
        mysqli_free_result($recommendEvent_set); ?>
        $("#recEventsData").html(recommendText);

        <?php while($event = mysqli_fetch_assoc($organizations_set)){?>
          //HTML/CSS GOES HERE!

          <?php }
          mysqli_free_result($organizations_set); ?>
          
        <?php }?> // end if student
      });
</script>

<title>All Events View</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="<?=WWW_ROOT?>/css/allEvents.css">

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
                <li class="tab col s1"><a href="#map">Map</a></li>
                <li class="tab col s2"><a href="#gator-times-content">Gator Times</a></li>
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
    <div id="map" class="col s12">
        <div id="mapData">
            <iframe height="100%" id="mapIframe" width="100%" src="map.php" style="border:none; margin-top:-5px;" name="target"></iframe>
            <p> test </p>
        </div>
    </div>
    <div id="gator-times-content" class="col s12">
        <div class="left-side-gator">
            <h1>Gator News!</h1>
            <img class="gator-logo" src="../images/gator.png" alt="Gator-logo">
        </div>
        <div class="right-side-gator">
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
