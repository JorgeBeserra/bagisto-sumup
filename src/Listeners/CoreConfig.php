<?php

namespace Jorgebeserra\Sumup\Listeners;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

/**
 * Core Config event handler
 *
 * @author Jorge Beserra <jorgebeserra@gmail.com>
 */
class CoreConfig
{
    /**
     * Generate menifest.json file
     *
     */
    public function openSumupAuthorization()
    {
        if (strpos(request()->url(), 'configuration/sales/paymentmethods') !== false) {
            $client_id = core()->getConfigData('sales.paymentmethods.sumup.client_id');
            return redirect()->to('https://api.sumup.com/authorize?response_type=code&client_id=' . $client_id . '&redirect_uri='. url('/') .'/sumup/callback&scope=payments%20user.app-settings%20transactions.history%20user.profile_readonly&state=2cFCsY36y95lFHk4')->send();
        }
    }
}
