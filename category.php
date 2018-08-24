<?php 
  require_once 'connection/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/slider.php';
  include 'includes/leftbar.php';
  
  if(isset($_GET['cat'])){
      $cat_id = sanitize($_GET['cat']);
  }else{
      $cat_id = '';
  }
  
  $sql = "SELECT * FROM products WHERE categories = '$cat_id'";
  $productQ = $connection->query($sql);
  $category = get_category($cat_id);
?>
<div class="col-md-8">
  <div class="row">
    <h2 class="text-center"><?=$category['child'];?></h2>
    <?php while($product = mysqli_fetch_assoc($productQ)) : ?>
    <div class="col-sm-3 text-center">
      <h4><?php echo $product['productname']; ?></h4>
      <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['productname']; ?>" class="img-thumb">
      <p class="price">Sale Price: £<?php echo $product ['price']; ?></p>
      <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal (<?php echo $product['id']; ?>)">Details</button>
    </div>
    <?php endwhile; ?>
  </div>
</div>


</div>

<?php 
  include 'includes/rightbar.php';
  include 'includes/footer.php';
  
?>

