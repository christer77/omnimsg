<?php

namespace OmniMsg\Contracts;

interface MessageDriverInterface
{
    /**
     * Send a message via the driver
     *
     * @param string $body: message content
     * @param string $to: Recipient (phone number)
     * @param array  $options: Additional options (dynamic credentials, priority, etc.)
     * @return array Result of the send (success, status_code, data, message)
     */
    public function send(string $body, string $to, array $options = []): array;
}