<?php

namespace Shopee;

class NodeGenerator
{
    public  function setNodeList(\Shopee\Client &$client)
    {
        $client->nodes['item'] = new Nodes\Item\Item($client);
        $client->nodes['logistics'] = new Nodes\Logistics\Logistics($client);
        $client->nodes['order'] = new Nodes\Order\Order($client);
        $client->nodes['returns'] = new Nodes\Returns\Returns($client);
        $client->nodes['shop'] = new Nodes\Shop\Shop($client);
        $client->nodes['shopCategory'] = new Nodes\ShopCategory\ShopCategory($client);
        $client->nodes['custom'] = new Nodes\Custom\Custom($client);
        $client->nodes['discount'] = new Nodes\Discount\Discount($client);
        $client->nodes['image'] = new Nodes\Image\Image($client);
        $client->nodes['push'] = new Nodes\Push\Push($client);
        $client->nodes['payment'] = new Nodes\Payment\Payment($client);
    }
}
