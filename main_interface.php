
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
    var event_object = {};
    event_object['invitees'] = [];
    var next_event_id;
    var current_location = {};
    $(document).ready(function(){
   		
    	
		function getLocation() {
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition);
		    } else {
		       console.log("Geolocation is not supported by this browser.");
		    }
		}
		function showPosition(position) {
		   console.log("Latitude: " + position.coords.latitude + 
		    "<br>Longitude: " + position.coords.longitude);
		    current_location.lat =  position.coords.latitude;
		    current_location.lng = position.coords.longitude;
		}

		getLocation();



   		function makeNavigation(){
		   		$('#set_plans_btn').click(function(){
									console.log('set plans clicked');
					   				$('#main_interface_container').toggleClass('hidden');
					   				$('#plans-container').toggleClass('hidden');
					 });

					// $('.manage_circles_btn').click(function(){
					//    				$('#main_interface').toggleClass('hidden');
					//    				$('#plans-container').toggleClass('hidden');
					//  });

					
					$('#get_invites_btn').click(function(){
									console.log('get invites clicked');
					   				$('#main_interface_container').toggleClass('hidden');
					   				$('.pending_invitations').toggleClass('hidden');


					 });

					$('.return_to_main_from_invitations').click(function(){
							console.log('in back to main');
							$('#main_interface_container').toggleClass('hidden');
					   		$('.pending_invitations').toggleClass('hidden');
					   		$('.event_container').remove();
					   		$('.venue_container').remove();

					});

					$('.return_to_main_from_set_plans').click(function(){
							console.log('in back to main');
							$('#main_interface_container').toggleClass('hidden');
					   		$('.plans-container').toggleClass('hidden');
					});

					$('.logout_button').click(function(){
                			console.log('in logout button');
                    		FB.logout(function(response){
                    		console.log(response);
                        	if(response.status !== 'connected'){
                          		console.log('not connected');
                          		$('#fb-login').toggleClass('hidden_button');
                          		$('.logout_button').toggleClass('hidden_button');
                          		console.log('toggled hide on logout button');
                          		checkLoginState();
                         	};  
						});
        			});             

		};

		makeNavigation();




   //  	function addGroup(){

			// var group_div = $('<div>',{
			// 					class:'col-sm-12 group_container',
			// 					text: $('#group_name').val(),
			// 				});		
   //  	};


		function sendInvites(obj){
		    		if(obj.length == 0){
		    			console.log('Please select friends to invite.');
		    		}else{
		    			$.ajax({
		    					url:'invite_friends.php',
		    					dataType: 'text',
		    					data: obj,
		    					method: 'post',
		    					success: function(response){
		    						console.log('in success');
		    						console.log(response);
		    					},
		    				});
					};
		    	};			
		    			
		    		
		
		



			

  			

    	function createEventDiv(event_of_venues){
    		
    						var event_container = $('<div>',{
   													class:'col-sm-12 event_container',
   													index_id: event_of_venues[0]['Outing ID'],

   							});
   							var event_owner_pic = $('<img>',{
   													class:'col-sm-2 event_owner_pic',
   													src: event_of_venues[0].EventOwnerPicture,
   							});
   							var event_owner_name = $('<span>',{
   													class: 'col-sm-10 event_owner_name_span',
   													text: 'Event Owner: ' + event_of_venues[0].EventOwner,  													
   							});

   							event_container.append(event_owner_pic, event_owner_name);
   							for(var i=0; i<event_of_venues.length; i++){
   								var event_description_span = $('<span>',{
   													class: 'col-sm-10 venues_description_span',
   													text: i+1+": " +event_of_venues[i].VenueJson.name +': ' +event_of_venues[i].EventDetails,  													
   								});
   								event_container.append(event_description_span);
   							};
   							$('.invitations_container').append(event_container);

   		
					   		event_container.on('click',function(){
					   				console.log('clicked event_container');
					   				console.log(this);
					   				var event_id_to_show = $(this).attr('index_id');
					   				var venues = '.venue_of'+event_id_to_show;
					   				console.log('venue to show: ',venues);
					   				$(venues).toggleClass('hidden');
					   		});

    	};
 

   	


   		function getInvitations(user_id){
   			console.log('in get invitations function');
   			$.ajax({
   					url: 'get_invites.php',
   					dataType: 'json',
   					data: {user_id: user_id},
   					method: 'post',
   					success: function(response){
   						console.log('in success');
   						console.log(response);
   						window.invitations = response;
   						for(var i=0; i<invitations.length; i++){
   							createEventDiv(invitations[i]);
							for(var j=0; j<invitations[i].length; j++){
								createVenueDiv(invitations[i][j]);
						};
   					};
   				},

   			});
   		};	
						

  
   	function clickGetInvites(){
   		$('#get_invites_btn').click(function(){
   				getInvitations(current_user_id);
   		});
   	};
 	
    	
                   
        
    	
    	function pullFriends(){
			        $.ajax({
			          url:'get_friends.php',
			          dataType:'json',
			          data: {userId: current_user_id},
			          method: 'post',
			          success: function(response){
				            console.log('in response');
				            window.pullFriendsResponse = response;

							displayFriends(response);

				           $('#invite_btn').click(function(){
				           		console.log('invite button clicked');
				           		$('.friend-page').toggleClass('hidden');
				           		$('#main_interface_container').toggleClass('hidden');
				              console.log('invite button clicked');
				              sendInvites(event_object);
				             });

				          },
			        });
		};

		function filterByDistance(array_of_possibilities){

		};
    

    function getPlans(){
    				$.ajax({
    					url: 'gen_plan.php',
    					dataType: 'json',
    					data: {
    						0: $('#search_param1').val(),
    						1: $('#search_param2').val(),
    						2: $('#search_param3').val(), 
    						location: {
    							city: $('#location').val(),
    							coordinates: '34.0547, -117.1825',
    						},
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
    						};
    					},
    				});
    			};	
    	



    	$('#generate_plans_btn').click(function(){
    				console.log('clicked');
    				console.log(current_user_id);
    				console.log('event_object');
					$('#plans-container').toggleClass('hidden');
					$('.plan-results').toggleClass('hidden');


    				 	getPlans();
			   			pullFriends();	
			   



			   		});

		});






    				
    	// 			event_object['date'] = $('#week').val() +' ' + $('#day').val() + ':' + $('#hour').val() + 
    	// 			':' + $('#minute').val() + ' ' + $('#day_night').val();
    	// 			console.log(event_object);
    	// 			var promise_for_map = new Promise(function(resolve,reject){

    				 
					// 	resolve($.ajax({
					// 		url: 'https://maps.googleapis.com/maps/api/geocode/json?address='+$('#location').val()+'&key=AIzaSyCQNq766unXxvfCp1ZJ-aMCIT8tMmglOlo',
					// 		method: 'Get',
					// 		dataType: 'json',
					// 		success: function(response){
					// 				console.log(response);
					// 				city_coordinates = response;
					// 				city_coordinates.location = new Object;
					// 				city_coordinates.location.coordinate = new Object;
					// 				city_coordinates.location.coordinate.latitude = response.results[0].geometry.location.lat;
					// 				city_coordinates.location.coordinate.longitude= response.results[0].geometry.location.lng;
									


									
					// 				console.log(city_coordinates);

					// 		},
					// 	}));
					// }); 
					// createCarousel();
    	// 			promise_for_map.then(
    		
    					
    						//add a random event to the event array
    						
    						

    						
    						

    						//Create event jquery objects and push them to outing_results array + give each member of event_array an index.
    		// 				for(var i=0; i<event_array.length;i++){

    		// 					event_array[i].index_id = i;
    		// 					createDescriptionText(event_array[i]);
	    						
    		// 					var eventContainer = createEventElements(event_array[i]);

    		// 					outing_results.push(eventContainer);
    		// 				};
    							
    							
    							


    							

    		// 					$('.center_stage').append(outing_results);
    							
    		// 					for(var i=0;i<event_array.length; i++){
	    							
	    	// 					};

    		// 					var map_canvas = $('<div>',{
    		// 										id: 'map-canvas',
    		// 					});

    		// 					// var map_marker = new google.maps.Marker({
						// 					// 			      position: (33.6935463, -117.9250412),
						// 					// 			      map: map,
						// 					// 			      title: 'Hello World!'
						// 					// 			  })
    							
    		// 					$('.carousel_window').append(map_canvas);
    		// 					function initialize() {
						// 					  var mapOptions = {
						// 					    zoom: 14,
						// 					    center: {lat: event_array[0].location.coordinate.latitude, lng: event_array[0].location.coordinate.longitude},
						// 					  };

						// 					  var map = new google.maps.Map(document.getElementById('map-canvas'),
						// 					      mapOptions);

						// 					  for(var i=0; i<event_array.length;i++){
						// 						  var marker = new MarkerWithLabel({
						// 						      position: {lat: event_array[i].location.coordinate.latitude, lng: event_array[i].location.coordinate.longitude},
						// 						      map: map,
						// 						      title: event_array[i].name,
						// 						      labelContent: event_array[i].name,
						// 						      labelAnchor: new google.maps.Point(22, 0),
   			// 										  labelClass: "labels",
						// 						  });
						// 						};
						// 					};
						// 			initialize();
    							
    		// 					outing_results[0].addClass('current_event');



    		// 					$('.outing_container').click(function(){
    		// 						console.log(this);
    		// 						$(this).toggleClass('open_event');
    		// 					})


						// 		$('.swap_button').click(function(){
						// 			console.log(event_array);
						// 			console.log('clicked swap_button');
						// 			console.log($(this).attr('index_id'));
						// 			index_to_get = $(this).attr('index_id');
						// 			console.log(response[index_to_get]);
						// 			event_array[index_to_get] = (search_result[index_to_get][Math.floor((Math.random()*search_result[index_to_get].length))]);
						// 			console.log(event_array);
						// 			$(outing_results[index_to_get][0]).remove();
									
						// 			outing_results[index_to_get];

						// 			generateEventInfo(index_to_get);
						// 			console.log($(outing_results[index_to_get][0]));
									
						// 			console.log(outing_results[index_to_get][0])

						// 		});



    		// 				},
    		// 		});
    		// 	});
    			
    		// });			
    					
    		
			    	
    



// function createCarousel(){

// 	var carousel_window = $('<div>',{
//     						class:'carousel_window col-sm-10 col-sm-offset-1'
    												
//     });

//     var center_stage = $('<div>',{
//     						class:'center_stage col-sm-12',
    												
//     });

// 	var prev_button = $('<button>',{
// 							type: 'button',
// 							id:'prev_btn',
// 							class:'col-sm-2',
// 							text: 'Prev',
// 							onclick: 'prevEvent();'
// 	})

// 	var accept_button = $('<button>',{
// 							type: 'button',
// 							id:'accpt_btn',
// 							class:'col-sm-2 col-sm-offset-3',
// 							text: 'Wrap Up Plan',
// 	})

		

// 	var next_button = $('<button>',{
// 							type: 'button',
// 							id:'next_btn',
// 							class:'col-sm-2 col-sm-offset-3',
// 							text: 'Next',
// 							onclick:'nextEvent();'
// 	})

// 	var plan_details_foot = $('<div>',{
							
							
// 							class:'col-sm-12',
							
// 	})

// 	carousel_window.append(center_stage);

// 	$('.plan-results').append(carousel_window);
// 	plan_details_foot.append(prev_button, accept_button, next_button);
// 	$('.plan-results').append(plan_details_foot);

// 		promise_for_next_event_id.then($('#accpt_btn').click(function(){
// 					console.log('accept button clicked');
// 					$('.friend-page').toggleClass('hidden');
// 					$('.plan-results').toggleClass('hidden');
// 					event_object['event_id'] = next_event_id; 
// 					console.log('event_object: ',event_object);
// 					console.log('in accept');
// 					for(var i=0; i<outing_results.length; i++){
// 						$(outing_results[i][0]).addClass('current_event');
// 					};

					
// 				}));
	




// };

// function createDescriptionText(eventData){
// 	var description_text = '';
// 	for(var i=0; i<eventData.categories.length; i++){

// 		//check for last loop
// 		if(i==eventData.categories.length-1){
// 			description_text += eventData.categories[i][0];
// 		}else{
// 			description_text += eventData.categories[i][0] + ', ';
// 		}
		
// 	};

// 	eventData.description_text = description_text;

	
// }

// function createEventElements(eventData){

// 	var info_container = $('<div>',{
// 							class:'row outing_container col-sm-4 col-sm-offset-4',
// 							index_id: eventData.index,
// 						});

// 	var yelp_image = $('<img>',{
// 							src: eventData.image_url,
// 							class:'result_images col-sm-12',
// 						});

// 	var event_title = $('<h5>',{
// 						class: 'col-sm-12',
// 						text: eventData.name,

// 	})

// 	var description_span = $('<span>',{
// 						class: 'col-sm-12 description_span',
// 						text: eventData.description_text,
					
// 	})

// 	var price_span = $('<span>',{
// 						class: 'col-sm-12 event_details',
// 						text: 'Price: google',
// 	})

// 	var rate_span = $('<span>',{
// 						class: 'col-sm-12 event_details',
// 						text: 'Rating: '+eventData.rating,
// 	})

// 	var review_count_span = $('<span>',{
// 						class: 'col-sm-12 event_details',
// 						text: 'Review Count: '+eventData.review_count,
// 	})

// 	var hours_op_span = $('<span>',{
// 						class: 'col-sm-12 event_details',
// 						text: 'Hours: google',
// 	})

// 	var distance_span = $('<span>',{
// 						class: 'col-sm-12 event_details',
// 						text: 'Address: '+ eventData.location.address[0]+ ', '+eventData.location.city+ ', '+eventData.location.state_code,
// 	})

// 	var phone_span = $('<span>',{
// 						class: 'col-sm-12 event_details',
// 						text: 'Phone: '+ eventData.display_phone,
// 	})

// 	var swap_button = $('<button>',{
// 						class: 'col-sm-12 swap_button',
// 						index_id: eventData.index_id,
// 						text: 'Swap Event',
						
// 	})

// 	info_container.append(yelp_image, event_title, description_span, price_span, rate_span, review_count_span, hours_op_span, distance_span, phone_span, swap_button);

// 	return info_container;
// }


	
//  var current_index = 1;
// 	function nextEvent(){
// 		console.log('current_index; ', current_index);
// 		console.log('in next event');
// 		if(current_index<outing_results.length){		
// 			$(outing_results[current_index]).addClass('current_event');
// 			$(outing_results[current_index-1]).removeClass('current_event');
// 			$(outing_results[current_index-1]).addClass('previous_event');
// 			current_index+=1;
// 		}
// 	};

// function prevEvent(){
		
// 		console.log('current_index; ', current_index);
// 		console.log('in prev event');
// 		if(current_index>0){
// 			current_index -=1;		
// 			$(outing_results[current_index]).removeClass('current_event');
// 			$(outing_results[current_index-1]).removeClass('previous_event');
// 			$(outing_results[current_index-1]).addClass('current_event');
			
// 		}
// 	};




// 							function generateEventInfo(index_to_generate){
								
// 								var description_text = '';
//     							for(var cat=0; cat<event_array[index_to_generate].categories.length; cat++){
//     								description_text += event_array[index_to_generate].categories[cat][0] + ', '
//     							};

//     							description_text = description_text.substr(0,description_text.length-2);
    							
//     							var info_container = $('<div>',{
//     												class:'row outing_container col-sm-4 col-sm-offset-4',
//     												index_id: index_to_generate,


//     							});
//     							var yelp_image = $('<img>',{
//     												src: event_array[index_to_generate].image_url,
//     												class:'result_images col-sm-12',

//     							});

//     							var event_title = $('<h5>',{
//     												class: 'col-sm-12',
//     												text: event_array[index_to_generate].name,

//     							});

//     							var description_span = $('<span>',{
//     												class: 'col-sm-12 description_span',
//     												text: description_text,
    											
//     							});

//     							var price_span = $('<span>',{
//     												class: 'col-sm-12 event_details',
//     												text: 'Price: google',
//     							});

//     							var rate_span = $('<span>',{
//     												class: 'col-sm-12 event_details',
//     												text: 'Rating: '+event_array[index_to_generate].rating,
//     							});

//     							var review_count_span = $('<span>',{
//     												class: 'col-sm-12 event_details',
//     												text: 'Review Count: '+event_array[index_to_generate].review_count,
//     							});

//     							var hours_op_span = $('<span>',{
//     												class: 'col-sm-12 event_details',
//     												text: 'Hours: google',
//     							});

//     							var distance_span = $('<span>',{
//     												class: 'col-sm-12 event_details',
//     												text: 'Address: '+ event_array[index_to_generate].location.address[0]+ ', '+event_array[index_to_generate].location.city+ ', '+event_array[index_to_generate].location.state_code,
//     							});

//     							var phone_span = $('<span>',{
//     												class: 'col-sm-12 event_details',
//     												text: 'Phone: '+ event_array[index_to_generate].display_phone,
//     							});

//     							var swap_button = $('<button>',{
//     												class: 'col-sm-12 swap_button',
//     												index_id: index_to_generate,
//     												text: 'Swap Event',
    												
//     							});
    							
//     							info_container.append(yelp_image, event_title, description_span, price_span, rate_span, review_count_span, hours_op_span, distance_span, phone_span, swap_button);
//     							info_container.addClass('current_event');
    							
//     							outing_results[index_to_generate] = (info_container);
    							
//     							$('.center_stage').append(info_container);
//     							$(outing_results[index_to_generate]).click(function(){
// 											$(outing_results[index_to_generate]).toggleClass('open_event');
// 									});
//     							$('.swap_button').click(function(){
									
// 									index_to_get = $(this).attr('index_id');
									
// 									event_array[index_to_get] = (search_result[index_to_get][Math.floor((Math.random()*search_result[index_to_get].length))]);
									
// 									$(outing_results[index_to_get][0]).remove();
									
// 									outing_results[index_to_get];

// 									generateEventInfo(index_to_get);
// 									console.log($(outing_results[index_to_get][0]));
									
// 									console.log(outing_results[index_to_get][0]);


// 								});

// };


// 	function createVenueDiv(individual_venue){
//    							console.log(individual_venue);

//    							var venue_container = $('<div>',{
//    													class:'col-sm-12 hidden venue_container venue_of'+individual_venue['Outing ID'],
//    							});
//    							var venue_pic = $('<img>',{
//    													class: 'col-sm-2 venue_pic',
//    													src: individual_venue.VenueJson['image_url'],  													
//    							});
//    							var venue_details_span = $('<span>',{
//    													class: 'col-sm-2 venue_details_span',
//    													text: individual_venue.EventDetails,  													
//    							});
//    							var venue_rating = $('<img>',{
//    													class: 'col-sm-2 venue_pic',
//    													src: individual_venue.VenueJson['rating_img_url'],  													
//    							});
//    							var venue_review_count = $('<span>',{
//    													class: 'col-sm-2 venue_review_count',
//    													text: 'Review count: ' + individual_venue.VenueJson['review_count'],  													
//    							});
//    							var venue_address = $('<span>',{
//    													class: 'col-sm-4  venue_address',
//    													text: 'Address: ' + individual_venue.Address +", " + individual_venue.VenueJson.location.city,  													
//    							});
//    							var venue_website = $('<span>',{
//    													class: 'col-sm-12 venue_website',
//    													text: 'Website: ' + individual_venue.VenueJson['url'],  													
//    							});
//    							var website_link = $('<a>',{
//    												class: 'col-sm-10 website_link',
//    												href: individual_venue.VenueJson['url'],
//    												text: individual_venue.VenueJson.name,
//    												target: '_blank',
//    							});
//    							console.log('website_link: ', website_link[0]);

//    							venue_container.append(venue_pic, venue_details_span, venue_rating, venue_review_count, venue_address, venue_website, 
//    								website_link[0]);
//    							$('.details_of_event').append(venue_container);
//    		};






		

	
     



    </script>


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
