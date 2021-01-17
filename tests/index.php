<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../vendor/autoload.php'; // You have to require the library from your Composer vendor folder
    
    try{
      
      $sumup = new \SumUp\SumUp([
        'app_id'     => '',
        'app_secret' => '',
        'grant_type' => 'client_credentials',
        'scopes'      => ['payments', 'transactions.history', 'user.app-settings', 'user.profile_readonly'],
      ]);

      $accessToken = $sumup->getAccessToken();
      $value = $accessToken->getValue();
      echo 'Bearer: ' . $value . '<br>';

      
      $sumup = new \SumUp\SumUp([
        'app_id'     => '',
        'app_secret' => '',
        'scopes'        => ['payments', 'transactions.history', 'user.app-settings', 'user.profile_readonly'],
        'access_token' => $value
      ]);
      
      $checkoutService = $sumup->getCheckoutService();
      echo 'Checkout Services ';
      var_dump($checkoutService);
      echo '<br>';
      $checkoutResponse = $checkoutService->create(5, 'BRL', '1', 'sabidos@sabidos.com.br');
      $checkoutId = $checkoutResponse->getBody()->id;
      echo 'ID: ' . $checkoutId . '<br>';
      
    } catch (\SumUp\Exceptions\SumUpResponseException $e) {
      echo 'Response error: ' . $e->getMessage();
    } catch(\SumUp\Exceptions\SumUpSDKException $e) {
      echo 'SumUp SDK error: ' . $e->getMessage();
    }

    $payment = new SumUp\Payment();
    
    $payment->transaction_amount = 141;
    $payment->token = "YOUR_CARD_TOKEN";
    $payment->description = "Ergonomic Silk Shirt";
    $payment->installments = 1;
    $payment->payment_method_id = "visa";
    $payment->payer = array(
      "email" => "sabidos@sabidos.com.br"
    );

    $payment->save();

    echo $payment->status;

?>