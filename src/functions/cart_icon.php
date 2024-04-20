<?php

include("./connection_db/dbconnect.php");

    function user_icon(){

        if (isset($_SESSION['customer_id'])) {
       
            echo '
            
                    <a class="flex items-center hover:text-gray-200" href="cart.php">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-yellow-200 text-gray-50"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>';
                        check_cart();
        echo'       </a>
                    <!-- Sign In / Register -->
                    <a class="userIcon flex items-center hover:text-gray-200" href="#" onclick="viewProfilCard()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-yellow-200 text-gray-50"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </a>
                
                
            ';
        }
         else {
            // User is not logged in, display "Login" link
            echo '
            <span class="flex text-yellow-100">
            <a class="flex items-center px-2 hover:text-yellow-200" href="login.php">
                Login
            </a>  
            <a class="flex items-center px-2 hover:text-yellow-200" href="register.php">
                  Signin
            </a>
            </span>
            ';
        }
    
    }

    function check_cart() {
        global $con;
        if (isset($_SESSION['customer_id'])) {
            $customer_id = $_SESSION['customer_id'];

            // Get shopping cart id
            $sql_get_shopping_cart_id = "SELECT shopping_cart_id FROM shopping_cart WHERE customer_id = ?";
            $stmt_get_shopping_cart_id = mysqli_prepare($con, $sql_get_shopping_cart_id);
            mysqli_stmt_bind_param($stmt_get_shopping_cart_id, 'i', $customer_id);
            mysqli_stmt_execute($stmt_get_shopping_cart_id);
            $result1 = mysqli_stmt_get_result($stmt_get_shopping_cart_id);

            // Check if there are any rows
            if (mysqli_num_rows($result1) > 0) {
                $row = mysqli_fetch_assoc($result1);
                $shopping_cart_id = $row["shopping_cart_id"];

                // Get cart item details
                $sql_get_cart_item_details = "SELECT cart_item_id FROM cart_item WHERE shopping_cart_id = ?";
                $stmt_get_cart_item_details = mysqli_prepare($con, $sql_get_cart_item_details);
                mysqli_stmt_bind_param($stmt_get_cart_item_details, 'i', $shopping_cart_id);
                mysqli_stmt_execute($stmt_get_cart_item_details);
                $result2 = mysqli_stmt_get_result($stmt_get_cart_item_details);

                // Check if there are any rows
                if (mysqli_num_rows($result2) > 0) {
                    echo 
                    '
                    <span class="flex absolute -mt-5 ml-4">
                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-pink-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-pink-500">
                        </span>
                    </span>
                    ';
                }
            }
        }
    }  

?>  