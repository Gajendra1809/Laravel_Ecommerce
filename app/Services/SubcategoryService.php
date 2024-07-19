<?php

namespace App\Services;

use App\Repositories\SubcategoryRepository;

class SubcategoryService {

    public $subcategoryRepository;

    public function __construct(SubcategoryRepository $subcategoryRepository){
        $this->subcategoryRepository = $subcategoryRepository;
    }

    public function getSubCategories($category_id){

        return $this->subcategoryRepository->getSubCategories($category_id);
        
    }

}
