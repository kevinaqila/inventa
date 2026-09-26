<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['code' => 'INV-PRJ-001', 'name' => 'Proyektor Epson EB-X500', 'status' => 'available', 'condition' => 'good', 'description' => 'Proyektor LCD 3300 Lumens, resolusi XGA'],
            ['code' => 'INV-PRJ-002', 'name' => 'Proyektor Epson EB-X500', 'status' => 'available', 'condition' => 'good', 'description' => 'Proyektor LCD 3300 Lumens, resolusi XGA'],
            ['code' => 'INV-PRJ-003', 'name' => 'Proyektor BenQ MX560', 'status' => 'available', 'condition' => 'good', 'description' => 'Proyektor DLP 4000 Lumens, resolusi XGA'],
            ['code' => 'INV-MIC-001', 'name' => 'Mic Wireless Sony WF-1000', 'status' => 'available', 'condition' => 'good', 'description' => 'Microphone wireless handheld, frekuensi UHF'],
            ['code' => 'INV-MIC-002', 'name' => 'Mic Wireless Shure SVX24', 'status' => 'available', 'condition' => 'good', 'description' => 'Microphone wireless handheld dengan receiver'],
            ['code' => 'INV-MIC-003', 'name' => 'Mic Clip-On Boya BY-M1', 'status' => 'available', 'condition' => 'good', 'description' => 'Microphone lavalier clip-on untuk presentasi'],
            ['code' => 'INV-KBL-001', 'name' => 'Kabel HDMI 5 Meter', 'status' => 'available', 'condition' => 'good', 'description' => 'Kabel HDMI to HDMI, panjang 5 meter'],
            ['code' => 'INV-KBL-002', 'name' => 'Kabel HDMI 3 Meter', 'status' => 'available', 'condition' => 'good', 'description' => 'Kabel HDMI to HDMI, panjang 3 meter'],
            ['code' => 'INV-KBL-003', 'name' => 'Kabel VGA 5 Meter', 'status' => 'available', 'condition' => 'good', 'description' => 'Kabel VGA male-to-male, panjang 5 meter'],
            ['code' => 'INV-SPK-001', 'name' => 'Speaker Portable JBL PartyBox 110', 'status' => 'available', 'condition' => 'good', 'description' => 'Speaker bluetooth portable 160W'],
            ['code' => 'INV-SPK-002', 'name' => 'Speaker Aktif Behringer B210D', 'status' => 'available', 'condition' => 'good', 'description' => 'Speaker aktif 200W untuk ruangan sedang'],
            ['code' => 'INV-LPT-001', 'name' => 'Laptop Lenovo ThinkPad T14', 'status' => 'available', 'condition' => 'good', 'description' => 'Laptop i5 Gen 12, RAM 8GB, SSD 256GB'],
            ['code' => 'INV-LPT-002', 'name' => 'Laptop ASUS VivoBook 14', 'status' => 'available', 'condition' => 'good', 'description' => 'Laptop i5 Gen 11, RAM 8GB, SSD 512GB'],
            ['code' => 'INV-PTR-001', 'name' => 'Laser Pointer Logitech R500s', 'status' => 'available', 'condition' => 'good', 'description' => 'Presenter wireless dengan laser pointer merah'],
            ['code' => 'INV-EXT-001', 'name' => 'Extension Cable / Roll Kabel 10M', 'status' => 'available', 'condition' => 'good', 'description' => 'Kabel roll listrik 10 meter, 4 lubang colokan'],
            ['code' => 'INV-WB-001', 'name' => 'Whiteboard Portable 120x80cm', 'status' => 'available', 'condition' => 'good', 'description' => 'Whiteboard magnetic portable dengan stand'],
            ['code' => 'INV-ADT-001', 'name' => 'Adapter USB-C to HDMI', 'status' => 'available', 'condition' => 'good', 'description' => 'Dongle converter USB Type-C ke HDMI 4K'],
            ['code' => 'INV-ADT-002', 'name' => 'Adapter VGA to HDMI', 'status' => 'available', 'condition' => 'good', 'description' => 'Converter VGA ke HDMI dengan audio jack'],
            ['code' => 'INV-CAM-001', 'name' => 'Webcam Logitech C920 HD Pro', 'status' => 'available', 'condition' => 'good', 'description' => 'Webcam Full HD 1080p untuk video conference'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
