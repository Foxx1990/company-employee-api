<?php

namespace App\Controller;

use App\Service\CompanyService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api/companies", name="company_")
 */
class CompanyController extends AbstractController
{
    private CompanyService $companyService;

    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    /**
     * @Route("api/companies", methods={"POST"}, name="create")
     */
    public function create(Request $request): JsonResponse
    {

        // Pobieranie danych z query parameters
        $data = [
            'name' => $request->query->get('name'),
            'nip' => $request->query->get('nip'),
            'address' => $request->query->get('address'),
            'city' => $request->query->get('city'),
            'postalCode' => $request->query->get('postalCode'),
        ];

        // Sprawdzenie, czy wszystkie wymagane parametry zostały podane
        if (in_array(null, $data, true)) {
            return $this->json(['error' => 'Missing required parameters'], 400);
        }
        try {
            $company = $this->companyService->createCompany($data);
            return $this->json($company, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("", methods={"GET"}, name="index")
     */
    public function index(): JsonResponse
    {
        $companies = $this->companyService->getAllCompanies();
        return $this->json($companies);
    }

    /**
     * @Route("/{id}", methods={"GET"}, name="show")
     */
    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->getCompanyById($id);
        if (!$company) {
            return $this->json(['message' => 'Company not found'], 404);
        }
        return $this->json($company);
    }

    /**
     * @Route("/{id}", methods={"PUT"}, name="update")
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = [
            'name' => $request->query->get('name'),
            'nip' => $request->query->get('nip'),
            'address' => $request->query->get('address'),
            'city' => $request->query->get('city'),
            'postalCode' => $request->query->get('postalCode'),
        ];

        // Sprawdzenie, czy wszystkie wymagane parametry zostały podane
        if (in_array(null, $data, true)) {
            return $this->json(['error' => 'Missing required parameters'], 400);
        }

        try {
            $company = $this->companyService->updateCompany($id, $data);
            return $this->json($company);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/{id}", methods={"DELETE"}, name="delete")
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->companyService->deleteCompany($id);
            return $this->json(['message' => 'Company deleted']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
