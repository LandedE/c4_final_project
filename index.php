
<html>
<head>
	<title></title>
	<script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel='stylesheet' href='outing_styles.css'>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQNq766unXxvfCp1ZJ-aMCIT8tMmglOlo">
    </script>
    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.0.1/src/markerwithlabel.js" type="text/javascript"></script>
    <script type='text/javascript' src='facebook_login.js'></script>
    <script type= 'text/javascript' src='jsindex.js'></script>
</head>

<body>
	<div id="main_interface_container" class="col-sm-8 col-sm-offset-2">
		<div id='fb-login' class='col-sm-8 col-sm-offset-4'>
				<div id='status'></div>
				<fb:login-button class="login_button" scope="public_profile,email,user_friends" onlogin="checkLoginState();">
				</fb:login-button>
				
		</div>
		<button type='button' class='logout_button hidden_button'>Logout</button>
		
		<button id ='set_plans_btn' type="button" class="col-sm-8 col-sm-offset-2">Give Me Plans</button>		

		<button id='manage_circles_btn' class='col-sm-8 col-sm-offset-2'>Manage Friends</button>
		
		<button id='get_invites_btn' class='col-sm-8 col-sm-offset-2'>Pending Events</button>
		
	</div>
	
	<div id="plans-container" class="col-sm-8 col-sm-offset-2 hidden">
		<div class="plan-form">

			<h3 class='col-sm-4 col-sm-offset-4'>Set Plan Details</h3>
			 <form class ='col-sm-10 col-sm-offset-1' id='generate_form'>
       		<input type='text' value='dinner' class='col-sm-12  search_parameters' id='search_param1' name='search_param1' placeholder='Enter first stop i.e. "Restaurant"'>
       		<input type='text' value='drinks' class='col-sm-12 search_parameters' id='search_param2' name='search_param2' placeholder='Enter second stop i.e. "Bars"'>
       		<input type='text' value='club' class='col-sm-12  search_parameters' id='search_param3' name='search_param3' placeholder='Enter third stop i.e. "Club"'>
       		<input type='text'  value='Redlands' class='col-sm-8 search_parameters' id='location' name='location' placeholder='Enter desired location.'>
       		<button type='button' class='col-sm-4' name='coordinates' id='coordinates'>Find Me</button>
       		<h5 class='col-sm-8 col-sm-offset-4'>Set Time:</h5>
       		<select class='col-sm-3' name="Week" id="week">
			  <option value="This Week">This Week</option>
			  <option value="Next Week">Next Week</option>
			  
			</select>
       		<select class='col-sm-3' name="day" id='day'>
			  <option value="Monday">Monday</option>
			  <option value="Tuesday">Tuesday</option>
			  <option value="Wednesday">Wednesday</option>
			  <option value="Thursday">Thursday</option>
			  <option value="Friday">Friday</option>
			  <option value="Saturday">Saturday</option>
			  <option value="Sunday">Sunday</option>
			</select>
			<select class='col-sm-3' name="hour" id='hour'>
			  <option value="1">1</option>
			  <option value="2">2</option>
			  <option value="3">3</option>
			  <option value="4">4</option>
			  <option value="5">5</option>
			  <option value="6">6</option>
			  <option value="7">7</option>
			  <option value="8">8</option> 
			  <option value="9">9</option>
			  <option value="10">10</option>
			  <option value="11">11</option>`
			  <option value="12">12</option>
			</select>
			<select class='col-sm-2' name="minute" id="minute">
			  <option value="00">00</option>
			  <option value="15">15</option>
			  <option value="30">30</option>
			  <option value="45">45</option>
			</select>
			<select class='col-sm-1' name="morn_eve" id="day_night">
			  <option value="AM">AM</option>
			  <option value="PM">PM</option>
			</select>
        	<button id='generate_plans_btn' type="button" class="col-sm-3">Generate Plan</button>
        	<button class='col-sm-4 col-sm-offset-4 return_to_main_from_set_plans'>Back To Main</button>
        </form>
        
		</div>

		</div>

	
		<div class="plan-results col-sm-8 col-sm-offset-2 hidden">
		<h3 class='col-sm-4 col-sm-offset-4'>Review Plan Details</h3>
		</div>


		<div class='friend-page col-sm-8 col-sm-offset-2 hidden'>
			<h3 class='col-sm-2 col-sm-offset-5'>Who's Invited?</h3>
			<div class='friend_container col-sm-6'></div>
		</div>

		<div class="pending_invitations col-sm-8 col-sm-offset-2 hidden">
			<h3 class='col-sm-7 col-sm-offset-5'>Pending Invitations</h3>
			<div class='invitations_container col-sm-5'></div>
			<div class='details_of_event col-sm-6 col-sm-offset-1'></div>
			<button class='col-sm-4 col-sm-offset-4 return_to_main_from_invitations'>Back To Main</button>
        </div>


        <div class="edit_profile col-sm-8 col-sm-offset-2 hidden">
			<h3 class='col-sm-7 col-sm-offset-5'>Edit Profile</h3>
			<button class='col-sm-4 col-sm-offset-4 return_to_main_from_invitations'>Back To Main</button>
        </div>

       <!--  <div class='manage_circles col-sm-8 col-sm-offset-2'>
			<h3 class='col-sm-4 col-sm-offset-5'>Manage Circles</h3>
			<div class='group_container col-sm-5'>
				<button class='col-sm-12 add_group_button'>Add Group</button>
				<input class='col-sm-12 hidden' type='text' id='group_name' placeholder='Enter Group Name'>
			</div>
			<div class='list_of_friends col-sm-6 col-sm-offset-1'></div>
		</div> -->
      
    </div>

  </div>
</div>


</body>
</html>
