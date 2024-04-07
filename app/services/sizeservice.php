<?php
namespace Services;

use Repositories\SizeRepository;

class SizeService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new SizeRepository();
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }
}
