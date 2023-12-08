<?php

namespace App\Repositories\Organization;

interface OrganizationInterface
{
    public function getDataOrganization();
    public function getDataOrganizationIdSuccess();
    public function storeOrganization($request =  []);
    public function getDataOrganizationFind($id);
    public function deleteOrganization($id);
    public function updateOrganization($request = [], $id);
}
