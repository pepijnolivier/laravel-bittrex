# Laravel-Bittrex

Start trading on Bittrex right away using your favorite PHP framework.

### Installation

`composer require angelkurten/laravel-bittrex`.

Add the service provider to your `config/app.php`:
 
 ``` 
 'providers' => [
    AngelKurten\Bittrex\BittrexServiceProvider::class,
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
    'Bittrex' => Angelkurten\Bittrex\Bittrex::class,
],
```

### Usage

Please refer to the [Api Documentation](https://bittrex.com/home/api) for more info, or read the [docblocks](https://github.com/angelkurten/laravel-bittrex/blob/master/src/Client.php) !

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

// Public API 2.0 methods
Bittrex::getValidChartDataTickIntervals();
Bittrex::getChartData($market, $tickInterval='hour');

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


This package is provided as-is. Do with it what you want ! PR's will be looked into.
I personally believe in freedom and equality, which is one of the reasons I'm in crypto.
It's also the reason I'm sharing most of the reusable code I write.

If you're feeling generous, you can always leave a tip. Any satoshi will do.
May the chain be with you. And may you be with the chain.
