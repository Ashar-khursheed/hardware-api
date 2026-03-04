<?php

namespace App\Repositories\Eloquents;

use Exception;
use App\Enums\RoleEnum;
use App\Models\Setting;
use App\Helpers\Helpers;
use App\Models\Currency;
use Illuminate\Support\Arr;
use App\Enums\MessageMethod;
use App\Enums\PaymentMethod;
use App\Enums\FrontSettingsEnum;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Exceptions\ExceptionHandler;
use Prettus\Repository\Eloquent\BaseRepository;

class SettingRepository extends BaseRepository
{
    protected $currency;

    function model()
    {
        $this->currency = new Currency();
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
            foreach ($paymentMethods as $paymentMethod) {
                $paymentMethodStatus[] = [
                    "name" => $paymentMethod,
                    "title" => $settingValues['payment_methods'][$paymentMethod]['title'],
                    "status" => $settingValues['payment_methods'][$paymentMethod]['status']
                ];
            }

            foreach ($smsMethods as  $smsMethod) {
                $smsMethodStatus[] = [
                    "name" => $smsMethod,
                    "title" => $settingValues['sms_methods'][$smsMethod]['title'],
                    "status" => $settingValues['sms_methods'][$smsMethod]['status']
                ];
            }

            $settings['values'] = Arr::only($settingValues, array_column(FrontSettingsEnum::cases(), 'value'));
            $settings['values']['payment_methods'] = $paymentMethodStatus;
            $settings['values']['sms_methods'] = $smsMethodStatus;

            return $settings;

        } catch (Exception $e) {

            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {

            $settings = $this->model->first();
            $settings->update($request);
            $settings = $settings->fresh();
            $this->env($request['values']);

            DB::commit();
            return $settings;

        } catch (Exception $e) {

            DB::rollback();
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

    public function env($value)
    {
        try {

            $keys = [];

            if (isset($value['general'])) {
                $keys['APP_NAME'] = $value['general']['site_name'] ?? '';
            }

            if (isset($value['email'])) {
                $keys['MAIL_MAILER']        = $value['email']['mail_mailer']       ?? '';
                $keys['MAIL_HOST']          = $value['email']['mail_host']         ?? '';
                $keys['MAIL_PORT']          = $value['email']['mail_port']         ?? '';
                $keys['MAIL_USERNAME']      = $value['email']['mail_username']     ?? '';
                $keys['MAIL_PASSWORD']      = $value['email']['mail_password']     ?? '';
                $keys['MAIL_ENCRYPTION']    = $value['email']['mail_encryption']   ?? '';
                $keys['MAIL_FROM_ADDRESS']  = $value['email']['mail_from_address'] ?? '';
                $keys['MAIL_FROM_NAME']     = $value['email']['mail_from_name']    ?? '';
                $keys['MAILGUN_DOMAIN']     = $value['email']['mailgun_domain']    ?? '';
                $keys['MAILGUN_SECRET']     = $value['email']['mailgun_secret']    ?? '';
            }

            if (isset($value['media_configuration'])) {
                $mediaDisk = $value['media_configuration']['media_disk'] ?? '';
                $keys['MEDIA_DISK'] = $mediaDisk;
                if ($mediaDisk === 'aws') {
                    $keys['AWS_ACCESS_KEY_ID']     = $value['media_configuration']['aws_access_key_id']     ?? '';
                    $keys['AWS_SECRET_ACCESS_KEY'] = $value['media_configuration']['aws_secret_access_key'] ?? '';
                    $keys['AWS_BUCKET']            = $value['media_configuration']['aws_bucket']            ?? '';
                    $keys['AWS_DEFAULT_REGION']    = $value['media_configuration']['aws_default_region']    ?? '';
                }
            }

            if (isset($value['google_reCaptcha'])) {
                $keys['GOOGLE_RECAPTCHA_SECRET'] = $value['google_reCaptcha']['secret']   ?? '';
                $keys['GOOGLE_RECAPTCHA_KEY']    = $value['google_reCaptcha']['site_key'] ?? '';
            }

            if (isset($value['payment_methods'])) {
                $keys['PAYPAL_MODE']           = ($value['payment_methods']['paypal']['sandbox_mode'] ?? false) ? 'sandbox' : 'live';
                $keys['PAYPAL_CLIENT_ID']      = $value['payment_methods']['paypal']['client_id']      ?? '';
                $keys['PAYPAL_CLIENT_SECRET']  = $value['payment_methods']['paypal']['client_secret']  ?? '';
                $keys['STRIPE_API_KEY']        = $value['payment_methods']['stripe']['key']            ?? '';
                $keys['STRIPE_SECRET_KEY']     = $value['payment_methods']['stripe']['secret']         ?? '';
                $keys['RAZORPAY_KEY']          = $value['payment_methods']['razorpay']['key']          ?? '';
                $keys['RAZORPAY_SECRET']       = $value['payment_methods']['razorpay']['secret']       ?? '';
                $keys['MOLLIE_KEY']            = $value['payment_methods']['mollie']['secret_key']     ?? '';
                $keys['CCAVENUE_SANDBOX_MODE'] = $value['payment_methods']['ccavenue']['sandbox_mode'] ?? '';
                $keys['CCAVENUE_MERCHANT_ID']  = $value['payment_methods']['ccavenue']['merchant_id']  ?? '';
                $keys['CCAVENUE_ACCESS_CODE']  = $value['payment_methods']['ccavenue']['access_code']  ?? '';
                $keys['CCAVENUE_WORKING_KEY']  = $value['payment_methods']['ccavenue']['working_key']  ?? '';
                $keys['PHONEPE_SANDBOX_MODE']  = $value['payment_methods']['phonepe']['sandbox_mode']  ?? '';
                $keys['PHONEPE_MERCHANT_ID']   = $value['payment_methods']['phonepe']['merchant_id']   ?? '';
                $keys['PHONEPE_SALT_KEY']      = $value['payment_methods']['phonepe']['salt_key']      ?? '';
                $keys['PHONEPE_SALT_INDEX']    = $value['payment_methods']['phonepe']['salt_index']    ?? '';
                $keys['INSTAMOJO_SANDBOX_MODE']  = $value['payment_methods']['instamojo']['sandbox_mode']  ?? '';
                $keys['INSTAMOJO_CLIENT_ID']     = $value['payment_methods']['instamojo']['client_id']     ?? '';
                $keys['INSTAMOJO_CLIENT_SECRET'] = $value['payment_methods']['instamojo']['client_secret'] ?? '';
                $keys['INSTAMOJO_SALT_KEY']      = $value['payment_methods']['instamojo']['salt_key']      ?? '';
                $keys['BKASH_SANDBOX_MODE'] = $value['payment_methods']['bkash']['sandbox_mode'] ?? '';
                $keys['BKASH_APP_KEY']      = $value['payment_methods']['bkash']['app_key']      ?? '';
                $keys['BKASH_APP_SECRET']   = $value['payment_methods']['bkash']['app_secret']   ?? '';
                $keys['BKASH_USERNAME']     = $value['payment_methods']['bkash']['username']     ?? '';
                $keys['BKASH_PASSWORD']     = $value['payment_methods']['bkash']['password']     ?? '';
                $keys['FLW_SANDBOX_MOD']    = $value['payment_methods']['flutter_wave']['sandbox_mode'] ?? '';
                $keys['FLW_PUBLIC_KEY']     = $value['payment_methods']['flutter_wave']['public_key']   ?? '';
                $keys['FLW_SECRET_KEY']     = $value['payment_methods']['flutter_wave']['secret_key']   ?? '';
                $keys['FLW_SECRET_HASH']    = $value['payment_methods']['flutter_wave']['secret_hash']  ?? '';
                $keys['PAYSTACK_SANDBOX_MODE'] = $value['payment_methods']['paystack']['sandbox_mode'] ?? '';
                $keys['PAYSTACK_PUBLIC_KEY']   = $value['payment_methods']['paystack']['public_key']   ?? '';
                $keys['PAYSTACK_SECRET_KEY']   = $value['payment_methods']['paystack']['secret_key']   ?? '';
                $keys['SSLC_STORE_ID']       = $value['payment_methods']['sslcommerz']['store_id']       ?? '';
                $keys['SSLC_STORE_PASSWORD'] = $value['payment_methods']['sslcommerz']['store_password'] ?? '';
                $keys['SSLC_SANDBOX_MODE']   = $value['payment_methods']['sslcommerz']['sandbox_mode']   ?? '';
            }

            if (isset($value['sms_methods'])) {
                $keys['TWILIO_SID']        = $value['sms_methods']['twilio']['twilio_sid']        ?? '';
                $keys['TWILIO_AUTH_TOKEN'] = $value['sms_methods']['twilio']['twilio_auth_token'] ?? '';
                $keys['TWILIO_NUMBER']     = $value['sms_methods']['twilio']['twilio_number']     ?? '';
            }

            if (!empty($keys)) {
                $this->writeEnv($keys);
            }

        } catch (Exception $e) {

            DB::rollback();
            throw new ExceptionHandler($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Write key-value pairs to the .env file using native PHP.
     * Existing keys are updated in-place; new keys are appended.
     */
    protected function writeEnv(array $keys): void
    {
        $envPath = base_path('.env');
        $content = file_exists($envPath) ? file_get_contents($envPath) : '';

        foreach ($keys as $key => $value) {
            // Wrap value in quotes if it contains spaces or special chars
            $escaped = (strpbrk((string) $value, " \t\n\r#") !== false)
                ? '"' . addslashes((string) $value) . '"'
                : (string) $value;

            $pattern = '/^' . preg_quote($key, '/') . '\s*=.*/m';
            $replacement = $key . '=' . $escaped;

            if (preg_match($pattern, $content)) {
                $content = preg_replace($pattern, $replacement, $content);
            } else {
                $content = rtrim($content) . "\n" . $replacement . "\n";
            }
        }

        file_put_contents($envPath, $content);
    }
}
