<?php
include_once("../controllers/orders_control.php");
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

            <h1 class="mt-4">Orders</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Orders
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                
                                
                                
                        </div>
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>User Id</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Address</th>
                                <th>Order Status</th>
                                <th>Order date/time</th>
                                <th>Show/Change</th>
                            </tr>
                        </thead>
                       
                        <tfoot>
                            <tr>
                                <th>Order Id</th>
                                <th>User Id</th>
                                <th>Total Price</th>
                                <th>Payment Method</th>
                                <th>Payment Status</th>
                                <th>Address</th>
                                <th>Order Status</th>
                                <th>Order date/time</th>
                                <th>Show/Change</th>
                            </tr>
                        </tfoot>
                        </table>
                    </div>
                </div>
        </div>
        </main>

<?php 
include_once("../layout/footer.php")
?>