<?php

namespace App\Services;

use App\Models\UserHistory;
use Illuminate\Support\Facades\Http;

class UserHistoryService
{
    public function store($userId, $activity)
    {
        try {

            // Get user IP
            $ip = request()->ip();

            // If localhost then get real public IP
            if (in_array($ip, ['127.0.0.1', '::1'])) {
                $ip = Http::timeout(3)->get('https://api.ipify.org')->body();
            }

            // Get location from API
            $response = Http::timeout(5)->get("http://ip-api.com/json/".$ip);

            $location = $response->successful() ? $response->json() : [];

            $city = $location['city'] ?? 'Unknown City';
            $country = $location['country'] ?? 'Unknown Country';
            $region = $location['regionName'] ?? null;

            // Device / Browser info
            $userAgent = request()->header('User-Agent');

            // Store history
            UserHistory::create([
                'user_id' => $userId,
                'activity' => $activity,
                'ip_address' => $ip,
                'city' => $city,
                'country' => $country,
                'device' => $userAgent,
                'user_agent' => $userAgent,
                'activity_time' => now(),
            ]);

        } catch (\Exception $e) {

            // Fail safe: still store activity without location
            UserHistory::create([
                'user_id' => $userId,
                'activity' => $activity,
                'ip_address' => request()->ip(),
                'city' => null,
                'country' => null,
                'device' => request()->header('User-Agent'),
                'user_agent' => request()->header('User-Agent'),
                'activity_time' => now(),
            ]);

        }
    }
}