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
     * @Route("", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $company = $this->companyService->createCompany($data);
            return $this->json($company, 201);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $companies = $this->companyService->getAllCompanies();
        return $this->json($companies);
    }

    /**
     * @Route("/{id}", methods={"GET"})
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
     * @Route("/{id}", methods={"PUT"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $company = $this->companyService->updateCompany($id, $data);
            return $this->json($company);
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
            $this->companyService->deleteCompany($id);
            return $this->json(['message' => 'Company deleted']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
