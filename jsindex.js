    var event_array = [];
    var city_coordinates;
    var outing_results = [];
    var current_user_id;
    var event_object = {};
    event_object['invitees'] = [];
    var next_event_id;
    var current_location = {};
    var distance_limit = 5;
    
    $(document).ready(function(){
		getLocation();
		makeNavigation();
		
		
    
	});
   

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
					
				};
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

					var website_link = $('<a>',{
						href:event_array[index_to_generate].url,
						target: '_blank',
					});

					

					var yelp_image = $('<img>',{
										src: event_array[index_to_generate].image_url,
										class:'result_images col-sm-12',

					});

					

					var event_title = $('<h5>',{
										class: 'col-sm-12',
										text: event_array[index_to_generate].name,

					});
					website_link.append(event_title);

					var description_span = $('<span>',{
										class: 'col-sm-12 description_span',
										text: description_text,
									
					});

					var price_span = $('<span>',{
										class: 'col-sm-12 event_details',
										text: 'Price: google',
					});

					var rate_span = $('<span>',{
										class: 'col-sm-12 event_details',
										text: 'Rating: '+event_array[index_to_generate].rating,
					});

					var review_count_span = $('<span>',{
										class: 'col-sm-12 event_details',
										text: 'Review Count: '+event_array[index_to_generate].review_count,
					});

					var hours_op_span = $('<span>',{
										class: 'col-sm-12 event_details',
										text: 'Hours: google',
					});

					var distance_span = $('<span>',{
										class: 'col-sm-12 event_details',
										text: 'Address: '+ event_array[index_to_generate].location.address[0]+ ', '+event_array[index_to_generate].location.city+ ', '+event_array[index_to_generate].location.state_code,
					});

					var phone_span = $('<span>',{
										class: 'col-sm-12 event_details',
										text: 'Phone: '+ event_array[index_to_generate].display_phone,
					});

					var swap_button = $('<button>',{
										class: 'col-sm-12 swap_button',
										index_id: index_to_generate,
										text: 'Swap Event',
										
					});
					
					info_container.append(yelp_image, website_link, description_span, price_span, rate_span, review_count_span, hours_op_span, distance_span, phone_span, swap_button);
					if(index_to_generate==0){
					info_container.addClass('current_event');
					}else{
						info_container.addClass('next_event');
					}			
					outing_results[index_to_generate] = (info_container);
					
					$('.center_stage').append(info_container);
					$(outing_results[index_to_generate]).click(function(){
								$(outing_results[index_to_generate]).toggleClass('open_event');
						});
					$('.swap_button').click(function(){
						
						index_to_get = $(this).attr('index_id');
						
						event_array[index_to_get] = (search_result[index_to_get][Math.floor((Math.random()*search_result[index_to_get].length))]);
						
						$(outing_results[index_to_get][0]).remove();
						
						

						generateEventInfo(index_to_get);
						outing_results[index_to_get].addClass('current_event');
						console.log($(outing_results[index_to_get][0]));
						
						console.log(outing_results[index_to_get][0]);
						$('#map_canvas').remove()
						promise_for_map.then(initialize());

					});

};

		function filterByDistance(array_of_possibilities, distance_limit){
			var index_to_use = Math.floor(Math.random() * array_of_possibilities[0].length);
			event_array.push(array_of_possibilities[0][index_to_use]);
			var origin = [event_array[0].location.coordinate.latitude, event_array[0].location.coordinate.longitude];	
			var indeces_to_chose_from = [];
			var destinations = [];
			for(var i=1; i<array_of_possibilities.length; i++){
				for(var j=0; j<array_of_possibilities[i].length; j++){
					var destination_object = {};
					destination_object._1dim = i;
					destination_object._2dim = j;
					destination_object.lat = array_of_possibilities[i][j].location.coordinate.latitude;
					destination_object.lng = array_of_possibilities[i][j].location.coordinate.longitude;
					destinations.push(destination_object);
					};
				};
				$.ajax({
					url: 'filter_by_distance.php',
					method: 'POST',
					dataType: 'json',
					data: {destination_array: destinations, origin_coordinate: origin},
					success: function(response){
						console.log('in success');
						console.log(response);
						 
						window.distances = [];
						for(var i=0; i<response.rows[0].elements.length; i++){
							distances.push(response.rows[0].elements[i].duration.text);
						};						
						for(var i=1; i<array_of_possibilities.length; i++){
							for(var j=0; j<array_of_possibilities[i].length; j++){
								array_of_possibilities[i][j].distance = parseInt(distances.splice(0,1).join().substr(0,1));
							};
						};						
						for(var i=1; i<array_of_possibilities.length; i++){
							for(var j=0; j<array_of_possibilities[i].length; j++){
								if(array_of_possibilities[i][j].distance > distance_limit){
									console.log(array_of_possibilities[i][j].distance ,'distance is greater than 5');
									 array_of_possibilities[i].splice(j,1);
									i--;	
								};
							};
						};					
						for(var i=1; i<array_of_possibilities.length; i++){
							console.log('array_of_possibilities[i]: ', array_of_possibilities[i]);
							var random_index = Math.floor(Math.random() * array_of_possibilities[i].length);
							console.log('random index',random_index);
							event_array.push(array_of_possibilities[i][random_index]);
							console.log(array_of_possibilities[i][random_index]);
						};
						console.log('event_array: ', event_array);
						//take newly made event_array and display it
						for(var i=0; i<event_array.length; i++){
							generateEventInfo(i);
							createDescriptionText(event_array[i]);
						};

						console.log(event_array);	
						promise_for_map.then(initialize());
					},
				});	
			};

			function getPlans(){
				console.log('in get plans');
				var coordinates = null;
				if($('.use_my_location').val()==1){
					coordinates = ''+current_location.lat+','+current_location.lng;
				};
    				$.ajax({
    					url: 'gen_plan.php',
    					dataType: 'json',
    					data: {
    						0: $('#search_param1').val(),
    						1: $('#search_param2').val(),
    						2: $('#search_param3').val(), 
    						location: {
    							city: $('#location').val(),
    							coordinates: coordinates,
    						},
    					},
    					method: 'POST',
    					success:  function(response){
    						console.log(city_coordinates);
    						console.log('in success');
    						console.log('response: ', response);
    						window.search_result = response;
    						filterByDistance(search_result, distance_limit);

    						if(search_result.length == 0){
    							console.error("Reponse is empty");
    							return;
    						};
    					},
    				});
    			};

    		function createCarousel(){

				var carousel_window = $('<div>',{
			    						class:'carousel_window col-sm-10 col-sm-offset-1',
			    });
			    var center_stage = $('<div>',{
			    						class:'center_stage col-sm-12',		
			    });
				var prev_button = $('<button>',{
										type: 'button',
										id:'prev_btn',
										class:'col-sm-2',
										text: 'Prev',
										onclick: 'prevEvent();',
				});
				var accept_button = $('<button>',{
										type: 'button',
										id:'accpt_btn',
										class:'col-sm-2 col-sm-offset-3',
										text: 'Wrap Up Plan',
				});
				var next_button = $('<button>',{
										type: 'button',
										id:'next_btn',
										class:'col-sm-2 col-sm-offset-3',
										text: 'Next',
										onclick:'nextEvent();',
				});
				var plan_details_foot = $('<div>',{
										class:'col-sm-12',
				});

				carousel_window.append(center_stage);
				$('.plan-results').append(carousel_window);

				plan_details_foot.append(prev_button, accept_button, next_button);
				$('.plan-results').append(plan_details_foot);
					
					
				$('#accpt_btn').click(function(){
						console.log('accept button clicked');
						for(var i=0; i<outing_results.length; i++){
							$(outing_results[i][0]).addClass('current_event');
						};
						setTimeout(function(){
						$('.friend-page').toggleClass('hidden');
						$('.plan-results').toggleClass('hidden');
						event_object['event_id'] = next_event_id; 
						console.log('event_object: ',event_object);
						console.log('in accept');
					},1000);
				});															
		};

	var promise_for_map = new Promise(function(resolve,reject){

	 
		resolve($.ajax({
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

			},
		}));
	}); 

	


				
				function initialize() {
					var map_canvas = $('<div>',{
									id: 'map-canvas',
					});

				
				
					$('.carousel_window').append(map_canvas);
							  
							  var mapOptions = {
							    zoom: 11,
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
								  });
								};
							};
					

		function makeNavigation(){
		   		$('#set_plans_btn').click(function(){
									console.log('set plans clicked');
					   				$('#main_interface_container').toggleClass('hidden');
					   				$('#plans-container').toggleClass('hidden');
					   				
					   				$('#generate_plans_btn').click(function(){
					    				console.log('clicked');
					    				console.log(current_user_id);
					    				console.log('event_object');
										$('#plans-container').toggleClass('hidden');
										$('.plan-results').toggleClass('hidden');
										event_object['date'] = $('#week').val() +' ' + $('#day').val() + ':' + $('#hour').val() + 
										':' + $('#minute').val() + ' ' + $('#day_night').val();
								    	
					    				getPlans();
							   			pullFriends();
							   			createCarousel();
							   			
							   			
							   			
							   				
								});

					 });

					// $('.manage_circles_btn').click(function(){
					//    				$('#main_interface').toggleClass('hidden');
					//    				$('#plans-container').toggleClass('hidden');
					//  });

					
					$('#get_invites_btn').click(function(){
									console.log('get invites clicked');
					   				$('#main_interface_container').toggleClass('hidden');
					   				$('.pending_invitations').toggleClass('hidden');
					   				getInvitations(current_user_id);


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


		function getLocation(){
		    if(navigator.geolocation){
		        navigator.geolocation.getCurrentPosition(showPosition);
		    }else{
		       console.log("Geolocation is not supported by this browser.");
		    };
		};

		function showPosition(position){
			current_location.lat=position.coords.latitude;
		    current_location.lng=position.coords.longitude;
		};
    
	
    	

			 
								
			
		
		
	
		

		
			



   		




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
    						console.log(event_of_venues);
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
						

  
   
                   
        
    	
    		
    	



    	

	





    				
    	

    							
    	// 						outing_results[0].addClass('current_event');



    	// 						$('.outing_container').click(function(){
    	// 							console.log(this);
    	// 							$(this).toggleClass('open_event');
    	// 						})


					// 			$('.swap_button').click(function(){
					// 				console.log(event_array);
					// 				console.log('clicked swap_button');
					// 				console.log($(this).attr('index_id'));
					// 				index_to_get = $(this).attr('index_id');
					// 				console.log(response[index_to_get]);
					// 				event_array[index_to_get] = (search_result[index_to_get][Math.floor((Math.random()*search_result[index_to_get].length))]);
					// 				console.log(event_array);
					// 				$(outing_results[index_to_get][0]).remove();
									
					// 				outing_results[index_to_get];

					// 				generateEventInfo(index_to_get);
					// 				console.log($(outing_results[index_to_get][0]));
									
					// 				console.log(outing_results[index_to_get][0])

					// 			});



    	// 					},
    	// 			});
    	// 		});
    			
    	// 	});			
    					
    		
			    	
    







function createEventElements(eventData){
	console.log('in create event elements');
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


	
 




							


	function createVenueDiv(individual_venue){
   							console.log(individual_venue);

   							var venue_container = $('<div>',{
   													class:'col-sm-12 hidden venue_container venue_of'+individual_venue['Outing ID'],
   							});
   							var venue_pic = $('<img>',{
   													class: 'col-sm-2 venue_pic',
   													src: individual_venue.VenueJson['image_url'],  													
   							});
   							var venue_details_span = $('<span>',{
   													class: 'col-sm-2 venue_details_span',
   													text: individual_venue.EventDetails,  													
   							});
   							var venue_rating = $('<img>',{
   													class: 'col-sm-2 venue_pic',
   													src: individual_venue.VenueJson['rating_img_url'],  													
   							});
   							var venue_review_count = $('<span>',{
   													class: 'col-sm-2 venue_review_count',
   													text: 'Review count: ' + individual_venue.VenueJson['review_count'],  													
   							});
   							var venue_address = $('<span>',{
   													class: 'col-sm-4  venue_address',
   													text: 'Address: ' + individual_venue.Address +", " + individual_venue.VenueJson.location.city,  													
   							});
   							var venue_website = $('<span>',{
   													class: 'col-sm-12 venue_website',
   													text: 'Website: ' + individual_venue.VenueJson['url'],  													
   							});
   							var website_link = $('<a>',{
   												class: 'col-sm-10 website_link',
   												href: individual_venue.VenueJson['url'],
   												text: individual_venue.VenueJson.name,
   												target: '_blank',
   							});
   							console.log('website_link: ', website_link[0]);

   							venue_container.append(venue_pic, venue_details_span, venue_rating, venue_review_count, venue_address, venue_website, 
   								website_link[0]);
   							$('.details_of_event').append(venue_container);
   		};






		

	
     



  
