<?php
include("./connection_db/dbconnect.php");
include("./functions/cart_icon.php");
include("./functions/user_profile.php");
include("./functions/metaTags.php");
session_start();

    if(isset($_GET["order_id"])){
        $order_id = $_GET["order_id"];
    // echo $order_id;

        // Use prepared statement to prevent SQL injection
        $sql_update_order = "UPDATE oders SET oders_deliver_cus = 1 WHERE oders_id = ?";
        $stmt_update_order = mysqli_prepare($con, $sql_update_order);
        mysqli_stmt_bind_param($stmt_update_order, 'i', $order_id);
        mysqli_stmt_execute($stmt_update_order);

    }


    if (!isset($_SESSION['customer_id'])) {
        header("Location: login.php");
        exit;
    } else {
        $customer_id = $_SESSION['customer_id'];

        function displayOrders(){
            global $con, $customer_id;

            $sql_select_orders = "SELECT DISTINCT C.customer_id, O.oders_id, O.oder_date, O.oders_total_cost, O.oders_code, O.oders_confrim_admin, O.oders_deliver_cus, SA.recever_full_name, SA.address, SA.city, SA.province, SA.postal_code, SA.shipping_address_tele
                        FROM oders AS O
                        INNER JOIN oder_item AS OI ON O.oders_id = OI.oders_id
                        INNER JOIN items AS I ON I.item_id = OI.item_id
                        INNER JOIN shipping_address AS SA ON SA.shipping_address_id = O.shipping_address_id
                        INNER JOIN customer AS C ON C.customer_id = SA.customer_id WHERE C.customer_id = ?";

            $stmt_select_orders = mysqli_prepare($con, $sql_select_orders);
            mysqli_stmt_bind_param($stmt_select_orders, "i", $customer_id);
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
                    $deliver_msg = 'Confirme after deliver your order...';
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
                echo '</div>';
                echo '<div>';
                //echo '<form action="#" method="post" id="confirmForm">';
                //echo  $order["order_id"];
                //echo '<input type="hidden" id="order_id" name="order_id" value="'.$order["order_id"].'">';
                echo '<h3><span class="text-'.$msg_color1.'-500">'.$deliver_msg.'</span></h3><br>';
                echo '<a href="my_orders.php?order_id='.$order["order_id"].'"><button type="button" id="btn_confrim_order" name="btn_confrim_order" class="items-center justify-self-auto px-4 py-2 mr-1 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-2 focus:ring-primary-300 dark:focus:ring-primary-900">Confirm Now</button></a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            } 
    
        }
    
    }

    function display_selected_orders() {
            
         displayOrders();
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
        }

        /* Customize the scrollbar track */
        ::-webkit-scrollbar-track {
            background: black;
        }

        /* Customize the scrollbar thumb */
        ::-webkit-scrollbar-thumb {
            background: rgb(93, 4, 25);
            border-radius: 6px;
        }

        /* Customize the scrollbar thumb on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #70031c;
        }

        /* Customize the scrollbar button (top and bottom buttons) */
        ::-webkit-scrollbar-button {
            display: none;
        }

        span {
            color: aquamarine;
        }
    </style>
</head>

<body
    class="h-max bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black">

    <!-- Navbar -->
    <nav class="flex justify-end lg:justify-between w-screen">
        <div class="px-5 xl:px-12 py-4 flex w-full items-center">
            <a class="text-2xl font-semibold font-heading text-yellow-200" href="#">
                <img class="h-12" src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo"
                    style="position: relative; left: 58px;">
                DDN MOBILE
            </a>
            <!-- Nav Links -->
            <ul class="hidden md:flex px-4 mx-auto text-3xl font-semibold font-heading space-x-12 text-yellow-100">
                <li>
                    <h1 class="hover:text-pink-200">My Orders</h1>
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
    <div class="px-10 py-10 container ">

    </div>


    <!--DISPLAY ORDERS-->
    <div class="px-4 py-10 mx-8 container ">
        <?php display_selected_orders() ?>
    </div>
    <hr class="mx-8 mt-4 sm:mx-3 md:mx-2 lg:mx-2 xl:mx-2">
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
    </div>

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

    <script src="../JS/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1800,
        })
    </script>

</body>

</html>