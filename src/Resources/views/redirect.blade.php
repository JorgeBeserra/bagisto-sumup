<?php
use Jorgebeserra\Sumup\Payment\SumUp;

/** @var SumUp $sumup */
$sumup = app(SumUp::class);
$sumup->init();
//$response = $sumup->send();
/*
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagamento SumUp</title>
</head>
<body>
<script src="https://gateway.sumup.com/gateway/ecom/card/v2/sdk.js"></script>
<div id="sumup-card"></div>
<script type="text/javascript" src="https://gateway.sumup.com/gateway/ecom/card/v2/sdk.js"></script>
<script type="text/javascript">
    SumUpCard.mount({
        checkoutId: 'demo',
        currency: "BRL",
        locale: "pt-BR",
        country: "BR",
        onResponse: function(type, body) {
            console.log('Type', type);
            console.log('Body', body);
        }
    });
</script>
</body>
</html>
*/
?>