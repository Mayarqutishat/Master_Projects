<?php
include_once("../controllers/products_control.php");
include_once("../layout/header.php");
?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php if (isset($_GET['message']) && isset($_GET['type'])): ?>
                    <div class="alert alert-<?php echo htmlspecialchars($_GET['type']); ?> alert-dismissible fade show"
                        role="alert">
                        <?php echo htmlspecialchars($_GET['message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <h1 class="mt-4">Product</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Product</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            product
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <div class="container">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                        Add New Product
                                    </button>
                                </div>


                                <div class="modal fade" id="addUserModal" tabindex="-1"
                                    aria-labelledby="addUserModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addUserModalLabel">Add New Product</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="../controllers/products_control.php" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="product_name" class="form-label">product
                                                            Name</label>
                                                        <input type="text" id="product_name" name="product_name"
                                                            class="form-control" placeholder="Enter the name product"
                                                            maxlength="200" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_description" class="form-label">Product
                                                            Description </label>
                                                        <input type="text" id="product_description"
                                                            name="product_description" class="form-control"
                                                            placeholder="Enter your description" maxlength='150'
                                                            required>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="product_cover" class="form-label">product
                                                            Cover</label>
                                                        <input type="file" id="product_cover" name="product_cover"
                                                            class="form-control" placeholder="Upload your cover"
                                                            maxlength='150' accept=".jpg, .jpeg, .png" required>
                                                    </div>
                                                   
                                                    <div class="mb-3">
                                                        <label for="league_id" class="form-label">League</label>
                                                        <select name="league_id" id="league_id" required>
                                                            <option value="" selected disabled>Select League</option>

                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="team_id" class="form-label">Team</label>
                                                        <select name="team_id" id="team_id" required>
                                                        <option value="" selected disabled>Select Team</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_price" class="form-label">Price</label>
                                                        <input type="number" id="product_price" name="product_price"
                                                            class="form-control" placeholder="Enter price product"
                                                            maxlength='200' required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="product_quantity"
                                                            class="form-label">quantity</label>
                                                        <input type="number" id="product_quantity" name="product_quantity"
                                                            class="form-control" placeholder="Enter Quantity product"
                                                            maxlength='200' required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <input type="submit" class="btn btn-success" name="add_product"
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
                                <th>Name</th>
                                <th>description</th>
                                <th>cover</th>
                                <th>price</th>
                                <th>quantity</th>
                                <th>action</th>
                            </tr>
                        </thead>

                       

                        </table>
                    </div>
                </div>
        </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2023</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         document.addEventListener("DOMContentLoaded", function() {
    fetch('../controllers/get_leagues.php')
        .then(response => response.json())
        .then(data => {
            const leagueSelect = document.getElementById('league_id');
            data.forEach(league => {
                let option = document.createElement('option');
                option.value = league.id;
                option.textContent = league.name;
                leagueSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error("Error fetching leagues:", error);
        });
});

// Fetch teams when a league is selected
document.getElementById('league_id').addEventListener('change', function() {
    const leagueId = this.value;
    const teamSelect = document.getElementById('team_id');
    teamSelect.innerHTML = '<option value="" selected disabled>Select a team</option>'; 

    fetch('../controllers/get_teams.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'league_id=' + encodeURIComponent(leagueId)
    })
    .then(response => response.json())
    .then(data => {
        data.forEach(team => {
            let option = document.createElement('option');
            option.value = team.id;
            option.textContent = team.name;
            teamSelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error("Error fetching teams:", error);
    });
});

    </script>
  

</body>

</html>