<?php

include("./connection_db/dbconnect.php");

    function userProfile(){
        echo'<div id="userProfile"
                class="max-w-xs bg-white shadow-md justify-end overflow-hidden rounded-lg absolute top-16 right-0 m-4 hidden">
                <div
                    class="container text-cyan-100 bg-[radial-gradient(ellipse_at_top_left,_var(--tw-gradient-stops))] from-pink-950 via-gray-900 to-black p-4">';
                     userDetails();
            echo'   <br>
                    <div class="mb-4">
                        <a href="oder_checkOut.php" class="hover:text-yellow-200">🧑‍💻Pending Odrders</a>
                    </div>
                    <div class="mb-4">
                        <a href="my_orders.php" class="hover:text-yellow-200">👀View My Orders</a>
                    </div>
                    <div>
                        <a href="logout.php?logout" class="hover:text-yellow-200">👆Logout</a>
                    </div>
                </div>
            </div>';
    }

    function userDetails() {
        global $con;
        if (isset($_SESSION['customer_id'])) {
            $customer_id = $_SESSION['customer_id'];

            $sql_select_customer = "SELECT customer_name,customer_email FROM customer WHERE customer_id = ?";
            $stmt_select_customer = mysqli_prepare($con, $sql_select_customer);
            mysqli_stmt_bind_param($stmt_select_customer, 'i', $customer_id);   
            mysqli_stmt_execute($stmt_select_customer);
            $result = mysqli_stmt_get_result($stmt_select_customer);

            while ($row = mysqli_fetch_assoc($result)) {
                $customer_name = $row["customer_name"];
                $customer_email = $row["customer_email"];

                echo '
                        <div>
                            <h3 class="text-center">'.$customer_name.'😊</h3>
                        </div>
                        <div>
                            <h3 class="text-red-400 text-sm text-center">'.$customer_email.'</h3>
                        </div>
                     ';
            }
        }
    }

?>