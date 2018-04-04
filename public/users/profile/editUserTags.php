<?php require_once('../../../private/initialize.php'); ?>

<?php $page_title = 'Edit Tags';

  if (is_post_request()){
    $sql = "DELETE FROM users.UserTags WHERE StudentID = '" . $_SESSION['id'] . "'";
    $result = mysqli_query($db, $sql);

    if ($result){
      foreach($_POST['checkTagList'] as $checkTagID) {
        $sql = "INSERT INTO users.UserTags";
        $sql .= "(TagID, StudentID) ";
        $sql .= "VALUES (";
        $sql .= "'" . $checkTagID . "',";
        $sql .= "'" . $_SESSION['id'] . "'";
        $sql .= ")";
        $result = mysqli_query($db, $sql);

      }
      redirect_to(url_for('/users/profile/profile.php'));

    } else {
      // UPDATE failed
      echo mysqli_error($db);
    }

  }

  $sql = "SELECT * FROM users.Tags Order by users.Tags.TagName";
  $allTags_set = mysqli_query($db, $sql);

?>
<?php include(SHARED_PATH . '/user_header.php'); ?>
<head>
    <meta charset="utf-8">
    <title>FOMO UF APP</title>
    <!-- Compiled and minified CSS -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--Custom imports !-->
    <link rel="stylesheet" href="../css/main.css">
	<link rel="stylesheet" href="<?=WWW_ROOT?>/css/allEvents.css">	
    <script type="text/javascript" src="../javascript/main.js"></script>
<style>

td, th {
	border: 1px; 
	border-style: inset;
    text-align: left;
    padding: 6px;
}

tr:nth-child(even) {
	color:black; 
    background-color: #89B2D6
}
tr:nth-child(odd) {
    background-color: #FFBB6F;
}
input[type=checkbox] {
   position: absolute;
   top: -9999px;
   left: -9999px;
}

input[type=checkbox] ~ div  {
	display:none; 
}
label { 
  display: inline-block;
  cursor: pointer;
}

/* Toggled State */
input[type=checkbox]:checked ~ div {
	display:inline-block; 
	font-size:16px; 
}
.tag-container{
    color:white;
    font-size:16px;
    text-align:center;
    cursor:pointer;
	vertical-align: middle;
}
</style>
</head>
<body>
<h2 align="center"> Click on each button to add a tag and then submit!</h2> 
	
<form action="<?php echo url_for('/users/profile/editUserTags.php')?>" method="post">

    <table style="border:5px; padding: 6px; margin-top:2em; font-family: 'Roboto', sans-serif; width:80%;" align="center">
	  <col width="30%">
	  <tr style= "color:#ffffff; background-color: #004d99;">
		<th style="width:20%;" >Name</th>
		<th style="width:20%; text-align:center;">Add Tag</th>
		<th style="width:20%" >Name</th>
		<th style="width:20%; text-align:center;" >Add Tag</th>
	  </tr>
	</table> 
  <?php while ($tag = mysqli_fetch_assoc($allTags_set)){?>
    <table style="width:80%; font-family: 'Roboto', sans-serif; " align="center">
	<col width="30%">
	  <tr >
		<td style="width:20%"></td>
		<td style="width:20%"></td>
		<td style="width:20%"></td>
		<td style="width:20%"></td>
	  </tr>
	  <tr >
		<!--<th><input type="checkbox" name="checkTagList[]" value="<?php echo $tag['TagID'];?>"><?php echo $tag['TagName']?><br> </th> -->
		<td style="padding-left:11px;">   <?php echo $tag['TagName']?> </td>		
		<td>
			<label style="margin-left:5em; margin-top:0em;" align="center" class="btn btn-small waves-effect waves-light blue" for="<?php echo $tag['TagID'];?>" ><i style=""class="material-icons">add</i></label>
			<input type="checkbox" name="checkTagList[]" value="<?php echo $tag['TagID'];?>" id="<?php echo $tag['TagID'];?>">
			<div style="margin-left:6px; color:#004d99; " align="center">Added!</div> 
		</td>		
		<?php $tag = mysqli_fetch_assoc($allTags_set)?>
		<td style="padding-left:18px;"><?php echo $tag['TagName']?> </td>		
		<td>
			<label style="margin-left:5em; margin-top:0em;" align="center" class="btn btn-small waves-effect waves-light blue" for="<?php echo $tag['TagID'];?>"><i style=""class="material-icons">add</i></label>
			<input type="checkbox" name="checkTagList[]" value="<?php echo $tag['TagID'];?>" id="<?php echo $tag['TagID'];?>">
			<div style="margin-left:6px; color:#004d99;" >Added!</div> 
		</td>	  
	  </tr>
  </table> 

  <?php } 
  mysqli_free_result($allTags_set);?>	
  <div class="tag-container">
  <center>  
	<input type="submit" style="padding-top:0em; margin:2em; padding-right:20em;padding-left:20em; -moz-focus-inner { border:20em; padding-right:20em;padding-left:20em; };" class="btn-large waves-light blue"  value="Submit">
  </center> 
  </div> 
  </form>
    <footer>
        <div class="footer-bar">
            <center>
                <a href="https://www.studentinvolvement.ufl.edu/Student-Organizations"><img src="<?=WWW_ROOT?>/images/soar.png" alt="SOAR" style="height: 30px; margin: 10px"></a>
            </center>
        </div>
    </footer>
	</body>