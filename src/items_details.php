<?php
include("./connection_db/dbconnect.php");
include("./functions/cart_icon.php");
include("./functions/user_profile.php");
include("./functions/product_display.php");
include("./functions/metaTags.php");
// Start a session
session_start();
global $con;

// ADD TO CART ITEMS
if (isset($_POST['add_to_cart'])) {
    // Retrieve and validate form data
    $cart_item_quantity = isset($_POST["quantity"]) ? intval($_POST["quantity"]) : 0;
    $cart_item_color = isset($_POST["select_color"]) ? $_POST["select_color"] : "";
    $item_id_cart = isset($_POST["item_id"]) ? intval($_POST["item_id"]) : 0;

    #
    if ($cart_item_quantity == 0) {
        $cart_item_quantity = 1;
    } else {
        $cart_item_quantity = $cart_item_quantity;
    }

    if (isset($_SESSION['customer_id'])) {
        $customer_id = $_SESSION['customer_id'];

        // Get shopping cart id
        $sql_get_shopping_cart_id = "SELECT shopping_cart_id FROM shopping_cart WHERE customer_id = ?";
        $stmt_get_shopping_cart_id = mysqli_prepare($con, $sql_get_shopping_cart_id);
        mysqli_stmt_bind_param($stmt_get_shopping_cart_id, 'i', $customer_id);
        mysqli_stmt_execute($stmt_get_shopping_cart_id);
        $result1 = mysqli_stmt_get_result($stmt_get_shopping_cart_id);

        if ($row = mysqli_fetch_assoc($result1)) {
            //if already has shopingcart id
            $shopping_cart_id = $row["shopping_cart_id"];
        } else {
            // Insert a new shopping cart for the customer
            $sql_set_shopping_cart_id = "INSERT INTO shopping_cart (customer_id) VALUES (?)";
            $stmt_set_shopping_cart_id = mysqli_prepare($con, $sql_set_shopping_cart_id);
            mysqli_stmt_bind_param($stmt_set_shopping_cart_id, 'i', $customer_id);
            mysqli_stmt_execute($stmt_set_shopping_cart_id);

            // Get shopping cart id
            $sql_get_shopping_cart_id = "SELECT shopping_cart_id FROM shopping_cart WHERE customer_id = ?";
            $stmt_get_shopping_cart_id = mysqli_prepare($con, $sql_get_shopping_cart_id);
            mysqli_stmt_bind_param($stmt_get_shopping_cart_id, 'i', $customer_id);
            mysqli_stmt_execute($stmt_get_shopping_cart_id);
            $result2 = mysqli_stmt_get_result($stmt_get_shopping_cart_id);

            if ($row = mysqli_fetch_assoc($result2)) {
                $shopping_cart_id = $row["shopping_cart_id"];
            }
        }


        // Insert item into cart_item
        $sql_add_cart_item = "INSERT INTO cart_item (cart_item_quntity, item_id, shopping_cart_id, cart_item_color) VALUES (?, ?, ?, ?)";
        $stmt_add_cart_item = mysqli_prepare($con, $sql_add_cart_item);
        mysqli_stmt_bind_param($stmt_add_cart_item, 'iiis', $cart_item_quantity, $item_id_cart, $shopping_cart_id, $cart_item_color);

        // Check if the insertion was successful
        if (mysqli_stmt_execute($stmt_add_cart_item)) {
            header("Location: cart.php");
        } else {
            // Handle the error, you can redirect to an error page or show a message
            echo "Error: " . mysqli_error($con);
        }
    } else {
        header("Location: login.php");
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <?php metaTag(); ?>
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

<body class="bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 w-screen" data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">

    <!-- Navbar -->
    <nav class="flex justify-end lg:justify-between w-screen" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="px-5 xl:px-12 py-4 flex w-full items-center" data-aos="zoom-in">
            <a class="text-2xl font-semibold font-heading text-yellow-200" href="index.php">
                <img class="h-12" src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo" style="position: relative; left: 58px;">
                DDN MOBILE
            </a>
            <!-- Nav Links -->
            <ul class="hidden md:flex px-4 mx-auto font-semibold font-heading space-x-12 text-yellow-100">
                <li><a class="hover:text-pink-200" href="#">Home</a></li>
                <li><a class="hover:text-pink-200" href="#about">About Us</a></li>
                <li><a class="hover:text-pink-200" href="items.php">Display All Items</a></li>
                <li><a class="hover:text-pink-200" href="#contuct_us">Contact Us</a></li>
            </ul>
            <!-- Header Icons -->
            <!-- Header Icons -->
            <div class="hidden md:flex items-end lg:items-center space-x-5" id="navList">
                <?php
                user_icon();
                ?>
            </div>


        </div>
        <!-- Responsive Navbar -->
        <div class="md:hidden relative right-4 flex space-x-2">
            <?php user_icon(); ?>
            <a class="navbar-burger self-center mr-10 md:hidden lg:hidden " href="#" id="burgerBtn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-yellow-200 text-gray-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </a>
        </div>
    </nav>
    <hr class="mx-10 ">
    <!-- Responsive Navbar Links -->
    <div class="hidden z-30 xl:hidden absolute right-4 top-32 rounded-lg m-3 p-2" id="responsiveNav" style="background-color:#0015;">
        <ul class="px-4 font-semibold font-heading space-y-4 text-yellow-100">
            <li><a class="hover:text-pink-200" href="#">Home</a></li>
            <li><a class="hover:text-pink-200" href="#about">About Us</a></li>
            <li><a class="hover:text-pink-200" href="items.php">Display All Items</a></li>
            <li><a class="hover:text-pink-200" href="#contact_us">Contact Us</a></li>
        </ul>
    </div>

    <!--USER PROFILE-->
    <?php
    userProfile();
    // userDetails();
    ?>

    <div>
        <hr class="mx-10 ">
    </div>

    <div class="bottom-0 right-4 mb-4 mr-4 z-10 fixed" id="facebook-icon">
        <div>
            <a title="Follow us on Facebook" href="https://www.facebook.com/profile.php?id=100063510759103&mibextid=LQQJ4d" target="_blank" class="block w-16 h-16 rounded-full transition-all shadow hover:shadow-lg transform hover:scale-110 hover:rotate-12">
                <img class="object-cover object-center w-full h-full rounded-full" src="../img/ICON/facebook_non_bg.png" />
            </a>
        </div>
    </div>

    
    <?php
    #Display selected item details using item_id parameter.
    if (isset($_GET['item_id'])) {
        $item_id = $_GET['item_id'];
        display_item_details($item_id);
    }

    function display_item_details($item_id)
    {
        global $con;
        $sql1 = 'SELECT item_name,item_discription, item_price, item_discount_rate, item_stock_quantity, item_img_url1, brand_id, categories_id,item_img_url2,item_img_url3 FROM items WHERE item_id = ' . $item_id . '';
        $stmt1 = mysqli_prepare($con, $sql1);
        mysqli_stmt_execute($stmt1);
        $result = mysqli_stmt_get_result($stmt1);


        while ($row = mysqli_fetch_assoc($result)) {
            $item_name = $row["item_name"];
            $item_discrip = $row["item_discription"];
            $item_price = $row["item_price"];
            $item_discount_rate = $row["item_discount_rate"];
            $item_stock_quantity = $row["item_stock_quantity"];
            $item_img_url1 = $row["item_img_url1"];
            $brand_id = $row["brand_id"];
            $category = $row["categories_id"];
            $item_img_url2 = $row["item_img_url2"];
            $item_img_url3 = $row["item_img_url3"];

            // SELECT ITEM BRAND NAME
            $sql2 = 'SELECT brand_name FROM brand WHERE brand_id=?';
            $stmt2 = mysqli_prepare($con, $sql2);
            mysqli_stmt_bind_param($stmt2, 'i', $brand_id);
            mysqli_stmt_execute($stmt2);
            $result2 = mysqli_stmt_get_result($stmt2);
            $row2 = mysqli_fetch_assoc($result2);
            $brand_name = $row2['brand_name'];

            // Initialize price variables
            $discount_price = 0; // Initialize to 0
            $origenal_price = ' '; // Initialize to an empty string

            // SET PRICE
            if ($item_discount_rate > 0) {
                $origenal_price = $item_price;
                $discount_price = $item_price - ($item_price * $item_discount_rate / 100);
            } elseif ($item_discount_rate == 0) {
                $origenal_price = ' ';
                $discount_price = $item_price;
            }

            // SET PRODUCT STATUS
            $stockBtnColor = $item_stock_quantity == 0 ? "red" : "green";
            $stockStatus = $item_stock_quantity == 0 ? "OUT OF STOCK" : "INSTOCK";

            // Check if the item is in stock
            $isInStock = $item_stock_quantity > 0;

            echo
            '
                <!--Detailes Display-->
                    <!-- Rest of your form elements -->

                    <section class="overflow-hidden z-10 py-11 font-poppins ">
                        <div class="max-w-6xl z-10 px-4 py-4 mx-auto lg:py-8 md:px-6">
                            <div class="flex flex-wrap -mx-4">
                                <div class="w-full z-10 px-4 md:w-1/2">
                                    <div class="sticky top-0 z-50 z-10 overflow-hidden">
                                            <div class="relative z-10 mb-6 lg:mb-10 lg:h-1/2">
                                                <img id="main" src="../img/item_imgs/' . $item_img_url1 . '" alt=""
                                                    class="object-cover z-10 w-full lg:h-full">
                                            </div>
                                            <div class="flex-wrap hidden md:flex">
                                                <div class="w-1/2 p-2 sm:w-1/4">
                                                    <a href="#"
                                                        class="block border border-blue-300 dark:border-transparent dark:hover:border-blue-300 hover:border-blue-300">
                                                        <img id="1" src="../img/item_imgs/' . $item_img_url1 . '"
                                                            alt="" class="object-cover w-full lg:h-20">
                                                    </a>
                                                </div>
                                                <div class="w-1/2 p-2 sm:w-1/4">
                                                    <a href="#"
                                                        class="block border border-transparent dark:border-transparent dark:hover:border-blue-300 hover:border-blue-300">
                                                        <img id="2" src="../img/item_imgs/' . $item_img_url2 . '" alt="" class="object-cover w-full lg:h-20">
                                                    </a>
                                                </div>
                                                <div class="w-1/2 p-2 sm:w-1/4">
                                                    <a href="#"
                                                        class="block border border-transparent dark:border-transparent dark:hover:border-blue-300 hover:border-blue-300">
                                                        <img id="3" src="../img/item_imgs/' . $item_img_url3 . '" alt="" class="object-cover w-full lg:h-20">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-full px-4 md:w-1/2 ">
                                        <div class="lg:pl-20">
                                            <div class="mb-8 ">
                                                <h2 class="max-w-xl mt-2 mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                                                    ' . $brand_name . ' ' . $item_name . '</h2>
                                                <!-- <div class="flex items-center mb-6">
                                                    <ul class="flex mr-2">
                                                        <li>
                                                            <a href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor"
                                                                    class="w-4 mr-1 text-red-500 dark:text-gray-400 bi bi-star "
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor"
                                                                    class="w-4 mr-1 text-red-500 dark:text-gray-400 bi bi-star "
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor"
                                                                    class="w-4 mr-1 text-red-500 dark:text-gray-400 bi bi-star "
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                                </svg>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    fill="currentColor"
                                                                    class="w-4 mr-1 text-red-500 dark:text-gray-400 bi bi-star "
                                                                    viewBox="0 0 16 16">
                                                                    <path
                                                                        d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                                </svg>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <p class="text-xs dark:text-gray-400 ">(2 customer reviews)</p>
                                                </div> -->

                                                <!--ITEM DISCRIPTION-->
                                                <p class="max-w-md mb-8 text-gray-700 dark:text-gray-400">
                                                    ' . $item_discrip . '
                                                </p>
                                                <!--ITEM PRICE-->
                                                <p class="inline-block mb-8 text-4xl font-bold text-gray-700 dark:text-gray-400">
                                                <!--DISCOUNTED PRICE-->
                                                <span>Rs.' . number_format((float)$discount_price, 2) . '</span>
                                                    <!--ORIGRNAL PRICE-->
                                                    <span class="text-base font-normal text-gray-500 line-through dark:text-gray-400">Rs.' . number_format((float)$origenal_price, 2) . '</span>
                                                </p>
                                                <!--STOCK  STATUS-->
                                                <p class="text-' . $stockBtnColor . '-500 dark:text-' . $stockBtnColor . '-500 " style="color:' . $stockBtnColor . ';"><span id="inStock">' . $item_stock_quantity . '</span> in stock</p>
                                            </div>
                                            <!--ITEM COLORS-->
                                            <div class="flex items-center mb-8">
                                                <h2 class="w-16 mr-6 text-xl font-bold dark:text-gray-400">
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

                while ($row_get_color = mysqli_fetch_assoc($result_get_color)) {
                    $color_code = $row_get_color['color_code'];
                    echo '
                                                            <li class="#color">
                                                                <span class="block p-1 border-2 border-gray-900 rounded-full transition ease-in duration-300" style="hover:border-color:' . $color_code . ';">
                                                                    <p class="block w-3 h-3 bg-' . $color_code . '-600 rounded-full"
                                                                    style="background-color:' . $color_code . ';"></p>
                                                                </span>
                                                            </li>';
                }
            }

            echo '
                                                    </ul>
                                                </div>
                                            </div>
                                            <!-- SELECT COLOR -->
                                            <div>
                                            <div class="relative inline-flex ">
                                                <svg class="w-2 h-2 absolute top-0 right-0 m-4 pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 412 232"><path d="M206 171.144L42.678 7.822c-9.763-9.763-25.592-9.763-35.355 0-9.763 9.764-9.763 25.592 0 35.355l181 181c4.88 4.882 11.279 7.323 17.677 7.323s12.796-2.441 17.678-7.322l181-181c9.763-9.764 9.763-25.592 0-35.355-9.763-9.763-25.592-9.763-35.355 0L206 171.144z" fill="#648299" fill-rule="nonzero"/></svg>
                                                <form method="post" action="';
            echo $_SERVER['PHP_SELF'];
            echo '">
                                                <select id="select_color" name="select_color" class="border border-gray-300 rounded-full text-white h-10 pl-5 pr-10 hover:border-rose-900 focus:outline-none appearance-none bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900">
                                                    <option value="0" class="text-black">Choose a color</option>';
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

                while ($row_get_color = mysqli_fetch_assoc($result_get_color)) {
                    $color_code = $row_get_color['color_code'];
                    echo '
                                                                <option value="' . $color_code . '" class="text-black">' . $color_code . '</option>
                                                            ';
                }
            }

            echo '        
                                                </select>
                                                
                                            </div> 
                                            <p style="color:red;"><span class="text-sm ml-4" id="ErrorMsg" name="ErrorMsg">giboiubhjjkn</span></p>
                                            </div>
                                            <!-- <div class="flex items-center mb-8">
                                                <h2 class="w-16 text-xl font-bold dark:text-gray-400">
                                                    Size:</h2>
                                                <div class="flex flex-wrap -mx-2 -mb-2">
                                                    <button
                                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 dark:border-gray-400 hover:text-blue-600 dark:hover:border-gray-300 dark:text-gray-400">XL
                                                    </button>
                                                    <button
                                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 hover:text-blue-600 dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400">S
                                                    </button>
                                                    <button
                                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 hover:text-blue-600 dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400">M
                                                    </button>
                                                    <button
                                                        class="py-1 mb-2 mr-1 border w-11 hover:border-blue-400 hover:text-blue-600 dark:border-gray-400 dark:hover:border-gray-300 dark:text-gray-400">XS
                                                    </button>
                                                </div>
                                            </div>-->

                                            <!--Quantity Select-->
                                            <div class="w-32 mb-8">
                                                <label for=""
                                                    class="w-full text-xl font-semibold text-gray-700 dark:text-gray-400">Quantity</label>
                                                <div class="relative flex flex-row w-full h-10 mt-4 bg-transparent rounded-lg">
                                                    <button type="button" id="decrement" class="w-20 h-full text-white  rounded-l outline-none cursor-pointer bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700">
                                                        <span class="m-auto text-2xl font-thin">-</span>
                                                    </button>
                                                    
                                                    <input id="quantity" name="quantity" type="number"
                                                        class="flex items-center w-full font-semibold text-center textwhite placeholder-gray-100 bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:outline-none text-md hover:text-black"
                                                        placeholder="1" min="1" max="' . $item_stock_quantity . '">
                                                    
                                                        <button type="button" id="increment" class="w-20 h-full text-white rounded-r outline-none cursor-pointer bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700">
                                                            <span class="m-auto text-2xl font-thin">+</span>
                                                        </button>
                                                </div>
                                            </div>

                                            <!--BUTTONS-->
                                            <div class="flex flex-wrap items-center -mx-4 ">
                                                <div class="w-full px-4 mb-4 lg:w-1/2 lg:mb-0">
                                                
                                                <input type="hidden" name="item_id" id="item_id" value="';
            echo '' . $item_id . '';
            echo '">
                                                    <button type="submit" 
                                                        class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6" ' . (!$isInStock ? ' disabled' : '') . ' id="add_to_cart" name="add_to_cart">
                                                        Add to Cart
                                                    </button>
                                                </form>
                                                </div>
                                                <div class="w-full px-4 mb-4 lg:mb-0 lg:w-1/2">
                                        
                                                <a href="oder_checkOut.php?item_id='. $item_id .'" id="buy_now_link">
                                                    <button type="button"
                                                        class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6" ' . (!$isInStock ? ' disabled' : '') . ' id="buy_now" name="buy_now">
                                                        Buy Now
                                                    </button>
                                                </a>
                                                </div>
                                            </div>
                                        </div>
                                 </div>
                            </div>
                        </div>
                    </section>
                ';
        }
    }
    ?>

    <hr class="mx-14 sm:mx-24 md:mx-28 lg:mx-32 xl:mx-36">
    <!-----------FOOTER----------->
    <?php include("./functions/footer.php") ?>



    <!-- //VALIDATE SELECT COLOR//-->
    <script>
        var selectColor = document.getElementById('select_color');
        var buyNowLink = document.getElementById('buy_now_link');

        selectColor.addEventListener('change', function() {
            // Get the selected color
            var selectedColor = selectColor.value;

            // Update the "Buy Now" link with the selected color
            updateBuyNowLink(selectedColor);
        });

        document.getElementById('buy_now').addEventListener('click', function(event) {
            var quantity = document.getElementById('quantity').value;
            var selectedColor = selectColor.value;

            // Update the "Buy Now" link with the selected color
            updateBuyNowLink(selectedColor);

            if (quantity === '') {
                quantity = 1;
            } else {
                quantity = parseInt(quantity);
            }

            // Check if the quantity parameter is already present in the URL
            var url = buyNowLink.href;
            var quantityParam = '&quantity=' + quantity;

            if (url.includes('&quantity=')) {
                // Quantity parameter is already present, so replace it
                url = url.replace(/&quantity=\d+/, quantityParam);
            } else {
                // Quantity parameter is not present, so add it
                url = url + quantityParam;
            }

            // Update the href attribute with the modified URL
            buyNowLink.href = url;
        });

        function updateBuyNowLink(selectedColor) {
            var item_id = <?php echo $item_id; ?>; // Assuming you have PHP variable for item_id
            var url = 'oder_checkOut.php?item_id=' + item_id;

            if (selectedColor) {
                url += '&item_color=' + selectedColor;
            }

            // Update the href attribute with the modified URL
            buyNowLink.href = url;
        }

        // Display error msg if not select a color
        const colorSelect = document.querySelector("#select_color");
        const ErrorMsg = document.querySelector("#ErrorMsg");
        const add_to_cart_btn = document.querySelector("#add_to_cart");
        ErrorMsg.textContent = '';
        const buy_now_btn = document.querySelector("#buy_now");

        add_to_cart_btn.addEventListener('click', function(event) {
            if (colorSelect.value === '0') {
                ErrorMsg.textContent = 'Please select a color.';
                colorSelect.focus();
                event.preventDefault(); // Prevent form submission
            } else {
                ErrorMsg.textContent = ''; // Clear any previous error message
            }

            // location.reload(); // Reload the current page (PHP file) after the condition
        });

        buy_now_btn.addEventListener('click', function(event) {
            if (colorSelect.value === '0') {
                ErrorMsg.textContent = 'Please select a color.';
                colorSelect.focus();
                event.preventDefault(); // Prevent form submission
            } else {
                ErrorMsg.textContent = ''; // Clear any previous error message
            }
        });

        //INCREMENT QUANTITY//-->

        const decrementButton = document.getElementById('decrement');
        const incrementButton = document.getElementById('increment');
        const quantityInput = document.getElementById('quantity');
        const inStockElement = document.getElementById('inStock');
        let stockQuantity = parseInt(inStockElement.textContent);

        decrementButton.addEventListener('click', function() {
            // Decrease the quantity by 1, but do not allow values less than 1
            if (quantityInput.value > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        });

        incrementButton.addEventListener('click', function() {
            // Increase the quantity by 1
            if (quantityInput.value < stockQuantity) {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            }
        });


        // SHOW IMG WHEN CLICK//-->

        // Get references to the main image and the clickable images
        const mainImage = document.getElementById('main');
        const clickableImages = document.querySelectorAll('img[id^="1"], img[id^="2"], img[id^="3"], img[id^="4"]');

        // Add a click event listener to each clickable image
        clickableImages.forEach(image => {
            image.addEventListener('click', function() {
                // Set the source of the main image to the clicked image's source
                mainImage.src = this.src;
            });
        });
    </script>

    <script>
        // JavaScript to toggle responsive navigation links on small screens
        document.getElementById('burgerBtn').addEventListener('click', function() {
            document.getElementById('responsiveNav').classList.toggle('hidden');
        });

        function viewProfilCard() {
            var userProfile = document.getElementById('userProfile');
            userProfile.classList.toggle('hidden');
        }
    </script>

    <script src="../JS/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1500,
        })
    </script>
</body>

</html>