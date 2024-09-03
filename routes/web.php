<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/weather', function () {
    $city = request('city');
    $apiKey = '9a1e9cd0008d64950995c7e7c4b9d36d';
    $response = Http::withOptions(['verify' => false])->get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric");
    
    if ($response->successful()) {
        $weather = $response->json();
        return "{$weather['main']['temp']}°C, {$weather['weather'][0]['description']}";
    }

    return 'N/A';
});

Route::get('/countries', function () {
    $response = Http::withOptions(['verify' => false])->get('https://restcountries.com/v3.1/all');
    $countries = $response->json();

    // Search filter
    $search = request('search');
    if ($search) {
        $countries = array_filter($countries, function ($country) use ($search) {
            return stripos($country['name']['common'], $search) !== false;
        });
    }


    // Paginate results
    $perPage = 20;
    $page = request('page', 1);
    $total = count($countries);
    $countries = array_slice($countries, ($page - 1) * $perPage, $perPage);

    return view('countries.index', [
        'countries' => $countries,
        'total' => $total,
        'perPage' => $perPage,
        'currentPage' => $page,
        'search' => $search,
    ]);
});
