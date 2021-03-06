<?php

namespace Jorgebeserra\Sumup\Http\Controllers;

use Jorgebeserra\Sumup\Helper\Helper;
use Jorgebeserra\Sumup\Payment\SumUp;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use InvalidArgumentException;
use SumUp\Configuration\Configure;
use SumUp\Helpers\Xhr;
use SumUp\Library;
use SumUp\Services\Transactions\Notification;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Core\Repositories\CoreConfigRepository;

/**
 * Class SumupController
 * @package Jorgebeserra\Sumup\Http\Controllers
 */
class SumupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * CoreConfigRepository object
     *
     * @var \Webkul\Core\Repositories\CoreConfigRepository
     */
    protected $coreConfigRepository;

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * Create a new controller instance.
     *
     * @param OrderRepository $orderRepository
     * @param Helper $helper
     */
    public function __construct(
        OrderRepository $orderRepository,
        Helper $helper,
        CoreConfigRepository $coreConfigRepository
    )
    {
        $this->coreConfigRepository = $coreConfigRepository;
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
        $this->helper = $helper;
    }

    /**
     * @return Factory|View
     */
    public function redirect()
    {
        return view('sumup::redirect');
    }

    /**
     * Cancel payment from sumup.
     *
     * @return Response
     */
    public function cancel()
    {
        session()->flash('error', 'Você cancelou o pagamento, pedido não finalizado');

        return redirect()->route('shop.checkout.cart.index');
    }


    /**
     * Callback from sumup.
     *
     * @return Response
     */
    public function callback(Request $request)
    {
        $code = $request->input('code');

        $sumupDetails = Array(
            'client_code' => $code
        );

        $sumup = Array(
            'sumup' => $sumupDetails
        );

        $paymentsmethods = Array(
            'paymentmethods' => $sumup
        );

        $sales = Array(
            'channel' => 'default',
            'locale' => 'pt_BR',
            'sales' => $paymentsmethods
        );

        
        $this->coreConfigRepository->create($sales);
        
        session()->flash('success', trans('admin::app.configuration.save-message'));
        return redirect()->route('admin.configuration.index');
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function success(Request $request, SumUp $sumUp)
    {
        // @todo: Analisar request do retorno do success
        /**
         * @var \Webkul\Sales\Models\Order $order
         */
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        try {
            $response = $sumUp->transaction($order->cart_id);
            if (isset($response->transactions)) {
                foreach ($response->transactions as $transaction) {
                    // Update order with transaction info
                    $this->helper->updateOrder($transaction);
                }
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }

    /**
     * @param Request $request
     * @param SumUp $sumUp
     */
    public function notify(Request $request, SumUp $sumUp)
    {
        try {
            $response = $sumUp->notification($request->get('notificationCode'), $request->get('notificationType'));
            if ($response) {
                $this->helper->updateOrder($response);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }
}