# Laravel eCommerce Sumup Payment

Módulo criado para adicionar a opção de meio de pagamento sumup na ferramenta de e-Commerce Bagisto

Para maiores informações acesse a página da extenção oficial [clicando aqui](https://developer.sumup.com/docs/php-sdk/)

## Registrar Aplicação na SUMUP

1 - https://developer.sumup.com/docs/register-app/

## Instalação

1- Run `composer require jorgebeserra/bagisto-sumup` in your bagisto project

2- Não esqueça de colocar as rotas do sumup no exceptions do `app/Http/Middleware/VerifyCsrfToken.php`:

```php
/**
 * The URIs that should be excluded from CSRF verification.
 *
 * @var array
 */
protected $except = [
    'sumup/*'
];
```
 
3- Rodar `php artisan config:clear` para limpar as configurações cacheadas.


## Configurações

Para configurar seu módulo acesse: Admin > Configurar > Vendas > Métodos de Pagamento > Sumup.

Configurações disponíveis:

* **Título**: Nome do método de pagamento.
* **Descrição**: Opcional
* **Tipo de Checkout**: Tipo de checkout, redirect (A venda é finalizada no site do sumup), ou lightbox (A venda é finalizada em um popuo na própria loja).
* **Sumup Email**: E-mail da conta criada no sumup que irá receber os pagamentos.
* **Sandbox**: Permite testar sua loja em modo de testes, quando você tiver pronto para começar a vender de verdade essa opção precisa ser deselecionada, para conseguir um usuario de teste, precisa solicitar ao suporte da SUMUP.
* **Status**: Ativa ou desativa o método de pagamento

## Conheça outros Packages para Bagisto

* [Bagisto - PicPay](https://github.com/cagartner/bagisto-picpay)
* [Bagisto - Correios](https://github.com/cagartner/bagisto-correios)
* [Bagisto - Campos Brasileiros](https://github.com/cagartner/bagisto-brazilcustomer)

## Conheça a comunidade Brasileira de Bagisto
- [Portal Oficial](https://bagisto.com.br)
- [Grupo do WhatsApp](https://chat.whatsapp.com/HpMKEoxf5neIfnpUlHGmaO)
- [Grupo do Facebook](https://www.facebook.com/groups/2552301808420521)
- [Grupo do Telegram](https://t.me/bagistobrasil)
- [Twitter](http://twitter.com/bagistobr)