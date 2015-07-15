


    


// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('about to test');
    console.log('statusChangeCallback');
    console.log(response);
    
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    

    if (response.status === 'connected') {
      current_user_id = response.authResponse.userID;
      console.log(current_user_id);
      // Logged into your app and Facebook.
      $('#fb-login').toggleClass('hidden_button');
      $('.logout_button').toggleClass('hidden_button');
      console.log('toggled hide on logout button');
      
      var promise_check_user = new Promise(function(resolve, reject){
                      resolve($.ajax({
                                    url: 'check_for_new_user.php',
                                    data:{user_id: current_user_id, token: response.authResponse.accessToken},
                                    dataType: 'text',
                                    method: 'post',
                                    success: function(response){
                                      console.log('in promise check user success function');
                                      console.log('response: ', response);

                                    },
                              }));

      });
      var promise_backend_login = new Promise(function(resolve, reject){
                        resolve($.ajax({
                                          url: 'access_facebook.php',
                                          method: 'post',
                                          data: {token: response.authResponse.accessToken, userID: response.authResponse.userID},
                                          dataType: 'json',
                                          success: function(response){
                                            console.log('ajax call successful');
                                            console.log(response);
                                          },
                                  }));
      });                      
      console.log('did back end log in?');
      var promise_for_next_event_id = new Promise(function(resolve, reject){  
        console.log('in promise for next event id');
        resolve(
          $.ajax({
            url: 'get_event_id.php',
            dataType: 'json',
            data: {user_id : current_user_id},
            method: 'post',
            success: function(response){
              console.log('in success');
              console.log('response in getNextEventID: ', response);
              next_event_id = response[0];
            },
        }));
      });
    
      promise_check_user.then(promise_backend_login).then(promise_for_next_event_id).then(testAPI());

     

     
      // $('#manage_circles_btn').click(function(){u
      //   console.log('in manage circles click handler');
     



    } else if (response.status === 'not_authorized') {
      current_user_id = null;
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      current_user_id = null;
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }


  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

   window.fbAsyncInit = function() {
    FB.init({
      appId      : '850571841703710',
      xfbml      : true,
      version    : 'v2.3'
    });
  
  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';

    });

  }


function displayFriends(arr){

      for(var i=0; i<arr.length; i++){
        console.log('in displayFriends loop');
        var individual_friend = $('<div>',{
                                class: 'individual_friend col-sm-12',
                                id: 'individual_friend'+i,
                                userId: arr[i].UserID,

        });

        var individual_avatar = $('<img>',{
                                  src: arr[i].Avatar,
                                  class: 'individual_avatar col-sm-2',
        });

        var username_span = $('<span>',{
                              text: arr[i].Username,
                              class: 'username_span col-sm-4 col-sm-offset-1',

        });

        var check_button = $('<input>',{
                              type:'checkbox',
                              id: 'check'+i,
                              index_id: i,
                              class:'col-sm-1 col-sm-offset-4 select_friend checkbutton',
                              text: 'Invite Friend',
                              userId: arr[i].UserID, 
        });

      


        individual_friend.append(individual_avatar, username_span, check_button);
        $('.friend_container').append(individual_friend);
        $('.list_of_friends').append(individual_friend);

        
      }

        var invite_button = $('<button>',{
                            type: 'button',
                            id:'invite_btn',
                            class:'col-sm-4 col-sm-offset-1',
                            text:'Send Invites',

                           
        });  

       
     
      $('.friend-page').append(invite_button);

      $('.checkbutton').click(function(){
            console.log('click');           
            event_object['user_id'] = current_user_id;
            console.log(event_object);
            
          
            console.log(this.checked);
            if(this.checked){
               event_object.invitees[$(this).attr('index_id')] = $(this).attr('userid');
              // [$(this).attr('index_id')]
              console.log(event_object);
            }else{
              console.log('in delete');
              delete event_object.invitees[$(this).attr('index_id')];
              console.log(event_object);
            }

            event_object['outing'] = event_array;
      })
};
    












