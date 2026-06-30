<?php

namespace OmniMsg\Channels;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;

class ChannelManager
{
    protected Application $app;
    protected string $defaultChannel = 'whatsapp';
    protected ?string $driverKey = null;
    protected ?string $recipient = null;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function channel(string $channel): static
    {
        $this->defaultChannel = $channel;
        $this->driverKey = null;
        return $this;
    }

    public function via(string $driverKey): static
    {
        $this->driverKey = $driverKey;
        return $this;
    }

    public function to(string $recipient): static
    {
        $this->recipient = $recipient;
        return $this;
    }

    public function send(string $body = null, string $to = null, array $options = []): array
    {
        $recipient = $to ?? $this->recipient;
        $driver = $this->resolveDriver();
        return $driver->send($body, $recipient, $options);
    }

    public function initDeposit(array $options)
    {
        $driver = $this->resolveDriver();
        return $driver->initDeposit($options);
    }
    
    public function initWithdraw(array $options)
    {
        $driver = $this->resolveDriver();
        return $driver->initWithdraw($options);
    }
    
    public function getTransactionStatus(string $transaction_id, string $type = 'deposit')
    {
        $driver = $this->resolveDriver();
        return $driver->getTransactionStatus($transaction_id, $type);
    }

    protected function resolveDriver()
    {
        $channelConfig = config("omnimsg.channels.{$this->defaultChannel}");

        if (!$channelConfig) {
            throw new InvalidArgumentException("Channel [{$this->defaultChannel}] not found in config.");
        }

        $driverKey = $this->driverKey ?? $channelConfig['default_driver'];
        $driverConfig = $channelConfig['drivers'][$driverKey] ?? null;

        if (!$driverConfig) {
            throw new InvalidArgumentException("Driver [{$driverKey}] not found for channel [{$this->defaultChannel}].");
        }

        $driverClass = $driverConfig['class'];
        $credentials = $driverConfig['credentials'] ?? [];

        return new $driverClass($credentials);
    }
}
