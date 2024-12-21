		  
	if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

	$(document).ready(function()
	{
    var site_url= '<?php echo $shop;?>';
	$('#dis_code').click(function() {
		$('#waiting_div_discount').show();
	});
	$('#discount_form').submit(function(){
	// show that something is loading
	$('#response').html("<b>Loading response...</b>");
	// Call ajax for pass data to other place
	$.ajax({
	type: 'POST',
	dataType: "json",
	url: "{{url('/checkout/apply-code')}}",
	data: $(this).serialize() // getting filed value in serialize form
	})
	.done(function(data){ // if getting done then call.
	var msg_check='';
	msg_check=data.msg;
	if(!msg_check){
	$("#total_price").hide();
	$("#purchase").show();
	$("#err_msg").hide();
	$("#updated_price").show();
	$('#waiting_div_discount').hide();
	$("#purchase").html('<td class="font-montserrat all-caps fs-12 w-50"> Purchase Code : </td><td class="text-right b-r b-dashed b-grey w-25"><span class="hint-text small">'+data.cpn_title+'</span></td><td class="w-25"><span class="font-montserrat fs-18">'+data.cpn_price+'</span></td>');
	$("#updated_price").html('<h4 class="m-b-20"><span><strong>Amount Due:</strong></span> <span class="text-success"><strong>'+data.total_onions+' Onions</strong></span></h4>');
	}else{
		console.log(msg_check);
		$("#err_msg").show();
	$("#err_msg").html(msg_check);
	$('#waiting_div_discount').hide();
	}})
	.fail(function() { // if fail then getting message
	// just in case posting your form failed
	alert( "Posting failed." );
	});
	// to prevent refreshing the whole page page
	return false;
	});	
      $.ajax({
      type:"GET",
      url:"{{url('/checkout/fetch-afstates')}}?code="+'AF',
      success:function(res)
      {               
        if(res){
        $("#state").empty();
        $.each(res,function(key,value){
          //console.log(res);
            $("#state").append('<option value="'+value.st_code+'">'+value.st_name+'</option>');
        });
      }
        else
        {
          $("#state").empty();
        }
      }
      });
    $('#country').change(function(){
    var countryID = $(this).val(); 
     localStorage.setItem('countryid', countryID);
     localStorage.setItem('country',  $("#country option:selected").html());
      if(countryID){
      $.ajax({
      type:"GET",
      url:"{{url('/checkout/fetch-states')}}?code="+countryID,
      success:function(res)
      {               
        if(res){
        $("#state").empty();
        $.each(res,function(key,value){
          //console.log(res);
            $("#state").append('<option value="'+value.st_code+'">'+value.st_name+'</option>');
        });
      }
        else
        {
          $("#state").empty();
        }
      }
      });}
     });
      $('#state').change(function(){
        var stateid = $(this).val(); 
        localStorage.setItem('stateid', stateid);
        localStorage.setItem('state',  $("#state option:selected").html());

      });
	});
	
	var barcode_value= '<?php echo $barcode_path;?>';
	var site_url= '<?php echo $shop;?>';

	$(function(){
	$('#barcode').qrcode(barcode_value);
	});
	document.getElementById("wallet_address_code").addEventListener("click", copy_password);

	function copy_password() {
		var copyText = document.getElementById("pwd_spn");
	   var textArea = document.createElement("textarea");
		textArea.value = copyText.textContent;
		document.body.appendChild(textArea);
		var elementTitle = document.getElementById('wallet_address_code').title;
		textArea.select();
		document.execCommand("Copy");
		$('#wallet_address_code').attr("data-original-title", "Copied successfully.");
		textArea.remove();
	}   

	$( document ).ready(function() {
	var countDownDate = new Date().getTime()+ (1500 * 1000);
	countdown_start(countDownDate);
	});
	  function countdown_start(countDownDate){
	var x = setInterval(function() {
	  var now = new Date().getTime();    
	  var distance = countDownDate - now;    
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);    
	  document.getElementById("countdown").innerHTML = "Invoice is valid for " + minutes + "m " + seconds + "s ";    
	  if (distance < 0) {
		  clearInterval(x);
		  window.location.replace(site_url+'/cart?message=this transaction has to be done again');

	  }
	}, 1000);
	}





    var cart_url= site_url+'/cart/clear.js';
    var line_items=<?php echo json_encode($cart_datas); ?>;
    var addon_address=<?php echo json_encode($wallet_address); ?>;
    var shipping_rates=<?php echo json_encode($shipping_rates); ?>;
    $('.form-control').on('input', function () {
    var email = $("#email").val();
    var fname = $("#fname").val();
    var lname = $("#lname").val();
    var address =  $("#address").val();
    var city = $("#city").val();
    var zip_code = $("#zip_code").val();
    var country =  $("#country").val();
    var state =  $("#state").val();
    var zip_code = $("#zip_code").val();
    localStorage.setItem('zip_code',zip_code);
      if (email != "" && fname != "" && lname != "" && zip_code !="") {
      AjaxValidate.call(email, fname, lname,zip_code,address,city,country,state);
      }
      });
    var AjaxValidate = {
    call: function (email, fname, lname,zip_code,address,city,country,state) {
    if (typeof this.xhr !== 'undefined') {
    this.xhr.abort();
    }

    this.xhr = $.ajax({
    url: "{{url('/checkout/activecarriers')}}",
    dataType: "json",
	  cache:false,
    type: 'POST',
    data: {
    email: email,
    fname: fname,
    lname: lname,
    zip_code: zip_code,
    address: address,
    city: city,
    state: state,
    country: country,
    line_items:line_items,
    addon_address:addon_address
    },
     beforeSend: function(){
		$("#waiting_div_ship").show();
    $("#ship_err_msg").hide();
		$("#shipping_carriers").hide();
    },
   /* complete: function(){
    $('#image').hide();
        $("#waiting_div_ship").hide();
    $("#shipping_carriers").hide();

    },*/
    success:function(res){ 
      var order_check='';

      var check= res.success;
      order_check=res.withoutship;
      var ship_check='';
        ship_check=res.msg;
      if(order_check)
      {
      	console.log('order');
          if(res.success==='true') 
          {
            $('#waiting_div').hide();
            $('#success_div').show(); 
            $("#waiting_div_ship").hide();
            $("#shipping_carriers").hide();
            $.getScript(cart_url);
            $.ajax({
              url: cart_url,
              cache: false,
              success: function(html){
              }
            }); 
            window.onbeforeunload = null;
            window.location.href = res.redirect_url;
          }else{
            var minuteMS = 20 * 1000; // seconds * milliSeconds
    			setInterval(function() {
    				this.xhr = $.ajax({
    					url: "{{url('/checkout/withoutShipping')}}",
    					dataType: "json",
    					cache:false,
    					type: 'POST',
    					data: {
    					email: email,
    					fname: fname,
    					lname: lname,
    					zip_code: zip_code,
    					address: address,
    					city: city,
    					state: state,
    					country: country,
    					line_items:line_items,
    					addon_address:addon_address
    					},
						success:function(response){
						if(response.success === 'true') 
						{
              $.getScript(cart_url);
              $.ajax({
              url: cart_url,
              cache: false,
              success: function(html){
              }
            }); 
							$('#waiting_div').hide();
							$('#success_div').show(); 
							$("#waiting_div_ship").hide();
							$("#shipping_carriers").hide();
							window.onbeforeunload = null;
							window.location.href = response.redirect_url;
					    }else
					    {
                $("#waiting_div_ship").hide();
    						$('#waiting_div').show();
                $("#ship_err_msg").show();
    						$("#ship_err_msg").html(ship_check);
					    }
    					}
			   	});
			}, minuteMS);
          }
      }
    	if(!ship_check)
    	{	
            $("#ship_err_msg").hide();
            $("#waiting_div_ship").hide();
            $("#shipping_carriers").show();
            $("#shipping_carriers").html('<h5>Shipping Method</h5>');
    		$.each(shipping_rates, function (key, val) {
    			//console.log(val.delivery_range);
    		});
             for(var i=0;i<res.length;i++)
             {
    			var rang = res[i].delivery_range;
    		if(rang == null){
    			var rang = 5;
    			$('<div class="radio radio-success" id="'+i+'" ><input value="'+res[i].checkout.total_price+'"  class="radiobutton" name="optionyes" id="mycheckbox'+i+'" type="radio"><label for="mycheckbox'+i+'">'+res[i].title+'-Free-'+res[i].price+'</label><div class="shipp_rang">(Within '+ rang +' Business Days)</div></div>').appendTo('#shipping_carriers');   
              }else{
    			
             $('<div class="radio radio-success" id="'+i+'" ><input value="'+res[i].checkout.total_price+'"  class="radiobutton" name="optionyes" id="mycheckbox'+i+'" type="radio"><label for="mycheckbox'+i+'">'+res[i].id+'</label><div class="shipp_rang">(Within '+ rang +' Business Days)</div></div>').appendTo('#shipping_carriers');   
              }
    		 }

	}else{
    $("#waiting_div_ship").hide();
		$("#ship_err_msg").show();
		$("#shipping_carriers").hide();
		$("#ship_err_msg").html(ship_check);
	}
      }
    });
    }
    };
    /***************
	*sending carrier and current checked checkbox info to the controller
	**/
	$(document).on("click", ".radio,.radio-success", function()
	 {
        var carrier_name;
        var id= $(this).attr('id');      
        var value= $('#mycheckbox'+id).val();  
        carrier_name=$('label[for="mycheckbox'+id+'"]').text();
        var cname= carrier_name;
        var combine=value + "-" + cname;
         $('#mycheckbox'+id).prop('checked', true);  
              $.ajax({
                    url : "{{url('/checkout/total-onions')}}/"+combine,
                    type : "GET",
                    dataType: "json",
                    cache:false,
                    success : function(data) {
                      $("#total_price").hide();
                      $("#updated_price").show();
    $("#updated_price").html('<h4 class="m-b-20"><span><strong>Amount Due:</strong></span> <span class="text-success"><strong>'+data.onions+' Onions</strong></span></h4>');
                checkBalance(data.onions,cname);
                    }
              });
              return false;    
         
	});
	/***************
	*keep checking balance against the merchnat address
	**/
	function checkBalance(onions,cname){
      var all_values={};
        all_values.fname=localStorage.getItem('fname');
        all_values.lname=localStorage.getItem('lname');
        all_values.email=localStorage.getItem('email');
        all_values.address=localStorage.getItem('address');
        all_values.address2=localStorage.getItem('address2');
        all_values.investor=localStorage.getItem('investor');
        all_values.city=localStorage.getItem('city');
        all_values.country=localStorage.getItem('country');
        all_values.countryid=localStorage.getItem('countryid');
        all_values.stateid=localStorage.getItem('stateid');
        all_values.state=localStorage.getItem('state');
      //  all_values.carrier_name=localStorage.getItem('carrier_name');
        all_values.zip_code=$("#zip_code").val();
        var onions=onions;
        var cname=cname;
        all_values.onions=onions;
        all_values.cname=cname;
      //  all_values.token=token;
        var encod= JSON.stringify(all_values);
		var value = 0;
    var xhr;
  
    var fn = function(){
    //function shippingAjax(){
	   value++;
        if(xhr && xhr.readyState != 4 && xhr.readyState != 0){
            xhr.abort();
        }
        xhr = $.ajax({
            url: "{{url('/checkout/check-payment')}}",
              dataType: "json",
              type: 'POST',
			        cache:false,
              data: {
              line_items:line_items,
              info:encod
          },
            success: function(data) 
			{
        console.log(data);
        var blnce_check='';
    blnce_check=data.msg;
			//	setInterval(function(){
			//	fn();
			//	}, 5000);
     if(!blnce_check)
      {
				$('#waiting_div').hide();
				$('#success_div').show();		 
          if (data.success == 'true') 
          {
  				  $.getScript(cart_url);
  					$.ajax({
  					  url: cart_url,
  					  cache: false,
  					  success: function(html){
  					  }
  					}); 
            window.onbeforeunload = null;
            window.location.href = data.redirect_url;
          }else{                      
              //  swal({type: 'info',html: objJSON.error,showCloseButton: true});
                $('#loading').hide();
                }
              }else{
               // alert(blnce_check);
               $("#ship_err_msg").show();
               $("#ship_err_msg").html(blnce_check);
              }
          }
        });
    };
    var minuteMS = 20 * 1000;
		var interval = setInterval(fn, minuteMS);
		//fn();	
	}
   
	function hideTotal()
	{
	var divobj = document.getElementById('totalPrice');
	divobj.style.display='none';
	}
      document.getElementById("fname").value = getSavedValue("fname");    // set the value to this input
      document.getElementById("lname").value = getSavedValue("lname");   // set the value to this input
      document.getElementById("email").value = getSavedValue("email");
      document.getElementById("address").value = getSavedValue("address");
      document.getElementById("city").value = getSavedValue("city");
      document.getElementById("address2").value = getSavedValue("address2");
      document.getElementById("investor").value = getSavedValue("investor");					
		/* Here you can add more inputs to set value. if it's saved */

        //Save the value function - save it to localStorage as (ID, VALUE)
        function saveValue(e){
            var id = e.id;  // get the sender's id to save it . 
            var val = e.value; // get the value. 
            localStorage.setItem(id, val);// Every time user writing something, the localStorage's value will override . 
        }
        //get the saved value function - return the value of "v" from localStorage. 
        function getSavedValue  (v){
            if (localStorage.getItem(v) === null) {
                return "";// You can change this to your defualt value. 
            }
            return localStorage.getItem(v);
        }
		
		
