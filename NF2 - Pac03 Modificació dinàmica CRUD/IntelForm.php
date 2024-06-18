<?php
include 'db.php';

class IntelForm {
    private $objPDO;

    public function __construct($objPDO) {
        $this->objPDO = $objPDO;
    }

    public function handleCommand($command) {
        $response = "";

        if (preg_match('/^create\s+(\w+)$/', $command, $matches)) {
            $response .= $this->createForm($matches[1]);
        } elseif (preg_match('/^read\s+(\w+)\s+all$/', $command, $matches)) {
            $response .= $this->readAll($matches[1]);
        } elseif (preg_match('/^read\s+(\w+)\s+(\d+)$/', $command, $matches)) {
            $response .= $this->readItem($matches[1], $matches[2]);
        } elseif (preg_match('/^update\s+(\w+)\s+(\d+)$/', $command, $matches)) {
            $response .= $this->updateForm($matches[1], $matches[2]);
        } elseif (preg_match('/^delete\s+(\w+)\s+(\d+)$/', $command, $matches)) {
            $response .= $this->deleteForm($matches[1], $matches[2]);
        }

        echo $response;
    }

    public function validateForm($postData) {
        $errors = [];
        $data = [];

        if (empty($postData['name'])) {
            $errors['name'] = 'Name is required.';
        }

        if (empty($postData['email'])) {
            $errors['email'] = 'Email is required.';
        }

        if (empty($postData['superheroAlias'])) {
            $errors['superheroAlias'] = 'Superhero alias is required.';
        }

        if (!empty($errors)) {
            $data['success'] = false;
            $data['errors'] = $errors;
        } else {
            $data['success'] = true;
            $data['message'] = 'Success!';
        }

        return json_encode($data);
    }

    public function createTable($table_name, $columns, $types) {
        $columns_query = "";
        for ($i = 0; $i < count($columns); $i++) {
            $columns_query .= $columns[$i] . " " . $types[$i] . ",";
        }
        $columns_query = rtrim($columns_query, ',');

        $sql = "CREATE TABLE $table_name (".$table_name."_id SERIAL PRIMARY KEY, $columns_query)";
        try {
            $this->objPDO->exec($sql);
            return "Table $table_name created successfully";
        } catch (PDOException $e) {
            return "Error creating table: " . $e->getMessage();
        }
    }

    public function updateItem($table_name, $id, $fields) {
        $primary_key = $table_name . '_id';
        $update_query = "";
        foreach ($fields as $key => $value) {
            $update_query .= "$key = :$key,";
        }
        $update_query = rtrim($update_query, ',');

        $sql = "UPDATE $table_name SET $update_query WHERE $primary_key = :id";
        try {
            $stmt = $this->objPDO->prepare($sql);
            foreach ($fields as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "Item updated successfully";
        } catch (PDOException $e) {
            return "Error updating item: " . $e->getMessage();
        }
    }

    public function deleteItem($table_name, $id) {
        $primary_key = $table_name . '_id';
        $sql = "DELETE FROM $table_name WHERE $primary_key = :id";
        try {
            $stmt = $this->objPDO->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return "Item deleted successfully";
        } catch (PDOException $e) {
            return "Error deleting item: " . $e->getMessage();
        }
    }

    private function createForm($table_name) {
        return '<form id="createTableForm">
                    <input type="text" name="tableName" value="' . $table_name . '" readonly>
                    <div id="columnsContainer">
                        <input type="text" name="columns[]" placeholder="Column Name">
                        <input type="text" name="types[]" placeholder="Data Type">
                    </div>
                    <button type="button" id="addColumn">Add Column</button>
                    <button type="submit">Create Table</button>
                </form>';
    }

    private function readAll($table_name) {
        $stmt = $this->objPDO->query("SELECT * FROM $table_name");
        $response = '<table border="1"><tr>';

        for ($i = 0; $i < $stmt->columnCount(); $i++) {
            $columnMeta = $stmt->getColumnMeta($i);
            $response .= '<th>' . $columnMeta['name'] . '</th>';
        }

        $response .= '</tr>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response .= '<tr>';
            foreach ($row as $cell) {
                $response .= '<td>' . $cell . '</td>';
            }
            $response .= '</tr>';
        }

        $response .= '</table>';
        return $response;
    }

    private function readItem($table_name, $id) {
        $primary_key = $table_name . '_id';
        $stmt = $this->objPDO->prepare("SELECT * FROM $table_name WHERE $primary_key = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $response = '';

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response .= '<table border="1"><tr>';
            foreach ($row as $key => $value) {
                $response .= '<th>' . $key . '</th>';
            }
            $response .= '</tr><tr>';
            foreach ($row as $value) {
                $response .= '<td>' . $value . '</td>';
            }
            $response .= '</tr></table>';
        } else {
            $response .= 'No item found';
        }

        return $response;
    }

    private function updateForm($table_name, $id) {
        $primary_key = $table_name . '_id';
        $stmt = $this->objPDO->prepare("SELECT * FROM $table_name WHERE $primary_key = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $response = '';

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response .= '<form id="updateForm">';
            $response .= '<input type="hidden" name="tableName" value="' . $table_name . '">';
            $response .= '<input type="hidden" name="id" value="' . $id . '">';
            foreach ($row as $key => $value) {
                $response .= '<input type="text" name="' . $key . '" value="' . $value . '">';
            }
            $response .= '<button type="submit">Update Item</button></form>';
        } else {
            $response .= 'No item found';
        }

        return $response;
    }

    private function deleteForm($table_name, $id) {
        $primary_key = $table_name . '_id';
        $stmt = $this->objPDO->prepare("SELECT * FROM $table_name WHERE $primary_key = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $response = '';

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $response .= '<form id="deleteForm">';
            $response .= '<input type="hidden" name="tableName" value="' . $table_name . '">';
            $response .= '<input type="hidden" name="id" value="' . $id . '">';
            $response .= '<table border="1"><tr>';
            foreach ($row as $key => $value) {
                $response .= '<th>' . $key . '</th>';
            }
            $response .= '</tr><tr>';
            foreach ($row as $value) {
                $response .= '<td>' . $value . '</td>';
            }
            $response .= '</tr></table>';
            $response .= '<button type="submit" id="confirmDelete">Confirm Delete</button>';
            $response .= '</form>';
        } else {
            $response .= 'No item found';
        }

        return $response;
    }
}

// Usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $command = $_POST['command'] ?? null;
    $form = new IntelForm($objPDO);

    if ($command) {
        $form->handleCommand($command);
    } elseif (isset($_POST['create'])) {
        $table_name = $_POST['tableName'];
        $columns = $_POST['columns'];
        $types = $_POST['types'];
        echo $form->createTable($table_name, $columns, $types);
    } elseif (isset($_POST['update'])) {
        $table_name = $_POST['tableName'];
        $id = $_POST['id'];
        $fields = array_filter($_POST, function($key) {
            return $key !== 'tableName' && $key !== 'id' && $key !== 'update';
        }, ARRAY_FILTER_USE_KEY);
        echo $form->updateItem($table_name, $id, $fields);
    } elseif (isset($_POST['delete'])) {
        $table_name = $_POST['tableName'];
        $id = $_POST['id'];
        echo $form->deleteItem($table_name, $id);
    } elseif (isset($_POST['validate'])) {
        echo $form->validateForm($_POST);
    }
}
?>
