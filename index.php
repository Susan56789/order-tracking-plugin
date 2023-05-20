<?php
/**
 * Plugin Name: Order Tracking Plugin
 * Description: Allows customers to track their orders by order ID and email.
 * Version: 1.0
 * Author: Susan
 */

// Shortcode to display the order tracking form
function order_tracking_form_shortcode() {
    ob_start();
    ?>
    <form id="order-tracking-form" action="" method="post">
        <p><label for="order_id">Order ID:</label>
        <input type="text" name="order_id" id="order_id" required /></p>
        
        <p><label for="email">Email:</label>
        <input type="email" name="email" id="email" required /></p>
        
        <p><input type="submit" name="submit" value="Track Order" /></p>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('order_tracking_form', 'order_tracking_form_shortcode');

// Shortcode to display the order status with product details
function order_status_shortcode($atts) {
    if (isset($_POST['submit'])) {
        $order_id = sanitize_text_field($_POST['order_id']);
        $email = sanitize_email($_POST['email']);

        // Retrieve the order status and product details
        $order = wc_get_order($order_id);
        if ($order && $order->get_billing_email() === $email) {
            $status = $order->get_status();
            $status_date = $order->get_date_created();
            $items = $order->get_items();


            // Display the product details
            echo '<h2 style="margin-bottom: 10px">Order #'.$order_id.' Details:</h2>';
            echo '<table>';
            echo '<tr><td>Image</td><td>Name</td><td>Status</td><td>Time</td></tr>';
            foreach ($items as $item) {
                $product = $item->get_product();
                $product_name = $product->get_name();
                $product_image = $product->get_image();
                echo '<tr>';
                echo '<td style="width: 100px; height: 100px">' . $product_image . '</td>';
                echo '<td>' . $product_name . '</td>';
                echo '<td>' ; 
                if($status == "processing"){
                echo '<span style="color:white; border-radius: 5px; padding: 5px 10px; background-color: green">' . $status . '</span>';
                }
                elseif($status == "Shipping"){
                echo '<span style="color:white; border-radius: 5px; padding: 5px 10px; background-color: blue">' . $status . '</span>';
                }
                elseif($status == "on-hold"){
                echo '<span style="color:white; border-radius: 5px; padding: 5px 10px; background-color: orange">' . $status . '</span>';
                }
                elseif($status == "completed"){
                echo '<span style="color:white; border-radius: 5px; padding: 5px 10px; background-color: black"> ' . $status . '</span>';
                }
                elseif($status == "cancelled"){
                echo '<span style="color:white; border-radius: 5px; padding: 5px 10px; background-color: red">' . $status . '</span>';
                }
                echo '</td>';
                echo '<td>' . $status_date->format('Y-m-d H:i:s') . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            return;
        }
    }

    // Display the order tracking form if no valid order was found
    echo do_shortcode('[order_tracking_form]');
}
add_shortcode('order_status', 'order_status_shortcode');
