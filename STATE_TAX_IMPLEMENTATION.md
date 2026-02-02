# State-Based Tax Implementation

## Overview
This implementation adds state-based tax calculation for US states. When customers add their address during checkout, the tax will be automatically calculated based on their state's tax rate.

## Changes Made

### 1. Database Migration
**File**: `database/migrations/2026_02_02_000000_add_tax_rate_to_states_table.php`

Added a `tax_rate` column to the `states` table to store the tax percentage for each state.

```sql
ALTER TABLE states ADD COLUMN tax_rate DECIMAL(5,2) DEFAULT 0 COMMENT 'State tax percentage';
```

### 2. State Tax Seeder
**File**: `database/seeders/UsStateTaxSeeder.php`

Created a seeder that populates all 50 US states with their respective tax rates:
- States with 0% tax: Delaware, Montana, New Hampshire, Oregon
- Highest tax: Louisiana (9.56%)
- Lowest tax (excluding 0%): Alaska (1.82%)

### 3. Model Updates
**File**: `app/Models/State.php`

Added `tax_rate` to the fillable array to allow mass assignment.

### 4. Tax Calculation Logic
**File**: `app/Http/Traits/CheckoutTrait.php`

#### New Method: `getStateTaxRate($request)`
This method retrieves the tax rate based on the customer's address. It checks in the following order:
1. Billing address ID (for logged-in users)
2. Billing address array (for guest checkout)
3. Shipping address ID (fallback)
4. Shipping address array (fallback)

#### Updated Method: `getTax($product_id, $subtotal, $request = null)`
Modified to prioritize state-based tax rates:
1. First, tries to get state tax rate from the customer's address
2. Falls back to product-specific tax if no state tax is found
3. Calculates tax amount based on subtotal

## How It Works

### For Logged-in Users
1. User selects a saved address (billing or shipping)
2. System retrieves the state_id from the selected address
3. Looks up the tax_rate for that state
4. Applies the state tax rate to all products in the cart

### For Guest Checkout
1. User enters their address information including state
2. System extracts state_id from the billing_address or shipping_address array
3. Looks up the tax_rate for that state
4. Applies the state tax rate to all products in the cart

### Fallback Behavior
If no state is provided or the state doesn't have a tax rate:
- System falls back to the product's assigned tax rate (existing behavior)
- This ensures backward compatibility

## Installation Steps

### 1. Run the Migration
```bash
php artisan migrate
```

This will add the `tax_rate` column to the `states` table.

### 2. Run the Seeder
```bash
php artisan db:seed --class=UsStateTaxSeeder
```

This will:
- Create the United States country record if it doesn't exist
- Insert or update all 50 US states with their tax rates

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## US State Tax Rates

| State | Tax Rate |
|-------|----------|
| Alabama | 9.29% |
| Alaska | 1.82% |
| Arizona | 8.38% |
| Arkansas | 9.45% |
| California | 8.85% |
| Colorado | 7.81% |
| Connecticut | 6.35% |
| Delaware | 0% |
| Florida | 7.00% |
| Georgia | 7.38% |
| Hawaii | 4.50% |
| Idaho | 6.03% |
| Illinois | 8.86% |
| Indiana | 7.00% |
| Iowa | 6.94% |
| Kansas | 8.65% |
| Kentucky | 6.00% |
| Louisiana | 9.56% |
| Maine | 5.50% |
| Maryland | 6.00% |
| Massachusetts | 6.25% |
| Michigan | 6.00% |
| Minnesota | 8.04% |
| Mississippi | 7.06% |
| Missouri | 8.39% |
| Montana | 0% |
| Nebraska | 6.97% |
| Nevada | 8.24% |
| New Hampshire | 0% |
| New Jersey | 6.60% |
| New Mexico | 7.62% |
| New York | 8.53% |
| North Carolina | 7.00% |
| North Dakota | 7.04% |
| Ohio | 7.24% |
| Oklahoma | 8.99% |
| Oregon | 0% |
| Pennsylvania | 6.34% |
| Rhode Island | 7.00% |
| South Carolina | 7.50% |
| South Dakota | 6.11% |
| Tennessee | 9.55% |
| Texas | 8.20% |
| Utah | 7.25% |
| Vermont | 6.36% |
| Virginia | 5.77% |
| Washington | 9.38% |
| West Virginia | 6.57% |
| Wisconsin | 5.70% |
| Wyoming | 5.44% |

## Testing

### Test Case 1: Logged-in User with Saved Address
1. Login as a user
2. Add products to cart
3. Go to checkout
4. Select a saved address with a US state
5. Verify that the tax is calculated based on the state's tax rate

### Test Case 2: Guest Checkout
1. Add products to cart
2. Go to checkout as guest
3. Enter billing address with a US state
4. Verify that the tax is calculated based on the state's tax rate

### Test Case 3: States with 0% Tax
1. Select an address in Delaware, Montana, New Hampshire, or Oregon
2. Verify that no tax is applied (0%)

### Test Case 4: Fallback to Product Tax
1. Select an address without a state or with a non-US state
2. Verify that the product's assigned tax rate is used

## API Request Example

### Calculate Checkout Request
```json
{
  "products": [
    {
      "product_id": 1,
      "variation_id": null,
      "quantity": 2
    }
  ],
  "payment_method": "stripe",
  "billing_address_id": 5,
  "shipping_address_id": 5
}
```

Or for guest checkout:
```json
{
  "products": [
    {
      "product_id": 1,
      "variation_id": null,
      "quantity": 2
    }
  ],
  "payment_method": "stripe",
  "billing_address": {
    "title": "Home",
    "street": "123 Main St",
    "city": "Los Angeles",
    "state_id": 5,
    "country_id": 1,
    "pincode": "90001"
  }
}
```

## Troubleshooting

### Issue: Tax not being calculated
**Solution**: 
- Ensure the state_id is being passed in the address
- Check that the state has a tax_rate set in the database
- Verify the migration and seeder ran successfully

### Issue: Wrong tax rate applied
**Solution**:
- Check the state_id in the address matches the correct state
- Verify the tax_rate in the states table is correct
- Clear all caches

### Issue: Database connection error during migration
**Solution**:
- Check your `.env` file for correct database credentials
- Ensure your database server is running
- Verify you have the necessary permissions

## Future Enhancements

1. **County/City Level Taxes**: Add support for local taxes in addition to state taxes
2. **Tax Exemptions**: Implement tax exemption logic for certain products or customers
3. **International Support**: Extend to support VAT and other international tax systems
4. **Admin Interface**: Create an admin panel to manage state tax rates
5. **Tax Reports**: Generate tax collection reports by state

## Notes

- The tax rates provided are approximate averages including state and local taxes
- Some states have varying local tax rates; these are averaged
- States with 0% tax (Delaware, Montana, New Hampshire, Oregon) have no statewide sales tax
- Alaska has a very low state tax but allows local jurisdictions to impose their own taxes

## Support

If you encounter any issues or need assistance, please check:
1. Laravel logs: `storage/logs/laravel.log`
2. Database connection settings in `.env`
3. Migration status: `php artisan migrate:status`
