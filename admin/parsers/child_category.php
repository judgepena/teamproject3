<?php
    $hostname = 'localhost';
    $username = 'judge1991';
    $password = '';
    $databaseName = 'c7154275';
    $connection = mysqli_connect($hostname, $username, $password, $databaseName) or exit("Unable to connect database");
?>
<?php
    $parentID = (int)$_POST['parentID'];
    $selected = $_POST['selected'];
    $childQuery = $connection->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");
    ob_start(); ?>
    <option value=""></option>
    <?php while($child = mysqli_fetch_assoc($childQuery)): ?>
    <option value="<?=$child['id'];?>"<?=(($selected == $child['id'])?' selected':''); ?>><?=$child['category']; ?></option>
    <?php endwhile; ?>
<?php echo ob_get_clean();?>