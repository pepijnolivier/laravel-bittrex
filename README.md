# Laravel-Bittrex

Start trading on Bittrex right away using your favorite PHP framework.

### Installation

`composer require pepijnolivier/laravel-bittrex`.

Add the service provider to your `config/app.php`:
 
 ``` 
 'providers' => [
 
     Pepijnolivier\Bittrex\BittrexServiceProvider::class,
     
 ],
 ```
 
...run `php artisan vendor:publish` to copy the config file.

Edit the `config/bittrex.php` or add Bittrex api and secret in your `.env` file

```
BITTREX_KEY={YOUR_API_KEY}
BITTREX_SECRET={YOUR_API_SECRET}

```

Add the alias to your `config/app.php`:

```    
'aliases' => [
           
    'Bittrex' => Pepijnolivier\Bittrex\Bittrex::class,
           
],
```

### Usage

Please refer to the [Api Documentation](https://bittrex.com/home/api) for more info, or read the [docblocks](https://github.com/pepijnolivier/laravel-bittrex/blob/master/src/Client.php) !

```
use Pepijnolivier\Bittrex\Bittrex;

// public API methods
Bittrex::getMarkets();
Bittrex::getCurrencies();
Bittrex::getTicker($marker);
Bittrex::getMarketSummaries();
Bittrex::getMarketSummary($market);
Bittrex::getOrderBook($market, $type, $depth=20);
Bittrex::getMarketHistory($market);

// market API methods
Bittrex::buyLimit($market, $quantity, $rate);
Bittrex::sellLimit($market, $quantity, $rate);
Bittrex::cancelOrder($uuid);
Bittrex::getOpenOrders($market=null);

// account API methods
Bittrex::getBalances();
Bittrex::getBalance($currency);
Bittrex::getDepositAddress($currency);
Bittrex::withdraw($currency, $quantity, $address, $paymentId=null);
Bittrex::getOrder($uuid);
Bittrex::getOrderHistory($market=null);
Bittrex::getWithdrawalHistory($currency=null);
Bittrex::getDepositHistory($currency=null);
```


Did I help you with this package ?
BTC Tipjar: `1N5ET46r5Z4HdfhRjGMp7SpEMPes9S1H9n`
