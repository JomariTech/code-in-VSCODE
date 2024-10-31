<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    // Method to check if email exists in the database
    public function checkEmail($email)
    {
        // Check if the email exists in the 'clients' table
        $exists = Client::where('email', $email)->exists();

        // Return the response
        return response()->json(['exists' => $exists]);
    }


    // Method to check if username exists in the database
    public function checkUsername($username)
    {
        // Check if the username exists in the 'clients' table
        $exists = Client::where('username', $username)->exists();

        // Return the response
        return response()->json(['exists' => $exists]);
    }


    // Method to handle user registration
    public function store(Request $request)
    {
        // Validate the request data for registration
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:clients',     // Username is required, must be unique in 'clients' table
            'email' => 'required|string|email|max:255|unique:clients',  // Email is required, must be valid and unique
            'password' => 'required|string|confirmed',                  // Password must be at least 8 characters and confirmed
        ]);

        // If validation fails, return error messages with a 400 (Bad Request) status
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Client record with the provided username, email, and hashed password
        $client = Client::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),   // Hash the password before storing it
        ]);

        // Return a success message and set 'navigate' to true to trigger navigation on the frontend
        return response()->json(['message' => 'Registered successfully', 'navigate' => true], 201);
    }


    // Method to retrieve all clients from the database
    public function index()
    {
        $clients = Client::all();           // Retrieve all records from the 'clients' table
        return response()->json($clients);  // Return the list of clients in JSON format
    }


    // Method to show a specific client by their ID
    public function show($id)
    {
        $client = Client::find($id);                                         // Find the client by their ID
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404); // Return 404 if client doesn't exist
        }
        return response()->json($client, 200);                               // Return the client details with a 200 status code
    }


     // Method to delete a client by their ID
     public function destroy($id)
     {
         $client = Client::find($id);                                           // Find the client by their ID
         if (!$client) {
             return response()->json(['message' => 'Client not found'], 404);   // Return 404 if client doesn't exist
         }
         $client->delete(); // Delete the client from the database
         return response()->json(['message' => 'Deleted successfully'], 200);   // Return a success message
     }


    // Method to update a client's information
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'username' => 'required|string|max:255', // Username is required
            'email' => 'required|email|max:255|unique:clients,email,' . $id, // Email must be unique, but allow the current client's email
        ]);

        // Find the client to update, or fail if not found
        $client = Client::findOrFail($id);
        $client->username = $validatedData['username'];     // Update username
        $client->email = $validatedData['email'];           // Update email
        $client->save();                                    // Save changes to the database
        return response()->json($client, 200);              // Return the updated client information
    }


    // Method for handling user login
public function login(Request $request)
{
    // Validate the login request
    $request->validate([
        'username' => 'required|string',      // Username or email is required
        'password' => 'required|string',      // Password is required
    ]);

    // Try to find the user by username or email
    $user = Client::where('username', $request->username)
                  ->orWhere('email', $request->username)    // Check if it's either a username or email
                  ->first();

    // If the user exists and the password is correct (using the Hash check)
    if ($user && Hash::check($request->password, $user->password)) {

        /*
        // Check if the user is an admin
        if ($user->role === 'admin') {
            return response()->json([
                'success' => true,
                'message' => 'Admin login successful',  // Admin login message
                'role' => 'admin',                      // Return role for admin
                'data' => $user,                        // Optionally return the user's data
            ]);
        }
        */

        // If the user is not an admin, consider them a regular user
        return response()->json([
            'success' => true,
            'message' => 'User login successful',  // User login message
            'role' => 'user',                      // Return role for user
            'data' => $user,                       // Optionally return the user's data
        ]);
    }

    // If the login attempt fails, return an unauthorized response
    return response()->json([
        'success' => false,
        'message' => 'Incorrect Username/Email or Password'     // Error message for incorrect login credentials
    ], 401); // 401 Unauthorized status
}
}