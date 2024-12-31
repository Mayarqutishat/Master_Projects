<?php
session_start();
include_once("../controllers/contact_us_control.php");
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

            <h1 class="mt-4">Contact Us</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Contact Us</li>
            </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Contact Us
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                        </div>
                        <thead>
                            <tr>
                                <th>Message ID</th>
                                <th>Sent From Email</th>
                                <th>Name</th>
                                <th>Entered Email</th>
                                <th>Message</th>
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