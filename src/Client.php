<?php
namespace Pepijnolivier\Bittrex;

class Client implements ClientContract
{
    private $returnType = 'array';

    /**
     * @var string
     */
    public $marketUrl;

    /**
     * @var string
     */
    public $publicUrl;

    /**
     * @var string
     */
    public $publicUrlV2;

    /**
     * @var string
     */
    public $accountUrl;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * Client constructor.
     *
     * @param array $auth
     * @param array $urls
     */
    public function __construct(array $auth, array $urls) {
        $this->marketUrl  = array_get($urls, 'market');
        $this->publicUrl  = array_get($urls, 'public');
        $this->publicUrlV2  = array_get($urls, 'publicv2');
        $this->accountUrl = array_get($urls, 'account');

        $this->key    = array_get($auth, 'key');
        $this->secret = array_get($auth, 'secret');
    }

    /**
     * @return string
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * @param $returnType
     */
    public function setReturnType($returnType)
    {
        $this->returnType = $returnType;
    }

    /**
     * Used to get the open and available trading markets at Bittrex along with other meta data.
     *
     * @return array|\stdClass
     */
    public function getMarkets() {
        return $this->public('getmarkets');
    }

    /**
     * Used to get all supported currencies at Bittrex along with other meta data.
     *
     * @return array|\stdClass
     */
    public function getCurrencies() {
        return $this->public('getcurrencies');
    }

    /**
     * Used to get the current tick values for a market.
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @return array|\stdClass
     */
    public function getTicker($market) {
        return $this->public('getticker', [
            'market' => $market
        ]);
    }

    /**
     * Used to get the last 24 hour summary of all active exchanges
     *
     * @return array|\stdClass
     */
    public function getMarketSummaries() {
        return $this->public('getmarketsummaries');
    }

    /**
     * https://bittrex.com/api/v2.0/pub/Markets/GetMarketSummaries
     *
     * @return array|\stdClass
     */
    public function getMarketSummariesV2() {
        return $this->public('Markets/GetMarketSummaries', [
            // no extra data
        ], 'v2.0');
    }

    /**
     * Used to get the last 24 hour summary of all active exchanges
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @return array|\stdClass
     */
    public function getMarketSummary($market) {
        return $this->public('getmarketsummary', [
            'market' => $market,
        ]);
    }

    /**
     * Used to get retrieve the orderbook for a given market
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @param string $type buy, sell or both to identify the type of orderbook to return
     * @param int $depth defaults to 20 - how deep of an order book to retrieve. Max is 50
     * @return array|\stdClass
     */
    public function getOrderBook($market, $type, $depth=20) {
        return $this->public('getorderbook', [
            'market' => $market,
            'type' => $type,
            'depth' => $depth,
        ]);
    }

    /**
     * Used to retrieve the latest trades that have occured for a specific market.
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @return array|\stdClass
     */
    public function getMarketHistory($market) {
        return $this->public('getmarkethistory', [
            'market' => $market,
        ]);
    }

    public function getValidChartDataTickIntervals() {
        $validTickIntervals = [
            '60' => 'oneMin',
            '300' => 'fiveMin',
            // fifteenMin
            '1800' => 'thirtyMin',
            '3600' => 'hour',
            '86400' => 'day',
            // threeDays
            // week
            // month
        ];

        return $validTickIntervals;
    }


    /**
     * This is an undocumented public API, that will return the chart data.
     * Valid tick intervals are: ['oneMin','fiveMin','thirtyMin','hour','day']
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @param string $tickInterval
     * @return mixed
     */
    public function getChartData($marketName, $tickInterval='hour') {
        $timestamp = strtotime('now');
        return $this->public('market/GetTicks', [
            'marketName' => $marketName,
            'tickInterval' => $tickInterval,
            '_' => $timestamp,
        ], 'v2.0');
    }

    /**
     * Used to place a buy order in a specific market. Use buylimit to place limit orders.
     * Make sure you have the proper permissions set on your API keys for this call to work
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @param string|float $quantity the amount to purchase
     * @param string|float rate the rate at which to place the order.
     *
     * @return array|\stdClass Returns you the order uuid
     */
    public function buyLimit($market, $quantity, $rate) {
        return $this->market('buylimit', [
            'market' => $market,
            'quantity' => $quantity,
            'rate' => $rate,
        ]);
    }

    /**
     * Used to place an sell order in a specific market. Use selllimit to place limit orders.
     * Make sure you have the proper permissions set on your API keys for this call to work
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @param string|float $quantity the amount to sell
     * @param string|float rate the rate at which to place the order.
     *
     * @return array|\stdClass Returns you the order uuid
     *
     */
    public function sellLimit($market, $quantity, $rate) {
        return $this->market('selllimit', [
            'market' => $market,
            'quantity' => $quantity,
            'rate' => $rate,
        ]);
    }

    /**
     * Used to cancel a buy or sell order.
     *
     * @param string $uuid uuid of buy or sell order
     * @return array|\stdClass
     */
    public function cancelOrder($uuid) {
        return $this->market('cancel', [
            'uuid' => $uuid,
        ]);
    }

    /**
     * Get all orders that you currently have opened. A specific market can be requested
     *
     * @param string|null $market a string literal for the market (ie. BTC-LTC)
     * @return array|\stdClass
     */
    public function getOpenOrders($market=null) {
        return $this->market('getopenorders', [
            'market' => $market,
        ]);
    }

    /**
     * Used to retrieve all balances from your account
     *
     * @return array|\stdClass
     */
    public function getBalances() {
        return $this->account('getbalances');
    }

    /**
     * Used to retrieve the balance from your account for a specific currency.
     *
     * @param string $currency a string literal for the currency (ex: LTC)
     * @return array|\stdClass
     */
    public function getBalance($currency) {
        return $this->account('getbalance', [
            'currency' => $currency,
        ]);
    }

    /**
     * Used to retrieve or generate an address for a specific currency.
     * If one does not exist, the call will fail and return ADDRESS_GENERATING until one is available.
     *
     * @param string $currency a string literal for the currency (ex: LTC)
     * @return array|\stdClass
     */
    public function getDepositAddress($currency) {
        return $this->account('getdepositaddress', [
            'currency' => $currency,
        ]);
    }

    /**
     * Used to withdraw funds from your account.
     * note: please account for txfee.
     *
     * @param string $currency a string literal for the currency (ex: LTC)
     * @param string|float $quantity the quantity of coins to withdraw
     * @param string $address the address where to send the funds.
     * @param string $paymentId used for CryptoNotes/BitShareX/Nxt optional field (memo/paymentid)
     * @return array|\stdClass Returns you the withdrawal uuid
     */
    public function withdraw($currency, $quantity, $address, $paymentId=null) {
        return $this->account('withdraw', [
            'currency' => $currency,
            'quantity' => $quantity,
            'address' => $address,
            'paymentid' => $paymentId,
        ]);
    }

    /**
     * Used to retrieve a single order by uuid.
     *
     * @param string $uuid the uuid of the buy or sell order
     * @return array|\stdClass
     */
    public function getOrder($uuid) {
        return $this->account('getorder', [
            'uuid' => $uuid,
        ]);
    }

    /**
     * Used to retrieve your order history.
     *
     * @param string|null $market
     * @return array|\stdClass
     */
    public function getOrderHistory($market=null) {
        return $this->account('getorderhistory', [
            'market' => $market,
        ]);
    }

    /**
     * Used to retrieve your withdrawal history.
     *
     * @param string| null $currency a string literal for the currecy (ie. BTC). If omitted, will return for all currencies
     * @return array|\stdClass
     */
    public function getWithdrawalHistory($currency=null) {
        return $this->account('getwithdrawalhistory', [
            'currency' => $currency,
        ]);
    }

    /**
     * Used to retrieve your deposit history.
     *
     * @param string| null $currency a string literal for the currecy (ie. BTC). If omitted, will return for all currencies
     * @return array|\stdClass
     */
    public function getDepositHistory($currency=null) {
        return $this->account('getdeposithistory', [
            'currency' => $currency,
        ]);
    }


    /**
     * Execute a public API request
     *
     * @param $segment
     * @param array $parameters
     * @return array|\stdClass
     */
    function public ($segment, array $parameters=[], $version='v1.1') {
        $options = [
            'http' => [
                'method'  => 'GET',
                'timeout' => 10,
            ],
        ];

        $publicUrl = $this->getPublicUrl($version);
        $url = $publicUrl . $segment . '?' . http_build_query(array_filter($parameters));
        $feed = file_get_contents($url, false, stream_context_create($options));
        if ($this->returnType == 'object') {
            return json_decode($feed);
        } else {
            return json_decode($feed, true);
        }
    }

    /**
     * Execute a market API request
     *
     * @param $segment
     * @param array $parameters
     * @return array|\stdClass
     */
    public function market($segment, array $parameters=[]) {
        $baseUrl = $this->marketUrl;
        return $this->nonPublicRequest($baseUrl, $segment, $parameters);
    }

    /**
     * Execute an account API request
     *
     * @param $segment
     * @param array $parameters
     * @return array|\stdClass
     */
    public function account($segment, array $parameters=[]) {
        $baseUrl = $this->accountUrl;
        return $this->nonPublicRequest($baseUrl, $segment, $parameters);
    }


    /**
     * Executes a non-public API request (market|account),
     * using nonce, key & secret
     *
     * @param $baseUrl
     * @param $segment
     * @param array $parameters
     * @return array|\stdClass
     */
    protected function nonPublicRequest($baseUrl, $segment, $parameters=[]) {
        $parameters = array_merge(array_filter($parameters), [
            'apiKey' => $this->key,
            'nonce' => time()
        ]);

        $uri = $baseUrl . $segment . '?' . http_build_query($parameters);
        $sign = hash_hmac('sha512', $uri, $this->secret);
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apisign:$sign",
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,
            'Mozilla/4.0 (compatible; Bittrex PHP-Laravel Client; ' . php_uname('a') . '; PHP/' . phpversion() . ')'
        );

        $execResult = curl_exec($ch);
        if ($this->returnType == 'object') {
            $res = json_decode($execResult);
        } else {
            $res = json_decode($execResult, true);
        }
        return $res;
    }

    private function getPublicUrl($version)
    {
        switch($version) {
            case 'v1.1':
                return $this->publicUrl;
            case 'v2.0':
                return $this->publicUrlV2;
            default:
                throw new \Exception("Invalid Bittrex API version: $version");
        }
    }
    
    public function setAuthKey($key)
    {
        $this->key = $key;
    }    
    public function setAuthSecret($key){
        $this->secret = $key;
    }
    
}
