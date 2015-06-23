
    



// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('about to test');
    console.log('statusChangeCallback');
    console.log(response);
    current_user_id = response.authResponse.userID;
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    

    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      $('#fb-login').toggleClass('hidden_button');
      $('.logout_button').toggleClass('hidden_button');
      
      var promise_backend_login = $.ajax({
                          url: 'access_facebook.php',
                          method: 'post',
                          data: {token: response.authResponse.accessToken, userID: response.authResponse.userID},
                          dataType: 'text',
                          success: function(response){
                            console.log('ajax call successful');
                            console.log(response);
                          }
                  });
      console.log('did back end log in?');
      promise_backend_login.then(testAPI());

      $('#manage_circles_btn').click(function(){
        console.log('in manage circles click handler');
        $.ajax({
          url:'get_friends.php',
          dataType:'json',
          data: {userId: current_user_id},
          method: 'post',
          success: function(response){
            console.log('in response');
            console.log(response);

            displayFriends(response);

          }
        })
      });


    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
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

        
      }

        var invite_button = $('<button>',{
                            type: 'button',
                            id:'invite_btn',
                            class:'col-sm-4 col-sm-offset-1',
                            text:'Send Invtes',
                           
        });  
     
      $('.friend-page').append(invite_button);

      $('.checkbutton').click(function(){
            console.log('click');
            
            
            console.log(invitees);
            console.log($(this)[0].attributes[4].value);
            console.log($(this)[0].attributes[2].value);
            if(this.checked){
              invitees[$(this)[0].attributes[2].value]= $(this)[0].attributes[4].value;
              console.log(invitees);
            }else{
              delete invitees[$(this)[0].attributes[2].value];
              console.log(invitees);
            }
      })
};
    












