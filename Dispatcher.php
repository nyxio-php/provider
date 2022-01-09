<?php

declare(strict_types=1);

namespace Nyx\Provider;

use Nyx\Contract\Container\ContainerInterface;
use Nyx\Contract\Provider\ProviderDispatcherInterface;
use Nyx\Contract\Provider\ProviderInterface;

class Dispatcher implements ProviderDispatcherInterface
{
    public function __construct(private readonly ContainerInterface $container)
    {
    }

    /**
     * @param string[] $providers
     * @return void
     */
    public function dispatch(array $providers = []): void
    {
        foreach ($providers as $providerClass) {
            $provider = $this->container->get($providerClass);

            if (!$provider instanceof ProviderInterface) {
                continue;
            }

            if (!$this->container->hasSingleton($providerClass)) {
                $this->container->singleton($providerClass, $provider);
            }

            $provider->process();
        }
    }
}
