<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Smartphone;              // Model for storing smartphone data
use App\Models\RateReviewAndComment;    // Model for storing reviews and ratings
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SmartphoneController extends Controller
{

    // Method to retrieve all smartphones
    public function index()
    {
        // Retrieve all records from the 'smartphones' table
        $smartphones = Smartphone::all();

        // Handle case when no smartphones are found
        if ($smartphones->isEmpty()) {
        return response()->json(['message' => 'No smartphones found'], 404);
    }   
        // Return the retrieved smartphones with a 200 status code
        return response()->json($smartphones, 200); 
    }


    // Method to create and store a new smartphone
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',
            'brands' => 'required|string|max:255',
            'model' => 'required|string|max:255|unique:smartphones',
            'overview' => 'required|string|max:255',
            'processor' => 'required|string|max:255',
            'memory' => 'required|string|max:255',
            'display' => 'required|string|max:255',
            'battery' => 'required|string|max:255',
            'camera' => 'required|string|max:1000',
            'price' => 'required|string|max:500',
            'number_of_reviews' => 'integer|max:255',
            'percentage_of_ratings' => 'numeric|max:255',
            
        ]);

        // Create the smartphone record
        $smartphone = Smartphone::create($validatedData);

        // Return a JSON response with the created smartphone and 201 status
        return response()->json(['message' => 'Smartphone created successfully', 'smartphone' => $smartphone], 201);
    }


    // Method to retrieve a specific smartphone by its ID
    public function show($id)
    {
        // Find the smartphone by their ID
        $smartphone = Smartphone::find($id);                                     

        // Handle case when the smartphone is not found
        if (!$smartphone) {
            return response()->json(['message' => 'Smartphone not found'], 404); 
        }
        // Return the smartphone details with a 200 status code
        return response()->json($smartphone, 200);                               
    }


     // Method to delete a smartphone by its ID
     public function destroy($id)
     {
        // Find the smartphone by its ID
         $smartphone = Smartphone::find($id);   
         
          // Handle case when the smartphone is not found
         if (!$smartphone) {
             return response()->json(['message' => 'Smartphone not found'], 404);   
         }

         // Delete the smartphone from the database
         $smartphone->delete(); // Delete the smartphone from the database

         // Return a success message
         return response()->json(['message' => 'Deleted successfully'], 200);   
     }


    // Method to update a smartphone's information
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'type' => 'required|string|max:255',        
            'brands' => 'required|string|max:255',
            'model' => 'required|max:255|',
            'overview' => 'required|string|max:255',
            'processor' => 'required|string|max:255',
            'memory' => 'required|string|max:255',      
            'display' => 'required|string|max:255',
            'battery' => 'required|string|max:255',
            'camera' => 'required|string|max:1000',
            'price' => 'required|string|max:255',
            'number_of_reviews' => 'integer|max:255',
            'percentage_of_ratings' => 'numeric|max:255',
        ]);

        // Find the smartphone to update or fail if not found
        $smartphone = Smartphone::findOrFail($id);

        // Update smartphone details with validated data
        $smartphone->type = $validatedData['type'];             // Update type
        $smartphone->brands = $validatedData['brands'];         // Update brands
        $smartphone->model = $validatedData['model'];           // Update model
        $smartphone->overview = $validatedData['overview'];     // Update overview
        $smartphone->processor = $validatedData['processor'];   // Update processor
        $smartphone->memory = $validatedData['memory'];         // Update memory
        $smartphone->display = $validatedData['display'];       // Update display
        $smartphone->battery = $validatedData['battery'];       // Update battery
        $smartphone->camera = $validatedData['camera'];         // Update camera
        $smartphone->price = $validatedData['price'];           // Update price
        $smartphone->number_of_reviews = $validatedData['number_of_reviews'];           // Update number of reviews
        $smartphone->percentage_of_ratings = $validatedData['percentage_of_ratings'];   // Update percetage of ratings
        
        // Save changes to the database
        $smartphone->save();           
        
        // Return the updated smartphone information
        return response()->json($smartphone, 200);             
    }

    public function getRatings($id)
    {
    $smartphone = Smartphone::select('number_of_reviews', 'percentage_of_ratings')
                            ->where('id', $id)
                            ->first();
    return response()->json($smartphone);
    }
public function getReviewsBySmartphoneId($smartphone_id)
    {
    // Fetch reviews where the smartphone_id matches the provided value
    $reviews = DB::table('rate_review_and_comments')
                 ->where('smartphone_id', $smartphone_id)
                 ->get();

    return response()->json($reviews);
    }
}