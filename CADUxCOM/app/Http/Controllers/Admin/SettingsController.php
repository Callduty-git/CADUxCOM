<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Mostrar la página principal de configuraciones
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Mostrar configuraciones generales
     */
    public function general()
    {
        $settings = $this->getAllSettings();
        
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Actualizar configuraciones generales
     */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'app_keywords' => 'nullable|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:255',
            'maintenance_mode' => 'boolean',
            'registration_enabled' => 'boolean',
            'company_registration_enabled' => 'boolean',
            'auto_approve_companies' => 'boolean',
            'max_products_per_company' => 'required|integer|min:1|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = $request->only([
            'app_name', 'app_description', 'app_keywords',
            'contact_email', 'contact_phone', 'contact_address',
            'maintenance_mode', 'registration_enabled', 
            'company_registration_enabled', 'auto_approve_companies',
            'max_products_per_company'
        ]);

        // Manejar subida de logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            $settings['logo'] = $logoPath;
        }

        // Manejar subida de favicon
        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $settings['favicon'] = $faviconPath;
        }

        $this->updateSettings($settings);

        return redirect()->route('admin.settings.general')
            ->with('success', 'Configuraciones generales actualizadas correctamente.');
    }

    /**
     * Mostrar configuraciones de email
     */
    public function email()
    {
        $settings = $this->getAllSettings();
        
        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Actualizar configuraciones de email
     */
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required|in:smtp,sendmail,mailgun,ses,postmark,log',
            'mail_host' => 'required_if:mail_driver,smtp|nullable|string',
            'mail_port' => 'required_if:mail_driver,smtp|nullable|integer',
            'mail_username' => 'required_if:mail_driver,smtp|nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
            'notification_emails' => 'boolean',
            'welcome_emails' => 'boolean',
            'company_approval_emails' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = $request->only([
            'mail_driver', 'mail_host', 'mail_port', 'mail_username',
            'mail_encryption', 'mail_from_address', 'mail_from_name',
            'notification_emails', 'welcome_emails', 'company_approval_emails'
        ]);

        // Solo actualizar la contraseña si se proporciona
        if ($request->filled('mail_password')) {
            $settings['mail_password'] = $request->mail_password;
        }

        $this->updateSettings($settings);

        return redirect()->route('admin.settings.email')
            ->with('success', 'Configuraciones de email actualizadas correctamente.');
    }

    /**
     * Mostrar configuraciones de la plataforma
     */
    public function platform()
    {
        $settings = $this->getAllSettings();
        
        return view('admin.settings.platform', compact('settings'));
    }

    /**
     * Actualizar configuraciones de la plataforma
     */
    public function updatePlatform(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reviews_enabled' => 'boolean',
            'reviews_require_approval' => 'boolean',
            'max_reviews_per_user' => 'required|integer|min:1|max:100',
            'min_review_length' => 'required|integer|min:10|max:500',
            'max_review_length' => 'required|integer|min:50|max:2000',
            'search_results_per_page' => 'required|integer|min:5|max:100',
            'featured_companies_limit' => 'required|integer|min:1|max:50',
            'cache_duration' => 'required|integer|min:1|max:1440',
            'session_lifetime' => 'required|integer|min:60|max:10080',
            'password_reset_expire' => 'required|integer|min:15|max:1440',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $settings = $request->only([
            'reviews_enabled', 'reviews_require_approval', 'max_reviews_per_user',
            'min_review_length', 'max_review_length', 'search_results_per_page',
            'featured_companies_limit', 'cache_duration', 'session_lifetime',
            'password_reset_expire'
        ]);

        $this->updateSettings($settings);

        // Limpiar cache si se cambió la duración
        if ($request->has('cache_duration')) {
            Cache::flush();
        }

        return redirect()->route('admin.settings.platform')
            ->with('success', 'Configuraciones de la plataforma actualizadas correctamente.');
    }

    /**
     * Obtener todas las configuraciones
     */
    private function getAllSettings()
    {
        $defaultSettings = [
            // Configuraciones generales
            'app_name' => config('app.name', 'CADUxCOM'),
            'app_description' => 'Plataforma de conexión entre empresas y usuarios',
            'app_keywords' => 'empresas, productos, servicios, directorio',
            'contact_email' => 'admin@caduxcom.com',
            'contact_phone' => '',
            'contact_address' => '',
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'company_registration_enabled' => true,
            'auto_approve_companies' => false,
            'max_products_per_company' => 50,
            'logo' => '',
            'favicon' => '',

            // Configuraciones de email
            'mail_driver' => config('mail.default', 'smtp'),
            'mail_host' => config('mail.mailers.smtp.host', ''),
            'mail_port' => config('mail.mailers.smtp.port', 587),
            'mail_username' => config('mail.mailers.smtp.username', ''),
            'mail_password' => config('mail.mailers.smtp.password', ''),
            'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
            'mail_from_address' => config('mail.from.address', ''),
            'mail_from_name' => config('mail.from.name', ''),
            'notification_emails' => true,
            'welcome_emails' => true,
            'company_approval_emails' => true,

            // Configuraciones de la plataforma
            'reviews_enabled' => true,
            'reviews_require_approval' => false,
            'max_reviews_per_user' => 10,
            'min_review_length' => 20,
            'max_review_length' => 500,
            'search_results_per_page' => 20,
            'featured_companies_limit' => 12,
            'cache_duration' => 60,
            'session_lifetime' => 120,
            'password_reset_expire' => 60,
        ];

        // Obtener configuraciones guardadas desde cache o archivo
        $savedSettings = Cache::get('app_settings', []);
        
        // Si no hay configuraciones en cache, intentar cargar desde archivo
        if (empty($savedSettings) && Storage::exists('settings.json')) {
            $savedSettings = json_decode(Storage::get('settings.json'), true) ?? [];
            Cache::put('app_settings', $savedSettings, now()->addHours(24));
        }

        return array_merge($defaultSettings, $savedSettings);
    }

    /**
     * Actualizar configuraciones
     */
    private function updateSettings(array $settings)
    {
        $currentSettings = $this->getAllSettings();
        $updatedSettings = array_merge($currentSettings, $settings);

        // Guardar en cache
        Cache::put('app_settings', $updatedSettings, now()->addHours(24));

        // Guardar en archivo
        Storage::put('settings.json', json_encode($updatedSettings, JSON_PRETTY_PRINT));

        return true;
    }

    /**
     * Obtener una configuración específica
     */
    public static function getSetting($key, $default = null)
    {
        $settings = Cache::get('app_settings', []);
        
        if (empty($settings) && Storage::exists('settings.json')) {
            $settings = json_decode(Storage::get('settings.json'), true) ?? [];
            Cache::put('app_settings', $settings, now()->addHours(24));
        }

        return $settings[$key] ?? $default;
    }

    /**
     * Limpiar cache de configuraciones
     */
    public function clearCache()
    {
        Cache::forget('app_settings');
        Cache::flush();

        return redirect()->back()
            ->with('success', 'Cache de configuraciones limpiado correctamente.');
    }

    /**
     * Exportar configuraciones
     */
    public function export()
    {
        $settings = $this->getAllSettings();
        
        $filename = 'configuraciones_' . date('Y-m-d_H-i-s') . '.json';
        
        return response()->json($settings)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Importar configuraciones
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings_file' => 'required|file|mimes:json|max:1024',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            $content = file_get_contents($request->file('settings_file')->getRealPath());
            $settings = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Archivo JSON inválido');
            }

            $this->updateSettings($settings);

            return redirect()->route('admin.settings.index')
                ->with('success', 'Configuraciones importadas correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al importar configuraciones: ' . $e->getMessage());
        }
    }
}