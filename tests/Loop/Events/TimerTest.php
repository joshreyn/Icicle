<?php
namespace Icicle\Tests\Loop\Events;

use Icicle\Loop\Events\Timer;
use Icicle\Tests\TestCase;

class TimerTest extends TestCase
{
    const TIMEOUT = 0.1;
    
    protected $manager;
    
    public function setUp()
    {
        $this->manager = $this->getMock('Icicle\Loop\Events\Manager\TimerManagerInterface');
    }
    
    public function createTimer($interval, $periodic, callable $callback, array $args = null)
    {
        return new Timer($this->manager, $interval, $periodic, $callback, $args);
    }

    public function testGetInterval()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));
        
        $this->assertSame(self::TIMEOUT, $timer->getInterval());
    }
    
    /**
     * @depends testGetInterval
     */
    public function testInvalidInterval()
    {
        $timer = $this->createTimer(-1, false, $this->createCallback(0));
        
        $this->assertGreaterThanOrEqual(0, $timer->getInterval());
    }
    
    public function testCall()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(2));
        
        $timer->call();
        $timer->call();
    }
    
    /**
     * @depends testCall
     */
    public function testInvoke()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(2));
        
        $timer();
        $timer();
    }
    
    public function testIsPending()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));
        
        $this->manager->expects($this->once())
            ->method('isPending')
            ->with($this->identicalTo($timer))
            ->will($this->returnValue(true));
        
        $this->assertTrue($timer->isPending());
    }
    
    public function testIsPeriodic()
    {
        $timer = $this->createTimer(self::TIMEOUT, true, $this->createCallback(0));
        
        $this->assertTrue($timer->isPeriodic());
        
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));
        
        $this->assertFalse($timer->isPeriodic());
    }

    public function testStart()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));

        $this->manager->expects($this->once())
            ->method('start')
            ->with($this->identicalTo($timer));

        $timer->start();
    }

    public function testStop()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));
        
        $this->manager->expects($this->once())
            ->method('stop')
            ->with($this->identicalTo($timer));
        
        $timer->stop();
    }
    
    public function testUnreference()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));
        
        $this->manager->expects($this->once())
            ->method('unreference')
            ->with($this->identicalTo($timer));
        
        $timer->unreference();
    }
    
    public function testReference()
    {
        $timer = $this->createTimer(self::TIMEOUT, false, $this->createCallback(0));
        
        $this->manager->expects($this->once())
            ->method('reference')
            ->with($this->identicalTo($timer));
        
        $timer->reference();
    }
    
    /**
     * @depends testCall
     */
    public function testArguments()
    {
        $arg1 = 1;
        $arg2 = 2;
        $arg3 = 3;
        $arg4 = 4;
        
        $callback = $this->createCallback(1);
        $callback->method('__invoke')
                 ->with(
                     $this->identicalTo($arg1),
                     $this->identicalTo($arg2),
                     $this->identicalTo($arg3),
                     $this->identicalTo($arg4)
                 );
        
        $timer = $this->createTimer(self::TIMEOUT, false, $callback, [$arg1, $arg2, $arg3, $arg4]);
        
        $timer->call();
    }
}
