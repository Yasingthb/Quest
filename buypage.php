<?php
require_once("DBconnection.php");
require_once("messages.php");

if($_SERVER['REQUEST_METHOD'] == 'GET'){

$query = $baglan->query("SELECT * FROM products", PDO::FETCH_ASSOC);
$product = array();
if ($query->rowCount() > 0) 
  {
      
      foreach ($query as $row) {
          $product[] = array(
              "id" => $row["product_id"],
              "book_title" => $row["title"],
              "category_title" => $row["category_title"],
              "category_id" => $row["category_id"],
              "author" => $row["author"],
              "list_price" => $row["list_price"],
              "stock_quantity" => $row["stock_quantity"],
            
          );
      }
    }
    else
    {
       echo islem('', 400, "Kayıt bulunamadı!");
    }
        

      $returns=islem($product, 900, "Kayıt Listelendi!");  
      foreach ($returns["content"] as $key=>$value ){
        
        foreach ($value as $keys=>$values)
        {
       
             
             if($keys=='list_price')
             {

                 $price[]=$values;
                 
             }
           
             if($keys == 'id'){
                 $id[]=$values;
             }
             if($keys == 'category_title')
             {
                 $category[]=$values;

             }
             if($keys =='author')
             {
                 $author[]=$values;
             }
             if($keys =='book_title')
             {
                 $book[]=$values;
             }
         }
         
     }
     $combined_price = array_combine($id, $price);
     $combined_category = array_combine($id, $category);
     $combined_author = array_combine($id, $author);
     $combined_book = array_combine($id, $book);
  
}

$query = $baglan->query("SELECT * FROM campaigns", PDO::FETCH_ASSOC);
$campaign = array();
if ($query->rowCount() > 0) 
{
      
      foreach ($query as $row) 
      {
          $campaigns[] = array(
              "id" => $row["id"],
              "name" => $row["name"],
              "type" => $row["type"],
              "discount_rate" => $row["discount_rate"]
            
          );
      }
      $campaign=islem($campaigns, 900, "Kayıt Listelendi!");
 
      foreach ($campaign["content"] as $key=>$value ){
        
        foreach ($value as $keys=>$values)
        {
       
             
             if($keys=='name')
             {

                 $campaing_name[]=$values;
                 
             }
           
             if($keys == 'id'){
                 $campaing_id[]=$values;
             }
             if($keys == 'type')
             {
                 $campaing_type[]=$values;

             }
             if($keys =='discount_rate')
             {
                 $campaing_rate[]=$values;
             }
           
         }
      
        
     }
     $combined_rate = array_combine($campaing_id, $campaing_rate);
     $combined_type = array_combine($campaing_id, $campaing_type);
     $combined_campaing = array_combine($campaing_id, $campaing_name);
}


?>
<!DOCTYPE html>
<html>
    <meta charset="utf-8">
<head>
	<title>Ürün Satın Alma Sayfası</title>
   
	  <link rel="stylesheet" href="style.css">

<button class="my-button"><a href="addcampaing.php">Kampanya Ekle</a></button>


</head>
<body>
	<h1>Ürün Satın Alma Sayfası</h1>
	<form action="Customer.php" method="POST">

    
		<label for="name">Adınız:</label>
		<input type="text" id="name" name="name">
		<br><br>
		<label for="email">E-posta Adresiniz:</label>
		<input type="email" id="email" name="email">
		<br><br>
		<label for="phone">Telefon Numaranız:</label>
		<input type="tel" id="phone" name="phone">
		<br><br>
		<label for="address">Adresiniz:</label>
		<textarea id="address" name="address"></textarea>
		<br><br>
		
		
		<label for="product">Ürün:</label>
		<select id="product" name="product">
     
            <?php foreach($combined_book as $key=>$value) {?>
            
                
			<option value="<?php echo $key .":".$value.":".$combined_category[$key].":".$combined_price[$key].":".$combined_author[$key];?>"><?php echo $value." \t  \t". $combined_price[$key]."TL";?></option>
            <?php } ?>
            
		</select>
		<br><br>
		<label for="kampanya">Kampanya:</label>
		<select id="kampanya" name="kampanya">
        <?php 
        foreach($combined_campaing as $key=>$value) {?>
			<option value="<?php echo $key.':'.$value;?>"><?php echo $value?></option>
            
             <?php } ?>
		</select>
		
        <label for="count">Miktar:</label>
        <input type="number" id="count" name="count" min="1" max="100">
		<input type="submit" value="Satın Al">
	</form>
</body>
</html>
