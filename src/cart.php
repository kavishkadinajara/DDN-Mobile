<?php
    include("./connection_db/dbconnect.php");
    include("./functions/user_profile.php");
    include("./functions/cart_icon.php");
    include("./functions/metaTags.php");
    session_start();
    if (!isset($_SESSION['customer_id'])) {
        header("Location: login.php");
        exit;
    }

    function display_cart_item() {
        global $con;
        $cart_msg = " ";
        $total_quntity = 0;
        $total_Item_cost = 0;
        $final_total_cost = 0;
        
        if (!isset($_SESSION['customer_id'])) {
            header("Location: login.php");
            exit; // Add an exit to stop executing further code
        } else {
            $customer_id = $_SESSION['customer_id'];

            // Get shopping cart id
            $sql_get_shopping_cart_id = "SELECT shopping_cart_id FROM shopping_cart WHERE customer_id = ?";
            $stmt_get_shopping_cart_id = mysqli_prepare($con, $sql_get_shopping_cart_id);
            mysqli_stmt_bind_param($stmt_get_shopping_cart_id, 'i', $customer_id);
            mysqli_stmt_execute($stmt_get_shopping_cart_id);
            $result1 = mysqli_stmt_get_result($stmt_get_shopping_cart_id);

            $shopping_cart_id = null; // Initialize shopping_cart_id

            while ($row = mysqli_fetch_assoc($result1)) {
                $shopping_cart_id = $row["shopping_cart_id"];
            }

            if (empty($shopping_cart_id)) {
                $cart_msg = "No Shopping Items Yet...";
                echo '<div>
                    <h1 class="text-3xl text-red-700 font-semibold text-center mb-20 mt-28"> ' . $cart_msg . '  </h1>
                </div>';
            } else {
                

                $sql_get_cart_item_details = "SELECT item_img_url1,item_discount_rate, item_name, brand_name,cart_item_id, cart_item_color, item_price, cart_item_quntity FROM cart_item CI JOIN items I ON CI.item_id = I.item_id JOIN brand B ON I.brand_id = B.brand_id WHERE shopping_cart_id = ?";
                
                $stmt_get_cart_item_details = mysqli_prepare($con, $sql_get_cart_item_details);
                mysqli_stmt_bind_param($stmt_get_cart_item_details, 'i', $shopping_cart_id);
                mysqli_stmt_execute($stmt_get_cart_item_details);
                $result2 = mysqli_stmt_get_result($stmt_get_cart_item_details);

                echo '
                    <div class="flex justify-between border-b pb-8 text-yellow-200">
                        <h1 class="font-semibold text-xl">Shopping Cart</h1>
                       
                    </div>
                    <div class="flex mt-10 mb-5 font-semibold text-white text-xs uppercase">
                        <h3 class="w-2/5 text-center">Product Details</h3>
                        <h3 class="w-1/5 text-center">Quantity</h3>
                        <h3 class="w-1/5 text-center">Price</h3>
                        <h3 class="w-1/5 text-center">Total</h3>
                    </div>
                    <hr>
                ';

                $count_cart_ids = null;

                while ($row = mysqli_fetch_assoc($result2)) {
                    $item_img_url1 = $row["item_img_url1"];
                    $item_discount_rate = $row["item_discount_rate"];
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

                    $count_cart_ids = $count_cart_ids + $cart_item_id;

                    

                    
                    if ( $cartIsEmpty = empty($cart_item_id)) {
                        $cart_msg = "No Shopping Items Yet...";
                        echo '<div>
                            <h1 class="text-3xl text-red-700 font-semibold text-center mb-20 mt-28"> ' . $cart_msg . '</h1>
                        </div>';
                    } else{ 
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
                                    <a href="remove_item.php?cart_item_id='.$cart_item_id.'" class="font-semibold hover:text-red-700 text-red-400 text-xs">Remove</a>
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
                    
                }
            }
            $cartIsEmpty = empty($cart_item_id);

            echo '
            <a href="items.php" class="flex font-semibold text-indigo-600 text-sm mt-10">
                <svg class="fill-current mr-2 text-indigo-600 w-4" viewBox="0 0 448 512">
                    <path
                        d="M134.059 296H436c6.627 0 12-5.373 12-12v-56c0-6.627-5.373-12-12-12H134.059v-46.059c0-21.382-25.851-32.09-40.971-16.971L7.029 239.029c-9.373 9.373-9.373 24.569 0 33.941l86.059 86.059c15.119 15.119 40.971 4.411 40.971-16.971V296z" />
                </svg>
                Continue Shopping
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
            <a href="oder_checkOut.php?shopping_cart_id='.$shopping_cart_id.'">
                <button
                    class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6" ' . ($cartIsEmpty ? 'disabled' : '') . '>Checkout</button>
            </a>
        ';
        }
    }
//display_cart_item(); // Call the function to display the cart items.
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
            width: 12px; /* Set the width of the scrollbar */
        }

        /* Customize the scrollbar track */
        ::-webkit-scrollbar-track {
            /* Set the color of the track */
            background: black;
        }

        /* Customize the scrollbar thumb */
        ::-webkit-scrollbar-thumb {
            background: rgb(93, 4, 25); /* Set the color of the thumb */
            border-radius: 6px; /* Round the corners of the thumb */
        }

        /* Customize the scrollbar thumb on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #70031c; /* Change thumb color on hover */
        }

        /* Customize the scrollbar button (top and bottom buttons) */
        ::-webkit-scrollbar-button {
            display: none; /* Hide the scroll buttons */
        }

    </style>
</head>

<body
    class="bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 w-screen"
    data-aos="fade-zoom-in" data-aos-easing="ease-in-back" data-aos-delay="300" data-aos-offset="0">

    <!-- Navbar -->
    <nav class="flex justify-end lg:justify-between w-screen" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="px-5 xl:px-12 py-4 flex w-full items-center" data-aos="zoom-in">
            <a class="text-2xl font-semibold font-heading text-yellow-200" href="index.php">
                <img class="h-12" src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo"
                    style="position: relative; left: 58px;">
                DDN MOBILE
            </a>
            <!-- Nav Links -->
            <ul class="hidden md:flex px-4 mx-auto text-3xl font-semibold font-heading space-x-12 text-yellow-100">
                <li>
                    <h1 class="hover:text-pink-200">My Cart</h1>
                </li>

            </ul>
            <!-- Header Icons -->
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

                <!--DISPLAY PRODUCT-->
                <?php display_cart_item() ?>

            </div>
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