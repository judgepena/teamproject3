<?php
require_once '../connection/init.php';
$id = $_POST ['id'];
$id = (int)$id;
$sql1 = "SELECT * FROM products WHERE id = '$id'";
$result = $connection->query($sql1);
$product = mysqli_fetch_assoc($result);

$product_type_num_id = $product['product_type'];
$sql2 = "SELECT product_type FROM producttype WHERE product_type_num = '$product_type_num_id'";
$producttype_query = $connection->query($sql2);
$product_type = mysqli_fetch_assoc($producttype_query);


?>

<?php ob_start(); ?>
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
      <button class="close" type="button" onclick="closeModal()" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title text-center"><?php echo $product['productname']; ?></h4>
    </div>
    <div class="modal-body">
      <div class="container-fluid">
        <div class="row">
          <span id="modal_errors" class="bg-danger"></span>
          <div class="col-sm-6">
            <div class="center-block">
              <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['productname']; ?>" class="details img-responsive">
            </div>
          </div>
            <div class="col-sm-6">
              <h4>Details</h4>
              <p><?php echo $product['description']; ?></p>
              <hr>
              <p>Price: £<?= $product['price']; ?></p>
              <p>Product Type: <?php echo $product_type['product_type']; ?></p>
              <form action="add_cart.php" method="post" id="add_product_form">
                <input type="hidden" name="product_id" value="<?=$id;?>">
                <input type="hidden" name="available" id="available" value="">
                <div class="form-group">
                  <div class="col-xs-3">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                  </div><br><br>
                  <p>Available: <?php echo $product['stock_available']; ?></p>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-default" onclick="closeModal()">Close</button>
      <button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add To Cart</button>
    </div>
    </div>
  </div>
</div>
<script>
  function closeModal(){
    jQuery('#details-modal').modal('hide');
    setTimeout(function(){
      jQuery('#details-modal').remove();
      jQuery('.modal-backdrop').remove();
    },500);
  }
</script>
<?php echo ob_get_clean(); ?>