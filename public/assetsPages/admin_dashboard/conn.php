<?php
class connection{
    protected $server="localhost";
    protected $username="root";
    protected $password="";
    protected $dbname="ecommerce_db";
    protected $dsn;
    public $dbconnection;
    public $query;

    public function connect (){
        try{
            $this->dsn="mysql:host=$this->server;dbname=$this->dbname";
            $this->dbconnection=new PDO ($this->dsn,$this->username,$this->password);

            echo "Database Connected";

            }catch (PDOException $error){
            echo $error;
            }

                
    }
}

class CRUD extends connection{


    public function readData() {
        $query = "SELECT * FROM `users` WHERE deleted = false";
        $users = $this->dbconnection->query($query);
        
        if ($users->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($users as $user) {
                echo "<tr>
                        <td>$user[id]</td>
                        <td>$user[user_name]</td>
                        <td>$user[email]</td>
                        <td>$user[phone_number]</td>
                        <td>$user[address_line_1]</td>
                        <td>$user[address_line_2]</td>
                        <td>$user[country]</td>
                        <td>$user[role]</td>
                        <td>
                            <a href='#' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal-$user[id]'>Edit</a>
                            <a href='#' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal-$user[id]'>Delete</a>
                        </td>
                    </tr>";
    
                // Edit User Modal (for each user)
                echo "
                <div class='modal fade' id='editModal-$user[id]' tabindex='-1' aria-labelledby='editModalLabel-$user[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-$user[id]'>Edit User</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='conn.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='$user[id]'>
                                    <div class='mb-3'>
                                        <label for='user_name' class='form-label'>User Name</label>
                                        <input type='text' name='user_name' class='form-control' value='$user[user_name]' maxlength='200' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='email' class='form-label'>Email</label>
                                        <input type='email' name='email' class='form-control' value='$user[email]' maxlength='150' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='phone_number' class='form-label'>Phone Number</label>
                                        <input type='tel' name='phone_number' class='form-control' value='$user[phone_number]' maxlength='14'required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='address_line_1' class='form-label'>Address line 1</label>
                                        <input type='text' name='address_line_1' class='form-control' value='$user[address_line_1]' maxlength='200'required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='address_line_2' class='form-label'>Address line 2</label>
                                        <input type='text' name='address_line_2' class='form-control' value='$user[address_line_2]' maxlength='200'>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='country' class='form-label'>Country</label>
                                        <input type='text' name='country' class='form-control' value='$user[country]' maxlength='100' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='role' class='form-label'>Role</label>
                                        <input type='number' name='role' class='form-control' value='$user[role]' min='0' max='3' maxlength='1'required>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_user' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                ";

                echo "
            <div class='modal fade' id='deleteModal-$user[id]' tabindex='-1' aria-labelledby='deleteModalLabel-$user[id]' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='deleteModalLabel-$user[id]'>Confirm Delete</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <p>Are you sure you want to delete this user?</p>
                        </div>
                        <div class='modal-footer'>
                            <form action='conn.php' method='get'>
                                <input type='hidden' name='id' value='$user[id]'>
                                <input type='hidden' name='action' value='delete'>
                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>No</button>
                                <button type='submit' class='btn btn-danger'>Yes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            ";
            }
        }
    }
    
    public function createFormData() {
        if (isset($_POST['add_user'])) {
            $user_name = $_POST['user_name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $phone_number = $_POST['phone_number'];
            $address_line_1 = $_POST['address_line_1'];
            $address_line_2 = $_POST['address_line_2'];
            $country = $_POST['country'];
            $role = $_POST['role'];
        
            $query = "INSERT INTO `users` (`user_name`, `email`, `password`,`phone_number`, `address_line_1`, `address_line_2`,`country`,  `role`) VALUES (:user_name, :email, :password, :phone_number, :address_line_1, :address_line_2, :country, :role)";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':user_name', $user_name);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':password', $password);
            $statement->bindParam(':phone_number', $phone_number);
            $statement->bindParam(':address_line_1', $address_line_1);
            $statement->bindParam(':address_line_2', $address_line_2);
            $statement->bindParam(':country', $country);
            $statement->bindParam(':role', $role);
            
            if ($statement->execute()) {
                $_SESSION['message'] = "User added successfully!";
                header('Location: tables.php?message=User added successfully');
                exit();
            }
        }
    }


    public function getUserById($id) {
        $query = "SELECT * FROM `users` WHERE `id` = :id";
        $statement = $this->dbconnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser() {
        if (isset($_POST['update_user'])) {
            $id = $_POST['id'];
            $user_name = $_POST['user_name'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $address_line_1 = $_POST['address_line_1'];
            $address_line_2 = $_POST['address_line_2'];
            $country = $_POST['country'];
            $role = $_POST['role'];
    
            $query = "UPDATE `users` SET `user_name` = :user_name, `email` = :email, `phone_number` = :phone_number, `address_line_1` = :address_line_1, `address_line_2` = :address_line_2, `country` = :country, `role` = :role WHERE `id` = :id";
    
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':user_name', $user_name);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':phone_number', $phone_number);
            $statement->bindParam(':address_line_1', $address_line_1);
            $statement->bindParam(':address_line_2', $address_line_2);
            $statement->bindParam(':country', $country);
            $statement->bindParam(':role', $role);
            $statement->bindParam(':id', $id);
    
            if ($statement->execute()) {
                $_SESSION['message'] = "User updated successfully!";
                header('Location: tables.php?message=User updated successfully');
                exit();
            }
        }
    }
    

        public function deleteUser($id) {
            $query = "UPDATE users SET deleted = true WHERE id = :id";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($statement->execute()) {
                $_SESSION['message'] = "User deleted successfully!";
                header('Location: tables.php?message=User deleted successfully');
                exit();
            }
        }
    

}

$users = new CRUD();
$users->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users->createFormData(); // Handle add user
    $users->updateUser(); // Handle update user
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $userId = $_GET['id'];
    $users->deleteUser($userId); // Call the delete user method
}


?>