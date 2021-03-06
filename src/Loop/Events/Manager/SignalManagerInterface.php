<?php
namespace Icicle\Loop\Events\Manager;

use Icicle\Loop\Events\SignalInterface;

interface SignalManagerInterface
{
    /**
     * Creates a signal event connected to the manager.
     *
     * @param   int $signo
     * @param   callable $callback
     * @param   mixed[]|null $args
     *
     * @return  \Icicle\Loop\Events\SignalInterface
     */
    public function create($signo, callable $callback, array $args = null);

    /**
     * Enables listening for the signal.
     *
     * @param \Icicle\Loop\Events\SignalInterface $signal
     */
    public function enable(SignalInterface $signal);

    /**
     * Disables listening for the signal.
     *
     * @param   \Icicle\Loop\Events\SignalInterface
     */
    public function disable(SignalInterface $signal);
    
    /**
     * Determines if the signal event is in the loop.
     *
     * @param   \Icicle\Loop\Events\SignalInterface
     *
     * @return  bool
     */
    public function isEnabled(SignalInterface $signal);

    /**
     * Clears all signals from the manager.
     */
    public function clear();
}
