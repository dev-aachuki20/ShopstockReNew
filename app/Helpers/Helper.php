<?php

use App\Models\Customer;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Uploads;
use App\Models\LogActivity;
use Carbon\Carbon;
use App\Models\RoleIp;
use App\Models\RoleIpPermission;
use App\Models\PaymentTransaction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as Str;

if (!function_exists('getCommonValidationRuleMsgs')) {
    function getCommonValidationRuleMsgs()
    {
        return [
            'currentpassword.required' => 'The current password is required.',
            'currentpassword.min' => 'The current password must be at least :min characters',
            'password.required' => 'The new password is required.',
            'password.min' => 'The new password must be at least :min characters',
            'password.different' => 'The new password and current password must be different.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password_confirmation.required' => 'The new password confirmation is required.',
            'password_confirmation.min' => 'The new password confirmation must be at least :min characters',
            'email.required' => 'Please enter email address.',
            'email.email' => 'Email is not valid. Enter email address for example test@gmail.com',
            'email.exists' => "Please Enter Valid Registered Email!",
            'password_confirmation.same' => 'The confirm password and new password must match.'
        ];
    }
}

if (!function_exists('addToLog')) {
    /**
     * Create log activity
     *
     * @return string
     */
    function addToLog($request, $modelName = "", $activity = "", $new_value = "", $old_value = "")
    {
        $inputs = $request->all();
        $inputs['model_name'] = $modelName;
        $inputs['activity'] = $activity;
        $inputs['created_by'] = auth()->check() ? auth()->user()->id : 0;
        $inputs['old_value'] = $old_value ? json_encode($old_value) : '';
        $inputs['new_value'] = $new_value ? json_encode($new_value) : '';
        LogActivity::create($inputs);
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 20)
    {

        $randomString = Str::random($length);

        return $randomString;
    }
}

if (!function_exists('getWithDateTimezone')) {
    function getWithDateTimezone($date)
    {
        $newdate = Carbon::parse($date)->setTimezone(config('app.timezone'))->format('d-m-Y H:i:s');
        return $newdate;
    }
}

if (!function_exists('uploadImage')) {
    /**
     * Upload Image.
     *
     * @param array $input
     *
     * @return array $input
     */
    function uploadImage($directory, $file, $folder, $type = "profile", $fileType = "jpg", $actionType = "save", $uploadId = null, $orientation = null)
    {
        $oldFile = null;

        if ($actionType == "save") {

            $upload                       = new Uploads;
        } else {

            $upload                       = Uploads::find($uploadId);
            $oldFile = $upload->file_path;
        }
        $upload->file_path          = $file->store($folder, 'public');
        $upload->extension          = $file->getClientOriginalExtension();
        $upload->original_file_name = $file->getClientOriginalName();
        $upload->type                 = $type;
        $upload->file_type             = $fileType;
        $upload->orientation         = $orientation;
        $response                     = $directory->uploads()->save($upload);

        // delete old file
        if ($oldFile) {
            Storage::disk('public')->delete($oldFile);
        }

        return $upload;
    }
}

if (!function_exists('deleteFile')) {
    /**
     * Destroy Old Image.	 *
     * @param int $id
     */
    function deleteFile($upload_id)
    {
        $upload = Uploads::find($upload_id);
        Storage::disk('public')->delete($upload->file_path);
        $upload->delete();
        return true;
    }
}


if (!function_exists('getSetting')) {
    function getSetting($key)
    {
        $result = null;
        $setting = Setting::where('key', $key)->where('status', 1)->first();
        if ($setting) {
            if ($setting->type == 'image') {
                $result = $setting->image_url;
            } elseif ($setting->type == 'video') {
                $result = $setting->video_url;
            } else {
                $result = $setting->value;
            }
        }
        return $result;
    }
}

if (!function_exists('generateInvoiceNumber')) {
    function generateInvoiceNumber($orderId)
    {
        $timeframe = now()->format('M-y'); // Get the current month abbreviation
        $invoiceNumber = strtoupper($timeframe) . '-' . str_pad($orderId, 4, '0', STR_PAD_LEFT);
        return $invoiceNumber;
    }
}

if (!function_exists('generateInvoicePdf')) {
    function generateInvoicePdf($order, $type = null)
    {
        $order = Order::with('orderProduct.product')->findOrFail($order);
        $pdfFileName = 'invoice_' . $order->invoice_number . '.pdf';
        $pdf = PDF::loadView('admin.order.pdf.invoice-pdf', compact('order', 'type'));
        $pdfContent = $pdf->output();
        // Create a temporary file to save the PDF
        $customer_name = $order->customer->full_name;
        $pdfFileName = $order->invoice_number . '_' . $customer_name . '.pdf';

        //return $pdf->download($pdfFileName);
        return ['pdfContent' => $pdfContent, 'pdfFileName' => $pdfFileName];
    }
}


if (!function_exists('str_limit_custom')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int  $limit
     * @param  string  $end
     * @return string
     */
    function str_limit_custom($value, $limit = 100, $end = '...')
    {
        return \Illuminate\Support\Str::limit($value, $limit, $end);
    }
}


/// Function for handling Data Type of a number , if 50.00 then return 50 , if 50.64 then return 50.64
/// It will return 2 digit after point

if (!function_exists('handleDataTypeTwoDigit')) {
    function handleDataTypeTwoDigit($number)
    {
        $number = $number == intval($number) ? intval($number) : number_format($number, 2, '.', '');

        return $number;
    }
}

/// It will return 3 digit after point
if (!function_exists('handleDataTypeThreeDigit')) {
    function handleDataTypeThreeDigit($number)
    {
        $number = $number == intval($number) ? intval($number) : number_format($number, (fmod($number, 1) !== 0) ? 3 : 0, '.', '');

        return $number;
    }
}

/// Calculate Category Amount Ratio's percentage

if (!function_exists('CategoryAmountPercent')) {
    function CategoryAmountPercent($amount, $totalAmount)
    {
        if ($totalAmount == 0) {
            return '0.00%';
        }
        $percentShare = ($amount / $totalAmount) * 100;
        return number_format($percentShare, 2) . '%';
    }
}
if (!function_exists('checkRoleIpPermission')) {
    function checkRoleIpPermission($ip, $roleid)
    {
        $checkPermission = RoleIp::where('ip_address', $ip)
            ->leftJoin('role_ip_permissions', 'role_ips.id', '=', 'role_ip_permissions.role_ip_id')
            ->where('role_ip_permissions.role_id', $roleid)->first();
        if ($checkPermission) {
            return "Yes";
        }
        return "No";
    }
}
if (!function_exists('getTotalBlance')) {
    /**
     * Return the total debit amount into payment transaction table.
     *
     * @return string
     */
    function getTotalBlance($customer_id, $is_label = 0)
    {
        $totalDebit = getTotalDebit($customer_id);
        $totalCredit = getTotalCredit($customer_id);
        $total = $totalCredit - $totalDebit;

        if ($is_label == 0) {
            $total = number_format(abs($total), 2);
            if ($totalDebit >= $totalCredit) {
                return '<button type="button" class="btn btn-success"><i class="fa fa-inr"></i>' . $total . '/-</button>';
            } else {
                return '<button type="button" class="btn btn-danger"><i class="fa fa-inr"></i>' . $total . '/-</button>';
            }
        }
        return $total;
    }
}

if (!function_exists('getTotalDebit')) {
    /**
     * Return the total debit amount into payment transaction table.
     *
     * @return string
     */
    function getTotalDebit($customer_id)
    {
        return PaymentTransaction::where('payment_type', 'debit')->where('customer_id', $customer_id)->sum('amount');
    }
}


if (!function_exists('getTotalCredit')) {
    /**
     * Return the total credit amount into payment transaction table.
     *
     * @return string
     */
    function getTotalCredit($customer_id)
    {
        return PaymentTransaction::where('payment_type', 'credit')->where('customer_id', $customer_id)->sum('amount');
    }
}
if (!function_exists('getNewInvoiceNumber')) {
    /**
     * Return the total number of areas in table.
     *
     * @return string
     */
    function getNewInvoiceNumber($orderId = '', $reqRouteName = 'new', $checkInvoiceNumber = '')
    {
        $invoiceNumber = '';
        if ((!empty($orderId) && !empty($checkInvoiceNumber)) || $reqRouteName == 'new_edit') {
            $invoiceNumber = Order::where('id', '!=', $orderId)->where('invoice_number', $checkInvoiceNumber)->exists();
        } else {

            // Find the latest invoice number in the database
            $currentMonth = strtoupper(date('M')) . date('y');
            if ($reqRouteName == 'return') {
                $currentMonth = $currentMonth . '-R';
            } else if ($reqRouteName == 'new_cash_receipt') {
                $currentMonth = $currentMonth . '-CR';
            } else if ($reqRouteName == 'new') {
                $currentMonth = $currentMonth . '-';
            }

            if ($reqRouteName == 'new_cash_receipt') {
                $latestInvoice = PaymentTransaction::select('voucher_number')->where('voucher_number', 'like', $currentMonth . '%')->withTrashed()->orderByRaw('CAST(SUBSTRING(voucher_number, 7) AS UNSIGNED) DESC')
                    ->orderBy('voucher_number', 'DESC')->first();
            } else if ($reqRouteName == 'new') {

                $matchingPattern = $currentMonth . '[0-9]{4}';
                $latestInvoice = Order::select('invoice_number')->where('invoice_number', 'REGEXP', $matchingPattern)->withTrashed()
                    ->orderByRaw('CAST(SUBSTRING(invoice_number, 5) AS UNSIGNED) DESC')
                    ->orderBy('invoice_number', 'DESC')->first();
                //dd($latestInvoice);
            } else if ($reqRouteName == 'return') {
                $latestInvoice = Order::select('invoice_number')->where('invoice_number', 'like', $currentMonth . '%')->withTrashed()
                    ->orderByRaw('CAST(SUBSTRING(invoice_number, 6) AS UNSIGNED) DESC')
                    ->orderBy('invoice_number', 'DESC')->first();
            }

            //    dd($latestInvoice->invoice_number);
            if ($latestInvoice) {

                if ($reqRouteName == 'new' || $reqRouteName == 'return') {
                    $lastInvoiceNumber = $latestInvoice->invoice_number;
                } else if ($reqRouteName == 'new_cash_receipt') {
                    $lastInvoiceNumber = $latestInvoice->voucher_number;
                }

                $stringRegexCondition = '/([A-Z]+)(\d+)/';
                // $stringRegexCondition = '/([A-Z]+)-/';
                // $numericRegexCondition = '/-(\d+)$/';
                if ($reqRouteName == 'new_cash_receipt' || $reqRouteName == 'return') {
                    $stringRegexCondition = '/([A-Z]+[\d]+-[A-Z]+)/';
                }

                // Extract string portion (e.g., 'AUG-CR')
                preg_match($stringRegexCondition, $lastInvoiceNumber, $matches);
                $stringPortion = '';
                if ($matches) {
                    $stringPortion = $matches[0];
                }

                // Extract numeric portion (e.g., '0999')
                preg_match('/\d+$/', $lastInvoiceNumber, $matches);
                $numericPortion = (int) $matches[0];

                $defaultNumericLength = strlen($numericPortion) > 4 ? strlen($numericPortion) : 4;

                $newNumericPortion = str_pad($numericPortion + 1, $defaultNumericLength, '0', STR_PAD_LEFT);

                if($reqRouteName == 'new_cash_receipt' || $reqRouteName == 'return'){
                    $invoiceNumber = $stringPortion . $newNumericPortion;
                }else{
                    $invoiceNumber = $stringPortion . '-' . $newNumericPortion;
                }
                // $invoiceNumber = $stringPortion . $newNumericPortion;

                // dd($lastInvoiceNumber,$numericPortion,$newNumericPortion,$invoiceNumber);

            } else {
                $invoiceNumber = $currentMonth . '0001';
            }
        }

        return $invoiceNumber;
    }
}

if (!function_exists('removeTrailingZeros')) {
    function removeTrailingZeros($number): string
    {
        $number = floatval($number);
        $number = number_format($number, 2);
        $number_string = strval($number);

        if (substr($number_string, -3) === ".00") {
            return substr($number_string, 0, -3);
        } else {
            return $number_string;
        }
    }
}

if (!function_exists('glassProductMeasurement')) {
    /**
     * Generate a route name for the previous request.
     *
     * @return string|null
     */
    function glassProductMeasurement($object, $type = 'new_line')
    {
        $glassProductMeasurementList = ($type == 'new_line') ? '' : [];
        if ($object) {
            foreach (json_decode($object, true) as $key => $otherDetail) {
                $productMeasurement = ' ';
                $extra_option_hint = $otherDetail['extra_option_hint'] ?? '';
                if (isset($otherDetail['height']) && isset($otherDetail['width'])) {
                    $productMeasurement = $otherDetail['height'] . ' ' . $extra_option_hint . ' × ' . $otherDetail['width'] . ' ' . $extra_option_hint . ' - ' . $otherDetail['qty'] . ' pc';
                } else if (isset($otherDetail['width']) && isset($otherDetail['length'])) {
                    $productMeasurement = $otherDetail['width'] . ' ' . $extra_option_hint . ' × ' . $otherDetail['length'] . ' ' . $extra_option_hint . ' - ' . $otherDetail['qty'] . ' pc';
                } else if (isset($otherDetail['height']) && isset($otherDetail['length'])) {
                    $productMeasurement = $otherDetail['height'] . ' ' . $extra_option_hint . ' × ' . $otherDetail['length'] . ' ' . $extra_option_hint . ' - ' . $otherDetail['qty'] . ' pc';
                } else if (isset($otherDetail['height']) && isset($otherDetail['width']) && isset($otherDetail['length'])) {
                    $productMeasurement = $otherDetail['height'] . ' ' . $extra_option_hint . ' × ' . $otherDetail['width'] . ' ' . $extra_option_hint . ' × ' . $otherDetail['length'] . 'inch - ' . $otherDetail['qty'] . ' pc';
                } else if (isset($otherDetail['height']) && !isset($otherDetail['width']) && !isset($otherDetail['length'])) {
                    $productMeasurement = $otherDetail['height'] . ' ' . $extra_option_hint . ' - ' . $otherDetail['qty'] . ' pc';
                }

                if ($type == 'new_line') {
                    $glassProductMeasurementList .= "<p style='margin-bottom: 0px;'>" . $productMeasurement . "</p>";
                } else if ($type == 'one_line') {
                    $glassProductMeasurementList[$key] = $productMeasurement;
                }
            }
        }

        if ($type == 'new_line') {
            return $glassProductMeasurementList;
        } else if ($type == 'one_line') {
            return implode(' , ', $glassProductMeasurementList);
        }
    }
}


if (!function_exists('GetYearOpeningBalance')) {
    function GetYearOpeningBalance($firstopeningBalance=0, $customerID, $year)
    {
        $customer = Customer::findOrFail($customerID);

        $customerCreatedAtYear = Carbon::createFromFormat('Y-m-d H:i:s', $customer->created_at)->year;

        if ($year < $customerCreatedAtYear)
        {
           $openingBalance = 0;
        }
        elseif ($year == $customerCreatedAtYear)
        {
            $openingBalance = $firstopeningBalance;
        }
        else
        {
            $estimateDataquery = PaymentTransaction::selectRaw("SUM(amount) as total_debit_amount, 'sales' as type")
            ->where('customer_id', $customerID)->where('payment_way', 'order_create')->whereYear('entry_date', '<', $year)->first();

            $cashReceiptDataquery = PaymentTransaction::selectRaw("SUM(amount) as total_cashreceipt_amount, 'cashreceipt' as type")
            ->where('customer_id', $customerID)->whereIn('payment_way', ['by_cash', 'by_check', 'by_account'])->whereNotNull('voucher_number')
            ->where('remark', '!=', 'Opening balance')->whereYear('entry_date', '<', $year)->first();

            $estimateReturnDataquery = PaymentTransaction::selectRaw("SUM(amount) as total_salereturn_amount, 'sales_return' as type")
            ->where('customer_id', $customerID)->where('payment_way', 'order_return')->whereYear('entry_date', '<', $year)->first();

            $total_debit_amount = $estimateDataquery->total_debit_amount ?? 0;
            $total_credit_amount = ($cashReceiptDataquery->total_cashreceipt_amount ?? 0) + ($estimateReturnDataquery->total_salereturn_amount ?? 0);
            $newopening_balance = $firstopeningBalance + $total_debit_amount - $total_credit_amount;
            $openingBalance = $newopening_balance;
        }

        return $openingBalance;
    }
}
