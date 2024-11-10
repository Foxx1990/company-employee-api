<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class CompanyEmployeeApiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        // Tworzymy nową instancję klienta dla testów
        $this->client = static::createClient();
    }

    public function testCreateCompany()
    {
        // Tworzenie nowej firmy
        $this->client->request('POST', '/api/companies', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Example Company',
            'nip' => '1234567890',
            'address' => 'Main St. 123',
            'city' => 'Example City',
            'postalCode' => '12-345',
        ]));

        $this->assertResponseStatusCodeSame(201, 'Company creation failed');
        
        // Weryfikacja odpowiedzi
        $company = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $company, 'Response does not contain company ID');
        $this->assertEquals('Example Company', $company['name']);
        
        return $company['id']; // Przekazanie ID firmy do kolejnych testów
    }

    /**
     * @depends testCreateCompany
     */
    public function testCreateEmployee($companyId)
    {
        // Tworzenie pracownika przypisanego do utworzonej firmy
        $this->client->request('POST', '/api/employees', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'phoneNumber' => '123-456-789',
            'company' => '/api/companies/' . $companyId,
        ]));

        $this->assertResponseStatusCodeSame(201, 'Employee creation failed');

        // Weryfikacja odpowiedzi
        $employee = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('id', $employee, 'Response does not contain employee ID');
        $this->assertEquals('John', $employee['firstName']);
        $this->assertEquals($companyId, $employee['company']['id'], 'Employee not associated with correct company');

        return $companyId; // Przekazanie ID firmy do kolejnego testu
    }

    /**
     * @depends testCreateCompany
     */
    public function testGetEmployeesByCompany($companyId)
    {
        // Pobieranie pracowników przypisanych do firmy
        $this->client->request('GET', '/api/companies/' . $companyId . '/employees');

        $this->assertResponseStatusCodeSame(200, 'Failed to fetch employees for company');

        // Weryfikacja odpowiedzi
        $employees = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($employees, 'Response is not an array');
        $this->assertNotEmpty($employees, 'No employees found for company');
        
        $firstEmployee = $employees[0];
        $this->assertEquals('John', $firstEmployee['firstName'], 'Employee name does not match');
        $this->assertEquals($companyId, $firstEmployee['company']['id'], 'Employee not associated with correct company');
    }
}