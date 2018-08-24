<?php
    require_once '../connection/connection.php';
    if(!is_logged_in()){
       login_error_redirect();
    }
    include 'includes/head.php';
    include 'includes/navigation.php';
    
    //Delete Product
    if(isset($_GET['delete'])){
        $id = sanitize($_GET['delete']);
        $connection->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
        header('Location: products.php');
    }
    
    $dbpath = '';
    if(isset($_GET['add']) || isset($_GET['edit'])){
    $product_query = $connection->query("SELECT * FROM producttype ORDER BY product_type");
    $parentQuery = $connection->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
    $productname = ((isset($_POST['productname']) && $_POST['productname'] != '')?sanitize($_POST['productname']):'');
    $product_type = ((isset($_POST['product_type']) && !empty($_POST['product_type']))?sanitize($_POST['product_type']):'');
    $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
    $category = ((isset($_POST['child'])) && !empty($_POST['child'])?sanitize($_POST['child']):'');
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
    $quantity = ((isset($_POST['quantity']) && $_POST['quantity'] != '')?sanitize($_POST['quantity']):'');
    $saved_image = '';

    if(isset($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $productResults = $connection->query("SELECT * FROM products WHERE id = '$edit_id'");
        $product = mysqli_fetch_assoc($productResults);
        if(isset($_GET['delete_image'])){
            $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];echo $image_url;
           unlink($image_url);
           $connection->query("UPDATE products SET image = '' WHERE id = '$edit_id'");
           header('Location: products.php?edi='.$edit_id);
        }
        $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
        $productname = ((isset($_POST['productname']) && !empty($_POST['productname']))?sanitize($_POST['productname']):$product['productname']);
        $product_type = ((isset($_POST['product_type']) && !empty($_POST['product_type']))?sanitize($_POST['product_type']):$product['product_type']);
        $parentsQ = $connection->query("SELECT * FROM categories WHERE id = '$category'");
        $parentResult = mysqli_fetch_assoc($parentsQ);
        $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
        $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
        $list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$product['list_price']);
        $description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);
        $quantity = ((isset($_POST['quantity']) && !empty($_POST['quantity']))?sanitize($_POST['quantity']):$product['stock_available']);
        $saved_image = (($product['image'] != '')?$product['image']:'');
        $dbpath = $saved_image;
    }
    
    if($_POST){
        
        $categories = $_POST['child'];
        $price = $_POST['price'];
        $list_price = $_POST['list_price'];
        $quantity = $_POST['quantity'];
        $description = $_POST['description'];
        $dbpath = '';
        
         $required = array('productname', 'product_type', 'price', 'parent', 'child', 'quantity');
         foreach($required as $field){
             if($_POST[$field] == ''){
                 $errors[] = 'All Fields With Star are required';
                 break;
             }
         }
         if(!empty($_FILES)){
            var_dump($_FILES);
            $photo = $_FILES['photo'];
            $name = $photo['name'];
            $nameArray = explode('.',$name);
            $fileName = $nameArray[0];
            $fileExt = $nameArray[1];
            $mime = explode('/',$photo['type']);
            $mimeType = $mime[0];
            $mimeExt = $photo[1];
            $tmpLoc = $photo ['tmp_name'];
            $fileSize = $photo['size'];
            $allowed = array('png','jpg','jpeg','gif');
            $uploadName = md5(microtime()).'.'.$fileExt;
            $uploadPath = BASEURL.'../images/products/'.$uploadName;
            $dbpath = '/c7154275/products/images/'.$uploadName;
            if ($mimeType != 'image'){
                $errors[] = 'The File must be an image.';
            } 
            if (!in_array($fileExt, $allowed)){
                $errors[] = 'The photo must be png, jpg, jpeg, or gif';
            }
            if ($fileSize > 15000000){
                $errors[] = 'The File must be under 15mb';
            }
            if ($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
                $errors[] = 'File extension does not match the file.';
            }
            
         }
         if(!empty($errors)){
             echo display_errors($errors);
         }else{
             //Upload file and insert into database
             move_uploaded_file($tmpLoc,$uploadPath);
             $insertSQL = "INSERT INTO products (`productname`, `product_type`, `categories`, `price`, `list_price`, `stock_available`, `description`, `image`) 
             VALUES ('$productname','$product_type','$category','$price','$list_price','$quantity','$description','$dbpath')";
             if (isset($_GET['edit'])){
                 $insertSQL = "UPDATE products SET productname = '$productname', product_type = '$product_type', categories = '$category', price = '$price',
                 list_price = '$list_price', stock_available = '$quantity', description = '$description', image = '$dbpath' WHERE id = '$edit_id'";
             }
             $connection->query($insertSql);
             header('Location: products.php');
             
             
         }
    }
        
    
  
    ?>
    <h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New')?> Product</h2><hr>
    <form action="products.php?<?=((isset($_GET['edit']))?'edit=' .$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
        <div class="form-group col-md-3">
            <label for="productname">Product name*:</label>
            <input type="text" name="productname" class="form-control" id="productname" value="<?=$productname;?>">
        </div>
        <div class="form-group col-md-3">
            <label for="product_type">Product Type*:</label>
            <select class="form-control" id="product_type" name="product_type">
                <option value=""<?=(($product_type == '' )?' selected':''); ?>></option>
                <?php while($p = mysqli_fetch_assoc($product_query)) :?>
                <option value="<?=$p['product_type_num'];?>"<?=(($product_type == $p['product_type_num'])?' selected':'');?>><?=$p['product_type'] ;?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="parent">Parent Category*:</label>
            <select class="form-control" id="parent" name="parent">
                <option value=""<?=(($parent == '')?' selected':'');?></option>
                <?php while($pa = mysqli_fetch_assoc($parentQuery)) : ?>
                <option value="<?=$pa['id'];?>"<?=(($parent == $pa['id'])?' selected':'');?>><?=$pa['category'];?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="child">Child Category*:</label>
            <select id="child" name="child" class="form-control">
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="price">Price*:</label>
            <input type="text" id="price" name="price" class="form-control" value="<?=$price;?>">
        </div>
         <div class="form-group col-md-3">
            <label for="price">List Price:</label>
            <input type="text" id="list_price" name="list_price" class="form-control" value="<?=$list_price;?>">
        </div>
        <!-- Change to TEXT -->
        <div class="form-group col-md-3">
            <label for="quantity">Stock Quantity*:</label>
            <input type="text" id="quantity" name="quantity" class="form-control" value="<?=$quantity;?>">
        </div>
        <div class="form-group col-md-3">
            <?php if($saved_image != ''): ?>
            <div class="saved-image"><img scr="<?=$saved_image;?>" alt="saved image" /><br>
            <a href="products.php?delete_image=1&edit=<?=$edit_id; ?>" class="text-danger">Delete Image</a>
            </div>
            <?php else: ?>
            <label for="photo">Product Photo:</label>
            <input type="file" name="photo" id="photo" class="form-control">
            <?php endif; ?>
        </div>
        <div class="form-group col-md-6">
            <label for="description">Description*:</label>
            <textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>
        </div>
        <div class="form-group pull-right">
            <a href="products.php" class="btn btn-default">Cancel</a>
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Product" class="btn btn-success">
        </div><div class="clearfix"></div>
    </form>
    
    <?php    
    }else{
        
    
    
    $sql = "SELECT * FROM products WHERE deleted = 0";
    $presults = $connection->query($sql);
    if(isset($_GET['featured'])){
        $id = (int)$_GET['id'];
        $featured = (int)$_GET['featured'];
        $featuredSql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
        $connection->query($featuredSql);
        header('Location: products.php');
    }
    ?>

<h2 class="text-center">Products</h2>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn" style="margin-top: -35px;">Add Product</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
    <thead><th></th><th>Product</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
    <tbody>
        <?php while($product = mysqli_fetch_assoc($presults)):
            $childID = $product['categories'];
            $catSql = "SELECT * FROM categories WHERE id = '$childID'";
            $result = $connection->query($catSql);
            $child = mysqli_fetch_assoc($result);
            $parentID = $child['parent'];
            $pSql = "SELECT * FROM categories WHERE id = '$parentID'";
            $presult = $connection->query($pSql);
            $parent = mysqli_fetch_assoc($presult);
            $category = $parent['category'].' ~ '.$child['category'];
        
        ?>
        <tr>
            <td>
                <a href="products.php?edit=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="products.php?delete=<?=$product['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
            </td>
            <td><?=$product['productname'];?></td>
            <td><?=money($product['price']);?></td>
            <td><?=$category;?></td>
            <td><a href="products.php?featured=<?=(($product['featured'] == 0 )?'1':'0');?>&id=<?=$product['id'];?>" class="btn btn-xs btn-default">
                <span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span>
                </a>&nbsp <?=(($product['featured'] == 1)?'Featured Product':'');?></td>
            <td>0</td>
            
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
    
    
    
<?php } include 'includes/footer.php'; ?>

<script>
    jQuery('document').ready(function(){
        get_child_options('<?=$category;?>');
    });
</script>