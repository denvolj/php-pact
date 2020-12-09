<?php

namespace Pact\Service;

use Pact\Http\Methods;

class CompanyService extends AbstractService
{
    protected static $endpoint = '/companies';

    /**
     * This method return list of all user companies
     * @link https://pact-im.github.io/api-doc/#companies
     * 
     * @param string $from Next page token geted from last request. 
     *               Not valid or empty token return first page
     * @param int $per Number of elements per page. Default: 50
     * @param string $sort Change sorting direction. Available values: asc, desc. Default: asc.
     * @return Json|null
     */
    public function getCompanies(string $from = null, int $per = null, string $sort = null)
    {
        $this->validator->between($per, 1, 100, 'Number of fetching elements must be between 1 and 100.');
        $this->validator->sort($sort);

        $query = ['from' => $from, 'per' => $per, 'sort_direction' => $sort];

        return $this->request(
            Methods::GET, 
            $this->getRouteTemplate(),
            [],
            null,
            $query
        );
    }

    /**
     * This method updates specific company attributes
     * @link https://pact-im.github.io/api-doc/#get-all-companies
     * 
     * @param int $companyId Id of the company for update
     * @param string $name Company name
     * @param string $phone Official company phone number of contact person
     * @param string $description Company description
     * @param string $webhook_url Endpoint for webhooks
     * @return Json|null
     */
    public function updateCompany(
        int $companyId, 
        string $name = null, 
        string $phone = null, 
        string $description = null, 
        string $webHookUrl = null
    ) {
        $this->validator->_(strlen($name) === 0, 'Name must be non-empty string');
        $this->validator->_(strlen($name) > 255, 'Name length must be less than 256 symbols');

        $body = [
            'name' => $name,
            'phone' => $phone,
            'description' => $description,
            'webhook_url' => $webHookUrl
        ];

        return $this->request(
            Methods::PUT, 
            $this->getRouteTemplate() . '/%s', 
            [$companyId],
            $body
        );
    }

    /**
     * This method creates a new company for user
     * @link https://pact-im.github.io/api-doc/#update-company
     * 
     * @param string $name Company name
     * @param string $phone Official company phone number of contact person
     * @param string $description Company description
     * @param string $webhook_url Endpoint for webhooks
     * @return Json|null
     */
    public function createCompany(
        string $name, 
        string $phone = null, 
        string $description = null, 
        string $webHookUrl = null
    ) {
        $this->validator->_(strlen($name) === 0, 'Name must be non-empty string');
        $this->validator->_(strlen($name) > 255, 'Name length must be less than 256 symbols');

        $body = [
            'name' => $name,
            'phone' => $phone,
            'description' => $description,
            'webhook_url' => $webHookUrl
        ];

        return $this->request(
            Methods::POST, 
            $this->getRouteTemplate(),
            [],
            $body
        );
    }
}
