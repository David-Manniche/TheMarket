<?php

trait StripeConnectFunctions
{
    /**
     * create - Create Custom Account
     *
     * @param array $requestParam
     * @return object
     */
    private function create(array $requestParam): object
    {
        return \Stripe\Account::create($requestParam);
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
     * @param array $requestParam
     * @return object
     */
    private function update(array $requestParam): object
    {
        return \Stripe\Account::update(
            $this->getAccountId(),
            $requestParam
        );
    }

    /**
     * createExternalAccount - For Financial(Bank) Data
     *
     * @param array $requestParam
     * @return object
     */
    private function createExternalAccount(array $requestParam): object
    {
        return \Stripe\Account::createExternalAccount(
            $this->getAccountId(),
            $requestParam
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
     * @param array $requestParam
     * @return object
     */
    private function createPerson(array $requestParam): object
    {
        array_walk_recursive($requestParam, function (&$val) {
            if (!is_object($val) && ($val == 0 || $val == 1)) {
                $val = 0 < $val ? 'true' : 'false';
            }
        });
        return \Stripe\Account::createPerson(
            $this->getAccountId(),
            $requestParam
        );
    }

    /**
     * updatePerson
     *
     * @param array $requestParam - Update Relationship Person
     * @return object
     */
    private function updatePerson(array $requestParam): object
    {
        array_walk_recursive($requestParam, function (&$val) {
            if (!is_object($val) && ($val == 0 || $val == 1)) {
                $val = 0 < $val ? 'true' : 'false';
            }
        });
        return \Stripe\Account::updatePerson(
            $this->getAccountId(),
            $this->getRelationshipPersonId(),
            $requestParam
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
     * @param array $requestParam
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
    private function createSession(array $requestParam): object
    {
        $this->resp = \Stripe\Checkout\Session::create($requestParam);
        if (false === $this->resp) {
            return (object) array();
        }
        return $this->resp;
    }

    /**
     * createPrice
     *
     * @param array $requestParam
     * @return object
     */
    private function createPrice(array $requestParam): object
    {
        return \Stripe\Price::create($requestParam);
    }

    /**
     * createCustomer
     *
     * @param array $requestParam
     * @return object
     */
    private function createCustomer(array $requestParam): object
    {
        return \Stripe\Customer::create($requestParam);
    }

    /**
     * updateCustomer
     *
     * @param array $requestParam
     * @return object
     */
    private function updateCustomer(array $requestParam): object
    {
        return \Stripe\Customer::update(
            $this->getCustomerId(),
            $requestParam
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
     * @param array $requestParam
     * @return object
     */
    private function connectedAccounts(array $requestParam = ['limit' => 10]): object
    {
        return \Stripe\Account::all($requestParam);
    }

    /**
     * requestRefund
     *
     * @param array $requestParam
     * Follow : https://stripe.com/docs/api/refunds/create
     * @return object
     */
    private function requestRefund(array $requestParam = []): object
    {
        return \Stripe\Refund::create($requestParam);
    }

    /**
     * transferAmount
     *
     * @param array $requestParam : [
     *         'amount' => 7000,
     *         'currency' => 'inr',
     *         'destination' => '{{CONNECTED_STRIPE_ACCOUNT_ID}}',
     *         'transfer_group' => '{ORDER10}',
     *       ]
     * @return object
     */
    private function transferAmount(array $requestParam = []): object
    {
        return \Stripe\Transfer::create($requestParam);
    }

    /**
     * reverseTransfer
     *
     * @param array $requestParam : [
     *         'transferId' => 'tr_1XXXXXXXXXXXX,
     *         'data' => [
     *              'amount' => 1000, // In Paisa
     *              'description' => '',
     *              'metadata' => [
     *                  'xyz' => 'abc' // Set of key-value pairs that you can attach to an object.
     *              ],
     *          ],
     *       ]
     * @return object
     */
    private function reverseTransfer(array $requestParam = []): object
    {
        $transferId = $requestParam['transferId'];
        $data = $requestParam['data'];
        return \Stripe\Transfer::createReversal($transferId, $data);
    }
    
    /**
     * doRequest
     *
     * @param  mixed $requestType
     * @return mixed
     */
    public function doRequest(int $requestType, array $requestParam = [])
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
                    return $this->updateAccount($requestParam);
                    break;
                case self::REQUEST_PERSON_TOKEN:
                    return $this->getPersonToken();
                    break;
                case self::REQUEST_ADD_BANK_ACCOUNT:
                    return $this->addFinancialInfo($requestParam);
                    break;
                case self::REQUEST_UPDATE_BUSINESS_TYPE:
                    return $this->updateBusinessType($requestParam);
                    break;
                case self::REQUEST_CREATE_PERSON:
                    return $this->createPerson($requestParam);
                    break;
                case self::REQUEST_UPDATE_PERSON:
                    return $this->updatePerson($requestParam);
                    break;
                case self::REQUEST_UPLOAD_VERIFICATION_FILE:
                    return $this->createFile(reset($requestParam));
                    break;
                case self::REQUEST_DELETE_ACCOUNT:
                    return $this->delete();
                    break;
                case self::REQUEST_CREATE_SESSION:
                    return $this->createSession($requestParam);
                    break;
                case self::REQUEST_CREATE_PRICE:
                    return $this->createPrice($requestParam);
                    break;
                case self::REQUEST_CREATE_CUSTOMER:
                    return $this->createCustomer($requestParam);
                    break;
                case self::REQUEST_UPDATE_CUSTOMER:
                    return $this->updateCustomer($requestParam);
                    break;
                case self::REQUEST_CREATE_LOGIN_LINK:
                    return $this->loginLink();
                    break;
                case self::REQUEST_ALL_CONNECT_ACCOUNTS:
                    return $this->connectedAccounts($requestParam);
                    break;
                case self::REQUEST_INITIATE_REFUND:
                    return $this->requestRefund($requestParam);
                    break;
                case self::REQUEST_TRANSFER_AMOUNT:
                    return $this->transferAmount($requestParam);
                    break;
                case self::REQUEST_REVERSE_TRANSFER:
                    return $this->reverseTransfer($requestParam);
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
        } catch (Error $e) {
            // Handle error
            $this->error = $e->getMessage();
        }
        return false;
    }
}
