<?php
namespace Pepijnolivier\Bittrex;

interface ClientContract
{
    /**
     * Get my balances.
     *
     * @return array
     */
    public function getMarkets();
}
