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

    public function getMarkets()
    {
        return $this->public('getmarkets');
    }

    public function getCurrencies()
    {
        return $this->public('getcurrencies');
    }

    /**
     * @param string $market a string literal for the market (ex: BTC-LTC)
     */
    public function getTicker($market)
    {
        return $this->public('getticker', [
            'market' => $market
        ]);
    }

    public function getMarketSummaries() {
        return $this->public('getmarketsummaries');
    }

    /**
     * @param string $market a string literal for the market (ex: BTC-LTC)
     */
    public function getMarketSummary($market) {
        return $this->public('getmarketsummary', [
            'market' => $market,
        ]);
    }

    /**
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @param string $type buy, sell or both to identify the type of orderbook to return
     * @param int $depth defaults to 20 - how deep of an order book to retrieve. Max is 50
     * @return mixed
     */
    public function getOrderBook($market, $type, $depth=20) {
        return $this->public('getorderbook', [
            'market' => $market,
            'type' => $type,
            'depth' => $depth,
        ]);
    }

    /**
     * @param string $market a string literal for the market (ex: BTC-LTC)
     * @return mixed
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

    /**
     * @inheritdoc
     */
    public function trading(array $parameters = [])
    {
        $mt                  = (string) microtime(true);
        $parameters['nonce'] = intval(substr(str_replace('.', '', $mt), 0, 13));

        $post = http_build_query(array_filter($parameters), '', '&');
        $sign = hash_hmac('sha512', $post, $this->secret);

        $headers = [
            'Key: ' . $this->key,
            'Sign: ' . $sign,
        ];

        static $ch = null;

        if (is_null($ch)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT,
                'Mozilla/4.0 (compatible; Bittrex PHP-Laravel Client; ' . php_uname('a') . '; PHP/' . phpversion() . ')'
            );
        }

        curl_setopt($ch, CURLOPT_URL, $this->tradingUrl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        return json_decode($response, true);
    }
}
