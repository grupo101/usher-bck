<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "usuarios";
 
    // object properties
    public $id;
    public $name;
    public $surname;
    public $username;
    public $password;
    public $access;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
        // used when filling up the update user form
        function readOne(){
        
            // select all query
            $query = "SELECT
                        u.userID as id, 
                        u.name, 
                        u.surname, 
                        u.username, 
                        u.password, 
                        u.access
                    FROM " . $this->table_name . " u
                    WHERE
                        u.userID = ?
                    LIMIT
                        0,1";
        
            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        
            // bind id of user to be updated
            $stmt->bindParam(1, $this->id);
        
            // execute query
            $stmt->execute();
        
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // set values to object properties
            $this->name = $row['name'];
            $this->surname = $row['surname'];
            $this->username = $row['username'];
            $this->password = $row['password'];
            $this->access = $row['access'];
        }
    
    // read users
    function read(){
    
        // select all query
        $query = "SELECT
                    u.userID as id, 
                    u.name, 
                    u.surname, 
                    u.username, 
                    u.password, 
                    u.access
                FROM " . $this->table_name . " u
                ORDER BY
                    u.username DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // read users with pagination
    public function readPaging($from_record_num, $records_per_page){
    
        // select query
        $query = "SELECT
                    u.userID as id, 
                    u.name, 
                    u.surname, 
                    u.username, 
                    u.password, 
                    u.access
                FROM
                    " . $this->table_name . " u
                ORDER BY u.username DESC
                LIMIT ?, ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind variable values
        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    
        // execute query
        $stmt->execute();
    
        // return values from database
        return $stmt;
    }

    // used for paging products
    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['total_rows'];
    }

    // create user
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, 
                    surname=:surname, 
                    username=:username, 
                    password=:password, 
                    access=0, 
                    tstamp=NULL";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // update the user
    function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    surname = :surname,
                    username = :username,
                    password = :password,
                    tstamp = NULL
                WHERE
                    userID = :id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->surname=htmlspecialchars(strip_tags($this->surname));
        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));
        
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":surname", $this->surname);
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if($stmt->execute()){
            echo $stmt->rowCount();
            return ($stmt->rowCount() > 0);
        }
    
        return false;
    }

    // delete the user
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " 
                WHERE userID = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return ($stmt->rowCount() > 0);
        }
    
        return false;

    }

    // search users
    function search($keywords){
    
        // select all query
        $query = "SELECT
                    u.userID as id, 
                    u.name, 
                    u.surname, 
                    u.username, 
                    u.password, 
                    u.access
                FROM
                    " . $this->table_name . " u
                WHERE
                    u.name LIKE ? OR u.surname LIKE ? OR u.username LIKE ?
                ORDER BY
                    u.username DESC";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $keywords=htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";
    
        // bind
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

}