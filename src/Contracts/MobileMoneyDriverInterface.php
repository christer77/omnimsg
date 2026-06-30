<?php

namespace OmniMsg\Contracts;

interface MobileMoneyDriverInterface
{
    /**
     * Initialize a deposit via the driver
     *
     * @param array  $options: Additional options (dynamic credentials, priority, etc.)
     * @return array Result of the initialization (success, status_code, data, message)
     */
    public function initDeposit(array $options): array;
    
    /**
     * Initialize a withdraw via the driver
     *
     * @param array  $options: Additional options (dynamic credentials, priority, etc.)
     * @return array Result of the initialization (success, status_code, data, message)
     */
    public function initWithdraw(array $options): array;
    
    /**
     * Get the status of a transaction
     *
     * @param string $transaction_id: The transaction ID
     * @param string $type: The type of transaction (deposit or withdraw)
     * @return array Result of the transaction status (success, status_code, data, message)
     */
    public function getTransactionStatus(string $transaction_id, string $type = 'deposit'): array;
}