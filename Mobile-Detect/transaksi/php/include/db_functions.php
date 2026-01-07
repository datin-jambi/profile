<?php
class DB_Functions {

    private $conn;

    function __construct() {
        require_once ('db_connect.php');
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    function __destruct() {
    }
    

    //Mengambil dan mencocokkan data penjual berdasarkan email dan password
    //Digunakan untuk login penjual di aplikasi
    public function getPenjualByEmailAndPassword($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM tb_penjual WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $salt = $user['salt'];
            $encrypted_password = $user['password'];
            $hash = $this->checkhashSSHA($salt, $password);
            if ($encrypted_password == $hash) {
			    return $user;
            }
        } else {
          return NULL;
        }
    }
    
    //Membuat akun pembeli saat registrasi
    public function storePembeli($nama, $email, $password, $phone) {
        //$uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"];
        $salt = $hash["salt"];
        $stmt = $this->conn->prepare("INSERT INTO tb_pembeli(nama,email,password,salt,no_telp,created_at) VALUES(?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $nama, $email, $encrypted_password, $salt, $phone);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pembeli WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Melakukan proses enkripsi password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Melakukan proses dekripsi password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }
        
    //Meng-generate token
    public function getToken($length){
     $token = "";
     $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
     $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
     $codeAlphabet.= "0123456789";
     $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_bytes(0, $max-1)];
    }

    return $token;
    }
}
?>
