<?php

namespace PUGX\Godfather\Test;

use PUGX\Godfather\Godfather;
use PUGX\Godfather\Test\Fixture\Entity\Sandwich;
use PUGX\Godfather\Test\Fixture\Entity\Socket;
use PUGX\Godfather\Test\Fixture\SocketCartItem;
use PUGX\Godfather\Test\Fixture\StandardCartItem;
use PUGX\Godfather\Test\Fixture\Entity\Ticket;
use PUGX\Godfather\Test\Fixture\TicketCartItem;

class FunctionalTest extends \PHPUnit_Framework_TestCase
{
    public $godfather;

    public function setUp()
    {

        $this->godfather = new Godfather();
        $this->godfather->addContext('cart', 'PUGX\Godfather\Test\Fixture\CartItemInterface', new StandardCartItem());

        // start adding billion of strategy
        $this->godfather->addStrategy('cart', 'PUGX\Godfather\Test\Fixture\Entity\Ticket', new TicketCartItem());
        $this->godfather->addStrategy('cart', 'PUGX\Godfather\Test\Fixture\Entity\Socket', new SocketCartItem());
    }

    /**
     * @dataProvider provider
     */
    public function testStrategyUsage($contextName, $product, $input, $assertion)
    {
        $strategy = $this->godfather->getStrategy($contextName, $product);
        $strategy->addToCart($product, $input);
    }

    public function provider()
    {
        return array(
            'with the Ticket Entity' => array('cart', new Ticket(), 'today', 'ticket.today'),
            'with the Socket Entity' => array('cart', new Socket(), 'tomorrow', 'socket.tomorrow'),
            'with the Fallback' => array('cart', new Sandwich(), 'yesterday', 'standard.yesterday'),
        );
    }
}