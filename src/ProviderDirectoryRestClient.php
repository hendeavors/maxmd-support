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
     * Get all Provider records
     * @return array provider records
     */
    public function all()
    {
        return $this->getArray('');
    }

    public function byHisp($hisp)
    {
        return $this->getArray(rawurlencode($hisp));
    }

    public function byFirstNameLastName($firstName, $lastName)
    {
        $results = $this->getArray('ByName/' . rawurlencode($firstName) . '/' . rawurlencode($lastName));
        return is_array($results[0]) ? $results[0] : $results;
    }

    public function byProviderNpi($npi)
    {
        $results = $this->getArray('ByProviderNPI/' . $npi);
        return is_array($results[0]) ? $results[0] : $results;
    }

    public function byOrganizationNpi($npi)
    {
        return $this->getArray('ByOrganizationNPI/' . $npi);
    }

    public function byOrganizationName($organizationName)
    {
        return $this->getArray('ByOrganizationName/' . rawurlencode($organizationName));
    }

    public function byZipCodeRange($startZipCode, $endZipCode)
    {
        return $this->getArray('ByZipcodeRange/' . $startZipCode . '/' . $endZipCode);
    }

    public function byCustom($hispOperator, $directAddress, $stateList, $startDate, $endDate, $status)
    {
        return $this->getArray(rawurlencode($hispOperator) . '/' . $directAddress . '/' . rawurlencode($stateList) . '/' . $startDate . '/' . $endDate . '/' . $status);
    }

    /**
     * Turn the response from the api into array from csv.
     * We will also add the username and password to all requests.
     * @return array - the result from the api as an array
     */
    protected function getArray($endpoint, $params = [], $headers = [])
    {
        $endpointWithUserAndPass = $this->username . '/' . $this->password;
        $endpoint = rtrim($endpoint, '\/');

        if (strlen($endpoint)) {
            $endpointWithUserAndPass = rtrim($endpoint, '\/') . '/' . $this->username . '/' . $this->password;
        }

        // Split into lines and parse into arrays
        $asArray = array_map('str_getcsv', explode("\n", trim(parent::Get($endpointWithUserAndPass, $params, $headers), "\n")));

        // Make columns headers on each row
        array_walk($asArray, function (&$a) use ($asArray) {
            if (count($asArray[0]) === count($a)) {
                $a = array_combine($asArray[0], $a);
            }
        });

        // Remove columns row
        array_shift($asArray);

        return $asArray;
    }
}
