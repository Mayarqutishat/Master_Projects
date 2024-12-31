<?php
session_start();
include_once("../controllers/coupon_control.php");
include_once("../layout/header.php");
?>

        <div id="layoutSidenav_content">
            <main>
            <div class="container-fluid px-4">
            <?php if (isset($_GET['message']) && isset($_GET['type'])): ?>
        <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_GET['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

            <h1 class="mt-4">Coupon</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Coupon</li>
            </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Coupon
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <div class="container">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addCouponModal">
                                        Add Coupon
                                    </button>
                                </div>

                                
                                <div class="modal fade" id="addCouponModal" tabindex="-1"
                                    aria-labelledby="addCouponModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addCouponModalLabel">Add New Coupon</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controllers/coupon_control.php" method="post">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="coupon_name" class="form-label">Coupon Code</label>
                                                        <input type="text" id="coupon_name" name="code"
                                                            class="form-control" placeholder="Enter coupon code" maxlength="50"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="coupon_status" class="form-label">Coupon Status</label>
                                                        <select class="form-select" id="coupon_status" name="status" required>
                                                        <option value="" disabled selected>Select coupon status</option>
                                                        <option value="valid">Valid</option>
                                                        <option value="invalid">Invalid</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="coupon_expiry_date" class="form-label">Coupon Expiry Date</label>
                                                        <input type="datetime-local" id="coupon_name" name="expiry_date"
                                                        class="form-control" placeholder="Enter coupon expiry date" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="coupon_discount" class="form-label">Coupon Discount</label>
                                                        <input type="number" id="coupon_discount" name="discount_percentage"
                                                        class="form-control" placeholder="Enter enter a decimal number between 0-100" min="0" max="1" step="1" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" name="add_coupon" value="Add">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    
                                </div>
                        </div>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Coupon Code</th>
                                <th>Coupon Status</th>
                                <th>Coupon Expiry Date</th>
                                <th>Coupon Discount %</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        
                       
                        </table>
                    </div>
                    
                </div>
        </div>
        </main>
<?php 
include_once("../layout/footer.php")
?>