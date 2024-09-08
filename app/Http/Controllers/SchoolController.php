<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Http\Requests\UpdateSchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = User::with('package')->get();
        return response()->success(SchoolResource::collection($schools));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolRequest $request)
    {
        $data = $request->validated();

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Get the selected package
        $package = Package::findOrFail($data['package_id']);

        // Create the school in the central database
        $school = User::create([
            'name' => $data['name'],
            'address' => $data['address'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'logo' => $logoPath,
            'package_id' => $package->id,
        ]);

        return response()->success(new SchoolResource($school));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->success(new SchoolResource($user->load('package')));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            // Handle logo upload and deletion of old logo
            if ($user->logo) {
                Storage::disk('public')->delete($user->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $user->update($data);

        return response()->success(new SchoolResource($user));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Delete logo if exists
        if ($user->logo) {
            Storage::disk('public')->delete($user->logo);
        }

        $user->delete();

        return response()->deleted();
    }
}
