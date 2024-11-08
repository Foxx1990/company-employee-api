<?php

namespace App\Controller;

use App\Service\EmployeeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/employees", name="employee_")
 */
class EmployeeController extends AbstractController
{
    private EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $employee = $this->employeeService->createEmployee($data);
            return $this->json($employee, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->getAllEmployees();
        return $this->json($employees);
    }

    /**
     * @Route("/{id}", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        $employee = $this->employeeService->getEmployeeById($id);
        if (!$employee) {
            return $this->json(['message' => 'Employee not found'], 404);
        }
        return $this->json($employee);
    }

    /**
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $employee = $this->employeeService->updateEmployee($id, $data);
            return $this->json($employee);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->employeeService->deleteEmployee($id);
            return $this->json(['message' => 'Employee deleted']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
