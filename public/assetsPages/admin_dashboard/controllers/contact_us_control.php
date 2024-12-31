<?php
require_once("../connection/conn.php");

class CRUD extends connection {

    public function readData() {
        $query = "SELECT contact_us.id, contact_us.user_id, contact_us.contact_email,contact_us.name, users.email, contact_us.message 
                  FROM contact_us 
                  JOIN users ON contact_us.user_id = users.id";
                  
        $messages = $this->dbconnection->query($query);

        if ($messages->rowCount() == 0) {
            echo ("empty table");
        } else {
            foreach ($messages as $message) {
                echo "<tr>
                        <td>{$message['id']}</td>
                        <td>{$message['email']}</td>
                        <td>{$message['name']}</td>
                        <td>{$message['contact_email']}</td>
                        <td>{$message['message']}</td>
                    </tr>";
            }
        }
    }

}

?>
