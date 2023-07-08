<?php

$store_id = isset($_POST['store_id'])? $_POST['store_id'] : '' ;
$manager_token  = isset($_POST['manager_token'])? $_POST['manager_token'] : '';


//get Products list
function Products_list($s_id){

    $curl = curl_init();
    curl_setopt_array($curl, [
        //this is query parameters
        CURLOPT_URL => "https://api.zid.sa/v1/products/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
        "Accept: application/json",
        "Accept-Language: en",
        "Access-Token: ",
        "Authorization: ",
        "Store-Id:".$s_id
        ],
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $data = json_decode($response);
        return $data;
    }
}


$data_from_api = Products_list($store_id);
//products array before add new price
$products_arr = isset($data_from_api->results)? $data_from_api->results: '' ;


//add new price percentage or fixed
function get_arr($products){

    $num = isset ($_POST['num'])? $_POST['num'] :'';
    $percentage = isset ($_POST['percentage'])? true : '';
    
    $new_arr = array();
    foreach($products as $index=>$product){
        $price_adjustment = empty($percentage) ? $num  : ($product->price * ($num  / 100)) ;
        if($product->is_taxable == true){
            $new_arr[$index] = [
                'id'=> $product->id,
                'price' => $product->price - ($product->price * 0.1304347826086957  ) + $price_adjustment,
               //  'sale_price' => $product->sale_price + $price_adjustment 
           ];
        }else{
            $new_arr[$index] = [
                'id'=> $product->id,
                'price' => $product->price + $price_adjustment,
               //  'sale_price' => $product->sale_price + $price_adjustment 
           ];
        }
       
         
     }
return $new_arr;
 
}

//this is products array after add new price 
$submit_arr = get_arr($products_arr);
echo '';


//send new price to api and update products as a Bulk 
function update_products($s_id , $token , $su_arr){

    $curl = curl_init();

    curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.zid.sa/v1/products/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PATCH",
    CURLOPT_POSTFIELDS => json_encode($su_arr),
    CURLOPT_HTTPHEADER => [
        "Accept: application/json; charset=utf-8",
        "Accept-Language: ar",
        "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxMTciLCJqdGkiOiJhMTg5ZTg3MmYxMzhkMWVhYjU5MjVkMDkyMGE5NmI0YjliNjg0Y2E2ZTdmM2M2MjljZWYxNmQ4NDJjMmJlYmVhMjI4YTdmMTA0ZWQ4NWE5NCIsImlhdCI6MTY3OTU3Njk5OS41NjY4NzcsIm5iZiI6MTY3OTU3Njk5OS41NjY4OCwiZXhwIjoxNzExMTk5Mzk5LjQ4NjE1Mywic3ViIjoiMTgyNDc1Iiwic2NvcGVzIjpbInRoaXJkLXBhcnRpZXMtYXBpcyJdfQ.i07ef09nVNXGZF-g-QXpNoS2vlFQK_zntAqAMS4Az2XD2EyMLhxLZZRL-QlR11zUPqMmXjMAl_4ooKa3M3zkfZQ6Ga6qStvamk8RnC_39VUx0lfN2A4k65ERZpqwrMy6-t3dE99zay3aicIdNvbgi0zeuMSE5Tn99u-2AtSRa8ffbfAcYPPXacHrhdmlYzdiZS_x_skovFEow1E-nDjdL1WHqO92XdZ7RfNLkiYFTjZlZmM_UruvioaR3q6TXJbqRK_ZrziivezL8ohIQ2SBosUp58I29rlKzvlw_R2j0rKKYZbdxYDaxAHOISmOFKAlO66k7dNevAHI3s4uGIjoGA6ZXHknccWPLLLiaAQ0r64HV8GowW5dg2rhZNurJGDTnLlBQ6F-ql42ptHzSAfzzi576CEoN3gMVpgXcbntUY3reETkFsTBPUjeSuMpANMioXAA0GRp3Ut-84fTnrWxqsCW1WVUIx33HvmfCGPXIdkaCCWoA6G6KXo04MtFbKXQmXkK9esQWI-rqdVnMD3zSR3g3yFHZSL1U-mZeNja03706Rav1ordsRNOtRwtLuoRRbk9KasbUpEwqq4Ao9lqZZwRIjdEw-pQtnUT8V53fhmuuRIefCLFO7eGEtGUnh9o6Uh_pgi6AB6uSlnN9GEMGgI1alqvMmTjxvC-HHt0V-Y",
        "Content-Type: application/json",
        "Role: ",
        "Store-Id:".$s_id,
        "X-Manager-Token: ".$token
    ],
    ]);

    $response = curl_exec($curl);
    $data =json_decode($response);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {

    echo'sucsess';
    
    }

}

if(isset($_POST['submit'])){
    update_products($store_id, $manager_token, $submit_arr);

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Products</title>
    <style>
        .form-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .form-group input[type="checkbox"] {
            margin-top: 5px;
        }

        .form-group button[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            cursor: pointer;
        }

        .form-group button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Products</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="store_id">Store ID:</label>
                <input type="text" name="store_id" id="store_id" required>
            </div>
            <div class="form-group">
                <label for="manager_token">Manager Token:</label>
                <input type="text" name="manager_token" id="manager_token" required>
            </div>
            <div class="form-group">
                <label for="num">Percentage/Fixed Number:</label>
                <input type="text" name="num" id="num" required>
            </div>
            <div class="form-group">
                <label for="percentage">Apply as Percentage:</label>
                <input type="checkbox" name="percentage" id="percentage">
                <!-- <select name="percentage" id="percentage" >
                    <option></option>
                    <option value="true">yes</option>
                </select> -->
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Update Products</button>
            </div>
        </form>
    </div>
</body>
</html>
