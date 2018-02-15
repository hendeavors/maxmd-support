<?php
namespace Endeavors\MaxMD\Support;

use Endeavors\MaxMD\Support\Contracts\IRestClient;
use Endeavors\MaxMD\Support\RestClient;

/**
 * Make all rest calls to the provider directory endpoints provided by MaxMd
 * https://www.maxmdirect.com.eval.max.md/api/Directory/RESTful
 */
final class ProviderDirectoryRestClient extends RestClient implements IRestClient
{
    private static $instance = null;

    /**
     * The base url for all the api calls
     * @var string
     */
    private $url;

    /**
     * The username and password we will need to authenticate with the api
     * @var string
     */
    private static $username;
    private static $password;

    /**
     * Build the class given the authentication credentials and the base domain
     * @param string $url   The base url we will make our calls to. ie 'https://directapi.max.md:8445/Directory/rest/getDirectory/'
     */
    public function __construct($url)
    {
        $this->url = rtrim($url, '\/') . '/';
    }

    /**
     * [login description]
     * @param string $username The username we will use to authenticate to maxmd
     * @param string $password The password we will use to authenticate to maxmd
     */
    public static function login($username, $password)
    {
        static::$username = $username;
        static::$password = md5($password);
    }

    /**
     * Forget the current login credentials to disallow subsequent requests
     */
    public static function logout()
    {
        static::$username = null;
        static::$password = null;
    }

    /**
     * Get an instance of the current class
     * @return ProviderDirectoryRestClient
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new ProviderDirectoryRestClient("https://directapi.max.md:8445/Directory/rest/getDirectory/");
        }

        return static::$instance;
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

    /**
     * Get all records given a HISP
     * @param  string $hisp   The hisp to filter on
     * @return array          An array of the results from the request
     */
    public function byHisp($hisp)
    {
        return $this->getArray(rawurlencode($hisp));
    }

    /**
     * Get a record given a provider's first and last name
     * @param  string $firstName The provider's first name
     * @param  string $lastName  The provider's last name
     * @return array|null        A single record with the provider's information or null if we can't find them.
     */
    public function byFirstNameLastName($firstName, $lastName)
    {
        $results = $this->getArray('ByName/' . rawurlencode($firstName) . '/' . rawurlencode($lastName));
        return !empty($results[0]) && is_array($results[0]) ? $results[0] : null;
    }

    /**
     * Get a record given a provider's NPI number
     * @param  int $npi     The provider's NPI number
     * @return array|null        A single record with the provider's information or null if we can't find them.
     */
    public function byProviderNpi($npi)
    {
        $results = $this->getArray('ByProviderNPI/' . $npi);
        return !empty($results[0]) && is_array($results[0]) ? $results[0] : null;
    }

    /**
     *  Get all records given an organization npi number
     * @param  int  $npi    An organization's NPI numeber
     * @return array        An array of the results from the request
     */
    public function byOrganizationNpi($npi)
    {
        return $this->getArray('ByOrganizationNPI/' . $npi);
    }

    /**
     *  Get all records given an organization name
     * @param  string  $organizationName    An organization's name
     * @return array                        An array of the results from the request
     */
    public function byOrganizationName($organizationName)
    {
        return $this->getArray('ByOrganizationName/' . rawurlencode($organizationName));
    }

    /**
     * Get all records given a zip code range
     * @param  string $startZipCode the starting zip code. May be xxxxx or xxxxx-xxxx format
     * @param  string $endZipCode   the ending zip code. May be xxxxx or xxxxx-xxxx format
     * @return array                An array of the results from the request
     */
    public function byZipCodeRange($startZipCode, $endZipCode)
    {
        return $this->getArray('ByZipcodeRange/' . $startZipCode . '/' . $endZipCode);
    }

    /**
     * Get all records given a set of custom parameters. Defaults will do no filtering.
     * @param  string $hisp           The hisp to filter on
     * @param  string $directAddress  a direct address to filter on
     * @param  string $stateList      a comma separated list of states to filter on (full state names. ie ARIZONA, CALIFORNIA)
     * @param  string $startDate      Creation start date to filter on. String in Y-m-d format
     * @param  string $endDate        Creation end date to filter on. String in Y-m-d format
     * @param  string $status         The status of the provider. ALL|ACTIVE|DELETED
     * @return array                  An array of the results from the request
     */
    public function byCustom($hispOperator = "ALL", $directAddress = "ALL", $stateList = "ALL", $startDate = "undefined", $endDate = "undefined", $status = "ALL")
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
        if (empty(static::$username) || empty(static::$password)) {
            throw new \UnexpectedValueException('Unexpected values for username and password. Make sure to call ProviderDirectoryRestClient::login with your username and password beore making api calls.');
        }

        $endpointWithUserAndPass = static::$username . '/' . static::$password;
        $endpoint = rtrim($endpoint, '\/');

        if (strlen($endpoint)) {
            $endpointWithUserAndPass = rtrim($endpoint, '\/') . '/' . static::$username . '/' . static::$password;
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
