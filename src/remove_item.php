<?php
include("./connection_db/dbconnect.php");

if (isset($_GET['cart_item_id'])) {
    $cart_item_id = $_GET['cart_item_id'];

   
        // Item belongs to the current user, so it can be removed
        $sql_remove_item = "DELETE FROM cart_item WHERE cart_item_id = ?";
        $stmt_remove_item = mysqli_prepare($con, $sql_remove_item);
        mysqli_stmt_bind_param($stmt_remove_item, 'i', $cart_item_id);
        mysqli_stmt_execute($stmt_remove_item);

        // Redirect back to the shopping cart page
        header("Location: cart.php");
        exit;
}

if (isset($_GET["item_id"])) {
    $item_id = $_GET["item_id"];
    // Ensure $item_id is properly sanitized and validated to prevent SQL injection
    $sql_delete_color = "DELETE FROM item_color WHERE item_id = ?";
    $stmt_delete_color = mysqli_prepare($con, $sql_delete_color);
    mysqli_stmt_bind_param($stmt_delete_color, "i", $item_id);
    mysqli_stmt_execute($stmt_delete_color);

    // Assuming item_id is an integer
    $sql_delete_item = "DELETE FROM items WHERE item_id = ?";
    $stmt_delete_item = mysqli_prepare($con, $sql_delete_item);
    mysqli_stmt_bind_param($stmt_delete_item, "i", $item_id);
    mysqli_stmt_execute($stmt_delete_item);

    // Redirect after the deletion
    header("Location: admin_home.php");
    exit;
}

if(isset($_GET["cetogery_id"])) {
    $cetogery_id = $_GET["cetogery_id"];

    $sql_delete_cetogery = "DELETE FROM categories WHERE categories_id = ?";
    $stmt_delete_cetogery = mysqli_prepare($con, $sql_delete_cetogery);
    mysqli_stmt_bind_param($stmt_delete_cetogery, "i", $cetogery_id);
    mysqli_stmt_execute($stmt_delete_cetogery);

    header("Location: admin_home.php");
    exit;
}

if(isset($_GET["brand_id"])) {
    $brand_id = $_GET["brand_id"];

    $sql_delete_brand = "DELETE FROM brand WHERE brand_id = ?";
    $stmt_delete_brand = mysqli_prepare($con, $sql_delete_brand);
    mysqli_stmt_bind_param($stmt_delete_brand, "i", $brand_id);
    mysqli_stmt_execute($stmt_delete_brand);

    header("Location: admin_home.php");
    exit;
}

if(isset($_GET["color_id"])) {
    $colors_id = $_GET["color_id"];

    $sql_delete_colors = "DELETE FROM colors WHERE color_id = ?";
    $stmt_delete_colors = mysqli_prepare($con, $sql_delete_colors);
    mysqli_stmt_bind_param($stmt_delete_colors, "i", $colors_id);
    mysqli_stmt_execute($stmt_delete_colors);

    header("Location: admin_home.php");
    exit;
}
// If the item doesn't exist or doesn't belong to the current user, handle this case as needed.
?>
