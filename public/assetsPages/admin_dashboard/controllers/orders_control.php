<?php
require_once("../connection/conn.php");

class CRUD extends connection{


    public function readData() {
        // Adjusted query to join users table and fetch address_line_1 and address_line_2
        $query = "
            SELECT 
                orders.*, 
                users.address_line_1, 
                users.address_line_2 
            FROM 
                `orders` 
            JOIN 
                `users` ON orders.user_id = users.id";
        
        $orders = $this->dbconnection->query($query);
        
        if ($orders->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($orders as $order) {
                echo "<tr>
                        <td>{$order['id']}</td>
                        <td>{$order['user_id']}</td>
                        <td>{$order['total_price']}</td>
                        <td>{$order['payment_method']}</td>
                        <td>{$order['payment_status']}</td>
                        <td>{$order['address_line_1']}</td>  <!-- Display address_line_1 -->
                        <td>{$order['address_line_2']}</td>  <!-- Display address_line_2 -->
                        <td>{$order['order_status']}</td>
                        <td>{$order['created_at']}</td>
                        <td>
                            <a href='#' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal-{$order['id']}'>Edit</a>
                            <a href='#' class='btn btn-secondary' data-bs-toggle='modal' data-bs-target='#showModal-{$order['id']}'>Show</a>
                        </td>
                    </tr>";
            
        

    
                // Edit order Modal (for each order)
                echo "
                <div class='modal fade' id='editModal-$order[id]' tabindex='-1' aria-labelledby='editModalLabel-$order[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-$order[id]'>Edit Order Status</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../controllers/orders_control.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='$order[id]'>
                                    
                                    <div class='mb-3'>
                                        <label for='payment_status' class='form-label'>Payment Status</label>
                                        <select name='payment_status' class='form-select' required>
                                            <option value='pending' ".($order['payment_status'] == 'pending' ? 'selected' : '').">Pending</option>
                                            <option value='paid' ".($order['payment_status'] == 'paid' ? 'selected' : '').">Paid</option>
                                        </select>
                                    </div>

                                    <div class='mb-3'>
                                        <label for='order_status' class='form-label'>Order Status</label>
                                        <select name='order_status' class='form-select' required>
                                            <option value='pending' ".($order['order_status'] == 'pending' ? 'selected' : '').">Pending</option>
                                            <option value='processing' ".($order['order_status'] == 'processing' ? 'selected' : '').">Processing</option>
                                            <option value='shipped' ".($order['order_status'] == 'shipped' ? 'selected' : '').">Shipped</option>
                                            <option value='delivered' ".($order['order_status'] == 'delivered' ? 'selected' : '').">Delivered</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_order' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                ";

                echo "
                <div class='modal fade' id='showModal-{$order['id']}' tabindex='-1' aria-labelledby='showModalLabel-{$order['id']}' aria-hidden='true'>
                    <div class='modal-dialog modal-lg'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='showModalLabel-{$order['id']}'>Order Items</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <div class='row d-flex'>";
                
                // Fetch order items for the current order
                $orderItems = $this->getOrderItems($order['id']);
                if (!empty($orderItems)) {
                    foreach ($orderItems as $item) {
                        echo "
                        <div class='col-md-4 mb-4'>
                            <div class='card h-100' style='height: 350px; margin: 15px;'> <!-- Set fixed height for the card -->
                                <img src='".htmlspecialchars($item['product_cover'])."' class='card-img-top' alt='Product Image' style='height: 150px; object-fit: cover;'> <!-- Set image height -->
                                <div class='card-body d-flex flex-column'> <!-- Use flexbox on card body -->
                                    <h5 class='card-title'>".htmlspecialchars($item['product_name'])."</h5>
                                    <p class='card-text'>".htmlspecialchars($item['product_description'])."</p>
                                    <p class='card-text'>Price: $".number_format($item['product_price'], 2)."</p>
                                    <p class='card-text'>Quantity: ".htmlspecialchars($item['product_quantity'])."</p> <!-- Display quantity -->
                                    <p class='card-text'>Size: ".htmlspecialchars($item['product_size'])."</p>
                                    <a href='#' class='btn btn-primary mt-auto'>Go to Products</a>
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p>No items found for this order.</p>"; // Message when no items are found
                }

                echo "          </div>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        }
}



public function updateOrder() {
if (isset($_POST['update_order'])) {
$id = $_POST['id'];
$payment_status = $_POST['payment_status'];
$order_status = $_POST['order_status'];

$query = "UPDATE `orders` SET `payment_status` = :payment_status, `order_status` = :order_status WHERE `id` = :id";

$statement = $this->dbconnection->prepare($query);
$statement->bindParam(':payment_status', $payment_status);
$statement->bindParam(':order_status', $order_status);
$statement->bindParam(':id', $id);

if ($statement->execute()) {
$_SESSION['message'] = "Order updated successfully!";
header('Location: ../pages/orders.php?message=Order updated successfully');
exit();
} else {
var_dump($statement->errorInfo());
}
}
}

public function getOrderItems($order_id) {
    $query = 'SELECT 
                order_items.id AS order_item_id,
                orders.id AS order_id,
                orders.created_at AS order_date,
                products.cover AS product_cover,
                products.name AS product_name,
                products.description AS product_description,
                products.price AS product_price,
                order_items.quantity AS product_quantity,  -- Fetch quantity
                GROUP_CONCAT(CASE WHEN product_attributes.type = "size" THEN product_attributes.value END) AS product_size  
              FROM 
                order_items
              JOIN 
                orders ON order_items.order_id = orders.id
              JOIN 
                products ON order_items.product_id = products.id
              LEFT JOIN 
                product_attributes ON products.id = product_attributes.product_id
              WHERE 
                orders.id = :order_id
              GROUP BY
                order_items.id';

    $statement = $this->dbconnection->prepare($query);
    $statement->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


}

$orders = new CRUD();
$orders->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//$orders->createFormData(); // Handle add user
$orders->updateOrder(); // Handle update user
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
$userId = $_GET['id'];
$orders->deleteUser($userId); // Call the delete user method
}


?>