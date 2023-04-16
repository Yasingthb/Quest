<?php
require_once("DBconnection.php");
require_once("messages.php");


var_dump('?name=yasin&email=yasin@yasin.com&phone=5452575522&address=ankara&urun=3:kitap adi:55.55:yasin&kampanya=1:FREEBOOK&count=1');
var_dump( $_SERVER['REQUEST_METHOD']);

if($_SERVER["REQUEST_METHOD"]=='POST') 
{

    $product=$_POST['product'];
   
    $kampanya=$_POST['kampanya'];
    $product_details = explode(':', $product);
    var_dump($_POST['product']);
    var_dump($_POST['kampanya']);
    die;
    var_dump($product_details);
  
    //    'category' =>$product_details[2],
    $kampanya_details = explode(':', $kampanya);
    $stmt = $baglan->prepare("SELECT * FROM products WHERE product_id = :product_id");
    $stmt->execute(array(':product_id' => $product_details[0]));
    $product_control = $stmt->fetch(PDO::FETCH_ASSOC);
    if($product_control['stock_quantity']>=$_POST['count'])
    {
      $params=array(
        'customer_name'=>$_POST['name'],
        'customer_email'=>$_POST['email'],
        'customer_phone'=>$_POST['phone'],
        'customer_address'=>$_POST['address'],
        'product_id' => $product_details[0],
        'campaign_id' => $kampanya_details[0],
        'amount' =>$product_details[3]*intval($_POST['count']),
        'shipping_cost'=>'',
        'book_name' =>$product_details[1],
        'campaign_name' => $kampanya_details[1],
        'piece' => $_POST['count']   
      );
    
      var_dump($params['product_id']);
      if ($product_details[3]*intval($_POST['count']) >= 200) {
        echo "Tebrikler, Ücretsiz kargo kazandınız<br>";
        $params["shipping_cost"] = 0;
      } 


      $temp_after_freebook=0;
      $query = $baglan->query("SELECT * FROM campaigns ", PDO::FETCH_ASSOC);
      $campaigns = $query->fetchAll();
      $campaigns1= $campaigns[0];
      $campaigns2= $campaigns[1];
      var_dump($campaigns1['book_category']);
      
      $temp_after_freebook=($params['amount']-$product_details[3]);

      $temp_after_percent=$params['amount']-($params['amount']*floatval($campaigns2['discount_rate']));
      var_dump($campaigns2['discount_rate']);
      
      function applyPercentDiscount($amount) {
        return $amount;
    }
    
    function applyFreeBookDiscount($amount) {
        echo "Ücretsiz kitap bir indirimi uygulandı";
        return $amount;
    }



    var_dump($product_details[2].':'.$campaigns1['book_category']);
      if ($params['campaign_name'] == 'percent_discount' && $temp_after_percent > $temp_after_freebook) {
        echo "Yüzdelik indirimi seçtiniz ancak sepetiniz için ücretsiz kitap indirimi(FREEBOOK) uygundur.";
        $params['amount'] = applyFreeBookDiscount($temp_after_freebook);
        $params['campaign_name'] = 'FREEBOOK';
    } elseif ($params['campaign_name'] == 'percent_discount' && $temp_after_percent < $temp_after_freebook) {
        $params['amount'] = applyPercentDiscount($temp_after_percent);
        $params['campaign_name'] = 'percent_discount';
    } elseif ($params['campaign_name'] == 'FREEBOOK' ) {
    
        if ($temp_after_percent < $temp_after_freebook) {
            if ($product_details[2] === $campaigns1['book_category'] ) {
                function_alert('Ücretsiz bir kitap hediyesi seçtiniz ancak sepetiniz için yüzdelik indirim (percent_discount) daha uygundur.');
                var_dump('deneme0');
                $params['campaign_name'] = 'percent_discount';
                $params['amount'] = applyPercentDiscount($temp_after_percent);
            } else {
                function_alert("Sabahattin ali romanlarından kitaplar seçmediniz üzgünüz kampanyadan yararlanamazsınız.<br>Ancak otomatik size en uygun kampanya atandı keyifli alışverişler ");
                $params['campaign_name'] = 'percent_discount';
                
                $params['amount'] = applyPercentDiscount($temp_after_percent);
            }
           
        }else{
          function_alert("Sabahattin ali romanlarından kitaplar seçmediniz üzgünüz kampanyadan yararlanamazsınız.<br>Ancak otomatik size en uygun kampanya atandı keyifli alışverişler ");
          $params['campaign_name'] = 'percent_discount';
          
          $params['amount'] = applyPercentDiscount($temp_after_percent);
        }
        
        $params['amount'] = applyFreeBookDiscount($temp_after_freebook);
    }
 
    

   

    if($params['amount']<200)
    {
      
      function_alert('indirim uygulandığı için sepet tutarınız 200₺ altında kalmıştır. Kargo ücreti alınacaktır.');
      //echo "<script>indirim uygulandığı için sepet tutarınız 200₺ altında kalmıştır. Kargo ücreti alınacaktır.</script>";
      $params["shipping_cost"] = 75;
      $params['amount'] += 75;
      

    }
     var_dump( $params['campaign_name']);
    $sql = 'INSERT INTO customer_details (name, email, phone, address, product_id, campaign_id, amount, shipping_cost, campaign_name, customer_buy,piece)
    VALUES (:customer_name, :customer_email, :customer_phone, :customer_address, :product_id, :campaign_id, :amount, :shipping_cost, :campaign_name, :book_name,:piece)';
    try {
    $stmt = $baglan->prepare($sql);
    $stmt->execute($params);
    $response=islem('', 900, "Veriler başarıyla eklendi");
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    } catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    }
    $new_stock_quantity=$product_control['stock_quantity']-$params['piece'];
    $new_stock_quantity = $product_control['stock_quantity'] - $params['piece'];
    $sql = "UPDATE products SET stock_quantity = :new_stock_quantity WHERE product_id = :product_id";
    
    $stmt = $baglan->prepare($sql);
    $stmt->bindParam(":new_stock_quantity", $new_stock_quantity, PDO::PARAM_INT);
    $stmt->bindParam(":product_id", $params['product_id'], PDO::PARAM_INT);
    if($stmt->execute()){
        echo "Update successful!";
    } else {
        echo "Update failed!";
        print_r($stmt->errorInfo());
    }
     
  }
  else{
   
    echo function_alert('Stokta Yeterli Ürün Mevcut değil.');
    exit;
  }
  
   
 
}
  elseif($_SERVER["REQUEST_METHOD"]=='GET')
  {
 
    $id = $_GET["id"];
    $query = $baglan->query("SELECT * FROM customer_details WHERE id=$id", PDO::FETCH_ASSOC);
    $customer = array();
        foreach ($query as $row) {
            $customer[] = array(
                "id" => $row["id"],
                "name" => $row["name"],
                "email" => $row["email"],
                "phone" => $row["phone"],
                "address" => $row["address"],
                "product_id" => $row["product_id"],
                "campaign_id" => $row["campaign_id"],
                "amount" => $row["amount"],
                "shipping_cost"=>$row['shipping_cost'],
                "campaign_name"=>$row['campaign_name'],
                "customer_buy"=>$row['customer_buy'],
                "piece" => $row['piece']
            );
        }

        $a=islem($customer, 900, "Kayıt Listelendi!");
        echo json_encode($a,JSON_UNESCAPED_UNICODE);
  }
  

?>