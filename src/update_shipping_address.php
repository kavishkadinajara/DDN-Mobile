<?php
global $con;
    include("./connection_db/dbconnect.php");
    include("./functions/cart_icon.php");
    include("./functions/user_profile.php");
    include("./functions/metaTags.php");
    session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_POST["btn_update_address"])) {
    $customer_id = $_SESSION['customer_id'];

    $txtShippingAddressId = $_POST["txtShippingAddress"];
    $txtReceverName = $_POST["txtReceverName"];
    $txtReceverTele = $_POST["txtReceverTele"];
    $txtReceverAddress = $_POST["txtReceverAddress"];
    $txtCity = $_POST["txtCity"];
    $listProvince = $_POST["listProvince"];
    $txtPostalCode = $_POST["txtPostalCode"];

    $sql_update_shipping_address = "UPDATE shipping_address SET city = ?, province = ?, postal_code = ?, recever_full_name = ?, shipping_address_tele = ?, address = ? WHERE shipping_address_id = ?";
    
    $stmt_update_shipping_address = mysqli_prepare($con, $sql_update_shipping_address);
    
    // Check if the statement was prepared successfully
    if ($stmt_update_shipping_address) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt_update_shipping_address, "ssssssi", $txtCity, $listProvince, $txtPostalCode, $txtReceverName, $txtReceverTele, $txtReceverAddress, $txtShippingAddressId);
        
        if (mysqli_stmt_execute($stmt_update_shipping_address)) {
            header("Location: oder_checkOut.php");
            exit;
        } else {
            echo "Error updating address: " . mysqli_error($con);
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt_update_shipping_address);
    } else {
        echo "Error preparing the statement: " . mysqli_error($con);
    }
}

//TO GET DATA FOR UPDATE
    function update_shipping_address($shipping_address_id){
        global $con;
        $customer_id = $_SESSION['customer_id'];

        $sql_get_address_data = "SELECT * FROM shipping_address WHERE shipping_address_id = ?";
        $stmt_get_address_data = mysqli_prepare($con, $sql_get_address_data);
        mysqli_stmt_bind_param($stmt_get_address_data, "i", $shipping_address_id);
        mysqli_stmt_execute($stmt_get_address_data);
        $result_get_address_data = mysqli_stmt_get_result($stmt_get_address_data);
        $row = mysqli_fetch_assoc($result_get_address_data);
    
        // Retrieve address data
        $city = $row["city"];
        $province = $row["province"];
        $postal_code = $row["postal_code"];
        $recever_full_name = $row["recever_full_name"];
        $shipping_address_tele = $row["shipping_address_tele"];
        $address = $row["address"];
    
        
        
                echo'   <input type="hidden"  value="'.$shipping_address_id.'" name="txtShippingAddress" id="txtShippingAddress">
                        <div>
                            <label class="block text-gray-500">Name</label>
                            <input type="text" value="'.$recever_full_name.'" name="txtReceverName" id="txtReceverName" placeholder="Receiver Name"
                                class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                                autofocus autocomplete>
                            <p><span class="text-sm text-red-600 ml-4" id="ErrorReceverName" name="ErrorReceverName"></span></p>
                        </div>

                        <div>
                            <label class="block text-gray-500">Telephone</label>
                            <input type="text" value="'.$shipping_address_tele.'" name="txtReceverTele" id="txtReceverTele" placeholder="Receiver Telephone"
                                class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                                autofocus autocomplete>
                            <p><span class="text-sm text-red-600 ml-4" id="ErrorReceverTele" name="ErrorReceverTele"></span></p>
                        </div>

                        <div>
                            <label class="block text-gray-500">Address</label>
                            <input type="text" value="'.$address.'" name="txtReceverAddress" id="txtReceverAddress" placeholder="Receiver Address"
                                class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                                autofocus autocomplete>
                            <p><span class="text-sm text-red-600 ml-4" id="ErrorReceverAddress"
                                    name="ErrorReceverAddress"></span></p>
                        </div>

                        <div>
                            <label class="block text-gray-500">City</label>
                            <input type="text" value="'.$city.'" name="txtCity" id="txtCity" placeholder="City"
                                class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                                autofocus autocomplete>
                            <p><span class="text-sm text-red-600 ml-4" id="ErrorCity" name="ErrorCity"></span></p>
                        </div>

                        <div>
                            <label class="block text-gray-500">Province</label>
                            <select name="listProvince" id="listProvince"
                                class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900">
                                <option class="text-black" value="0">Select province</option>
                                <option selected class="text-black" value="'.$province.'">'.$province.'</option>
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
                            <input type="text" value="'.$postal_code.'" name="txtPostalCode" id="txtPostalCode" placeholder="Postal Code"
                                class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900 mt-2 border focus-border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900"
                                autofocus autocomplete>
                            <p><span class="text-sm text-red-800 ml-4" id="ErrorPostalCode" name="ErrorPostalCode"></span></p>
                        </div>
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
            width: 12px; /* Set the width of the scrollbar */
        }

        /* Customize the scrollbar track */
        ::-webkit-scrollbar-track {
            /* Set the color of the track */
           
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
                    <h1 class="hover:text-pink-200">ConFrim My Order</h1>
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

    <!-- Update Address -->
    <section style="display: block;" id="update_shipping_add">
        <div
            class="text-yellow-100 absolute w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-36 left-14 md:left-20 lg:left-36 xl:left-44 bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-purple-900 to-slate-900">
            <!-- FORM -->
            <form method="post" action="#" id="frmUpdateShippingAddress" onsubmit="return validateShippingAddressForm()"
                class="container grid grid-cols-1 lg:grid-cols-2 m-5">
                <input type="hidden" name="shipping_address_id" id="shipping_address_id" value="31">

                <?php                        
                if(isset($_GET["shipping_address_id"])){
                    $shipping_address_id = $_GET["shipping_address_id"];
                    update_shipping_address($shipping_address_id);
                }
            ?>

                <div class="ml-2 mb-4 flex gap-4">
                    <button type="submit" id="btn_update_address" name="btn_update_address"
                        class=" block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus-ring-4 focus-ring-primary-300 dark-focus-ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-14 py-3 mt-6">Update
                    </button>
                    <a href="oder_checkOut.php">
                        <button type="button"
                            class=" block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus-ring-4 focus-ring-primary-300 dark-focus-ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-14 py-3 mt-6">Cancel
                        </button>
                    </a>
                </div>
            </form>
        </div>
    </section>

    <script src="../JS/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1800,
        })
    </script>
</body>

</html>