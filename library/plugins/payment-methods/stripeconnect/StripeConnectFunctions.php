<?php

trait StripeConnectFunctions
{
    /**
     * boolParams - Used to get bool type request params
     *
     * @var array
     */
    public $boolParams = [
        "director",
        "executive",
        "owner",
        "representative",
    ];

    /**
     * convertToBool
     *
     * @param  array $requestParam
     * @return void
     */
    public function convertToBool(array &$requestParam): void
    {
        array_walk_recursive($requestParam, function (&$val, $key) {
            if (in_array($key, $this->boolParams)) {
                $val = (bool) $val;
            }
        });
    }

    /**
     * create - Create Custom Account
     *
     * @param array $requestParam
     * @return object
     */
    private function create(array $requestParam): object
    {
        return $this->stripe->accounts->create($requestParam);
    }

    /**
     * retrieve - Retrieve account info
     *
     * @return object
     */
    private function retrieve(string $accountId = ""): object
    {
        $accountId = empty($accountId) ? $this->getAccountId() : $accountId;
        return $this->stripe->accounts->retrieve($accountId);
    }

    /**
     * update - Update Account Data
     *
     * @param array $requestParam
     * @return object
     */
    private function update(array $requestParam): object
    {
        return $this->stripe->accounts->update(
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
        return $this->stripe->accounts->createExternalAccount(
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
        return $this->stripe->tokens->create([
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
        $this->convertToBool($requestParam);
        /* var_dump($requestParam);
CommonHelper::printArray($requestParam);
die; */
        return $this->stripe->accounts->createPerson(
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
        $this->convertToBool($requestParam);
        /* var_dump($requestParam);
CommonHelper::printArray($requestParam);
die; */
        return $this->stripe->accounts->updatePerson(
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
        return $this->stripe->accounts->delete($this->getAccountId());
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
        $this->resp = $this->stripe->checkout->sessions->create($requestParam);
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
        return $this->stripe->prices->create($requestParam);
    }

    /**
     * createCustomer
     *
     * @param array $requestParam
     * @return object
     */
    private function createCustomer(array $requestParam): object
    {
        return $this->stripe->customers->create($requestParam);
    }

    /**
     * retrieveCustomer
     *
     * @return object
     */
    private function retrieveCustomer(): object
    {
        return $this->stripe->customers->retrieve($this->getCustomerId());
    }

    /**
     * updateCustomer
     *
     * @param array $requestParam
     * @return object
     */
    private function updateCustomer(array $requestParam): object
    {
        return $this->stripe->customers->update(
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
        return $this->stripe->accounts->createLoginLink(
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
        return $this->stripe->accounts->all($requestParam);
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
        return $this->stripe->refunds->create($requestParam);
    }

    /**
     * transferAmount
     *
     * @param array $requestParam : [
     *         'amount' => 7000,
     *         'currency' => 'inr',
     *         'destination' => '{{CONNECTED_STRIPE_ACCOUNT_ID}}',
     *         'transfer_group' => '{ORDER10}',
     *         'description' => '',
     *         'metadata' => [
     *              'xyz' => 'XXX'
     *          ]
     *       ]
     * @return object
     */
    private function transferAmount(array $requestParam = []): object
    {
        return $this->stripe->transfers->create($requestParam);
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
     *                  'xyz' => 'XXX' // Set of key-value pairs that you can attach to an object.
     *              ],
     *          ],
     *       ]
     * @return object
     */
    private function reverseTransfer(array $requestParam = []): object
    {
        $transferId = $requestParam['transferId'];
        $data = $requestParam['data'];
        return $this->stripe->transfers->createReversal($transferId, $data);
    }

    /**
     * createSource - This function is being used to save card on stripe connect
     *
     * @param array $requestParam : ['source' => 'tok_mastercard']
     * @return object
     */
    private function createSource(array $requestParam): object
    {
        return $this->stripe->customers->createSource(
            $this->getCustomerId(),
            ["source" => $requestParam['cardToken']]
        );
    }

    /**
     * deleteSource
     *
     * @param array $requestParam : ['cardId' => 'card_xxxxx']
     * @return object
     */
    private function deleteSource(array $requestParam): object
    {
        $cardId = $requestParam['cardId'];
        return $this->stripe->customers->deleteSource(
            $this->getCustomerId(),
            $cardId
        );
    }

    /**
     * listAllCards
     *
     * @return object
     */
    private function listAllCards(): object
    {
        $pagesize = FatApp::getConfig('CONF_ADMIN_PAGESIZE', FatUtility::VAR_INT, 10);
        return $this->stripe->customers->allSources(
            $this->getCustomerId(),
            ['object' => 'card', 'limit' => $pagesize]
        );
    }

    /**
     * createCardToken - Used if customer don't want to save card.
     *
     * @param array $requestParam : [
     *           'number' => '4242424242424242',
     *           'exp_month' => 6,
     *           'exp_year' => 2021,
     *           'cvc' => '314',
     *       ]
     * @return object
     */
    private function createCardToken(array $requestParam): object
    {
        return $this->stripe->tokens->create([
            'card' => $requestParam
        ]);
    }

    /**
     * charge
     *
     * @param array $requestParam
     * @return object
     */
    private function charge(array $requestParam): object
    {
        return $this->stripe->charges->create([$requestParam]);
    }
}
