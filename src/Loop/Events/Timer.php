<?php
namespace Icicle\Loop\Events;

use Icicle\Loop\Events\Manager\TimerManagerInterface;

class Timer implements TimerInterface
{
    const MIN_INTERVAL = 0.001; // 1ms minimum interval.
    
    /**
     * @var \Icicle\Loop\Events\Manager\TimerManagerInterface
     */
    private $manager;
    
    /**
     * Callback function to be called when the timer expires.
     *
     * @var callable
     */
    private $callback;
    
    /**
     * Number of seconds until the timer is called.
     *
     * @var float
     */
    private $interval;
    
    /**
     * True if the timer is periodic, false if the timer should only be called once.
     *
     * @var bool
     */
    private $periodic;
    
    /**
     * @param   \Icicle\Loop\Events\Manager\TimerManagerInterface $manager
     * @param   int|float $interval Number of seconds until the callback function is called.
     * @param   bool $periodic True to repeat the timer, false to only run it once.
     * @param   callable $callback Function called when the interval expires.
     * @param   mixed[]|null $args Optional array of arguments to pass the callback function.
     */
    public function __construct(
        TimerManagerInterface $manager,
        $interval,
        $periodic,
        callable $callback,
        array $args = null
    ) {
        $this->manager = $manager;
        $this->interval = (float) $interval;
        $this->periodic = (bool) $periodic;

        if (empty($args)) {
            $this->callback = $callback;
        } else {
            $this->callback = function () use ($callback, $args) {
                call_user_func_array($callback, $args);
            };
        }

        if (self::MIN_INTERVAL > $this->interval) {
            $this->interval = self::MIN_INTERVAL;
        }
    }
    
    /**
     * @inheritdoc
     */
    public function call()
    {
        $callback = $this->callback;
        $callback();
    }
    
    /**
     * @inheritdoc
     */
    public function __invoke()
    {
        $this->call();
    }
    
    /**
     * @inheritdoc
     */
    public function isPending()
    {
        return $this->manager->isPending($this);
    }

    /**
     * @inheritdoc
     */
    public function start()
    {
        $this->manager->start($this);
    }

    /**
     * @inheritdoc
     */
    public function stop()
    {
        $this->manager->stop($this);
    }
    
    /**
     * @inheritdoc
     */
    public function unreference()
    {
        $this->manager->unreference($this);
    }
    
    /**
     * @inheritdoc
     */
    public function reference()
    {
        $this->manager->reference($this);
    }
    
    /**
     * @inheritdoc
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @inheritdoc
     */
    public function isPeriodic()
    {
        return $this->periodic;
    }
}
