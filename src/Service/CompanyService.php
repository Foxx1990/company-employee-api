<?php

namespace App\Service;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyService
{
    private CompanyRepository $companyRepository;
    private EntityManagerInterface $em;
    private ValidatorInterface $validator;

    public function __construct(CompanyRepository $companyRepository, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->companyRepository = $companyRepository;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function createCompany(array $data): Company
    {
        $company = new Company();
        $company->setName($data['name']);
        $company->setNip($data['nip']);
        $company->setAddress($data['address']);
        $company->setCity($data['city']);
        $company->setPostalCode($data['postalCode']);

        $errors = $this->validator->validate($company);
        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }

        $this->em->persist($company);
        $this->em->flush();

        return $company;
    }

    public function getAllCompanies(): array
    {
        return $this->companyRepository->findAll();
    }

    public function getCompanyById(int $id): ?Company
    {
        return $this->companyRepository->find($id);
    }

    public function updateCompany(int $id, array $data): Company
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            throw new \Exception('Company not found');
        }

        $company->setName($data['name']);
        $company->setNip($data['nip']);
        $company->setAddress($data['address']);
        $company->setCity($data['city']);
        $company->setPostalCode($data['postalCode']);

        $errors = $this->validator->validate($company);
        if (count($errors) > 0) {
            throw new \Exception((string) $errors);
        }

        $this->em->flush();

        return $company;
    }

    public function deleteCompany(int $id): void
    {
        $company = $this->companyRepository->find($id);
        if (!$company) {
            throw new \Exception('Company not found');
        }

        $this->em->remove($company);
        $this->em->flush();
    }
}
