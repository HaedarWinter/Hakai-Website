<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        return view('settings.index', [
            'title' => 'Pengaturan Akun',
            'user' => auth()->user()
        ]);
    }

    /**
     * Update user profile (web form submission).
     * Delegates to API controller for business logic.
     */
    public function update(Request $request)
    {
        $apiController = app(\App\Http\Controllers\Api\SettingsApiController::class);
        $response = $apiController->updateProfile($request);

        $data = $response->getData(true);

        if ($data['success']) {
            auth()->user()->refresh();
            return back()->with('success', $data['message']);
        }

        return back()->withErrors($data['errors'] ?? [])->withInput();
    }

    /**
     * Update user password (web form submission).
     * Delegates to API controller for business logic.
     */
    public function updatePassword(Request $request)
    {
        $apiController = app(\App\Http\Controllers\Api\SettingsApiController::class);
        $response = $apiController->updatePassword($request);

        $data = $response->getData(true);

        if ($data['success']) {
            return back()->with('success', $data['message']);
        }

        return back()->withErrors($data['errors'] ?? [])->withInput();
    }
}

