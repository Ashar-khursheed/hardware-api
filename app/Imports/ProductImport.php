<?php

// namespace App\Imports;

// use Exception;
// use App\Enums\RoleEnum;
// use App\Models\Product;
// use App\Helpers\Helpers;
// use App\Models\Variation;
// use App\Enums\StockStatus;
// use App\Models\LicenseKey;
// use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\ToModel;
// use App\GraphQL\Exceptions\ExceptionHandler;
// use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithValidation;

// class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
// {
//     private $products = [];
//     private $translateFields = ['name','short_description','description','meta_title','meta_description','estimated_delivery_text','return_policy_text'];

//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */

//     public function rules(): array
//     {
//         return [
//             'name'  => ['string', 'max:255'],
//             'product_type' =>  ['required','in:physical,digital,external'],
//             'description' => ['string', 'min:10'],
//             'short_description' => ['nullable'],
//             'type' => ['required','in:simple,classified'],
//             'price' => ['required_if:type,==,simple'],
//             'stock_status' => ['required_if:type,==,simple', 'in:in_stock,out_of_stock'],
//             'quantity' => ['nullable','required_if:type,==,simple'],
//             'sku' => ['required_if:type,==,simple', 'unique:products,sku,NULL,id,deleted_at,NULL'],
//             'discount' => ['nullable','numeric','regex:/^([0-9]{1,2}){1}(\.[0-9]{1,2})?$/'],
//             'show_stock_quantity' => ['min:0', 'max:1'],
//             'is_featured' => ['min:0', 'max:1'],
//             'secure_checkout' => ['min:0', 'max:1'],
//             'safe_checkout' => ['min:0', 'max:1'],
//             'social_share' => ['min:0', 'max:1'],
//             'encourage_order' => ['min:0', 'max:1'],
//             'encourage_view' => ['min:0', 'max:1'],
//             'is_cod' => ['min:0', 'max:1'],
//             'is_return' => ['min:0', 'max:1'],
//             'is_free_shipping' => ['min:0', 'max:1'],
//             'is_changeable' => ['min:0', 'max:1'],
//             'is_sale_enable' => ['min:0', 'max:1'],
//             'is_external' => ['min:0', 'max:1'],
//             'external_url' => ['required_if:is_external,1'],
//             'watermark' => ['min:0', 'max:1'],
//             'watermark_position' =>  ['required_if:watermark,1'],
//             'is_licensable' => ['required_if:product_type,digital','min:0', 'max:1'],
//             'is_licensekey_auto' => ['required_if:is_licensable,1'],
//             'sale_starts_at' => ['nullable', 'date'],
//             'store_id' => ['nullable','exists:stores,id,deleted_at,NULL'],
//             'brand_id' => ['nullable','exists:brands,id,deleted_at,NULL'],
//             'sale_expired_at' => ['nullable','date', 'after:sale_starts_at'],
//             'separator' => ['nullable', 'in:new_line,double_new_line,comma,semicolon,pipe'],
//             'status' => ['required','min:0','max:1'],
//             'visible_time' => ['nullable','date'],
//             'variations' => ['required_if:type,==,classified'],
//         ];
//     }

//     public function customValidationMessages()
//     {
//         return [
//             'name.required' => __('validation.name_Required'),
//             'name.string' => __('validation.name_in_string'),
//             'name.max' => __('validation.name_not_exceed'),
//             'name.unique' => __('validation.name_already_taken'),

//             'description.required' => __('validation.description_field_required'),
//             'description.string' => __('validation.description_must_be_string'),
//             'description.min' => __('validation.description_length'),

//             'short_description.required' => __('validation.short_description_field_required'),

//             'type.required' => __('validation.type_field_required'),
//             'type.in' => __('validation.product_simple_classified'),

//             'price.required_if' => __('validation.simple_product_price_required'),
//             'price.numeric' => __('validation.price_must_be_number'),

//             'stock_status.required_if' => __('validation.simple_product_stock_status_required'),
//             'stock_status.in' => __('validation.valid_stock_status_required'),

//             'quantity.required_if' => __('validation.simple_product_quantity_required'),

//             'sku.required_if' => __('validation.simple_product_sku_required'),
//             'sku.unique' => __('validation.sku_is_already_taken'),

//             'discount.numeric' => __('validation.discount_must_be_number'),
//             'discount.regex' => __('validation.discount_must_be_correct_format'),

//             'show_stock_quantity.min' => __('validation.show_stock_quantity_min'),
//             'show_stock_quantity.max' => __('validation.show_stock_quantity_max'),

//             'is_featured.min' => __('validation.feature_field_min'),
//             'is_featured.max' => __('validation.feature_field_max'),

//             'secure_checkout.min' => __('validation.secure_checkout_min'),
//             'secure_checkout.max' => __('validation.secure_checkout_max'),

//             'safe_checkout.min' => __('validation.safe_checkout_field_min'),
//             'safe_checkout.max' => __('validation.safe_checkout_field_max'),

//             'social_share.min' => __('validation.social_share_field_min'),
//             'social_share.max' => __('validation.social_share_field_max'),

//             'encourage_order.min' => __('validation.encourage_order_field_min'),
//             'encourage_order.max' => __('validation.encourage_order_field_max'),

//             'encourage_view.min' => __('validation.encourage_view_field_min'),
//             'encourage_view.max' => __('validation.encourage_view_field_max'),

//             'is_cod.min' => __('validation.cod_field_min'),
//             'is_cod.max' => __('validation.cod_field_max'),

//             'is_return.min' => __('validation.return_field_min'),
//             'is_return.max' => __('validation.return_field_max'),

//             'is_free_shipping.min' => __('validation.free_shipping_field_min'),
//             'is_free_shipping.max' => __('validation.free_shipping_field_max'),

//             'is_changeable.min' => __('validation.changeable_field_min'),
//             'is_changeable.max' => __('validation.changeable_field_max'),

//             'is_sale_enable.min' => __('validation.sale_enable_field_min'),
//             'is_sale_enable.max' => __('validation.sale_enable_field_max'),

//             'sale_starts_at.date' => __('validation.sale_starts_must_be_valid'),

//             'store_id.exists' => __('validation.selected_store_invalid'),

//             'sale_expired_at.date' => __('validation.sale_expire_date_invalid'),
//             'sale_expired_at.after' => __('validation.sale_expire_date_after_start_date'),

//             'status.required' => __('validation.status_field_required'),
//             'status.min' => __('validation.status_field_min'),
//             'status.max' => __('validation.status_field_max'),

//             'visible_time.date' => __('validation.visible_time_invalid'),

//             'variations.required_if' => __('validation.variations_for_classified_required'),
//         ];
//     }

//     /**
//      * @param \Throwable $e
//      */
//     public function onError(\Throwable $e)
//     {
//         throw new ExceptionHandler($e->getMessage() , 422);
//     }

//     public function getImportedProducts()
//     {
//         return $this->products;
//     }

//     public function getMinPriceVariation($request, $price)
//     {
//         return head(array_filter(json_decode($request['variations']), function ($variation) use ($price) {
//             return $variation->price == $price;
//         }));
//     }

//     public function model(array $row)
//     {
//         DB::beginTransaction();
//         try {
//             $store_id = null;
//             $roleName = Helpers::getCurrentRoleName();
//             if ($roleName != RoleEnum::ADMIN) {
//                 $settings = Helpers::getSettings();
//                 if ($roleName == RoleEnum::VENDOR) {
//                     if (!Helpers::isMultiVendorEnable()) {
//                         throw new Exception(__('auth.multi_vendor_deactivated'), 403);
//                     }

//                     $store_id = Helpers::getCurrentVendorStoreId();
//                 }

//                 $isAutoApprove = $settings['activation']['product_auto_approve'];
//             }

//             if(isset($row['variations']) && !empty($row['variations']) && $row['type'] == 'classified') {
//                 $variations = json_decode($row['variations']);
//                 if (is_array($variations)) {
//                     if (count($variations)) {
//                         $price = min(array_column($variations, 'price'));
//                         $minPriceVariation = $this->getMinPriceVariation($row, $price);
//                         $discount = $minPriceVariation->discount;
//                         $sale_price = round($price  - (($price  * $discount)/100), 2);
//                         $quantity = max(array_column($variations, 'quantity'));
//                         $stock_status = StockStatus::OUT_OF_STOCK;

//                         if ($quantity > 0) {
//                             $stock_status = StockStatus::IN_STOCK;
//                         }
//                     }
//                 }
//             }

//             if (isset($row['quantity']) && !is_null($row['quantity'])) {
//                 $stock_status = StockStatus::OUT_OF_STOCK;
//                 if ($row['quantity'] > 0) {
//                     $stock_status = StockStatus::IN_STOCK;
//                 }
//             }

//             if (isset($row['discount']) && !is_null($row['discount'])) {
//                 $mrpPrice = $row['price'] ?? $price;
//                 $sale_price = round($mrpPrice - (($mrpPrice * $row['discount'])/100), 2);
//             }
//             $row = $this->filterRow($row);
//             $product = new Product([
//                 'name' => $row['name'],
//                 'product_type' => $row['product_type'],
//                 'short_description' => $row['short_description'],
//                 'description' => $row['description'],
//                 'type' => $row['type'],
//                 'unit' => $row['unit'],
//                 'quantity' => $row['quantity'] ?? $quantity,
//                 'weight' => $row['weight'],
//                 'price' => $price ?? $row['price'],
//                 'sale_price' => $sale_price ?? $row['sale_price'],
//                 'discount' => $discount ?? $row['discount'],
//                 'sku' => $row['sku'],
//                 'stock_status' => $stock_status ?? $row['stock_status'],
//                 'meta_title' => $row['meta_title'],
//                 'meta_description' => $row['meta_description'],
//                 'store_id' => $store_id ?? $row['store_id'],
//                 'is_free_shipping' => $row['is_free_shipping'],
//                 'is_external' => $row['is_external'],
//                 'external_button_text' => $row['external_button_text'],
//                 'external_url'=> $row['external_url'],
//                 'is_featured' => $row['is_featured'],
//                 'is_return' => $row['is_return'],
//                 'is_trending' => $row['is_trending'],
//                 'is_sale_enable' => $row['is_sale_enable'],
//                 'is_random_related_products' => $row['is_random_related_products'],
//                 'sale_starts_at' => $row['sale_starts_at'],
//                 'sale_expired_at' => $row['sale_expired_at'],
//                 'shipping_days' => $row['shipping_days'],
//                 'show_stock_quantity' => $row['show_stock_quantity'],
//                 'estimated_delivery_text' => $row['estimated_delivery_text'],
//                 'return_policy_text' => $row['return_policy_text'],
//                 'safe_checkout' => $row['safe_checkout'],
//                 'secure_checkout' => $row['secure_checkout'],
//                 'social_share' => $row['social_share'],
//                 'encourage_order' => $row['encourage_order'],
//                 'encourage_view' => $row['encourage_view'],
//                 'is_approved' => $isAutoApprove ?? $row['is_approved'],
//                 'status' => $row['status'],
//                 'is_licensable' => $row['is_licensable'],
//                 'preview_url' => $row['preview_url'],
//                 'watermark' => $row['watermark'],
//                 'watermark_position' => $row['watermark_position'],
//                 'wholesale_price_type' => $row['wholesale_price_type'],
//                 'separator' => $row['separator'],
//                 'preview_type' => $row['preview_type'],
//                 'is_licensekey_auto' => $row['is_licensekey_auto'],
//                 'external_details' => $row['external_details'],
//                 'publication_id' => $row['publication_id'],
//             ]);

//             $this->setTranslations($product, $row);
//             if (isset($row['product_thumbnail_url']) && !is_null($row['product_thumbnail_url'])) {
//                 $media = $product->addMediaFromUrl($row['product_thumbnail_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->product_thumbnail_id = $media->id;

//                 if ($row['watermark']) {
//                     if (isset($row['watermark_position']) && isset($row['watermark_image_id'])) {
//                         $media = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
//                         $media->save();
//                         $product->watermark_image_id = $media->id;
//                         $watermark_id = $product->watermark_image_id;
//                         $file_id =  $product->product_thumbnail_id;
//                         $position = $row['watermark_position'];
//                         $product->product_thumbnail_id = Helpers::createWatermarkImage($watermark_id, $file_id, $position);
//                         $product->save();
//                     }

//                     $product->watermark_image()->associate($product->product_thumbnail_id);
//                     $product->watermark_image;
//                 }
//             }

//             if (isset($row['product_meta_image_url']) && !is_null($row['product_meta_image_url'])) {
//                 $media = $product->addMediaFromUrl($row['product_meta_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->product_meta_image_id = $media->id;
//             }

//             if (isset($row['size_chart_image_url']) && !is_null($row['size_chart_image_url'])) {
//                 $media = $product->addMediaFromUrl($row['size_chart_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->size_chart_image_id = $media->id;
//             }

//             if (isset($row['preview_audio_file_url']) && !is_null($row['watermark_image_url'])) {
//                 $media = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->watermark_image_id = $media->id;
//             }

//             if (isset($row['preview_video_file_url']) && !is_null($row['preview_video_file_url'])) {
//                 $media = $product->addMediaFromUrl($row['preview_video_file_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->preview_video_file_id = $media->id;
//             }

//             $product->save();
//             if (isset($row['product_galleries_url']) && !is_null($row['product_galleries_url'])) {
//                 $product_galleries_urls = explode(',', $row['product_galleries_url']);
//                 $product_galleries_ids = [];
//                 if (is_array($product_galleries_ids) && is_array($product_galleries_ids)) {
//                     foreach ($product_galleries_urls as $product_galleries_url) {
//                         $media = $product->addMediaFromUrl($product_galleries_url)->toMediaCollection('attachment');
//                         $media->save();
//                         $product_galleries_ids[] = $media->id;
//                     }

//                     $gallery_ids = null;
//                     if ($row['watermark']) {
//                         if (isset($row['watermark_position']) && isset($row['watermark_image_id'])) {
//                             foreach ($product_galleries_ids as $gallery_id) {
//                                 $media = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
//                                 $media->save();
//                                 $product->watermark_image_id = $media->id;
//                                 $watermark_id = $media->id;
//                                 $position = $row['watermark_position'];
//                                 $gallery_ids[] = Helpers::createWatermarkImage($watermark_id, $gallery_id, $position);
//                             }
//                         }
//                     }

//                     $product->product_galleries()->attach($gallery_ids ?? $product_galleries_ids);
//                     $product->product_galleries;
//                 }
//             }

//             if (isset($row['digital_files_url']) && !is_null($row['digital_files_url'])) {
//                 $digital_file_urls = explode(',', $row['digital_files_url']);
//                 $digital_files_ids = [];
//                 foreach ($digital_file_urls as $digital_file_url) {
//                     $media = $product->addMediaFromUrl($digital_file_url)->toMediaCollection('attachment');
//                     $media->save();
//                     $digital_files_ids[] = $media->id;
//                 }

//                 $product->digital_files()->attach($digital_files_ids);
//                 $product->digital_files;
//             }

//             if (isset($row['categories']) && !is_null($row['categories'])) {
//                 $product->categories()->attach(explode(',', $row['categories']));
//                 $product->categories;
//             }

//             if (isset($row['tags']) && !is_null($row['tags'])) {
//                 $product->tags()->attach(explode(',', $row['tags']));
//                 $product->tags;
//             }

//             if (isset($row['attributes']) && !is_null($row['attributes'])) {
//                 $product->attributes()->attach(explode(',', $row['attributes']));
//                 $product->attributes;
//             }

//             if (isset($row['variations']) && !is_null($row['variations']) && $row['type'] == 'classified'){
//                 $variations = json_decode($row['variations']);
//                 if (is_array($variations)) {
//                     foreach ($variations as $variation) {
//                         $this->createProductVariation($product, $variation);
//                         $product->variations;
//                     }
//                 }
//             }

//             if (isset($request['authors_id']) && is_array($request['authors_id'])) {
//                 $product->authors()->attach($request['authors_id']);
//                 $product->authors;
//             }

//             if (
//                 ($row['type'] == 'simple' && $row['product_type'] == 'digital') &&
//                 ($row['is_licensekey_auto'] == '0' && $row['is_licensable'] == '1') &&
//                 (!empty(isset($row['license_keys'])) && !empty($row['separator']))
//             ) {
//                 $license_keys = Helpers::explodeLicenseKeys($row['separator'], $row['license_keys']);
//                 $this->updateOrCreateProductLicenseKeys($product, $license_keys);
//             }

//             if (isset($row['wholesale_prices'])) {
//                 $this->updateOrCreateWholesaleProduct($product, $row['wholesale_prices']);
//                 $product?->wholesales;
//             }

//             $this->products[] = [
//                 'id' => $product->id,
//                 'name' => $product->name,
//                 'product_type' => $product->product_type,
//                 'short_description' => $product->short_description,
//                 'description' => $product->description,
//                 'type' => $product->type,
//                 'unit' => $product->unit,
//                 'quantity' => $product->quantity,
//                 'weight' => $product->weight,
//                 'price' => $product->price,
//                 'sale_price' =>$product->price,
//                 'discount' => $product->discount,
//                 'sku' => $product->sku,
//                 'is_featured' => $product->is_featured,
//                 'shipping_days' => $product->shipping_days,
//                 'is_free_shipping' => $product->is_free_shipping,
//                 'is_sale_enable' => $product->is_sale_enable,
//                 'sale_starts_at' => $product->sale_starts_at,
//                 'sale_expired_at' => $product->sale_expired_at,
//                 'is_trending' => $product->is_trending,
//                 'stock_status' => $product->stock_status,
//                 'meta_title' => $product->meta_title,
//                 'is_return' => $product->is_return,
//                 'is_external' =>  $product->is_external,
//                 'external_url' => $product->external_url,
//                 'external_button_text' => $product->external_button_text,
//                 'meta_description' => $product->meta_description,
//                 'is_random_related_products' => $product->is_random_related_products,
//                 'estimated_delivery_text' => $product->estimated_delivery_text,
//                 'return_policy_text' => $product->return_policy_text,
//                 'safe_checkout' => $product->safe_checkout,
//                 'secure_checkout' => $product->secure_checkout,
//                 'social_share' => $product->social_share,
//                 'encourage_order' => $product->encourage_order,
//                 'encourage_view' => $product->encourage_view,
//                 'is_approved' => $product->is_approved,
//                 'status' => $product->status,
//                 'product_thumbnail' => $product->product_thumbnail,
//                 'product_meta_image' => $product->product_meta_image,
//                 'product_galleries' =>  $product->product_galleries,
//                 'categories' => $product->categories,
//                 'attributes' => $product->attributes,
//                 'tags' => $product->tags,
//                 'variations' => $product->variations,
//                 'external_details' => $product->external_details,
//                 'publication_id' => $product->publication_id,
//                 'authors_id' => $product->authors_id,
//             ];

//             DB::commit();
//             return $product;

//         } catch (Exception $e) {

//             DB::rollback();
//             throw new ExceptionHandler($e->getMessage(), $e->getCode());
//         }
//     }

//     public function getVariationSKU($sku)
//     {
//         $i = 1;
//         do {

//             $sku = $sku.str_repeat(' (COPY)', $i++);

//         } while (Variation::where('sku', $sku)->whereNull('deleted_at')->exists());

//         return $sku;
//     }

//     public function updateOrCreateWholesaleProduct($product, $wholesalePrices)
//     {
//         $wholesaleIds = [];
//         if (is_array($wholesalePrices)) {
//             foreach ($wholesalePrices as $wholesalePrice) {
//                 $wholesale = $product->wholesales()->updateOrCreate(['id' => $wholesalePrice['id'] ?? null], [
//                     'min_qty' => $wholesalePrice['min_qty'],
//                     'max_qty' => $wholesalePrice['max_qty'],
//                     'value' =>  $wholesalePrice['value'],
//                 ]);

//                 $wholesaleIds[] = $wholesale?->id;
//             }

//             $product->wholesales()->whereNotIn('id', $wholesaleIds)?->delete();
//             return $product;
//         }
//     }

//     public function updateOrCreateProductLicenseKeys($product, $license_keys, $variation_id = null)
//     {
//         $licenseKeyIds = [];
//         if (is_array($license_keys)) {
//             foreach ($license_keys as $license_key) {
//                 $licenseKey = $product->license_keys()->updateOrCreate(['license_key' => $license_key], [
//                     'license_key' => $this->getUniqueLicenseKey($license_key),
//                     'variation_id' => $variation_id
//                 ]);

//                 $licenseKeyIds[] = $licenseKey?->id;
//             }

//             $product->license_keys()->whereNotIn('id', $licenseKeyIds)?->delete();
//         }

//         return $product;
//     }

//     public function getUniqueLicenseKey($license_key)
//     {
//         $i = 0;
//         do {

//             $license_key = $license_key . str_repeat(' (COPY)', $i++);

//         } while (LicenseKey::where("license_key", $license_key)->whereNull('deleted_at')->exists());

//         return $license_key;
//     }

//     public function createProductVariation($product, $variation)
//     {
//         if (isset($variation->attribute_values)) {
//             $variation->sale_price = $variation->price;
//             if (isset($variation->discount)) {
//                 $variation->sale_price = round($variation->price - (($variation->price * $variation->discount)/100),2);
//             }

//             if (isset($variation->quantity)) {
//                 $variation->stock_status = StockStatus::OUT_OF_STOCK;
//                 if ($variation->quantity > 0) {
//                     $variation->stock_status = StockStatus::IN_STOCK;
//                 }
//             }
//             $variation = $this->filterRow($variation);
//             $variationData = $product->variations()->create([
//                 'name' => $variation['name'],
//                 'price' => $variation['price'],
//                 'quantity' => $variation['quantity'],
//                 'sku'  =>  $this->getVariationSKU($variation['sku']),
//                 'sale_price' => $variation['sale_price'],
//                 'discount' => $variation['discount'] ?? null,
//                 'stock_status' => $variation['stock_status'],
//                 'status' => $variation['status'],
//                 'separator' => $variation['separator'] ?? null,
//                 'is_licensable' => $variation['is_licensable'],
//                 'is_licensekey_auto' => $variation['is_licensekey_auto'],
//                 'product_id' => $product->id
//             ]);

//             $this->setTranslations($variationData, $variation);
//             if (isset($variation['variation_image_url'])) {
//                 $media = $variationData->addMediaFromUrl($variation['variation_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $variationData->variation_image_id = $media->id;
//                 $variationData->save();
//             }

//             if (isset($variation['variation_galleries_url']) && !is_null($variation['variation_galleries_url'])) {
//                 $variation_galleries_urls = $variation['variation_galleries_url'];
//                 $variation_galleries_ids = [];
//                 if (is_array($variation_galleries_ids) && is_array($variation_galleries_urls)) {
//                     foreach ($variation_galleries_urls as $variation_galleries_url) {
//                         $media = $product->addMediaFromUrl($variation_galleries_url)->toMediaCollection('attachment');
//                         $media->save();
//                         $variation_galleries_ids[] = $media->id;
//                     }

//                     $variation->variation_galleries()->attach($variation_galleries_ids);
//                     $variation->variation_galleries;
//                 }
//             }

//             if ($variation['is_licensable'] && !$variation['is_licensekey_auto'] && $variation['license_key']) {
//                 $this->updateOrCreateProductLicenseKeys($product, $variation['license_key'], $variationData?->id);
//             }
//             $variationData->attribute_values()->attach($variation['attribute_values']);
//         }
//     }

//     function filterRow($row)
//     {
//         foreach ($row as $key => $value) {

//             $lastUnderscorePos = strrpos($key, "_");
//             $separatedKeys = [
//                 1 => substr($key, 0, $lastUnderscorePos),
//                 2 => substr($key, $lastUnderscorePos + 1),
//             ];
//             if(in_array(head($separatedKeys),$this->translateFields)) {
//                 $rows[head($separatedKeys)][last($separatedKeys)] = $value;
//             }else{
//                 $rows[$key] = $value;
//             }
//         }
//         return $rows;
//     }

//     function setTranslations($data, $row)
//     {
//         $locale = app()->getLocale();
//         foreach ($row as $key => $value) {
//             if ($data->isTranslatableAttribute($key)) {
//                 $translations = is_array($value) ? $value : [$locale => $value];
//                 $data->setTranslations($key, $translations);
//             }
//         }
//         return $data->save();
//     }
// }


// namespace App\Imports;

// use Exception;
// use App\Enums\RoleEnum;
// use App\Models\Product;
// use App\Helpers\Helpers;
// use App\Models\Variation;
// use App\Enums\StockStatus;
// use App\Models\LicenseKey;
// use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel\Concerns\ToModel;
// use App\GraphQL\Exceptions\ExceptionHandler;
// use Maatwebsite\Excel\Concerns\SkipsOnError;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithValidation;
// use Illuminate\Validation\Rule;

// class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
// {
//     private $products = [];
//     private $translateFields = ['name','short_description','description','meta_title','meta_description','estimated_delivery_text','return_policy_text'];

//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */

//     // public function rules(): array
//     // {
//     //     return [
//     //         'name'  => ['string', 'max:255'],
//     //         'product_type' =>  ['required','in:physical,digital,external'],
//     //         'description' => ['string', 'min:10'],
//     //         'short_description' => ['nullable'],
//     //         'type' => ['required','in:simple,classified'],
//     //         'price' => ['required_if:type,==,simple'],
//     //         'stock_status' => ['required_if:type,==,simple', 'in:in_stock,out_of_stock'],
//     //         'quantity' => ['nullable','required_if:type,==,simple'],
//     //         'sku' => ['required_if:type,==,simple'],
//     //         'discount' => ['nullable','numeric','regex:/^([0-9]{1,2}){1}(\.[0-9]{1,2})?$/'],
//     //         'show_stock_quantity' => ['min:0', 'max:1'],
//     //         'is_featured' => ['min:0', 'max:1'],
//     //         'secure_checkout' => ['min:0', 'max:1'],
//     //         'safe_checkout' => ['min:0', 'max:1'],
//     //         'social_share' => ['min:0', 'max:1'],
//     //         'encourage_order' => ['min:0', 'max:1'],
//     //         'encourage_view' => ['min:0', 'max:1'],
//     //         'is_cod' => ['min:0', 'max:1'],
//     //         'is_return' => ['min:0', 'max:1'],
//     //         'is_free_shipping' => ['min:0', 'max:1'],
//     //         'is_changeable' => ['min:0', 'max:1'],
//     //         'is_sale_enable' => ['min:0', 'max:1'],
//     //         'is_external' => ['min:0', 'max:1'],
//     //         'external_url' => ['required_if:is_external,1'],
//     //         'watermark' => ['min:0', 'max:1'],
//     //         'watermark_position' =>  ['required_if:watermark,1'],
//     //         'is_licensable' => ['required_if:product_type,digital','min:0', 'max:1'],
//     //         'is_licensekey_auto' => ['required_if:is_licensable,1'],
//     //         'sale_starts_at' => ['nullable', 'date'],
//     //         'store_id' => ['nullable','exists:stores,id,deleted_at,NULL'],
//     //         'brand_id' => ['nullable','exists:brands,id,deleted_at,NULL'],
//     //         'sale_expired_at' => ['nullable','date', 'after:sale_starts_at'],
//     //         'separator' => ['nullable', 'in:new_line,double_new_line,comma,semicolon,pipe'],
//     //         'status' => ['required','min:0','max:1'],
//     //         'visible_time' => ['nullable','date'],
//     //         'variations' => ['required_if:type,==,classified'],
//     //     ];
//     // }
    
// public function rules(): array
// {
//     return [
//         'name'  => ['nullable', 'string', 'max:255'],
//         'product_type' =>  ['nullable','in:physical,digital,external'],
//         'description' => ['nullable', 'string', 'min:10'],
//         'short_description' => ['nullable'],
//         'type' => ['nullable','in:simple,classified'],
//         'price' => ['nullable', 'numeric'],
//         'stock_status' => ['nullable', 'in:in_stock,out_of_stock'],
//         'quantity' => ['nullable', 'numeric', 'min:0'],
//         'sku' => ['nullable', 'string'],
//         'discount' => ['nullable','numeric','regex:/^([0-9]{1,2}){1}(\.[0-9]{1,2})?$/'],
//         'show_stock_quantity' => ['nullable', 'boolean'],
//         'is_featured' => ['nullable', 'boolean'],
//         'secure_checkout' => ['nullable', 'boolean'],
//         'safe_checkout' => ['nullable', 'boolean'],
//         'social_share' => ['nullable', 'boolean'],
//         'encourage_order' => ['nullable', 'boolean'],
//         'encourage_view' => ['nullable', 'boolean'],
//         'is_cod' => ['nullable', 'boolean'],
//         'is_return' => ['nullable', 'boolean'],
//         'is_free_shipping' => ['nullable', 'boolean'],
//         'is_changeable' => ['nullable', 'boolean'],
//         'is_sale_enable' => ['nullable', 'boolean'],
//         'is_external' => ['nullable', 'boolean'],
//         'external_url' => ['nullable', 'url'],
//         'watermark' => ['nullable', 'boolean'],
//         'watermark_position' => ['nullable', 'string'],
//         'is_licensable' => ['nullable', 'boolean'],
//         'is_licensekey_auto' => ['nullable', 'boolean'],
//         'sale_starts_at' => ['nullable', 'date'],
//         'store_id' => ['nullable','exists:stores,id,deleted_at,NULL'],
//         'brand_id' => ['nullable','exists:brands,id,deleted_at,NULL'],
//         'sale_expired_at' => ['nullable','date', 'after_or_equal:sale_starts_at'],
//         'separator' => ['nullable', 'in:new_line,double_new_line,comma,semicolon,pipe'],
//         'status' => ['nullable', 'boolean'],
//         'visible_time' => ['nullable','date'],
//         'variations' => ['nullable', 'json'],
//     ];
// }
//     public function customValidationMessages()
//     {
//         return [
//             'name.required' => __('validation.name_Required'),
//             'name.string' => __('validation.name_in_string'),
//             'name.max' => __('validation.name_not_exceed'),
//             'name.unique' => __('validation.name_already_taken'),

//             'description.required' => __('validation.description_field_required'),
//             'description.string' => __('validation.description_must_be_string'),
//             'description.min' => __('validation.description_length'),

//             'short_description.required' => __('validation.short_description_field_required'),

//             'type.required' => __('validation.type_field_required'),
//             'type.in' => __('validation.product_simple_classified'),

//             'price.required_if' => __('validation.simple_product_price_required'),
//             'price.numeric' => __('validation.price_must_be_number'),

//             'stock_status.required_if' => __('validation.simple_product_stock_status_required'),
//             'stock_status.in' => __('validation.valid_stock_status_required'),

//             'quantity.required_if' => __('validation.simple_product_quantity_required'),

//             'sku.required_if' => __('validation.simple_product_sku_required'),
//             'sku.unique' => __('validation.sku_is_already_taken'),

//             'discount.numeric' => __('validation.discount_must_be_number'),
//             'discount.regex' => __('validation.discount_must_be_correct_format'),

//             'show_stock_quantity.min' => __('validation.show_stock_quantity_min'),
//             'show_stock_quantity.max' => __('validation.show_stock_quantity_max'),

//             'is_featured.min' => __('validation.feature_field_min'),
//             'is_featured.max' => __('validation.feature_field_max'),

//             'secure_checkout.min' => __('validation.secure_checkout_min'),
//             'secure_checkout.max' => __('validation.secure_checkout_max'),

//             'safe_checkout.min' => __('validation.safe_checkout_field_min'),
//             'safe_checkout.max' => __('validation.safe_checkout_field_max'),

//             'social_share.min' => __('validation.social_share_field_min'),
//             'social_share.max' => __('validation.social_share_field_max'),

//             'encourage_order.min' => __('validation.encourage_order_field_min'),
//             'encourage_order.max' => __('validation.encourage_order_field_max'),

//             'encourage_view.min' => __('validation.encourage_view_field_min'),
//             'encourage_view.max' => __('validation.encourage_view_field_max'),

//             'is_cod.min' => __('validation.cod_field_min'),
//             'is_cod.max' => __('validation.cod_field_max'),

//             'is_return.min' => __('validation.return_field_min'),
//             'is_return.max' => __('validation.return_field_max'),

//             'is_free_shipping.min' => __('validation.free_shipping_field_min'),
//             'is_free_shipping.max' => __('validation.free_shipping_field_max'),

//             'is_changeable.min' => __('validation.changeable_field_min'),
//             'is_changeable.max' => __('validation.changeable_field_max'),

//             'is_sale_enable.min' => __('validation.sale_enable_field_min'),
//             'is_sale_enable.max' => __('validation.sale_enable_field_max'),

//             'sale_starts_at.date' => __('validation.sale_starts_must_be_valid'),

//             'store_id.exists' => __('validation.selected_store_invalid'),

//             'sale_expired_at.date' => __('validation.sale_expire_date_invalid'),
//             'sale_expired_at.after' => __('validation.sale_expire_date_after_start_date'),

//             'status.required' => __('validation.status_field_required'),
//             'status.min' => __('validation.status_field_min'),
//             'status.max' => __('validation.status_field_max'),

//             'visible_time.date' => __('validation.visible_time_invalid'),

//             'variations.required_if' => __('validation.variations_for_classified_required'),
//         ];
//     }

//     /**
//      * @param \Throwable $e
//      */
//     public function onError(\Throwable $e)
//     {
//         throw new ExceptionHandler($e->getMessage() , 422);
//     }

//     public function getImportedProducts()
//     {
//         return $this->products;
//     }

//     public function getMinPriceVariation($request, $price)
//     {
//         return head(array_filter(json_decode($request['variations']), function ($variation) use ($price) {
//             return $variation->price == $price;
//         }));
//     }

//     public function model(array $row)
//     {
//         DB::beginTransaction();
//         try {
//             $store_id = null;
//             $roleName = Helpers::getCurrentRoleName();
//             if ($roleName != RoleEnum::ADMIN) {
//                 $settings = Helpers::getSettings();
//                 if ($roleName == RoleEnum::VENDOR) {
//                     if (!Helpers::isMultiVendorEnable()) {
//                         throw new Exception(__('auth.multi_vendor_deactivated'), 403);
//                     }

//                     $store_id = Helpers::getCurrentVendorStoreId();
//                 }

//                 $isAutoApprove = $settings['activation']['product_auto_approve'];
//             }

//             // Check if product exists by SKU for update
//             $existingProduct = null;
//             if (isset($row['sku']) && !empty($row['sku'])) {
//                 $existingProduct = Product::where('sku', $row['sku'])
//                     ->whereNull('deleted_at')
//                     ->first();
//             }

//             if(isset($row['variations']) && !empty($row['variations']) && $row['type'] == 'classified') {
//                 $variations = json_decode($row['variations']);
//                 if (is_array($variations)) {
//                     if (count($variations)) {
//                         $price = min(array_column($variations, 'price'));
//                         $minPriceVariation = $this->getMinPriceVariation($row, $price);
//                         $discount = $minPriceVariation->discount;
//                         $sale_price = round($price  - (($price  * $discount)/100), 2);
//                         $quantity = max(array_column($variations, 'quantity'));
//                         $stock_status = StockStatus::OUT_OF_STOCK;

//                         if ($quantity > 0) {
//                             $stock_status = StockStatus::IN_STOCK;
//                         }
//                     }
//                 }
//             }

//             if (isset($row['quantity']) && !is_null($row['quantity'])) {
//                 $stock_status = StockStatus::OUT_OF_STOCK;
//                 if ($row['quantity'] > 0) {
//                     $stock_status = StockStatus::IN_STOCK;
//                 }
//             }

//             if (isset($row['discount']) && !is_null($row['discount'])) {
//                 $mrpPrice = $row['price'] ?? $price;
//                 $sale_price = round($mrpPrice - (($mrpPrice * $row['discount'])/100), 2);
//             }
            
//             $row = $this->filterRow($row);
            
//             // Update existing product or create new one
//              // Replace the product creation/update sections with this:

//             // Update existing product or create new one
//             if ($existingProduct) {
//                 $product = $existingProduct;
//                 $product->update([
//                     'name' => $row['name'] ?? $product->name,
//                     'product_type' => $row['product_type'] ?? $product->product_type,
//                     'short_description' => $row['short_description'] ?? $product->short_description,
//                     'description' => $row['description'] ?? $product->description,
//                     'type' => $row['type'] ?? $product->type,
//                     'unit' => $row['unit'] ?? $product->unit,
//                     'quantity' => $row['quantity'] ?? $quantity ?? $product->quantity,
//                     'weight' => $row['weight'] ?? $product->weight,
//                     'price' => $price ?? $row['price'] ?? $product->price,
//                     'sale_price' => $sale_price ?? $row['sale_price'] ?? $product->sale_price,
//                     'discount' => $discount ?? $row['discount'] ?? $product->discount,
//                     'stock_status' => $stock_status ?? $row['stock_status'] ?? $product->stock_status,
//                     'meta_title' => $row['meta_title'] ?? $product->meta_title,
//                     'meta_description' => $row['meta_description'] ?? $product->meta_description,
//                     'store_id' => $store_id ?? $row['store_id'] ?? $product->store_id,
//                     'is_free_shipping' => $row['is_free_shipping'] ?? $product->is_free_shipping,
//                     'is_external' => $row['is_external'] ?? $product->is_external,
//                     'external_button_text' => $row['external_button_text'] ?? $product->external_button_text,
//                     'external_url'=> $row['external_url'] ?? $product->external_url,
//                     'is_featured' => $row['is_featured'] ?? $product->is_featured,
//                     'is_return' => $row['is_return'] ?? $product->is_return,
//                     'is_trending' => $row['is_trending'] ?? $product->is_trending,
//                     'is_sale_enable' => $row['is_sale_enable'] ?? $product->is_sale_enable,
//                     'is_random_related_products' => $row['is_random_related_products'] ?? $product->is_random_related_products,
//                     'sale_starts_at' => $row['sale_starts_at'] ?? $product->sale_starts_at,
//                     'sale_expired_at' => $row['sale_expired_at'] ?? $product->sale_expired_at,
//                     'shipping_days' => $row['shipping_days'] ?? $product->shipping_days,
//                     'show_stock_quantity' => $row['show_stock_quantity'] ?? $product->show_stock_quantity,
//                     'estimated_delivery_text' => $row['estimated_delivery_text'] ?? $product->estimated_delivery_text,
//                     'return_policy_text' => $row['return_policy_text'] ?? $product->return_policy_text,
//                     'safe_checkout' => $row['safe_checkout'] ?? $product->safe_checkout,
//                     'secure_checkout' => $row['secure_checkout'] ?? $product->secure_checkout,
//                     'social_share' => $row['social_share'] ?? $product->social_share,
//                     'encourage_order' => $row['encourage_order'] ?? $product->encourage_order,
//                     'encourage_view' => $row['encourage_view'] ?? $product->encourage_view,
//                     'is_approved' => $isAutoApprove ?? $row['is_approved'] ?? $product->is_approved,
//                     'status' => $row['status'] ?? $product->status,
//                     'is_licensable' => $row['is_licensable'] ?? $product->is_licensable,
//                     'preview_url' => $row['preview_url'] ?? $product->preview_url,
//                     'watermark' => $row['watermark'] ?? $product->watermark,
//                     'watermark_position' => $row['watermark_position'] ?? $product->watermark_position,
//                     'wholesale_price_type' => $row['wholesale_price_type'] ?? $product->wholesale_price_type,
//                     'separator' => $row['separator'] ?? $product->separator,
//                     'preview_type' => $row['preview_type'] ?? $product->preview_type,
//                     'is_licensekey_auto' => $row['is_licensekey_auto'] ?? $product->is_licensekey_auto,
//                     // 'external_details' => $row['external_details'] ?? $product->external_details,
//                     'external_details' => is_string($row['external_details'] ?? null)
//                     ? json_decode($row['external_details'], true)
//                     : ($row['external_details'] ?? $product->external_details),
//                     'publication_id' => $row['publication_id'] ?? $product->publication_id,
//                 ]);
//             } else {
//                 // Create new product with defaults
//                 $product = new Product([
//                     'name' => $row['name'] ?? 'Untitled Product',
//                     'product_type' => $row['product_type'] ?? 'physical',
//                     'short_description' => $row['short_description'] ?? null,
//                     'description' => $row['description'] ?? 'No description provided',
//                     'type' => $row['type'] ?? 'simple',
//                     'unit' => $row['unit'] ?? null,
//                     'quantity' => $row['quantity'] ?? $quantity ?? 0,
//                     'weight' => $row['weight'] ?? null,
//                     'price' => $price ?? $row['price'] ?? 0,
//                     'sale_price' => $sale_price ?? $row['sale_price'] ?? null,
//                     'discount' => $discount ?? $row['discount'] ?? 0,
//                     'sku' => $row['sku'] ?? 'SKU-' . uniqid(),
//                     'stock_status' => $stock_status ?? $row['stock_status'] ?? 'out_of_stock',
//                     'meta_title' => $row['meta_title'] ?? null,
//                     'meta_description' => $row['meta_description'] ?? null,
//                     'store_id' => $store_id ?? $row['store_id'] ?? null,
//                     'is_free_shipping' => $row['is_free_shipping'] ?? 0,
//                     'is_external' => $row['is_external'] ?? 0,
//                     'external_button_text' => $row['external_button_text'] ?? null,
//                     'external_url'=> $row['external_url'] ?? null,
//                     'is_featured' => $row['is_featured'] ?? 0,
//                     'is_return' => $row['is_return'] ?? 0,
//                     'is_trending' => $row['is_trending'] ?? 0,
//                     'is_sale_enable' => $row['is_sale_enable'] ?? 0,
//                     'is_random_related_products' => $row['is_random_related_products'] ?? 0,
//                     'sale_starts_at' => $row['sale_starts_at'] ?? null,
//                     'sale_expired_at' => $row['sale_expired_at'] ?? null,
//                     'shipping_days' => $row['shipping_days'] ?? null,
//                     'show_stock_quantity' => $row['show_stock_quantity'] ?? 0,
//                     'estimated_delivery_text' => $row['estimated_delivery_text'] ?? null,
//                     'return_policy_text' => $row['return_policy_text'] ?? null,
//                     'safe_checkout' => $row['safe_checkout'] ?? 0,
//                     'secure_checkout' => $row['secure_checkout'] ?? 0,
//                     'social_share' => $row['social_share'] ?? 0,
//                     'encourage_order' => $row['encourage_order'] ?? 0,
//                     'encourage_view' => $row['encourage_view'] ?? 0,
//                     'is_approved' => $isAutoApprove ?? $row['is_approved'] ?? 1,
//                     'status' => $row['status'] ?? 1,
//                     'is_licensable' => $row['is_licensable'] ?? 0,
//                     'preview_url' => $row['preview_url'] ?? null,
//                     'watermark' => $row['watermark'] ?? 0,
//                     'watermark_position' => $row['watermark_position'] ?? null,
//                     'wholesale_price_type' => $row['wholesale_price_type'] ?? null,
//                     'separator' => $row['separator'] ?? null,
//                     'preview_type' => $row['preview_type'] ?? null,
//                     'is_licensekey_auto' => $row['is_licensekey_auto'] ?? 0,
//                     'external_details' => $row['external_details'] ?? null,
//                     'publication_id' => $row['publication_id'] ?? null,
//                 ]);
//             }

//             $this->setTranslations($product, $row);

//             // Handle media files (same logic for both create and update)
//             if (isset($row['product_thumbnail_url']) && !is_null($row['product_thumbnail_url'])) {
//                 $media = $product->addMediaFromUrl($row['product_thumbnail_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->product_thumbnail_id = $media->id;

//                 if ($row['watermark']) {
//                     if (isset($row['watermark_position']) && isset($row['watermark_image_id'])) {
//                         $media = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
//                         $media->save();
//                         $product->watermark_image_id = $media->id;
//                         $watermark_id = $product->watermark_image_id;
//                         $file_id =  $product->product_thumbnail_id;
//                         $position = $row['watermark_position'];
//                         $product->product_thumbnail_id = Helpers::createWatermarkImage($watermark_id, $file_id, $position);
//                         $product->save();
//                     }

//                     $product->watermark_image()->associate($product->product_thumbnail_id);
//                     $product->watermark_image;
//                 }
//             }

//             if (isset($row['product_meta_image_url']) && !is_null($row['product_meta_image_url'])) {
//                 $media = $product->addMediaFromUrl($row['product_meta_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->product_meta_image_id = $media->id;
//             }

//             if (isset($row['size_chart_image_url']) && !is_null($row['size_chart_image_url'])) {
//                 $media = $product->addMediaFromUrl($row['size_chart_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->size_chart_image_id = $media->id;
//             }

//             if (isset($row['preview_audio_file_url']) && !is_null($row['watermark_image_url'])) {
//                 $media = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->watermark_image_id = $media->id;
//             }

//             if (isset($row['preview_video_file_url']) && !is_null($row['preview_video_file_url'])) {
//                 $media = $product->addMediaFromUrl($row['preview_video_file_url'])->toMediaCollection('attachment');
//                 $media->save();
//                 $product->preview_video_file_id = $media->id;
//             }

//             $product->save();
            
//             // Handle galleries
//             if (isset($row['product_galleries_url']) && !is_null($row['product_galleries_url'])) {
//                 $product_galleries_urls = explode(',', $row['product_galleries_url']);
//                 $product_galleries_ids = [];
//                 if (is_array($product_galleries_ids) && is_array($product_galleries_ids)) {
//                     foreach ($product_galleries_urls as $product_galleries_url) {
//                         $media = $product->addMediaFromUrl($product_galleries_url)->toMediaCollection('attachment');
//                         $media->save();
//                         $product_galleries_ids[] = $media->id;
//                     }

//                     $gallery_ids = null;
//                     if ($row['watermark']) {
//                         if (isset($row['watermark_position']) && isset($row['watermark_image_id'])) {
//                             foreach ($product_galleries_ids as $gallery_id) {
//                                 $media = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
//                                 $media->save();
//                                 $product->watermark_image_id = $media->id;
//                                 $watermark_id = $media->id;
//                                 $position = $row['watermark_position'];
//                                 $gallery_ids[] = Helpers::createWatermarkImage($watermark_id, $gallery_id, $position);
//                             }
//                         }
//                     }

//                     // For updates, sync instead of attach to replace existing galleries
//                     if ($existingProduct) {
//                         $product->product_galleries()->sync($gallery_ids ?? $product_galleries_ids);
//                     } else {
//                         $product->product_galleries()->attach($gallery_ids ?? $product_galleries_ids);
//                     }
//                     $product->product_galleries;
//                 }
//             }

//             // Handle digital files
//             if (isset($row['digital_files_url']) && !is_null($row['digital_files_url'])) {
//                 $digital_file_urls = explode(',', $row['digital_files_url']);
//                 $digital_files_ids = [];
//                 foreach ($digital_file_urls as $digital_file_url) {
//                     $media = $product->addMediaFromUrl($digital_file_url)->toMediaCollection('attachment');
//                     $media->save();
//                     $digital_files_ids[] = $media->id;
//                 }

//                 // For updates, sync instead of attach to replace existing files
//                 if ($existingProduct) {
//                     $product->digital_files()->sync($digital_files_ids);
//                 } else {
//                     $product->digital_files()->attach($digital_files_ids);
//                 }
//                 $product->digital_files;
//             }

//             // Handle categories
//             if (isset($row['categories']) && !is_null($row['categories'])) {
//                 if ($existingProduct) {
//                     $product->categories()->sync(explode(',', $row['categories']));
//                 } else {
//                     $product->categories()->attach(explode(',', $row['categories']));
//                 }
//                 $product->categories;
//             }

//             // Handle tags
//             if (isset($row['tags']) && !is_null($row['tags'])) {
//                 if ($existingProduct) {
//                     $product->tags()->sync(explode(',', $row['tags']));
//                 } else {
//                     $product->tags()->attach(explode(',', $row['tags']));
//                 }
//                 $product->tags;
//             }

//             // Handle attributes
//             if (isset($row['attributes']) && !is_null($row['attributes'])) {
//                 if ($existingProduct) {
//                     $product->attributes()->sync(explode(',', $row['attributes']));
//                 } else {
//                     $product->attributes()->attach(explode(',', $row['attributes']));
//                 }
//                 $product->attributes;
//             }

//             // Handle variations
//             if (isset($row['variations']) && !is_null($row['variations']) && $row['type'] == 'classified'){
//                 $variations = json_decode($row['variations']);
//                 if (is_array($variations)) {
//                     // For updates, delete existing variations first
//                     if ($existingProduct) {
//                         $product->variations()->delete();
//                     }
                    
//                     foreach ($variations as $variation) {
//                         $this->createProductVariation($product, $variation);
//                         $product->variations;
//                     }
//                 }
//             }

//             if (isset($request['authors_id']) && is_array($request['authors_id'])) {
//                 if ($existingProduct) {
//                     $product->authors()->sync($request['authors_id']);
//                 } else {
//                     $product->authors()->attach($request['authors_id']);
//                 }
//                 $product->authors;
//             }

//             if (
//                 ($row['type'] == 'simple' && $row['product_type'] == 'digital') &&
//                 ($row['is_licensekey_auto'] == '0' && $row['is_licensable'] == '1') &&
//                 (!empty(isset($row['license_keys'])) && !empty($row['separator']))
//             ) {
//                 $license_keys = Helpers::explodeLicenseKeys($row['separator'], $row['license_keys']);
//                 $this->updateOrCreateProductLicenseKeys($product, $license_keys);
//             }

//             if (isset($row['wholesale_prices'])) {
//                 $this->updateOrCreateWholesaleProduct($product, $row['wholesale_prices']);
//                 $product?->wholesales;
//             }

//             $this->products[] = [
//                 'id' => $product->id,
//                 'name' => $product->name,
//                 'product_type' => $product->product_type,
//                 'short_description' => $product->short_description,
//                 'description' => $product->description,
//                 'type' => $product->type,
//                 'unit' => $product->unit,
//                 'quantity' => $product->quantity,
//                 'weight' => $product->weight,
//                 'price' => $product->price,
//                 'sale_price' =>$product->price,
//                 'discount' => $product->discount,
//                 'sku' => $product->sku,
//                 'is_featured' => $product->is_featured,
//                 'shipping_days' => $product->shipping_days,
//                 'is_free_shipping' => $product->is_free_shipping,
//                 'is_sale_enable' => $product->is_sale_enable,
//                 'sale_starts_at' => $product->sale_starts_at,
//                 'sale_expired_at' => $product->sale_expired_at,
//                 'is_trending' => $product->is_trending,
//                 'stock_status' => $product->stock_status,
//                 'meta_title' => $product->meta_title,
//                 'is_return' => $product->is_return,
//                 'is_external' =>  $product->is_external,
//                 'external_url' => $product->external_url,
//                 'external_button_text' => $product->external_button_text,
//                 'meta_description' => $product->meta_description,
//                 'is_random_related_products' => $product->is_random_related_products,
//                 'estimated_delivery_text' => $product->estimated_delivery_text,
//                 'return_policy_text' => $product->return_policy_text,
//                 'safe_checkout' => $product->safe_checkout,
//                 'secure_checkout' => $product->secure_checkout,
//                 'social_share' => $product->social_share,
//                 'encourage_order' => $product->encourage_order,
//                 'encourage_view' => $product->encourage_view,
//                 'is_approved' => $product->is_approved,
//                 'status' => $product->status,
//                 'product_thumbnail' => $product->product_thumbnail,
//                 'product_meta_image' => $product->product_meta_image,
//                 'product_galleries' =>  $product->product_galleries,
//                 'categories' => $product->categories,
//                 'attributes' => $product->attributes,
//                 'tags' => $product->tags,
//                 'variations' => $product->variations,
//                 'external_details' => $product->external_details,
//                 'publication_id' => $product->publication_id,
//                 'authors_id' => $product->authors_id,
//             ];

//             DB::commit();
//             return $product;

//         } catch (Exception $e) {

//             DB::rollback();
//             throw new ExceptionHandler($e->getMessage(), $e->getCode());
//         }
//     }

//     public function getVariationSKU($sku)
//     {
//         $i = 1;
//         do {

//             $sku = $sku.str_repeat(' (COPY)', $i++);

//         } while (Variation::where('sku', $sku)->whereNull('deleted_at')->exists());

//         return $sku;
//     }

//     public function updateOrCreateWholesaleProduct($product, $wholesalePrices)
//     {
//         $wholesaleIds = [];
//         if (is_array($wholesalePrices)) {
//             foreach ($wholesalePrices as $wholesalePrice) {
//                 $wholesale = $product->wholesales()->updateOrCreate(['id' => $wholesalePrice['id'] ?? null], [
//                     'min_qty' => $wholesalePrice['min_qty'],
//                     'max_qty' => $wholesalePrice['max_qty'],
//                     'value' =>  $wholesalePrice['value'],
//                 ]);

//                 $wholesaleIds[] = $wholesale?->id;
//             }

//             $product->wholesales()->whereNotIn('id', $wholesaleIds)?->delete();
//             return $product;
//         }
//     }

//     public function updateOrCreateProductLicenseKeys($product, $license_keys, $variation_id = null)
//     {
//         $licenseKeyIds = [];
//         if (is_array($license_keys)) {
//             foreach ($license_keys as $license_key) {
//                 $licenseKey = $product->license_keys()->updateOrCreate(['license_key' => $license_key], [
//                     'license_key' => $this->getUniqueLicenseKey($license_key),
//                     'variation_id' => $variation_id,
//                     'status' => 1,
//                 ]);

//                 $licenseKeyIds[] = $licenseKey?->id;
//             }

//             $product->license_keys()->whereNotIn('id', $licenseKeyIds)->where('variation_id', $variation_id)?->delete();
//             return $product;
//         }
//     }

//     public function getUniqueLicenseKey($license_key)
//     {
//         $i = 1;
//         $originalKey = $license_key;
        
//         do {
//             $license_key = $originalKey . str_repeat(' (COPY)', $i++);
//         } while (LicenseKey::where('license_key', $license_key)->whereNull('deleted_at')->exists());

//         return $license_key;
//     }

//     public function createProductVariation($product, $variation)
//     {
//         $stock_status = StockStatus::OUT_OF_STOCK;
//         if (isset($variation->quantity) && $variation->quantity > 0) {
//             $stock_status = StockStatus::IN_STOCK;
//         }

//         $sale_price = null;
//         if (isset($variation->discount) && $variation->discount > 0) {
//             $sale_price = round($variation->price - (($variation->price * $variation->discount) / 100), 2);
//         }

//         // Handle multilingual variation name
//         $currentLocale = app()->getLocale();
//         $variationName = 'Default Variation';
        
//         if (isset($variation->{'name_' . $currentLocale})) {
//             $variationName = $variation->{'name_' . $currentLocale};
//         } elseif (isset($variation->name)) {
//             $variationName = $variation->name;
//         }

//         $variationData = [
//             'name' => $variationName,
//             'price' => $variation->price ?? 0,
//             'sale_price' => $sale_price,
//             'discount' => $variation->discount ?? 0,
//             'quantity' => $variation->quantity ?? 0,
//             'sku' => $this->getVariationSKU($variation->sku ?? 'VAR-' . uniqid()),
//             'stock_status' => $stock_status,
//             'status' => $variation->status ?? 1,
//             'is_default' => $variation->is_default ?? 0,
//         ];

//         $productVariation = $product->variations()->create($variationData);

//         // Handle variation attributes
//         if (isset($variation->attribute_values) && !empty($variation->attribute_values)) {
//             $productVariation->attribute_values()->attach(explode(',', $variation->attribute_values));
//         }

//         // Handle variation image
//         if (isset($variation->variation_image_url) && !empty($variation->variation_image_url)) {
//             $media = $product->addMediaFromUrl($variation->variation_image_url)->toMediaCollection('attachment');
//             $media->save();
//             $productVariation->variation_image_id = $media->id;
//             $productVariation->save();
//         }

//         // Handle variation galleries
//         if (isset($variation->variation_galleries_url) && !empty($variation->variation_galleries_url)) {
//             $variation_galleries_urls = explode(',', $variation->variation_galleries_url);
//             $variation_galleries_ids = [];
            
//             foreach ($variation_galleries_urls as $variation_gallery_url) {
//                 $media = $product->addMediaFromUrl($variation_gallery_url)->toMediaCollection('attachment');
//                 $media->save();
//                 $variation_galleries_ids[] = $media->id;
//             }

//             $productVariation->variation_galleries()->attach($variation_galleries_ids);
//         }

//         // Handle variation digital files
//         if (isset($variation->variation_digital_files_url) && !empty($variation->variation_digital_files_url)) {
//             $variation_digital_files_urls = explode(',', $variation->variation_digital_files_url);
//             $variation_digital_files_ids = [];
            
//             foreach ($variation_digital_files_urls as $variation_digital_file_url) {
//                 $media = $product->addMediaFromUrl($variation_digital_file_url)->toMediaCollection('attachment');
//                 $media->save();
//                 $variation_digital_files_ids[] = $media->id;
//             }

//             $productVariation->variation_digital_files()->attach($variation_digital_files_ids);
//         }

//         // Handle variation license keys
//         if (isset($variation->license_keys) && !empty($variation->license_keys) && 
//             isset($variation->separator) && !empty($variation->separator) &&
//             $product->product_type == 'digital' && $product->is_licensable == 1 && $product->is_licensekey_auto == 0) {
            
//             $license_keys = Helpers::explodeLicenseKeys($variation->separator, $variation->license_keys);
//             $this->updateOrCreateProductLicenseKeys($product, $license_keys, $productVariation->id);
//         }

//         return $productVariation;
//     }

//     public function filterRow($row)
//     {
//         // Map multilingual fields to base fields
//         $currentLocale = app()->getLocale();
//         $multilingualFields = ['name', 'short_description', 'description', 'meta_title', 'meta_description', 'estimated_delivery_text', 'return_policy_text'];
        
//         foreach ($multilingualFields as $field) {
//             $localeField = $field . '_' . $currentLocale;
//             if (isset($row[$localeField]) && !empty($row[$localeField])) {
//                 $row[$field] = $row[$localeField];
//             }
//         }
    
//         // Only filter out null values, keep empty strings and zeros
//         $filteredRow = array_filter($row, function($value) {
//             return $value !== null;
//         });
    
//         // Set default values only if not present at all
//         if (!array_key_exists('name', $filteredRow)) {
//             $filteredRow['name'] = 'Untitled Product';
//         }
    
//         if (!array_key_exists('product_type', $filteredRow)) {
//             $filteredRow['product_type'] = 'physical';
//         }
    
//         if (!array_key_exists('type', $filteredRow)) {
//             $filteredRow['type'] = 'simple';
//         }
    
//         if (!array_key_exists('description', $filteredRow)) {
//             $filteredRow['description'] = 'No description provided';
//         }
    
//         // Set default values for boolean fields only if not present
//         $booleanFields = [
//             'is_featured', 'show_stock_quantity', 'secure_checkout', 'safe_checkout',
//             'social_share', 'encourage_order', 'encourage_view', 'is_cod',
//             'is_return', 'is_free_shipping', 'is_changeable', 'is_sale_enable',
//             'is_external', 'watermark', 'is_licensable', 'is_licensekey_auto',
//             'is_trending', 'is_random_related_products', 'is_approved', 'status'
//         ];
    
//         foreach ($booleanFields as $field) {
//             if (!array_key_exists($field, $filteredRow)) {
//                 $filteredRow[$field] = 0;
//             }
//         }
    
//         return $filteredRow;
//     }

//     public function setTranslations($product, $row)
//     {
//         $currentLocale = app()->getLocale();
        
//         foreach ($this->translateFields as $field) {
//             // Check for locale-specific field first (e.g., name_en)
//             $localeField = $field . '_' . $currentLocale;
//             if (isset($row[$localeField]) && !empty($row[$localeField])) {
//                 $product->setTranslation($field, $currentLocale, $row[$localeField]);
//             }
//             // Fallback to base field if locale-specific doesn't exist
//             elseif (isset($row[$field]) && !empty($row[$field])) {
//                 $product->setTranslation($field, $currentLocale, $row[$field]);
//             }
//         }
        
//         $product->save();
//     }
// }

namespace App\Imports;
use Exception;
use App\Enums\RoleEnum;
use App\Models\Product;
use App\Helpers\Helpers;
use App\Models\Variation;
use App\Enums\StockStatus;
use App\Models\LicenseKey;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use App\GraphQL\Exceptions\ExceptionHandler;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    private $products = [];
    private $translateFields = ['name','short_description','description','meta_title','meta_description','estimated_delivery_text','return_policy_text'];
    public function rules(): array
    {
        return [
            'name'  => ['nullable', 'string', 'max:255'],
            'product_type' =>  ['nullable','in:physical,digital,external'],
            'description' => ['nullable', 'string', 'min:10'],
            'short_description' => ['nullable'],
            'type' => ['nullable','in:simple,classified'],
            'price' => ['nullable', 'numeric'],
             'sale_price' => ['nullable', 'numeric'],
            'stock_status' => ['nullable', 'in:in_stock,out_of_stock'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'sku' => ['nullable', 'string'],
            'discount' => ['nullable','numeric','regex:/^([0-9]{1,2}){1}(\.[0-9]{1,2})?$/'],
            'show_stock_quantity' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'secure_checkout' => ['nullable', 'boolean'],
            'safe_checkout' => ['nullable', 'boolean'],
            'social_share' => ['nullable', 'boolean'],
            'encourage_order' => ['nullable', 'boolean'],
            'encourage_view' => ['nullable', 'boolean'],
            'is_cod' => ['nullable', 'boolean'],
            'is_return' => ['nullable', 'boolean'],
            'is_free_shipping' => ['nullable', 'boolean'],
            'is_changeable' => ['nullable', 'boolean'],
            'is_sale_enable' => ['nullable', 'boolean'],
            'is_external' => ['nullable', 'boolean'],
            'external_url' => ['nullable', 'url'],
            'watermark' => ['nullable', 'boolean'],
            'watermark_position' => ['nullable', 'string'],
            'is_licensable' => ['nullable', 'boolean'],
            'is_licensekey_auto' => ['nullable', 'boolean'],
            'sale_starts_at' => ['nullable', 'date'],
            'store_id' => ['nullable','exists:stores,id,deleted_at,NULL'],
            'brand_id' => ['nullable','exists:brands,id,deleted_at,NULL'],
            'sale_expired_at' => ['nullable','date', 'after_or_equal:sale_starts_at'],
            'separator' => ['nullable', 'in:new_line,double_new_line,comma,semicolon,pipe'],
            'status' => ['nullable', 'boolean'],
            'visible_time' => ['nullable','date'],
            'variations' => ['nullable', 'json'],
            // Image URL validations
            'product_thumbnail_url' => ['nullable', 'url'],
            'product_meta_image_url' => ['nullable', 'url'],
            'size_chart_image_url' => ['nullable', 'url'],
            'preview_audio_file_url' => ['nullable', 'url'],
            'preview_video_file_url' => ['nullable', 'url'],
            'product_galleries_url' => ['nullable', 'string'],
            'digital_files_url' => ['nullable', 'string'],
            'watermark_image_url' => ['nullable', 'url'],
        ];
    }
    public function customValidationMessages()
    {
        return [
            'name.required' => ('validation.name_Required'),
            'name.string' => ('validation.name_in_string'),
            'name.max' => ('validation.name_not_exceed'),
            'name.unique' => ('validation.name_already_taken'),
            'external_url.url' => ('validation.external_url_must_be_valid'),
            'product_thumbnail_url.url' => ('validation.product_thumbnail_url_must_be_valid'),
            'product_meta_image_url.url' => __('validation.product_meta_image_url_must_be_valid'),
            // Add more custom messages as needed
        ];
    }
    public function onError(\Throwable $e)
    {
        throw new ExceptionHandler($e->getMessage() , 422);
    }
    public function getImportedProducts()
    {
        return $this->products;
    }
    public function getMinPriceVariation($request, $price)
    {
        return head(array_filter(json_decode($request['variations']), function ($variation) use ($price) {
            return $variation->price == $price;
        }));
    }
    // public function model(array $row)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $store_id = null;
    //         $roleName = Helpers::getCurrentRoleName();
    //         $isAutoApprove = true; // Default value

    //         if ($roleName != RoleEnum::ADMIN) {
    //             $settings = Helpers::getSettings();
    //             if ($roleName == RoleEnum::VENDOR) {
    //                 if (!Helpers::isMultiVendorEnable()) {
    //                     throw new Exception(__('auth.multi_vendor_deactivated'), 403);
    //                 }
    //                 $store_id = Helpers::getCurrentVendorStoreId();
    //             }
    //             $isAutoApprove = $settings['activation']['product_auto_approve'] ?? true;
    //         }
    //         // Check if product exists by SKU for update
    //         $existingProduct = null;
    //         if (isset($row['sku']) && !empty($row['sku'])) {
    //             $existingProduct = Product::where('sku', $row['sku'])
    //                 ->whereNull('deleted_at')
    //                 ->first();
    //         }
    //         // Calculate variation-based pricing
    //         $price = null;
    //         $sale_price = null;
    //         $discount = null;
    //         $quantity = null;
    //         $stock_status = null;
    //         if(isset($row['variations']) && !empty($row['variations']) && isset($row['type']) && $row['type'] == 'classified') {
    //             $variations = json_decode($row['variations']);
    //             if (is_array($variations) && count($variations)) {
    //                 $price = min(array_column($variations, 'price'));
    //                 $minPriceVariation = $this->getMinPriceVariation($row, $price);
    //                 $discount = $minPriceVariation->discount ?? 0;
    //                 $sale_price = round($price - (($price * $discount)/100), 2);
    //                 $quantity = max(array_column($variations, 'quantity'));
    //                 $stock_status = $quantity > 0 ? StockStatus::IN_STOCK : StockStatus::OUT_OF_STOCK;
    //             }
    //         }
    //         // Handle simple product stock status
    //         if (isset($row['quantity']) && !is_null($row['quantity'])) {
    //             $stock_status = $row['quantity'] > 0 ? StockStatus::IN_STOCK : StockStatus::OUT_OF_STOCK;
    //         }
    //         // Calculate sale price from discount
    //         if (isset($row['discount']) && !is_null($row['discount']) && $row['discount'] > 0) {
    //             $mrpPrice = $row['price'] ?? $price ?? 0;
    //             $sale_price = round($mrpPrice - (($mrpPrice * $row['discount'])/100), 2);
    //         }

    //         $row = $this->filterRow($row);

    //         // Update existing product or create new one
    //         if ($existingProduct) {
    //             $product = $existingProduct;
    //             $this->updateProduct($product, $row, $store_id, $isAutoApprove, $price, $sale_price, $discount, $quantity, $stock_status);
    //         } else {
    //             $product = $this->createProduct($row, $store_id, $isAutoApprove, $price, $sale_price, $discount, $quantity, $stock_status);
    //         }
    //         $this->setTranslations($product, $row);
    //         // Handle media files with error handling
    //         $this->handleMediaFiles($product, $row);
    //         // Handle relationships
    //         $this->handleRelationships($product, $row, $existingProduct);
    //         // Handle variations
    //         if (isset($row['variations']) && !is_null($row['variations']) && isset($row['type']) && $row['type'] == 'classified'){
    //             $this->handleVariations($product, $row, $existingProduct);
    //         }
    //         // Handle license keys
    //         if ($this->shouldHandleLicenseKeys($product, $row)) {
    //             $license_keys = Helpers::explodeLicenseKeys($row['separator'], $row['license_keys']);
    //             $this->updateOrCreateProductLicenseKeys($product, $license_keys);
    //         }
    //         // Handle wholesale prices
    //         if (isset($row['wholesale_prices'])) {
    //             $this->updateOrCreateWholesaleProduct($product, $row['wholesale_prices']);
    //         }
    //         $this->products[] = $this->formatProductResponse($product);
    //         DB::commit();
    //         return $product;
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         throw new ExceptionHandler($e->getMessage(), $e->getCode());
    //     }
    // }
    public function model(array $row)
{
    DB::beginTransaction();
    try {
        // Filter and clean the row data FIRST
        $row = $this->filterRow($row);
        
        $store_id = null;
        $roleName = Helpers::getCurrentRoleName();
        $isAutoApprove = true; // Default value

        if ($roleName != RoleEnum::ADMIN) {
            $settings = Helpers::getSettings();
            if ($roleName == RoleEnum::VENDOR) {
                if (!Helpers::isMultiVendorEnable()) {
                    throw new Exception(__('auth.multi_vendor_deactivated'), 403);
                }
                $store_id = Helpers::getCurrentVendorStoreId();
            }
            $isAutoApprove = $settings['activation']['product_auto_approve'] ?? true;
        }

        // Check if product exists by SKU for update
        $existingProduct = null;
        if (isset($row['sku']) && !empty($row['sku'])) {
            $existingProduct = Product::where('sku', $row['sku'])
                ->whereNull('deleted_at')
                ->first();
        }

        // Calculate variation-based pricing
        $price = null;
        $sale_price = null;
        $discount = null;
        $quantity = null;
        $stock_status = null;
        
         if (isset($row['sale_price']) && !empty($row['sale_price'])) {
            $sale_price = (float) $row['sale_price'];
        }

        if(isset($row['variations']) && !empty($row['variations']) && isset($row['type']) && $row['type'] == 'classified') {
            $variations = json_decode($row['variations']);
            if (is_array($variations) && count($variations)) {
                $price = min(array_column($variations, 'price'));
                $minPriceVariation = $this->getMinPriceVariation($row, $price);
                $discount = $minPriceVariation->discount ?? 0;
                $sale_price = round($price - (($price * $discount)/100), 2);
                $quantity = max(array_column($variations, 'quantity'));
                $stock_status = $quantity > 0 ? StockStatus::IN_STOCK : StockStatus::OUT_OF_STOCK;
            }
        }

        // Handle simple product stock status
        if (isset($row['quantity']) && !is_null($row['quantity'])) {
            $stock_status = $row['quantity'] > 0 ? StockStatus::IN_STOCK : StockStatus::OUT_OF_STOCK;
        }

        // Calculate sale price from discount
        // if (isset($row['discount']) && !is_null($row['discount']) && $row['discount'] > 0) {
        //     $mrpPrice = $row['price'] ?? $price ?? 0;
        //     $sale_price = round($mrpPrice - (($mrpPrice * $row['discount'])/100), 2);
        // }
        
         if ($sale_price === null && isset($row['discount']) && !is_null($row['discount']) && $row['discount'] > 0) {
            $mrpPrice = $row['price'] ?? $price ?? 0;
            $sale_price = round($mrpPrice - (($mrpPrice * $row['discount'])/100), 2);
        }

        // Update existing product or create new one
        if ($existingProduct) {
            $product = $existingProduct;
            $this->updateProduct($product, $row, $store_id, $isAutoApprove, $price, $sale_price, $discount, $quantity, $stock_status);
        } else {
            $product = $this->createProduct($row, $store_id, $isAutoApprove, $price, $sale_price, $discount, $quantity, $stock_status);
        }

        $this->setTranslations($product, $row);

        // Handle media files with error handling
        $this->handleMediaFiles($product, $row);

        // Handle relationships
        $this->handleRelationships($product, $row, $existingProduct);

        // Handle variations
        if (isset($row['variations']) && !is_null($row['variations']) && isset($row['type']) && $row['type'] == 'classified'){
            $this->handleVariations($product, $row, $existingProduct);
        }

        // Handle license keys
        if ($this->shouldHandleLicenseKeys($product, $row)) {
            $license_keys = Helpers::explodeLicenseKeys($row['separator'], $row['license_keys']);
            $this->updateOrCreateProductLicenseKeys($product, $license_keys);
        }

        // Handle wholesale prices
        if (isset($row['wholesale_prices'])) {
            $this->updateOrCreateWholesaleProduct($product, $row['wholesale_prices']);
        }

        $this->products[] = $this->formatProductResponse($product);
        DB::commit();
        return $product;
    } catch (Exception $e) {
        DB::rollback();
        throw new ExceptionHandler($e->getMessage(), $e->getCode());
    }
}
    private function updateProduct($product, $row, $store_id, $isAutoApprove, $price, $sale_price, $discount, $quantity, $stock_status)
    {
        $updateData = [];

        // Only update fields that are present in the row
      $fieldsToUpdate = [
                'name', 'product_type', 'short_description', 'description', 'type', 'unit',
                'weight', 'meta_title', 'meta_description', 'is_free_shipping', 'is_external',
                'external_button_text', 'external_url', 'is_featured', 'is_return', 'is_trending',
                'is_sale_enable', 'is_random_related_products', 'sale_starts_at', 'sale_expired_at',
                'shipping_days', 'show_stock_quantity', 'estimated_delivery_text', 'return_policy_text',
                'safe_checkout', 'secure_checkout', 'social_share', 'encourage_order', 'encourage_view',
                'status', 'is_licensable', 'preview_url', 'watermark', 'watermark_position',
                'wholesale_price_type', 'separator', 'preview_type', 'is_licensekey_auto',
                'external_details', 'publication_id', 'price', 'discount', 'sale_price'  // Add these
            ];
        foreach ($fieldsToUpdate as $field) {
            if (array_key_exists($field, $row)) {
                $updateData[$field] = $row[$field];
            }
        }
        // Handle calculated fields
        if ($quantity !== null) $updateData['quantity'] = $quantity;
        if ($price !== null) $updateData['price'] = $price;
        // if ($sale_price !== null) $updateData['sale_price'] = $sale_price;
        if ($discount !== null) $updateData['discount'] = $discount;
        if ($stock_status !== null) $updateData['stock_status'] = $stock_status;
        
        if ($sale_price !== null) {
        $updateData['sale_price'] = $sale_price;
            } elseif (array_key_exists('sale_price', $row) && !empty($row['sale_price'])) {
                $updateData['sale_price'] = $row['sale_price'];
            } elseif (isset($updateData['price']) && isset($updateData['discount']) && $updateData['discount'] > 0) {
                // Calculate sale_price if we have price and discount
                $updateData['sale_price'] = round($updateData['price'] - (($updateData['price'] * $updateData['discount']) / 100), 2);
            }
        // Handle special fields
        if ($store_id !== null) $updateData['store_id'] = $store_id;
        if (array_key_exists('is_approved', $row)) {
            $updateData['is_approved'] = $row['is_approved'];
        } else {
            $updateData['is_approved'] = $isAutoApprove;
        }
        // Handle external_details JSON
        if (array_key_exists('external_details', $row)) {
            $updateData['external_details'] = is_string($row['external_details']) 
                ? json_decode($row['external_details'], true) 
                : $row['external_details'];
        }
        $product->update($updateData);
    }
    private function createProduct($row, $store_id, $isAutoApprove, $price, $sale_price, $discount, $quantity, $stock_status)
    {
        $productData = [
            'name' => $row['name'] ?? 'Untitled Product',
            'product_type' => $row['product_type'] ?? 'physical',
            'short_description' => $row['short_description'] ?? null,
            'description' => $row['description'] ?? 'No description provided',
            'type' => $row['type'] ?? 'simple',
            'unit' => $row['unit'] ?? null,
            'quantity' => $quantity ?? $row['quantity'] ?? 0,
            'weight' => $row['weight'] ?? null,
            'price' => $price ?? $row['price'] ?? 0,
            'sale_price' => $sale_price ?? $row['sale_price'] ?? null,
            'discount' => $discount ?? $row['discount'] ?? 0,
            'sku' => $row['sku'] ?? 'SKU-' . uniqid(),
            'stock_status' => $stock_status ?? $row['stock_status'] ?? 'out_of_stock',
            'meta_title' => $row['meta_title'] ?? null,
            'meta_description' => $row['meta_description'] ?? null,
            'store_id' => $store_id ?? $row['store_id'] ?? null,
            'is_free_shipping' => $row['is_free_shipping'] ?? 0,
            'is_external' => $row['is_external'] ?? 0,
            'external_button_text' => $row['external_button_text'] ?? null,
            'external_url'=> $row['external_url'] ?? null,
            'is_featured' => $row['is_featured'] ?? 0,
            'is_return' => $row['is_return'] ?? 0,
            'is_trending' => $row['is_trending'] ?? 0,
            'is_sale_enable' => $row['is_sale_enable'] ?? 0,
            'is_random_related_products' => $row['is_random_related_products'] ?? 0,
            'sale_starts_at' => $row['sale_starts_at'] ?? null,
            'sale_expired_at' => $row['sale_expired_at'] ?? null,
            'shipping_days' => $row['shipping_days'] ?? null,
            'show_stock_quantity' => $row['show_stock_quantity'] ?? 0,
            'estimated_delivery_text' => $row['estimated_delivery_text'] ?? null,
            'return_policy_text' => $row['return_policy_text'] ?? null,
            'safe_checkout' => $row['safe_checkout'] ?? 0,
            'secure_checkout' => $row['secure_checkout'] ?? 0,
            'social_share' => $row['social_share'] ?? 0,
            'encourage_order' => $row['encourage_order'] ?? 0,
            'encourage_view' => $row['encourage_view'] ?? 0,
            'is_approved' => $row['is_approved'] ?? $isAutoApprove,
            'status' => $row['status'] ?? 1,
            'is_licensable' => $row['is_licensable'] ?? 0,
            'preview_url' => $row['preview_url'] ?? null,
            'watermark' => $row['watermark'] ?? 0,
            'watermark_position' => $row['watermark_position'] ?? null,
            'wholesale_price_type' => $row['wholesale_price_type'] ?? null,
            'separator' => $row['separator'] ?? null,
            'preview_type' => $row['preview_type'] ?? null,
            'is_licensekey_auto' => $row['is_licensekey_auto'] ?? 0,
            'external_details' => is_string($row['external_details'] ?? null)
                ? json_decode($row['external_details'], true)
                : ($row['external_details'] ?? null),
            'publication_id' => $row['publication_id'] ?? null,
        ];
        return Product::create($productData);
    }
    private function handleMediaFiles($product, $row)
    {
        try {
            // Handle product thumbnail
            if (isset($row['product_thumbnail_url']) && !empty($row['product_thumbnail_url'])) {
                $this->handleSingleMediaFile($product, $row['product_thumbnail_url'], 'product_thumbnail_id', $row);
            }
            // Handle product meta image
            if (isset($row['product_meta_image_url']) && !empty($row['product_meta_image_url'])) {
                $this->handleSingleMediaFile($product, $row['product_meta_image_url'], 'product_meta_image_id');
            }
            // Handle size chart image
            if (isset($row['size_chart_image_url']) && !empty($row['size_chart_image_url'])) {
                $this->handleSingleMediaFile($product, $row['size_chart_image_url'], 'size_chart_image_id');
            }
            // Handle preview audio file
            if (isset($row['preview_audio_file_url']) && !empty($row['preview_audio_file_url'])) {
                $this->handleSingleMediaFile($product, $row['preview_audio_file_url'], 'preview_audio_file_id');
            }
            // Handle preview video file
            if (isset($row['preview_video_file_url']) && !empty($row['preview_video_file_url'])) {
                $this->handleSingleMediaFile($product, $row['preview_video_file_url'], 'preview_video_file_id');
            }
            // Handle watermark image
            if (isset($row['watermark_image_url']) && !empty($row['watermark_image_url'])) {
                $this->handleSingleMediaFile($product, $row['watermark_image_url'], 'watermark_image_id');
            }
            $product->save();
        } catch (Exception $e) {
            // Log the error but don't stop the import
            \Log::error('Media file handling error: ' . $e->getMessage());
        }
    }
    private function handleSingleMediaFile($product, $url, $fieldName, $row = null)
    {
        try {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new Exception("Invalid URL: $url");
            }
            $media = $product->addMediaFromUrl($url)->toMediaCollection('attachment');
            $media->save();
            $product->$fieldName = $media->id;
            // Handle watermark for thumbnail
            if ($fieldName === 'product_thumbnail_id' && isset($row['watermark']) && $row['watermark']) {
                $this->applyWatermark($product, $row);
            }
        } catch (Exception $e) {
            \Log::error("Failed to handle media file $url: " . $e->getMessage());
            throw $e;
        }
    }
    private function applyWatermark($product, $row)
    {
        if (isset($row['watermark_position']) && isset($row['watermark_image_url'])) {
            try {
                $watermarkMedia = $product->addMediaFromUrl($row['watermark_image_url'])->toMediaCollection('attachment');
                $watermarkMedia->save();
                $product->watermark_image_id = $watermarkMedia->id;

                $watermark_id = $product->watermark_image_id;
                $file_id = $product->product_thumbnail_id;
                $position = $row['watermark_position'];

                $product->product_thumbnail_id = Helpers::createWatermarkImage($watermark_id, $file_id, $position);
                $product->watermark_image()->associate($product->product_thumbnail_id);
            } catch (Exception $e) {
                \Log::error('Watermark application error: ' . $e->getMessage());
            }
        }
    }
    private function handleRelationships($product, $row, $existingProduct)
    {
        // Handle galleries
        if (isset($row['product_galleries_url']) && !empty($row['product_galleries_url'])) {
            $this->handleGalleries($product, $row, $existingProduct);
        }
        // Handle digital files
        if (isset($row['digital_files_url']) && !empty($row['digital_files_url'])) {
            $this->handleDigitalFiles($product, $row, $existingProduct);
        }
        // Handle categories
        if (isset($row['categories']) && !empty($row['categories'])) {
            $categoryIds = explode(',', $row['categories']);
            if ($existingProduct) {
                $product->categories()->sync($categoryIds);
            } else {
                $product->categories()->attach($categoryIds);
            }
        }
        // Handle tags
        if (isset($row['tags']) && !empty($row['tags'])) {
            $tagIds = explode(',', $row['tags']);
            if ($existingProduct) {
                $product->tags()->sync($tagIds);
            } else {
                $product->tags()->attach($tagIds);
            }
        }
        // Handle attributes
        if (isset($row['attributes']) && !empty($row['attributes'])) {
            $attributeIds = explode(',', $row['attributes']);
            if ($existingProduct) {
                $product->attributes()->sync($attributeIds);
            } else {
                $product->attributes()->attach($attributeIds);
            }
        }
        // Handle authors
        if (isset($row['authors_id']) && !empty($row['authors_id'])) {
            $authorIds = is_array($row['authors_id']) ? $row['authors_id'] : explode(',', $row['authors_id']);
            if ($existingProduct) {
                $product->authors()->sync($authorIds);
            } else {
                $product->authors()->attach($authorIds);
            }
        }
    }
    private function handleGalleries($product, $row, $existingProduct)
    {
        try {
            $galleryUrls = explode(',', $row['product_galleries_url']);
            $galleryIds = [];

            foreach ($galleryUrls as $url) {
                $url = trim($url);
                if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $media = $product->addMediaFromUrl($url)->toMediaCollection('attachment');
                    $media->save();
                    $galleryIds[] = $media->id;
                }
            }
            if (!empty($galleryIds)) {
                if ($existingProduct) {
                    $product->product_galleries()->sync($galleryIds);
                } else {
                    $product->product_galleries()->attach($galleryIds);
                }
            }
        } catch (Exception $e) {
            \Log::error('Gallery handling error: ' . $e->getMessage());
        }
    }
    private function handleDigitalFiles($product, $row, $existingProduct)
    {
        try {
            $digitalFileUrls = explode(',', $row['digital_files_url']);
            $digitalFileIds = [];

            foreach ($digitalFileUrls as $url) {
                $url = trim($url);
                if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $media = $product->addMediaFromUrl($url)->toMediaCollection('attachment');
                    $media->save();
                    $digitalFileIds[] = $media->id;
                }
            }
            if (!empty($digitalFileIds)) {
                if ($existingProduct) {
                    $product->digital_files()->sync($digitalFileIds);
                } else {
                    $product->digital_files()->attach($digitalFileIds);
                }
            }
        } catch (Exception $e) {
            \Log::error('Digital files handling error: ' . $e->getMessage());
        }
    }
    private function handleVariations($product, $row, $existingProduct)
    {
        $variations = json_decode($row['variations']);
        if (is_array($variations)) {
            // For updates, delete existing variations first
            if ($existingProduct) {
                $product->variations()->delete();
            }

            foreach ($variations as $variation) {
                $this->createProductVariation($product, $variation);
            }
        }
    }
    private function shouldHandleLicenseKeys($product, $row)
    {
        return (isset($row['type']) && $row['type'] == 'simple' && isset($row['product_type']) && $row['product_type'] == 'digital') &&
               ($row['is_licensekey_auto'] == '0' && $row['is_licensable'] == '1') &&
               (!empty($row['license_keys']) && !empty($row['separator']));
    }
    // ... (keep all the existing helper methods: getVariationSKU, updateOrCreateWholesaleProduct, 
    // updateOrCreateProductLicenseKeys, getUniqueLicenseKey, createProductVariation, etc.)
    public function getVariationSKU($sku)
    {
        $i = 1;
        do {
            $sku = $sku.str_repeat(' (COPY)', $i++);
        } while (Variation::where('sku', $sku)->whereNull('deleted_at')->exists());
        return $sku;
    }
    public function updateOrCreateWholesaleProduct($product, $wholesalePrices)
    {
        $wholesaleIds = [];
        if (is_array($wholesalePrices)) {
            foreach ($wholesalePrices as $wholesalePrice) {
                $wholesale = $product->wholesales()->updateOrCreate(['id' => $wholesalePrice['id'] ?? null], [
                    'min_qty' => $wholesalePrice['min_qty'],
                    'max_qty' => $wholesalePrice['max_qty'],
                    'value' =>  $wholesalePrice['value'],
                ]);
                $wholesaleIds[] = $wholesale?->id;
            }
            $product->wholesales()->whereNotIn('id', $wholesaleIds)?->delete();
            return $product;
        }
    }
    public function updateOrCreateProductLicenseKeys($product, $license_keys, $variation_id = null)
    {
        $licenseKeyIds = [];
        if (is_array($license_keys)) {
            foreach ($license_keys as $license_key) {
                $licenseKey = $product->license_keys()->updateOrCreate(['license_key' => $license_key], [
                    'license_key' => $this->getUniqueLicenseKey($license_key),
                    'variation_id' => $variation_id,
                    'status' => 1,
                ]);
                $licenseKeyIds[] = $licenseKey?->id;
            }
            $product->license_keys()->whereNotIn('id', $licenseKeyIds)->where('variation_id', $variation_id)?->delete();
            return $product;
        }
    }
    public function getUniqueLicenseKey($license_key)
    {
        $i = 1;
        $originalKey = $license_key;

        do {
            $license_key = $originalKey . str_repeat(' (COPY)', $i++);
        } while (LicenseKey::where('license_key', $license_key)->whereNull('deleted_at')->exists());
        return $license_key;
    }
    public function createProductVariation($product, $variation)
    {
        $stock_status = StockStatus::OUT_OF_STOCK;
        if (isset($variation->quantity) && $variation->quantity > 0) {
            $stock_status = StockStatus::IN_STOCK;
        }
        $sale_price = null;
        if (isset($variation->discount) && $variation->discount > 0) {
            $sale_price = round($variation->price - (($variation->price * $variation->discount) / 100), 2);
        }
        // Handle multilingual variation name
        $currentLocale = app()->getLocale();
        $variationName = 'Default Variation';

        if (isset($variation->{'name' . $currentLocale})) {
            $variationName = $variation->{'name' . $currentLocale};
        } elseif (isset($variation->name)) {
            $variationName = $variation->name;
        }
        $variationData = [
            'name' => $variationName,
            'price' => $variation->price ?? 0,
            'sale_price' => $sale_price,
            'discount' => $variation->discount ?? 0,
            'quantity' => $variation->quantity ?? 0,
            'sku' => $this->getVariationSKU($variation->sku ?? 'VAR-' . uniqid()),
            'stock_status' => $stock_status,
            'status' => $variation->status ?? 1,
            'is_default' => $variation->is_default ?? 0,
        ];
        $productVariation = $product->variations()->create($variationData);
        // Handle variation relationships with error handling
        try {
            // Handle variation attributes
            if (isset($variation->attribute_values) && !empty($variation->attribute_values)) {
                $productVariation->attribute_values()->attach(explode(',', $variation->attribute_values));
            }
            // Handle variation image
            if (isset($variation->variation_image_url) && !empty($variation->variation_image_url)) {
                $this->handleVariationMedia($product, $productVariation, $variation->variation_image_url, 'variation_image_id');
            }
            // Handle variation galleries
            if (isset($variation->variation_galleries_url) && !empty($variation->variation_galleries_url)) {
                $this->handleVariationGalleries($product, $productVariation, $variation->variation_galleries_url);
            }
            // Handle variation digital files
            if (isset($variation->variation_digital_files_url) && !empty($variation->variation_digital_files_url)) {
                $this->handleVariationDigitalFiles($product, $productVariation, $variation->variation_digital_files_url);
            }
            // Handle variation license keys
            if (isset($variation->license_keys) && !empty($variation->license_keys) && 
                isset($variation->separator) && !empty($variation->separator) &&
                $product->product_type == 'digital' && $product->is_licensable == 1 && $product->is_licensekey_auto == 0) {

                $license_keys = Helpers::explodeLicenseKeys($variation->separator, $variation->license_keys);
                $this->updateOrCreateProductLicenseKeys($product, $license_keys, $productVariation->id);
            }
        } catch (Exception $e) {
            \Log::error('Variation media handling error: ' . $e->getMessage());
        }
        return $productVariation;
    }
   private function handleVariationMedia($product, $variation, $url, $fieldName)
    {
        try {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $media = $product->addMediaFromUrl($url)->toMediaCollection('attachment');
                $media->save();
                $variation->$fieldName = $media->id;
                $variation->save();
            }
        } catch (Exception $e) {
            \Log::error("Failed to handle variation media $url: " . $e->getMessage());
        }
    }

    private function handleVariationGalleries($product, $variation, $galleryUrls)
    {
        try {
            $urls = explode(',', $galleryUrls);
            $galleryIds = [];
            
            foreach ($urls as $url) {
                $url = trim($url);
                if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $media = $product->addMediaFromUrl($url)->toMediaCollection('attachment');
                    $media->save();
                    $galleryIds[] = $media->id;
                }
            }
            
            if (!empty($galleryIds)) {
                $variation->variation_galleries()->attach($galleryIds);
            }
        } catch (Exception $e) {
            \Log::error('Variation galleries handling error: ' . $e->getMessage());
        }
    }

    private function handleVariationDigitalFiles($product, $variation, $digitalFileUrls)
    {
        try {
            $urls = explode(',', $digitalFileUrls);
            $digitalFileIds = [];
            
            foreach ($urls as $url) {
                $url = trim($url);
                if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $media = $product->addMediaFromUrl($url)->toMediaCollection('attachment');
                    $media->save();
                    $digitalFileIds[] = $media->id;
                }
            }
            
            if (!empty($digitalFileIds)) {
                $variation->variation_digital_files()->attach($digitalFileIds);
            }
        } catch (Exception $e) {
            \Log::error('Variation digital files handling error: ' . $e->getMessage());
        }
    }

  private function filterRow($row)
{
    $rows = [];
    
    foreach ($row as $key => $value) {
        $lastUnderscorePos = strrpos($key, "_");
        
        if ($lastUnderscorePos !== false) {
            $separatedKeys = [
                1 => substr($key, 0, $lastUnderscorePos),
                2 => substr($key, $lastUnderscorePos + 1),
            ];
            
            if (in_array(head($separatedKeys), $this->translateFields)) {
                $rows[head($separatedKeys)][last($separatedKeys)] = $value;
            } else {
                $rows[$key] = $value;
            }
        } else {
            $rows[$key] = $value;
        }
    }
    
    // Remove empty values and normalize data
    $filteredRow = array_filter($rows, function($value) {
        return !is_null($value) && $value !== '';
    });
    
    // Convert string booleans to actual booleans
    $booleanFields = [
        'show_stock_quantity', 'is_featured', 'secure_checkout', 'safe_checkout',
        'social_share', 'encourage_order', 'encourage_view', 'is_cod', 'is_return',
        'is_free_shipping', 'is_changeable', 'is_sale_enable', 'is_external',
        'watermark', 'is_licensable', 'is_licensekey_auto', 'status', 'is_trending',
        'is_random_related_products'
    ];
    
    foreach ($booleanFields as $field) {
        if (isset($filteredRow[$field])) {
            $filteredRow[$field] = filter_var($filteredRow[$field], FILTER_VALIDATE_BOOLEAN);
        }
    }
    
    // Convert numeric fields
    $numericFields = ['price', 'quantity', 'discount', 'weight', 'shipping_days' , 'sale_price'];
    foreach ($numericFields as $field) {
        if (isset($filteredRow[$field])) {
            $filteredRow[$field] = is_numeric($filteredRow[$field]) ? 
                (float)$filteredRow[$field] : $filteredRow[$field];
        }
    }
    
    return $filteredRow;
}

private function setTranslations($product, $row)
{
    $locale = app()->getLocale();
    
    foreach ($row as $key => $value) {
        if ($product->isTranslatableAttribute($key)) {
            $translations = is_array($value) ? $value : [$locale => $value];
            $product->setTranslations($key, $translations);
        }
    }
    
    return $product->save();
}

    // private function filterRow($row)
    // {
    //     // Remove empty values and normalize data
    //     $filteredRow = array_filter($row, function($value) {
    //         return !is_null($value) && $value !== '';
    //     });
        
    //     // Convert string booleans to actual booleans
    //     $booleanFields = [
    //         'show_stock_quantity', 'is_featured', 'secure_checkout', 'safe_checkout',
    //         'social_share', 'encourage_order', 'encourage_view', 'is_cod', 'is_return',
    //         'is_free_shipping', 'is_changeable', 'is_sale_enable', 'is_external',
    //         'watermark', 'is_licensable', 'is_licensekey_auto', 'status', 'is_trending',
    //         'is_random_related_products'
    //     ];
        
    //     foreach ($booleanFields as $field) {
    //         if (isset($filteredRow[$field])) {
    //             $filteredRow[$field] = filter_var($filteredRow[$field], FILTER_VALIDATE_BOOLEAN);
    //         }
    //     }
        
    //     // Convert numeric fields
    //     $numericFields = ['price', 'quantity', 'discount', 'weight', 'shipping_days'];
    //     foreach ($numericFields as $field) {
    //         if (isset($filteredRow[$field])) {
    //             $filteredRow[$field] = is_numeric($filteredRow[$field]) ? 
    //                 (float)$filteredRow[$field] : $filteredRow[$field];
    //         }
    //     }
        
    //     return $filteredRow;
    // }

    private function formatProductResponse($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'sale_price' => $product->sale_price,
            'quantity' => $product->quantity,
            'stock_status' => $product->stock_status,
            'is_approved' => $product->is_approved,
            'status' => $product->status,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];
    }
}