<?php

session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

$loginError = '';

if (isset($_POST['btnLogin'])) {
    $username = $_POST['txtUserNameLogin'];
    $password = $_POST['txtPassword'];
    include("./connection_db/dbconnect.php");

    if ($username == "ddnmobilestore" && $password == "ddn123456") {
        
            $_SESSION['loggedin'] = true;
            $_SESSION['ddnMobile'];
            header('Location: admin_home.php');
        exit();
    } else {
        $sql = 'SELECT customer_id, customer_email, customer_password FROM customer WHERE customer_email=?';
        $stmt = mysqli_prepare($con, $sql);

        if (!$stmt) {
            die('Error in the prepared statement: ' . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, 's', $username);

        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $hashedPassword = $row['customer_password'];

                if (password_verify($password, $hashedPassword)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['customer_id'] = $row['customer_id'];
                    header('Location: items.php');
                    exit();
                } else {
                    $loginError = 'Invalid email or password.';
                }
            } else {
                $loginError = 'Invalid email or password.';
            }
        } else {
            die('Error executing the statement: ' . mysqli_stmt_error($stmt));
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
}

require 'google-api/vendor/autoload.php';

$client = new Google_Client();
// client
$client->setRedirectUri('http://localhost/FinalProjectDDN/src/login.php');
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        $id = mysqli_real_escape_string($db_connection, $google_account_info->id);
        $full_name = mysqli_real_escape_string($db_connection, trim($google_account_info->name));
        $email = mysqli_real_escape_string($db_connection, $google_account_info->email);
        $profile_pic = mysqli_real_escape_string($db_connection, $google_account_info->picture);

        $get_user = mysqli_query($db_connection, "SELECT `google_id` FROM `customer` WHERE `google_id`='$id'");
        if (mysqli_num_rows($get_user) > 0) {
            $_SESSION['login_id'] = $id; 
            header('Location: index.php');
            exit;
        } else {
            $insert = mysqli_query($db_connection, "INSERT INTO `customer`(`google_id`,`customer_name`,`customer_email`,`profile_image`) VALUES('$id','$full_name','$email','$profile_pic')");
            
            if ($insert) {
                $_SESSION['login_id'] = $id; 
                header('Location: home.php');
                exit;
            } else {
                echo "Sign up failed!(Something went wrong).";
            }
        }
    } else {
        header('Location: login.php');
        exit;
    }
}
?>

<!-- Rest of your HTML code remains unchanged -->






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DDN MOBILE | LOGIN</title>
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
<!-- component -->

<body class="bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900">
    <!-- component -->
    <section class="flex flex-col md:flex-row h-screen items-center">
        <div data-aos="fade-down-left" style="width: 300px;height: 350px;position: absolute; top: 10px; left: 700px;"
            class="color-patch-blur absolute  w-16 rounded-full rotate-45 bg-gradient-to-b from-green-500 to-purple-600 blur-0xl mx-auto scale-y-150 opacity-100">
        </div>
        <div data-aos="fade-down-left" style="width: 200px;height: 280px;position: absolute; top: 380px; left: 0px;"
            class="color-patch-blur absolute  w-16 rounded-full rotate- bg-gradient-to-b from-blue-500 to-pink-600 blur-0xl mx-auto scale-y-150 opacity-100">
        </div>

        <div class=" hidden lg:block w-full md:w-1/2 xl:w-2/3 h-screen">
            <a href="index.html">
                <h1 class="whitespace-nowrap dark:text-yellow-300 font-extrabold ml-48 mt-12" style="font-size: 95px;">
                </h1>
            </a>
            <div class="">
                <img src="../img/DDN_LOGO/LOGO_semi_bg.png" class="animate-bounce"
                    style="position: relative; left: 250px; top: 10px;  width: 660px; " alt="">
                <img src="" alt="" style="position: relative; left: 450px;top: -253px; width: 70px; ">
            </div>
        </div>
        <!-----------------LOGIN FORM---------------->
        <div id="loginDiv" style="display:block;"
            class="w-full md:max-w-md lg:max-w-full md:mx-auto ms:mx-0 md:w-1/2 xl:w-1/3 h-screen px-6 lg:px-16 xl:px-12 flex items-center justify-center">
            <div class="w-full h-100">

                <h1 class="text-xl md:text-2xl font-thin leading-tight mt-12 text-yellow-200">Log in to your account
                </h1>

                <form class="mt-6" action="#" method="POST" id="frmLogin" name="frmLogin">
                    <!--<?php echo $_SERVER['PHP_SELF']; ?>-->
                    <div>
                        <label class="block text-gray-500">User Name</label>
                        <input type="text" name="txtUserNameLogin" id="txtUserNameLogin"
                            placeholder="Enter Email Address"
                            class="w-full px-4 py-3 text-yellow-200 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 "
                            autofocus autocomplete>
                        <p><span class="text-sm text-red-800 ml-4" id="UNErrorMsgLogin" name="UNErrorMsgLogin"></span>
                        </p>
                    </div>

                    <div class="mt-4">
                        <label class="block text-gray-500">Password</label>
                        <input type="password" name="txtPassword" id="txtPassword" placeholder="Enter Password"
                            minlength="8"
                            class="w-full px-4 py-3 text-yellow-200 rounded-3xl bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 mt-2 border focus:border-blue-500 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 ">
                        <p><span class="text-sm text-red-800 ml-4" id="PWErrorMsgLogin" name="PWErrorMsgLogin"></span>
                        </p>
                    </div>

                    <div class="text-right mt-2">
                        <a href="#" class="text-sm font-semibold text-gray-500 hover:text-blue-700 focus:text-blue-700"
                            onclick="forgotPassword()">Forgot Password?</a>
                    </div>
                    <p><span class="text-sm text-red-800 ml-4 font-semibold" id="PWErrorMsgLogin"
                            name="PWErrorMsgLogin"><?php echo $loginError; ?></span></p>
                    <button type="submit" id="btnLogin" name="btnLogin"
                        class="w-full block rounded-3xl bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 text-white font-semibold px-4 py-3 mt-6">Log
                        In</button>
                </form>

                <hr class="my-6 border-gray-300 w-full">

                <a type="button" href="<?php echo $client->createAuthUrl(); ?>"
                    class="w-full block bg-white hover:bg-gray-100 focus:bg-gray-100 text-gray-900 font-semibold rounded-3xl px-4 py-3 border border-gray-300">
                    <div class="flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            class="w-6 h-6" viewBox="0 0 48 48">
                            <defs>
                                <path id="a"
                                    d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z" />
                            </defs>
                            <clipPath id="b">
                                <use xlink:href="#a" overflow="visible" />
                            </clipPath>
                            <path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z" />
                            <path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z" />
                            <path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z" />
                            <path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z" />
                        </svg>
                        <span class="ml-4"> Log in with Google</span>
                    </div>
                </a>
                <p class="mt-8 text-yellow-100">Need an account? <a href="register.php"
                        class="text-blue-500 hover:text-blue-700 font-semibold">Create an account</a></p>
            </div>
        </div>
        


        <!--------------------------RESET PASSWORD------------------------>
        <div id="resetPWDiv" style="display:none;"
            class="w-full h-100 md:max-w-md lg:max-w-full md:mx-auto md:mx-0 md:w-1/2 xl:w-1/3 h-screen px-6 lg:px-16 xl:px-12 flex items-center justify-center ">

            <h1 class="text-xl md:text-2xl font-thin leading-tight mt-12 text-yellow-200">Reset Password</h1>

            <form method="POST" action="send_password_reset.php" class="my-10">
                <div class="flex flex-col space-y-5">
                    <label for="email">
                        <p class="font-medium text-slate-500 pb-2">Email address</p>
                        <input id="email" name="email" type="email"
                            class="w-full px-4 py-3 rounded-3xl mt-2 border focus:border-blue-500 bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 focus:bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900  "
                            placeholder="Enter email address">
                    </label>

                    <hr class="my-6 border-gray-300 w-full">

                    <button type="submit" id="btnResetPassword" name="btnResetPassword"
                        class="w-full py-3 mt-12 font-medium text-white bg-gradient-to-r from-green-700 to-blue-700 hover:from-pink-600 hover:to-yellow-700 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 transition-all animate-duration-700 rounded-3xl border-indigo-500 hover:shadow inline-flex space-x-2 items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                        </svg>
                        <span>Reset password</span>
                    </button>
                    <p class="text-center text-yellow-100">Not registered yet? <a href="register.php"
                            class="text-indigo-600 font-medium inline-flex space-x-1 items-center"><span>Register now
                            </span><span><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg></span></a></p>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Function to validate the form
        function validateForm() {
            // Get form elements
            var userName = document.getElementById('txtUserNameLogin');
            var password = document.getElementById('txtPassword');
            var userTypeErrorMsg = document.getElementById('UNErrorMsgUserType');
            var userNameErrorMsg = document.getElementById('UNErrorMsgLogin');
            var passwordErrorMsg = document.getElementById('PWErrorMsgLogin');

            // Reset error messages
            userTypeErrorMsg.textContent = '';
            userNameErrorMsg.textContent = '';
            passwordErrorMsg.textContent = '';

            // Check user name
            if (userName.value.trim() === '') {
                userNameErrorMsg.textContent = 'Please enter a user name.';
                userName.focus();
                return false;
            }

            // Check password
            if (password.value.trim() === '') {
                passwordErrorMsg.textContent = 'Please enter a password.';
                password.focus();
                return false;
            }

            // Additional password length check
            if (password.value.length < 8) {
                passwordErrorMsg.textContent = 'Password must be at least 8 characters long.';
                password.focus();
                return false;
            }

            // Form is valid, allow submission
            return true;
        }

        // Function to handle form submission
        function handleSubmit(event) {
            if (!validateForm()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        }

        // Add form submission event listener
        var loginForm = document.getElementById('frmLogin');
        loginForm.addEventListener('submit', handleSubmit);
    </script>

    <script src="../JS/main.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1800,
        })
    </script>

</body>
<!-- support me by buying a coffee -->
<a href="https://www.buymeacoffee.com/danimai" target="_blank"
    class="bg-purple-600 p-2 rounded-lg text-white fixed right-0 bottom-0">
    Support me
</a>

</html>