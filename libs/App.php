<?php
class App
{
    public $host = HOST;
    public $dbname = DBNAME;
    public $user = USER;
    public $password = PASSWORD;

    public $link;


    public function __construct()
    {
        $this->connect();
    }
    public function connect()
    {
        $this->link = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
        if ($this->link) {
            //echo "db connected";
        } else {
            echo "db not connected";
        }
    }
    public function selectAll($query)
    {
        $rows = $this->link->query($query);
        $rows->execute();

        $allRows = $rows->fetchAll(PDO::FETCH_OBJ);
        if ($allRows) {
            return $allRows;
        } else {
            return false;
        }
    }
    public function selectOne($query) {

        $row = $this->link->query($query);
        $row->execute();

        $singleRow = $row->fetch(PDO::FETCH_OBJ);

        if($singleRow) {
            
            return $singleRow;

        } else {

            return false;

        }

    }
    public function validateCart($q) {
        $row = $this->link->query($q);
        $row->execute();
        $count = $row->rowCount();
        return $count;
    }
    public function insert($query, $array, $path)
    {
        if ($this->validate($array) == "empty") {
            echo "<script>alert('one or more inputs are empty')</script>";
        } else {
            $insert_record = $this->link->prepare($query);
            $insert_record->execute($array);

            if ($insert_record) {
                echo "<script>window.location.href='".$path."'</script>";
            } else {
                echo "<script>alert('record not inserted')</script>";
            }
        }
    }
    public function update($query, $array, $path)
    {
        if ($this->validate($array) == "empty") {
            echo "<script>alert('one or more inputs are empty')</script>";
        } else {
            $update_record = $this->link->prepare($query);
            $update_record->execute($array);

            if ($update_record) {
                header("location: $path");
            } else {
                echo "<script>alert('record not updated')</script>";
            }
        }

    }
    public function delete($query, $path)
    {
        $delete_record = $this->link->prepare($query);
        $delete_record->execute();

        echo "<script>window.location.href='".$path."'</script>";
    }
    public function validate($array)
    {
        if (in_array("", $array)) {
            echo "empty";
        }
    }

    public function register($query, $array, $path)
    {
        if ($this->validate($array) == "empty") {
            echo "<script>alert('one or more inputs are empty')</script>";
        } else {
            $register_user = $this->link->prepare($query);
            $register_user->execute($array);

            if ($register_user) {
                header("location: $path");
            } else {
                echo "<script>alert('record not inserted')</script>";
            }
        }
    }
    public function login($query, $data, $path)
    {
        $login_user = $this->link->query($query);
        $login_user->execute();

        $fetch = $login_user->fetch(PDO::FETCH_ASSOC);

        if ($login_user->rowCount() > 0) {
            if (password_verify($data['password'], $fetch['password'])) {
                $_SESSION['email'] = $fetch['email'];
                $_SESSION['user_id'] = $fetch['id'];
                $_SESSION['username'] = $fetch['username'];
                header("location: $path");
            } else {
                echo "<script>alert('password not matched')</script>";
            }
        }

    }
    public function startingSession()
    {
        session_start();
    }
    public function validateSession()
    {
        if (isset($_SESSION['user_id'])) {
            echo "<script>window.location.href='".APPURL."'</script>";
        }
    }
}