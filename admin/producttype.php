<?php
    require_once '../connection/connection.php';
    if(!is_logged_in()){
       login_error_redirect();
    }
    include 'includes/head.php';
    include 'includes/navigation.php';
    
    $sql = "SELECT * FROM producttype ORDER BY product_type";
    $results = $connection->query($sql);
    $errors = array();
    
    //Edit Product
    if(isset($_GET['edit']) && !empty($_GET['edit'])){
        $edit_id = (int)$_GET['edit'];
        $edit_id = sanitize($edit_id);
        $sql2 = "SELECT * FROM producttype WHERE product_type_num = '$edit_id'";
        $edit_result = $connection->query($sql2);
        $eProd = mysqli_fetch_assoc($edit_result);
    }
    
    //Delete product
    if(isset($_GET['delete']) && !empty($_GET['delete'])){
        $delete_id = (int)$_GET['delete'];
        $delete_id = sanitize($delete_id);
        $sql = "DELETE FROM `producttype` WHERE product_type_num = '$delete_id'";
        $connection->query($sql);
        header('Location: producttype.php');
    }
    
    //IF ADD FORM IS SUBMITTED
    if(isset($_POST['add_submit'])){
        $product_type = $_POST['product_type'];
        //Check if Product is blank
        if($_POST['product_type'] == ''){
            $errors[] .= 'Please Enter Product!';
        }
        //Check if Product exists in database
        $sql = "SELECT * FROM producttype WHERE product_type = '$product_type'";
        if(isset($_GET['edit'])){
            $sql = "SELECT * FROM producttype WHERE product_type = '$product_type' AND product_type_num != 'edit_id'";
        }
        $result = $connection->query($sql);
        $count = mysqli_num_rows($result);
        if ($count > 0){
            $errors[] .= $product_type. ' is already exists. Please Enter New Product';
        }
        
        //Display errors
        if(!empty($errors)){
            echo display_errors($errors);
        }else{
            //Add Product to Databas
            $sql = "INSERT INTO producttype (product_type) VALUES ('$product_type')";
            if(isset($_GET['edit'])){
                $sql = "UPDATE producttype SET product_type = '$product_type' WHERE product_type_num = '$edit_id'";
            }
            $connection->query($sql);
            header('Location: producttype.php');
        }
        
    }
?>



<h2 class="text-center">Product</h2><hr>
<!-- Product Form -->
<div class="text-center">
    <form class="form-inline" action="producttype.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
        <div class="form-group">
            <?php 
            $product_value = '';
            if(isset($_GET['edit'])){
                $product_value = $eProd['product_type'];
            }else{
                if(isset($_POST['product_type'])){
                    $product_value = sanitize($_POST['product_type']);
                }
            }
            ?>
            <label for="product_type"><?=((isset($_GET['edit']))?'Edit':'Add A'); ?> Product: </label>
            <input type="text" name="product_type" id="product_type" class="form-control" value="<?=$product_value; ?>">
            <?php if(isset($_GET['edit'])): ?>
            <a href="producttype.php" class="btn btn-default">Cancel</a>
            <?php endif; ?>
            <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add'); ?> Product" class="btn btn-success">
        </div><hr>
    </form>
</div>
<table class="table table-bordered table-striped table-auto" style="width:auto; margin:0 auto;">
    <thead>
        <th></th><th>Product Type</th><th></th>
    </thead>
        <tbody>
            <?php while($producttype = mysqli_fetch_assoc($results)) : ?>
            <tr>
                <td><a href="producttype.php?edit=<?php echo $producttype['product_type_num']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
                <td><?php echo $producttype['product_type']; ?></td>
                <td><a href="producttype.php?delete=<?php echo $producttype['product_type_num']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
</table>
<?php include 'includes/footer.php';