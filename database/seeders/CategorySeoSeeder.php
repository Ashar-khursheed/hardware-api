<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeoSeeder extends Seeder
{
    /**
     * Run: php artisan db:seed --class=CategorySeoSeeder
     * SEO data from Meta's.xlsx — 53 category pages
     */
    public function run(): void
    {
        $updates = [
            'storage-devices' => [
                'meta_title' => 'Buy Computer Storage Devices Online | The Hardware Box',
                'meta_description' => 'Shop computer storage devices, SSDs, HDDs and enterprise storage solutions. Buy online at competitive prices with fast shipping and expert support.',
            ],
            'internal-hard-drives' => [
                'meta_title' => 'Shop Internal Hard Drives for Pcs & Server | The Hardware Box',
                'meta_description' => 'Browse high-capacity internal hard drives designed for servers, workstations, and PCs. Competitive prices and fast shipping from The Hardware Box.',
            ],
            'desktop-hard-drives' => [
                'meta_title' => 'Buy Desktop Hard Drives for PC Upgrades | The Hardware Box',
                'meta_description' => 'Find desktop hard drives for PC upgrades, backups, and everyday storage. Explore trusted brands, competitive pricing, and fast shipping.',
            ],
            'laptop-hard-drives' => [
                'meta_title' => 'Buy Laptop Hard Drives from Top Brands | The Hardware Box',
                'meta_description' => 'Shop tested laptop hard drives backed by warranty. Reliable storage solutions, competitive pricing, and fast shipping from The Hardware Box.',
            ],
            'server-hard-drives' => [
                'meta_title' => 'Buy Server Hard Drives Ready to Ship | The Hardware Box',
                'meta_description' => 'Browse tested server hard drives from trusted brands. Warranty-backed storage solutions for enterprise IT, data centers, and business applications.',
            ],
            'external-hard-drives' => [
                'meta_title' => 'Buy External Hard Drives Online | The Hardware Box',
                'meta_description' => 'Discover external hard drives designed for secure storage, easy backups, and everyday portability. Tested products, warranty coverage, and fast shipping.',
            ],
            'ssds' => [
                'meta_title' => 'Buy SSD Storage Solutions Online | The Hardware Box',
                'meta_description' => 'Discover solid state drives (SSDs) built for faster boot times, quick file access, and reliable performance. Tested products, warranty coverage, and fast shipping.',
            ],
            'Hard-Drives-Enclosures' => [
                'meta_title' => 'Shop Hard Drive Enclosures from Top Brands | The Hardware Box',
                'meta_description' => 'Discover hard drive enclosures for HDD and SSD drives. Expand storage, transfer data, and protect drives with tested products and fast shipping.',
            ],
            'memories' => [
                'meta_title' => 'Buy RAM Memory for PC & Servers | The Hardware Box',
                'meta_description' => 'Discover RAM memory for PCs, laptops, and servers. Improve speed and performance with tested products, warranty coverage, and fast shipping.',
            ],
            'desktop-memory' => [
                'meta_title' => 'Buy High-Performance Desktop Memory | The Hardware Box',
                'meta_description' => 'Boost your PC performance with desktop memory modules built for speed, stability, and smooth multitasking. Tested quality with fast shipping.',
            ],
            'laptop-memory' => [
                'meta_title' => 'Buy Laptop RAM for Faster Performance | The Hardware Box',
                'meta_description' => 'Upgrade your laptop performance with reliable memory designed for faster speed, smooth multitasking, and stable everyday use.',
            ],
            'server-memory' => [
                'meta_title' => 'Buy Server Memory (RAM) Online | The Hardware Box',
                'meta_description' => 'Upgrade your infrastructure with server memory built for enterprise performance, stability, and demanding workloads. Tested and warranty-backed.',
            ],
            'networking-devices' => [
                'meta_title' => 'Buy Networking Devices for Business & IT | The Hardware Box',
                'meta_description' => 'Explore network devices for business, office, and enterprise use. Reliable, tested networking hardware. Shop now with fast shipping and warranty support.',
            ],
            'switches' => [
                'meta_title' => 'Buy High-Speed Network Switches | The Hardware Box',
                'meta_description' => 'Get reliable network switches designed for fast and stable connectivity. Enterprise-tested hardware with warranty support. Shop now with quick shipping.',
            ],
            'transceivers' => [
                'meta_title' => 'Buy Network Transceivers Online | The Hardware Box',
                'meta_description' => 'Get reliable network transceivers for high-speed data transmission and stable connectivity. Enterprise-tested hardware. Order now with fast delivery.',
            ],
            'routers' => [
                'meta_title' => 'Buy Reliable Network Routers | The Hardware Box',
                'meta_description' => 'Get reliable network routers for fast and stable connectivity in offices and enterprises. Tested hardware. Order now with fast delivery.',
            ],
            'ip-phones' => [
                'meta_title' => 'Buy High-Quality IP Phone Devices | The Hardware Box',
                'meta_description' => 'IP phone devices built for modern business communication, offering smooth connectivity, crisp audio quality, and dependable VoIP functionality. Shop now',
            ],
            'network-accessories' => [
                'meta_title' => 'Buy Network Accessories Online | The Hardware Box',
                'meta_description' => 'Build and support your IT setup with essential network accessories designed for stable connectivity and dependable business performance. Shop now',
            ],
            'motherboards' => [
                'meta_title' => 'Motherboards for All PC Type | buy Online | The Hardware Box',
                'meta_description' => 'Find computer motherboards for gaming, office, and server builds. Compatible with Intel and AMD systems for stable performance. Shop now',
            ],
            'desktop-motherboards' => [
                'meta_title' => 'High-Performance Desktop Motherboards | The Hardware Box',
                'meta_description' => 'Supports Intel and AMD processors with stable performance for gaming, office work, and custom PC builds. Desktop motherboards built for reliability. Shop now',
            ],
            'gaming-motherboards' => [
                'meta_title' => 'Buy Best Gaming Motherboards | The Hardware Box',
                'meta_description' => 'Buy gaming motherboards for stable FPS, smooth gameplay, and strong performance. Built for Intel & AMD systems. Shop now for latest models.',
            ],
            'laptop-motherboards' => [
                'meta_title' => 'Buy Laptop Motherboards for All Models | The Hardware Box',
                'meta_description' => 'Laptop motherboards for stable performance and reliable system compatibility across major brands like Dell, HP, and Lenovo. Explore now',
            ],
            'server-motherboards' => [
                'meta_title' => 'Buy Server Motherboards with Fast Shipping | The Hardware Box',
                'meta_description' => 'Buy server motherboards for data centers and enterprise systems, built for stable performance, scalability, and long-term uptime. Explore now',
            ],
            'printer-scanners' => [
                'meta_title' => 'Buy Cheap Printers & Scanners Online | The Hardware Box',
                'meta_description' => 'Buy affordable printers and scanners for home and office use with reliable performance, easy setup, and smooth operation. Shop now',
            ],
            'barcode-printers' => [
                'meta_title' => 'Buy Barcode Printers for Retail & Warehouse Use | The Hardware Box',
                'meta_description' => 'Buy barcode printers for fast, accurate label printing in retail and warehouses. Improve inventory tracking with reliable performance. Shop now',
            ],
            'pos-printers' => [
                'meta_title' => 'Buy POS Printers for Shops, Restaurants | The Hardware Box',
                'meta_description' => 'Buy POS printers for fast, reliable receipt printing in retail stores and restaurants. Smooth billing performance for daily use. Shop now',
            ],
            'barcode-scanners' => [
                'meta_title' => 'Buy Barcode Scanners Online | The Hardware Box',
                'meta_description' => 'Buy barcode scanners for fast, accurate data capture in retail, warehouse, and inventory systems. Improve workflow efficiency and speed. Shop now',
            ],
            'office-printers' => [
                'meta_title' => 'Office Printers for Business & Work Use | The Hardware Box',
                'meta_description' => 'Buy office printers for fast, reliable document printing in workplaces, schools, and businesses. Improve productivity with smooth performance. Shop now',
            ],
            'Card-printers' => [
                'meta_title' => 'Buy Employee ID Card Printers Online | The Hardware Box',
                'meta_description' => 'Buy card printers for professional ID card printing in offices, schools, and organizations. Fast, reliable output for employee badges. Shop now',
            ],
            'sensors' => [
                'meta_title' => 'Buy Access Control Devices for Security | The Hardware Box',
                'meta_description' => 'Buy access control devices for secure entry management in offices, buildings, and businesses. Improve safety with reliable systems. Shop now',
            ],
            'pc-and-servers' => [
                'meta_title' => 'Buy PCs and Servers for Office | The Hardware Box',
                'meta_description' => 'Buy PCs and servers for business, office, and IT infrastructure needs. Built for reliable performance, stability, and enterprise workloads. Shop now',
            ],
            'desktops' => [
                'meta_title' => 'Buy Desktop PCs for Work, Gaming | The Hardware Box',
                'meta_description' => 'Buy desktop computers for home, office, and business use with reliable performance, fast processing speed, and smooth multitasking experience. Shop now',
            ],
            'laptops' => [
                'meta_title' => 'Buy Laptops for Work, Study & Business | The Hardware Box',
                'meta_description' => 'Buy laptops online for business, study, and work use with fast performance, reliable battery life, and smooth multitasking experience.',
            ],
            'servers' => [
                'meta_title' => 'Buy Server Computers Online for Business | The Hardware Box',
                'meta_description' => 'Buy server computers for business and IT infrastructure with high performance, stable uptime, warranty support, and refurbished options available.',
            ],
            'workstations' => [
                'meta_title' => 'Buy High Performance Workstations Online | The Hardware Box',
                'meta_description' => 'Buy high performance workstations for 3D design, CAD, AI, and heavy workloads with powerful processing, warranty, and refurbished options available.',
            ],
            'tablets' => [
                'meta_title' => 'Tablet Phones for work, study & Gaming | The Hardware Box',
                'meta_description' => 'Buy tablet phones for work, study, and entertainment with fast performance, large display, tested refurbished options, and warranty support.',
            ],
            'power-supply-and-protection' => [
                'meta_title' => 'Buy Power Supply & Protection Devices | The Hardware Box',
                'meta_description' => 'Buy power supply & protection solutions for PCs, servers, and offices with stable backup, surge protection, tested quality, and reliable performance.',
            ],
            'power-distributions' => [
                'meta_title' => 'Buy Power Distribution Units Online | The Hardware Box',
                'meta_description' => 'Buy power distribution units for servers and IT systems with tested devices, stable performance, reliable power control, and fast shipping available.',
            ],
            'power-adapters-chargers' => [
                'meta_title' => 'Buy Power Adapters & Chargers Online | The Hardware Box',
                'meta_description' => 'Buy power adapters & chargers for laptops and devices with tested quality, fast charging support, and reliable performance. Fast shipping available.',
            ],
            'cpus-processors' => [
                'meta_title' => 'Buy CPUs & Processors Online | The Hardware Box',
                'meta_description' => 'Buy CPUs & processors for desktops, servers, & gaming systems with high performance, tested quality, warranty support, & fast shipping at The Hardware Box.',
            ],
            'server-processors' => [
                'meta_title' => 'Server Processors for Cloud & Data Center | The Hardware Box',
                'meta_description' => 'Server processors for data centers & enterprise systems offering high performance, tested quality, warranty support, and fast shipping at The Hardware Box.',
            ],
            'desktop-processors' => [
                'meta_title' => 'Buy Desktop Processors Online | The Hardware Box',
                'meta_description' => 'Boost your PC performance with desktop processors built for gaming, work, and multitasking, backed by tested quality and fast shipping. Shop Now',
            ],
            'laptop-processors' => [
                'meta_title' => 'Buy Laptop Processors for Upgrades | The Hardware Box',
                'meta_description' => 'Upgrade your laptop with high-performance processors for work, business, and multitasking, backed by tested quality, warranty, and fast shipping. Buy Now.',
            ],
            'gpus' => [
                'meta_title' => 'Buy Graphics Cards | NVIDIA & AMD | The Hardware Box',
                'meta_description' => 'Upgrade your PC with NVIDIA and AMD graphics cards for gaming, rendering, AI workloads, and content creation. Tested quality. Buy Now.',
            ],
            'cables-adapters' => [
                'meta_title' => 'Buy Computer Cables & Adapters Online | The Hardware Box',
                'meta_description' => 'Shop cables and adapters for computers, servers, networking equipment, & IT infrastructure with tested quality and fast shipping at The Hardware Box.',
            ],
            'adapters' => [
                'meta_title' => 'Buy Computer Adapters for PCs | The Hardware Box',
                'meta_description' => 'Buy computer adapters for PCs, monitors, & peripherals with reliable compatibility, tested quality, reasonable prices, & fast shipping at The Hardware Box.',
            ],
            'cables' => [
                'meta_title' => 'Buy Computer Cables for IT Use | The Hardware Box',
                'meta_description' => 'Computer cables for PCs, servers, and networking setups with reliable connectivity, tested quality, competitive pricing, and fast shipping at The Hardware Box.',
            ],
            'gaming' => [
                'meta_title' => 'Buy Gaming Accessories & Devices | The Hardware Box',
                'meta_description' => 'Gaming devices for PC and console setups including high-performance gear, tested quality, competitive pricing, and fast shipping at The Hardware Box.',
            ],
            'gaming-console' => [
                'meta_title' => 'Buy Gaming Consoles for Next-Level Play | The Hardware Box',
                'meta_description' => 'Experience next-level gaming with consoles built for smooth performance, immersive graphics, and fast shipping from The Hardware Box. Buy Now.',
            ],
            'vr-headsets' => [
                'meta_title' => 'Buy Next-Gen VR Headsets | The Hardware Box',
                'meta_description' => 'Buy VR headsets for immersive gaming with realistic visuals, smooth tracking, and high performance. Get the best deals at The Hardware Box.',
            ],
            'xbox' => [
                'meta_title' => 'Buy Xbox Consoles Online Today | The Hardware Box',
                'meta_description' => 'Level up your gaming setup with Xbox consoles delivering immersive visuals, stable performance, warranty support, fast shipping.Buy Now at The Hardware Box.',
            ],
            'playstations' => [
                'meta_title' => 'Buy PlayStation Consoles Online | The Hardware Box',
                'meta_description' => 'Step into next-gen gaming with PlayStation consoles built for ultra-smooth performance, stunning visuals, warranty support at The Hardware Box. Buy Now.',
            ],
            'gaming-accessories' => [
                'meta_title' => 'Next-Gen Gaming Accessories |Buy Now| | Hardware Box',
                'meta_description' => 'Explore gaming accessories for PC & console setups with reliable performance, tested quality, warranty support, fast shipping at The Hardware Box. Buy Now.',
            ],
        ];

        foreach ($updates as $slug => $seo) {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                $category = Category::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->first();
            }

            if (!$category) {
                $this->command?->warn("Category not found: {$slug}");
                continue;
            }

            $category->update($seo);
            $this->command?->info("Updated SEO: {$slug}");
        }
    }
}
