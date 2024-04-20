<?php
include("./connection_db/dbconnect.php");

//insert new cetogery
if (isset($_POST["btnCetogery"])) {
    $new_cetogery = $_POST["txtCatogery"];

    $sql_insert_cetogery = "INSERT INTO categories (categories_name) VALUES (?)";
    $stmt_insert_cetogery = mysqli_prepare($con, $sql_insert_cetogery);

    if ($stmt_insert_cetogery) {
        mysqli_stmt_bind_param($stmt_insert_cetogery, "s", $new_cetogery);
        mysqli_stmt_execute($stmt_insert_cetogery);
        mysqli_stmt_close($stmt_insert_cetogery);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
}

//insert new brand
if (isset($_POST["btn_brand"])) {
    $new_brand = $_POST["txtBrand"];

    $sql_insert_brand = "INSERT INTO brand (brand_name) VALUES (?)";
    $stmt_insert_brand = mysqli_prepare($con, $sql_insert_brand);

    if ($stmt_insert_brand) {
        mysqli_stmt_bind_param($stmt_insert_brand, "s", $new_brand);
        mysqli_stmt_execute($stmt_insert_brand);
        mysqli_stmt_close($stmt_insert_brand);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
}

//insert new color
if (isset($_POST["btnColor"])) {
    $new_color = $_POST["txtColor"];

    $sql_insert_color = "INSERT INTO colors (color_code) VALUES (?)";
    $stmt_insert_color = mysqli_prepare($con, $sql_insert_color);

    if ($stmt_insert_color) {
        mysqli_stmt_bind_param($stmt_insert_color, "s", $new_color);
        mysqli_stmt_execute($stmt_insert_color); 
        mysqli_stmt_close($stmt_insert_color);
    } else {
        echo "Error preparing statement: " . mysqli_error($con);
    }
}



//display items call function
function displayItems() {
    if (isset($_GET['txtSearch'])) {
        $search_txt = $_GET['txtSearch'];
        display_search_items($search_txt);
    } else {
        display_all_products();
    }
}

//display search items
function display_search_items($search_txt){
    global $con;

    $sql_search_item = 'SELECT item_id, item_name, item_price, item_discount_rate, item_stock_quantity, item_img_url1, categories_id, brand_name FROM items INNER JOIN brand ON items.brand_id = brand.brand_id WHERE item_keywords LIKE "%' . $search_txt . '%"';
    $stmt_search_item = mysqli_prepare($con, $sql_search_item);
   // mysqli_stmt_bind_param($stmt, 's', $search_param); // Bind the parameter
    mysqli_stmt_execute($stmt_search_item);
    $result_search_item = mysqli_stmt_get_result($stmt_search_item);

    $num_of_rows = mysqli_num_rows($result_search_item);
    if ($num_of_rows == 0) {
        echo '<div class="w-screen text-2xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl text-red-600 text-center mt-56 mb-56">
            <h2 style="color:red;">No items found for the given search term.</h2>
        </div>';
    } else {
        while ($row = mysqli_fetch_assoc($result_search_item)) {
            $item_id = $row["item_id"];
            $item_name = $row["item_name"];
            $item_price = $row["item_price"];
            $item_discount_rate = $row["item_discount_rate"];
            $item_stock_quantity = $row["item_stock_quantity"];
            $item_img_url1 = $row["item_img_url1"];
            $brand_name = $row["brand_name"];
    
            echo '
            <div class="flex items-center hover:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-400 via-rose-950 to-slate-700 -mx-8 px-6 py-5">
                <div class="flex w-2/5 content-center justify-center">
                    <!-- product -->
                    <div class="w-20">
                        <img class="h-24" src="../img/item_imgs/' . $item_img_url1 . '" alt="">
                    </div>
                    <div class="flex flex-col justify-between ml-4 flex-grow">
                        <span class="font-bold text-sm text-amber-100 dark:text-cyan-50">' . $item_name . '</span>
                        <span class="text-green-500 text-xs">' . $brand_name . '</span>
                        <!--ITEM COLORS-->
                    <div class="flex items-center mb-8">
                        <h2 class="w-16 mr-6 text-sm font-thin dark:text-gray-400">
                            Colors:</h2>
                        <div class="flex flex-wrap -mx-2 -mb-2" id="colorSelection">
                            <ul class="flex flex-row justify-center items-center space-x-2">';
        
                            $sql_get_color_id = 'SELECT color_id FROM item_color WHERE item_id=?';
                            $stmt_get_color_id = mysqli_prepare($con, $sql_get_color_id);
                            mysqli_stmt_bind_param($stmt_get_color_id, 'i', $item_id);
                            mysqli_stmt_execute($stmt_get_color_id);
                            $result_get_color_id = mysqli_stmt_get_result($stmt_get_color_id);

                            while ($row_get_color_id = mysqli_fetch_assoc($result_get_color_id)) {
                                $color_id = $row_get_color_id['color_id'];
                                $sql_get_color = 'SELECT color_code FROM colors WHERE color_id=?';
                                $stmt_get_color = mysqli_prepare($con, $sql_get_color);
                                mysqli_stmt_bind_param($stmt_get_color, 'i', $color_id);
                                mysqli_stmt_execute($stmt_get_color);
                                $result_get_color = mysqli_stmt_get_result($stmt_get_color);

                                while($row_get_color = mysqli_fetch_assoc($result_get_color)){
                                    $color_code = $row_get_color['color_code'];
                                    echo '
                                    <li class="#color">
                                        <span class="block p-1 border-2 border-gray-900 rounded-full transition ease-in duration-300" style="hover:border-color:'.$color_code.';">
                                            <p class="block w-2 h-2 bg-'.$color_code.'-600 rounded-full"
                                            style="background-color:'.$color_code.';"></p>
                                        </span>
                                    </li>';
                                }
                            }

                            echo'
                            </ul>
                        </div>
                    </div>
                        <a href="remove_item.php?item_id='.$item_id.'"
                            class="font-semibold hover:text-red-700 text-red-400 text-xs">Remove</a>
                    </div>
                </div>
                <div class="flex justify-center w-1/5">
                    <p class="text-amber-100">' . $item_stock_quantity . '</p>
                </div>
                <span class="text-center w-1/5 font-semibold text-sm text-amber-100">Rs.' . number_format((float) $item_price, 2) . '</span>
                <span class="text-center w-1/5 font-semibold text-sm text-amber-100"><a href="edit_items.php?item_id='.$item_id.'"
                        class="text-yellow-300 hover:text-blue-500">Edit Item Details</a></span>
            </div>            
            ';
        }
    }

}

//display all products
function display_all_products() {
    global $con;
    $sql_select_items = 'SELECT items.item_id, item_name, item_price, item_discount_rate, item_stock_quantity, item_img_url1, items.brand_id, categories_id, brand_name FROM items INNER JOIN brand ON items.brand_id = brand.brand_id';
    $stmt_select_items = mysqli_prepare($con, $sql_select_items);
    mysqli_stmt_execute($stmt_select_items);
    $result_select_items = mysqli_stmt_get_result($stmt_select_items);

    while ($row = mysqli_fetch_assoc($result_select_items)) {
        $item_id = $row["item_id"];
        $item_name = $row["item_name"];
        $item_price = $row["item_price"];
        $item_discount_rate = $row["item_discount_rate"];
        $item_stock_quantity = $row["item_stock_quantity"];
        $item_img_url1 = $row["item_img_url1"];
        $brand_name = $row["brand_name"];

        echo '
        <div class="flex items-center hover:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-400 via-rose-950 to-slate-700 -mx-8 px-6 py-5">
            <div class="flex w-2/5 content-center justify-center">
                <!-- product -->
                <div class="w-20">
                    <img class="h-24" src="../img/item_imgs/' . $item_img_url1 . '" alt="">
                </div>
                <div class="flex flex-col justify-between ml-4 flex-grow">
                    <span class="font-bold text-sm text-amber-100 dark:text-cyan-50">' . $item_name . '</span>
                    <span class="text-green-500 text-sm">' . $brand_name . '</span>
                    <!--ITEM COLORS-->
                    <div class="flex items-center mb-8">
                        <h2 class="w-16 mr-6 text-sm font-thin dark:text-gray-400">
                            Colors:</h2>
                        <div class="flex flex-wrap -mx-2 -mb-2" id="colorSelection">
                            <ul class="flex flex-row justify-center items-center space-x-2">';
        
                            $sql_get_color_id = 'SELECT color_id FROM item_color WHERE item_id=?';
                            $stmt_get_color_id = mysqli_prepare($con, $sql_get_color_id);
                            mysqli_stmt_bind_param($stmt_get_color_id, 'i', $item_id);
                            mysqli_stmt_execute($stmt_get_color_id);
                            $result_get_color_id = mysqli_stmt_get_result($stmt_get_color_id);

                            while ($row_get_color_id = mysqli_fetch_assoc($result_get_color_id)) {
                                $color_id = $row_get_color_id['color_id'];
                                $sql_get_color = 'SELECT color_code FROM colors WHERE color_id=?';
                                $stmt_get_color = mysqli_prepare($con, $sql_get_color);
                                mysqli_stmt_bind_param($stmt_get_color, 'i', $color_id);
                                mysqli_stmt_execute($stmt_get_color);
                                $result_get_color = mysqli_stmt_get_result($stmt_get_color);

                                while($row_get_color = mysqli_fetch_assoc($result_get_color)){
                                    $color_code = $row_get_color['color_code'];
                                    echo '
                                    <li class="#color grid grid-cols-1">
                                        <span class=" p-1 border-1 md:border-2 border-gray-900 rounded-full transition ease-in duration-300" style="hover:border-color:'.$color_code.';">
                                            <p class=" w-3 h-3   bg-'.$color_code.'-600 rounded-full"
                                            style="background-color:'.$color_code.';"></p>
                                        </span>
                                    </li>';
                                }
                            }

                            echo'
                            </ul>
                        </div>
                    </div>
                    <a href="remove_item.php?item_id='.$item_id.'"
                        class="font-semibold hover:text-red-700 text-red-400 text-xs">Delete Item</a>
                </div>
            </div>
            <div class="flex justify-center w-1/5">
                <p class="text-amber-100">' . $item_stock_quantity . '</p>
            </div>
            <span class="text-center w-1/5 font-semibold text-sm text-amber-100">Rs.' . number_format((float) $item_price, 2) . '</span>
            <span class="text-center w-1/5 font-semibold text-sm text-amber-100"><a href="edit_items.php?item_id='.$item_id.'"
                    class="text-yellow-300 hover:text-blue-500">Edit Item Details</a></span>
        </div>            
        ';
    }
}

//display all cetogeries
function displayCetogery() {
    global $con;

    $sql_select_cetogery = "SELECT * FROM categories";
    $stmt_select_cetogery = mysqli_prepare($con, $sql_select_cetogery);
    mysqli_stmt_execute($stmt_select_cetogery);
    $result_select_cetogery = mysqli_stmt_get_result($stmt_select_cetogery);

    while ($row = mysqli_fetch_assoc($result_select_cetogery)) {
        $categories_id = $row["categories_id"];
        $categories_name = $row["categories_name"];

        echo '
                <div class="bg-gradient-to-br from-green-500 via-purple-900 to-purple-600 rounded-lg p-4 hover:shadow-md">
                    <h2 class="text-white text-center text-xl font-semibold">'.$categories_name.'</h2>
                    <a href="remove_item.php?cetogery_id='.$categories_id.'" class="block text-end text-red-400 hover:text-red-700 mt-2">Delete</a>
                </div>
    
            ';
    }

}

//display all brands
function displayBrand() {
    global $con;

    $sql_select_brand = "SELECT * FROM brand";
    $stmt_select_brand = mysqli_prepare($con, $sql_select_brand);
    mysqli_stmt_execute($stmt_select_brand);
    $result_select_brand = mysqli_stmt_get_result($stmt_select_brand);

    while ($row = mysqli_fetch_assoc($result_select_brand)) {
        $brand_id = $row["brand_id"];
        $brand_name = $row["brand_name"];

        echo '
                <div
                    class="bg-gradient-to-br from-green-500 via-purple-900 to-purple-600 rounded-lg p-4 hover:shadow-md">
                    <h2 class="text-white text-center text-xl font-semibold">'.$brand_name.'</h2>
                    <a href="remove_item.php?brand_id='.$brand_id.'" class="block text-end text-red-400 hover:text-red-700 mt-2">Delete</a>
                </div>
    
            ';
    }

}

//display all colors
function displayColor() {
    global $con;

    $sql_select_color = "SELECT * FROM colors";
    $stmt_select_color = mysqli_prepare($con, $sql_select_color);
    mysqli_stmt_execute($stmt_select_color);
    $result_select_color = mysqli_stmt_get_result($stmt_select_color);

    while ($row = mysqli_fetch_assoc($result_select_color)) {
        $color_id = $row["color_id"];
        $color_code = $row["color_code"];

        echo '
                <div
                    class="bg-gradient-to-br from-green-500 via-purple-900 to-purple-600 rounded-lg p-4 hover:shadow-md">
                    <h2 class="text-center text-xl text-'.$color_code.'-500 font-semibold">'.$color_code.'</h2>
                    <a href="remove_item.php?color_id='.$color_id.'" class="block text-end text-red-400 hover:text-red-700 mt-2">Delete</a>
                </div>
    
            ';
    }

}
?>


<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDN MOBILE | SMART PHONES | WATCHES</title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* Customize the scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
            /* Set the width of the scrollbar */
        }

        /* Customize the scrollbar track */
        ::-webkit-scrollbar-track {
            /* Set the color of the track */
            background: black;
        }

        /* Customize the scrollbar thumb */
        ::-webkit-scrollbar-thumb {
            background: rgb(93, 4, 25);
            /* Set the color of the thumb */
            border-radius: 6px;
            /* Round the corners of the thumb */
        }

        /* Customize the scrollbar thumb on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #70031c;
            /* Change thumb color on hover */
        }

        /* Customize the scrollbar button (top and bottom buttons) */
        ::-webkit-scrollbar-button {
            display: none;
            /* Hide the scroll buttons */
        }
    </style>
</head>

<body
    class="bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black min-h-screen">

    <section id="blurBody">
        <!-- Navbar -->
        <nav>
            <div
                class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto pt-10 px-2 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
                <div class="relative bottom-4">
                    <a class="text-lg md:xl lg:text-2xl font-semibold font-heading text-yellow-200" href="#">
                        <img class="h-8 md:h-10 lg:h-12 relative left-10 lg:left-16"
                            src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo">
                        DDN MOBILE
                    </a>
                </div>

                <div class=" justify-center content-center">
                    <div>
                        <h1
                            class="self-center text-2xl md:text-3xl lg:text-4xl font-semibold whitespace-nowrap dark:text-white">
                            Admin Dashboard</h1>
                    </div>
                </div>

                <div>
                    <a href="index.php">
                        <span class="self-center text-1xl font-semibold whitespace-nowrap dark:text-white">Home Page</span>
                    </a>
                </div>
            </div>
        </nav>
        <hr class="mx-10 mb-4">

        <!--SEARCH BAR-->
        <div class="px-20 justify-end flex">
            <form method="get" action="#">
                <div class="flex flex-auto">
                    <div>
                        <input type="search" placeholder="search" id="txtSearch" name="txtSearch"
                            class="text-yellow-300 font-thin justify-center px-6 py-2 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 placeholder-opacity-5 place-content-center peer-placeholder-shown:">
                    </div>
                    <div>
                        <div
                            style="width:5%; margin: 0 auto; text-align:center; font-family: helvetica, arial, sans-serif;">
                            <button type="submit" id="btnSearch" name="btnSearch">
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
        
        </div>
        <hr class="mx-10 mt-4">

        <!--BUTTON PANEL-->
        <div class="container mx-auto mt-10 ">
            <div class="grid shadow-md my-10  grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                <div class="justify-center grid grid-row overflow-y-auto" style="max-height: 350px;">
                    <!--ADD NEW ITEM-->
                    <a href="add_new_item.php">
                        <button
                            class="w-52 block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold p-5 py-3 mt-6">Add
                            New Item</button>
                    </a>
                    <!--ADD NEW CATEGORY-->
                    <button onclick="showCetogery()"
                        class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">Add
                        New Category
                    </button>

                    <!--ADD NEW BRAND-->
                    <button onclick="showBrand()"
                        class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">
                        Add New Brand
                    </button>

                    <!--ADD NEW COLOR-->
                    <button onclick="showColor()"
                        class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">Add
                        new Color
                    </button>

                    <!--VIEW OREDRS LIST-->
                    <a href="view_order_list.php?shopping_cart_id='.$shopping_cart_id.'">
                        <button
                            class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">View
                            Order List</button>
                    </a>
                </div>
                <!--ITEM DISPLY-->
                <div class="px-10 py-10 container col-span-1 md:col-span-2 lg:col-span-3 xl:col-span-3">
                    <div class="flex justify-center border-b pb-8 text-yellow-200">
                        <h1 class="font-semibold text-xl">Item List</h1>
                    </div>
                    <div class="flex mt-10 mb-5 font-semibold text-white text-xs uppercase">
                        <h3 class="w-2/5 text-center">Item Details</h3>
                        <h3 class="w-1/5 text-center">Quantity</h3>
                        <h3 class="w-1/5 text-center">Price</h3>
                        <h3 class="w-1/5 text-center"></h3>
                    </div>
                    <hr>

                    <div>
                        <?php displayItems() ?>
                    </div>

                </div>
            </div>
        </div>

        <hr class="mx-0 mt-4 sm:mx-3 md:mx-2 lg:mx-2 xl:mx-2">
       <!--lishens-->
       <div class="w-full max-w-screen-xl mx-auto md:py-0">
           <div class="sm:flex sm:items-center sm:justify-between">
               <div>
                   <a href="index.php" class="flex items-center mb-4 sm:mb-0">
                       <img src="../img/DDN_LOGO/Origibal_non_bg.png" class="" alt="DDN Logo"
                           style="width: 30%;">
                   </a>
               </div>
               <div>
                   <span class="block text-sm text-yellow-300 sm:text-start dark:text-yellow-200">© 2023 <a
                           href="index.php" class="hover:text-blue-400">DDN MOBILE</a>. All Rights Reserved.</span>
               </div>
              
           </div>
       </div>
    </section>


    <!--ADD NEW CATEGORY-->
    <div id="addNewCategory" style="display:none"
        class="text-yellow-100 fixed w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-28 left-14 md:left-20 lg:left-36 xl:left-44 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 rounded-lg">
        <div class="container mx-auto px-8 py-6">
            <h1 class="text-center text-yellow-300 text-2xl font-semibold mb-3">Add New Category</h1>
            <hr class="my-3 border-t-2 border-yellow-300">

            <!-- Category Form -->
            <form action="#" method="post" class="flex items-center justify-between mb-4" name="frmCatogery"
                id="frmCatogery">
                <input type="text" name="txtCatogery" id="txtCatogery" placeholder="Enter New Category"
                    class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                    autofocus autocomplete>
                <button type="submit" id="btnCetogery" name="btnCetogery"
                    class="px-6 py-3 text-sm font-medium text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Add
                    Category</button>
            </form>


            <hr class="my-3 border-t-2 border-yellow-300">

            <!-- Category List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto"
                style="max-height: 300px;">
                <!-- Category Card -->
                <?php displayCetogery() ?>
            </div>
            <div class="flex justify-end pt-10">
                <button type="button" onclick="hideCetogery();"
                    class="px-6 py-3 text-sm font-medium text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                    Cancel
                </button>
            </div>
        </div>

    </div>

    <!-- ADD NEW BRAND -->
    <div id="addNewBrand" style="display: none"
        class="text-yellow-100 fixed w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-28 left-14 md:left-20 lg:left-36 xl:left-44 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 rounded-lg overflow-y-auto">
        <div class="container mx-auto px-8 py-6">
            <h1 class="text-center text-yellow-300 text-2xl font-semibold mb-3">Add New Brand</h1>
            <hr class="my-3 border-t-2 border-yellow-300">

            <!-- Brand Form -->
            <form action="#" method="post" class="flex items-center justify-between mb-4" name="frmBrand" id="frmBrand">
                <input type="text" name="txtBrand" id="txtBrand" placeholder="Enter New Brand"
                    class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                    autofocus autocomplete>
                <button type="submit" id="btn_brand" name="btn_brand"
                    class="px-6 py-3 text-sm font-medium text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Add
                    Brand
                </button>
            </form>

            <hr class="my-3 border-t-2 border-yellow-300">

            <!-- Brand List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto"
                style="max-height: 300px;">
                <!-- Brand Card -->
                <?php displayBrand() ?>
            </div>
            <div class="flex justify-end pt-10">
                <button type="button" onclick="hideBrand();"
                    class="px-6 py-3 text-sm font-medium text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    <!--ADD NEW COIOR-->
    <div id="addNewColor" style="display:none"
        class="text-yellow-100 fixed w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-28 left-14 md:left-20 lg:left-36 xl:left-44 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 rounded-lg">
        <div class="container mx-auto px-8 py-6">
            <h1 class="text-center text-yellow-300 text-2xl font-semibold mb-3">Add New Color</h1>
            <hr class="my-3 border-t-2 border-yellow-300">

            <!-- Color Form -->
            <form action="#" method="post" class="flex items-center justify-between mb-4" name="frmColor" id="frmColor">
                <input type="text" name="txtColor" id="txtColor" placeholder="Enter New Color"
                    class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                    autofocus autocomplete>
                <button type="submit" id="btnColor" name="btnColor"
                    class="px-6 py-3 text-sm font-medium text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Add
                    Color</button>
            </form>

            <hr class="my-3 border-t-2 border-yellow-300">

            <!-- Color List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto"
                style="max-height: 300px;">
                <!-- Color Card -->
                <?php displayColor() ?>
            </div>
            <div class="flex justify-end pt-10">
                <button type="button" onclick="hideColor()"
                    class="px-6 py-3 text-sm font-medium text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">
                    Cancel
                </button>
            </div>
        </div>

    </div>

    <script>
        const blurBody = document.getElementById("blurBody");
        const addNewColor = document.getElementById("addNewColor");
        const addNewBrand = document.getElementById("addNewBrand");
        const addNewCategory = document.getElementById("addNewCategory");

        function showCetogery() {
            addNewCategory.style.display = "block";
            blurBody.classList.add('blur');
        }

        function hideCetogery() {
            addNewCategory.style.display = "none";
            blurBody.classList.remove('blur');
        }

        function showBrand() {
            addNewBrand.style.display = "block";
            blurBody.classList.add('blur');
        }

        function hideBrand() {
            addNewBrand.style.display = "none";
            blurBody.classList.remove('blur');
        }

        function showColor() {
            addNewColor.style.display = "block";
            blurBody.classList.add('blur');
        }

        function hideColor() {
            addNewColor.style.display = "none";
            blurBody.classList.remove('blur');
        }
    </script>

</body>

</html>