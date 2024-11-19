<?php

namespace App\Http\Controllers\Database;

use Illuminate\Support\Facades\Artisan;

class DatabaseController {

    public function refreshDatabase() {
        try {
            Artisan::call('migrate:fresh');
            return response()->json(['status' => 'success', 'response' => 'Database refreshed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function seedDatabase() {
        try {
            Artisan::call('db:seed');
            return response()->json(['status' => 'success', 'response' => 'Database seeded successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
