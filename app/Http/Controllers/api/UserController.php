<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;

class UserController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);

        return $user;
    }

    public function getMyId()
    {
        $userId = Auth::id();

        return $userId;
    }
    public function update(Request $request)
    {
        $user = Auth::user();

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'date_of_birth' => 'required|date',
                'phone' => 'nullable|string|max:20',
                'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bio' => 'nullable|string',
                'social_media_links' => 'nullable|json',
            ]);

            // Handle file upload
            if ($request->hasFile('profile_picture')) {
                // Delete the old profile picture if it exists
                if ($user->profile_picture && \Storage::exists('public/' . $user->profile_picture)) {
                    \Storage::delete('public/' . $user->profile_picture);
                }

                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $user->profile_picture = $path;
            } else if ($request->input('profile_picture') === null) {
                // Delete the old profile picture if it exists
                if ($user->profile_picture && \Storage::exists('public/' . $user->profile_picture)) {
                    \Storage::delete('public/' . $user->profile_picture);
                }
                $user->profile_picture = null;
            }

            // Update user data conditionally
            $user->name = $validatedData['name'] ?? $user->name;
            $user->email = $validatedData['email'] ?? $user->email;
            $user->date_of_birth = $validatedData['date_of_birth'];
            $user->phone = $validatedData['phone'] ?? null;
            $user->role = $validatedData['role'] ?? $user->role;
            $user->status = $validatedData['status'] ?? $user->status;
            $user->bio = $validatedData['bio'] ?? null;
            $user->social_media_links = isset($validatedData['social_media_links']) ? json_decode($validatedData['social_media_links'], true) : null;

            $user->save();

            return response()->json($user);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
