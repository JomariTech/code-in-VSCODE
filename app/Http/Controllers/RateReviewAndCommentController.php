<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RateReviewAndComment; 
use App\Models\Smartphone;
use Illuminate\Support\Facades\DB;

class RateReviewAndCommentController extends Controller
{

    // Store a new review and update smartphone rating information
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string|max:255',        // Username is required and should be max 255 characters
            'rating' => 'required|integer|min:1|max:5',     // Rating is required, between 1 and 5
            'comment' => 'nullable|string|max:1000',        // Comment is optional, max 1000 characters
            'smartphone_id' => 'required|integer',          // The related smartphone ID is required
        ]);

        // Create a new review record
        $review = RateReviewAndComment::create([
            'username' => $request->username,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'smartphone_id' => $request->smartphone_id,     
        ]);

        // Retrieve all reviews for this specific smartphone
        $reviews = RateReviewAndComment::where('smartphone_id', $request->smartphone_id)->get();

        // Calculate the number of ratings and the percentage of ratings
        $number_of_ratings = $reviews->count();
        $sumRatings = $reviews->sum('rating');
        $percentageOfRatings = $number_of_ratings > 0 ? number_format(($sumRatings / $number_of_ratings), 2, '.', '') : 0.00;

        // Update the smartphone's number of reviews and percentage of ratings
        Smartphone::where('id', $request->smartphone_id)->update([
            'number_of_reviews' => $number_of_ratings,
            'percentage_of_ratings' => $percentageOfRatings,
        ]);

        // Return a success response with the new review data
        return response()->json([
            'success' => true,
            'message' => 'Review submitted and smartphone ratings updated successfully!',
            'data' => $review,
        ], 201);
    }


    // Retrieve and display all reviews
    public function index()
    {
        $reviews = RateReviewAndComment::all();    // Retrieve all reviews
        return response()->json($reviews);         // Return the list of reviews in JSON format
    }


    // Display a specific review by its ID
    public function show($id)
    {
        $reviews = RateReviewAndComment::find($id);                           // Find the review by its ID
        if (!$reviews) {
            return response()->json(['message' => 'Client not found'], 404);  // Return 404 if client doesn't exist
        }
        return response()->json($reviews, 200);                               // Return the client details with a 200 status code
    }


    // Delete a review by its ID and update smartphone rating information
    public function destroy($id)
    {
    
    $review = RateReviewAndComment::find($id);                           // Find the review by its ID
    if (!$review) {
        return response()->json(['message' => 'Review not found'], 404); // Return 404 if review doesn't exist
    }

     // Get the smartphone ID associated with the review
    $smartphoneId = $review->smartphone_id;

     // Delete the review from the database
    $review->delete(); 

    // Recalculate the number of ratings and average rating after deletion
    $reviews = RateReviewAndComment::where('smartphone_id', $smartphoneId)->get();
    $number_of_ratings = $reviews->count();
    $sumRatings = $reviews->sum('rating');
    $percentageOfRatings = $number_of_ratings > 0 ? number_format(($sumRatings / $number_of_ratings), 2, '.', '') : 0.00;

    // Update the smartphone's number of reviews and percentage of ratings
    Smartphone::where('id', $smartphoneId)->update([
        'number_of_reviews' => $number_of_ratings,
        'percentage_of_ratings' => $percentageOfRatings,
    ]);

    // Return a success message indicating review deletion and rating update
    return response()->json(['message' => 'Review deleted successfully and smartphone ratings updated'], 200);
    }


    // Update an existing review and update smartphone rating information
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
         $request->validate([
            'username' => 'required|string|max:255',        // Username is required and max 255 characters
            'rating' => 'required|integer|min:1|max:5',     // Rating is required, between 1 and 5
            'comment' => 'nullable|string|max:1000',        // Comment is optional, max 1000 characters
            'smartphone_id' => 'required|integer',          // Related smartphone ID is required
        ]);

        // Find the review by its ID
        $review = RateReviewAndComment::find($id);
            if (!$review) {
            return response()->json(['message' => 'Review not found'], 404); // Return 404 if review doesn't exist
    }

        // Update the review record with new data
        $review->update([
            'username' => $request->username,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'smartphone_id' => $request->smartphone_id,
    ]);

        // Get the smartphone ID associated with the updated review
        $smartphoneId = $review->smartphone_id;

        // Retrieve all reviews for this smartphone and recalculate ratings
        $reviews = RateReviewAndComment::where('smartphone_id', $smartphoneId)->get();
        $number_of_ratings = $reviews->count();
        $sumRatings = $reviews->sum('rating');
        $percentageOfRatings = $number_of_ratings > 0 ? number_format(($sumRatings / $number_of_ratings), 2, '.', '') : 0.00;

        // Update the smartphone's number of reviews and percentage of ratings
        Smartphone::where('id', $smartphoneId)->update([
            'number_of_reviews' => $number_of_ratings,
            'percentage_of_ratings' => $percentageOfRatings,
    ]);

        // Return a success message indicating review update and rating recalculation
        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully and smartphone ratings updated!',
            'data' => $review,
        ], 200);
    }
    public function getIphone16ProMaxBySmartphoneId($smartphone_id)
{
    // Fetch reviews where the smartphone_id matches the provided value
    $reviews = DB::table('rate_review_and_comments')
                 ->where('smartphone_id', $smartphone_id)
                 ->get();

    return response()->json($reviews);
}
public function getSamsungGalaxyS24BySmartphoneId($smartphone_id)
{
    // Fetch reviews where the smartphone_id matches the provided value
    $reviews = DB::table('rate_review_and_comments')
                 ->where('smartphone_id', $smartphone_id)
                 ->get();

    return response()->json($reviews);
}
public function getReviewsBySmartphoneId($smartphone_id)
{
    $reviews = RateReviewAndComment::where('smartphone_id', $smartphone_id)->get();
    return response()->json($reviews);
}
}
