<?php
include_once("../controllers/users_control.php");
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

            <h1 class="mt-4">Users</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Users
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <div class="container">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                        Add New User
                                    </button>
                                </div>

                                
                                <div class="modal fade" id="addUserModal" tabindex="-1"
                                    aria-labelledby="addUserModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controllers/users_control.php" method="post">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="user_name" class="form-label">user Name</label>
                                                        <input type="text" id="user_name" name="user_name"
                                                            class="form-control" placeholder="Enter the name" maxlength="200"
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">User's Email</label>
                                                        <input type="email" id="email" name="email" class="form-control"
                                                        placeholder="Enter your email" maxlength='150' required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">User's Password</label>
                                                        <input type="password" id="password" name="password"
                                                        class="form-control" placeholder="Enter your password" maxlength='150'
                                                        required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone_number" class="form-label">Phone Number</label>
                                                        <input type="phone" id="phone_number" name="phone_number"
                                                            class="form-control" placeholder="Enter phone number" maxlength='14'
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address_line_1" class="form-label">Address Line 1</label>
                                                        <input type="text" id="address_line_1" name="address_line_1"
                                                            class="form-control" placeholder="Enter address line 1" maxlength='200'
                                                            required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address_line_2" class="form-label">Address Line 2</label>
                                                        <input type="text" id="address_line_2" name="address_line_2"
                                                            class="form-control" placeholder="Enter address line 2 (Optional)" maxlength='200'
                                                            >
                                                    </div>
                                                    <div class="mb-3">
                                                    <select class="form-select" name="country" id="country" required>
                                                    <option value="" disabled selected>Select your country</option>
                                                    </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">User Role</label>
                                                        <input type="number" id="role" name="role" class="form-control"
                                                            min="0" max="3" maxlength="1" placeholder="Enter user's role" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" name="add_user"
                                                        value="Add">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address </th>
                                <th>Country</th>
                                <th>Role</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        
                       
                        </table>
                    </div>
                </div>
        </div>
        </main>
        <script src="../js/country.js"></script>
        
    

<?php 
include_once("../layout/footer.php")
?>