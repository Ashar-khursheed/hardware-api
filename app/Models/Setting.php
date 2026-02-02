<?php

namespace App\Models;

use App\Models\language;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $casts = [
        //  'values' => 'json',
    ];

    /**
     * The values that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'values',
    ];

    /**
     * @return Int
     */
    public function getId($request)
    {
        return ($request->id) ? $request->id : $request->route('settings');
    }
    
    // public function getValuesAttribute($value)
    // {
    //     $values = json_decode($value, true) ?? [];
    
    //     // Ensure keys exist before accessing
    //     $general = $values['general'] ?? [];
    //     $maintenance = $values['maintenance'] ?? [];
    
    //     $values['general'] = $general;
    //     $values['maintenance'] = $maintenance;
    
    //     // Safe retrieval
    //     $values['general']['light_logo_image'] = isset($general['light_logo_image_id']) ? Attachment::find($general['light_logo_image_id']) : null;
    //     $values['general']['dark_logo_image'] = isset($general['dark_logo_image_id']) ? Attachment::find($general['dark_logo_image_id']) : null;
    //     $values['general']['favicon_image'] = isset($general['favicon_image_id']) ? Attachment::find($general['favicon_image_id']) : null;
    //     $values['general']['tiny_logo_image'] = isset($general['tiny_logo_image_id']) ? Attachment::find($general['tiny_logo_image_id']) : null;
    //     $values['general']['default_currency'] = isset($general['default_currency_id']) ? Currency::find($general['default_currency_id']) : null;
    //     $values['general']['default_language'] = isset($general['default_language_id']) ? language::find($general['default_language_id']) : null;
    
    //     $values['maintenance']['maintenance_image'] = isset($maintenance['maintenance_image_id']) ? Attachment::find($maintenance['maintenance_image_id']) : null;
    
    //     return $values;
    // }
public function getValuesAttribute($value)
{
    // Decode JSON safely
    $values = json_decode($value, true);
    if (!is_array($values)) {
        $values = [];
    }

    // Ensure 'general' and 'maintenance' keys exist
    $values['general'] = $values['general'] ?? [];
    $values['maintenance'] = $values['maintenance'] ?? [];

    // Safe retrieval of attachments and relations
    $values['general']['light_logo_image'] = isset($values['general']['light_logo_image_id']) 
        ? Attachment::find($values['general']['light_logo_image_id']) 
        : null;

    $values['general']['dark_logo_image'] = isset($values['general']['dark_logo_image_id']) 
        ? Attachment::find($values['general']['dark_logo_image_id']) 
        : null;

    $values['general']['favicon_image'] = isset($values['general']['favicon_image_id']) 
        ? Attachment::find($values['general']['favicon_image_id']) 
        : null;

    $values['general']['tiny_logo_image'] = isset($values['general']['tiny_logo_image_id']) 
        ? Attachment::find($values['general']['tiny_logo_image_id']) 
        : null;

    $values['general']['default_currency'] = isset($values['general']['default_currency_id']) 
        ? Currency::find($values['general']['default_currency_id']) 
        : null;

    $values['general']['default_language'] = isset($values['general']['default_language_id']) 
        ? language::find($values['general']['default_language_id']) 
        : null;

    $values['maintenance']['maintenance_image'] = isset($values['maintenance']['maintenance_image_id']) 
        ? Attachment::find($values['maintenance']['maintenance_image_id']) 
        : null;

    return $values;
}


    //  public function getValuesAttribute($value)
    // {
    //     $values = json_decode($value, true);
    //     $lightLogoImage = Attachment::find($values['general']['light_logo_image_id']);
    //     $darkLogoImage = Attachment::find($values['general']['dark_logo_image_id']);
    //     $faviconImage = Attachment::find($values['general']['favicon_image_id']);
    //     $tinyImage = Attachment::find($values['general']['tiny_logo_image_id']);
    //     $defaultCurrency = Currency::find($values['general']['default_currency_id']);
    //     $maintenanceImage = Attachment::find($values['maintenance']['maintenance_image_id']);
    //     $defaultLanguage = language::find($values['general']['default_language_id']) ?? null;

    //     $values['general']['light_logo_image'] = $lightLogoImage;
    //     $values['general']['dark_logo_image'] = $darkLogoImage;
    //     $values['general']['favicon_image'] = $faviconImage;
    //     $values['general']['tiny_logo_image'] = $tinyImage;
    //     $values['general']['default_currency'] = $defaultCurrency;
    //     $values['maintenance']['maintenance_image'] = $maintenanceImage;
    //     $values['general']['default_language'] = $defaultLanguage;

    //     return $values;
    // }

    // public function setValuesAttribute($value)
    // {
    //     $this->attributes['values'] = json_encode($value);
    // }
    public function setValuesAttribute($value)
{
    // If it's an array, encode it
    if (is_array($value)) {
        $this->attributes['values'] = json_encode($value);
    }
    // If it's already a valid JSON string, store as-is
    elseif (is_string($value) && $this->isJson($value)) {
        $this->attributes['values'] = $value;
    }
    // Fallback: store empty JSON object
    else {
        $this->attributes['values'] = json_encode(new \stdClass());
    }
}

/**
 * Helper function to check if a string is valid JSON
 */
private function isJson($string)
{
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}

}
