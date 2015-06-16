
<html>
<head>
	<title></title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel='stylesheet' href='outing_styles.css'>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>


    <script>
    var outing_results = [];
   
    var event_array = [];
    $(document).ready(function(){

    	$('#generate_plans_btn').click(function(){
    			$.ajax({
    					url: 'gen_plan.php',
    					dataType: 'json',
    					data: {0: $('#search_param1').val(),1: $('#search_param2').val(),2: $('#search_param3').val()},
    					method: 'Post',
    					success:  function(response){
    						console.log('in success');
    						console.log('response: ', response);
    						window.search_result = response;
    						
    						for(var i=0; i<search_result.length; i++){
    							event_array.push(search_result[i][Math.floor((Math.random()*search_result[i].length))])
    						}
    						console.log('event_array: ', event_array);
    						var carousel_window = $('<div>',{
    												class:'carousel_window col-sm-10 col-sm-offset-1'
    												
    										});

    						$('#plan_modal').append(carousel_window);
    						$('#generate_form').remove();
    						

    						
    						for(var i=0; i<event_array.length;i++){


    							
    							var info_container = $('<div>',{
    												class:'row outing_container col-sm-6 col-sm-offset-3',


    							});
    							var yelp_image = $('<img>',{
    												src: event_array[i].image,
    												class:'result_images col-sm-12',

    							});

    							var event_title = $('<h3>',{
    												class: 'col-sm-12',
    												text: event_array[i].name,

    							});

    							var price_span = $('<span>',{
    												class: 'col-sm-12',
    												text: 'Price: google',
    							})

    							var rate_span = $('<span>',{
    												class: 'col-sm-12',
    												text: 'Rating: '+event_array[i].rating,
    							})

    							var review_count_span = $('<span>',{
    												class: 'col-sm-12',
    												text: 'Review Count: '+event_array[i].review_count,
    							})

    							var hours_op_span = $('<span>',{
    												class: 'col-sm-12',
    												text: 'Hours: google',
    							})

    							var distance_span = $('<span>',{
    												class: 'col-sm-12',
    												text: 'Distance: '+ event_array[i].location.latitude+ ' ' + event_array[i].location.longitude,
    							})
    							
    							info_container.append(yelp_image, event_title, price_span, rate_span, review_count_span, hours_op_span, distance_span);
    							outing_results.push(info_container);
    							};


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

    							plan_details_foot.append(prev_button, accept_button, next_button);

    							$('#gen_plan_foot').remove()
    							for(var i=0; i<outing_results.length; i++){
    								(carousel_window).append(outing_results[i][0]);
    							}
    							$('#plan_modal').append(plan_details_foot);
    							outing_results[0].addClass('current_event');



    							$('.outing_container').click(function(){
    								console.log(this);
    								this.css('height: 40vh');
    							})




    					}
    			})
			    	
    })
})


	
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






    </script>


</head>
<body>
	<div id='main_interface_container' class='col-sm-8 col-sm-offset-2'>
		
		<!-- <div id='generate_plan_btn' class='col-sm-8 col-sm-offset-2'> -->
			<button id ='set_plans_btn' type="button" class="btn btn-info btn-lg col-sm-8 col-sm-offset-2" data-toggle="modal" data-target="#generatePlanModal">Give Me Plans</button>

<!-- Modal -->
<div id="generatePlanModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div id='generate_modal' class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Set Plan Details</h4>


      </div>
      <div id='plan_modal'class="modal-body">
       	
       <form class ='col-sm-10' id='generate_form'>
       		<input type='text' class='col-sm-12  search_parameters' id='search_param1' name='search_param1' placeholder='Enter first stop i.e. "Restaurant"'>
       		<input type='text' class='col-sm-12 search_parameters' id='search_param2' name='search_param2' placeholder='Enter second stop i.e. "Bars"'>
       		<input type='text' class='col-sm-12 col-sm-offset-6search_parameters' id='search_param3' name='search_param3' placeholder='Enter third stop i.e. "Club"'>
       		<select name="Week">
			  <option value="This Week">This Week</option>
			  <option value="Next Week">Next Week</option>
			  
			</select>
       		<select name="day">
			  <option value="Monday">Monday</option>
			  <option value="Tuesday">Tuesday</option>
			  <option value="Wednesday">Wednesday</option>
			  <option value="Thursday">Thursday</option>
			  <option value="Friday">Friday</option>
			  <option value="Saturday">Saturday</option>
			  <option value="Sunday">Sunday</option>
			</select>
			<select name="hour">
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
			  <option value="11">11</option>
			  <option value="12">12</option>
			</select>
			<select name="minute">
			  <option value="00">00</option>
			  <option value="15">15</option>
			  <option value="30">30</option>
			  <option value="45">45</option>
			</select>
			<select name="morn_eve">
			  <option value="AM">AM</option>
			  <option value="PM">PM</option>
			</select>
       </form>
       <div id='gen_plan_foot' class="modal-footer">
        <button id='generate_plans_btn' type="button" class="btn btn-default col-sm-3 col-sm-offset-9">Generate Plan</button>
      </div>


      </div>
      
    </div>

  </div>
</div>
<!-- END OF MODAL -->


		<div id='manage_circles_btn' class='col-sm-8 col-sm-offset-2'>
		</div>
		<div id='reminisce_btn' class='col-sm-8 col-sm-offset-2'>
		</div>
	

</body>
</html>
