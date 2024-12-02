<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /**
     * Login to API
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $response = Http::post(env('API_BASE_URL') . '/merchant/user/login', $credentials);

        if ($response->successful()) {
            session(['token' => $response->json()['token']]);
            return redirect()->route('dashboard')->with('success', 'Successfully logged in!');
        }

        return back()->withErrors(['error' => 'Login failed!']);
    }

    /**
     * Get Transaction Report
     */
    public function getTransactionReport(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $formattedFromDate = date('Y-m-d', strtotime($fromDate));
        $formattedToDate = date('Y-m-d', strtotime($toDate));

        $merchant = $request->merchant;
        $acquirer = $request->acquirer;

        $token = session('token');
        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post(env('API_BASE_URL') . '/transactions/report', [
            'fromDate' => $formattedFromDate,
            'toDate' => $formattedToDate,
            'merchant' => $merchant, 
            'acquirer' => $acquirer  
        ]);

        return response()->json($response->json(), $response->status());
    }

    /**
     * Get Transaction Details
     */
    public function getTransaction(Request $request)
    {
        $transactionId = $request->transactionId;
        $token = session('token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post(env('API_BASE_URL') . '/transaction', [
            'transactionId' => $transactionId,
        ]);

        $responseData = $response->json();

        if ($response->successful()) {
            return response()->json($responseData, $response->status());
        }

        return response()->json([
            'status' => 'DECLINED',
            'message' => $responseData['message'] ?? 'An error occurred while fetching transaction details.',
        ], $response->status());
    }

    /**
     * Get Client Details
     */
    public function getClient(Request $request)
    {
        $token = session('token');
        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post(env('API_BASE_URL') . '/client', [
            'transactionId' => $request->transactionId,
        ]);

        return response()->json($response->json(), $response->status());
    }
}
