# Quick Setup Guide - State Tax Implementation

## Step-by-Step Installation

### Step 1: Run Migration
This adds the `tax_rate` column to the states table.

```bash
php artisan migrate
```

**Expected Output:**
```
Migrating: 2026_02_02_000000_add_tax_rate_to_states_table
Migrated:  2026_02_02_000000_add_tax_rate_to_states_table
```

### Step 2: Seed US States with Tax Rates
This populates all 50 US states with their tax rates.

```bash
php artisan db:seed --class=UsStateTaxSeeder
```

**Expected Output:**
```
US states with tax rates seeded successfully!
```

### Step 3: Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 4: Test the Implementation
1. Go to your checkout page
2. Add a product to cart
3. Enter an address with a US state
4. Verify the tax is calculated correctly

## Verification

### Check if States Have Tax Rates
Run this SQL query to verify:

```sql
SELECT name, tax_rate FROM states WHERE country_id = (SELECT id FROM countries WHERE iso_3166_2 = 'US') ORDER BY name;
```

You should see all 50 US states with their respective tax rates.

### Test Tax Calculation
Make a POST request to `/api/v1/checkout/verify` with:

```json
{
  "products": [
    {
      "product_id": 1,
      "quantity": 1
    }
  ],
  "payment_method": "cod",
  "billing_address": {
    "title": "Home",
    "street": "123 Main St",
    "city": "Los Angeles",
    "state_id": [STATE_ID_FOR_CALIFORNIA],
    "country_id": [USA_COUNTRY_ID],
    "pincode": "90001"
  }
}
```

The response should include tax calculated at 8.85% (California's rate).

## Troubleshooting

### If migration fails with database connection error:
1. Check your `.env` file
2. Verify database credentials
3. Ensure database server is running
4. Make sure you have proper permissions

### If seeder fails:
1. Ensure the migration ran successfully first
2. Check if the countries table has a USA entry
3. Run: `php artisan db:seed --class=UsStateTaxSeeder --force`

### If tax is not being calculated:
1. Clear all caches
2. Check that state_id is being passed in the request
3. Verify the state has a tax_rate in the database
4. Check Laravel logs: `storage/logs/laravel.log`

## Files Modified/Created

### Created:
- `database/migrations/2026_02_02_000000_add_tax_rate_to_states_table.php`
- `database/seeders/UsStateTaxSeeder.php`
- `STATE_TAX_IMPLEMENTATION.md`
- `QUICK_SETUP_GUIDE.md`

### Modified:
- `app/Models/State.php` - Added tax_rate to fillable
- `app/Http/Traits/CheckoutTrait.php` - Added state-based tax calculation logic

## Important Notes

1. **State-based tax takes priority** over product tax
2. **Fallback to product tax** if no state is provided
3. **Guest checkout supported** - tax calculated from address array
4. **Logged-in users** - tax calculated from saved address
5. **Zero-tax states** - Delaware, Montana, New Hampshire, Oregon (0%)

## Next Steps

After successful installation:
1. Test with different states
2. Verify tax calculations in orders
3. Check tax reporting
4. Update frontend to show state selection clearly
5. Add state tax information to invoices/receipts

## Support

For issues or questions:
1. Check `storage/logs/laravel.log`
2. Review the full documentation in `STATE_TAX_IMPLEMENTATION.md`
3. Verify all steps were completed in order
