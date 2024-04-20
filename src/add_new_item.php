<?php
include("./connection_db/dbconnect.php");
global $con;

if (isset($_POST["btn_new_item"])) {
    // Get other form data
    $name = $_POST['txtItemName'];
    $quantity = $_POST["txtStockQuantity"];
    $description = $_POST['txtDiscription'];
    $keyWords = $_POST['txtKeyWords'];
    $price = $_POST['txtPrice'];
    $brand = $_POST['listBrand'];
    $category = $_POST['listCategory'];
    $discountRate = $_POST['txtDiscountRate'];

    // Insert the item information into the database
    $sql_insert_item = "INSERT INTO items (item_name, item_stock_quantity, item_discription, item_keywords, item_price, brand_id, categories_id, item_discount_rate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_item = mysqli_prepare($con, $sql_insert_item);
    mysqli_stmt_bind_param($stmt_insert_item, "sissdiid", $name, $quantity, $description, $keyWords, $price, $brand, $category, $discountRate);
    mysqli_stmt_execute($stmt_insert_item);
    mysqli_stmt_close($stmt_insert_item);

    // Get the last inserted item ID
    $lastInsertedItemId = mysqli_insert_id($con);

    if (isset($_POST['color_ids']) && is_array($_POST['color_ids'])) {
        $color_ids = $_POST['color_ids'];

        // Insert the selected colors into a separate table (e.g., 'item_colors')
        foreach ($color_ids as $color_id) {
            $sql_insert_color = "INSERT INTO item_color (item_id, color_id) VALUES (?, ?)";
            $stmt_insert_color = mysqli_prepare($con, $sql_insert_color);
            mysqli_stmt_bind_param($stmt_insert_color, "ii", $lastInsertedItemId, $color_id);
            mysqli_stmt_execute($stmt_insert_color);
            mysqli_stmt_close($stmt_insert_color);

            echo $color_id;
        }
    }
    // Handle image uploads
   
    
    if (isset($_FILES['file_input'])) {
        $imageNames = array();
        $uploadDirectory = "../img/item_imgs/"; // Change to your desired directory
        $fileCount = count($_FILES['file_input']['name']);
    
        for ($i = 0; $i < $fileCount; $i++) {
            $imageName = $_FILES['file_input']['name'][$i];
            $tempFilePath = $_FILES['file_input']['tmp_name'][$i];
            $targetFilePath = $uploadDirectory . $imageName;
    
            if (move_uploaded_file($tempFilePath, $targetFilePath)) {
                // Image was successfully uploaded, add the filename to the array
                $imageNames[] = $imageName;
            } else {
                // Handle the error if the upload fails
                echo "Error uploading image: " . $imageName;
            }
            // Insert the image names into the database in separate columns (img1, img2, img3)
            $sql_insert_images = "UPDATE items SET item_img_url" . ($i + 1) . " = ? WHERE item_id = ?";
            $stmt_insert_images = mysqli_prepare($con, $sql_insert_images);
            mysqli_stmt_bind_param($stmt_insert_images, "si", $imageName, $lastInsertedItemId);
            mysqli_stmt_execute($stmt_insert_images);
            mysqli_stmt_close($stmt_insert_images);
        }
    
        // update image names into a separate table
        
          /*  for ($j = 1; $j <= 3; $j++) {
                $sql_insert_images = "UPDATE items SET item_img_url{$j} = ? WHERE item_id = ?";
                $stmt_insert_images = mysqli_prepare($con, $sql_insert_images);
                mysqli_stmt_bind_param($stmt_insert_images, "si", $imageName, $lastInsertedItemId);
                mysqli_stmt_execute($stmt_insert_images);

                echo $imageName;
            }*/
    }
}

// Handle image uploads




echo'

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
    class="h-fit bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black">

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
                        Add New Item</h1>
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

    <section class="mt-10 " id="add_new_item">
        <div
            class="text-yellow-100 relative w-3/4 md:w-3/4 lg:2/3 xl:1/2 top-16 left-16 md:left-20 lg:left-36 xl:left-44 ">
            <!-- FORM -->
            <form action="#" method="post" enctype="multipart/form-data" id="frmNewItem" onsubmit="return validateNewItemForm()">
                <div class="container grid grid-cols-1 lg:grid-cols-2 m-5">
                    <!--ITEM NAME-->
                    <div>
                        <label class="block text-gray-500">Item Name</label>
                        <input type="text" name="txtItemName" id="txtItemName" placeholder="Item Name"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorItemName" name="ErrorItemName"></span></p>
                    </div>
                    <!--QUANTITY-->
                    <div>
                        <label class="block text-gray-500">Stock Quantity</label>
                        <input type="text" name="txtStockQuantity" id="txtStockQuantity"
                            placeholder="Item Stock Quantity"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorStockQuantity"
                                name="ErrorStockQuantity"></span>
                        </p>
                    </div>
                    <!--DISCRIPTION-->
                    <div class="row-span-3">
                        <label class="block text-gray-500"> Discription</label>
                        <textarea rows="4" type="range" name="txtDiscription" id="txtDiscription"
                            placeholder="Item Discription"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete> </textarea>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorDiscription" name="ErrorDiscription"></span>
                        </p>
                    </div>
                    <!--KEY WORDS-->
                    <div class="row-span-3">
                        <label class="block text-gray-500"> Key Words</label>
                        <textarea rows="4" type="range" name="txtKeyWords" id="txtKeyWords" placeholder="Item Key Words"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete> </textarea>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorKeyWords" name="ErrorKeyWords"></span></p>
                    </div>
                    <!--PRICE-->
                    <div>
                        <label class="block text-gray-500">Price</label>
                        <input type="text" name="txtPrice" id="txtPrice" placeholder="Item Price"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorPrice" name="ErrorPrice"></span></p>
                    </div>
                    <!--DISCOUNT RATE-->
                    <div>
                        <label class="block text-gray-500">Discount Rate</label>
                        <input type="text" name="txtDiscountRate" id="txtDiscountRate" placeholder="Item Discount Rate"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-600 ml-4" id="ErrorDiscountRate"
                                name="ErrorDiscountRate"></span>
                        </p>
                    </div>
                    <!--BRAND -->
                    <div>
                        <label class="block text-gray-500">Brand</label>
                        <select name="listBrand" id="listBrand"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900">
                            <option class="text-black" value="0">Select Brand</option> ';
                            
                                
                                $sql_select_brand = 'SELECT brand_id, brand_name FROM brand';
                                $stmt_select_brand = mysqli_prepare($con, $sql_select_brand);
                                mysqli_stmt_execute($stmt_select_brand);
                                $result_select_brand = mysqli_stmt_get_result($stmt_select_brand);
                                while ($row = mysqli_fetch_assoc($result_select_brand)) {
                                    $brand_id = $row["brand_id"];
                                    $brand_name = $row["brand_name"];
                                    echo '
                                            <option class="text-black" value="'.$brand_id.'">'.$brand_name.'</option>
                                         ';
                                }     
            echo '               
                        </select>
                        <p><span class="text-sm text-red-800 ml-4" id="ErrorBrand" name="ErrorBrand"></span></p>
                    </div>
                    <!--CATEGORY -->
                    <div>
                        <label class="block text-gray-500">Catrgory</label>
                        <select name="listCategory" id="listCategory"
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900">
                            <option class="text-black" value="0">Select Catrgory</option>';
                            
                                $sql_select_category = 'SELECT categories_id, categories_name FROM categories';
                                $stmt_select_category = mysqli_prepare($con, $sql_select_category);
                                mysqli_stmt_execute($stmt_select_category);
                                $result_select_category = mysqli_stmt_get_result($stmt_select_category);
                                while ($row = mysqli_fetch_assoc($result_select_category)) {
                                    $categories_id = $row["categories_id"];
                                    $categories_name = $row["categories_name"];
                                    echo '
                                            <option class="text-black" value="'.$categories_id.'">'.$categories_name.'</option>
                                         ';
                                }
                echo '            
                        </select>
                        <p><span class="text-sm text-red-800 ml-4" id="ErrorCatrgory" name="ErrorCatrgory"></span></p>
                    </div>

                    <!-- ITEM IMAGES -->
                    <div>
                        <label class="block text-gray-500" for="file_input">Upload file</label>
                        <input
                            class="w-3/4 px-4 py-3 rounded-3xl bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[radial-gradient(ellipse_at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900"
                            id="file_input" name="file_input[]" type="file" accept=".png, .jpeg, .jpg" multiple
                            onchange="checkFileCount(this);">
                        <p><span class="text-sm text-red-800 ml-4" id="ErrorImage" name="ErrorImage"></span></p>
                    </div>

                    <!-- ITEM COLOR -->
                    <div>
                        <label class="block text-gray-500" for="color">Select Colors</label>
                        <div class="flex flex-wrap mb-4" id="color_ids">
                            ';
                            $sql_select_color = 'SELECT color_id, color_code FROM colors';
                            $stmt_select_color = mysqli_prepare($con, $sql_select_color);

                            if ($stmt_select_color) {
                                mysqli_stmt_execute($stmt_select_color);
                                $result_select_color = mysqli_stmt_get_result($stmt_select_color);

                                if ($result_select_color) {
                                    while ($row = mysqli_fetch_assoc($result_select_color)) {
                                        $color_id = $row["color_id"];
                                        $color_code = $row["color_code"];
                                        echo '
                                            <div class="flex items-center me-4">
                                                <input id="color_' . $color_id . '" name="color_ids[]" type="checkbox" value="' . $color_id . '"
                                                    class="w-4 h-4 accent-pink-500 text-'.$color_code.'-600 bg-'.$color_code.'-600 border-'.$color_code.'-600 rounded focus:ring-'.$color_code.'-500 dark:focus:ring-'.$color_code.'-600 dark:ring-offset-'.$color_code.'-800 focus:ring-2 dark:bg-'.$color_code.'-700 dark:border-'.$color_code.'-600">
                                                <label for="color_' . $color_id . '" class="ms-2 text-sm font-medium">' . $color_code . '</label>
                                            </div>
                                        ';
                                    }
                                } else {
                                    echo "Error in fetching the result: " . mysqli_error($con);
                                }
                            } else {
                                echo "Error in preparing the statement: " . mysqli_error($con);
                            }
                        
            echo '            </div>
                        <p><span class="text-sm text-red-800 ml-4" id="ErrorColor" name="ErrorColor"></span></p>
                    </div>
                </div>

                <!-- ITEM CAPACITY -->
               <!-- <div>
                    <label class="block text-gray-500" for="color">Select Storage Capacity</label>
                    <div class="flex grid-cols-6 flex-wrap mb-4" id="color_ids">
                        ';
                        $sql_select_capacity = 'SELECT capacity_id, capacity_storage FROM capacity';
                        $stmt_select_capacity = mysqli_prepare($con, $sql_select_capacity);

                        if ($stmt_select_capacity) {
                            mysqli_stmt_execute($stmt_select_capacity);
                            $result_select_capacity = mysqli_stmt_get_result($stmt_select_capacity);

                            if ($result_select_capacity) {
                                while ($row = mysqli_fetch_assoc($result_select_capacity)) {
                                    $capacity_id = $row["capacity_id"];
                                    $capacity_storage = $row["capacity_storage"];
                                    echo '
                                        <div class="flex  items-center me-4">
                                            <input id="color_' . $capacity_id . '" name="color_ids[]" type="checkbox" value="' . $capacity_id . '"
                                                class="w-4 h-4 text-yellow-200 bg-yellow-600 border-green-600 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-green-800 focus:ring-2 dark:bg-green-700 dark:border-green-600">
                                            <label for="color_' . $capacity_id . '" class="ms-2 text-sm font-medium">' . $capacity_storage . '</label>
                                        </div>
                                    ';
                                }
                            } else {
                                echo "Error in fetching the result: " . mysqli_error($con);
                            }
                        } else {
                            echo "Error in preparing the statement: " . mysqli_error($con);
                        }
                    
        echo '            </div>
                    <p><span class="text-sm text-red-800 ml-4" id="ErrorColor" name="ErrorColor"></span></p>
                </div> -->

                <div class=" container ml-2 mb-4 flex gap-4 justify-start lg:justify-end">
                    <button type="submit" id="btn_new_item" name="btn_new_item"
                        class="inline-flex items-start justify-self-auto px-12 py-3 mr-2 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Submit</button>
                    
                <a href="admin_home.php">        
                    <button type="button" id="btn_cancel_address" name="btn_cancel_address"
                        class="inline-flex items-start justify-self-auto px-12 py-3 mr-2 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900">Cancel</button>
                <a/>
                </div>
            </form>
        </div>        
    </section>

    ';

    ?>

    <div class="m-16 top-5">
        <hr class="relative mt-6 p-8 top-16">
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


    <script>
       function validateNewItemForm() {
            let isValid = true;

            // Clear previous error messages
            const errorElements = document.querySelectorAll('[id^=Error]');
            errorElements.forEach((element) => {
                element.textContent = '';
            });

            // Item Name validation
            const itemName = document.getElementById('txtItemName').value.trim();
            if (itemName === '') {
                document.getElementById('ErrorItemName').textContent = 'Item Name is required';
                isValid = false;
            } else if (itemName.length < 3) {
                document.getElementById('ErrorItemName').textContent = 'Item Name should be at least 3 characters';
                isValid = false;
            }

            // Stock Quantity validation
            const stockQuantity = document.getElementById('txtStockQuantity').value.trim();
            if (stockQuantity === '') {
                document.getElementById('ErrorStockQuantity').textContent = 'Stock Quantity is required';
                isValid = false;
            } else if (isNaN(stockQuantity) || stockQuantity <= 0) {
                document.getElementById('ErrorStockQuantity').textContent = 'Enter a valid Stock Quantity';
                isValid = false;
            }

            var description = document.getElementById("txtDiscription");
            var keywords = document.getElementById("txtKeyWords");
            var errorDescription = document.getElementById("ErrorDiscription");
            var errorKeywords = document.getElementById("ErrorKeyWords");
            errorDescription.textContent = "";
            errorKeywords.textContent = "";

            // Validation for Description
            if (description.value.trim() === "") {
                errorDescription.textContent = "Description is required.";
                isValid = false;
            }

            // Validation for Keywords
            if (keywords.value.trim() === "") {
                errorKeywords.textContent = "Keywords are required.";
                isValid = false;
            }

            // Price validation
            const price = document.getElementById('txtPrice').value.trim();
            if (price === '') {
                document.getElementById('ErrorPrice').textContent = 'Price is required';
                isValid = false;
            } else if (isNaN(price) || price <= 0) {
                document.getElementById('ErrorPrice').textContent = 'Enter a valid Price';
                isValid = false;
            }

            // Discount Rate validation
            const discountRate = document.getElementById('txtDiscountRate').value.trim();
            if (discountRate === '') {
                document.getElementById('ErrorDiscountRate').textContent = 'Discount Rate is required';
                isValid = false;
            } else if (isNaN(discountRate) || discountRate < 0 || discountRate > 100) {
                document.getElementById('ErrorDiscountRate').textContent = 'Enter a valid Discount Rate (0-100)';
                isValid = false;
            }

            // Brand validation
            const selectedBrand = document.getElementById('listBrand').value;
            if (selectedBrand === '0') {
                document.getElementById('ErrorBrand').textContent = 'Select a Brand';
                isValid = false;
            }

            // Category validation
            const selectedCategory = document.getElementById('listCatrgory').value;
            if (selectedCategory === '0') {
                document.getElementById('ErrorCatrgory').textContent = 'Select a Category';
                isValid = false;
            }

            // Item Image validation
            const fileInput = document.getElementById('file_input');
            if (fileInput.files.length === 0) {
                document.getElementById('ErrorImage').textContent = 'Upload at least one Image';
                isValid = false;
            }

            // Color validation
            const selectedColors = document.querySelectorAll('input[type=checkbox]:checked');
            if (selectedColors.length === 0) {
                document.getElementById('ErrorColor').textContent = 'Select at least one Color';
                isValid = false;
            }

            return isValid;
        }


        var ErrorImage = document.getElementById('ErrorImage'); // Fix the variable name

        function checkFileCount(inputElement) {
            const maxFiles = 3;

            if (inputElement.files.length > maxFiles) {
                ErrorImage.textContent =
                    `You can only select a maximum of ${maxFiles} files`; // Use backticks for string interpolation
                inputElement.value = ''; // Clear the input
            }
        }
    </script>


</body>

</html>