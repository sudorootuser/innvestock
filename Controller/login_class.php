<?php
include_once 'main_class.php';
class userClass
{
    public function userLogin($username, $password)
    {
        try {
            $db = getDB();
            $hash_password = encrypt_decrypt('encrypt', $password); //Password encryption 


            $stmt = $db->prepare("SELECT idUsuario,usuario_nombre,usuario_idRol,usuario_apellido,usuario_documento, usuario_correo, last_session ,usuario_idBodega
            FROM usuario WHERE usuario_correo=:email OR usuario_documento=:document 
            AND usuario_password=:hash_password");
            $stmt->bindParam("email", $username, PDO::PARAM_STR);
            $stmt->bindParam("document", $username, PDO::PARAM_STR);
            $stmt->bindParam("hash_password", $hash_password, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->rowCount();
            $data = $stmt->fetch(PDO::FETCH_OBJ);


            if ($count >= 1) {

                $_SESSION['idUsuario'] = $data->idUsuario; // Almmacenando la session del id del usuario
                $_SESSION['us_nombre'] = $data->usuario_nombre; // Almacenando el nombre del usuario
                $_SESSION['usuario_Rol'] = $data->usuario_idRol; // Almacenando el rol del usuario
                $_SESSION['us_apellido'] = $data->usuario_apellido; // Almacenando el rol del usuario
                $_SESSION['last_session'] = $data->last_session; // Almacenando la ultima session del usuario
                $_SESSION['bodega'] = $data->usuario_idBodega; // Almacenando el tipo de bodega

                // Se actualiza la fecha del ingreso
                $db = getDB();
                $date = date('Y-m-d H:i:s', time());
                $tm = $db->prepare("UPDATE usuario SET last_session=:dat WHERE idUsuario=:id");
                $tm->bindParam(":dat", $date, PDO::PARAM_STR);
                $tm->bindParam(":id", $_SESSION['idUsuario'], PDO::PARAM_INT);

                $tm->execute();

                $db = null;

                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
}
