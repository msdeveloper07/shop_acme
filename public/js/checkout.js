

         $(function() {
              window.onbeforeunload = function(e) {run_ajax();return true;}
              var owner = $('#owner');
              var cardNumber = $('#cardNumber');
              var cardNumberField = $('#card-number-field');
              var CVV = $("#cvv");
              var mastercard = $("#mastercard");
              var confirmButton = $('#confirm_purchase');
              var visa = $("#visa");
              var amex = $("#amex");
              var customer_email = $("#customer_email");
              var customer_first_name = $("#customer_first_name");
              var customer_last_name = $("#customer_last_name");
              var cus_add_address1 = $("#cus_add_address1");
              var cus_add_city = $("#cus_add_city");
              var country_to_state = $(".country_to_state");
              var state_select = $(".state_select");
              var billing_address_2_field = $("#cus_add_address2");
              var billing_postcode_field = $("#cus_add_zip");
              var billing_phone_field = $("#customer_phone");
              cardNumber.payform('formatCardNumber');
              CVV.payform('formatCardCVC');
              confirmButton.click(function(e) {
                 e.preventDefault();
                 if($( "#saved_card option:selected" ).val()){
                    var isCvvValid = $("#cvv_saved").val();
                    if (isCvvValid.length < 3) {
                      alert("Wrong CVV");
                    }else if (customer_email.val().length == '0') {
                       alert('Email field not empty ');
                    }else if (customer_first_name.val().length < '5') {
                       alert("First Name should More then 5 character");
                    }else if (cus_add_address1.val().length == '0') {
                       alert("Address field should not be empty");
                    }else if (cus_add_city.val().length == 0) {
                       alert("City field should not be empty");
                    }else if (billing_address_2_field.val().length == 0) {
                       alert("Address 2 field should not be empty");
                    }else if (billing_postcode_field.val().length == 0) {
                       alert("Pin code field should not be empty");
                    }else if (billing_phone_field.val().length == 0) {
                       alert("Pin code field should not be empty");
                    }else if (country_to_state.val().length == 0) {
                       alert("Country field should not be empty");
                    }else if (state_select.val().length == 0) {
                       alert("State field should not be empty");
                    } else {
                      if (confirm('Are You sure your details are correct?')) {mypopup();}
                    }
                 }else{
                   var isCardValid = $.payform.validateCardNumber(cardNumber.val());
                   var isCvvValid = $.payform.validateCardCVC(CVV.val());
                   if (owner.val().length < 5) {
                       alert("Wrong owner name");
                   } else if (!isCardValid) {
                       alert("Wrong card number");
                   } else if (!isCvvValid) {
                       alert("Wrong CVV");
                   }else if (customer_email.val().length == '0') {
                       alert("Valid Email");
                    }else if (customer_first_name.val().length < 5) {
                       alert("First Name should More then 5 charactor");
                    }else if (cus_add_address1.val().length == 0) {
                       alert("Address field should not be empty");
                    }else if (cus_add_city.val().length == 0) {
                       alert("City field should not be empty");
                    }else if (billing_address_2_field.val().length == 0) {
                       alert("Address 2 field should not be empty");
                    }else if (billing_postcode_field.val().length == 0) {
                       alert("Pin code field should not be empty");
                    }else if (billing_phone_field.val().length == 0) {
                       alert("Pin code field should not be empty");
                    }else if (country_to_state.val().length == 0) {
                       alert("Country field should not be empty");
                    }else if (state_select.val().length == 0) {
                       alert("State field should not be empty");
                    } else {
                       if (confirm('Are You sure your details are correct?')) {
                        mypopup();
                      }
                   }
                 }
             });
         });



          function mypopup() {
             var data = $('#raw_data').val();
             $('#loading').hide();
             $.ajax({
                 url: 'https://naveen.store/shopify_app/public/checkout/cardsubmit',
                 type: 'POST',
                 data: {
                     'customer_form': $('#customer_form').serialize()
                 },
                 beforeSend: function() {
                     $('#loading').show();
                     console.log('Order is Processing');
                 },
                 success: function(response) {
                  var objJSON = JSON.parse(response);                  
                  if (objJSON.success == 'true') {
                    alert('Order Complete Successfully');
                    window.onbeforeunload = null;
                    window.location.href = objJSON.redirect_url;
                  }else{                      
                  swal({type: 'info',html: objJSON.error,showCloseButton: true});
                  $('#loading').hide();
                  }
                }
              });
            }

         $(document).ready(function() {
                       var isMobile = window.matchMedia("only screen and (max-width: 760px)");

    if (isMobile.matches) {
       $("#order_review").fadeOut('slow');
      $("#arrow").html('<span class="glyphicon glyphicon-chevron-up"></span>');
      $('#order_review_to').click(function(){
        var button = $(this);
          if(button.attr('fade') == 'FadeOut') {
            $('#order_review').fadeOut('slow');
            $("#arrow").html('<span class="glyphicon glyphicon-chevron-down"></span>');
            button.attr('fade','FadeIn');
          }else {
            $('#order_review').fadeIn('slow');
            $("#arrow").html('<span class="glyphicon glyphicon-chevron-up"></span>');
            button.attr('fade',"FadeOut");
          }

      });

    }else{
       $("#order_review_to").hide();

    }
       $(".red").hide();
       $(".green").hide();
       $('#checkbox_green').click(function(){
                var inputValue = $(this).attr("value");
                if($(this).prop( "checked") == true){
                    $("." + inputValue).show();
                }else{
                    $("." + inputValue).hide();                        
                }
            });
            $('#checkbox_red').click(function(){
                var inputValue = $(this).attr("value");
                if($(this).prop( "checked") == true){
                    $("." + inputValue).show();
                }else{
                    $("." + inputValue).hide();                        
                }
            });

           $('.nav-toggle').click(function(){
         	//get collapse content selector
         	var collapse_content_selector = $(this).attr('href');					
         			
         	//make the collapse content to be shown or hide
         	var toggle_switch = $(this);
         	$(collapse_content_selector).toggle(function(){
         	  if($(this).css('display')=='none'){
                                       //change the button label to be 'Show'
         		toggle_switch.html('Show Order Summery');
         	  }else{
                                       //change the button label to be 'Hide'
         		toggle_switch.html('Hide Order Summery');
         	  }
         	});
           });
         		
         });	

          jQuery('#saved_card').on('change', function() {
              if ($(this).val() != '') {
                  jQuery('#new_cc').hide();
              } else {
                  jQuery('#new_cc').show();
              }
          });

          document.getElementById("confirm_purchase").onclick = function() {
              window.onbeforeunload = null;
          };
          setTimeout(function() {
              swal({
                  type: 'info',
                  html: 'You are not doing anything. we send your order as Abandoned Order',
                  showCloseButton: true
              });
              run_ajax();
          }, 600000);

          function run_ajax() {
              var customer_email = $('.customer_email').val();
              if (customer_email) {
                  console.log('customer_email: ' + customer_email);
                  $.ajax({
                      url: 'https://naveen.store/shopify_app/public/orders/abandoned_checkouts',
                      type: 'POST',
                      data: {
                          'raw_data': $('#raw_data').val(),
                          'customer_email': customer_email,
                      },
                      success: function(response) {
                          console.log(response);
                      }
                  });
              }
          }
          window.fbAsyncInit = function() {
              // FB JavaScript SDK configuration and setup
              FB.init({
                  appId: '211344289625310', // FB App ID
                  cookie: true, // enable cookies to allow the server to access the session
                  xfbml: true, // parse social plugins on this page
                  version: 'v2.8' // use graph api version 2.8
              });

              // Check whether the user already logged in
              FB.getLoginStatus(function(response) {
                  if (response.status === 'connected') {
                      //display user data
                      getFbUserData();
                  }
              });
          };

          // Load the JavaScript SDK asynchronously
          (function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s);
              js.id = id;
              js.src = "//connect.facebook.net/en_US/sdk.js";
              fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));

          // Facebook login with JavaScript SDK
          function fbLogin() {
              FB.login(function(response) {
                  if (response.authResponse) {
                      // Get and display the user profile data
                      getFbUserData();
                  } else {
                      document.getElementById('status').innerHTML = 'User canceled login or did not fully authorize.';
                  }
              }, {
                  scope: 'email'
              });
          }

          // Fetch the user profile data from facebook
          function getFbUserData() {
              FB.api('/me', {
                      locale: 'en_US',
                      fields: 'id,first_name,last_name,email,link,gender,locale,picture'
                  },
                  function(response) {
                      document.getElementById('fbLink').setAttribute("onclick", "fbLogout()");
                      document.getElementById('fbLink').innerHTML = 'Logout from Facebook';


                      jQuery('.customer_email').val(response.email);
                      jQuery('.customer_first_name').val(response.first_name);
                      jQuery('.customer_last_name').val(response.last_name);
                      jQuery('#user').val('facebook');
                  });
          }

          // Logout from facebook
          function fbLogout() {
              FB.logout();
          }