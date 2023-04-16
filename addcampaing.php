<?php
require_once("DBconnection.php");
   $query = $baglan->query("SELECT * FROM categories", PDO::FETCH_ASSOC);
   $category = array();
   foreach ($query as $row) {
    $category[] = array(
        "category_title" => $row["category_title"],
    
        );
    }
    foreach($category as $key=>$value){
        
        foreach($value as $keys=>$values){
            if($keys=='category_title'){
                $categories[]=$values;
                $keyy[]=$key+1;
            }
        }
    }
    $combined_category = array_combine($keyy, $categories);

?>  
<!DOCTYPE html>
<html>
    <meta charset="utf-8">
<head>
	<title>Ürün Satın Alma Sayfası</title>
   
	  <link rel="stylesheet" href="style.css">


	  <button class="my-button"><a href="buypage.php">Satın Alma Sayfası</a></button>


</head>
<body>
	<h1>Yeni kampanya Ekle</h1>
<form action="Campaing.php" method="post">
 
  <label>Campaing Name:</label>
  <input type="text" name="name" ><br>
  <label>Type:</label>
  <input type="text" name="type" ><br>
  <label>Discount Rate:</label>
  <input type="text" name="discount_rate" ><br>
  <label>Author:</label>
  <input type="text" name="author" ><br>
  <label>category:</label>
  <select id="category" name="category">
  <?php foreach ($combined_category as $key=>$value) {?>
  <option value="<?php echo $key.':'.$value;?>"><?php echo $value?></option>
  <?php }?>
  <input type="submit" value="Kampanya Ekle">
</form>
</body>
</html>
