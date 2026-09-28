<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'label' => 'Nama Website / Kos', 'value' => 'KosKora', 'type' => 'text', 'group' => 'general'],
            ['key' => 'app_description', 'label' => 'Deskripsi Pendek', 'value' => 'Kos eksklusif yang nyaman, aman, dan berfasilitas lengkap.', 'type' => 'textarea', 'group' => 'general'],
            
            // Contact
            ['key' => 'contact_whatsapp', 'label' => 'Nomor WhatsApp', 'value' => '081234567890', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_email', 'label' => 'Email Utama', 'value' => 'info@koskora.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'address', 'label' => 'Alamat Lengkap', 'value' => 'Jl. Sudirman No. 123, Jakarta Selatan', 'type' => 'textarea', 'group' => 'contact'],
            
            // Rules
            ['key' => 'kos_rules', 'label' => 'Peraturan Kos', 'value' => "1. Dilarang membawa hewan peliharaan.\n2. Gerbang ditutup jam 23:00 WIB.\n3. Tamu menginap wajib lapor.", 'type' => 'textarea', 'group' => 'rules'],
            ['key' => 'payment_policy', 'label' => 'Kebijakan Pembayaran', 'value' => "1. Pembayaran paling lambat tanggal 5 setiap bulannya.\n2. Keterlambatan dikenakan denda Rp50.000/hari.", 'type' => 'textarea', 'group' => 'rules'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
