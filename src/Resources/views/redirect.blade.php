<?php
use Jorgebeserra\Sumup\Payment\SumUp;

/** @var SumUp $sumup */
$sumup = app(SumUp::class);
$sumup->init();
$response = $sumup->send();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pagamento SumUp</title>
    <script type="text/javascript" src="{{ $sumup->getJavascriptUrl() }}"></script>
</head>
<body>
<script type="text/javascript">
    var code = '<?= $response->getCode() ?>'
    @if(core()->getConfigData(SumUp::CONFIG_TYPE) === 'lightbox')
        var callback = {
            success : function(transactionCode) {
                window.location.href = '<?= route('sumup.success') ?>?transactionCode=' + transactionCode;
            },
            abort : function() {
                window.location.href = '<?= route('sumup.cancel') ?>';
            }
        };
        var isOpenLightbox = SumUpLightbox(code, callback);
        if (!isOpenLightbox){
            location.href= '<?= $sumup->getSumupUrl() ?>' + code;
        }
    @else
        location.href= '<?= $sumup->getSumupUrl() ?>' + code;
    @endif
</script>
</body>
</html>