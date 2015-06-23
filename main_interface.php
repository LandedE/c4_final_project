
<html>
<head>
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel='stylesheet' href='outing_styles.css'>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQNq766unXxvfCp1ZJ-aMCIT8tMmglOlo">
    </script>
    <script src="http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.0.1/src/markerwithlabel.js" type="text/javascript"></script>
    <script src='facebook_login.js'></script>


    <script>
    var event_array = [];
    var city_coordinates;
    var outing_results = [];
    var current_user_id;
    $(document).ready(function(){
    	





    	
    	 $('.logout_button').click(function(){
                console.log('in logout button');
                    FB.logout(function(response) {
                    	console.log(response);
                        if(response.status !== 'connected'){
                          console.log('not connected');
                          $('#fb-login').toggleClass('hidden_button');
                          $('.logout_button').toggleClass('hidden_button');
                         }  

                     });
                   
                 
               
        })
    	
    	
    	$('#generate_plans_btn').click(function(){
    				var promise = 
						$.ajax({
							url: 'https://maps.googleapis.com/maps/api/geocode/json?address='+$('#location').val()+'&key=AIzaSyCQNq766unXxvfCp1ZJ-aMCIT8tMmglOlo',
							method: 'Get',
							dataType: 'json',
							success: function(response){
									console.log(response);
									city_coordinates = response;
									city_coordinates.location = new Object;
									city_coordinates.location.coordinate = new Object;
									city_coordinates.location.coordinate.latitude = response.results[0].geometry.location.lat;
									city_coordinates.location.coordinate.longitude= response.results[0].geometry.location.lng;
									


									
									console.log(city_coordinates);

							}
						})

					createCarousel();
    				promise.then($.ajax({
    					url: 'gen_plan.php',
    					dataType: 'json',
    					data: {
    						0: $('#search_param1').val(),
    						1: $('#search_param2').val(),
    						2: $('#search_param3').val(), 
    						location: {
    							city: $('#location').val()
    						}
    					},
    					method: 'POST',
    					success:  function(response){
    						console.log(city_coordinates);
    						console.log('in success');
    						console.log('response: ', response);
    						window.search_result = response;

    						if(search_result.length == 0){
    							console.error("Reponse is empty");
    							return;
    						}
    						
    						for(var i=0; i<search_result[0].length; i++){
    							if(filterByDistance(city_coordinates, search_result[0][i], 0, i)){
    									i-=1;
    								}
    						}
    						var randomIndex = Math.floor(Math.random()*search_result[0].length);
    							event_array.push(search_result[0][randomIndex]);
    							console.log(event_array);

    						for(var i=1; i<search_result.length; i++){
    							console.log('i' ,i);
    							for(var j=0; j<search_result[i].length;j++){
    								console.log('length: ',search_result[i].length);
    								console.log('in the for loop in the for loop');
    								console.log('Comparing: ',event_array[0], search_result[i][j] );
    								if(filterByDistance(event_array[0], search_result[i][j], i, j)){
    									j-=1;
    								}
    								
    							}
    							var randomIndex = Math.floor(Math.random()*search_result[i].length);
    								event_array.push(search_result[i][randomIndex]);
    							console.log('event_array: ', event_array);
    						}
    					
    						//add a random event to the event array
    						
    						

    						
    						

    						//Create event jquery objects and push them to outing_results array + give each member of event_array an index.
    						for(var i=0; i<event_array.length;i++){

    							event_array[i].index_id = i;
    							createDescriptionText(event_array[i]);
	    						
    							var eventContainer = createEventElements(event_array[i]);

    							outing_results.push(eventContainer);
    						}
    							
    							
    							


    							

    							$('.center_stage').append(outing_results);
    							
    							for(var i=0;i<event_array.length; i++){
	    							
	    						}

    							var map_canvas = $('<div>',{
    												id: 'map-canvas',
    							})

    							// var map_marker = new google.maps.Marker({
											// 			      position: (33.6935463, -117.9250412),
											// 			      map: map,
											// 			      title: 'Hello World!'
											// 			  })
    							
    							$('.carousel_window').append(map_canvas);
    							function initialize() {
											  var mapOptions = {
											    zoom: 14,
											    center: {lat: event_array[0].location.coordinate.latitude, lng: event_array[0].location.coordinate.longitude},
											  };

											  var map = new google.maps.Map(document.getElementById('map-canvas'),
											      mapOptions);

											  for(var i=0; i<event_array.length;i++){
												  var marker = new MarkerWithLabel({
												      position: {lat: event_array[i].location.coordinate.latitude, lng: event_array[i].location.coordinate.longitude},
												      map: map,
												      title: event_array[i].name,
												      labelContent: event_array[i].name,
												      labelAnchor: new google.maps.Point(22, 0),
   													  labelClass: "labels",
												  })
												}
											}
									initialize();
    							
    							outing_results[0].addClass('current_event');



    							$('.outing_container').click(function(){
    								console.log(this);
    								$(this).toggleClass('open_event');
    							})


								$('.swap_button').click(function(){
									console.log(event_array);
									console.log('clicked swap_button');
									console.log($(this).attr('index_id'));
									index_to_get = $(this).attr('index_id');
									console.log(response[index_to_get]);
									event_array[index_to_get] = (search_result[index_to_get][Math.floor((Math.random()*search_result[index_to_get].length))]);
									console.log(event_array);
									$(outing_results[index_to_get][0]).remove();
									
									outing_results[index_to_get];

									generateEventInfo(index_to_get);
									console.log($(outing_results[index_to_get][0]));
									
									console.log(outing_results[index_to_get][0])

								})

								$('#accpt_btn').click(function(){
										console.log('in accept');
										for(var i=0; i<outing_results.length; i++){
											$(outing_results[i][0]).addClass('current_event');
										}
									})

    							}


    					
    			}))
			    	
    })
})

function createCarousel(){

	var carousel_window = $('<div>',{
    						class:'carousel_window col-sm-10 col-sm-offset-1'
    												
    });

    var center_stage = $('<div>',{
    						class:'center_stage col-sm-12',
    												
    });

	var prev_button = $('<button>',{
							type: 'button',
							id:'prev_btn',
							class:'col-sm-2',
							text: 'Prev',
							onclick: 'prevEvent();'
	})

	var accept_button = $('<button>',{
							type: 'button',
							id:'accpt_btn',
							class:'col-sm-2 col-sm-offset-3',
							text: 'Wrap Up Plan',
	})

		

	var next_button = $('<button>',{
							type: 'button',
							id:'next_btn',
							class:'col-sm-2 col-sm-offset-3',
							text: 'Next',
							onclick:'nextEvent();'
	})

	var plan_details_foot = $('<div>',{
							
							
							class:'col-sm-12',
							
	})

	carousel_window.append(center_stage);

	$('.plan-results').append(carousel_window);
	plan_details_foot.append(prev_button, accept_button, next_button);
	$('.plan-results').append(plan_details_foot);

}





function createDescriptionText(eventData){
	var description_text = '';
	for(var i=0; i<eventData.categories.length; i++){

		//check for last loop
		if(i==eventData.categories.length-1){
			description_text += eventData.categories[i][0];
		}else{
			description_text += eventData.categories[i][0] + ', ';
		}
		
	};

	eventData.description_text = description_text;

	
}

function createEventElements(eventData){

	var info_container = $('<div>',{
							class:'row outing_container col-sm-4 col-sm-offset-4',
							index_id: eventData.index,
						});

	var yelp_image = $('<img>',{
							src: eventData.image_url,
							class:'result_images col-sm-12',
						});

	var event_title = $('<h5>',{
						class: 'col-sm-12',
						text: eventData.name,

	})

	var description_span = $('<span>',{
						class: 'col-sm-12 description_span',
						text: eventData.description_text,
					
	})

	var price_span = $('<span>',{
						class: 'col-sm-12 event_details',
						text: 'Price: google',
	})

	var rate_span = $('<span>',{
						class: 'col-sm-12 event_details',
						text: 'Rating: '+eventData.rating,
	})

	var review_count_span = $('<span>',{
						class: 'col-sm-12 event_details',
						text: 'Review Count: '+eventData.review_count,
	})

	var hours_op_span = $('<span>',{
						class: 'col-sm-12 event_details',
						text: 'Hours: google',
	})

	var distance_span = $('<span>',{
						class: 'col-sm-12 event_details',
						text: 'Address: '+ eventData.location.address[0]+ ', '+eventData.location.city+ ', '+eventData.location.state_code,
	})

	var phone_span = $('<span>',{
						class: 'col-sm-12 event_details',
						text: 'Phone: '+ eventData.display_phone,
	})

	var swap_button = $('<button>',{
						class: 'col-sm-12 swap_button',
						index_id: eventData.index_id,
						text: 'Swap Event',
						
	})

	info_container.append(yelp_image, event_title, description_span, price_span, rate_span, review_count_span, hours_op_span, distance_span, phone_span, swap_button);

	return info_container;
}


	
 var current_index = 1;
	function nextEvent(){
		console.log('current_index; ', current_index);
		console.log('in next event');
		if(current_index<outing_results.length){		
			$(outing_results[current_index]).addClass('current_event');
			$(outing_results[current_index-1]).removeClass('current_event');
			$(outing_results[current_index-1]).addClass('previous_event');
			current_index+=1;
		}
	};

function prevEvent(){
		
		console.log('current_index; ', current_index);
		console.log('in prev event');
		if(current_index>0){
			current_index -=1;		
			$(outing_results[current_index]).removeClass('current_event');
			$(outing_results[current_index-1]).removeClass('previous_event');
			$(outing_results[current_index-1]).addClass('current_event');
			
		}
	};




							function generateEventInfo(index_to_generate){
								
								var description_text = '';
    							for(var cat=0; cat<event_array[index_to_generate].categories.length; cat++){
    								description_text += event_array[index_to_generate].categories[cat][0] + ', '
    							};

    							description_text = description_text.substr(0,description_text.length-2);
    							
    							var info_container = $('<div>',{
    												class:'row outing_container col-sm-4 col-sm-offset-4',
    												index_id: index_to_generate,


    							});
    							var yelp_image = $('<img>',{
    												src: event_array[index_to_generate].image_url,
    												class:'result_images col-sm-12',

    							});

    							var event_title = $('<h5>',{
    												class: 'col-sm-12',
    												text: event_array[index_to_generate].name,

    							});

    							var description_span = $('<span>',{
    												class: 'col-sm-12 description_span',
    												text: description_text,
    											
    							})

    							var price_span = $('<span>',{
    												class: 'col-sm-12 event_details',
    												text: 'Price: google',
    							})

    							var rate_span = $('<span>',{
    												class: 'col-sm-12 event_details',
    												text: 'Rating: '+event_array[index_to_generate].rating,
    							})

    							var review_count_span = $('<span>',{
    												class: 'col-sm-12 event_details',
    												text: 'Review Count: '+event_array[index_to_generate].review_count,
    							})

    							var hours_op_span = $('<span>',{
    												class: 'col-sm-12 event_details',
    												text: 'Hours: google',
    							})

    							var distance_span = $('<span>',{
    												class: 'col-sm-12 event_details',
    												text: 'Address: '+ event_array[index_to_generate].location.address[0]+ ', '+event_array[index_to_generate].location.city+ ', '+event_array[index_to_generate].location.state_code,
    							})

    							var phone_span = $('<span>',{
    												class: 'col-sm-12 event_details',
    												text: 'Phone: '+ event_array[index_to_generate].display_phone,
    							})

    							var swap_button = $('<button>',{
    												class: 'col-sm-12 swap_button',
    												index_id: index_to_generate,
    												text: 'Swap Event',
    												
    							})
    							
    							info_container.append(yelp_image, event_title, description_span, price_span, rate_span, review_count_span, hours_op_span, distance_span, phone_span, swap_button);
    							info_container.addClass('current_event');
    							
    							outing_results[index_to_generate] = (info_container);
    							
    							$('.center_stage').append(info_container);
    							$(outing_results[index_to_generate]).click(function(){
											$(outing_results[index_to_generate]).toggleClass('open_event');
									});
    							$('.swap_button').click(function(){
									console.log(event_array);
									console.log('clicked swap_button');
									console.log($(this).attr('index_id'));
									index_to_get = $(this).attr('index_id');
									console.log(search_result[index_to_get]);
									event_array[index_to_get] = (search_result[index_to_get][Math.floor((Math.random()*search_result[index_to_get].length))]);
									console.log(event_array);
									$(outing_results[index_to_get][0]).remove();
									
									outing_results[index_to_get];

									generateEventInfo(index_to_get);
									console.log($(outing_results[index_to_get][0]));
									
									console.log(outing_results[index_to_get][0])

								})

};





function distance(lat1, lon1, lat2, lon2, unit) {

    var radlat1 = Math.PI * lat1/180;

    var radlat2 = Math.PI * lat2/180;

    var radlon1 = Math.PI * lon1/180;

    var radlon2 = Math.PI * lon2/180;

    var theta = lon1-lon2;

    var radtheta = Math.PI * theta/180;
    
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);

    dist = Math.acos(dist);

    dist = dist * 180/Math.PI;

    dist = dist * 60 * 1.1515;

    if (unit=="K") { dist = dist * 1.609344 };

    if (unit=="N") { dist = dist * 0.8684 };

    return dist;

}


// for(var i=1; i<search_result.length; i++){
// 	for(var j=0; j<search_result[i]; j++){

// 	}

// }
function filterByDistance(center, outing, i, j){
	console.log('filtering');
	console.log('center: ', center);
	console.log('outing', outing);
		console.log('in filter by distance, i: ', i);
		console.log('in filter by distance, j: ', j);
		if(outing.location.address.length==0){
			return;
		}else if(distance(center.location.coordinate.latitude, center.location.coordinate.longitude, outing.location.coordinate.latitude, outing.location.coordinate.longitude, 'M')>5){
			console.log(distance(center.location.coordinate.latitude, center.location.coordinate.longitude, outing.location.coordinate.latitude, outing.location.coordinate.longitude, 'M'));
			search_result[i].splice(search_result[i][j], 1)
			console.log('not within 7 miles');
			return true;
		}else{
			console.log('within 7 miles');
		}
};
		








    </script>


</head>
<body>
	<div id="main_interface_container" class="col-sm-8 col-sm-offset-2">
		<div id='fb-login' class='col-sm-8 col-sm-offset-4'>
				<div id='status'></div>
				<fb:login-button class="login_button" scope="public_profile,email" onlogin="checkLoginState();">
				</fb:login-button>
				<button type='button' class='logout_button hidden_button'>Logout</button>
		</div>

		<button id ='set_plans_btn' type="button" class="col-sm-8 col-sm-offset-2">Give Me Plans</button>		

		<button id='manage_circles_btn' class='col-sm-8 col-sm-offset-2'>Manage Friends</button>
		
		<div id='reminisce_btn' class='col-sm-8 col-sm-offset-2'>
		</div>
	</div>
	
	<div id="plans-container" class="col-sm-8 col-sm-offset-2">
		<div class="plan-form">

			<h3 class='col-sm-4 col-sm-offset-4'>Set Plan Details</h3>
			 <form class ='col-sm-10 col-sm-offset-1' id='generate_form'>
       		<input type='text' value='dinner' class='col-sm-12  search_parameters' id='search_param1' name='search_param1' placeholder='Enter first stop i.e. "Restaurant"'>
       		<input type='text' value='drinks' class='col-sm-12 search_parameters' id='search_param2' name='search_param2' placeholder='Enter second stop i.e. "Bars"'>
       		<input type='text' value='club' class='col-sm-12  search_parameters' id='search_param3' name='search_param3' placeholder='Enter third stop i.e. "Club"'>
       		<input type='text' value='Irvine' class='col-sm-12  search_parameters' id='location' name='location' placeholder='Enter desired location.'>
       		<h5 class='col-sm-8 col-sm-offset-4'>Set Time:</h5>
       		<select class='col-sm-3' name="Week">
			  <option value="This Week">This Week</option>
			  <option value="Next Week">Next Week</option>
			  
			</select>
       		<select class='col-sm-3' name="day">
			  <option value="Monday">Monday</option>
			  <option value="Tuesday">Tuesday</option>
			  <option value="Wednesday">Wednesday</option>
			  <option value="Thursday">Thursday</option>
			  <option value="Friday">Friday</option>
			  <option value="Saturday">Saturday</option>
			  <option value="Sunday">Sunday</option>
			</select>
			<select class='col-sm-3' name="hour">
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
			<select class='col-sm-2' name="minute">
			  <option value="00">00</option>
			  <option value="15">15</option>
			  <option value="30">30</option>
			  <option value="45">45</option>
			</select>
			<select class='col-sm-1' name="morn_eve">
			  <option value="AM">AM</option>
			  <option value="PM">PM</option>
			</select>
        	<button id='generate_plans_btn' type="button" class="col-sm-3">Generate Plan</button>
        </form>
        
		</div>



		
		</div>

	
		<div class="plan-results col-sm-8 col-sm-offset-2">
		<h3 class='col-sm-4 col-sm-offset-4'>Review Plan Details</h3>
		
       
      </div>


		<div class='friend-page col-sm-8 col-sm-offset-2'>
			<h3 class='col-sm-2 col-sm-offset-5'>Who's Invited?</h3>
			<div class='friend_container col-sm-6'></div>
			

			

		</div>

      </div>
      
    </div>

  </div>
</div>


</body>
</html>
