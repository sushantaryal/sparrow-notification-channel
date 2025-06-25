<?php

namespace Sushant\SparrowSmsNotification\Tests;

use PHPUnit\Framework\TestCase;
use Sushant\SparrowSmsNotification\SparrowMessage;

class SparrowMessageTest extends TestCase
{
    public function test_content_via_constructor()
    {
        $message = new SparrowMessage('Hello World');

        $this->assertEquals('Hello World', $message->content);
    }

    public function test_content_using_content_method()
    {
        $message = new SparrowMessage();

        $message->content('Hello World');

        $this->assertEquals('Hello World', $message->content);
    }

    public function test_from_using_from_method()
    {
        $message = new SparrowMessage('Hello World');

        $message->from('1234567890');

        $this->assertEquals('1234567890', $message->from);
    }

    public function test_supports_method_chaining()
    {
        $message = (new SparrowMessage('Hello World'))
            ->content('Hello World')
            ->from('1234567890');

        $this->assertEquals('Hello World', $message->content);
        $this->assertEquals('1234567890', $message->from);
    }
}
