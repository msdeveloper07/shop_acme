<div class="row">
@if (is_array($abandoned_checkouts))
	<div class="table-responsive">
<table class="table table-condensed" id="abundand_cart">
<thead>
<tr>
<th><input  id="checkAll" value="all" type="checkbox"></th>
<th>Users</th>
<th>Products</th>
<th>Updated On</th>
<th>Synced</th>
<th>Status</th>
</tr>
</thead>
<tbody>   
    
	@foreach ($abandoned_checkouts as $abandoned_checkout)
<tr>
		<td><input id="cb_select_{{$abandoned_checkout['customer_id']}}" name="post[]" customer_id="{{$abandoned_checkout['customer_id']}}" order_id="{{$abandoned_checkout['order_id']}}" shop="{{$shop}}"  value="{{$abandoned_checkout['customer_id']}}" type="checkbox"></td>
		<td>{{$abandoned_checkout['cart_user']}}</td>
		<td>{{$abandoned_checkout['cart_products']}}</td>
		<td>{{$abandoned_checkout['created_at']}}</td>
		<td>{{$abandoned_checkout['cart_sync']}}</td>
		<td id="{{$abandoned_checkout['order_id']}}">{{$abandoned_checkout['admin_mail_send']}}</td>
		<td><button class="btn btn-default single_email_send" type="submit" customer_id="{{$abandoned_checkout['customer_id']}}" order_id="{{$abandoned_checkout['order_id']}}" name="{{$abandoned_checkout['cart_user']}}" shop="{{$shop}}" >Send Email</button></td>
</tr>
	@endforeach
</tbody>
</table>

<button id="all_mail_send">Send to Selected Users</button>
</div>
@else
    I don't have any records!
@endif             
</div>
<script>
	
	   $('#checkAll').change(function() {
       if ($(this).is(":checked")) {
           $('tbody tr td input[type="checkbox"]').each(function() {
               $(this).prop('checked', true);
           });
       } else {
           $('tbody tr td input[type="checkbox"]').each(function() {
               $(this).prop('checked', false);
           });
       }
   });

   $("#all_mail_send").click(function() {
       var values = new Array();
       $.each($("input[name='post[]']:checked"), function() {
           var customer_id = $(this).attr("customer_id");
           var name = $(this).attr("name");
           var order_id = $(this).attr("order_id");
           var shop = $(this).attr("shop");
           all_ajax_run(customer_id, order_id, shop, name);
       });
       swal({
           type: 'info',
           html: 'Mail Send Successfully to All Users',
           showCloseButton: true
       });
   });



   $(".single_email_send").click(function() {
       var name = $(this).attr("name");
       var customer_id = $(this).attr("customer_id");
       var order_id = $(this).attr("order_id");
       var shop = $(this).attr("shop");
       var data = ajax_run(customer_id, order_id, shop, name);
       console.log(data);
   });

   function ajax_run(customer_id, order_id, shop, name) {
       $.ajax({
           url: 'https://naveen.store/shopify_app/orders/abandoned_checkouts_email',
           type: 'POST',
           data: {
               'customer_id': customer_id,
               'order_id': order_id,
               'shop': shop
           },
           success: function(response) {
               if (response == 1) {
                   swal({
                       type: 'info',
                       html: 'mail send successfully to:' + name,
                       showCloseButton: true
                   });
                   $('#' + order_id).html('Yes');
               } else {
                   swal({
                       type: 'info',
                       html: 'something went wrong',
                       showCloseButton: true
                   });
                   return '0';
               }
           }
       });
   }

   function all_ajax_run(customer_id, order_id, shop, name) {
       $.ajax({
           url: 'https://naveen.store/shopify_app/orders/abandoned_checkouts_email',
           type: 'POST',
           data: {
               'customer_id': customer_id,
               'order_id': order_id,
               'shop': shop
           },
           success: function(response) {
               if (response == 1) {
                   swal({
                       type: 'info',
                       html: 'mail send successfully to:' + name,
                       showCloseButton: true
                   });
                   $('#' + order_id).html('Yes');
               } else {
                   swal({
                       type: 'info',
                       html: 'Something went wrong',
                       showCloseButton: true
                   });
                   return '0';
               }
           }
       });
   }

</script>