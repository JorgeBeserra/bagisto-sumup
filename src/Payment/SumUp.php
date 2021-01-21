<?php

namespace Jorgebeserra\Sumup\Payment;

use Jorgebeserra\Sumup\Helper\Helper;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SumUp\Configuration\Configure;
use SumUp\Enum\PaymentMethod\Config\Keys;
use SumUp\Enum\PaymentMethod\Group;
use SumUp\Enum\Shipping\Type;
use SumUp\Library;
use SumUp\Services\Session;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Payment\Payment\Payment;
use function core;

/**
 * Class SumUp
 * @package Jorgebeserra\Sumup\Payment
 */
class SumUp extends Payment
{
    /**
     *
     */
    const CONFIG_EMAIL_ADDRES = 'sales.paymentmethods.sumup.email_address';
    /**
     *
     */
    const CONFIG_CLIENT_ID = 'sales.paymentmethods.sumup.client_id';
    /**
     *
     */
    const CONFIG_CLIENT_SECRET = 'sales.paymentmethods.sumup.client_secret';
    /**
     *
     */
    const CONFIG_CODE = 'sales.paymentmethods.sumup.code';
    /**
     *
     */
    const CONFIG_SANDBOX = 'sales.paymentmethods.sumup.sandbox';
    /**
     *
     */
    const CONFIG_DEBUG = 'sales.paymentmethods.sumup.debug';
    /**
     *
     */
    const CONFIG_NO_INTEREST = 'sales.paymentmethods.sumup.no_interest';
    /**
     *
     */
    const CONFIG_TYPE = 'sales.paymentmethods.sumup.type';
    /**
     *
     */
    const CONFIG_MAX_INSTALLMENTS = 'sales.paymentmethods.sumup.max_installments';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'sumup';
    /**
     * @var
     */
    protected $sessionCode;
    /**
     * @var \SumUp\Domains\Requests\Payment
     */
    protected $payment;
    /**
     * @var array
     */
    protected $urls = [];
    /**
     * @var bool
     */
    protected $sandbox = false;
    /**
     * @var string
     */
    protected $environment = 'production';
    /**
     * @var
     */
    protected $email;
    /**
     * @var
     */
    protected $client_id;
    /**
     * @var
     */
    protected $client_secret;
    /**
     * @var
     */
    protected $client_code;
    /**
     * SumUp constructor.
     */
    public function __construct()
    {
        $this->email = core()->getConfigData(self::CONFIG_EMAIL_ADDRES);
        $this->client_id = core()->getConfigData(self::CONFIG_CLIENT_ID);
        $this->client_secret = core()->getConfigData(self::CONFIG_CLIENT_SECRET);
        $this->client_code = core()->getConfigData(self::CONFIG_CODE);

        if (core()->getConfigData(self::CONFIG_SANDBOX)) {
            $this->sandbox = true;
            $this->environment = 'sandbox';
        }

        $this->setUrls();
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        
        if (!$this->email || !$this->client_id || !$this->client_secret) {
            throw new Exception('Sumup: To use this payment method you need to inform the token and email account of SumUp account.');
        }

        /** @var Cart $cart */
        $cart = $this->getCart();
        try{

            $sumup = new \SumUp\SumUp([
            'app_id'     => $this->client_id,
            'app_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'scopes'     => ['payments', 'transactions.history', 'user.app-settings', 'user.profile_readonly'],
            'code'       => $this->client_code
          ]);
    
          $accessToken = $sumup->getAccessToken();
          $refreshToken = $accessToken->getRefreshToken();
          $value = $accessToken->getValue();
          echo 'Bearer: ' . $value . '<br>';
          
          $sumup = new \SumUp\SumUp([
            'app_id'     => $this->client_id,
            'app_secret' => $this->client_secret,
            'scopes'        => ['payments', 'payment_instruments', 'transactions.history', 'user.app-settings', 'user.profile_readonly'],
            'access_token' => $value
          ]);

          echo 'Variaveis Configuração ';
          var_dump($this->client_id);
          var_dump($this->client_secret);
          var_dump($this->client_code);
          echo '<br>';
          echo '<br>';

          echo 'Variavel SumUp ';
          var_dump($sumup);
          echo '<br>';
          echo '<br>';
          
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


        /*
        $this->payment = new \SumUp\Domains\Requests\Payment();
        $this->configurePayment($cart);
        $this->addItems();
        $this->addCustomer($cart);
        $this->addShipping($cart);
        */

        try {
           //$this->sessionCode = Session::create(Configure::getAccountCredentials());
        } catch (Exception $e) {
            throw new Exception('SumUp: ' . $e->getMessage());
        }
    }

    /**
     * @param Cart $cart
     */
    public function configurePayment(Cart $cart)
    {
        $this->payment->setCurrency('BRL');
        $this->payment->setReference($cart->id);
        $this->payment->setRedirectUrl(route('sumup.success'));
        $this->payment->setNotificationUrl(route('sumup.notify'));

//        //Add installments with no interest
//        if ($maxInstallments = core()->getConfigData(self::CONFIG_MAX_INSTALLMENTS)) {
//            $this->payment->addPaymentMethod()->withParameters(
//                Group::CREDIT_CARD,
//                Keys::MAX_INSTALLMENTS_LIMIT,
//                (int) $maxInstallments // (int) qty of installment
//            );
//        }
//
//        // Limit the max installments
//        if ($installmentsNoInterest = core()->getConfigData(self::CONFIG_NO_INTEREST)) {
//            $this->payment->addPaymentMethod()->withParameters(
//                Group::CREDIT_CARD,
//                Keys::MAX_INSTALLMENTS_NO_INTEREST,
//                (int) $installmentsNoInterest // (int) qty of installment
//            );
//        }
    }

    /**
     *
     */
    public function addItems()
    {
        /**
         * @var \Webkul\Checkout\Models\CartItem[] $items
         */
        $items = $this->getCartItems();

        foreach ($items as $cartItem) {
            $this->payment->addItems()->withParameters(
                $cartItem->product_id,
                $cartItem->name,
                $cartItem->quantity,
                $cartItem->price
            );
        }
    }

    /**
     * Add the customer to the payment request
     * @param Cart $cart
     */
    public function addCustomer(Cart $cart)
    {
        $fullname = $this->fullnameConversion($cart->customer_first_name . ' ' . $cart->customer_last_name);
        $this->payment->setSender()->setName($fullname);
        $this->payment->setSender()->setEmail($cart->customer_email);
    }

    /**
     *
     */
    public function addShipping(Cart $cart)
    {
        /**
         * @var CartAddress $billingAddress
         */
        $billingAddress = $cart->getBillingAddressAttribute();

        // Add telephone
        $telephone = Helper::phoneParser($billingAddress->phone);

        if ($telephone) {
            $this->payment->setSender()->setPhone()->withParameters(
                $telephone['ddd'],
                $telephone['number']
            );
        }

        // Add CPF
        if ($billingAddress->vat_id) {
            $this->payment->setSender()->setDocument()->withParameters(
                'CPF',
                Helper::justNumber($billingAddress->vat_id)
            );
        }

        if ($cart->selected_shipping_rate) {
            $addresses = explode(PHP_EOL, $billingAddress->address1);

            // Add address
            $this->payment->setShipping()->setAddress()->withParameters(
                isset($addresses[0]) ? $addresses[0] : null,
                isset($addresses[1]) ? $addresses[1] : null,
                isset($addresses[2]) ? $addresses[2] : null,
                $billingAddress->postcode,
                $billingAddress->city,
                $billingAddress->state,
                $billingAddress->country,
                isset($addresses[3]) ? $addresses[3] : null
            );

            // Add Shipping Method
            $this->payment->setShipping()->setCost()->withParameters($cart->selected_shipping_rate->price);
            if (Str::contains($cart->selected_shipping_rate->carrier, 'correio')) {
                if (Str::contains($cart->selected_shipping_rate->method, 'sedex')) {
                    $this->payment->setShipping()->setType()->withParameters(Type::SEDEX);
                }
                if (Str::contains($cart->selected_shipping_rate->method, 'pac')) {
                    $this->payment->setShipping()->setType()->withParameters(Type::PAC);
                }
            } else {
                $this->payment->setShipping()->setType()->withParameters(Type::NOT_SPECIFIED);
            }
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function send()
    {
        /*
        return $this->payment->register(
            Configure::getAccountCredentials(),
            true
        );
    */
    return '';
}

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return route('sumup.redirect');
    }

    /**
     * @return string
     */
    public function getSumupUrl()
    {
        return $this->urls['redirect'];
    }

    /**
     * @param array $urls
     */
    public function setUrls(): void
    {
        $env = $this->sandbox ? $this->environment . '.' : '';
        $this->urls = [
            'preApprovalRequest' => 'https://ws.' . $env . 'sumup.com.br/v2/pre-approvals/request',
            'preApproval' => 'https://ws.' . $env . 'sumup.com.br/pre-approvals',
            'preApprovalCancel' => 'https://ws.' . $env . 'sumup.com.br/v2/pre-approvals/cancel/',
            'cancelTransaction' => 'https://ws.' . $env . 'sumup.com.br/v2/transactions/cancels',
            'preApprovalNotifications' => 'https://ws.' . $env . 'sumup.com.br/v2/pre-approvals/notifications/',
            'session' => 'https://ws.' . $env . 'sumup.uol.com.br/v2/sessions',
            'transactions' => 'https://ws.' . $env . 'sumup.uol.com.br/v2/transactions',
            'notifications' => 'https://ws.' . $env . 'sumup.uol.com.br/v3/transactions/notifications/',
            'javascript' => 'https://stc.' . $env . 'sumup.uol.com.br/api/v2/checkout/sumup.directpayment.js',
            'lightbox' => 'https://stc.' . $env . 'sumup.uol.com.br/api/v2/checkout/sumup.lightbox.js',
            'boletos' => 'https://ws.sumup.com.br/recurring-payment/boletos',
            'redirect' => 'https://' . $env . 'sumup.com.br/v2/checkout/payment.html?code=',
        ];
    }

    /**
     * @return mixed
     */
    public function getJavascriptUrl()
    {
        return core()->getConfigData(self::CONFIG_TYPE) == 'lightbox' ? $this->urls['lightbox'] : $this->urls['javascript'];
    }

    /**
     * @param $notificationCode
     * @param string $notificationType
     * @return \SimpleXMLElement
     * @throws Exception
     */
    public function notification($notificationCode, $notificationType = 'transaction')
    {
        if ($notificationType == 'transaction') {
            return $this->sendTransaction([
                'email' => $this->email,
                'token' => $this->token,
            ], $this->urls['notifications'] . $notificationCode, false);
        } elseif ($notificationType == 'preApproval') {
            return $this->sendTransaction([
                'email' => $this->email,
                'token' => $this->token,
            ], $this->urls['preApprovalNotifications'] . $notificationCode, false);
        }
    }

    /**
     * @param $reference
     * @return \SimpleXMLElement
     * @throws Exception
     */
    public function transaction($reference)
    {
        return $this->sendTransaction([
            'reference' => $reference,
            'email' => $this->email,
            'token' => $this->token,
        ], $this->urls['transactions'], false);
    }

    /**
     * @param array $parameters
     * @param null $url
     * @param bool $post
     * @param array $headers
     * @return \SimpleXMLElement
     * @throws Exception
     */
    protected function sendTransaction(
        array $parameters,
        $url = null,
        $post = true,
        array $headers = ['Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1']
    )
    {
        if ($url === null) {
            $url = $this->url['transactions'];
        }

        $parameters = Helper::array_filter_recursive($parameters);

        $data = '';
        foreach ($parameters as $key => $value) {
            $data .= $key . '=' . $value . '&';
        }
        $parameters = rtrim($data, '&');

        $method = 'POST';

        if (!$post) {
            $url .= '?' . $parameters;
            $parameters = null;
            $method = 'GET';
        }

        $result = $this->executeCurl($parameters, $url, $headers, $method);

        return $this->formatResult($result);
    }

    /**
     * @param $result
     * @return \SimpleXMLElement
     * @throws Exception
     */
    private function formatResult($result)
    {
        if ($result === 'Unauthorized' || $result === 'Forbidden') {
            Log::error('Erro ao enviar a transação', ['Retorno:' => $result]);

            throw new Exception($result . ': Não foi possível estabelecer uma conexão com o SumUp.', 1001);
        }
        if ($result === 'Not Found') {
            Log::error('Notificação/Transação não encontrada', ['Retorno:' => $result]);

            throw new Exception($result . ': Não foi possível encontrar a notificação/transação no SumUp.', 1002);
        }

        try {
            $encoder = new XmlEncoder();
            $result = $encoder->decode($result, 'xml');
            $result = json_decode(json_encode($result), FALSE);
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        if (isset($result->error) && isset($result->error->message)) {
            Log::error($result->error->message, ['Retorno:' => $result]);

            throw new Exception($result->error->message, (int)$result->error->code);
        }

        return $result;
    }

    /**
     * @param $parameters
     * @param $url
     * @param array $headers
     * @param $method
     * @return bool|string
     * @throws Exception
     */
    private function executeCurl($parameters, $url, array $headers, $method)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
        } elseif ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        }

        if ($parameters !== null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !$this->sandbox);

        $result = curl_exec($curl);

        $getInfo = curl_getinfo($curl);
        if (isset($getInfo['http_code']) && $getInfo['http_code'] == '503') {
            Log::error('Serviço em manutenção.', ['Retorno:' => $result]);

            throw new Exception('Serviço em manutenção.', 1000);
        }
        if ($result === false) {
            Log::error('Erro ao enviar a transação', ['Retorno:' => $result]);

            throw new Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        return $result;
    }

    /**
     * @param $name
     * @return string
     */
    protected function fullnameConversion($name)
    {
        $name = preg_replace('/\d/', '', $name);
        $name = preg_replace('/[\n\t\r]/', ' ', $name);
        $name = preg_replace('/\s(?=\s)/', '', $name);
        $name = trim($name);
        $name = explode(' ', $name);
        if(count($name) == 1 ) {
            $name[] = 'dos Santos';
        }
        $name = implode(' ', $name);
        return $name;
    }
}