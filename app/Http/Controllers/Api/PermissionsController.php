<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PermissionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionsController extends Controller
{
    public function index(Request $request)
    {

        $permissions = $request->user()->getAllPermissions();

        PermissionResource::wrap('data');
        return PermissionResource::collection($permissions);
    }
}
