<div class="row">

    <div class="table-responsive">
        <form method="POST" action="https://naveen.store/shopify_app/product/sync-product" accept-charset="UTF-8" class="form_sync_product">
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
        <input type="hidden" name="product_data" value="1">
        <input type="hidden" name="shop" value="<?php echo e($shop); ?>">
            <br><br>
            <!--label>Remaining Products <strong><?php echo e(@$remain_count_pro); ?></strong></label>
            <input type="submit" class="btn" value="Sync product To Kychng"--->
        </form>
    </div>
    <br><br>
    
    <table id="example1" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Title</th>
                <th>Total Price</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = @$all_order; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $meta_data = json_decode($order->meta_data, true);
            ?>
                <tr>
                    <td class="nr"><?php echo e(@$order->order_id); ?></td>
                    <td><?php echo e(@$meta_data['name']); ?></td>
                    <td><?php echo e(@$meta_data['total_price']); ?></td>
                    <td class="em"><?php echo e(@$meta_data['email']); ?></td>
                    <td><span class="refund">Refund</span></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Order ID</th>
                <th>Order Title</th>
                <th>Total Price</th>
                <th>Email</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>


<script>
$(document).ready(function() {
    $('#example1').DataTable( {
        "pagingType": "full_numbers"
    });

    $('#example1').on('click','.refund', function (event) {
        if (event.target.type !== 'checkbox') {
            event.preventDefault();
            var $row = $(this).closest("tr");
            var $text = $row.find(".nr").text();
            var $email = $row.find(".em").text();
            var $currentURl = $(location).attr('href');
            //alert($text);
            event.preventDefault();
            var formData = {
               orderID: $text,
               email: $email,
               url: $currentURl
            }
            $.ajax({
               type: "POST",
               url: 'https://pay.kachyng.com/swapp/refund',
               data: formData,
               cache: false,
               beforeSend: function() {
                   // $('#loading-image').show();
                   console.log('AB');
               },
               complete: function() {
                   // $('#loading-image').hide();
                    console.log('CD');
               },
               success: function(data) {
                   console.log(data);
               }
            })
        }
    });

} );
</script>