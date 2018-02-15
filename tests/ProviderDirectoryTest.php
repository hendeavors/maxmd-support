<?php
namespace Endeavors\MaxMD\Support\Tests;

use Orchestra\Testbench\TestCase;
use Endeavors\MaxMD\Support\ProviderDirectoryRestClient;

class ProviderDirectoryTest extends TestCase
{
    const HISP_OPERATOR = 'MAXMD';

    // Test variables we will set. Check for the variables being set in tests before proceeding.
    private static $testVariablesSet = false;
    private static $npiFirstName = '';
    private static $npiLastName = '';
    private static $npiNumber = 0;
    private static $organizationNpi = 0;
    private static $organizationName = '';
    private static $zipRangeStart = '';
    private static $zipRangeEnd = '';
    private static $directAddress = '';
    private static $state = '';

    private $ProviderRestClient;

    public function setUp()
    {
        parent::setUp();
        $dotEnv = new \Dotenv\Dotenv(dirname(__DIR__));
        $dotEnv->load();

        ProviderDirectoryRestClient::login(env('MAXMD_APIUSERNAME'), env('MAXMD_APIPASSWORD'));

        if(!static::$testVariablesSet) {
            $this->setup_test_vars_and_test_get_list_by_hisp();
            static::$testVariablesSet = true;
        }


        $this->ProviderRestClient = ProviderDirectoryRestClient::getInstance();
    }

    public function test_class_is_built()
    {
        $this->assertEquals('https://evalapi.max.md:8445/Directory/rest/getDirectory/', $this->ProviderRestClient->url());
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function test_for_exception_when_not_logged_in()
    {
        ProviderDirectoryRestClient::logout();
        $results = $this->ProviderRestClient->byHisp(self::HISP_OPERATOR);
    }

    public function test_get_providers_list()
    {
        $this->markTestSkipped('Getting all the data is very slow.');
        $result = $this->ProviderRestClient->all();
    }

    public function test_get_list_by_first_and_last_name()
    {
        $result = $this->ProviderRestClient->byFirstNameLastName(static::$npiFirstName, static::$npiLastName);
        $this->assertTrue(is_array($result), 'Failed to get an array with npi name: ' . static::$npiFirstName .' ' . static::$npiLastName .'.');
        $this->assertHasAllColumns($result, 'Failed to get all columns with npi name: ' . static::$npiFirstName .' ' . static::$npiLastName .'.');
    }

    public function test_get_list_by_first_and_last_name_null_when_not_found()
    {
        $result = $this->ProviderRestClient->byFirstNameLastName('asdfds','xedfrtyu');
        $this->assertNull($result);
    }

    public function test_get_list_by_npi()
    {
        $result = $this->ProviderRestClient->byProviderNpi(static::$npiNumber);
        $this->assertTrue(is_array($result), 'Failed to get an array with npi number: ' . static::$npiNumber .'.');
        $this->assertHasAllColumns($result, 'Failed to get all columns with npi number: ' . static::$npiNumber .'.');
    }

    public function test_get_list_by_npi_null_when_not_found()
    {
        $result = $this->ProviderRestClient->byProviderNpi('notarealnpi');
        $this->assertNull($result);
    }

    public function test_get_list_by_organization_npi()
    {
        $results = $this->ProviderRestClient->byOrganizationNpi(static::$organizationNpi);
        $this->assertTrue(is_array($results), 'Failed to get an array with organization npi number: ' . static::$organizationNpi .'.');
        $this->assertArrayHasKey(0, $results, 'Failed to get array index 0 with organization npi number: ' . static::$organizationNpi .'.');
        $this->assertHasAllColumns($results[0], 'Failed to get all columns with organization npi number: ' . static::$organizationNpi .'.');
    }

    public function test_get_list_by_organization_name()
    {
        $results = $this->ProviderRestClient->byOrganizationName(static::$organizationName);
        $this->assertTrue(is_array($results), 'Failed to get an array with organization name: ' . static::$organizationName .'.');
        $this->assertArrayHasKey(0, $results, 'Failed to get array index 0 with organization name: ' . static::$organizationName .'.');
        $this->assertHasAllColumns($results[0], 'Failed to get all columns with organization name: ' . static::$organizationName .'.');
    }

    public function test_get_list_by_zip_code_range()
    {
        $results = $this->ProviderRestClient->byZipCodeRange(static::$zipRangeStart, static::$zipRangeEnd);
        $this->assertTrue(is_array($results), 'Failed to get an array with zip codes: ' . static::$zipRangeStart . 'and' . static::$zipRangeEnd . '.');
        $this->assertArrayHasKey(0, $results, 'Failed to get array index 0 with zip codes: ' . static::$zipRangeStart . 'and' . static::$zipRangeEnd . '.');
        $this->assertHasAllColumns($results[0], 'Failed to get all columns with zip codes: ' . static::$zipRangeStart . 'and' . static::$zipRangeEnd . '.');
    }

    public function test_get_list_by_custom()
    {
        $customArgs = [
          'hisp' => self::HISP_OPERATOR,
          'directAddress' => static::$directAddress,
          'state' => static::$state,
          'startDate' => date('Y-m-d', strtotime('-10 years')),
          'endDate' => date('Y-m-d'),
          'status' => 'ALL',
        ];
        $results = $this->ProviderRestClient->byCustom(
          $customArgs['hisp'],
          $customArgs['directAddress'],
          $customArgs['state'],
          $customArgs['startDate'],
          $customArgs['endDate'],
          $customArgs['status']);

        $this->assertTrue(is_array($results), 'Failed to get an array with custom arguments: ' . implode(', ', $customArgs));
        $this->assertArrayHasKey(0, $results, 'Failed to get array index 0 custom arguments: ' . implode(', ', $customArgs));
        foreach($results as $result) {
          $this->assertHasAllColumns($result, 'Failed to get all columns custom arguments: ' . implode(', ', $customArgs));
        }
    }

    private function setup_test_vars_and_test_get_list_by_hisp()
    {
        $results = ProviderDirectoryRestClient::getInstance()->byHisp(self::HISP_OPERATOR);
        $this->assertTrue(is_array($results));
        $this->assertArrayHasKey(0, $results);
        $this->assertHasAllColumns($results[0], 'Failed checking for columns with hisp ' . self::HISP_OPERATOR);
        $needsToBeConfigured = [
          'npiFirstName' => 'provider_first_name',
          'npiLastName' => 'provider_last_name',
          'npiNumber' => 'provider_npi',
          'organizationNpi' => 'organization_npi',
          'organizationName' => 'organization_name',
          'zipRangeStart' => 'organization_postal_code',
          'directAddress' => 'service_direct_address',
          'state' => 'organization_state',
        ];

        foreach($needsToBeConfigured as $staticVar => $resultKey) {
            foreach($results as $result) {
                if(!empty($result[$resultKey])) {
                    static::${$staticVar} = $result[$resultKey];
                    break;
                }
            }
        }

        static::$zipRangeEnd = substr(static::$zipRangeStart, 0, -1) . '9';
    }

    private function assertHasAllColumns($result, $error = null)
    {
        $this->assertTrue(is_array($result));
        foreach($this->getReturnColumns() as $column) {
            $this->assertArrayHasKey($column, $result, $error);
        }
    }

    private function getReturnColumns()
    {
        return [
                    'hisp_operator',
                    'service_direct_address',
                    'service_description',
                    'provider_uid',
                    'provider_npi',
                    'provider_first_name',
                    'provider_middle_name',
                    'provider_last_name',
                    'provider_suffix',
                    'provider_healthcare_service_class',
                    'provider_specialty',
                    'provider_role',
                    'organization_uid',
                    'organization_npi',
                    'organization_name',
                    'organization_address_line_1',
                    'organization_address_line_2',
                    'organization_city',
                    'organization_state',
                    'organization_postal_code',
                    'organization_phone',
                    'organization_healthcare_service_class',
                    'organization_specialty',
                ];
    }
}
