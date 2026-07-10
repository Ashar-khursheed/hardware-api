<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class BarcodePrintersCategoryContentSeeder extends Seeder
{
    /**
     * Run: php artisan db:seed --class=BarcodePrintersCategoryContentSeeder
     */
    public function run()
    {
        $category = $this->resolveBarcodePrintersCategory();

        $description = 'Shop barcode printers for retail, warehouse, logistic, office and shipping labels. This section provides thermal barcode printer models, label-based printers and high-speed printers, sorted out according to the method of printing, size of the label and price range. Compare specs, filter by use case, and find barcode printers online ready for fast checkout.';

        $content = <<<'HTML'
<h2>Browse Barcode Printers by Type and Business Requirement</h2>
<p>This category is organized around how you actually shop: by printing method, output quality, and price point. You'll find thermal printing devices for everyday label runs, sharper output models for scan-heavy environments, and a mix of entry-level and professional machines side by side. Filter by label compatibility or business use case to narrow the list quickly, then drill into the specific subcategories below for a tighter selection.</p>

<h2>Thermal Barcode Printer Options for Continuous Label Printing</h2>
<p><strong>Shop thermal barcode printer models built for high-volume, repeat label output — the standard choice for retail price tags, warehouse bin labels, and daily shipping runs.</strong> These units skip ink and toner entirely, making them a practical pick wherever labels need to print back-to-back throughout the day. Browse direct thermal and thermal transfer formats side by side to match the right device to your label material.</p>

<h2>High Resolution Barcode Printer Options for Sharp Output</h2>
<p><strong>For environments where every scan needs to register on the first pass, this section features high resolution barcode printer models built for crisp, dense barcode output.</strong> These are suited to small label formats, fine-print compliance labels, and any setup where scan accuracy can't be compromised. Compare resolution specs directly on each listing to find the right fit for your label size.</p>

<h2>4x6 Barcode Label Printer Options for Shipping and Packaging</h2>
<p><strong>Built for couriers, fulfillment centers, and online sellers, this section lists 4x6 barcode label printer options sized specifically for shipping labels and packaging slips.</strong> These printers are matched to standard carrier label dimensions, making them a direct fit for daily parcel and freight labeling. Shop by label width to ensure compatibility with your shipping software and carrier requirements.</p>

<h2>Cheap Barcode Printer Options for Budget Buyers</h2>
<p><strong>Looking to keep costs down without skipping core functionality?</strong> This section rounds up cheap barcode printer picks suited to small retail counters, startups, and low-volume label needs. These models cover the essentials — clean barcode output and dependable daily use — at a price point built for lean budgets.</p>

<h2>Refurbished Barcode Printer Options for Cost-Effective Buying</h2>
<p><strong>Browse refurbished barcode printers for a lower-cost route to reliable label output.</strong> Each unit is tested and resold for buyers who want functional, ready-to-use equipment without paying for a new device. This section is a practical stop for businesses scaling operations or replacing equipment on a tighter budget.</p>

<h2>Barcode Printers for Business Operations</h2>
<p><strong>These barcode printers provide solutions for labeling applications wherever you need them, from checkout stands in retail environments to inventory locations in warehouses.</strong> Find a printer that suits your business based on its type, label size, or print speed requirements whether you operate a small till or a large multi-site company.</p>

<h2>Choosing a Barcode Printer Based on Usage Needs</h2>
<p>Narrow your search faster by keeping these factors in mind while browsing:</p>
<ul>
<li><strong>Printing volume</strong> — daily label count determines whether a light-duty or industrial-grade printer fits best</li>
<li><strong>Label size compatibility</strong> — match the printer to your label width and roll format, including 4x6 shipping labels</li>
<li><strong>Budget</strong> — select whether to purchase a cheap printer, refurbished printer or a brand new one</li>
<li><strong>Usage environment</strong> — retail counter, warehouse floor, shipping dock and office requires varying degrees of robustness</li>
<li><strong>New vs. refurbished</strong> — consider initial savings against warranty period and longevity</li>
</ul>

<h2>Complete Your Printing Ecosystem with Related Categories</h2>
<p><strong>Pair your barcode printer with other equipment from our Scanners &amp; Printers range.</strong> Explore <a href="/category/pos-printers">POS Printers</a> for receipt and checkout printing, <a href="/category/office-printers">Office Printers</a> for general document needs, and <a href="/category/Card-printers">Card Printers</a> for ID and access card production. If you're building out a full scanning and labeling setup, our <a href="/category/barcode-scanners">Barcode Scanners</a> range is a natural next stop. Browse the full <a href="/category/printer-scanners">Scanners &amp; Printers</a> category to see everything in one place.</p>
HTML;

        $category->setTranslation('heading', 'en', 'Buy Barcode Printers for Business Use');
        $category->setTranslation('description', 'en', $description);
        $category->setTranslation('content', 'en', $content);
        $category->save();

        $this->command?->info("Barcode Printers category content updated successfully (id: {$category->id}, slug: {$category->slug}).");
    }

    private function resolveBarcodePrintersCategory(): Category
    {
        $category = Category::whereRaw('LOWER(slug) = ?', ['barcode-printers'])->first()
            ?? Category::where('name', 'Barcode Printers')->first();

        if ($category) {
            return $category;
        }

        $this->command?->warn('Barcode Printers category not found. Creating it now...');

        $parent = Category::whereRaw('LOWER(slug) = ?', ['printer-scanners'])->first()
            ?? Category::where('name', 'Printer & Scanners')->first()
            ?? Category::where('name', 'Scanners & Printers')->first();

        if (!$parent) {
            $parent = Category::create([
                'name' => 'Printer & Scanners',
                'slug' => 'printer-scanners',
                'type' => 'product',
                'status' => 1,
            ]);
            $this->command?->info("Created parent category: printer-scanners (id: {$parent->id}).");
        }

        $category = Category::create([
            'name' => 'Barcode Printers',
            'slug' => 'barcode-printers',
            'type' => 'product',
            'status' => 1,
            'parent_id' => $parent->id,
        ]);

        $this->command?->info("Created Barcode Printers category (id: {$category->id}).");

        return $category;
    }
}
