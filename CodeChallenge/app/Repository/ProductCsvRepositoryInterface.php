<?php

namespace App\Repository;

interface ProductCsvRepositoryInterface
{
    public function validatePathExtension($file);

    public function validateCsvIssues($csv);
}