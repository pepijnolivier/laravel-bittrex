<?php
namespace Pepijnolivier\Bittrex;

class Client implements ClientContract
{
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
    public function __construct(array $auth, array $urls)
    {
        $this->marketUrl  = array_get($urls, 'market');
        $this->publicUrl  = array_get($urls, 'public');
        $this->accountUrl = array_get($urls, 'account');

        $this->key    = array_get($auth, 'key');
        $this->secret = array_get($auth, 'secret');
    }

    /**
     * Used to get the open and available trading markets at Bittrex along with other meta data.
     *
     * @return array
     */
    public function getMarkets()
    {
        return $this->public('getmarkets');
    }

    /**
     * Used to get all supported currencies at Bittrex along with other meta data.
     *
     * @return array
     */
    public function getCurrencies()
    {
        return $this->public('getcurrencies');
    }

    /**
     * Used to get the current tick values for a market.
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @return array
     */
    public function getTicker($market)
    {
        return $this->public('getticker', [
            'market' => $market
        ]);
    }

    /**
     * Used to get the last 24 hour summary of all active exchanges
     *
     * @return array
     */
    public function getMarketSummaries() {
        return $this->public('getmarketsummaries');
    }

    /**
     * Used to get the last 24 hour summary of all active exchanges
     *
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @return array
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
     * @return array
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
     * @return array
     */
    public function getMarketHistory($market) {
        return $this->public('getmarkethistory', [
            'market' => $market,
        ]);
    }

    /**
     * @inheritdoc
     */
    function public ($segment, array $parameters=[]) {
        $options = [
            'http' => [
                'method'  => 'GET',
                'timeout' => 10,
            ],
        ];

        $url = $this->publicUrl . $segment . '?' . http_build_query(array_filter($parameters));

        $feed = file_get_contents(
            $url, false, stream_context_create($options)
        );

        return json_decode($feed, true);
    }

    public function market($segment, array $parameters=[]) {
        $parameters = array_merge(array_filter($parameters), [
            'apiKey' => $this->key,
            'nonce' => time()
        ]);

        $uri = $this->marketUrl . $segment . '?' . http_build_query($parameters);
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
        $res = json_decode($execResult, true);
        return $res;
    }
}
