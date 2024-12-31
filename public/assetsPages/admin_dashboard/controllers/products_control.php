<?php
require_once("../connection/conn.php");
class CRUD extends connection{


    public function readData() {
        $query = " SELECT products.*,leagues.name AS league_name, teams.name AS team_name
                FROM products
                    left JOIN leagues ON products.league_id = leagues.id
                    left JOIN teams ON products.team_id = teams.id 
                    
                    where products.deleted=false";

        $products = $this->dbconnection->query($query);
        
        if ($products->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($products as $product) {
                echo "<tr>
                        <td>$product[id]</td>
                        <td>$product[name]</td>
                        <td>$product[description]</td>
                        
                        <td><img src='$product[cover]' width='75' height='75'></td>
                        <td>$product[league_name]</td>
                        <td>$product[team_name]</td>
                        <td>$product[price]</td>
                        <td>$product[quantity]</td>
                        <td>
                            <a href='#' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#editModal-$product[id]'>Edit</a>
                            <a href='#' class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal-$product[id]'>Delete</a>
                        </td>
                    </tr>";
    
                // Edit product (for each product)
                echo "
                <div class='modal fade' id='editModal-$product[id]' tabindex='-1' aria-labelledby='editModalLabel-$product[id]' aria-hidden='true'>
                    <div class='modal-dialog'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='editModalLabel-$product[id]'>Edit Product</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                            </div>
                            <form action='product.php' method='post'>
                                <div class='modal-body'>
                                    <input type='hidden' name='id' value='$product[id]'>
                                    <div class='mb-3'>
                                        <label for='product_name' class='form-label'>product name</label>
                                        <input type='text' name='product_name' class='form-control' value='$product[name]' maxlength='200' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_description' class='form-label'>product description</label>
                                        <input type='text' name='product_description' class='form-control' value='$product[description]' maxlength='150' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_cover' class='form-label'>product cover</label>
                                        <input type='file' name='product_cover' class='form-control' value ='$product[cover]' required>
                                    </div>
                                    <div class='mb-3'>
                                

                                         <label for='league_id' class='form-label'>League</label>
                                                        <select name='league_id' id='league_id' value='$product[league_id]' >
                                                            <option value='1'>Premier League</option>
                                                            <option value='2'>La Liga</option>
                                                            <option value='3'>Bundesliga</option>
                                                        </select>
                                    </div>
                                    <div class='mb-3'>

                                         <label for='team_id' class='form-label'>Team</label>
                                                        <select name='team_id' id='team_id' value='$product[team_id]'>
                                                            
                                                            <option value='1'>Manchester United</option>
                                                            <option value='2'>Real Madrid</option>
                                                            <option value='3'>Bayern Munich</option>
                                                            <option value='4'>Barclona</option>
                                                            <option value='5'>Liverpool</option>
                                                        </select>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_price' class='form-label'>product price</label>
                                        <input type='number' name='product_price' class='form-control' value='$product[price]' maxlength='100' required>
                                    </div>
                                    <div class='mb-3'>
                                        <label for='product_quantity' class='form-label'>product quantity</label>
                                        <input type='number' name='product_quantity' class='form-control' value='$product[quantity]' required>
                                    </div>
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                    <input type='submit' class='btn btn-success' name='update_product' value='Update'>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                ";

                echo "
            <div class='modal fade' id='deleteModal-$product[id]' tabindex='-1' aria-labelledby='deleteModalLabel-$product[id]' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='deleteModalLabel-$product[id]'>Confirm Delete</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <p>Are you sure you want to delete this user?</p>
                        </div>
                        <div class='modal-footer'>
                            <form action='../pages/products.php' method='get'>
                                <input type='hidden' name='id' value='$product[id]'>
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
        if (isset($_POST['add_product'])) {
            $product_name = $_POST['product_name'];
            $product_description = $_POST['product_description'];
            // $product_cover = "product_cover";
            $product_cover = $_FILES['product_cover']['name'];
            $tempname= $_FILES['product_cover']['tmp_name'];
            $folder = 'images/'.$product_cover;
            move_uploaded_file($tempname , $folder);
            // $allowed_extensions = array(".jpg","jpeg",".png",".gif");
            // $extension = substr($tempname,strlen($tempname)-4,strlen($tempname));
            // if(!in_array($extension,$allowed_extensions))
            //     {
            //     echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
            //     }
                // else
                
                // {
                // //rename the image file
                // $imgnewfile=md5($imgfile).$extension;
                // // Code for move image into directory
                // move_uploaded_file($_FILES["image"]["tmp_name"],"images/".$folder);
                // // Query for insertion data into database
                // $query=mysqli_query($con,"insert into tblimages(ImagesTitle,Image) values('$imgtitle','$imgnewfile')");
           
            $league_id = $_POST['league_id'];
            $team_id = $_POST['team_id'];
            $product_price = $_POST['product_price'];
            $product_quantity = $_POST['product_quantity'];
        
            $query = "INSERT INTO `products` (`name`, `description`, `cover`,`league_id`, `team_id`, `price`,`quantity`) VALUES (:product_name, :product_description, :product_cover, :league_id, :team_id, :product_price, :product_quantity)";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':product_name', $product_name);
            $statement->bindParam(':product_description', $product_description);
            $statement->bindParam(':product_cover', $folder);
            $statement->bindParam(':league_id', $league_id);
            $statement->bindParam(':team_id', $team_id);
            $statement->bindParam(':product_price', $product_price);
            $statement->bindParam(':product_quantity', $product_quantity);

            
            if ($statement->execute()) {
                $_SESSION['message'] = "User added successfully!";
                header('Location: ../pages/products.php?message=User added successfully');
                exit();
            }
        }
    }


    public function getUserById($id) {
        $query = "SELECT * FROM `products` WHERE `id` = :id";
        $statement = $this->dbconnection->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProduct() {
        if (isset($_POST['update_product'])) {

            $product_name = $_POST['product_name'];
            $product_description = $_POST['product_description'];
            // $product_cover = $_POST['product_cover'];
            
            $product_cover = $_FILES['product_cover']['name'];
            $tempname= $_FILES['product_cover']['tmp_name'];
            $folder = 'images/'.$product_cover;
            move_uploaded_file($tempname , $folder);

            $league_id = $_POST['league_id'];
            $team_id = $_POST['team_id'];
            $product_price = $_POST['product_price'];
            $product_quantity = $_POST['product_quantity'];
            $id = $_POST['id'];
    
            // $query = "UPDATE `products` SET `product_name` = :product_name, `product_description` = :product_description, `product_cover` = :product_cover, `league_id` = :league_id, `team_id` = :team_id, `product_price` = :product_price, `product_quantity` = :product_quantity WHERE `id` = :id";

            $query = "UPDATE `products` SET `name` = :product_name, `description` = :product_description, `cover` = :product_cover, `league_id` = :league_id, `team_id` = :team_id, `price` = :product_price, `quantity` = :product_quantity WHERE `id` = :id";
    
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':product_name', $product_name);
            $statement->bindParam(':product_description', $product_description);
            $statement->bindParam(':product_cover', $product_cover);
            $statement->bindParam(':league_id', $league_id);
            $statement->bindParam(':team_id', $team_id);
            $statement->bindParam(':product_price', $product_price);
            $statement->bindParam(':product_quantity', $product_quantity);
            $statement->bindParam(':id', $id);
    
            if ($statement->execute()) {
                $_SESSION['message'] = "User updated successfully!";
                header('Location: ../pages/products.php?message= product updated successfully');
                exit();
            }
        }
    }
    

        public function deleteUser($id) {
            $query = "UPDATE products SET deleted = true WHERE id = :id";
            $statement = $this->dbconnection->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            
            if ($statement->execute()) {
                $_SESSION['message'] = "User deleted successfully!";
                header('Location: ../c/product.php?message=User deleted successfully');
                exit();
            }
        }
    

}

$products = new CRUD();
$products->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $products->createFormData(); // Handle add user
    $products->updateProduct(); // Handle update product
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    $userId = $_GET['id'];
    $products->deleteUser($userId); // Call the delete user method
}


?>