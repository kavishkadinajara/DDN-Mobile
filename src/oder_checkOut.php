<?php
include("./connection_db/dbconnect.php");
include("./functions/cart_icon.php");
include("./functions/user_profile.php");
include("./functions/metaTags.php");
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

// INSERT INTO NEW SHIPPING ADDRESS
if (isset($_POST["btn_submit_address"])) {
    $customer_id = $_SESSION['customer_id'];

    $txtReceverName = $_POST["txtReceverName"];
    $txtReceverTele = $_POST["txtReceverTele"];
    $txtReceverAddress = $_POST["txtReceverAddress"];
    $txtCity = $_POST["txtCity"];
    $listProvince = $_POST["listProvince"];
    $txtPostalCode = $_POST["txtPostalCode"];

    $sql_insert_shipping_address = "INSERT INTO shipping_address (city, province, postal_code, recever_full_name, customer_id, shipping_address_tele, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_shipping_address = mysqli_prepare($con, $sql_insert_shipping_address);

    // Check if the prepare statement is successful
    if ($stmt_insert_shipping_address) {
        mysqli_stmt_bind_param($stmt_insert_shipping_address, 'ssssiss', $txtCity, $listProvince, $txtPostalCode, $txtReceverName, $customer_id, $txtReceverTele, $txtReceverAddress);
        $result = mysqli_stmt_execute($stmt_insert_shipping_address);

        if ($result) {
            // Close the database connection
            mysqli_close($con);
            // Redirect to a success page or display a success message
            header("Location: oder_checkOut.php");
            exit;
        } else {
            // Handle the error if execution fails
            echo "Error: " . mysqli_error($con);
        }
    } else {
        // Handle the error if prepare statement fails
        echo "Error: " . mysqli_error($con);
    }
    header("Location: oder_checkOut.php");
    exit;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function display_oder_item_from_cart() {
    global $con;
    $cart_msg = " ";

    //TO DISPLAY CART ORDER ITEM
    if (!isset($_SESSION['customer_id'])) {
        header("Location: login.php");
        exit; // Add an exit to stop executing further code
    } else {
        $customer_id = $_SESSION['customer_id'];
        
        // Select the temp_order record for the current customer
        $sql_select_temp_order1 = "SELECT * FROM temp_order WHERE customer_id = ?";
        $stmt_select_temp_order1 = mysqli_prepare($con, $sql_select_temp_order1);
        mysqli_stmt_bind_param($stmt_select_temp_order1, 'i', $customer_id);
        mysqli_stmt_execute($stmt_select_temp_order1);
        $result_select_temp_order1 = mysqli_stmt_get_result($stmt_select_temp_order1);
        $row = mysqli_fetch_assoc($result_select_temp_order1);
        $shopping_cart_id = $row["shopping_cart_id"];
        $item_id = $row["item_id"];
        $item_color = $row["color"];
        $quantity = intval($row["quantity"]);

        $total_quntity = 0;
        $total_Item_cost = 0;
        $final_total_cost = 0;

        /// DISPLAY SHIPPING CART ORDER ITEMS ///
        if ($shopping_cart_id != null) {
            $sql_get_cart_item_details = "SELECT item_img_url1, item_name, brand_name, cart_item_id, cart_item_color, item_price, cart_item_quntity FROM cart_item CI JOIN items I ON CI.item_id = I.item_id JOIN brand B ON I.brand_id = B.brand_id WHERE shopping_cart_id = ?";
            $stmt_get_cart_item_details = mysqli_prepare($con, $sql_get_cart_item_details);
            mysqli_stmt_bind_param($stmt_get_cart_item_details, 'i', $shopping_cart_id);
            mysqli_stmt_execute($stmt_get_cart_item_details);
            $result2 = mysqli_stmt_get_result($stmt_get_cart_item_details);

            while ($row = mysqli_fetch_assoc($result2)) {
                $item_img_url1 = $row["item_img_url1"];
                $item_name = $row["item_name"];
                $brand_name = $row["brand_name"];
                $cart_item_id = $row["cart_item_id"];
                $cart_item_color = $row["cart_item_color"];
                $item_price = $row["item_price"];
                $cart_item_quntity = $row["cart_item_quntity"];
                $total_price = $cart_item_quntity * $item_price;

                $total_quntity += $cart_item_quntity;
                $total_Item_cost = $total_Item_cost + $total_price;
                $final_total_cost = $total_Item_cost + 600;

                echo '
                    <div class="flex items-center hover:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-400 via-rose-950 to-slate-700 -mx-8 px-6 py-5">
                        <div class="flex w-2/5 content-center">
                            <!-- product -->
                            <div class="w-20">
                                <img class="h-24" src="../img/item_imgs/' . $item_img_url1 . '" alt="">
                            </div>
                            <div class="flex flex-col justify-between ml-4 flex-grow">
                                <span class="font-bold text-sm text-amber-100 dark:text-cyan-50">' . $item_name . '</span>
                                <span class="text-green-500 text-xs">' . $brand_name . '</span>
                                <span class="text-amber-100 text-xs">color : <span class="text-' . $cart_item_color . '-300" style="color:' . $cart_item_color . '">' . $cart_item_color . '</span></span>
                            </div>
                        </div>
                        <div class="flex justify-center w-1/5">
                            <p class="text-amber-100">' . $cart_item_quntity . '</p>
                        </div>
                        <span class="text-center w-1/5 font-semibold text-sm text-amber-100">Rs.' . number_format((float) $item_price, 2) . '</span>
                        <span class="text-center w-1/5 font-semibold text-sm text-amber-100">Rs.' . number_format((float) $total_price, 2) . '</span>
                    </div>
                ';
                
            }


            /// DIRECT ORDER ITEMS ///
        } elseif ($item_id != null) {
            $sql_get_order_item_details = "SELECT item_img_url1, item_name, brand_name, item_price FROM items I JOIN brand B ON I.brand_id = B.brand_id WHERE item_id = ?";
            $stmt_get_order_item_details = mysqli_prepare($con, $sql_get_order_item_details);
            mysqli_stmt_bind_param($stmt_get_order_item_details, 'i', $item_id);
            mysqli_stmt_execute($stmt_get_order_item_details);
            $result2 = mysqli_stmt_get_result($stmt_get_order_item_details);

            while ($row = mysqli_fetch_assoc($result2)) {
                $item_img_url1 = $row["item_img_url1"];
                $item_name = $row["item_name"];
                $brand_name = $row["brand_name"];
                $item_price = $row["item_price"];
                $total_price = $quantity * $item_price;

                $total_quntity += $quantity;
                $total_Item_cost = $total_Item_cost + $total_price;
                $final_total_cost = $total_Item_cost + 600;

                
                echo '
                    <div class="flex items-center hover:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-400 via-rose-950 to-slate-700 -mx-8 px-6 py-5">
                         <div class="flex w-2/5 content-center">
                             <!-- product -->
                            <div class="w-20">
                                <img class="h-24" src="../img/item_imgs/' . $item_img_url1 . '" alt="">
                                    </div>
                                    <div class="flex flex-col justify-between ml-4 flex-grow">
                                        <span class="font-bold text-sm text-amber-100 dark:text-cyan-50">' . $item_name . '</span>
                                        <span class="text-green-500 text-xs">' . $brand_name . '</span>
                                        <span class="text-amber-100 text-xs">color : <span class="text-' . $item_color . '-300" style="color:' . $item_color . '">' . $item_color . '</span></span>
                                    </div>
                                </div>
                                <div class="flex justify-center w-1/5">
                                    <p class="text-amber-100">' . $quantity . '</p>
                                </div>
                                <span class="text-center w-1/5 font-semibold text-xs md:text-sm xl:text-sm text-amber-100">Rs.' . number_format((float) $item_price, 2) . '</span>
                                <span class="text-center w-1/5 font-semibold text-xs md:text-sm xl:text-sm text-amber-100">Rs.' . number_format((float) $total_price, 2) . '</span>
                            </div>
                        ';
                        
            }
        }

        /// GET SHIPPING ADDRESS ID ///
        $selected_shipping_address_id = null;
        if (isset($_POST["btn_confirm_address"])) {
            
            if (isset($_POST["selected_shipping_address"])) { // Add missing if condition
                $selected_shipping_address_id = $_POST["selected_shipping_address"];

                if ($selected_shipping_address_id == null) {
                    $sql_get_first_sai = "SELECT shipping_address_id FROM shipping_address WHERE customer_id = ?";
                    $stmt_get_first_sai = mysqli_prepare($con, $sql_get_first_sai); // Correct the variable name
                    mysqli_stmt_bind_param($stmt_get_first_sai, 'i', $customer_id);
                    mysqli_stmt_execute($stmt_get_first_sai);
                    $result_get_first_sai = mysqli_stmt_get_result($stmt_get_first_sai);
            
                    $row = mysqli_fetch_assoc($result_get_first_sai);
                    $selected_shipping_address_id = $row["shipping_address_id"];
                } else {
                    $selected_shipping_address_id = $selected_shipping_address_id;
                }
            }
        
        }

        echo '
            <a href="items.php" class="flex font-semibold text-indigo-600 text-sm mt-10">
                <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
                    <path
                        d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
                </svg>
                Cancel Order
            </a>
        </div>
        
        <div id="summary" class="px-8 py-10 md:col-span-1 lg:col-span-1 xl:col-span-1">
            <h1 class="font-semibold text-yellow-200 text-xl border-b pb-8">Order Summary</h1>
            <div class="flex justify-between mt-10 mb-5 font-semibold text-sm text-amber-100">
                <span class="uppercase">Items ' . $total_quntity . '</span>
                <span class="">Rs.' . number_format((float) $total_Item_cost, 2) . '</span>
            </div>
            <div class="flex justify-between mt-10 mb-5 font-semibold text-sm text-amber-100">
                <span class="uppercase">Shipping cost</span>
                <span class="">Rs.600.00</span>
            </div>

            <div class="border-t mt-8 text-amber-100">
                <div class="flex font-semibold justify-between py-6 text-sm uppercase">
                    <span>Total cost</span>
                    <span>Rs.' . number_format((float) $final_total_cost, 2) . '</span>
                </div>
            </div>
            <a href="payment.php?shipping_address='.$selected_shipping_address_id.'&total_cost='.$final_total_cost.'">
                <button 
                    class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">Place Order</button>
            </a>
        </div>';
    }
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//TO DISPLAY SHIPPING ADDRESS
function shippingAddress() {
    global $con;

    if (!isset($_SESSION['customer_id'])) {
        header("Location: login.php");
        exit;
    } else {
        $customer_id = $_SESSION['customer_id'];

        if (isset($_POST["btn_confirm_address"])) {
            if (isset($_POST["selected_shipping_address"])) {
                // Radio button is selected, display the selected address
                $selected_shipping_address_id = $_POST["selected_shipping_address"];
                $sql_get_selected_address = "SELECT * FROM shipping_address WHERE shipping_address_id=?";
                $stmt_get_selected_address = mysqli_prepare($con, $sql_get_selected_address);
                mysqli_stmt_bind_param($stmt_get_selected_address, "i", $selected_shipping_address_id);
                mysqli_stmt_execute($stmt_get_selected_address);
                $result_get_selected_address = mysqli_stmt_get_result($stmt_get_selected_address);

                if ($row = mysqli_fetch_assoc($result_get_selected_address)) {
                    $recever_full_name = $row["recever_full_name"];
                    $shipping_address_tele = $row["shipping_address_tele"];
                    $address = $row["address"];

                    // Display the selected address
                    echo '<div class="m-8 text-cyan-100">
                        <p>Deliver to: <span>' . $recever_full_name . '</span></p>
                        <p>Telephone: <span>' . $shipping_address_tele . '</span></p> <br>
                        <p>Delivery address: <span>
                            <address>' . $address . '</address>
                        </span></p>
                        <button id="btn_change_shipping_add" class="mt-4" onclick="showShippingAddressList();">
                            <p class="text-end text-yellow-200 hover:text-blue-500">Change</p>
                        </button>
                    </div>';
                }
            } else {
                // No radio button selected, display an appropriate message
               /* echo '<p class="text-center text-red-700 my-3 py-2">Please select a shipping address...</p>
                <p class="text-center text-yellow-400 my-3 py-3 hover:text-blue-400"> 
                 <button type="button" id="btn_select_shipping_address" onclick="showShippingAddressList();">
                    Click here to select address... 
                 </button>
                </p>';*/
                // Display the address from the database

                ///////// Defalt Address ////////////
                $sql_get_shipping_address = "SELECT * FROM shipping_address WHERE customer_id=?";
                $stmt_get_shipping_address = mysqli_prepare($con, $sql_get_shipping_address);
                mysqli_stmt_bind_param($stmt_get_shipping_address, "i", $customer_id);
                mysqli_stmt_execute($stmt_get_shipping_address);
                $result_get_shipping_address = mysqli_stmt_get_result($stmt_get_shipping_address);

                if ($row = mysqli_fetch_assoc($result_get_shipping_address)) {
                    $recever_full_name = $row["recever_full_name"];
                    $shipping_address_tele = $row["shipping_address_tele"];
                    $address = $row["address"];
                    $selected_shipping_address_id = $row["shipping_address_id"];

                    // Display the address from the database
                    echo '<div class="m-8 text-cyan-100">
                        <p>Deliver to: <span>' . $recever_full_name . '</span></p>
                        <p>Telephone: <span>' . $shipping_address_tele . '</span></p> <br>
                        <p>Delivery address: <span>
                            <address>' . $address . '</address>
                        </span></p>
                        <button id="btn_change_shipping_add" class="mt-4" onclick="showShippingAddressList();">
                            <p class="text-end text-yellow-200 hover:text-blue-500">Change</p>
                        </button>
                    </div>';
                }


            }
        } else {
            // Display the address from the database
            $sql_get_shipping_address = "SELECT * FROM shipping_address WHERE customer_id=?";
            $stmt_get_shipping_address = mysqli_prepare($con, $sql_get_shipping_address);
            mysqli_stmt_bind_param($stmt_get_shipping_address, "i", $customer_id);
            mysqli_stmt_execute($stmt_get_shipping_address);
            $result_get_shipping_address = mysqli_stmt_get_result($stmt_get_shipping_address);

            if (mysqli_num_rows($result_get_shipping_address) <= 0) {
                echo '<p class="text-center text-red-700 my-3 py-2">No Shipping Address Found! Please add a new one.</p>
                <p class="text-center text-yellow-400 my-3 py-3 hover:text-blue-400"> 
                 <button id="btn_add_new_shipping_address">
                    Click here to add new address... 
                 </button>
                </p>';
            } 

            elseif ($row = mysqli_fetch_assoc($result_get_shipping_address)) {
                $recever_full_name = $row["recever_full_name"];
                $shipping_address_tele = $row["shipping_address_tele"];
                $address = $row["address"];

                // Display the address from the database
                echo '<div class="m-8 text-cyan-100">
                    <p>Deliver to: <span>' . $recever_full_name . '</span></p>
                    <p>Telephone: <span>' . $shipping_address_tele . '</span></p> <br>
                    <p>Delivery address: <span>
                        <address>' . $address . '</address>
                    </span></p>
                    <button id="btn_change_shipping_add" class="mt-4" onclick="showShippingAddressList();">
                        <p class="text-end text-yellow-200 hover:text-blue-500">Change</p>
                    </button>
                </div>';
            }
        }
    }
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//TO DISPLAY CUSTOMER's ALL SHIPPING ADDRESS
function displayAllShippingAddress() {
    global $con;

    if (!isset($_SESSION['customer_id'])) {
        header("Location: login.php");
        exit;
    } else {
        $customer_id = $_SESSION['customer_id'];

        $sql_get_shipping_address = "SELECT * FROM shipping_address WHERE customer_id=?";
        $stmt_get_shipping_address = mysqli_prepare($con, $sql_get_shipping_address);
        mysqli_stmt_bind_param($stmt_get_shipping_address, "i", $customer_id);
        mysqli_stmt_execute($stmt_get_shipping_address);
        $result_get_shipping_address = mysqli_stmt_get_result($stmt_get_shipping_address);

        while ($row = mysqli_fetch_assoc($result_get_shipping_address)) {
            $shipping_address_id = $row["shipping_address_id"];
            $recever_full_name = $row["recever_full_name"];
            $shipping_address_tele = $row["shipping_address_tele"];
            $address = $row["address"];

            // Display each address with a radio button
            echo '<div class="mt-4 mx-4">
                    <input type="radio" name="selected_shipping_address" id="' . $shipping_address_id . '" value="' . $shipping_address_id . '">
                    <label class="flex flex-col p-4 border-2 border-gray-400 cursor-pointer rounded-lg text-yellow-200">
                        <a href="update_shipping_address.php?shipping_address_id='.$shipping_address_id.'"><p class="text-end hover:text-blue-400 text-cyan-100">Edit</p></a>
                        <span class="text-sm">' . $recever_full_name . '</span>
                        <span class="text-sm mt-2">' . $shipping_address_tele . '</span>
                        <span class="text-sm mt-2">' . $address . '</span>
                    </label>
                </div>';
        }
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
        /* Custom radio button styles */
        input[type="radio"] {
            display: block;
            /* Hide the default radio input */
        }

        input[type="radio"]+label {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            border: 2px solid #ccc;
            cursor: pointer;
            border-radius: 0.5rem;
        }

        input[type="radio"]:checked+label {
            border-color: rgb(18, 219, 55);
            box-shadow: 0 10px 15px -3px rgba(84, 196, 29, 0.1), 0 4px 6px -2px rgba(19, 200, 28, 0.05);
        }


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
    class="bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black min-h-screen"
    data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">

    <section id="main_body">

        <!-- Navbar -->
        <nav class="flex justify-end lg:justify-between w-screen" data-aos="fade-up"
            data-aos-anchor-placement="top-bottom">
            <div class="px-5 xl:px-12 py-4 flex w-full items-center" data-aos="zoom-in">
                <a class="text-2xl font-semibold font-heading text-yellow-200" href="index.php">
                    <img class="h-12" src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo"
                        style="position: relative; left: 58px;">
                    DDN MOBILE
                </a>
                <!-- Nav Links -->
                <ul class="hidden md:flex px-4 mx-auto text-3xl font-semibold font-heading space-x-12 text-yellow-100">
                    <li>
                        <h1 class="hover:text-pink-200">Checkout Order</h1>
                    </li>

                </ul>
                <!-- Header Icons -->
                <div class="hidden md:flex items-end lg:items-center space-x-5" id="navList">
                    <?php user_icon(); ?>
                </div>
            </div>
            <!-- Responsive Navbar -->
            <div class="md:hidden relative right-4 flex space-x-2">
                <?php user_icon(); ?>
            </div>
        </nav>
        <hr class="mx-10 ">

        <!--USER PROFILE-->
        <?php 
            userProfile();
           // userDetails();
        ?>

        <!--CART-->
        <div class="container mx-auto mt-10">
            <div class="grid shadow-md my-10  grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                <div class="px-10 py-10 container col-span-1 md:col-span-2 lg:col-span-3 xl:col-span-3">
                    <!--TO DISPLAY SHIPPING ADDRESS-->
                    <div class=" border-double border rounded-lg mb-8">
                        <?php shippingAddress() ?>
                    </div>

                    <!--DISPLAY PRODUCT-->
                    <?php
                     echo '
                     <div class="flex justify-between border-b pb-8 text-yellow-200">
                         <h1 class="font-semibold text-xl">Order List</h1>
                         <h2 class="font-semibold text-xl"></h2>
                     </div>
                     <div class="flex mt-10 mb-5 font-semibold text-white text-xs uppercase">
                         <h3 class="w-2/5 text-center">Product Details</h3>
                         <h3 class="w-1/5 text-center">Quantity</h3>
                         <h3 class="w-1/5 text-center">Price</h3>
                         <h3 class="w-1/5 text-center">Total</h3>
                     </div>
                     <hr>
                    ';
                    
                    //FOR SHOPPING CART ITEMS
                    // Check if the 'shopping_cart_id' is set and if the user is logged in
                    if (isset($_GET['shopping_cart_id']) && isset($_SESSION['customer_id'])) {
                        $shopping_cart_id = $_GET['shopping_cart_id'];
                        $customer_id = $_SESSION['customer_id'];
                    
                        global $con; // Assuming $con is defined somewhere in your code
                    
                        // Check if a record exists in the temp_order table for the current customer
                        $sql_check_temp_order1 = "SELECT * FROM temp_order WHERE customer_id = ?";
                        $stmt_check_temp_order1 = mysqli_prepare($con, $sql_check_temp_order1);
                        mysqli_stmt_bind_param($stmt_check_temp_order1, 'i', $customer_id);
                        mysqli_stmt_execute($stmt_check_temp_order1);
                        $result_check_temp_order1 = mysqli_stmt_get_result($stmt_check_temp_order1);
                    
                        if ($result_check_temp_order1->num_rows > 0) {
                            // Delete existing records for the customer
                            $sql_delete_temp_order1 = "DELETE FROM temp_order WHERE customer_id = ?";
                            $stmt_delete_temp_order1 = mysqli_prepare($con, $sql_delete_temp_order1);
                            mysqli_stmt_bind_param($stmt_delete_temp_order1, 'i', $customer_id);
                            mysqli_stmt_execute($stmt_delete_temp_order1);
                        }
                    
                        // Insert a new record into the temp_order table
                        $sql_insert_temp_order1 = "INSERT INTO temp_order (customer_id, shopping_cart_id) VALUES (?, ?)";
                        $stmt_insert_temp_order1 = mysqli_prepare($con, $sql_insert_temp_order1);
                        mysqli_stmt_bind_param($stmt_insert_temp_order1, 'ii', $customer_id, $shopping_cart_id);
                        $result_insert_temp_order1 = mysqli_stmt_execute($stmt_insert_temp_order1);
                    
                        // Check if the INSERT query was successful
                        if ($result_insert_temp_order1) {
                            // Success
                        } else {
                            // Handle the error
                        }
                    
                        // Close the prepared statement
                        mysqli_stmt_close($stmt_insert_temp_order1);
                    }
                    
                    // FOR DIRECT ORDER ITEMS
                    if (isset($_GET['item_id'])) {
                        $customer_id = $_SESSION['customer_id'];
                        $item_id = $_GET['item_id'];
                        $quantity = $_GET['quantity'];
                        $item_color = $_GET['item_color'];
                    
                        global $con;
                    
                        // Check if a record exists in the temp_order table for the current customer
                        $sql_check_temp_order2 = "SELECT * FROM temp_order WHERE customer_id = ?";
                        $stmt_check_temp_order2 = mysqli_prepare($con, $sql_check_temp_order2);
                        mysqli_stmt_bind_param($stmt_check_temp_order2, 'i', $customer_id);
                        mysqli_stmt_execute($stmt_check_temp_order2);
                        $result_check_temp_order2 = mysqli_stmt_get_result($stmt_check_temp_order2);
                    
                        if ($result_check_temp_order2->num_rows > 0) {
                            // Delete existing records for the customer
                            $sql_delete_temp_order2 = "DELETE FROM temp_order WHERE customer_id = ?";
                            $stmt_delete_temp_order2 = mysqli_prepare($con, $sql_delete_temp_order2);
                            mysqli_stmt_bind_param($stmt_delete_temp_order2, 'i', $customer_id);
                            mysqli_stmt_execute($stmt_delete_temp_order2);
                        }
                    
                        // Insert a new record into the temp_order table
                        $sql_insert_temp_order2 = "INSERT INTO temp_order (customer_id, item_id, color, quantity) VALUES (?, ?, ?, ?)";
                        $stmt_insert_temp_order2 = mysqli_prepare($con, $sql_insert_temp_order2);
                        mysqli_stmt_bind_param($stmt_insert_temp_order2, 'iisi', $customer_id, $item_id, $item_color, $quantity);
                        $result_insert_temp_order2 = mysqli_stmt_execute($stmt_insert_temp_order2);
                    
                        // Check if the INSERT query was successful
                        if ($result_insert_temp_order2) {
                            // Success
                        } else {
                            // Handle the error
                        }
                    
                        // Close the prepared statement
                        mysqli_stmt_close($stmt_insert_temp_order2);
                    }
                    

                    display_oder_item_from_cart();
                    // Check if the "Submit" button with the name "btn_submit_address" is clicked
                    if (isset($_POST['btn_submit_address'])) {
                        display_oder_item_from_cart();
                    }
                    ?>
                </div>

            </div>
        </div>

        <footer class=" rounded-lg shadow m-4">
            <hr class="mx-0 mt-4 sm:mx-3 md:mx-2 lg:mx-2 xl:mx-2">
            <!--lishens-->
            <div class="w-full max-w-screen-xl mx-auto md:py-0">
                <div class="sm:flex sm:items-center sm:justify-between">
                    <div>
                        <a href="index.php" class="flex items-center mb-4 sm:mb-0">
                            <img src="../img/DDN_LOGO/Origibal_non_bg.png" class="" alt="DDN Logo" style="width: 30%;">
                        </a>
                    </div>
                    <div>
                        <span class="block text-sm text-yellow-300 sm:text-start dark:text-yellow-200">© 2023 <a
                                href="index.php" class="hover:text-blue-400">DDN MOBILE</a>. All Rights Reserved.</span>
                    </div>
                </div>
            </div>
        </footer>
    </section>



    <!--Change Shipping Address Card-->
    <section style="display: none;" id="change_shipping_add">
        <div
            class="fixed w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-36 left-14 md:left-20 lg:left-36 xl:left-44 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900">
            <h3 class="text-yellow-300 m-6 pt-4">My Delivery Address</h3>
            <!-- Component Start -->
            <form method="post" action="#" class="grid grid-cols-1 gap-2 w-full mr-3 max-w-screen-sm">

                <?php displayAllShippingAddress() ?>

                <div>
                    <button id="btn_add_new_address" type="button">
                        <p class="text-cyan-50 font-thin text-xs ml-6 mt-6"><span
                                class="text-xl text-yellow-300">+</span>Add new address</p>
                    </button>
                </div>
                <div class="ml-6 mb-4">
                    <button type="submit" id="btn_confirm_address" name="btn_confirm_address"
                        class=" block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-14 py-3 mt-6">Confirm
                    </button>
                </div>
            </form>
            <!-- Component End  -->
        </div>
    </section>


    <!-- Add New Address -->
    <section style="display: none;" id="add_new_shipping_add">
        <div
            class="text-yellow-100 fixed w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-36 left-14 md:left-20 lg:left-36 xl:left-44 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900">
            <!-- FORM -->
            <form method="post" action="#" id="frmNewShippingAddress" onsubmit="return validateShippingAddressForm()"
                class="container grid grid-cols-1 lg:grid-cols-2 m-5">
                <div>
                    <label class="block text-gray-500">Name</label>
                    <input type="text" name="txtReceverName" id="txtReceverName" placeholder="Receiver Name"
                        class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                        autofocus autocomplete>
                    <p><span class="text-sm text-red-600 ml-4" id="ErrorReceverName" name="ErrorReceverName"></span></p>
                </div>

                <div>
                    <label class="block text-gray-500">Telephone</label>
                    <input type="text" name="txtReceverTele" id="txtReceverTele" placeholder="Receiver Telephone"
                        class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                        autofocus autocomplete>
                    <p><span class="text-sm text-red-600 ml-4" id="ErrorReceverTele" name="ErrorReceverTele"></span></p>
                </div>

                <div>
                    <label class="block text-gray-500">Address</label>
                    <input type="text" name="txtReceverAddress" id="txtReceverAddress" placeholder="Receiver Address"
                        class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                        autofocus autocomplete>
                    <p><span class="text-sm text-red-600 ml-4" id="ErrorReceverAddress"
                            name="ErrorReceverAddress"></span></p>
                </div>

                <div>
                    <label class="block text-gray-500">City</label>
                    <input type="text" name="txtCity" id="txtCity" placeholder="City"
                        class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                        autofocus autocomplete>
                    <p><span class="text-sm text-red-600 ml-4" id="ErrorCity" name="ErrorCity"></span></p>
                </div>

                <div>
                    <label class="block text-gray-500">Province</label>
                    <select name="listProvince" id="listProvince"
                        class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900">
                        <option class="text-black" value="0">Select province</option>
                        <option class="text-black" value="Central">Central</option>
                        <option class="text-black" value="North Central">North Central</option>
                        <option class="text-black" value="North Eastern">North Eastern</option>
                        <option class="text-black" value="North Western">North Western</option>
                        <option class="text-black" value="Sabaragamuwa">Sabaragamuwa</option>
                        <option class="text-black" value="Southern">Southern</option>
                        <option class="text-black" value="Uva">Uva</option>
                        <option class="text-black" value="Western">Western</option>
                    </select>
                    <p><span class="text-sm text-red-800 ml-4" id="ErrorProvince" name="ErrorProvince"></span></p>
                </div>

                <div>
                    <label class="block text-gray-500">Postal Code</label>
                    <input type="text" name="txtPostalCode" id="txtPostalCode" placeholder="Postal Code"
                        class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                        autofocus autocomplete>
                    <p><span class="text-sm text-red-800 ml-4" id="ErrorPostalCode" name="ErrorPostalCode"></span></p>
                </div>

                <div class="ml-2 mb-4 flex gap-4">
                    <button type="submit" id="btn_submit_address" name="btn_submit_address"
                        class=" block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus-ring-4 focus-ring-primary-300 dark-focus-ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-14 py-3 mt-6">Submit</button>

                    <button type="button" id="btn_cancel_address" name="btn_cancel_address"
                        onclick="HideCreateNewAddressForm()"
                        class=" block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus-ring-4 focus-ring-primary-300 dark-focus-ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-14 py-3 mt-6">Cancel</button>
                </div>
            </form>
        </div>
    </section>

    <script>
        const btnChangeShippingAdd = document.querySelector("#btn_change_shipping_add");
        const btnConfirmAddress = document.querySelector("#btn_confirm_address");
        const changeShippingAdd = document.querySelector("#change_shipping_add");
        const mainBody = document.querySelector("#main_body");
        const addNewShippingAdd = document.querySelector("#add_new_shipping_add");
        const btnAddNewAddress = document.querySelector("#btn_add_new_address");
        const btnAddNewShippingAddress = document.querySelector("#btn_add_new_shipping_address");
        const btn_select_shipping_address = document.querySelector("#btn_select_shipping_address");


        function showShippingAddressList() {
            changeShippingAdd.style.display = "block";
            mainBody.classList.add('blur');
        }

        function hideShippingAddressList() {
            changeShippingAdd.style.display = "none";
            mainBody.classList.remove('blur');
        }

        function showAddressForm() {
            addNewShippingAdd.style.display = 'block';
            changeShippingAdd.style.display = "none";
        }

        function showCreateNewAddress() {
            addNewShippingAdd.style.display = 'block';
            changeShippingAdd.style.display = "none";
            mainBody.classList.add('blur');
        }

        function HideCreateNewAddressForm() {
            addNewShippingAdd.style.display = 'none';
            changeShippingAdd.style.display = "block";
        }

        btnAddNewAddress.addEventListener('click', showAddressForm);
        btnConfirmAddress.addEventListener('click', hideShippingAddressList);
        btnAddNewShippingAddress.addEventListener('click', showCreateNewAddress);
        btnChangeShippingAdd.addEventListener('click', showShippingAddressList);
        btn_select_shipping_address.addEventListener('click', showShippingAddressList);


        function validateShippingAddressForm() {
            var txtReceverName = document.getElementById("txtReceverName").value;
            var txtReceverTele = document.getElementById("txtReceverTele").value;
            var txtReceverAddress = document.getElementById("txtReceverAddress").value;
            var txtCity = document.getElementById("txtCity").value;
            var listProvince = document.getElementById("listProvince").value;
            var txtPostalCode = document.getElementById("txtPostalCode").value;

            var ErrorReceverName = document.getElementById("ErrorReceverName");
            var ErrorReceverTele = document.getElementById("ErrorReceverTele");
            var ErrorReceverAddress = document.getElementById("ErrorReceverAddress");
            var ErrorCity = document.getElementById("ErrorCity");
            var ErrorProvince = document.getElementById("ErrorProvince");
            var ErrorPostalCode = document.getElementById("ErrorPostalCode");

            ErrorReceverName.textContent = "";
            ErrorReceverTele.textContent = "";
            ErrorReceverAddress.textContent = "";
            ErrorCity.textContent = "";
            ErrorProvince.textContent = "";
            ErrorPostalCode.textContent = "";

            if (txtReceverName.trim() === "") {
                ErrorReceverName.textContent = "Name is required";
                return false;
            }

            if (txtReceverTele.trim() === "") {
                ErrorReceverTele.textContent = "Telephone Number is required";
                return false;
            }

            if (txtReceverAddress.trim() === "") {
                ErrorReceverAddress.textContent = "Address is required";
                return false;
            }

            if (txtCity.trim() === "") {
                ErrorCity.textContent = "City is required";
                return false;
            }

            if (listProvince === "0") {
                ErrorProvince.textContent = "Please select a province";
                return false;
            }

            if (txtPostalCode.trim() === "") {
                ErrorPostalCode.textContent = "Postal code is required";
                return false;
            }

            return true;
        }

        var NewShippingAddress = document.getElementById("frmNewShippingAddress");
        if (NewShippingAddress) {
            NewShippingAddress.addEventListener("submit", function (event) {
                if (!validateShippingAddressForm()) {
                    event.preventDefault();
                } else {
                    HideCreateNewAddressForm
                        (); // Close the form when all fields are entered and form is successfully submitted.
                }
            });
        }
        var UpdateShippingAddress = document.getElementById("frmUpdateShippingAddress");
        if (UpdateShippingAddress) {
            UpdateShippingAddress.addEventListener("submit", function (event) {
                if (!validateShippingAddressForm()) {
                    event.preventDefault();
                } else {
                    HideCreateNewAddressForm
                        (); // Close the form when all fields are entered and form is successfully submitted.
                }
            });
        }
    </script>

    <script>
        // JavaScript to toggle responsive navigation links on small screens
        document.getElementById('burgerBtn').addEventListener('click', function () {
            document.getElementById('responsiveNav').classList.toggle('hidden');
        });

        function viewProfilCard(){
            var userProfile = document.getElementById('userProfile');
            userProfile.classList.toggle('hidden');
        }

    </script>



    <script src="../JS/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1800,
        })
    </script>
</body>

</html>