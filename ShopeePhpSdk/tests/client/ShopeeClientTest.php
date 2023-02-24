<?php

namespace Test\client;

use Freeflygit\ShopeePhpSdk\client\ShopeeApiConfig;
use PHPUnit\Framework\TestCase;

class ShopeeClientTest extends TestCase
{
    public function testCreateShopeeClient(){
        $client = new ShopeeApiConfig();
        $client->setPartnerId(001);

        $this->assertEquals($client->getPartnerId(), 001);
    }
}
