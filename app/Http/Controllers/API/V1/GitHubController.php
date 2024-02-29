<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubController extends Controller
{
    public function getToken(Request $request)
    {
        $code = $request->input('code');

        try {
            $response = Http::post('https://github.com/login/oauth/access_token', [
                'client_id'     => 'bd65222f1fafd514ef30',
                'client_secret' => '46229d2db00407777b9390d70f2764008940dad1',
                'code'          => $code,
                'redirect_uri'  => 'http://127.0.0.1:8000', // Must match the OAuth App settings
            ]);
            // Parse the URL-encoded string into an associative array
            parse_str($response->body(), $tokenData);

            // Convert the array to JSON
            // $jsonResponse = json_encode($response);

            $accessToken = $tokenData['access_token'];
            // You can now handle or store the access token on the server side

            try {
                $response = Http::withToken($accessToken)->get('https://api.github.com/user');
        
                $userData = $response->json();
                // You can now handle or display user details
        
                return response()->json(['success' => true, 'user' => $userData, 'accessToken' => $accessToken]);
            } catch (\Exception $e) {
                // \Log::error('Error fetching user details: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Error fetching user details'], 500);
            }
            // return response()->json(['success' => true, 'accessToken' => $tokenData]);
        } catch (\Exception $e) {
            Log::error('Error exchanging code for access token: ' . $e->getMessage());
            // return response()->json($e->getMessage());
            return response()->json(['success' => false, 'message' => 'Token exchange failed'], 500);
        }
    }
}
