<?php
include("./connection_db/dbconnect.php");

// DISPLAY ALL ITEMS
function display_all_products(){
    global $con;
    $sql1 = 'SELECT item_id, item_name, item_price, item_discount_rate, item_stock_quantity, item_img_url1, brand_id, categories_id FROM items ORDER BY rand() LIMIT 0,24';
    $stmt1 = mysqli_prepare($con, $sql1);
    mysqli_stmt_execute($stmt1);
    $result = mysqli_stmt_get_result($stmt1);

    while ($row = mysqli_fetch_assoc($result)) {
        $item_id = $row["item_id"];
        $item_name = $row["item_name"];
        $item_price = $row["item_price"];
        $item_discount_rate = $row["item_discount_rate"];
        $item_stock_quantity = $row["item_stock_quantity"];
        $item_img_url1 = $row["item_img_url1"];
        $brand_id = $row["brand_id"];
    
        // SELECT ITEM BRAND NAME
        $sql2 = 'SELECT brand_name FROM brand WHERE brand_id=?';
        $stmt2 = mysqli_prepare($con, $sql2);
        mysqli_stmt_bind_param($stmt2, 'i', $brand_id);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $row2 = mysqli_fetch_assoc($result2);
        $brand_name = $row2['brand_name'];
    
        // SET PRODUCT STATUS
        $stockBtnColor = $item_stock_quantity == 0 ? "red" : "green";
        $stockStatus = $item_stock_quantity == 0 ? "OUT OF STOCK" : "INSTOCK";
    
        // Check if the item is in stock
        $isInStock = $item_stock_quantity > 0;
    
        // Display product details
        echo '<div class="flex justify-center items-center outline-none focus:outline-none bg-no-repeat bg-center"
                id="modal-id"
                data-aos="fade-zoom-in-up"
                data-aos-easing="ease-in-back"
                data-aos-delay="800"
                data-aos-offset="0">
            <div class="relative min-h-screen flex flex-col items-center justify-center">
                <div class="max-w-md w-full bg-gray-900 shadow-lg rounded-xl p-6">
                    <div class="flex flex-col">
                        <div class="product-image">
                            <img src="../img/item_imgs/' . $item_img_url1 . '" alt="Product Image" class="w-full object-fill rounded-2xl"
                                style="height: 365px; width: 300px;">
                        </div>
                        <div class="product-details mt-6">
                            <h2 class="product-title text-lg cursor-pointer text-gray-200 hover:text-purple-500 truncate">
                                ' . $brand_name . ' ' . $item_name . '
                            </h2>
                            <div class="product-status mt-4">
                                <div class="status-label flex items-cdenter bg-'.$stockBtnColor.'-600 text-white text-xs px-2 py-1 ml-3 rounded-lg"
                                style="background-color: '.$stockBtnColor.';">
                                    '.$stockStatus.'
                                </div>
                            </div>
                            <div class="product-price text-xl text-yellow-200 font-semibold mt-2">
                                Rs.'.$item_price.'.00
                            </div>
                        </div>
                    </div>
                    <div class="product-actions text-sm text-gray-600 flex space-x-2 font-medium justify-start mt-4">
                        <button class="add-to-cart-btn transition ease-in duration-300 inline-flex items-center text-sm font-medium mb-2 md:mb-0 bg-purple-500 px-5 py-2 hover:shadow-lg tracking-wider text-white rounded-full hover:bg-purple-600" '.(!$isInStock ? ' disabled' : '').'>
                        <a href="items_details.php?item_id='.$item_id.'">
                            <span>Add to Cart</span>
                        </a>    
                        </button>
                        <a href="items_details.php?item_id='.$item_id.'">
                        <button class="wishlist-btn transition ease-in duration-300 bg-gray-700 hover:bg-gray-800 border hover:border-gray-500 border-gray-700 hover:text-white hover:shadow-lg text-gray-400 rounded-full w-9 h-9 text-center p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>';
    }
    
}

// FOR CATEGORY BUTTON
function category_section() {
    global $con;
    $sql = 'SELECT categories_id, categories_name FROM categories';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $categories_id = $row["categories_id"];
        $categories_name = $row["categories_name"];

        // Add an "onclick" attribute to the button to call the display_items_related_to_category function
        echo '
            <a href="items.php?category='.$categories_id.'">
            <button class="w-screen py-3 bg-gradient-to-r bg-transparent hover:from-pink-700 hover:to-yellow-800 rounded-xl text-yellow-200 hover:text-red-100"
            style="width: 100%;"> 
            ' . $categories_name . '
            </button>
            </a>
        ';
    }
}

// FOR DISPLAY RELATED CATEGORY
    function display_items_related_to_category($category_id) {
        global $con;
    
            // Use the $category_id parameter in your SQL query
            $sql = 'SELECT item_id, item_name, item_price, item_discount_rate, item_stock_quantity, item_img_url1, brand_id, categories_id FROM items WHERE categories_id = ?';
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 's', $category_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $num_of_rows = mysqli_num_rows($result);
            if($num_of_rows == 0){
                echo'<div class="w-screen text-2xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-red-600 item-center text-center   mt-56 mb-56">
                        <h2 style="color:red;"> No stock for this category.. </h2>
                     </div>';
            }
            

        while ($row = mysqli_fetch_assoc($result)) {
            $item_id = $row["item_id"];
            $item_name = $row["item_name"];
            $item_price = $row["item_price"];
            $item_discount_rate = $row["item_discount_rate"];
            $item_stock_quantity = $row["item_stock_quantity"];
            $item_img_url1 = $row["item_img_url1"];
            $brand_id = $row["brand_id"];
    
                // SELECT ITEM BRAND NAME
                $sql2 = 'SELECT brand_name FROM brand WHERE brand_id=?';
                $stmt2 = mysqli_prepare($con, $sql2);
                mysqli_stmt_bind_param($stmt2, 's', $brand_id);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);
                $row2 = mysqli_fetch_assoc($result2);
                $brand_name = $row2['brand_name'];

                // SET PRODUCT STATUS
                $stockBtnColor = $item_stock_quantity == 0 ? "red" : "green";
                $stockStatus = $item_stock_quantity == 0 ? "OUT OF STOCK" : "INSTOCK";

                // Check if the item is in stock
                $isInStock = $item_stock_quantity > 0;

                // Display product details
                echo '<div class="flex justify-center items-center outline-none focus:outline-none bg-no-repeat bg-center"
                    id="modal-id"
                    data-aos="fade-zoom-in"
                    data-aos-easing="ease-in-back"
                    data-aos-delay="800"
                    data-aos-offset="0">
                    <div class="relative min-h-screen flex flex-col items-center justify-center">
                        <div class="max-w-md w-full bg-gray-900 shadow-lg rounded-xl p-6">
                            <div class="flex flex-col">
                                <div class="product-image">
                                    <img src="../img/item_imgs/' . $item_img_url1 . '" alt="Product Image" class="w-full object-fill rounded-2xl"
                                        style="height: 365px; width: 300px;">
                                </div>
                                <div class="product-details mt-6">
                                    <h2 class="product-title text-lg cursor-pointer text-gray-200 hover:text-purple-500 truncate">
                                        ' . $brand_name . ' ' . $item_name . '
                                    </h2>
                                    <div class="product-status mt-4">
                                        <div class="status-label flex items-center bg-'.$stockBtnColor.'-600 text-white text-xs px-2 py-1 ml-3 rounded-lg"
                                        style="background-color: '.$stockBtnColor.';">
                                            '.$stockStatus.'
                                        </div>
                                    </div>
                                    <div class="product-price text-xl text-yellow-200 font-semibold mt-2">
                                        Rs.'.$item_price.'.00
                                    </div>
                                </div>
                            </div>
                            <div class="product-actions text-sm text-gray-600 flex space-x-2 font-medium justify-start mt-4">
                                <button class="add-to-cart-btn transition ease-in duration-300 inline-flex items-center text-sm font-medium mb-2 md:mb-0 bg-purple-500 px-5 py-2 hover:shadow-lg tracking-wider text-white rounded-full hover:bg-purple-600"'.(!$isInStock ? ' disabled' : '').'>
                                <a href="items_details.php?item_id='.$item_id.'">
                                    <span>Add to Cart</span>
                                </a> 
                                </button>
                                
                                <a href="items_details.php?item_id='.$item_id.'">
                                    <button class="wishlist-btn transition ease-in duration-300 bg-gray-700 hover:bg-gray-800 border hover:border-gray-500 border-gray-700 hover:text-white hover:shadow-lg text-gray-400 rounded-full w-9 h-9 text-center p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>';    
            
        }

    }
    // FOR  BRAND BUTTON
function brand_section(){
    global $con;
    $sql = 'SELECT brand_id, brand_name FROM brand';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $brand_id = $row["brand_id"];
        $brand_name = $row["brand_name"];
        echo '
        
            <a href="items.php?brand='.$brand_id.'">
                <button class="w-screen py-3 bg-gradient-to-r bg-transparent hover:from-pink-700 hover:to-yellow-800 rounded-xl text-yellow-200 hover:text-red-100"
                style="width: 100%;">
                    ' . $brand_name . '
                </button>
            </a>
        
        ';
    }
}


// FOR DISPLAY RELATED BRAND
function display_items_related_to_brand($brand_id){
    global $con;
   
            // Use the $brand_id parameter in your SQL query
            $sql = 'SELECT item_id, item_name, item_price, item_discount_rate, item_stock_quantity, item_img_url1, brand_id, categories_id FROM items WHERE brand_id = ?';
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, 's', $brand_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $num_of_rows = mysqli_num_rows($result);
            if($num_of_rows == 0){
                echo'<div class="w-screen text-2xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-red-600 item-center text-center   mt-56 mb-56">
                        <h2 style="color:red;"> No stock for this brand.. </h2>
                     </div>';
            }
            

        while ($row = mysqli_fetch_assoc($result)) {
            $item_id = $row["item_id"];
            $item_name = $row["item_name"];
            $item_price = $row["item_price"];
            $item_discount_rate = $row["item_discount_rate"];
            $item_stock_quantity = $row["item_stock_quantity"];
            $item_img_url1 = $row["item_img_url1"];
            $brand_id = $row["brand_id"];
    
                // SELECT ITEM BRAND NAME
                $sql2 = 'SELECT brand_name FROM brand WHERE brand_id=?';
                $stmt2 = mysqli_prepare($con, $sql2);
                mysqli_stmt_bind_param($stmt2, 's', $brand_id);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);
                $row2 = mysqli_fetch_assoc($result2);
                $brand_name = $row2['brand_name'];

                // SET PRODUCT STATUS
                $stockBtnColor = $item_stock_quantity == 0 ? "red" : "green";
                $stockStatus = $item_stock_quantity == 0 ? "OUT OF STOCK" : "INSTOCK";

                // Check if the item is in stock
                $isInStock = $item_stock_quantity > 0;

                // Display product details
                echo '<div class="flex justify-center items-center outline-none focus:outline-none bg-no-repeat bg-center"
                    id="modal-id"
                    data-aos="fade-zoom-in"
                    data-aos-easing="ease-in-back"
                    data-aos-delay="800"
                    data-aos-offset="0">
                    <div class="relative min-h-screen flex flex-col items-center justify-center">
                        <div class="max-w-md w-full bg-gray-900 shadow-lg rounded-xl p-6">
                            <div class="flex flex-col">
                                <div class="product-image">
                                    <img src="../img/item_imgs/' . $item_img_url1 . '" alt="Product Image" class="w-full object-fill rounded-2xl"
                                        style="height: 365px; width: 300px;">
                                </div>
                                <div class="product-details mt-6">
                                    <h2 class="product-title text-lg cursor-pointer text-gray-200 hover:text-purple-500 truncate">
                                        ' . $brand_name . ' ' . $item_name . '
                                    </h2>
                                    <div class="product-status mt-4">
                                        <div class="status-label flex items-center bg-'.$stockBtnColor.'-600 text-white text-xs px-2 py-1 ml-3 rounded-lg"
                                        style="background-color: '.$stockBtnColor.';">
                                            '.$stockStatus.'
                                        </div>
                                    </div>
                                    <div class="product-price text-xl text-yellow-200 font-semibold mt-2">
                                        Rs.'.$item_price.'.00
                                    </div>
                                </div>
                            </div>
                            <div class="product-actions text-sm text-gray-600 flex space-x-2 font-medium justify-start mt-4">
                                <button class="add-to-cart-btn transition ease-in duration-300 inline-flex items-center text-sm font-medium mb-2 md:mb-0 bg-purple-500 px-5 py-2 hover:shadow-lg tracking-wider text-white rounded-full hover:bg-purple-600"'.(!$isInStock ? ' disabled' : '').'>
                                <a href="items_details.php?item_id='.$item_id.'">
                                    <span>Add to Cart</span>
                                </a> 
                                </button>
                                <a href="items_details.php?item_id='.$item_id.'">
                        <button class="wishlist-btn transition ease-in duration-300 bg-gray-700 hover:bg-gray-800 border hover:border-gray-500 border-gray-700 hover:text-white hover:shadow-lg text-gray-400 rounded-full w-9 h-9 text-center p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        </a>
                            </div>
                        </div>
                    </div>
                </div>';    
            
        }

}

// FOR SEARCH BUTTON
function searchbtn() {
    echo '
            <form method="get" action="#">
                <div class="flex flex-auto">
                    <div>
                        <input type="search" placeholder="search" id="txtSearch" name="txtSearch"
                            class="text-yellow-300 font-thin justify-center px-6 py-2 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 placeholder-opacity-5 place-content-center peer-placeholder-shown:">
                    </div>
                    <div>
                        <div
                            style="width:5%; margin: 0 auto; text-align:center; font-family: helvetica, arial, sans-serif;">
                            <button type="submit" id="btnSearch" class="z-10" name="btnSearch">
                                <!-- Heres the inline SVG icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                                    class="bi bi-search top-4 relative" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"
                                        fill="#feee86">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
    ';
}

    // FOR SEARCH ITEMS
    function display_search_items($search_txt){
        global $con;
    
        // Use the $brand_id parameter in your SQL query
        //$search_param = '%'. $search_txt .'%'; // Add % around the search term
        $sql = 'SELECT item_id, item_name, item_price, item_discount_rate, item_stock_quantity, item_img_url1, brand_id, categories_id FROM items WHERE item_keywords LIKE "%' . $search_txt . '%"';
        $stmt = mysqli_prepare($con, $sql);
       // mysqli_stmt_bind_param($stmt, 's', $search_param); // Bind the parameter
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        $num_of_rows = mysqli_num_rows($result);
        if ($num_of_rows == 0) {
            echo '<div class="w-screen text-2xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-red-600 text-center mt-56 mb-56">
                <h2 style="color:red;">No items found for the given search term.</h2>
            </div>';
        } else {
            // Loop through and display the search results
            while ($row = mysqli_fetch_assoc($result)) {
                $item_id = $row["item_id"];
                $item_name = $row["item_name"];
                $item_price = $row["item_price"];
                $item_discount_rate = $row["item_discount_rate"];
                $item_stock_quantity = $row["item_stock_quantity"];
                $item_img_url1 = $row["item_img_url1"];
                $brand_id = $row["brand_id"];
        
                    // SELECT ITEM BRAND NAME
                    $sql2 = 'SELECT brand_name FROM brand WHERE brand_id=?';
                    $stmt2 = mysqli_prepare($con, $sql2);
                    mysqli_stmt_bind_param($stmt2, 's', $brand_id);
                    mysqli_stmt_execute($stmt2);
                    $result2 = mysqli_stmt_get_result($stmt2);
                    $row2 = mysqli_fetch_assoc($result2);
                    $brand_name = $row2['brand_name'];
    
                    // SET PRODUCT STATUS
                    $stockBtnColor = $item_stock_quantity == 0 ? "red" : "green";
                    $stockStatus = $item_stock_quantity == 0 ? "OUT OF STOCK" : "INSTOCK";
    
                    // Check if the item is in stock
                    $isInStock = $item_stock_quantity > 0;
    
                    // Display product details
                    echo '<div class="flex justify-center items-center outline-none focus:outline-none bg-no-repeat bg-center"
                        id="modal-id"
                        data-aos="fade-zoom-in"
                        data-aos-easing="ease-in-back"
                        data-aos-delay="800"
                        data-aos-offset="0">
                        <div class="relative min-h-screen flex flex-col items-center justify-center">
                            <div class="max-w-md w-full bg-gray-900 shadow-lg rounded-xl p-6">
                                <div class="flex flex-col">
                                    <div class="product-image">
                                        <img src="../img/item_imgs/' . $item_img_url1 . '" alt="Product Image" class="w-full object-fill rounded-2xl"
                                            style="height: 365px; width: 300px;">
                                    </div>
                                    <div class="product-details mt-6">
                                        <h2 class="product-title text-lg cursor-pointer text-gray-200 hover:text-purple-500 truncate">
                                            ' . $brand_name . ' ' . $item_name . '
                                        </h2>
                                        <div class="product-status mt-4">
                                            <div class="status-label flex items-center bg-'.$stockBtnColor.'-600 text-white text-xs px-2 py-1 ml-3 rounded-lg"
                                            style="background-color: '.$stockBtnColor.';">
                                                '.$stockStatus.'
                                            </div>
                                        </div>
                                        <div class="product-price text-xl text-yellow-200 font-semibold mt-2">
                                            Rs.'.$item_price.'.00
                                        </div>
                                    </div>
                                </div>
                                <div class="product-actions text-sm text-gray-600 flex space-x-2 font-medium justify-start mt-4">
                                    <button class="add-to-cart-btn transition ease-in duration-300 inline-flex items-center text-sm font-medium mb-2 md:mb-0 bg-purple-500 px-5 py-2 hover:shadow-lg tracking-wider text-white rounded-full hover:bg-purple-600"'.(!$isInStock ? ' disabled' : '').'>
                                    <a href="items_details.php?item_id='.$item_id.'">
                                        <span>Add to Cart</span>
                                    </a> 
                                    </button>
                                    <a href="items_details.php?item_id='.$item_id.'">
                        <button class="wishlist-btn transition ease-in duration-300 bg-gray-700 hover:bg-gray-800 border hover:border-gray-500 border-gray-700 hover:text-white hover:shadow-lg text-gray-400 rounded-full w-9 h-9 text-center p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                                    </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>';    
                
            }
        }
    }

    

    // CALLING FUNCTION FOR items.php
    function display_selected_items() {
        if (isset($_GET['category'])) {
            $category_id = $_GET['category'];
                display_items_related_to_category($category_id);

        } elseif (isset($_GET['brand'])) {
            $brand_id = $_GET['brand'];
                display_items_related_to_brand($brand_id);
                
        } elseif (isset($_GET['txtSearch'])) {
            $search_txt = $_GET['txtSearch'];
            display_search_items($search_txt);

        }else {
            display_all_products();
        }
    }
?>
