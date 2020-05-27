<?php

trait StripeConnectFunctions
{
    /**
     * create - Create Custom Account
     * 
     * @param array $data 
     * @return object
     */
    private function create(array $data): object
    {
        return \Stripe\Account::create($data);
    }

    /**
     * retrieve - Retrieve account info
     *
     * @return object
     */
    private function retrieve(string $accountId = ""): object
    {
        $accountId = empty($accountId) ? $this->getAccountId() : $accountId;
        return \Stripe\Account::retrieve($accountId);
    }

    /**
     * update - Update Account Data
     * 
     * @param array $data 
     * @return object
     */
    private function update(array $data): object
    {
        return \Stripe\Account::update(
            $this->getAccountId(),
            $data
        );
    }

    /**
     * createExternalAccount - For Financial(Bank) Data
     * 
     * @param array $data 
     * @return object
     */
    private function createExternalAccount(array $data): object
    {
        return \Stripe\Account::createExternalAccount(
            $this->getAccountId(),
            $data
        );
    }

    /**
     * createToken - To generate person ID
     *
     * @return object
     */
    private function createToken(): object
    {
        return \Stripe\Token::create([
            'pii' => ['id_number' => '000000000'],
        ]);
    }

    /**
     * createPerson - Relationship Person
     * 
     * @param array $data 
     * @return object
     */
    private function createPerson(array $data): object
    {
        array_walk_recursive($data, function (&$val) {
            if (!is_object($val) && ($val == 0 || $val == 1)) {
                $val = 0 < $val ? 'true' : 'false';
            }
        });
        return \Stripe\Account::createPerson(
            $this->getAccountId(),
            $data
        );
    }

    /**
     * updatePerson
     * 
     * @param array $data - Update Relationship Person
     * @return object
     */
    private function updatePerson(array $data): object
    {
        array_walk_recursive($data, function (&$val) {
            if (!is_object($val) && ($val == 0 || $val == 1)) {
                $val = 0 < $val ? 'true' : 'false';
            }
        });
        return \Stripe\Account::updatePerson(
            $this->getAccountId(),
            $this->getRelationshipPersonId(),
            $data
        );
    }

    /**
     * createFile - Creating file object to update any document
     * 
     * @param string $filePath 
     * @return object
     */
    private function createFile(string $filePath): object
    {
        $fp = fopen($filePath, 'r');
        return \Stripe\File::create([
            'purpose' => 'identity_document',
            'file' => $fp
        ]);
    }
    
    /**
     * delete
     *
     * Description - Accounts created using test-mode keys can be deleted at any time. 
     * Accounts created using live-mode keys can only be deleted once all balances are zero.
     * @return object
     */
    private function delete(): object
    {
        return $this->retrieve($this->getAccountId())->delete();
    }

    /**
     * createSession
     * 
     * @param array $data 
     *      e.g.[
     *          'success_url' => '',
     *          'cancel_url' => '',
     *          'payment_method_types' => ['card'],
     *          'line_items' => [
     *               [
     *                 'price' => '',
     *                 'quantity' => ?,
     *               ],
     *           ],
     *       ]
     * @return object
     */
    private function createSession(array $data): object
    {
        $this->resp = \Stripe\Checkout\Session::create($data);
        if (false === $this->resp) {
            return (object) array();
        }
        return $this->resp;
    }

    /**
     * createPrice
     *
     * @param array $data
     * @return object
     */
    private function createPrice(array $data): object
    {
        return \Stripe\Price::create($data);
    }

    /**
     * createCustomer
     *
     * @param array $data
     * @return object
     */
    private function createCustomer(array $data): object
    {
        return \Stripe\Customer::create($data);
    }

    /**
     * updateCustomer
     *
     * @param array $data
     * @return object
     */
    private function updateCustomer(array $data): object
    {
        return \Stripe\Customer::update(
            $this->getCustomerId(),
            $data
        );
    }

    /**
     * loginLink
     * 
     * Description - You may only create login links for Express accounts connected to your platform.
     * @return object
     */
    private function loginLink(): object
    {
        return \Stripe\Account::createLoginLink(
            $this->getAccountId()
        );
    }

    /**
     * connectedAccounts
     * 
     * @param array $data
     * @return object
     */
    private function connectedAccounts(array $data = ['limit' => 10]): object
    {
        return \Stripe\Account::all($data);
    }

    /**
     * webhookConstructEvent
     * 
     * @param array $data
     * @return object
     */
    private function webhookConstructEvent(array $data = []): object
    {
        $payload = $data['payload'];
        $sig_header = $data['sig_header'];
        $endpoint_secret = $this->settings['webhook_signing_secret'];

        return \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    }

    /**
     * transferAmount
     * 
     * @param array $data : [
     *         'amount' => 7000,
     *         'currency' => 'inr',
     *         'destination' => '{{CONNECTED_STRIPE_ACCOUNT_ID}}',
     *         'transfer_group' => '{ORDER10}',
     *       ]
     * @return object
     */
    private function transferAmount(array $data = []): object
    {
        return \Stripe\Transfer::create($data);
    }
    
    /**
     * doRequest
     *
     * @param  mixed $requestType
     * @return mixed
     */
    public function doRequest(int $requestType, array $data = [])
    {
        try {
            switch ($requestType) {
                case self::REQUEST_CREATE_ACCOUNT:
                    return $this->createAccount();
                    break;
                case self::REQUEST_RETRIEVE_ACCOUNT:
                    return $this->retrieve();
                    break;
                case self::REQUEST_UPDATE_ACCOUNT:
                    return $this->updateAccount($data);
                    break;
                case self::REQUEST_PERSON_TOKEN:
                    return $this->getPersonToken();
                    break;
                case self::REQUEST_ADD_BANK_ACCOUNT:
                    return $this->addFinancialInfo($data);
                    break;
                case self::REQUEST_UPDATE_BUSINESS_TYPE:
                    return $this->updateBusinessType($data);
                    break;
                case self::REQUEST_CREATE_PERSON:
                    return $this->createPerson($data);
                    break;
                case self::REQUEST_UPDATE_PERSON:
                    return $this->updatePerson($data);
                    break;
                case self::REQUEST_UPLOAD_VERIFICATION_FILE:
                    return $this->createFile(reset($data));
                    break;
                case self::REQUEST_DELETE_ACCOUNT:
                    return $this->delete();
                    break;
                case self::REQUEST_CREATE_SESSION:
                    return $this->createSession($data);
                    break;
                case self::REQUEST_CREATE_PRICE:
                    return $this->createPrice($data);
                    break;
                case self::REQUEST_CREATE_CUSTOMER:
                    return $this->createCustomer($data);
                    break;
                case self::REQUEST_UPDATE_CUSTOMER:
                    return $this->updateCustomer($data);
                    break;
                case self::REQUEST_CREATE_LOGIN_LINK:
                    return $this->loginLink();
                    break;
                case self::REQUEST_ALL_CONNECT_ACCOUNTS:
                    return $this->connectedAccounts($data);
                    break;
                case self::REQUEST_CREATE_WEBHOOK_EVENT:
                    return $this->webhookConstructEvent($data);
                    break;
                case self::REQUEST_TRANSFER_AMOUNT:
                    return $this->transferAmount($data);
                    break;
            }
        } catch (\Stripe\Exception\CardException $e) {
            // Since it's a decline, \Stripe\Exception\CardException will be caught
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Too many requests made to the API too quickly
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Invalid parameters were supplied to Stripe's API
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Authentication with Stripe's API failed
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
            // (maybe you changed API keys recently)
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Network communication with Stripe failed
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Display a very generic error to the user, and maybe send
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
            // yourself an email
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Display a very generic error to the user, and maybe send
            $this->error = $e->getMessage();
            // yourself an email
        } catch (\UnexpectedValueException $e) {
            // Display a very generic error to the user, and maybe send
            $this->error = $e->getError()->param . ' - ' . $e->getMessage();
            // yourself an email
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->error = $e->getMessage();
        }
        return false;
    }
}