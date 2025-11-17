<?php

namespace Tests\Feature;

use App\Models\DatafeedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ImportDatafeedCsvCommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the command fails when file does not exist.
     */
    public function test_import_fails_when_file_does_not_exist(): void
    {
        $this->artisan('datafeed:import /non/existent/file.csv')
            ->expectsOutput('File not found: /non/existent/file.csv')
            ->assertExitCode(1);
    }

    /**
     * Test that the command successfully imports valid CSV.
     */
    public function test_import_successfully_imports_valid_csv(): void
    {
        // Create a temporary CSV file
        $csvContent = "aw_deep_link|product_name|aw_product_id|merchant_product_id|merchant_image_url|description|merchant_category|search_price|merchant_name|merchant_id|category_name|category_id|aw_image_url|currency|store_price|delivery_cost|merchant_deep_link|language|last_updated|display_price|data_feed_id|product_model|model_number|dimensions|brand_name|brand_id|colour|product_short_description|specifications|condition|keywords|promotional_text|product_type|commission_group|merchant_product_category_path|merchant_product_second_category|merchant_product_third_category|rrp_price|saving|savings_percent|base_price|base_price_amount|base_price_text|product_price_old|delivery_restrictions|delivery_weight|warranty|terms_of_contract|delivery_time|in_stock|stock_quantity|valid_from|valid_to|is_for_sale|web_offer|pre_order|stock_status|size_stock_status|size_stock_amount|merchant_thumb_url|large_image|alternate_image|aw_thumb_url|alternate_image_two|alternate_image_three|alternate_image_four|reviews|average_rating|rating|number_available|custom_1|custom_2|custom_3|custom_4|custom_5|custom_6|custom_7|custom_8|custom_9|ean|isbn|upc|mpn|parent_product_id|product_GTIN|basket_link|Fashion:suitable_for|Fashion:category|Fashion:size|Fashion:material|Fashion:pattern|Fashion:swatch\n";
        $csvContent .= "https://example.com/p1|Product 1|AW001|MERC001|https://img.jpg|Description|Category|99.99|Merchant|M123|Cat|C1|https://aw.jpg|BRL|99.99|10|https://m.com|pt|2025-11-17|R\$ 99|F1|Model|M1|10x10|Brand|B1|Black|Short|Specs|New|keywords|Promo|Physical|Standard|Path|Cat2|Cat3|109.99|10|10%|99.99|99.99|R\$ 99|109.99|Brazil|1kg|12m|Terms|2d|1|10|2025-11-17|2025-12-31|1|Offer|0|In stock|Avail|10|thumb|large|alt|aw_thumb|alt2|alt3|alt4|Review|4.5|5|10|C1|C2|C3|C4|C5|C6|C7|C8|C9|123|456|789|MPN|PAR|GTIN|basket|Unisex|Tech|M|Cotton|Solid|Black\n";
        $csvContent .= "https://example.com/p2|Product 2|AW002|MERC002|https://img2.jpg|Desc2|Cat2|149.99|Merchant|M123|Cat|C2|https://aw2.jpg|BRL|149.99|15|https://m2.com|pt|2025-11-17|R\$ 149|F1|Model2|M2|20x15|Brand2|B2|Red|Short2|Specs2|New|key2|Promo2|Fashion|Premium|Path2|Cat22|Cat32|179.99|30|20%|149.99|149.99|R\$ 149|179.99|World|0.5kg|6m|Terms2|3d|1|5|2025-11-17|2025-12-31|1|Offer2|0|Stock|Avail2|5|thumb2|large2|alt21|aw_thumb2|alt22|alt23|alt24|Review2|4.8|5|5|C21|C22|C23|C24|C25|C26|C27|C28|C29|1232|4562|7892|MPN2|PAR2|GTIN2|basket2|Women|Fashion|L|Silk|Pattern|Red\n";

        $tmpFile = tempnam(sys_get_temp_dir(), 'test_csv_');
        file_put_contents($tmpFile, $csvContent);

        try {
            // Run the import command
            $this->artisan("datafeed:import {$tmpFile}")
                ->expectsOutput("Starting import from: {$tmpFile}")
                ->expectsOutput('Found 92 columns in CSV.')
                ->expectsOutput('Successfully imported 2 products.')
                ->assertExitCode(0);

            // Verify data was imported
            $this->assertDatabaseCount('datafeeds_items', 2);

            // Verify specific data
            $firstItem = DatafeedItem::where('merchant_product_id', 'MERC001')->first();
            $this->assertNotNull($firstItem);
            $this->assertEquals('Product 1', $firstItem->product_name);
            $this->assertEquals('AW001', $firstItem->aw_product_id);
            $this->assertEquals('M123', $firstItem->merchant_id);

            $secondItem = DatafeedItem::where('merchant_product_id', 'MERC002')->first();
            $this->assertNotNull($secondItem);
            $this->assertEquals('Product 2', $secondItem->product_name);
            $this->assertEquals('AW002', $secondItem->aw_product_id);
        } finally {
            // Clean up
            if (file_exists($tmpFile)) {
                unlink($tmpFile);
            }
        }
    }

    /**
     * Test that the command handles empty rows correctly.
     */
    public function test_import_skips_empty_rows(): void
    {
        $csvContent = "aw_deep_link|product_name|aw_product_id|merchant_product_id|merchant_image_url|description|merchant_category|search_price|merchant_name|merchant_id|category_name|category_id|aw_image_url|currency|store_price|delivery_cost|merchant_deep_link|language|last_updated|display_price|data_feed_id|product_model|model_number|dimensions|brand_name|brand_id|colour|product_short_description|specifications|condition|keywords|promotional_text|product_type|commission_group|merchant_product_category_path|merchant_product_second_category|merchant_product_third_category|rrp_price|saving|savings_percent|base_price|base_price_amount|base_price_text|product_price_old|delivery_restrictions|delivery_weight|warranty|terms_of_contract|delivery_time|in_stock|stock_quantity|valid_from|valid_to|is_for_sale|web_offer|pre_order|stock_status|size_stock_status|size_stock_amount|merchant_thumb_url|large_image|alternate_image|aw_thumb_url|alternate_image_two|alternate_image_three|alternate_image_four|reviews|average_rating|rating|number_available|custom_1|custom_2|custom_3|custom_4|custom_5|custom_6|custom_7|custom_8|custom_9|ean|isbn|upc|mpn|parent_product_id|product_GTIN|basket_link|Fashion:suitable_for|Fashion:category|Fashion:size|Fashion:material|Fashion:pattern|Fashion:swatch\n";
        $csvContent .= "https://example.com/p1|Product 1|AW001|MERC001|https://img.jpg|Description|Category|99.99|Merchant|M123|Cat|C1|https://aw.jpg|BRL|99.99|10|https://m.com|pt|2025-11-17|R\$ 99|F1|Model|M1|10x10|Brand|B1|Black|Short|Specs|New|keywords|Promo|Physical|Standard|Path|Cat2|Cat3|109.99|10|10%|99.99|99.99|R\$ 99|109.99|Brazil|1kg|12m|Terms|2d|1|10|2025-11-17|2025-12-31|1|Offer|0|In stock|Avail|10|thumb|large|alt|aw_thumb|alt2|alt3|alt4|Review|4.5|5|10|C1|C2|C3|C4|C5|C6|C7|C8|C9|123|456|789|MPN|PAR|GTIN|basket|Unisex|Tech|M|Cotton|Solid|Black\n";
        $csvContent .= "||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||\n"; // Empty row
        $csvContent .= "https://example.com/p2|Product 2|AW002|MERC002|https://img2.jpg|Desc2|Cat2|149.99|Merchant|M123|Cat|C2|https://aw2.jpg|BRL|149.99|15|https://m2.com|pt|2025-11-17|R\$ 149|F1|Model2|M2|20x15|Brand2|B2|Red|Short2|Specs2|New|key2|Promo2|Fashion|Premium|Path2|Cat22|Cat32|179.99|30|20%|149.99|149.99|R\$ 149|179.99|World|0.5kg|6m|Terms2|3d|1|5|2025-11-17|2025-12-31|1|Offer2|0|Stock|Avail2|5|thumb2|large2|alt21|aw_thumb2|alt22|alt23|alt24|Review2|4.8|5|5|C21|C22|C23|C24|C25|C26|C27|C28|C29|1232|4562|7892|MPN2|PAR2|GTIN2|basket2|Women|Fashion|L|Silk|Pattern|Red\n";

        $tmpFile = tempnam(sys_get_temp_dir(), 'test_csv_');
        file_put_contents($tmpFile, $csvContent);

        try {
            $this->artisan("datafeed:import {$tmpFile}")
                ->expectsOutput('Successfully imported 2 products.')
                ->assertExitCode(0);

            // Should only import 2 products (empty row should be skipped)
            $this->assertDatabaseCount('datafeeds_items', 2);
        } finally {
            if (file_exists($tmpFile)) {
                unlink($tmpFile);
            }
        }
    }

    /**
     * Test that indexes exist on the table.
     */
    public function test_table_has_required_indexes(): void
    {
        $indexes = \DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='datafeeds_items'");
        $indexNames = array_map(fn ($idx) => $idx->name, $indexes);

        // Check that our custom indexes exist
        $this->assertContains('idx_merchant_product_id', $indexNames);
        $this->assertContains('idx_merchant_id', $indexNames);
        $this->assertContains('idx_aw_product_id', $indexNames);
    }
}
