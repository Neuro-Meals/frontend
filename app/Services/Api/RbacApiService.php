<?php

namespace App\Services\Api;

class RbacApiService extends BaseApiService
{
    // ─── Roles ───

    public function listRoles(): array
    {
        return $this->get('rbac.roles');
    }

    public function createRole(array $data): array
    {
        return $this->post('rbac.roles', [], $data);
    }

    // ─── Permissions ───

    public function listPermissions(): array
    {
        return $this->get('rbac.permissions');
    }

    public function createPermission(array $data): array
    {
        return $this->post('rbac.permissions', [], $data);
    }

    // ─── Assignments ───

    public function assignRole(array $data): array
    {
        return $this->post('rbac.assign_role', [], $data);
    }

    public function assignPermission(array $data): array
    {
        return $this->post('rbac.assign_permission', [], $data);
    }
}
