<?php
    include("./connection_db/dbconnect.php");
    include("./functions/cart_icon.php");
    include("./functions/user_profile.php");
    include("./functions/metaTags.php");
    session_start();

    $currentDate = date('Y-m-d');
    $randomOrderNumber = ' ';
    $order_suc_msg = "";
    

    // Function to generate a random order number
    function generateOrderNumber($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#$&@';
        $result = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[rand(0, $charactersLength - 1)];
        }
        return $result;
    }

    // GET SHIPPING ADDRESS
    function orderConfrimProsece() {
        global $con;
        $randomOrderNumber = ' ';
        $currentDate = date('Y-m-d');
        global $order_suc_msg;

        if (!isset($_SESSION['customer_id'])) {
            header("Location: login.php");
            exit;
    
        } else {
    
            if (isset($_GET['shipping_address']) && isset($_GET["total_cost"]) && isset($_SESSION['customer_id'])) {
                $shipping_address_id = $_GET["shipping_address"];
                $total_cost = $_GET["total_cost"];
                $customer_id = $_SESSION["customer_id"];
    
                if ($shipping_address_id == null) {
                    $sql_get_first_sai = "SELECT shipping_address_id FROM shipping_address WHERE customer_id = ?";
                    $stmt_get_first_sai = mysqli_prepare($con, $sql_get_first_sai);
                    mysqli_stmt_bind_param($stmt_get_first_sai, 'i', $customer_id);
                    mysqli_stmt_execute($stmt_get_first_sai);
                    $result_get_first_sai = mysqli_stmt_get_result($stmt_get_first_sai);
    
                    $row = mysqli_fetch_assoc($result_get_first_sai);
                    $shipping_address_id = $row["shipping_address_id"];
                } else {
                    $shipping_address_id = $shipping_address_id;
                }
            }
    
            
            if (isset($_POST["btn_confirm"])) {
                # GENERATE ORDER CODE NUMBER
                $randomOrderNumber = generateOrderNumber(8);
                $order_suc_msg = " ";
    
                $sql_select_temp_order1 = "SELECT * FROM temp_order WHERE customer_id = ?";
                $stmt_select_temp_order1 = mysqli_prepare($con, $sql_select_temp_order1);
                mysqli_stmt_bind_param($stmt_select_temp_order1, 'i', $customer_id);
                mysqli_stmt_execute($stmt_select_temp_order1);
                $result_select_temp_order1 = mysqli_stmt_get_result($stmt_select_temp_order1);
                $row = mysqli_fetch_assoc($result_select_temp_order1);
                $shopping_cart_id = $row["shopping_cart_id"];
                $item_id = $row["item_id"];
                $item_color = $row["color"];
                $quantity = $row["quantity"];
    
                # CODE FOR DIRECT ORDERS
                if ($item_id != null) {
                    # GET STOCK QUANTITY FOR ITEM
                    $sql_get_item_stock_quantity = "SELECT item_stock_quantity FROM items WHERE item_id = ?";
                    $stmt_get_item_stock_quantity = mysqli_prepare($con, $sql_get_item_stock_quantity);
                    mysqli_stmt_bind_param($stmt_get_item_stock_quantity, 'i', $item_id);
                    mysqli_stmt_execute($stmt_get_item_stock_quantity);
                    $result_get_item_stock_quantity = mysqli_stmt_get_result($stmt_get_item_stock_quantity);
                    $row = mysqli_fetch_assoc($result_get_item_stock_quantity);
                    $item_stock_quantity = $row["item_stock_quantity"];
    
                    $new_quantity = $item_stock_quantity - $quantity;
    
                    # UPDATE STOCK QUANTITY
                    $sql_update_quantity = "UPDATE items SET item_stock_quantity = ? WHERE item_id = ?";
                    $stmt_update_quantity = mysqli_prepare($con, $sql_update_quantity);
                    mysqli_stmt_bind_param($stmt_update_quantity, 'ii', $new_quantity, $item_id);
                    mysqli_stmt_execute($stmt_update_quantity);
    
                    # INSERT NEW ORDER
                    $sql_insert_into_orders = "INSERT INTO oders(oders_total_cost, oder_date, shipping_address_id, customer_id, oders_code) VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert_into_orders = mysqli_prepare($con, $sql_insert_into_orders);
                    mysqli_stmt_bind_param($stmt_insert_into_orders, 'dsiis', $total_cost, $currentDate, $shipping_address_id, $customer_id, $randomOrderNumber);
                    mysqli_stmt_execute($stmt_insert_into_orders);
    
                    # GET LAST INSERTED ORDER ID
                    $order_id = mysqli_insert_id($con);
    
                    $sql_insert_into_order_item = "INSERT INTO oder_item (oder_item_quantity, item_id, oders_id, oder_item_color) VALUES (?, ?, ?, ?)";
                    $stmt_insert_into_order_item = mysqli_prepare($con, $sql_insert_into_order_item);
                    mysqli_stmt_bind_param($stmt_insert_into_order_item, 'iiis', $quantity, $item_id, $order_id, $item_color);
                    mysqli_stmt_execute($stmt_insert_into_order_item); // You should execute $stmt_insert_into_order_item, not $stmt_insert_into_orders
                    
    
    
                    # CODE FOR SHOPPING CART ORDERS
                } elseif ($shopping_cart_id != null) {
                    // Start a transaction
                    mysqli_begin_transaction($con);
                
                    $sql_get_item_id_cart = "SELECT item_id, cart_item_quntity, cart_item_color FROM cart_item WHERE shopping_cart_id = ?";
                    $stmt_get_item_id_cart = mysqli_prepare($con, $sql_get_item_id_cart);
                
                    if ($stmt_get_item_id_cart === false) {
                        die('Failed to prepare the statement: ' . mysqli_error($con));
                    }
                
                    mysqli_stmt_bind_param($stmt_get_item_id_cart, 'i', $shopping_cart_id);
                    mysqli_stmt_execute($stmt_get_item_id_cart);
                    $result_get_item_id_cart = mysqli_stmt_get_result($stmt_get_item_id_cart);
                
                    $item_data = array();
                
                    while ($row = mysqli_fetch_assoc($result_get_item_id_cart)) {
                        $item_ids[] = $row["item_id"];
                        $item_data[] = array(
                            'item_id' => $row["item_id"],
                            'color' => $row["cart_item_color"],
                            'quantity' => $row["cart_item_quntity"]
                        );
                    }
                
                    foreach ($item_data as $item) {
                        $item_id = $item['item_id'];
                        $quantity = $item['quantity'];
                        $item_color = $item['color'];
                
                        $sql_get_item_stock_quantity = "SELECT item_stock_quantity FROM items WHERE item_id = ?";
                        $stmt_get_item_stock_quantity = mysqli_prepare($con, $sql_get_item_stock_quantity);
                        mysqli_stmt_bind_param($stmt_get_item_stock_quantity, 'i', $item_id);
                        mysqli_stmt_execute($stmt_get_item_stock_quantity);
                        $result_get_item_stock_quantity = mysqli_stmt_get_result($stmt_get_item_stock_quantity);
                
                        if ($result_get_item_stock_quantity) {
    
                            while ($row = mysqli_fetch_assoc($result_get_item_stock_quantity)) {
    
                                $item_stock_quantity = $row["item_stock_quantity"];
                                $new_quantity = $item_stock_quantity - $quantity;
                
                                // Update the stock quantity in the database
                                $sql_update_item_stock_quantity = "UPDATE items SET item_stock_quantity = ? WHERE item_id = ?";
                                $stmt_update_item_stock_quantity = mysqli_prepare($con, $sql_update_item_stock_quantity);
                                mysqli_stmt_bind_param($stmt_update_item_stock_quantity, 'ii', $new_quantity, $item_id);
                
                                if (mysqli_stmt_execute($stmt_update_item_stock_quantity)) {
                                    // The update was successful
                                } else {
                                    // Handle the update error
                                    mysqli_rollback($con); // Rollback the transaction
                                    die('Failed to update item stock quantity: ' . mysqli_error($con));
                                }
                            }
                        } else {
                            // Handle the SELECT error
                            mysqli_rollback($con); // Rollback the transaction
                            die('Failed to fetch item stock quantity: ' . mysqli_error($con));
                        }
                
                        
                    }
    
                // INSERT NEW ORDER
                    $sql_insert_into_orders = "INSERT INTO oders (oders_total_cost, oder_date, shipping_address_id, customer_id, oders_code) VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert_into_orders = mysqli_prepare($con, $sql_insert_into_orders);
    
                    if ($stmt_insert_into_orders) {
                        mysqli_stmt_bind_param($stmt_insert_into_orders, 'dsiis', $total_cost, $currentDate, $shipping_address_id, $customer_id, $randomOrderNumber);
    
                        if (mysqli_stmt_execute($stmt_insert_into_orders)) {
                            // The insertion was successful
                        } else {
                            // Handle the INSERT error
                            mysqli_rollback($con); // Rollback the transaction
                            die('Failed to insert into orders: ' . mysqli_error($con));
                        }
    
                        // GET LAST INSERTED ORDER ID
                        $order_id = mysqli_insert_id($con);
    
                        foreach ($item_data as $item) {
                            $item_id = $item['item_id'];
                            $quantity = $item['quantity'];
                            $item_color = $item['color'];
    
                            $sql_insert_into_order_item = "INSERT INTO oder_item (oder_item_quantity, item_id, oders_id, oder_item_color) VALUES (?, ?, ?, ?)";
                            $stmt_insert_into_order_item = mysqli_prepare($con, $sql_insert_into_order_item);
    
                            if ($stmt_insert_into_order_item) {
                                mysqli_stmt_bind_param($stmt_insert_into_order_item, 'iiis', $quantity, $item_id, $order_id, $item_color);
    
                                if (mysqli_stmt_execute($stmt_insert_into_order_item)) {
                                    // The insertion was successful
                                } else {
                                    // Handle the INSERT error
                                    mysqli_rollback($con); // Rollback the transaction
                                    die('Failed to insert into order_item: ' . mysqli_error($con));
                                }
                            } else {
                                // Handle the preparation error
                                mysqli_rollback($con); // Rollback the transaction
                                die('Failed to prepare statement for order_item: ' . mysqli_error($con));
                            }
                        }
    
                        # DELETE SHOPPING CART ITEMS
                        $sql_delete_cart_items = "DELETE FROM cart_item WHERE shopping_cart_id = ?";
                        $stmt_delete_cart_items = mysqli_prepare($con, $sql_delete_cart_items);
                        mysqli_stmt_bind_param($stmt_delete_cart_items, 'i', $shopping_cart_id);
                        mysqli_stmt_execute($stmt_delete_cart_items);
    
                        // Commit the transaction if everything was successful
                        mysqli_commit($con);
                    } else {
                        // Handle the preparation error for the initial order
                        die('Failed to prepare statement for orders: ' . mysqli_error($con));
                    }                    
                }
                #DELETE Temp ORDER
                $sql_delete_tem_order = "DELETE FROM temp_order WHERE customer_id = ?";
                $stmt_delete_tem_order = mysqli_prepare($con, $sql_delete_tem_order);
                mysqli_stmt_bind_param($stmt_delete_tem_order, 'i',$customer_id);
                mysqli_stmt_execute($stmt_delete_tem_order);

                $order_suc_msg = "Order Confirm Successful...";

            }
        }

        echo '
            <form action="#" method="post">
            <hr class="mx-10 ">
                    <div class="w-screen container mt-4 mb-4 grid grid-cols-0 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 items-center content-center">
                        <div class="items-center">      
                            <h4 id="date" class="text-xl md:text-2xl lg:text-3xl xl:text-3xl text-lime-400 text-center">'.$currentDate.'</h4>
                        </div>

                        <div>
                            <h4 id="order-number" class="text-xl md:text-2xl lg:text-3xl xl:text-3xl text-red-600 text-center"><span class="text-lg text-lime-400" >Your Order Code :- </span> '.$randomOrderNumber.'</h4>
                        </div>     
                    </div>
                    <hr class="mx-10 ">
                <!----------------------------------------------------------------------------------------------------------------------------------->
                    <div class="md:flex">
                    <!-- PAYMENT -->
                    <div class="mt-16 w-screen md:w-1/2 lg:w-2/3 xl:w-2/3 mx-auto">
                        <h2 class="text-green-500 text-center text-2xl font-semibold mb-4">Payment Information</h2>
                        <p class="text-yellow-200 text-center mb-2">Bank Deposit Only Allowed</p>
                        <p class="text-yellow-200 text-center mb-2">Make your payment to the following account:</p>
                        <p class="text-yellow-300 text-center text-lg font-bold mb-4">Account No: 2457855</p>
                        <p class="text-yellow-200 text-center mb-2">Please WhatsApp your bank slip to <span class="text-bold">0778954122</span></p>
                        <p class="text-red-500 text-center mb-2">Remember to write your order code on the bank slip.</p>
                        <!-- Placeholder for order code -->
                        <p class="text-yellow-300 text-center font-bold text-xl mt-4">Your Order Code: <span class="text-bold text-red-600">'.$randomOrderNumber.' </span></p> <br/>
                        <!-- Additional information if needed -->
                        <h3 class="text-4xl text-center text-green-500">'.$order_suc_msg.'</h3>
                    </div>

                    
                    <!--ORDER SUMMARY-->
                    <div id="summary" class="px-8 py-10 md:col-span-1 lg:col-span-1 xl:col-span-1">
                        <h1 class="font-semibold text-yellow-200 text-xl border-b pb-8">Order Summary</h1>
                    
                        <div class="border-t mt-8 text-amber-100">
                            <div class="flex gap-44 font-semibold justify-between py-6 text-sm uppercase">
                                <span>Total cost</span>
                                <span>Rs.' . number_format((float) $total_cost, 2) . '</span>
                            </div>
                        </div>
                        
                        <button id="btn_confirm" name="btn_confirm"
                                class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">Confirm Order</button>
                        
                    </div>
            </form>
             ';
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

<body
    class="bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black min-h-screen">

    <!-- Navbar -->
    <nav class="flex justify-end  lg:justify-between w-screen">
        <div class="px-5 xl:px-12 py-4 flex w-full items-center">
            <a class="text-2xl font-semibold font-heading text-yellow-200" href="index.php">
                <img class="h-12" src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo"
                    style="position: relative; left: 58px;">
                DDN MOBILE
            </a>
            <!-- Nav Links -->
            <ul class="hidden md:flex px-4 mx-auto text-3xl font-semibold font-heading space-x-12 text-yellow-100">
                <li>
                    <h1 class="hover:text-pink-200">ConFirm My Order</h1>
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

    <!------------------------------------------------------------------------------------------------------------------------------------>
    <!--DATE & ORDER NUMBER-->
    <?php orderConfrimProsece() ?>


    <script>
        // JavaScript to toggle responsive navigation links on small screens
        document.getElementById('burgerBtn').addEventListener('click', function () {
            document.getElementById('responsiveNav').classList.toggle('hidden');
        });

        function viewProfilCard() {
            var userProfile = document.getElementById('userProfile');
            userProfile.classList.toggle('hidden');
        }
    </script>
</body>

</html>