<?php
date_default_timezone_set("Asia/Bangkok");
require_once("./connect.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Disposition, Content-Type, Content-Length, Accept-Encoding");
header("Content-type:application/json");
error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $req = (object) json_decode(file_get_contents("php://input"));
    if ($req->router == "addProduct") {
        //C = Create
        try {
            $prod_name = $req->prod_name;
            $prod_price = $req->prod_price;
            $prod_stock = $req->prod_stock;
            if (empty($prod_name) || empty($prod_name) || empty($prod_name)) {
                echo json_encode(["err" => true, "msg" => "Data is empty!"]);
            } else {
                $stmt_add = $conn->prepare("INSERT INTO tb_products (prod_name,prod_price,prod_stock) VALUES (?,?,?)");
                $stmt_add->execute([$prod_name, $prod_price, $prod_stock]);
                if ($stmt_add) {
                    echo json_encode(["err" => false, "msg" => "Product added successfully!"]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    } else if ($req->router == "getProducts") {
        //R = Read 
        $stmt_get = $conn->prepare("SELECT * FROM tb_products ORDER BY id DESC");
        $stmt_get->execute();
        $result_get = $stmt_get->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result_get);
    } else if ($req->router == "updateProduct") {
        // U = Update
        try {
            $id = $req->id;
            $prod_name = $req->prod_name;
            $prod_price = $req->prod_price;
            $prod_stock = $req->prod_stock;
            if (empty($id) || empty($prod_name) || empty($prod_price) || empty($prod_stock)) {
                echo json_encode(["err" => true, "msg" => "Data is empty!"]);
            } else {
                $stmt_checkdb = $conn->prepare("SELECT * FROM tb_products WHERE id = ?");
                $stmt_checkdb->execute([$id]);
                if ($stmt_checkdb->rowCount() > 0) {
                    $stmt_update = $conn->prepare("UPDATE tb_products SET prod_name = ? ,prod_price = ? ,prod_stock = ?  WHERE id = ?");
                    $stmt_update->execute([$prod_name, $prod_price, $prod_stock, $id]);
                    echo json_encode(["err" => false, "msg" => "Product updated successfully!"]);
                } else {
                    echo json_encode(["err" => true, "msg" => "Id not founded!"]);
                }
            }
        } catch (PDOException $e) {
            echo json_encode(["err" => true, "msg" => $e->getMessage()]);
        }
    } else if ($req->router == "deleteProduct") {
        //D = Delete 
        $id = $req->id;
        if (empty($id)) {
            echo json_encode(["err" => true, "msg" => "Data is empty!"]);
        } else {
            try {
                $stmt_del = $conn->prepare("DELETE FROM tb_products WHERE id = ?");
                $stmt_del->execute([$id]);
                if ($stmt_del) {
                    echo json_encode(["err" => false,"msg" => "Product deleted !"]);
                }
            } catch (PDOException $e) {
                echo json_encode(["err" => true, "msg" => $e->getMessage()]);
            }
        }
    }
} else {
    echo json_encode(["msg" => "cann't route."]);
}
