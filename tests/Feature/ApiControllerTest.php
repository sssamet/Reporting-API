<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ApiControllerTest extends TestCase
{

    public function testLogin()
    {
        $response = $this->post('/api/login', [
            'email' => env('API_EMAIL'),
            'password' => env('API_PASSWORD'),
        ]);

        $response->assertStatus(302); 
        $response->assertSessionHas('token'); 
    }

    public function testGetTransactionReport()
    {
        $response = $this->post('/api/login', [
            'email' => env('API_EMAIL'),
            'password' => env('API_PASSWORD'),
        ]);
    
        $response->assertStatus(302);

        $token = session('token');
    
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/transactions/report', [
            'fromDate' => '2023-01-01',
            'toDate' => '2023-12-31',
        ]);
    
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'response', 
        ]);
    }
    
    

    public function testGetClient()
    {
        $response = $this->post('/api/login', [
            'email' => env('API_EMAIL'),
            'password' => env('API_PASSWORD'),
        ]);

        $response->assertStatus(302);

        $token = session('token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/client', [
            'transactionId' => '529-1438673740-2',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'customerInfo',
        ]);
    }
}
