<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class AdaptersCategoryContentSeeder extends Seeder
{
    /**
     * Run: php artisan db:seed --class=AdaptersCategoryContentSeeder
     */
    public function run()
    {
        $category = $this->resolveAdaptersCategory();

        $description = 'Buy computer adapters for desktop, laptop and IT hardware systems. This includes device-specific adapters for HDMI, USB, and connecting peripheral displays, networking devices, etc., for use in the home, office, and business environment. Ensure your ports, brand, and device type match and ensure all connections are compatible.<br><br>Use the connection type, device, or brand search to quickly filter your choices. This category is organized to make it easy to find the single adapter you need, or for those who need to get an office rollout of all the accessories, to add them to cart without sifting through unrelated hardware.';

        $content = <<<'HTML'
<h2>Essential Adapter Solutions for Device Compatibility</h2>
<p>Browse adapters based on type, connection, or purpose:</p>
<ul>
<li>Port-to-port conversion adapters for mismatched connections</li>
<li>Multi-device hubs and expansion adapters</li>
<li>Display and peripheral connectivity adapters</li>
<li>Brand-specific and universal compatibility options</li>
</ul>
<p>Each option is built to support single-screen or multi-device configurations requiring displays, peripherals and network equipment to communicate. Use the filters to quickly see adapters for the type of connection and compatible device.</p>

<h2>Browse Computer and PC Adapter Options</h2>
<p><strong>Shop computer adapter and PC adapter solutions for desktop towers, workstations, and multi-monitor builds:</strong></p>
<ul>
<li>Desktop expansion and port adapters</li>
<li>Workstation connectivity adapters</li>
<li>Multi-monitor display adapters</li>
<li>Storage and drive adapters</li>
</ul>
<p>These picks suit new builds, hardware upgrades, and desktops requiring additional ports or device connection. Use the entire collection to equip an individual PC or a line of workstations.</p>

<h2>USB Connectivity and Peripheral Adapter Solutions</h2>
<p><strong>Shop USB adapter options for peripherals, external devices, and expansion needs:</strong></p>
<ul>
<li>USB-A to USB-C adapters</li>
<li>Multi-port USB hubs</li>
<li>Peripheral connection adapters</li>
<li>External drive and storage adapters</li>
</ul>
<p>Ideal for connecting keyboards, mouse, drives and printers to the laptop or desktop as there is fewer built-in ports. Have extras for common areas or carry an extra for travel use.</p>

<h2>Laptop and Brand-Specific Adapter Options</h2>
<p><strong>Browse laptop adapter and HP adapter options matched to specific port layouts and brand requirements:</strong></p>
<ul>
<li>Laptop docking and expansion adapters</li>
<li>HP-compatible adapter options</li>
<li>Brand-specific port adapters</li>
<li><strong>Universal laptop adapters</strong></li>
</ul>
<p>Filter by brand or port type to find adapters that fit your exact laptop model, from everyday use to docking and multi-display setups.</p>

<h2>HDMI and Display Connectivity Solutions</h2>
<p><strong>Shop HDMI adapter options for monitors, projectors, and office display setups:</strong></p>
<ul>
<li>HDMI to VGA and DisplayPort adapters</li>
<li>Multi-monitor display adapters</li>
<li>Projector and conference room adapters</li>
</ul>
<p>Browse by output type to match your device's video port with the display you're connecting to, whether that's a single monitor or a full conference room setup.</p>

<h2>IT Hardware Adapters for Business and Professional Use</h2>
<p><strong>Shop IT hardware adapters for offices, IT departments, and technical infrastructure environments:</strong></p>
<ul>
<li>Office workstation adapters</li>
<li>Bulk-order adapters for IT teams</li>
<li>Server room and infrastructure adapters</li>
<li>Multi-location deployment options</li>
</ul>
<p><strong>Suited to IT hardware adapters needed across desks, shared workstations, and larger business setups, with bulk and repeat-order options for procurement teams.</strong></p>

<h2>Choosing the Right Adapter for Your Setup</h2>
<p>Check these details before you buy:</p>
<ul>
<li>Port type on your current device</li>
<li>Compatibility with your specific brand or model</li>
<li>Usage environment: home, office, or IT infrastructure</li>
<li>Room for future expansion as your setup grows</li>
</ul>
<p>These are factors to check out quickly during your shopping and should be called to assist with any compatibility issues that may occur before you check out.</p>

<h2>Complete Your Setup with Related Connectivity Products</h2>
<p><strong>Adapters work best alongside the right cables and network hardware.</strong> Browse our full <a href="/category/cables-adapters">Cables &amp; Adapters</a> category for additional connectors and accessories that pair with the products above, from extension cables to specialty connectors. Setting up or expanding an office network? Shop <a href="/category/switches">Network Switches</a> to connect multiple devices and workstations across your environment. Explore both categories to complete your connectivity and IT hardware adapter setup, and check back often as new products are added.</p>
HTML;

        $category->setTranslation('description', 'en', $description);
        $category->setTranslation('content', 'en', $content);
        $category->save();

        $this->command?->info("Adapters category content updated successfully (id: {$category->id}, slug: {$category->slug}).");
    }

    private function resolveAdaptersCategory(): Category
    {
        $category = Category::whereRaw('LOWER(slug) = ?', ['adapters'])->first()
            ?? Category::where('name', 'Adapters')->first();

        if ($category) {
            return $category;
        }

        $this->command?->warn('Adapters category not found. Creating it now...');

        $parent = Category::whereRaw('LOWER(slug) = ?', ['cables-adapters'])->first()
            ?? Category::where('name', 'Cables & Adapters')->first();

        if (!$parent) {
            $parent = Category::create([
                'name' => 'Cables & Adapters',
                'slug' => 'cables-adapters',
                'type' => 'product',
                'status' => 1,
            ]);
            $this->command?->info("Created parent category: cables-adapters (id: {$parent->id}).");
        }

        $category = Category::create([
            'name' => 'Adapters',
            'slug' => 'adapters',
            'type' => 'product',
            'status' => 1,
            'parent_id' => $parent->id,
        ]);

        $this->command?->info("Created Adapters category (id: {$category->id}).");

        return $category;
    }
}
