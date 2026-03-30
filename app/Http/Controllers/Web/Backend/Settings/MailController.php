<?php

namespace App\Http\Controllers\Web\Backend\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MailController extends Controller
{
    public function index(){
        return view("backend.layout.settings.mail-settings");
    }

    public function update(Request $request) {
        $data = $request->validate([
            'mail_mailer'       => 'nullable|string',
            'mail_host'         => 'nullable|string',
            'mail_port'         => 'nullable|string',
            'mail_username'     => 'nullable|string',
            'mail_password'     => 'nullable|string',
            'mail_encryption'   => 'nullable|string',
            'mail_from_address' => 'nullable|string',
        ]);

        try {
            $this->updateEnv([
                'MAIL_MAILER'       => $data['mail_mailer'],
                'MAIL_HOST'         => $data['mail_host'],
                'MAIL_PORT'         => $data['mail_port'],
                'MAIL_USERNAME'     => $data['mail_username'],
                'MAIL_PASSWORD'     => $data['mail_password'],
                'MAIL_ENCRYPTION'   => $data['mail_encryption'],
                'MAIL_FROM_ADDRESS' => '"' . $data['mail_from_address'] . '"',
            ]);

            return back()->with('success', 'Updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update ... ' . $e->getMessage());
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
}
