<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>

<div class="container">
	<h2>Select Carriers list of API</h2>
	<ul id="carriers_list">
		<?php $__currentLoopData = $carriers['data']['carriers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $carrier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		  <li>
		    <input type="radio" id="t-option" name="carrier" value="<?php echo e($carrier['carrier_id']); ?>" <?php if(!$index): ?> <?php echo "checked"; ?> <?php endif; ?>>
		    <label for="t-option"><?php echo e($carrier['carrier_code']); ?></label>
		    <div class="check"><div class="inside"></div></div>
		  </li>
	  	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</ul>
	<table id="customers">
    <thead>
		<tr>
      <th></th>
			<th>Package ID</th>
			<th>Package Code</th>
			<th>Name</th>
			<th>description</th>
		</tr>
		</thead>
    <tbody>
    </tbody>
</div>

<script>
  $('input[name=carrier]').change(function(){
    $("tbody tr").empty();
  	var _shop = "<?php echo $shop; ?>";
  	var carrier_id = $('input[name=carrier]:checked').val();
  	var formData = {
  		carrier_id: $('input[name=carrier]:checked').val(),
  		shop: _shop
      }
    runAjax(formData);
  });

  $(window).load(function() {
    var _shop = "<?php echo $shop; ?>";
    var carrier_id = $('input[name=carrier]:checked').val();
    console.log('ready..! '+carrier_id);
    var formData = {
      carrier_id: $('input[name=carrier]:checked').val(),
      shop: _shop
      }
    runAjax(formData);
  });

  function runAjax(formData)
  {
    var p_code = "<?php echo $package_code; ?>";
    var getUrl = window.location;
    var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    $.ajax({
        type: "POST",
        url: baseUrl + '/swapp/carriers_package',
        data: formData,
        cache: false,
        beforeSend: function() {
          $('#loading-image').show();
        },
        complete: function() {
          $('#loading-image').hide();
          $('input[name=package_code][value='+p_code+']').attr('checked', 'checked');
        },
        success: function(result) {
            var object = result.data.packages;
            //console.log(object)
            $.each(object, function(index, value) {
               var innerHtml = '<tr><td><input type="radio" id="package_code" name="package_code" value="'+value.package_code+'" p_name="'+value.name+'" p_des="'+value.description+'"></td><td>'+value.package_id+'</td><td>'+value.package_code+'</td><td>'+value.name+'</td><td>'+value.description+'</td></tr></table>';
               $('tbody').append(innerHtml);
            });
        }
    });
  }
  $('#customers').on('click', '#package_code', function() {
    var _shop = "<?php echo $shop; ?>";
    var package_code = $('input[name=package_code]:checked').val();
    var _carrier_id = $('input[name=carrier]:checked').val();
    var _pname = $('input[name=package_code]:checked').attr("p_name");
    var _p_des = $('input[name=package_code]:checked').attr("p_des");
    var formData = {
      package_code: $('input[name=package_code]:checked').val(),
      carrier_id: _carrier_id,
      shop: _shop,
      package_name: _pname,
      package_des: _p_des
      }
      var getUrl = window.location;
      var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
      $.ajax({
          type: "POST",
          url: baseUrl + '/swapp/carriers_package_save',
          data: formData,
          cache: false,
          beforeSend: function() {
             $('#loading-image').show();
          },
          complete: function() {
             $('#loading-image').hide();
          },
          success: function(result) {
              console.log('success');
          }
      });
    });

// $(document).ready(function() {
// 	var getUrl = window.location;
// 	var baseUrl = getUrl.protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
// 	//console.log(baseUrl)
// 	$('#profile-tab5').on('click', function(event) {
//         event.preventDefault();
//         $.ajax({
// 	       type: "GET",
// 	       url: baseUrl + '/swapp/carriers_list',
// 	       beforeSend: function() {
// 	           $('#loading-image').show();
// 	       },
// 	       complete: function() {
// 	           $('#loading-image').hide();
// 	       },
// 	       success: function(result) {
// 	           var object = result.data.carriers;
// 	           $.each(object, function(index, value) {
// 	               $('#' + index).val(value);
// 	               console.log(' Index '+index);
// 	               console.log(' Data '+value.carrier_id);
// 	               //$("").html();
// 	           });
// 	       }
// 	   });
//     });
// });
</script>