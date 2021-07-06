<?php


namespace App\Utils;


use App\Models\SysAdmin;
use Illuminate\Support\Collection;

class Routes
{

    private $admin;

    /**
     * Routes constructor.
     * @param SysAdmin $admin
     */
    public function __construct(SysAdmin $admin) {
        $this->setAdmin($admin);
    }

    /**
     * @return Collection
     */
    public function routes() : Collection
    {
        if($this->getAdmin()->status === 1) {
            $permissions = $this->permissionCollect();
            $permissions = $this->sortByDesc($permissions);
            $permissions = $this->formatRoutes($permissions);
            $permissions = $this->formatRoutesChildren($permissions);
        }else {
            $permissions = collect();
        }
        return $permissions->merge([[
            'path' => '*',
            'redirect' => '/404',
            'hidden' => true
        ]]);
    }

    /**
     * @return Collection
     */
    private function permissionCollect(): Collection
    {
        $permissions = $this->getAdmin()->getAllPermissions()
            ->where('guard_name', '=', 'admin')
            ->toArray();
        $collect = [];
        $pivots = [];
        foreach ($permissions as $permission) {
            $pivots[$permission['id']][] = $permission['pivot'];
            $permission['pivots'] = $pivots[$permission['id']];
            $collect[$permission['id']] = $permission;
        }
        return collect($collect);
    }

    /**
     * @param Collection $permissions
     * @return Collection
     */
    private function sortByDesc(Collection $permissions): Collection
    {
        $permissions = Arr::arraySort($permissions->toArray(), 'sort');
        return collect($permissions);
    }

    /**
     * @param Collection $permissions
     * @return Collection
     */
    private function formatRoutes(Collection $permissions) : Collection
    {
        return $permissions->map(function ($value) {
            $info = [];
            $info['id'] = $value['id'];
            $info['pid'] = $value['pid'];
            $info['path'] = $value['path'];
            $info['component'] = $value['component'];
            $info['name'] = $value['name'];
            $roles = [];
            if(isset($value['pivots'])) {
                foreach ($value['pivots'] as $pivot) {
                    if(isset($pivot['role_id'])) {
                        $roles[] = $pivot['role_id'];
                    }else {
                        $roles[] = $pivot['model_type'] . '\\' . $pivot['model_id'];
                    }
                }
            }
            $info['meta'] = [
              'title' => $value['name'],
              'icon' => $value['icon'],
              'roles' => $roles,
              'noCache' => true,
              'breadcrumb' => true,
              'affix' => false,
            ];

            $info['hidden'] = $value['hidden'] ? true : false;

            if($value['component'] === 'layout/Layout' || $value['component'] === 'rview') {
                $info['redirect'] = 'noRedirect';
            }
            return $info;
        });

    }

    /**
     * @param Collection $permissions
     * @return Collection
     */
    private function formatRoutesChildren(Collection $permissions): Collection
    {
        $permissions = Arr::getTree($permissions->toArray());

        foreach ($permissions as $key => $value) {
            if ($value['pid'] === 0 && $value['component'] !== 'layout/Layout' && $value['hidden'] === false) {
                $component = $value['component'];
                $permissions[$key]['component'] = 'layout/Layout';
                $permissions[$key]['redirect'] = 'noRedirect';
                $permissions[$key]['meta']['breadcrumb'] = false;
                $permissions[$key]['children'][] = [
                    'path' => 'index',
                    'component' => $component,
                    'name' => $value['name'],
                    'hidden' => $value['hidden'],
                    'meta' => [
                        'title' => $value['meta']['title'],
                        'icon' => $value['meta']['icon'],
                        'roles' => $value['meta']['roles'],
                        'noCache' => true,
                        'breadcrumb' => true,
                        'affix' => false,
                    ]
                ];
                unset($permissions[$key]['name']);
            }
        }

        return collect($permissions);
    }

    /**
     * @param SysAdmin $admin
     * @return void
     */
    private function setAdmin(SysAdmin $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return SysAdmin
     */
    public function getAdmin() : SysAdmin
    {
        return $this->admin;
    }
}
