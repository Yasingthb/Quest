<?php 


  
require_once("DBconnection.php");
require_once("messages.php");

if($_SERVER['REQUEST_METHOD'] == 'GET'){

  $id=$_GET['id'];
  $query = $baglan->query("SELECT * FROM campaigns where id = $id", PDO::FETCH_ASSOC);
  if ($query->rowCount() > 0) 
    {
        $campaign = array();
        foreach ($query as $row) {
            $campaign[] = array(
                "id" => $row["id"],
                "name" => $row["name"],
                "type" => $row["type"],
                "d_rate" => $row["discount_rate"],
                "book_category"=>$row['book_category'],
                "category_id"=>$row['category_id'],
                "author"=>$row['author'],
        
            );
        }
        $a=islem($campaign, 900, "Kayıt Listelendi!");
        echo json_encode($a, JSON_UNESCAPED_UNICODE);

  }
  else{
  echo json_encode(islem('',904,"Kayıtlar bulunamadı"));
  }


} elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
  $category=$_POST['category'];
  $category_details = explode(':', $category);

  $params=array(
    'name'=>$_POST['name'],
    'type'=>$_POST['type'],
    'discount_rate'=>$_POST['discount_rate'],
    'author' => $_POST['author'],
    'Category_id' => $category_details[0],
    'book_category' => $category_details[1],

    
  );

// Veri ekleme
$sql = "INSERT INTO campaigns (name, type, discount_rate, book_category, category_id, author) 
VALUES (:name, :type, :discount_rate, :book_category, :Category_id, :author)";
try {
  $stmt = $baglan->prepare($sql);
  $stmt->execute($params);
  $response=islem($params, 900, " Veriler başarıyla eklendi");
  echo json_encode($response, JSON_UNESCAPED_UNICODE);
  } catch(PDOException $e) {
    $response=islem('', 1000, "Birşeyler Ters Gitti veritabanına eklenemedi "." ".$e->getMessage());
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
  }
}

?>

