<?php
require_once("../connection/conn.php");

class CRUD extends connection {

    public function readData() {
        $query = "SELECT * FROM `coupon` WHERE deleted = 'false'";
        $coupons = $this->dbconnection->query($query);
        
        if ($coupons->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($coupons as $coupon) {
                echo "<tr>
                        <td>$coupon[id]</td>
                        <td>$coupon[code]</td>
                        <td>$coupon[status]</td>
                        <td>$coupon[expiry_date]</td>
                        <td>$coupon[discount_percentage]%</td>
                        <td>
                            <a href='#' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal-$coupon[id]'>Edit</a>
                            <a href='#' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal-$coupon[id]'>Delete</a>
                        </td>
                    </tr>";

                // Edit Modal
                echo "
                <div class='modal fade' id='editModal-$coupon[id]' tabindex='-1' aria-labelledby='editModalLabel-$coupon[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-$coupon[id]'>Edit Coupon</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../controllers/coupon_control.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='$coupon[id]'>
                                    <div class='mb-3'>
                                        <label for='code' class='form-label'>Code</label>
                                        <input type='text' name='code' class='form-control' value='$coupon[code]' maxlength='50' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='status' class='form-label'>Status</label>
                                        <select name='status' class='form-select' required>
                                            <option value='valid' " . ($coupon['status'] == 'valid' ? 'selected' : '') . ">Valid</option>
                                            <option value='invalid' " . ($coupon['status'] == 'invalid' ? 'selected' : '') . ">Invalid</option>
                                        </select>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='expiry_date' class='form-label'>Expiry Date</label>
                                        <input type='date' name='expiry_date' class='form-control' value='date('Y-m-d', strtotime($coupon[expiry_date]))' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='discount_percentage' class='form-label'>Discount Percentage</label>
                                        <input type='number' name='discount_percentage' class='form-control' value='$coupon[discount_percentage]' min='0' max='100' step='1' required>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_coupon' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";

                // Delete Modal
                echo "
                <div class='modal fade' id='deleteModal-$coupon[id]' tabindex='-1' aria-labelledby='deleteModalLabel-$coupon[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='deleteModalLabel-$coupon[id]'>Confirm Delete</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p>Are you sure you want to delete this coupon?</p>
                            </div>
                            <div class='modal-footer'>
                                <form action='../controllers/coupon_control.php' method='get'>
                                    <input type='hidden' name='id' value='$coupon[id]'>
                                    <input type='hidden' name='action' value='delete'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>
                                    <button type='submit' class='btn btn-danger'>Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        }
    }

    public function createFormData() {
        if (isset($_POST['add_coupon'])) {
            $code = $_POST['code'];
            $status = $_POST['status'];
            $expiry_date = $_POST['expiry_date'];
            $discount_percentage = $_POST['discount_percentage'];

            $query = "INSERT INTO `coupon` (`code`, `status`, `expiry_date`, `discount_percentage`) VALUES (:code, :status, :expiry_date, :discount_percentage)";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':code', $code);
            $statement->bindParam(':status', $status);
            $statement->bindParam(':expiry_date', $expiry_date);
            $statement->bindParam(':discount_percentage', $discount_percentage);
            
            if ($statement->execute()) {
                $_SESSION['message'] = "Coupon added successfully!";
                header('Location: ../pages/coupon.php?message=Coupon added successfully');
                exit();
            }
        }
    }

    public function updateCoupon() {
        if (isset($_POST['update_coupon'])) {
            $id = $_POST['id'];
            $code = $_POST['code'];
            $status = $_POST['status'];
            $expiry_date = $_POST['expiry_date'];
            $discount_percentage = $_POST['discount_percentage'];
    
            $query = "UPDATE `coupon` SET `code` = :code, `status` = :status, `expiry_date` = :expiry_date, `discount_percentage` = :discount_percentage WHERE `id` = :id";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':code', $code);
            $statement->bindParam(':status', $status);
            $statement->bindParam(':expiry_date', $expiry_date);
            $statement->bindParam(':discount_percentage', $discount_percentage);
            $statement->bindParam(':id', $id);
    
            if ($statement->execute()) {
                $_SESSION['message'] = "Coupon updated successfully!";
                header('Location: ../pages/coupon.php?message=Coupon updated successfully');
                exit();
            }
        }
    }

    public function deleteCoupon($id) {
        $query = "UPDATE coupon SET deleted = true WHERE id = :id";
        $statement = $this->dbconnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($statement->execute()) {
            $_SESSION['message'] = "Coupon deleted successfully!";
            header('Location: ../pages/coupon.php?message=Coupon deleted successfully');
            exit();
        }
    }
}

$coupons = new CRUD();
$coupons->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $coupons->createFormData(); // Handle add coupon
    $coupons->updateCoupon(); // Handle update coupon
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $couponId = $_GET['id'];
    $coupons->deleteCoupon($couponId); // Call delete coupon method
}
?>
