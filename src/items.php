<?php
    include("./connection_db/dbconnect.php");
    include("./functions/product_display.php");
    include("./functions/user_profile.php");
    include("./functions/cart_icon.php");
    include("./functions/metaTags.php");
    session_start();
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
            <ul class="hidden md:flex px-4 mx-auto font-semibold font-heading space-x-12 text-yellow-100">
                <li><a class="hover:text-pink-200" href="#">Home</a></li>
                <li><a class="hover:text-pink-200" href="#about">About Us</a></li>
                <li><a class="hover:text-pink-200" href="items.php">Display All Items</a></li>
                <li><a class="hover:text-pink-200" href="#contuct_us">Contact Us</a></li>
            </ul>
            <!-- Header Icons -->
            <!-- Header Icons -->
            <div class="hidden md:flex items-end lg:items-center space-x-5" id="navList">
                <?php
                    user_icon();
                ?>
            </div>


        </div>
        <!-- Responsive Navbar -->
        <div class="md:hidden relative right-4 flex space-x-2">
        <?php user_icon(); ?>
        </div>
    </nav>
    <hr class="mx-10 ">
    <!-- Responsive Navbar Links -->
    <div class="hidden z-30 xl:hidden absolute right-4 top-32 rounded-lg m-3 p-2" id="responsiveNav" style="background-color:#0015;">
        <ul class="px-4 font-semibold font-heading space-y-4 text-yellow-100">
            <li><a class="hover:text-pink-200" href="#">Home</a></li>
            <li><a class="hover:text-pink-200" href="#about">About Us</a></li>
            <li><a class="hover:text-pink-200" href="items.php">Display All Items</a></li>
            <li><a class="hover:text-pink-200" href="#contact_us">Contact Us</a></li>
        </ul>
    </div>

    <!--USER PROFILE-->
        <?php 
            userProfile();
           // userDetails();
        ?>    



    <div>
    <hr class="mx-10 ">

        <!--SEARCH BAR-->
        <div class="px-20 justify-end flex">
            <?php searchbtn(); ?>
        </div>
        <hr class="mx-10 mt-4">



        <!--------------SELECT CATEGORIES AND BRAND BUTTONS-------------->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
            <div class="mt-8 lg:mx-auto xl:mx-auto">
                <div class="flex justify-center items-center">
                    <button
                        class="bg-transparent hover:bg-yellow-300 text-yellow-100 hover:text-black  hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 rounded-full shadow hover:shadow-lg py-3 px-10 border border-yellow-300 hover:border-transparent"
                        onclick="showCategories()">
                        Categories
                    </button>
                </div>
            </div>

            <div class="mt-8 lg:mx-auto xl:mx-auto">
                <div class="flex justify-center items-center">
                    <button
                        class="bg-transparent hover:bg-yellow-300 text-yellow-100 hover:text-black  hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 rounded-full shadow hover:shadow-lg py-3 px-10 border border-yellow-300 hover:border-transparent"
                        onclick="showBrands()">
                        Brands
                    </button>
                </div>
            </div>
        </div>
        <hr class="mx-10 mt-7">
    </div>

    <div class="bottom-0 right-4 mb-4 mr-4 z-10 fixed" id="facebook-icon">
        <div>
            <a title="Follow us on Facebook"
                href="https://www.facebook.com/profile.php?id=100063510759103&mibextid=LQQJ4d" target="_blank"
                class="block w-16 h-16 rounded-full transition-all shadow hover:shadow-lg transform hover:scale-110 hover:rotate-12">
                <img class="object-cover object-center w-full h-full rounded-full"
                    src="../img/ICON/facebook_non_bg.png" />
            </a>
        </div>
    </div>


    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-4 gap-0">
        <!-- Product Cards -->

        <?php
        display_selected_items();
        ?>

    </div>


    <div class="grid grid-cols-2">
        <!------------Categories section------------>
        <div id="category_select"
            class="fixed z-50 bg-opacity-75 bg-blur backdrop-blur-md bg-top-0 w-full flex justify-center items-center">
            <?php
            category_section();
            ?>
        </div>

        <!------------Brands section------------>
        <div id="brand_select"
            class="fixed z-50 bg-opacity-75 bg-blur backdrop-blur-md bg-top-0 w-full flex justify-center items-center">
            <?php
            brand_section();
          //  display_uniqe_category();
            ?>
        </div>
    </div>


    <hr class="mx-14 sm:mx-24 md:mx-28 lg:mx-32 xl:mx-36">
    <!-----------FOOTER----------->
    <?php include("./functions/footer.php") ?>




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
            duration: 500,
        })
    </script>
</body>

</html>