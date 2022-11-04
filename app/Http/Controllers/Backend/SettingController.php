<?php

namespace App\Http\Controllers\Backend;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function general()
    {
        return view('backend.settings.general');
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'site_title'    =>  'required|max:255|min:2|string',
            'site_description'    =>  'nullable|max:255|min:2|string',
            'site_address'    =>  'nullable|max:255|min:2|string',
        ]);
        Setting::updateOrCreate(['name' => 'site_title'], ['value' => $request->get('site_title')]);

        Setting::updateOrCreate(['name' => 'video_1'], ['value' => $request->get('video_1')]);
        Setting::updateOrCreate(['name' => 'video_2'], ['value' => $request->get('video_2')]);
        Setting::updateOrCreate(['name' => 'video_3'], ['value' => $request->get('video_3')]);

        // Update .env file
        overWriteEnvFile('APP_NAME', $request->site_title);
        // overWriteEnvFile('YOUR_DOMAIN', trimDomain(env('APP_URL')));

        notify()->success('Settings Successfully Updated.', 'Success');
        Setting::updateOrCreate(['name' => 'site_description'], ['value' => $request->get('site_description')]);
        Setting::updateOrCreate(['name' => 'site_address'], ['value' => $request->get('site_address')]);
        return back();
    }
    public function appearance()
    {
        return view('backend.settings.appearance');
    }
    public function updateAppearance(Request $request)
    {
        $this->validate($request, [
            'site_logo'    =>  'nullable|image',
            'site_favicon'    =>  'nullable|image',
        ]);
        // Site Logo
        if ($request->hasFile('site_logo')) {
            
            CreateFolder('uploads/logos');

            $site_logo_image       = $request->file('site_logo');
            $filename    = 'logo.' . $site_logo_image->getClientOriginalExtension();

            $site_logo_image_resize = Image::make($site_logo_image->getRealPath());
            $site_logo_image_resize->fit(300, 300);
            $site_logo_image_resize->save(public_path('uploads/logos/' . $filename));
            
            $settings = Setting::updateOrCreate(['name' => 'site_logo'],['value' => $filename]);
            $settings->update();
        }
        // Site Logo
        if ($request->hasFile('site_favicon')) {
            $settings = Setting::updateOrCreate(
                ['name' => 'site_favicon'],
            );
            $site_fav_icon       = $request->file('site_favicon');
            $filename    = 'favicon-.' . $site_fav_icon->getClientOriginalExtension();
            $site_fav_icon_resize = Image::make($site_fav_icon->getRealPath());
            // $site_fav_icon_resize->fit(300, 300);
            CreateFolder('uploads/logos'); //create an folder in backend if folder not exist
            $site_fav_icon_resize->save(public_path('uploads/logos/' . $filename));
            $settings->update();
        }
        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }

    private function deleteOldLogo($path)
    {
        Storage::disk('public')->delete($path);
    }
    public function mail()
    {
        return view('backend.settings.mail');
    }
    public function updateMailSettings(Request $request)
    {
        $this->validate($request, [
            'mail_mailer' => 'required|max:255',
            'mail_host' => 'nullable|max:255',
            'mail_port' => 'nullable|max:255',
            'mail_username' => 'nullable|max:255',
            'mail_password' => 'nullable|max:255',
            'mail_encryption' => 'nullable|max:255',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|max:255',
        ]);
        Setting::updateOrCreate(['name' => 'mail_mailer'], ['value' => $request->get('mail_mailer')]);
        Setting::updateOrCreate(['name' => 'mail_host'], ['value' => $request->get('mail_host')]);
        Setting::updateOrCreate(['name' => 'mail_port'], ['value' => $request->get('mail_port')]);
        Setting::updateOrCreate(['name' => 'mail_username'], ['value' => $request->get('mail_username')]);
        Setting::updateOrCreate(['name' => 'mail_password'], ['value' => $request->get('mail_password')]);
        Setting::updateOrCreate(['name' => 'mail_encryption'], ['value' => $request->get('mail_encryption')]);
        Setting::updateOrCreate(['name' => 'mail_from_address'], ['value' => $request->get('mail_from_address')]);
        Setting::updateOrCreate(['name' => 'mail_from_name'], ['value' => $request->get('mail_from_name')]);

        // Update .env mail settings
        overWriteEnvFile('MAIL_MAILER', $request->mail_mailer);
        overWriteEnvFile('MAIL_HOST', $request->mail_host);
        overWriteEnvFile('MAIL_PORT', $request->mail_port);
        overWriteEnvFile('MAIL_USERNAME', $request->mail_username);
        overWriteEnvFile('MAIL_PASSWORD', $request->mail_password);
        overWriteEnvFile('MAIL_ENCRYPTION', $request->mail_encryption);
        overWriteEnvFile('MAIL_FROM_ADDRESS', $request->mail_from_address);
        overWriteEnvFile('MAIL_FROM_NAME', $request->mail_from_name);

        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }
    public function socialite()
    {
        return view('backend.settings.socialite');
    }

    public function updatesocialiteSettings(Request $request)
    {
        $this->validate($request, [
            'facebook_client_id' => 'nullable|max:255',
            'facebook_client_secret' => 'nullable|max:255',

            'google_client_id' => 'nullable|max:255',
            'google_client_secret' => 'nullable|max:255',

            'github_client_id' => 'nullable|max:255',
            'github_client_secret' => 'nullable|max:255',
        ]);
        Setting::updateOrCreate(['name' => 'facebook_client_id'], ['value' => $request->get('facebook_client_id')]);
        Setting::updateOrCreate(['name' => 'facebook_client_secret'], ['value' => $request->get('facebook_client_secret')]);

        Setting::updateOrCreate(['name' => 'google_client_id'], ['value' => $request->get('google_client_id')]);
        Setting::updateOrCreate(['name' => 'google_client_secret'], ['value' => $request->get('google_client_secret')]);

        Setting::updateOrCreate(['name' => 'github_client_id'], ['value' => $request->get('github_client_id')]);
        Setting::updateOrCreate(['name' => 'mail_encryption'], ['value' => $request->get('mail_encryption')]);
        Setting::updateOrCreate(['name' => 'mail_from_address'], ['value' => $request->get('mail_from_address')]);
        Setting::updateOrCreate(['name' => 'github_client_secret'], ['value' => $request->get('github_client_secret')]);

        // Update .env file
        overWriteEnvFile('FACEBOOK_CLIENT_ID', $request->facebook_client_id);
        overWriteEnvFile('FACEBOOK_CLIENT_SECRET', $request->facebook_client_secret);
        
        overWriteEnvFile('GOOGLE_CLIENT_ID', $request->google_client_id);
        overWriteEnvFile('GOOGLE_CLIENT_SECRET', $request->google_client_secret);
        
        overWriteEnvFile('GITHUB_CLIENT_ID', $request->github_client_id);
        overWriteEnvFile('GITHUB_CLIENT_SECRET', $request->github_client_secret);

        notify()->success('Settings Successfully Updated.', 'Success');
        return back();
    }
}
