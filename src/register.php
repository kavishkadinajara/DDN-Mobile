<?php
$msgShow = ''; // Initialize an empty message variable

if (isset($_POST["btnSaveCustomer"])) {
    // Accept Data
    $CustomerName = $_POST["txtCustomerName"];
    $CustomerEmail = $_POST["txtCustomerEmail"];
    $CustomerTele = $_POST["txtCustomerTele"];
    $CustomerPassword = $_POST["txtCustomerPW"];
    $CustomerConPassword = $_POST["txtCustomerCPW"];

    if ($CustomerPassword == $CustomerConPassword) {
        // Create a hash of the password
        $hashedPassword = password_hash($CustomerPassword, PASSWORD_DEFAULT);

        // Establish a connection to the database (improved security)
        include("./connection_db/dbconnect.php");

        // Check if the email already exists in the 'customer' table (use prepared statement)
        $checkEmailQuery = "SELECT COUNT(*) FROM customer WHERE customer_email = ?";
        if ($stmtCheckEmail = mysqli_prepare($con, $checkEmailQuery)) {
            mysqli_stmt_bind_param($stmtCheckEmail, "s", $CustomerEmail);
            mysqli_stmt_execute($stmtCheckEmail);
            mysqli_stmt_bind_result($stmtCheckEmail, $count);
            mysqli_stmt_fetch($stmtCheckEmail);

            if ($count > 0) {
                $msgShow .= "Email is already registered.<br>";
                mysqli_stmt_close($stmtCheckEmail);
            } else {
                mysqli_stmt_close($stmtCheckEmail);

                // Use prepared statements to insert data into the 'customer' table
                $sqlInsertCustomer = "INSERT INTO customer (customer_name, customer_tele, customer_email, customer_password) VALUES (?, ?, ?, ?)";

                if ($stmt1 = mysqli_prepare($con, $sqlInsertCustomer)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt1, "ssss", $CustomerName, $CustomerTele, $CustomerEmail, $hashedPassword);

                    // Execute the prepared statement
                    if (mysqli_stmt_execute($stmt1)) {
                        $msgShow .= "Registration successful.<br>";
                        header("Location: login.php");
                        exit; // Exit after redirect
                    } else {
                        $msgShow .= "Error: " . mysqli_error($con) . "<br>";
                    }
                    // Close the first prepared statement
                    mysqli_stmt_close($stmt1);
                } else {
                    $msgShow .= "Error: " . mysqli_error($con) . "<br>";
                }
            }
        } else {
            $msgShow .= "Error: " . mysqli_error($con) . "<br>";
        }

        // Close the database connection
        mysqli_close($con);
    } else {
        $msgShow .= "Passwords do not match.<br>";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDN MOBILE | REGISTER</title>
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

<body class="bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black">
    <div data-aos="fade-down-left" style="width: 300px;height: 280px;position: absolute; top: 70px; left: 1200px;"
        class="color-patch-blur absolute  w-16 rounded-full rotate- bg-gradient-to-b from-green-600 to-blue-600 blur-0xl mx-auto scale-y-150 opacity-100">
    </div>


    <section class=" h-full">
        <div class="container">
            <div class="g-6 flex h-full flex-wrap items-center justify-center text-neutral-800 dark:text-neutral-200">
                <div class="w-full">
                    <div class="block rounded-lg">
                        <div class="g-0 lg:flex lg:flex-wrap">
                            <!-- Left column container-->
                            <div class="px-4 md:px-0 lg:w-6/12">
                                <div class="md:mx-6 md:p-12">

                                    <!--------------CUSTOMER------------->
                                    <form id="frmRegisterCustomer" method="post" action="#"
                                        onsubmit="return validateCustomerRegistration()" style="display: block;">
                                        <p class="mb-8">Create an account</p>
                                        <!--Name-->
                                        <div>
                                            <label class="block text-gray-500">Name</label>
                                            <input type="text" name="txtCustomerName" id="txtCustomerName"
                                                placeholder="Enter Name "
                                                class="w-full px-4 py-3 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 "
                                                autofocus autocomplete>
                                            <p><span class="text-sm text-red-800 ml-4" id="ErrorCustomerName"
                                                    name="UNErrorCustomerName"></span></p>
                                        </div>
                                        <!--Email-->
                                        <div>
                                            <label class="block text-gray-500">Email</label>
                                            <input type="text" name="txtCustomerEmail" id="txtCustomerEmail"
                                                placeholder="Enter Email Address"
                                                class="w-full px-4 py-3 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 "
                                                autofocus autocomplete>
                                            <p><span class="text-sm text-red-800 ml-4" id="ErrorCustomerEmail"
                                                    name="ErrorCustomerEmail"></span></p>
                                        </div>
                                        <!--Telephone-->
                                        <div>
                                            <label class="block text-gray-500">Tele No:</label>
                                            <input type="tel" name="txtCustomerTele" id="txtCustomerTele"
                                                placeholder="Enter Telephone Number"
                                                class="w-full px-4 py-3 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 "
                                                autofocus autocomplete>
                                            <p><span class="text-sm text-red-800 ml-4" id="ErrorCustomerTele"
                                                    name="ErrorCustomerTele"></span></p>
                                        </div>
                                        <!--Password-->
                                        <div>
                                            <label class="block text-gray-500">Password</label>
                                            <input type="password" name="txtCustomerPW" id="txtCustomerPW"
                                                placeholder="Enter Password"
                                                class="w-full px-4 py-3 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 "
                                                autofocus autocomplete>
                                            <p><span class="text-sm text-red-800 ml-4" id="ErrorCustomerPW"
                                                    name="ErrorCustomerPW"></span></p>
                                        </div>
                                        <!--Confirm Password-->
                                        <div>
                                            <label class="block text-gray-500">Confirm Password</label>
                                            <input type="password" name="txtCustomerCPW" id="txtCustomerCPW"
                                                placeholder="Enter Password Again To Confirm"
                                                class="w-full px-4 py-3 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 "
                                                autofocus autocomplete>
                                            <p><span class="text-sm text-red-800 ml-4" id="ErrorConCustomerPW"
                                                    name="ErrorCustomerPW"></span></p>
                                        </div>
                                        <!--Submit button-->
                                        <div class="mb-12 pb-1 pt-1 text-center">
                                            <!--Forgot password link-->
                                        </div>

                                        <!--Register button-->
                                        <div class="flex items-center justify-between pb-6">
                                            <a href="login.php"
                                                class="text-indigo-600 font-medium inline-flex space-x-1 items-center">Have
                                                an account?</a>
                                            <button type="submit" name="btnSaveCustomer" id="btnSaveCustomer"
                                                class="inline-flex items-start justify-left px-8 py-3 mr-2 text-base font-medium text-center text-white rounded-3xl bg-gradient-to-r from-green-400 to-blue-500 hover:from-pink-500 hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900"
                                                style="position: relative; right: 0px;">Register</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Right column container with background and description-->
                            <div class="flex items-center rounded-b-lg lg:w-6/12 lg:rounded-r-lg lg:rounded-bl-none">
                                <div class="px-4 py-6 text-white md:mx-6 md:p-12">
                                    <div class=" hidden lg:block w-full md:w-1/2 xl:w-2/3 h-screen">
                                        <a href="index.php">
                                            <h1 class="whitespace-nowrap dark:text-yellow-300 font-extrabold ml-0 mt-12"
                                                style="font-size: 95px;">DDN MOBILE</h1>
                                        </a>
                                        <div class="text-2xl">
                                            <img src="../img/DDN_LOGO/rounded.png"
                                                class="animate-spin-img"
                                                style="position: relative; left: 125px;top: 100px;  width: 360px;"
                                                alt="logo animation">
                                            <img src="../img/DDN_LOGO/ICON_non_bg.png" alt="icon"
                                                 style="width: 60px; position: relative; left: 275px; bottom: 128px;">    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="content-center mx-36 mb-0">
        <label for="message" class="text-xl text-green-400" id="msgShow" name="msgShow"><?php echo $msgShow; ?></label>
    </div>


    <script>
    // Function to validate the Customer registration form
    function validateCustomerRegistration() {
        // for Customer
        var CustomerName = document.getElementById("txtCustomerName").value;
        var CustomerEmail = document.getElementById("txtCustomerEmail").value;
        var CustomerTele = document.getElementById("txtCustomerTele").value;
        var CustomerPW = document.getElementById("txtCustomerPW").value;
        var CustomerCPW = document.getElementById("txtCustomerCPW").value;

        // Get error label elements
        var errorCustomerName = document.getElementById("ErrorCustomerName");
        var errorCustomerEmail = document.getElementById("ErrorCustomerEmail");
        var errorCustomerTele = document.getElementById("ErrorCustomerTele");
        var errorCustomerPW = document.getElementById("ErrorCustomerPW");
        var errorConCustomerPW = document.getElementById("ErrorConCustomerPW");

        // Reset error messages
        errorCustomerName.textContent = "";
        errorCustomerEmail.textContent = "";
        errorCustomerTele.textContent = "";
        errorCustomerPW.textContent = "";
        errorConCustomerPW.textContent = "";

        // Validate Name (not empty)
        if (CustomerName.trim() === "") {
            errorCustomerName.textContent = "Name is required";
            return false;
        }

        // Validate Email (not empty and valid format)
        if (CustomerEmail.trim() === "") {
            errorCustomerEmail.textContent = "Email is required";
            return false;
        } else if (!isValidEmail(CustomerEmail)) {
            errorCustomerEmail.textContent = "Invalid email format";
            return false;
        }

        // Validate Telephone Number (not empty)
        if (CustomerTele.trim() === "") {
            errorCustomerTele.textContent = "Telephone Number is required";
            return false;
        }

        // Validate Password (at least 8 characters)
        if (CustomerPW.length < 8) {
            errorCustomerPW.textContent = "Password must be at least 8 characters";
            return false;
        }

        // Validate Confirm Password (matches Password)
        if (CustomerPW !== CustomerCPW) {
            errorConCustomerPW.textContent = "Passwords do not match";
            return false;
        }

        // If all validations pass, the form is valid
        return true;
    }

    // Function to check if an email is in a valid format
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Add event listener to the Customer registration form
    var CustomerForm = document.getElementById("frmRegisterCustomer");
    if (CustomerForm) {
        CustomerForm.addEventListener("submit", function (event) {
            if (!validateCustomerRegistration()) {
                event.preventDefault(); // Prevent form submission if there are errors
            }
        });
    }
</script>


    <script src="JS/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>