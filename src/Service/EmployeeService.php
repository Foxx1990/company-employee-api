<?php

namespace App\Service;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\EntityValidator;

class EmployeeService
{
    private EmployeeRepository $employeeRepository;
    private ValidatorInterface $validator;
    private EntityValidator $entityValidator;

    public function __construct(EmployeeRepository $employeeRepository, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->employeeRepository = $employeeRepository;
        $this->validator = $validator;
    }

    public function createEmployee(array $data): Employee
    {
        $employee = new Employee();
        $employee->setFirstName($data['firstName']);
        $employee->setLastName($data['lastName']);
        $employee->setEmail($data['email']);
        $employee->setPhoneNumber($data['phoneNumber'] ?? null);
        $employee->setCompany($data['$company']);

        $this->entityValidator->validate($employee);

        $this->employeeRepository->save($employee);

        return $employee;
    }

    public function getAllEmployees(): array
    {
        return $this->employeeRepository->findAll();
    }

    public function getEmployeeById(int $id): ?Employee
    {
        return $this->employeeRepository->find($id);
    }

    public function updateEmployee(int $id, array $data): Employee
    {
        $employee = $this->employeeRepository->find($id);
        if (!$employee) {
            throw new \Exception('Employee not found');
        }

        $employee->setFirstName($data['firstName']);
        $employee->setLastName($data['lastName']);
        $employee->setEmail($data['email']);
        $employee->setPhoneNumber($data['phoneNumber'] ?? null);

        $errors = $this->validator->validate($employee);
        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }

        $this->employeeRepository->save($employee);

        return $employee;
    }

    public function deleteEmployee(int $id): void
    {
        $employee = $this->employeeRepository->find($id);
        if (!$employee) {
            throw new \Exception('Employee not found');
        }

        $this->employeeRepository->delete($employee);
    }
}
