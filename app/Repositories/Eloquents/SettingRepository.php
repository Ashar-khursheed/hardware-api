<?php

namespace App\Repositories\Eloquents;

use Exception;
use App\Enums\RoleEnum;
use App\Models\Setting;
use App\Helpers\Helpers;
use App\Models\Currency;
use App\Models\language;
use Illuminate\Support\Arr;
use App\Enums\MessageMethod;
use App\Enums\PaymentMethod;
use App\Enums\FrontSettingsEnum;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Exceptions\ExceptionHandler;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Prettus\Repository\Eloquent\BaseRepository;


class SettingRepository extends BaseRepository
{
    protected $currency;

    function model()
    {
        $this->currency = new Currency();
        $this->language = new language();
        return Setting::class;
    }

    public function index()
    {
        if (Helpers::isUserLogin()) {
            $roleName = Helpers::getCurrentRoleName();
            if ($roleName != RoleEnum::CONSUMER) {
                return $this->model->latest('created_at')->first();
            }
        }

        return $this->frontSettings();
    }

    public function frontSettings()
    {
        try {

            $settingValues = Helpers::getSettings();
            $paymentMethods = PaymentMethod::ALL_PAYMENT_METHODS;
            $smsMethods = MessageMethod::ALL_MESSAGE_METHODS;
          $paymentMethodStatus = [];
            
            foreach ($paymentMethods as $paymentMethod) {
                if (isset($settingValues['payment_methods'][$paymentMethod])) {
                    $paymentMethodStatus[] = [
                        "name" => $paymentMethod,
                        "title" => $settingValues['payment_methods'][$paymentMethod]['title'] ?? ucfirst($paymentMethod),
                        "status" => $settingValues['payment_methods'][$paymentMethod]['status'] ?? false
                    ];
                } else {
                    // Fallback in case the payment method is not configured
                    $paymentMethodStatus[] = [
                        "name" => $paymentMethod,
                        "title" => ucfirst($paymentMethod),
                        "status" => false
                    ];
                }
            }


           $smsMethodStatus = [];
            
            foreach ($smsMethods as $smsMethod) {
                if (isset($settingValues['sms_methods'][$smsMethod])) {
                    $smsMethodStatus[] = [
                        "name" => $smsMethod,
                        "title" => $settingValues['sms_methods'][$smsMethod]['title'] ?? ucfirst($smsMethod),
                        "status" => $settingValues['sms_methods'][$smsMethod]['status'] ?? false
                    ];
                } else {
                    // Fallback if SMS method not configured
                    $smsMethodStatus[] = [
                        "name" => $smsMethod,
                        "title" => ucfirst($smsMethod),
                        "status" => false
                    ];
                }
            }


            $settings['values'] = Arr::only($settingValues, array_column(FrontSettingsEnum::cases(), 'value'));
            $settings['values']['payment_methods'] = $paymentMethodStatus;
            $settings['values']['sms_methods'] = $smsMethodStatus;

            return $settings;

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

//   public function update($request, $id)
// {
//     DB::beginTransaction();
//     try {

//         // Retrieve and update the settings from the database
//         $settings = $this->model->first();
//         $settings->update($request);

//         // Refresh the settings model to get the updated values
//         $settings = $settings->fresh();

//         // Optional: Handle other operations (like setting default language, etc.)
//         $this->setDefaultLanguage($settings?->values);

//         DB::commit();
//         return $settings;

//     } catch (Exception $e) {

//         DB::rollback();
//         // Optionally, you can log the error for further debugging
//         \Log::error('Error updating settings: ' . $e->getMessage());
//         throw new ExceptionHandler($e->getMessage(), $e->getCode());
//     }
// }
public function update($request, $id)
{
    DB::beginTransaction();
    try {
        $settings = $this->model->first();

        $values = $request['values'];

        // Convert payment_methods from array to associative array
        if (isset($values['payment_methods']) && is_array($values['payment_methods'])) {
            $normalizedPayments = [];
            foreach ($values['payment_methods'] as $method) {
                if (isset($method['name'])) {
                    $normalizedPayments[$method['name']] = $method;
                }
            }
            $values['payment_methods'] = $normalizedPayments;
        }

        // Convert sms_methods from array to associative array
        if (isset($values['sms_methods']) && is_array($values['sms_methods'])) {
            $normalizedSms = [];
            foreach ($values['sms_methods'] as $method) {
                if (isset($method['name'])) {
                    $normalizedSms[$method['name']] = $method;
                }
            }
            $values['sms_methods'] = $normalizedSms;
        }

        // Update settings
        // Ensure 'cod' exists in payment_methods
        if (!isset($values['payment_methods']['cod'])) {
            $values['payment_methods']['cod'] = [
                'title' => 'Cash On Delivery',
                'status' => true
            ];
        }

        $settings->update(['values' => json_encode($values)]);
        $settings = $settings->fresh();

        // Optional: handle locale
        $this->setDefaultLanguage($values);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'data' => $settings,
        ]);

    } catch (Exception $e) {
        DB::rollback();
        \Log::error('Error updating settings: ' . $e->getMessage());
        throw new ExceptionHandler($e->getMessage(), $e->getCode());
    }
}


    public function setDefaultCurrencyBasePrice($settings)
    {
        $currency = $this->currency->findOrFail($settings['general']['default_currency_id']);
        $currency->update([
            'exchange_rate' => true
        ]);
    }

    public function setDefaultLanguage($settings)
    {
        $language = $this->language->findOrFail($settings['general']['default_language_id']);
        app()->setLocale($language->locale);
    }



    // public function env($value)
    // {
    //     try {

    //         if (isset($value['general'])) {
    //             DotenvEditor::setKeys([
    //                 'APP_NAME' => $value['general']["site_name"]
    //             ]);
    //         }

    //         if (isset($value['email'])) {
    //             DotenvEditor::setKeys([
    //                 'MAIL_MAILER' => $value['email']["mail_mailer"],
    //                 'MAIL_HOST' => $value['email']["mail_host"],
    //                 'MAIL_PORT' => $value['email']["mail_port"],
    //                 'MAIL_USERNAME' => $value['email']["mail_username"],
    //                 'MAIL_PASSWORD' => $value['email']["mail_password"],
    //                 'MAIL_ENCRYPTION' => $value['email']["mail_encryption"],
    //                 'MAIL_FROM_ADDRESS' => $value['email']["mail_from_address"],
    //                 'MAIL_FROM_NAME' => $value['email']["mail_from_name"],
    //                 'MAILGUN_DOMAIN' => $value['email']["mailgun_domain"],
    //                 'MAILGUN_SECRET' => $value['email']["mailgun_secret"],
    //             ]);
    //         }

    //         if (isset($value['media_configuration'])) {
    //             DotenvEditor::setKeys([
    //                 'MEDIA_DISK' => $value['media_configuration']["media_disk"],
    //             ]);

    //             DotenvEditor::save();
    //             if ($value['media_configuration'] == 'aws') {
    //                 DotenvEditor::setKeys([
    //                     'AWS_ACCESS_KEY_ID' => $value['media_configuration']["aws_access_key_id"],
    //                     'AWS_SECRET_ACCESS_KEY' => $value['media_configuration']["aws_secret_access_key"],
    //                     'AWS_BUCKET' => $value['media_configuration']["aws_bucket"],
    //                     'AWS_DEFAULT_REGION' => $value['media_configuration']["aws_default_region"],
    //                 ]);

    //                 DotenvEditor::save();
    //             }
    //         }

    //         if (isset($value['google_reCaptcha'])) {
    //             DotenvEditor::setKeys([
    //                 'GOOGLE_RECAPTCHA_SECRET' => $value['google_reCaptcha']["secret"],
    //                 'GOOGLE_RECAPTCHA_KEY' => $value['google_reCaptcha']["site_key"],
    //             ]);

    //             DotenvEditor::save();
    //         }

    //         if (isset($value['payment_methods'])) {
    //             $paypal_mode = $value['payment_methods']['paypal']["sandbox_mode"]? 'sandbox' : 'live';
    //             DotenvEditor::setKeys([
    //                 'PAYPAL_MODE' =>  $paypal_mode,
    //                 'PAYPAL_CLIENT_ID' => $value['payment_methods']['paypal']["client_id"],
    //                 'PAYPAL_CLIENT_SECRET' => $value['payment_methods']['paypal']["client_secret"],
    //                 'STRIPE_API_KEY' => $value['payment_methods']['stripe']["key"],
    //                 'STRIPE_SECRET_KEY' => $value['payment_methods']['stripe']["secret"],
    //                 'RAZORPAY_KEY' => $value['payment_methods']['razorpay']["key"],
    //                 'RAZORPAY_SECRET' => $value['payment_methods']['razorpay']["secret"],
    //                 'MOLLIE_KEY' => $value['payment_methods']['mollie']["secret_key"],
    //                 'CCAVENUE_SANDBOX_MODE' => $value['payment_methods']['ccavenue']["sandbox_mode"],
    //                 'CCAVENUE_MERCHANT_ID' => $value['payment_methods']['ccavenue']["merchant_id"],
    //                 'CCAVENUE_ACCESS_CODE' => $value['payment_methods']['ccavenue']["access_code"],
    //                 'CCAVENUE_WORKING_KEY' => $value['payment_methods']['ccavenue']["working_key"],
    //                 'PHONEPE_SANDBOX_MODE' => $value['payment_methods']['phonepe']["sandbox_mode"],
    //                 'PHONEPE_MERCHANT_ID' => $value['payment_methods']['phonepe']["merchant_id"],
    //                 'PHONEPE_SALT_KEY' => $value['payment_methods']['phonepe']["salt_key"] ,
    //                 'PHONEPE_SALT_INDEX' => $value['payment_methods']['phonepe']["salt_index"],
    //                 'INSTAMOJO_SANDBOX_MODE' => $value['payment_methods']['instamojo']["sandbox_mode"],
    //                 'INSTAMOJO_CLIENT_ID' => $value['payment_methods']['instamojo']["client_id"],
    //                 'INSTAMOJO_CLIENT_SECRET' => $value['payment_methods']['instamojo']["client_secret"],
    //                 'INSTAMOJO_SALT_KEY' => $value['payment_methods']['instamojo']["salt_key"],
    //                 'BKASH_SANDBOX_MODE' =>  $value['payment_methods']['bkash']["sandbox_mode"],
    //                 'BKASH_APP_KEY' =>  $value['payment_methods']['bkash']["app_key"],
    //                 'BKASH_APP_SECRET' =>  $value['payment_methods']['bkash']["app_secret"],
    //                 'BKASH_USERNAME' =>  $value['payment_methods']['bkash']["username"],
    //                 'BKASH_PASSWORD' =>  $value['payment_methods']['bkash']["password"],
    //                 'FLW_SANDBOX_MOD' =>  $value['payment_methods']['flutter_wave']["sandbox_mode"],
    //                 'FLW_PUBLIC_KEY' =>  $value['payment_methods']['flutter_wave']["public_key"],
    //                 'FLW_SECRET_KEY' =>  $value['payment_methods']['flutter_wave']["secret_key"],
    //                 'FLW_SECRET_HASH' =>  $value['payment_methods']['flutter_wave']["secret_hash"],
    //                 'PAYSTACK_SANDBOX_MODE' =>  $value['payment_methods']['paystack']["sandbox_mode"],
    //                 'PAYSTACK_PUBLIC_KEY' =>  $value['payment_methods']['paystack']["public_key"],
    //                 'PAYSTACK_SECRET_KEY' =>  $value['payment_methods']['paystack']["secret_key"],
    //                 'SSLC_STORE_ID' =>  $value['payment_methods']['sslcommerz']["store_id"],
    //                 'SSLC_STORE_PASSWORD' =>  $value['payment_methods']['sslcommerz']["store_password"],
    //                 'SSLC_SANDBOX_MODE' =>  $value['payment_methods']['sslcommerz']["sandbox_mode"],
    //             ]);

    //             DotenvEditor::save();
    //         }

    //         if (isset($value['sms_methods'])) {
    //             DotenvEditor::setKeys([
    //                 'TWILIO_SID' =>  $value['sms_methods']['twilio']["twilio_sid"],
    //                 'TWILIO_AUTH_TOKEN' =>  $value['sms_methods']['twilio']["twilio_auth_token"],
    //                 'TWILIO_NUMBER' =>  $value['sms_methods']['twilio']["twilio_number"],
    //             ]);

    //             DotenvEditor::save();
    //         }

    //     } catch (Exception $e) {

    //         DB::rollback();
    //         throw new ExceptionHandler($e->getMessage(), $e->getCode());
    //     }
    // }
}
