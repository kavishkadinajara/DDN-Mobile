<?php
    include("./connection_db/dbconnect.php");
    global $con;
    $confirm_msg = '';
    $msg_color = '';

    if(isset($_GET["order_id"])){
        $order_id = $_GET["order_id"];
        // echo $order_id;

        // Use prepared statement to prevent SQL injection
        $sql_update_order = "UPDATE oders SET oders_confrim_admin = 1 WHERE oders_id = ?";
        $stmt_update_order = mysqli_prepare($con, $sql_update_order);
        mysqli_stmt_bind_param($stmt_update_order, 'i', $order_id);
        mysqli_stmt_execute($stmt_update_order);

    }

    function display_selected_orders() {
        if (isset($_GET['txtSearchCode'])) {
            $oders_code = $_GET["txtSearchCode"];
            displayViaSearch($oders_code);

        } else {
            displayAll();
        }
    }

    function displayViaSearch($oders_code) {
        global $con;
        

            $sql_select_orders = "SELECT DISTINCT O.oders_id, O.oder_date, O.oders_total_cost, O.oders_code, O.oders_confrim_admin, O.oders_deliver_cus, SA.recever_full_name, SA.address, SA.city, SA.province, SA.postal_code, SA.shipping_address_tele
            FROM oders AS O
            INNER JOIN oder_item AS OI ON O.oders_id = OI.oders_id
            INNER JOIN items AS I ON I.item_id = OI.item_id
            INNER JOIN shipping_address AS SA ON SA.shipping_address_id = O.shipping_address_id
            INNER JOIN customer AS C ON C.customer_id = SA.customer_id WHERE oders_code = ?";

            $stmt_select_orders = mysqli_prepare($con, $sql_select_orders); 
            mysqli_stmt_bind_param($stmt_select_orders, "s", $oders_code);
            // Execute the prepared statement
            mysqli_stmt_execute($stmt_select_orders);
            $result_select_orders = mysqli_stmt_get_result($stmt_select_orders);


            $orders = array();
            while ($row_select_orders = mysqli_fetch_assoc($result_select_orders)) {
                $oders_id = $row_select_orders["oders_id"];
                $oder_date = $row_select_orders["oder_date"];
                $oders_total_cost = $row_select_orders["oders_total_cost"];
                $oders_code = $row_select_orders["oders_code"];
                $oders_confrim_admin = $row_select_orders["oders_confrim_admin"];
                $oders_deliver_cus = $row_select_orders["oders_deliver_cus"];
                $recever_full_name = $row_select_orders["recever_full_name"];
                $address = $row_select_orders["address"];
                $city = $row_select_orders["city"];
                $province = $row_select_orders["province"];
                $postal_code = $row_select_orders["postal_code"];
                $shipping_address_tele = $row_select_orders["shipping_address_tele"];
        
                // Initialize an array to store items for the current order
                $items = array();
        
                // Fetch items for the current order
                $sql_select_items = "SELECT OI.oder_item_quantity, OI.oder_item_color, I.item_name, I.item_img_url1, B.brand_name
                                    FROM oder_item AS OI
                                    INNER JOIN items AS I ON I.item_id = OI.item_id
                                    INNER JOIN brand AS B ON B.brand_id = I.brand_id
                                    WHERE OI.oders_id = $oders_id";
        
                $stmt_select_items = mysqli_prepare($con, $sql_select_items);
                mysqli_stmt_execute($stmt_select_items);
                $result_select_items = mysqli_stmt_get_result($stmt_select_items);
        
                while ($item_row = mysqli_fetch_assoc($result_select_items)) {
                    $item_quantity = $item_row["oder_item_quantity"];
                    $item_color = $item_row["oder_item_color"];
                    $item_name = $item_row["item_name"];
                    $item_img_url1 = $item_row["item_img_url1"];
                    $brand_name = $item_row["brand_name"];
        
                    // Add the current item to the items array
                    $items[] = array(
                        "item_name" => $item_name,
                        "item_img_url1" => $item_img_url1,
                        "brand_name" => $brand_name,
                        "item_quantity" => $item_quantity,
                        "item_color" => $item_color,
                    );
                }
        
                // Add the current order and its items to the orders array
                $orders[] = array(
                    "order_id" => $oders_id,
                    "order_date" => $oder_date,
                    "total_cost" => $oders_total_cost,
                    "order_code" => $oders_code,
                    "confirm_admin" => $oders_confrim_admin,
                    "confirm_deliver" => $oders_deliver_cus,
                    "customer_name" => $recever_full_name,
                    "address" => $address,
                    "city" => $city,
                    "province" => $province,
                    "postal_code" => $postal_code,
                    "tele_no" => $shipping_address_tele,
                    "items" => $items,
                );
            }

            
            if (empty($orders)) {
                echo '<div class="text-center mb-20 mt-28 text-xl md:text-2xl lg:text-4xl text-red-500">No orders found for code: ' . $oders_code . '</div>';
                return; // Exit the function if no orders are found
            } 
        

        // Reverse the order of the $orders array
        $orders = array_reverse($orders);
    
        // Loop through the orders array to generate HTML
        foreach ($orders as $order) {
    
            if($order["confirm_admin"] == null) {
                $confrim_msg = 'This order not confirmed yet...';
                $msg_color = 'red';
            } else {
                $confrim_msg = 'This order confrimed...';
                $msg_color = 'green';
            }

            if($order["confirm_deliver"] == null) {
                $deliver_msg = 'Not deliver yet...';
                $msg_color1 = 'red';
            } else {
                $deliver_msg = 'Order deliver sucssefully...';
                $msg_color1 = 'green';
            }
    
            echo '<div class="items-center hover:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-400 via-rose-950 to-slate-700 -mx-8 px-4 py-5">';
            echo '<div class="grid w-full grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">';
    
            // Loop through items for the current order
            foreach ($order["items"] as $item) {
                echo '<div class="flex content-center justify-center my-1">';
                echo '<div class="w-20">';
                echo '<img class="h-24" src="../img/item_imgs/'.$item["item_img_url1"].'" alt="">';
                echo '</div>';
                echo '<div class="flex flex-col justify-between ml-4 flex-grow">';
                echo '<span class="font-bold text-sm text-amber-100 dark:text-cyan-50">' . $item["item_name"] . '</span>';
                echo '<span class="text-green-500 text-sm">' . $item["brand_name"] . '</span>';
                echo '<div class=" items-center mb-8">';
                echo '<div class="flex"><h2 class="w-16 mr-6 text-sm font-thin dark:text-gray-400">Color:</h2>';
                echo '<p class="w-2 h-2 bg-'.$item["item_color"].'-600 rounded-full" style="background-color:'.$item["item_color"].';"></p></div>';
                echo '<div class="flex"><h2 class="w-16 mr-6 text-sm font-thin dark:text-gray-400">Quantity:</h2>';
                echo '<p class=" text-yellow-100">'.$item["item_quantity"].'</p></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
    
            echo '</div>';
    
            echo '<div class="mt-3 text-yellow-100 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">';
            echo '<div><h3>Orderd date : <span>' . $order["order_date"] . '</span></h3><br></div>';
            echo '<div><h3>Total cost : <span>Rs.' . number_format((float)$order["total_cost"],2) . '</span></h3><br></div>';
            echo '<div><h3>Order code : <span>' . $order["order_code"] . '</span></h3><br></div>';
            echo '<div><h3>Customer Name : <span>' . $order["customer_name"] . '</span></h3><br></div>';
            echo '<div>';
            echo '<h3>Receiver name : <span>' . $order["customer_name"] . '</span></h3>';
            echo '<h3>Delivery Address : <address><span>' . $order["address"] . '</span></address></h3>';
            echo '<h3>City : <span>' . $order["city"] . '</span></h3>';
            echo '<h3>Province : <span>' . $order["province"] . '</span></h3>';
            echo '<h3>Postal code : <span>' . $order["postal_code"] . '</span></h3>';
            echo '<h3>Tele No : <span>' . $order["tele_no"] . '</span></h3>';
            echo '</div><div></div>';
            echo '<div>';
            //echo '<form action="#" method="post" id="confirmForm">';
            //echo  $order["order_id"];
            //echo '<input type="hidden" id="order_id" name="order_id" value="'.$order["order_id"].'">';
            echo '<h3><span class="text-'.$msg_color.'-500">'.$confrim_msg.'</span></h3><br>';
            echo '<a href="view_order_list.php?order_id='.$order["order_id"].'"><button type="button" id="btn_confrim_order" name="btn_confrim_order" class="items-center justify-self-auto px-4 py-2 mr-1 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-2 focus:ring-primary-300 dark:focus:ring-primary-900">Confirm Now</button></a>';
            echo '</div>';
            echo '<div>';
                //echo '<form action="#" method="post" id="confirmForm">';
                //echo  $order["order_id"];
                //echo '<input type="hidden" id="order_id" name="order_id" value="'.$order["order_id"].'">';
            echo '<h3><span class="text-'.$msg_color1.'-500">'.$deliver_msg.'</span></h3><br>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } 

    }

    function displayAll() {
        global $con;
    
    
        $sql_select_orders = "SELECT DISTINCT O.oders_id, O.oder_date, O.oders_total_cost, O.oders_code, O.oders_confrim_admin,O.oders_deliver_cus, SA.recever_full_name, SA.address, SA.city, SA.province, SA.postal_code, SA.shipping_address_tele
                            FROM oders AS O
                            INNER JOIN oder_item AS OI ON O.oders_id = OI.oders_id
                            INNER JOIN items AS I ON I.item_id = OI.item_id
                            INNER JOIN shipping_address AS SA ON SA.shipping_address_id = O.shipping_address_id
                            INNER JOIN customer AS C ON C.customer_id = SA.customer_id ";
    
        $stmt_select_orders = mysqli_prepare($con, $sql_select_orders);
        mysqli_stmt_execute($stmt_select_orders);
        $result_select_orders = mysqli_stmt_get_result($stmt_select_orders);
    
        // Fetch orders
        $orders = array();
        while ($row = mysqli_fetch_assoc($result_select_orders)) {
            $oders_id = $row["oders_id"];
            $oder_date = $row["oder_date"];
            $oders_total_cost = $row["oders_total_cost"];
            $oders_code = $row["oders_code"];
            $oders_confrim_admin = $row["oders_confrim_admin"];
            $oders_deliver_cus = $row["oders_deliver_cus"];
            $recever_full_name = $row["recever_full_name"];
            $address = $row["address"];
            $city = $row["city"];
            $province = $row["province"];
            $postal_code = $row["postal_code"];
            $shipping_address_tele = $row["shipping_address_tele"];
    
            // Initialize an array to store items for the current order
            $items = array();
    
            // Fetch items for the current order
            $sql_select_items = "SELECT OI.oder_item_quantity, OI.oder_item_color, I.item_name, I.item_img_url1, B.brand_name
                                FROM oder_item AS OI
                                INNER JOIN items AS I ON I.item_id = OI.item_id
                                INNER JOIN brand AS B ON B.brand_id = I.brand_id
                                WHERE OI.oders_id = $oders_id";
    
            $stmt_select_items = mysqli_prepare($con, $sql_select_items);
            mysqli_stmt_execute($stmt_select_items);
            $result_select_items = mysqli_stmt_get_result($stmt_select_items);
    
            while ($item_row = mysqli_fetch_assoc($result_select_items)) {
                $item_quantity = $item_row["oder_item_quantity"];
                $item_color = $item_row["oder_item_color"];
                $item_name = $item_row["item_name"];
                $item_img_url1 = $item_row["item_img_url1"];
                $brand_name = $item_row["brand_name"];
    
                // Add the current item to the items array
                $items[] = array(
                    "item_name" => $item_name,
                    "item_img_url1" => $item_img_url1,
                    "brand_name" => $brand_name,
                    "item_quantity" => $item_quantity,
                    "item_color" => $item_color,
                );
            }
    
            // Add the current order and its items to the orders array
            $orders[] = array(
                "order_id" => $oders_id,
                "order_date" => $oder_date,
                "total_cost" => $oders_total_cost,
                "order_code" => $oders_code,
                "confirm_admin" => $oders_confrim_admin,
                "confirm_deliver" => $oders_deliver_cus,
                "customer_name" => $recever_full_name,
                "address" => $address,
                "city" => $city,
                "province" => $province,
                "postal_code" => $postal_code,
                "tele_no" => $shipping_address_tele,
                "items" => $items,
            );
        }
    
    
    
        // Reverse the order of the $orders array
        $orders = array_reverse($orders);
    
        // Loop through the orders array to generate HTML
        foreach ($orders as $order) {
    
            if($order["confirm_admin"] == null) {
                $confrim_msg = 'This order not confirmed yet...';
                $msg_color = 'red';
            } else {
                $confrim_msg = 'This order confrimed...';
                $msg_color = 'green';
            }

            if($order["confirm_deliver"] == null) {
                $deliver_msg = 'Not deliver yet...';
                $msg_color1 = 'red';
            } else {
                $deliver_msg = 'Order deliver sucssefully...';
                $msg_color1 = 'green';
            }
    
            echo '<div class="items-center hover:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-400 via-rose-950 to-slate-700 -mx-8 px-4 py-5">';
            echo '<div class="grid w-full grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">';
    
            // Loop through items for the current order
            foreach ($order["items"] as $item) {
                echo '<div class="flex content-center justify-center my-1">';
                echo '<div class="w-20">';
                echo '<img class="h-24" src="../img/item_imgs/'.$item["item_img_url1"].'" alt="">';
                echo '</div>';
                echo '<div class="flex flex-col justify-between ml-4 flex-grow">';
                echo '<span class="font-bold text-sm text-amber-100 dark:text-cyan-50">' . $item["item_name"] . '</span>';
                echo '<span class="text-green-500 text-sm">' . $item["brand_name"] . '</span>';
                echo '<div class=" items-center mb-8">';
                echo '<div class="flex"><h2 class="w-16 mr-6 text-sm font-thin dark:text-gray-400">Color:</h2>';
                echo '<p class="w-2 h-2 bg-'.$item["item_color"].'-600 rounded-full" style="background-color:'.$item["item_color"].';"></p></div>';
                echo '<div class="flex"><h2 class="w-16 mr-6 text-sm font-thin dark:text-gray-400">Quantity:</h2>';
                echo '<p class=" text-yellow-100">'.$item["item_quantity"].'</p></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
    
            echo '</div>';
    
            echo '<div class="mt-3 text-yellow-100 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">';
            echo '<div><h3>Orderd date : <span>' . $order["order_date"] . '</span></h3><br></div>';
            echo '<div><h3>Total cost : <span>Rs.' . number_format((float)$order["total_cost"],2) . '</span></h3><br></div>';
            echo '<div><h3>Order code : <span>' . $order["order_code"] . '</span></h3><br></div>';
            echo '<div><h3>Customer Name : <span>' . $order["customer_name"] . '</span></h3><br></div>';
            echo '<div>';
            echo '<h3>Receiver name : <span>' . $order["customer_name"] . '</span></h3>';
            echo '<h3>Delivery Address : <address><span>' . $order["address"] . '</span></address></h3>';
            echo '<h3>City : <span>' . $order["city"] . '</span></h3>';
            echo '<h3>Province : <span>' . $order["province"] . '</span></h3>';
            echo '<h3>Postal code : <span>' . $order["postal_code"] . '</span></h3>';
            echo '<h3>Tele No : <span>' . $order["tele_no"] . '</span></h3>';
            echo '</div><div></div>';
            echo '<div>';
            //echo '<form action="#" method="post" id="confirmForm">';
            //echo  $order["order_id"];
            //echo '<input type="hidden" id="order_id" name="order_id" value="'.$order["order_id"].'">';
            echo '<h3><span class="text-'.$msg_color.'-500">'.$confrim_msg.'</span></h3><br>';
            echo '<a href="view_order_list.php?order_id='.$order["order_id"].'"><button type="button" id="btn_confrim_order" name="btn_confrim_order" class="items-center justify-self-auto px-4 py-2 mr-1 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-2 focus:ring-primary-300 dark:focus:ring-primary-900">Confirm Now</button></a>';
            echo '</div>';
            echo '<div>';
                //echo '<form action="#" method="post" id="confirmForm">';
                //echo  $order["order_id"];
                //echo '<input type="hidden" id="order_id" name="order_id" value="'.$order["order_id"].'">';
                echo '<h3><span class="text-'.$msg_color1.'-500">'.$deliver_msg.'</span></h3><br>';
                echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

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

        span {
            color: aquamarine;
        }
    </style>
</head>

<body
    class="h-max bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black">

    <!-- Navbar  -->
    <nav>
        <div
            class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto pt-10 px-2 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <div class="relative bottom-4">
                <a class="text-lg md:xl lg:text-2xl font-semibold font-heading text-yellow-200" href="admin_home.php">
                    <img class="h-8 md:h-10 lg:h-12 relative left-10 lg:left-16" src="../img/DDN_LOGO/ICON_non_bg.png"
                        alt="logo">
                    DDN MOBILE
                </a>
            </div>

            <div class=" justify-center content-center">
                <div>
                    <h1
                        class="self-center text-2xl md:text-3xl lg:text-4xl font-semibold whitespace-nowrap dark:text-white">
                        Order List</h1>
                </div>
            </div>

            <div>
                <a href="login.php">
                    <span class="self-center text-1xl font-semibold whitespace-nowrap dark:text-white"></span>
                </a>
            </div>
        </div>
    </nav>
    <hr class="mx-10 mb-1">

    <div class="px-10 py-10 container ">
        <!--SEARCH BAR-->
        <div class="px-20 justify-end flex">
            <form method="get" action="#">
                <div class="flex flex-auto">
                    <div>
                        <input type="search" placeholder="search by order code" id="txtSearchCode" name="txtSearchCode"
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
            </form>
        </div>
    </div>
    <hr class="mx-2 mt-4">
    
    <!--DISPLAY ORDERS-->
    <div class="px-4 py-10 container ">
        <?php display_selected_orders() ?>
    </div>
    <hr class="mx-8 mt-4 sm:mx-3 md:mx-2 lg:mx-2 xl:mx-2">
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
</div>


</body>

</html>