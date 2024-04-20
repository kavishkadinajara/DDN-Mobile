<?php 
include("./connection_db/dbconnect.php");
include("./functions/cart_icon.php");
include("./functions/user_profile.php");
include("./functions/metaTags.php");
session_start();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

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
    class="bg-[conic-gradient(at_bottom_right,_var(--tw-gradient-stops))] from-slate-900 via-rose-950 to-slate-900 w-screen">
    <div style="width: 250px;height: 300px;"
        class="color-patch-blur absolute start-x-1 inset-y-4 w-16 rounded-full rotate-90 bg-gradient-to-b from-yellow-700 to-purple-900 blur-0xl mx-auto scale-y-150 opacity-100"
        data-aos="zoom-in-up">
    </div>
    <div style="width: 250px;height: 300px; position: absolute; top: 1250px; left: 160px;"
        class="color-patch-blur absolute start-x-1 inset-y-4 w-16 rounded-full rotate-0 bg-gradient-to-b from-pink-700 to-purple-900 blur-0xl mx-auto scale-y-150 opacity-100"
        data-aos="zoom-in-left">
    </div>
    <div style="width: 250px;height: 400px; position: absolute; top: 400px; left: 1100px;"
        class="color-patch-blur absolute start-x-1 inset-y-4 w-16 rounded-full rotate-0 bg-gradient-to-b from-green-700 to-purple-900 blur-0xl mx-auto scale-y-150 opacity-100"
        data-aos="zoom-in-left">
    </div>
    <div style="width: 250px;height: 400px; position: absolute; top: 400px; left: 1100px;"
        class="color-patch-blur absolute start-x-1 inset-y-4 w-16 rounded-full rotate-0 bg-gradient-to-b from-green-700 to-purple-900 blur-0xl mx-auto scale-y-150 opacity-100"
        data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
    </div>
    <div style="width: 250px;height: 400px; position: absolute; top: 1000px; left: 1100px;"
        class="color-patch-blur absolute start-x-1 inset-y-4 w-16 rounded-full rotate-0 bg-gradient-to-b from-green-700 to-purple-900 blur-0xl mx-auto scale-y-150 opacity-100"
        data-aos="fade-down">
    </div>
    <div style="width: 250px;height: 400px; position: absolute; top: 2000px; left: -15px;"
        class="color-patch-blur absolute start-x-1 inset-y-4 w-16 rounded-lg rotate-0 bg-gradient-to-b from-green-700 to-blue-600 blur-0xl mx-auto scale-y-150 opacity-100"
        data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1500">
    </div>
<img src="" alt="">

    <!-- Navbar -->
    <nav class="flex justify-end lg:justify-between w-screen" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="px-5 xl:px-12 py-4 flex w-full items-center" data-aos="zoom-in">
            <a class="text-2xl font-semibold font-heading text-yellow-200" href="#">
                <img class="h-12" src="../img/DDN_LOGO/ICON_non_bg.png" alt="logo"
                    style="position: relative; left: 58px;">
                DDN MOBILE
            </a>
            <!-- Nav Links -->
            <ul class="hidden md:flex px-4 mx-auto font-semibold font-heading space-x-12 text-yellow-100">
                <li><a class="hover:text-pink-200" href="#">Home</a></li>
                <li><a class="hover:text-pink-200" href="#about">About Us</a></li>
                <li><a class="hover:text-pink-200" href="#category">Category</a></li>
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
            <a class="navbar-burger self-center mr-10 md:hidden lg:hidden " href="#" id="burgerBtn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-yellow-200 text-gray-50" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </a>
        </div>
    </nav>
    <hr class="mx-10 ">
    <!-- Responsive Navbar Links -->
    <div class="hidden xl:hidden absolute right-4 top-32 rounded-lg m-3 p-2" id="responsiveNav" style="background-color:#0015;">
        <ul class="px-4 font-semibold font-heading space-y-4 text-yellow-100">
            <li><a class="hover:text-pink-200" href="#">Home</a></li>
            <li><a class="hover:text-pink-200" href="#about">About Us</a></li>
            <li><a class="hover:text-pink-200" href="#category">Category</a></li>
            <li><a class="hover:text-pink-200" href="#contact_us">Contact Us</a></li>
        </ul>
    </div>

    <!--USER PROFILE-->
        <?php
            userProfile();
           // userDetails();
        ?>


    <section class="mt-48">
        <!-- Hero Section -->
        <div class="text-white w-screen mt-20 ">
            <div class=" mx-auto flex flex-col md:flex-row items-center my-8 md:my-24">
                <div class="flex flex-col w-full lg:w-1/3 justify-center items-start ps-24">
                    <h1 class="text-3xl font-bold md:text-5xl ps-8 text-yellow-200 font-sans tracking-loose"
                        data-aos="flip-down">
                        DDN MOBILE
                    </h1>
                    <h2 class="text-3xl md:text-2xl sm:text-lg leading-relaxed md:leading-snug mb-2 ps-20"
                        data-aos="fade-up-right">
                        YOUR BEST CHOICE
                    </h2>
                    <p class="text-sm md:text-base text-gray-50 mb-4 ps-0" data-aos="fade-up-left">
                        ALL BRAND NEW PHONE <span class="text-red-700">|</span> IPHONE <span
                            class="text-red-700">|</span>
                        ACCESSORIES
                    </p>
                    <div class="ms-24 mt-16">
                        <a href="items.php"
                            class="bg-transparent hover:bg-yellow-300 text-yellow-100 hover:text-black  hover:to-yellow-500 focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-900 rounded-full shadow hover:shadow-lg py-4 px-10 border border-yellow-300 hover:border-transparent">
                            Shop Now
                        </a>
                    </div>
                </div>
                <div class="p-8 mt-12 mb-6 md:mb-0 md:mt-0 ml-0 md:ml-12 lg:w-2/3 justify-center">
                    <div class="h-48 flex flex-wrap content-center">
                        <div>
                            <img class="inline-block mt-28 xl:block rounded-t-full rounded-bl-full rotate-0 m-5"
                                src="../img/SHOP/img8.png" style="width: 210px; height: 280px;" data-aos="fade-left"
                                id="hero-img1">
                        </div>
                        <div>
                            <img class="inline-block mt-24 md:mt-0 p-8 md:p-0 rounded-b-3xl rounded-t-3xl m-5"
                                src="../img/SHOP/shop_mathara.png" style="width: 340px;" data-aos="zoom-in"
                                data-aos-easing="linear" data-aos-duration="1900">
                        </div>
                        <div>
                            <img class="inline-block mt-28  lg:block rounded-b-full rounded-tr-full m-5 -rotate-0"
                                src="../img/SHOP/img2.png" style="width: 210px;" data-aos="fade-right" id="hero-img2">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Us Section -->
        <div class="" id="about">
            <div class="mt-48">
                <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto mt-24  mb-10 grid-cols-6"
                    data-aos="flip-up" data-aos-easing="linear" data-aos-duration="1000">
                    <div></div>
                    <div class="col-span-4 content-center">
                        <h4 class="self-center text-sm whitespace-nowrap dark:text-white"><span
                                class="font-semibold text-base">|</span> about <span class="font-semibold text-base">DDN
                                MOBILE
                                |</span></h4>
                    </div>
                    <div></div>
                </div>
                <!--------------LAPTOP & DESKTOP-------------->
                <div class=" grid grid-cols-4 md:grid-cols-0 lg:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-4 sm:grid-cols-0"
                    id="lap_desktop">
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; height: 275px; text-align: center; position: relative; top:130px; left: 146px;"
                        data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1200" id="about1">
                        <h1 class="text-yellow-200 text-xl m-6">📱 Discover, Dream, Navigate with DDN Mobile!</h1>
                        <p class="m-5">Welcome to <span class="font-semibold">DDN Mobile</span>, your ultimate
                            destination for
                            cutting-edge technology and exceptional mobile experiences. We're not just a store; we're a
                            gateway
                            to a
                            world of innovation, convenience, and style.</p>
                    </div>
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; text-align: center; position: relative; top: 270px; left: 85px;"
                        data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1300" id="about2">
                        <h1 class="text-yellow-200 text-xl m-6">🥰 Our Journey</h1>
                        <p class="m-5">At <span class="font-semibold">DDN Mobile</span>, we're not just a business;
                            we're a
                            story.
                            Our
                            journey began with a passion for delivering the latest in mobile technology, paired with a
                            commitment to
                            making the digital world accessible to everyone. From our humble beginnings to our four
                            vibrant
                            branches
                            in Yakkalamulla, Mathara, Akurassa, and Katanwila, we've strived to redefine your mobile
                            shopping
                            experience.</p>
                    </div>
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; text-align: center; position: relative; top: -10px; left: 10px;"
                        data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1300" id="about3">
                        <h1 class="text-yellow-200 text-xl m-6">🙊 The DDN Difference</h1>
                        <p class="m-5">What sets <span class="font-semibold">DDN Mobile</span> apart? It's our
                            relentless
                            pursuit of
                            excellence and the unwavering trust you place in us. We curate the finest selection of
                            mobile
                            phones,
                            smartwatches, ear pods, tablets, chargers, and accessories so you can explore the future
                            right at
                            your
                            fingertips. Our mission is to empower you with the latest innovations, top-notch quality,
                            and the
                            best
                            deals.</p>
                    </div>
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; text-align: center; position: relative; top: 310px; right: 155px;"
                        data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1300" id="about4">
                        <h1 class="text-yellow-200 text-xl m-6">🫶 Your Mobile Companion</h1>
                        <p class="m-5"><span class="font-semibold">DDN Mobile</span> is not just a store; we're your
                            mobile
                            companion on life's journey. Whether you're a tech enthusiast, a trendsetter, or someone who
                            simply
                            loves the convenience of technology, we're here to serve you. Our e-commerce website offers
                            you the
                            freedom to shop from anywhere, at any time, making your mobile shopping experience more
                            accessible
                            and
                            exciting.</p>
                    </div>
                </div>
                <!--------------MOBILE & TABS-------------->
                <div class=" grid grid-cols-0 md:grid-cols-0 lg:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-4 sm:grid-cols-0 items-center"
                    id="tab_mobile">
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; height: 275px; text-align: center;" data-aos="fade-right"
                        data-aos-easing="linear" data-aos-duration="1200" id="about1">
                        <h1 class="text-yellow-200 text-xl m-6">📱 Discover, Dream, Navigate with DDN Mobile!</h1>
                        <p class="m-5">Welcome to <span class="font-semibold">DDN Mobile</span>, your ultimate
                            destination for
                            cutting-edge technology and exceptional mobile experiences. We're not just a store; we're a
                            gateway
                            to a
                            world of innovation, convenience, and style.</p>
                    </div>
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; text-align: center;" data-aos="fade-up" data-aos-easing="linear"
                        data-aos-duration="1300" id="about2">
                        <h1 class="text-yellow-200 text-xl m-6">🥰 Our Journey</h1>
                        <p class="m-5">At <span class="font-semibold">DDN Mobile</span>, we're not just a business;
                            we're a
                            story.
                            Our
                            journey began with a passion for delivering the latest in mobile technology, paired with a
                            commitment to
                            making the digital world accessible to everyone. From our humble beginnings to our four
                            vibrant
                            branches
                            in Yakkalamulla, Mathara, Akurassa, and Katanwila, we've strived to redefine your mobile
                            shopping
                            experience.</p>
                    </div>
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; text-align: center;" data-aos="fade-down" data-aos-easing="linear"
                        data-aos-duration="1300" id="about3">
                        <h1 class="text-yellow-200 text-xl m-6">🙊 The DDN Difference</h1>
                        <p class="m-5">What sets <span class="font-semibold">DDN Mobile</span> apart? It's our
                            relentless
                            pursuit of
                            excellence and the unwavering trust you place in us. We curate the finest selection of
                            mobile
                            phones,
                            smartwatches, ear pods, tablets, chargers, and accessories so you can explore the future
                            right at
                            your
                            fingertips. Our mission is to empower you with the latest innovations, top-notch quality,
                            and the
                            best
                            deals.</p>
                    </div>
                    <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                        style="width: 340px; text-align: center;" data-aos="fade-left" data-aos-easing="linear"
                        data-aos-duration="1300" id="about4">
                        <h1 class="text-yellow-200 text-xl m-6">🫶 Your Mobile Companion</h1>
                        <p class="m-5"><span class="font-semibold">DDN Mobile</span> is not just a store; we're your
                            mobile
                            companion on life's journey. Whether you're a tech enthusiast, a trendsetter, or someone who
                            simply
                            loves the convenience of technology, we're here to serve you. Our e-commerce website offers
                            you the
                            freedom to shop from anywhere, at any time, making your mobile shopping experience more
                            accessible
                            and
                            exciting.</p>
                    </div>
                </div>
                <div class="text-cyan-100 border-double border-4 border-yellow-500 m-5 rounded-3xl"
                    style="width: 730px; text-align: center; position: relative; top: 280px; left: 233px;"
                    data-aos="fade-up-right" data-aos-easing="linear" data-aos-duration="1200" id="about5">
                    <h1 class="text-yellow-200 text-xl m-6">🤔 Why Choose DDN Mobile?</h1>
                    <p class="m-5"><span class="font-semibold">Quality Assurance:</span> We handpick the best
                        products to ensure you receive nothing but the best. <br>
                        <span class="font-semibold">Exceptional Service:</span> Our customer support team is here to
                        assist you every step of the way. <br>
                        <span class="font-semibold">Innovation at Your Doorstep:</span> Experience the latest tech
                        trends and innovations right in your hands. <br>
                        <span class="font-semibold">Multi-Branch Convenience:</span> With four branches, we're always
                        close by, both online and offline. <br>
                        <span class="font-semibold">Community Focus:</span> We're not just a business; we're part of
                        your community, committed to giving back and supporting your digital journey.</p>
                </div>
                <div data-aos="zoom-in" data-aos-easing="linear" data-aos-duration="1400" id="aboutlogo">
                    <img src="../img/DDN_LOGO/Origibal_non_bg.png" alt="logo.img"
                        style="width: 345px; position: relative; left: 95px; bottom: 320px;">
                </div>
            </div>
        </div>
        <hr class="mx-14 sm:mx-24 md:mx-28 lg:mx-32 xl:mx-36">
        <!-----------FOOTER----------->
        <?php include("./functions/footer.php") ?>
    </section>

    <!-- Follow Us on Social Media -->
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