<?php

namespace App\Http\Controllers;

use App\Http\Queries\RolesQuery;
use App\Http\Requests\Roles\AllRoleRequest;
use App\Http\Requests\Roles\CreateRequest;
use App\Http\Requests\Roles\SysnPermissionRequest;
use App\Http\Requests\Roles\SysnRoleRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Models\Permission;
use App\Notifications\RoleChange;
use App\Services\ApiCodeService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    /**
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request) : ResourceCollection
    {
        $perPage = $request->filled('limit') ? (int) $request->input('limit') : 15;
        $roles = (new RolesQuery())->orderByDesc('id')->paginate($perPage);
        return new ResourceCollection($roles);
    }

    /**
     * @param CreateRequest $request
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     */
    public function store(CreateRequest $request) : Response
    {
        $validator = $request->validated();
        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData(Role::create($validator))
            ->withMessage(__('message.common.create.success'))
            ->build();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param UpdateRequest $request
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     *
     */
    public function update(UpdateRequest $request) : Response
    {
        $validator = $request->validated();
        $id = $validator['id'];
        unset($validator['id']);
        $role = Role::query()->whereIn('id',$id)->first();
        $result = $role->update($validator);
        if($result) {
            return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
                ->withHttpCode(ApiCodeService::HTTP_OK)
                ->withData($role)
                ->withMessage(__('message.common.update.success'))
                ->build();
        }
        return ResponseBuilder::asError(ApiCodeService::HTTP_BAD_REQUEST)
            ->withHttpCode(ApiCodeService::HTTP_BAD_REQUEST)
            ->withData($role)
            ->withMessage(__('message.common.update.fail'))
            ->build();
    }

    /**
     * @param int $id
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     */
    public function destroy(int $id) : Response
    {
       $role =  Role::query()->findOrFail($id);
       $role->delete();
       return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
           ->withHttpCode(ApiCodeService::HTTP_OK)
           ->withMessage(__('message.common.delete.success'))
           ->build();
    }

    /**
     * @param SysnPermissionRequest $request
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     */
    public function syncPermissions(SysnPermissionRequest $request) : Response
    {
        $validated = $request->validated();
        $role = Role::query()->whereIn('id',$validated['id'])->first();
        $permissions = isset($validated['permissions']) ? Permission::query()->whereIn('id',$validated['permissions'])->get() : [];
        $role->syncPermissions($permissions);

        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData($role)
            ->withMessage(__('message.common.update.success'))
            ->build();

    }

    /**
     * @param SysnRoleRequest $request
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     */
    public function syncRoles(SysnRoleRequest $request) : Response
    {
        $validated = $request->validated();
        $guard = $request->guard();
        $roles = isset($validated['roles']) ?
            Role::query()->whereIn('id', $validated['roles'])->get() :
            [];
        $guard->syncRoles($roles);
        activity()
            ->useLog('role')
            ->performedOn(new Role())
            ->causedBy($request->user())
            ->withProperties($validated)
            ->log('update roles');
        $guard->notify(new RoleChange($roles));
        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData($guard)
            ->withMessage(__('message.common.update.success'))
            ->build();
    }

    public function getAllRoles(array $validated) : array
    {
        $where[] = ['guard_name', '=', $validated['guard_name']];

        return Role::query()->where($where)->select(['id','name'])->get()->toArray();
    }

    /**
     * @param AllRoleRequest $request
     * @return Response
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException
     * @throws \MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException
     */
    public function allRoles(AllRoleRequest $request) : Response
    {
        $validated = $request->validated();
        return ResponseBuilder::asSuccess(ApiCodeService::HTTP_OK)
            ->withHttpCode(ApiCodeService::HTTP_OK)
            ->withData([
                'roles' => $this->getAllRoles($validated)
            ])
            ->withMessage(__('message.common.search.success'))
            ->build();
    }
}
