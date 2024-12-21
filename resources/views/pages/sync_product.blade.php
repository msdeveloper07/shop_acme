<?php
 // $url = URL::to("/");
 // print_r($url);

// echo"<br>";
 // $url2 = url('/');
 // print_r($url2);
?>
<div class="row">

	<div class="table-responsive">
		<form method="POST" action="https://naveen.store/shopify_app/product/sync-product" accept-charset="UTF-8" class="form_sync_product">
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" name="product_data" value="1">
		<input type="hidden" name="shop" value="{{$shop}}">
			<br><br>
			<!--label>Remaining Products <strong>{{@$remain_count_pro}}</strong></label>
			<input type="submit" class="btn" value="Sync product To Kychng"--->
		</form>
	</div>
	<br><br>
	
	<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Title</th>
                <th>Product Price</th>
                <th>Product Image</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
			@foreach(@$pro_result_array->products as $product)
				<?php $product_status = DB::table('sync_products_record')->where('product_id',$product->id)->value('status');	?>
				<tr>
					<td>{{$product->id}}</td>
					<td>{{$product->title}}</td>
					<td>{{@$product->variants[0]->price}}</td>
					<td><img src="{{@$product->images[0]->src}}" style="width:50px;  height:50px;"></td>
					<td>{{@$product_status}}</td>
				</tr>
			@endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Product ID</th>
                <th>Product Title</th>
                <th>Product Price</th>
                <th>Product Image</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>

</div>





<script>
	$(document).ready(function() {
		$('#example').DataTable( {
			"pagingType": "full_numbers"
		} );
	} );


   $(document).ready(function() {
       $('.form_sync_product').on('submit', function(event) {
           event.preventDefault();
           var formData = {
               product_data: $('input[name=product_data]').val(),
               shop: $('input[name=shop]').val(),
               _token: $('input[name=_token]').val()

           }
           $.ajax({
               type: "POST",
               url: $(this).attr('action'),
               data: formData,
               cache: false,
               beforeSend: function() {
                   $('#loading-image').show();
               },
               complete: function() {
                   $('#loading-image').hide();
               },
               success: function(data) {
                   console.log(data);
               }
           })
       });
   });


</script>