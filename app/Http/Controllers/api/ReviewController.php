<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function submitReview(Request $request)
    {
        $request->validate([
            'reviewed_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = UserReview::create([
            'reviewer_id' => Auth::id(),
            'reviewed_id' => $request->reviewed_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }

    public function getUserReviews()
    {
        $user = Auth::user();
        $reviews = $user->receivedReviews()->with('reviewer')->get();

        return response()->json($reviews);
    }
    public function getReviewData()
    {
        $user = Auth::user();

        $reviewCount = $user->receivedReviews()->with('reviewer')->count();
        $rating = UserReview::where('reviewed_id', Auth::id())->avg('rating');

        $rating = round($rating);

        return response()->json([
            'reviewCount' => $reviewCount,
            'rating' => $rating,
        ]);
    }
}
