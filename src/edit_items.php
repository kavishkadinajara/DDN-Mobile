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
                        Edit Item Details</h1>
                </div>
            </div>

            <div>
                <a href="login.php">
                    <span class="self-center text-1xl font-semibold whitespace-nowrap dark:text-white"></span>
                </a>
            </div>
        </div>
    </nav>
    <hr class="mx-10 mb-4">

    <?php
include("./connection_db/dbconnect.php");
global $con;

if(isset($_GET["item_id"])){
    $item_id = $_GET["item_id"];
    $sql_select_item_details = "SELECT * FROM items WHERE item_id = ?";
    $stmt_select_item_details = mysqli_prepare($con, $sql_select_item_details);
    mysqli_stmt_bind_param($stmt_select_item_details, "i", $item_id);
    mysqli_stmt_execute($stmt_select_item_details);
    $result_select_item_details = mysqli_stmt_get_result($stmt_select_item_details);
    $row = mysqli_fetch_assoc($result_select_item_details);

    $item_name = $row["item_name"];
    $item_stock_quantity = $row["item_stock_quantity"];
    $item_discription = $row["item_discription"];
    $item_keywords = $row["item_keywords"];
    $item_price = $row["item_price"];
    $item_discount_rate = $row["item_discount_rate"];
}

if(isset($_POST["btn_edit_item"])){

    $quantity = $_POST["txtStockQuantity"];
    $description = $_POST['txtDiscription'];
    $keyWords = $_POST['txtKeyWords'];
    $price = $_POST['txtPrice'];
    $discountRate = $_POST['txtDiscountRate'];

    $sql_update_item = "UPDATE items SET item_stock_quantity = ?, item_discription = ?, item_keywords = ?, item_price = ?, item_discount_rate = ? WHERE item_id = ?";

    $stmt_update_item = mysqli_prepare($con, $sql_update_item);
    
    // Check if the statement was prepared successfully
    if ($stmt_update_item) {
        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt_update_item, "issddi", $quantity, $description, $keyWords, $price, $discountRate, $item_id);
        
        if (mysqli_stmt_execute($stmt_update_item)) {
            header("Location: admin_ home.php");
            exit;
        } else {
            echo "Error updating address: " . mysqli_error($con);
        }
        
        // Close the prepared statement
        mysqli_stmt_close($stmt_update_item);
    } else {
        echo "Error preparing the statement: " . mysqli_error($con);
    }
}




echo'
    <section class="mt-10 h-max">
        <div
            class="text-yellow-100 relative w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-16 left-16 md:left-20 lg:left-36 xl:left-44 ">
            <!-- FORM -->
            <form action="#" method="post" enctype="multipart/form-data" id="frmEditItem" onsubmit="return validateEditItemForm()">
                <div class="container grid grid-cols-1 lg:grid-cols-2 m-5">
                    <!--ITEM NAME-->
                    <div>
                        <label class="block text-gray-500">Item Name</label>
                        <input type="text" name="txtItemName" id="txtItemName" placeholder="Item Name" value="'.$item_name.'"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete readonly>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorItemName" name="ErrorItemName"></span></p>
                    </div>
                    <!--QUANTITY-->
                    <div>
                        <label class="block text-gray-500">Stock Quantity</label>
                        <input type="text" name="txtStockQuantity" id="txtStockQuantity"
                            placeholder="Item Stock Quantity" value="'.$item_stock_quantity.'"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorStockQuantity"
                                name="ErrorStockQuantity"></span>
                        </p>
                    </div>
                    <!--DISCRIPTION-->
                    <div class="row-span-3">
                        <label class="block text-gray-500"> Discription</label>
                        <textarea rows="4" type="range" name="txtDiscription" id="txtDiscription" value=
                            placeholder="Item Discription"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete> '.$item_discription.' </textarea>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorDiscription" name="ErrorDiscription"></span>
                        </p>
                    </div>
                    <!--KEY WORDS-->
                    <div class="row-span-3">
                        <label class="block text-gray-500"> Key Words</label>
                        <textarea rows="4" type="range" name="txtKeyWords" id="txtKeyWords" placeholder="Item Key Words"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete> '.$item_keywords.' </textarea>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorKeyWords" name="ErrorKeyWords"></span></p>
                    </div>
                    <!--PRICE-->
                    <div>
                        <label class="block text-gray-500">Price</label>
                        <input type="text" name="txtPrice" id="txtPrice" placeholder="Item Price" value="'.$item_price.'"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorPrice" name="ErrorPrice"></span></p>
                    </div>
                    <!--DISCOUNT RATE-->
                    <div>
                        <label class="block text-gray-500">Discount Rate</label>
                        <input type="text" name="txtDiscountRate" id="txtDiscountRate" placeholder="Item Discount Rate" value="'.$item_discount_rate.'"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorDiscountRate"
                                name="ErrorDiscountRate"></span>
                        </p>
                    </div>
                </div>
            
                <div class=" container ml-2 mb-4 flex gap-4 justify-start lg:justify-end">
                    <button type="submit" id="btn_edit_item" name="btn_edit_item"
                        class="inline-flex items-start justify-self-auto px-12 py-3 mr-2 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Submit</button>

                    <a href="admin_home.php"><button type="button" id="btn_cancel_address" name="btn_cancel_address"
                        class="inline-flex items-start justify-self-auto px-12 py-3 mr-2 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Cancel</button></a>
                </div>
            </form>
        </div>
    </section> ';
?>
    <div class="m-16 top-5">
        <hr class="relative mt-6 p-8 top-16">
    </div>
</body>

</html>