<?php
namespace HeraldOfArms\Herald;

use Arr;
use HeraldOfArms\Contracts\FactoryInterface;
use InvalidArgumentException;

class Herald implements FactoryInterface
{
    /**
     * The current adapters instances.
     *
     * @var HeraldOfArms\Contracts\GatewayInterface[]
     */
    protected $factories = [];

    /**
     * Create a new gateway instance.
     *
     * @param string[] $config
     *
     * @throws InvalidArgumentException
     *
     * @return HeraldOfArms\Contracts\GatewayInterface
     */
    public function make(array $config)
    {
        Arr::requires($config, 'driver');

        return $this->getGatewayFor($config['driver'])->make($config);
    }

    /**
     * Get an adapter instance by name.
     *
     * @param string $name
     *
     * @return HeraldOfArms\Contracts\GatewayInterface
     */
    public function getGatewayFor($name)
    {
        // Return if class were already instantiated.
        if (isset($this->factories['name'])) {
            return $this->factories['name'];
        }

        $driver = ucfirst($name);
        $class  = "HeraldOfArms\\{$driver}\\{$driver}Gateway";

        if (class_exists($class)) {
            return $this->factories['name'] = new $class();
        }

        throw new InvalidArgumentException("Unsupported [$name] gateway.");
    }
}