<?php

trait StripeConnectFunctions
{
	/**
     * create
     *
     * @return object
     */
    private function create(array $data): object
    {
        return \Stripe\Account::create($data);
    }

    /**
     * retrieve
     *
     * @return object
     */
    private function retrieve(): object
    {
        return \Stripe\Account::retrieve($this->getAccountId());
    }

    /**
     * update
     *
     * @return object
     */
    private function update(array $data): object
    {
        return \Stripe\Account::update($this->getAccountId(), $data);
    }

    /**
     * createExternalAccount - For Financial(Bank) Data
     *
     * @return object
     */
    private function createExternalAccount(array $data): object
    {
        return \Stripe\Account::createExternalAccount($this->getAccountId(), $data);
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
     * doRequest
     *
     * @param  mixed $requestType
     * @return mixed
     */
    private function doRequest(int $requestType, array $data = [])
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
                case self::REQUEST_PERSON_ID:
                    return $this->getPersonId();
                    break;
                case self::REQUEST_ADD_BANK_ACCOUNT:
                    return $this->addFinancialInfo($data);
                    break;
                case self::REQUEST_UPDATE_BUSINESS_TYPE:
                    return $this->updateBusinessType($data);
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
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $this->error = $e->getMessage();
        }
        return false;
    }
}