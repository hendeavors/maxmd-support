<?php
namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\RestClient;

/**
 * Make all rest calls to the provider directory endpoints provided by MaxMd
 * https://www.maxmdirect.com.eval.max.md/api/Directory/RESTful
 */
class ProviderDirectoryRestClient extends RestClient
{
    /**
     * The base url for all the api calls
     * @var string
     */
    protected $url;

    /**
     * The stub after the domain to the api endpoints
     * @var string
     */
    private $stub = '/Directory/rest/getDirectory/';

    /**
     * The username and password we will need to authenticate with the api
     * @var string
     */
    private $username;
    private $password;

    /**
     * Build the class given the authentication credentials and the base domain
     * @param string $username The username we will use to authenticate to maxmd
     * @param string $password The password we will use to authenticate to maxmd
     * @param string $domain   The base domain we will make our calls to. Default is production.
     */
    public function __construct($username, $password, $domain = 'https://directapi.max.md:8445')
    {
        $this->url = rtrim($domain, '\/') . $this->stub;
        $this->username = $username;
        $this->password = md5($password);
    }

    /**
     * The full base url for all provider directory endpoints
     * @return string - the url
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Overriden to add username and password to the end of all urls.
     * @see \Endeavors\MaxMD\Support\RestClient::Get
     */
    public function Get($endpoint, $params = [], $headers = [])
    {
        $endpoint = rtrim($endpoint, '\/') . $this->username . '/' . $this->password;
        return parent::Get($endpoint, $params, $headers);
    }

    /**
     * Get all Provider records
     * @return array provider records
     */
    public function all()
    {
        return $this->Get('');
    }

    public function byHisp($hisp)
    {
    }

    public function byFirstNameLastName($firstName, $lastName)
    {
    }

    public function byProviderNpi($npi)
    {
    }

    public function byOrganizationNpi($npi)
    {
    }

    public function byOrganizationName($organizationName)
    {
    }

    public function byZipCodeRange($startZipCode, $endZipCode)
    {
    }

    public function byCustom($hispOperator, $directAddress, $stateList, $startdate, $enddate, $status)
    {
    }
}
