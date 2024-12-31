<?php
require_once("../connection/conn.php");

class CRUD extends Connection {

    public function readData() {
        // Fetch leagues to populate dropdown
        $leagues = $this->getLeagues();
    
        $query = "SELECT teams.id, teams.name AS team_name, teams.description, teams.league_id, leagues.name AS league_name FROM teams LEFT JOIN leagues ON teams.league_id = leagues.id WHERE teams.deleted = false";
        $teams = $this->dbconnection->query($query);
    
        if ($teams->rowCount() == 0) {
            echo "Empty table";
        } else {
            foreach ($teams as $team) {
                echo "<tr>
                        <td>{$team['id']}</td>
                        <td>{$team['team_name']}</td>
                        <td>{$team['description']}</td>
                        <td>{$team['league_name']}</td> <!-- Display League Name -->
                        <td>
                            <a href='#' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal-{$team['id']}'>Edit</a>
                            <a href='#' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal-{$team['id']}'>Delete</a>
                        </td>
                    </tr>";
    
                // Edit Modal
                echo "
                <div class='modal fade' id='editModal-{$team['id']}' tabindex='-1' aria-labelledby='editModalLabel-{$team['id']}' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-{$team['id']}'>Edit Team</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='../controllers/teams_control.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='{$team['id']}'>
                                    <div class='mb-3'>
                                        <label for='name' class='form-label'>Name</label>
                                        <input type='text' name='name' class='form-control' value='{$team['team_name']}' maxlength='200' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='description' class='form-label'>Description</label>
                                        <textarea name='description' class='form-control' maxlength='500' required>{$team['description']}</textarea>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='league_id' class='form-label'>League</label>
                                        <select class='form-control' id='league_id' name='league_id' required>
                                            <option value=''>Select League</option>";
                foreach ($leagues as $league) {
                    // Check if the current team is in the league and set selected attribute
                    $selected = ($team['league_id'] == $league['id']) ? 'selected' : '';
                    echo "<option value='{$league['id']}' {$selected}>{$league['name']}</option>";
                }
                echo "
                                        </select>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_team' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>";
    
                // Delete Modal (remains unchanged)
                echo "
                <div class='modal fade' id='deleteModal-{$team['id']}' tabindex='-1' aria-labelledby='deleteModalLabel-{$team['id']}' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='deleteModalLabel-{$team['id']}'>Confirm Delete</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p>Are you sure you want to delete this team?</p>
                            </div>
                            <div class='modal-footer'>
                                <form action='../controllers/teams_control.php' method='get'>
                                    <input type='hidden' name='id' value='{$team['id']}'>
                                    <input type='hidden' name='action' value='delete'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>
                                    <button type='submit' class='btn btn-danger'>Yes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>";
   

                // Delete Modal
                echo "
                <div class='modal fade' id='deleteModal-{$team['id']}' tabindex='-1' aria-labelledby='deleteModalLabel-{$team['id']}' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='deleteModalLabel-{$team['id']}'>Confirm Delete</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <div class='modal-body'>
                                <p>Are you sure you want to delete this team?</p>
                            </div>
                            <div class='modal-footer'>
                                <form action='../controllers/teams_control.php' method='get'>
                                    <input type='hidden' name='id' value='{$team['id']}'>
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
        if (isset($_POST['add_team'])) {
            $name = $_POST['team_name']; // Make sure to match the name in the form
            $description = $_POST['team_description']; // Match this as well
            $league_id = $_POST['league_id']; // This should correspond to the dropdown name
    
            $query = "INSERT INTO `teams` (`name`, `description`, `league_id`, `deleted`) VALUES (:name, :description, :league_id, false)";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':description', $description);
            $statement->bindParam(':league_id', $league_id); // Bind the league ID from the dropdown
    
            // Execute the statement and handle the result
            if ($statement->execute()) {
                $_SESSION['message'] = "Team added successfully!";
                header('Location: ../pages/teams.php?message=Team added successfully');
                exit();
            } else {
                $_SESSION['message'] = "Failed to add team.";
                header('Location: ../pages/teams.php?message=Failed to add team');
                exit();
            }
        }
    }

    public function updateTeam() {
        if (isset($_POST['update_team'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $league_id = $_POST['league_id']; // Get the league ID from the form
    
            // Update query to include league_id
            $query = "UPDATE `teams` SET `name` = :name, `description` = :description, `league_id` = :league_id WHERE `id` = :id";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':description', $description);
            $statement->bindParam(':league_id', $league_id); // Bind the league ID
            $statement->bindParam(':id', $id);
    
            if ($statement->execute()) {
                $_SESSION['message'] = "Team updated successfully!";
                header('Location: ../pages/teams.php?message=Team updated successfully');
                exit();
            }
        }
    }
    

    public function deleteTeam($id) {
        $query = "UPDATE teams SET deleted = true WHERE id = :id";
        $statement = $this->dbconnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        if ($statement->execute()) {
            $_SESSION['message'] = "Team deleted successfully!";
            header('Location: ../pages/teams.php?message=Team deleted successfully');
            exit();
        }
    }
    public function getLeagues() {
        $query = "SELECT id, name FROM leagues WHERE deleted = false";
        $result = $this->dbconnection->query($query);

        // Prepare an array to store leagues
        $leagues = [];

        if ($result && $result->rowCount() > 0) {
            foreach ($result as $league) {
                $leagues[] = $league; // Store each league in the array
            }
        }
        return $leagues; // Return the array of leagues
    }

}

$teams = new CRUD();
$teams->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teams->createFormData(); // Handle add team
    $teams->updateTeam(); // Handle update team
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $teamId = $_GET['id'];
    $teams->deleteTeam($teamId); // Call delete team method
}
?>
