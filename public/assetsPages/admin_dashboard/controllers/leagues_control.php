<?php
require_once("../connection/conn.php");

class CRUD extends connection {

    public function readData() {
        $query = "SELECT * FROM `leagues` WHERE deleted='false'";
        $leagues = $this->dbconnection->query($query);
        
        if ($leagues->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($leagues as $league) {
                echo "<tr>
                        <td>$league[id]</td>
                        <td>$league[name]</td>
                        <td>$league[description]</td>
                        <td>
                            <a href='#' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal-$league[id]'>Edit</a>
                            <a href='#' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal-$league[id]'>Delete</a>
                        </td>
                    </tr>";

                // Edit Modal
                echo "
                <div class='modal fade' id='editModal-$league[id]' tabindex='-1' aria-labelledby='editModalLabel-$league[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-$league[id]'>Edit League</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../controllers/leagues_control.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='$league[id]'>
                                    <div class='mb-3'>
                                        <label for='name' class='form-label'>Name</label>
                                        <input type='text' name='name' class='form-control' value='$league[name]' maxlength='200' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='description' class='form-label'>Description</label>
                                        <textarea name='description' class='form-control' maxlength='500' required>$league[description]</textarea>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_league' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";

                // Delete Modal
                echo "
                <div class='modal fade' id='deleteModal-$league[id]' tabindex='-1' aria-labelledby='deleteModalLabel-$league[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='deleteModalLabel-$league[id]'>Confirm Delete</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p>Are you sure you want to delete this league?</p>
                            </div>
                            <div class='modal-footer'>
                                <form action='../controllers/leagues_control.php' method='get'>
                                    <input type='hidden' name='id' value='$league[id]'>
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
        if (isset($_POST['add_league'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
        
            $query = "INSERT INTO `leagues` (`name`, `description`) VALUES (:name, :description)";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':description', $description);
            
            if ($statement->execute()) {
                $_SESSION['message'] = "League added successfully!";
                header('Location: ../pages/leagues.php?message=League added successfully');
                exit();
            }
        }
    }

    public function updateLeague() {
        if (isset($_POST['update_league'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
    
            $query = "UPDATE `leagues` SET `name` = :name, `description` = :description WHERE `id` = :id";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':description', $description);
            $statement->bindParam(':id', $id);
    
            if ($statement->execute()) {
                $_SESSION['message'] = "League updated successfully!";
                header('Location: ../pages/leagues.php?message=League updated successfully');
                exit();
            }
        }
    }

    public function deleteLeague($id) {
        $query = "UPDATE leagues SET deleted = true WHERE id = :id";
        $statement = $this->dbconnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($statement->execute()) {
            $_SESSION['message'] = "League deleted successfully!";
            header('Location: ../pages/leagues.php?message=League deleted successfully');
            exit();
        }
    }
}

$leagues = new CRUD();
$leagues->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leagues->createFormData(); // Handle add league
    $leagues->updateLeague(); // Handle update league
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $leagueId = $_GET['id'];
    $leagues->deleteLeague($leagueId); // Call delete league method
}
?>
