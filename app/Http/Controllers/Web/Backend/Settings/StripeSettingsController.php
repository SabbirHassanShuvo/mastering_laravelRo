<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;


class StripeSettingsController extends Controller
{
    public function index(){
        return view("backend.layout.settings.payments-settings");
    }

    public function update(Request $request) {
        $data = $request->validate([
            'stripe_key'              => 'nullable|string',
            'stripe_secret'           => 'nullable|string',
            'stripe_webhook_secret'   => 'nullable|string',
        ]);

        try {
            $this->updateEnv([
                'STRIPE_KEY'            => $data['stripe_key'],
                'STRIPE_SECRET'         => $data['stripe_secret'],
                'STRIPE_WEBHOOK_SECRET' => $data['stripe_webhook_secret'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Stripe settings updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Stripe settings: ' . $e->getMessage()
            ], 422);
        }
    }

    public function updateSSL(Request $request) {
        $data = $request->validate([
            'sslc_store_id'       => 'nullable|string',
            'sslc_store_password' => 'nullable|string',
        ]);

        try {
            $this->updateEnv([
                'SSLCZ_STORE_ID'       => $data['sslc_store_id'],
                'SSLCZ_STORE_PASSWORD' => $data['sslc_store_password'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SSL Commerz settings updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update SSL Commerz settings: ' . $e->getMessage()
            ], 422);
        }
    }

    public function updateGeneral(Request $request) {
        $data = $request->validate([
            'app_name' => 'nullable|string',
            'app_url'  => 'nullable|string',
        ]);

        try {
            $this->updateEnv([
                'APP_NAME' => '"' . $data['app_name'] . '"',
                'APP_URL'  => $data['app_url'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'General settings updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update general settings: ' . $e->getMessage()
            ], 422);
        }
    }

    private function updateEnv(array $data) {
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $content = File::get($envPath);

            foreach ($data as $key => $value) {
                // Ensure key exists (not commented out), if not append it
                if (preg_match("/^$key=/m", $content)) {
                    $content = preg_replace("/^$key=.*$/m", "$key=$value", $content);
                } else {
                    $content .= "\n$key=$value";
                }
            }

            File::put($envPath, $content);
        }
    }

    public function test(){
        return 'test';
    }
}
