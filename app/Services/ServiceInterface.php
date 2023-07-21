<?php

namespace App\Services;

interface ServiceInterface
{
    public function index($data);

    public function store($data);

    public function update($data, $item);
}
