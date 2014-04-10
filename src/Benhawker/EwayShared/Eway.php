<?php namespace Benhawker\Eway;

class Eway
{

    /**
     *  Sandbox or live (default: true)
     *
     * @var bool
     */
    protected $sandbox;

    /**
     * The eWay partner ID.
     *
     * @var string
     */
    protected $partnerId;

    /**
     * The web address the customer is redirected to with the result of the action.
     *
     * @var string (url)
     */
    protected $redirectUrl;

    /**
     * The URL that the shared page redirects to if a customer cancels the transaction
     *
     * @var string (url)
     */
    protected $cancelUrl;

    /**
     * The URL of the merchants logo to display on the shared page
     *
     * @var string (url)
     */
    protected $logoUrl;

    /**
     * Short text description to be placed under the logo on the shared page
     *
     * @var string
     */
    protected $headerText;

    /**
     * Set the theme of the Responsive Shared Page from 12 predetermined themes
     *
     * @var string
     */
    protected $customView;

    /**
     * The customer's IPv4 address.
     *
     * @var string
     */
    protected $customerIp;

    /**
     * Transaction amount in lowest denomination
     *
     * @var int
     */
    protected $paymentTotalAmount;

    /**
     * The merchant’s invoice number for this transaction
     *
     * @var string
     */
    protected $paymentInvoiceNumber;

    /**
     * A description of the purchase that the customer is making
     *
     * @var string
     */
    protected $paymentInvoiceDescription;
    /**
     * The customer invoice number.
     *
     * @var string
     */
    protected $paymentInvoiceReference;

    /**
     * The customer’s title, empty string allowed.
     *
     * @var string (Values: Mr., Ms., Mrs., Miss, Dr., Sir., Prof.)
     */
    protected $customerTitle;

    /**
     * The customer's first name.
     *
     * @var string
     */
    protected $customerFirstName;

    /**
     * The customer's last name.
     *
     * @var string
     */
    protected $customerLastName;

    /**
     * The customer's email address.
     *
     * @var string
     */
    protected $customerEmail;

    /**
     * eWay option 1.
     *
     * @var string
     */
    protected $option1;

    /**
     * eWay option 2.
     *
     * @var string
     */
    protected $option2;

    /**
     * eWay option 3.
     *
     * @var string
     */
    protected $option3;

    /**
     * The URL to redirect the customer to enter their card details
     *
     * @var string
     */
    protected $responseSharedPaymentUrl;

    /**
     * A unique Access Code that is used to identify this transaction with Rapid 3.1 API.
     * This code will need to be present for all future requests associated with this transaction
     *
     * @var string
     */
    protected $responseAccessCode;

    /**
     * A code that describes the result of the action performed.
     *
     * @var string
     */
    protected $transactionMessage;

    /**
     * The transaction status.
     * For a successful transaction "True" is passed and
     * for a failed transaction "False" is passed.
     *
     * @var string
     */
    protected $transactionStatus;

    /**
     * The transaction id.
     * This is a unique number assigned by eWay.
     *
     * @var string
     */
    protected $transactionId;

    /**
     * The transaction amount returned by the gateway.
     * This will not necessarily be the same as the $paymentAmount.
     *
     * @var string
     */
    protected $transactionAmount;

     /**
     * Response codes returned by the gateway.
     * The list of codes is complete according to the
     * {@link http://www.eway.com.au/Developer/payment-code/transaction-results-response-codes.aspx eWAY Payment Gateway Bank Response Codes}.
     *
     * @var array
     */
    protected static $responseCodes = array(
            'F7000' => "Undefined Fraud",
            'V5000' => "Undefined System",
            'A0000' => "Undefined Approved",
            'A2000' => "Transaction Approved",
            'A2008' => "Honour With Identification",
            'A2010' => "Approved For Partial Amount",
            'A2011' => "Approved VIP",
            'A2016' => "Approved Update Track 3",
            'V6000' => "Undefined Validation",
            'V6001' => "Invalid Request CustomerIP",
            'V6002' => "Invalid Request DeviceID",
            'V6011' => "Invalid Payment Amount",
            'V6012' => "Invalid Payment InvoiceDescription",
            'V6013' => "Invalid Payment InvoiceNumber",
            'V6014' => "Invalid Payment InvoiceReference",
            'V6015' => "Invalid Payment CurrencyCode",
            'V6016' => "Payment Required",
            'V6017' => "Payment CurrencyCode Required",
            'V6018' => "Unknown Payment CurrencyCode",
            'V6021' => "Cardholder Name Required",
            'V6022' => "Card Number Required",
            'V6023' => "CVN Required",
            'V6031' => "Invalid Card Number",
            'V6032' => "Invalid CVN",
            'V6033' => "Invalid Expiry Date",
            'V6034' => "Invalid Issue Number",
            'V6035' => "Invalid Start Date",
            'V6036' => "Invalid Month",
            'V6037' => "Invalid Year",
            'V6040' => "Invalid Token Customer Id",
            'V6041' => "Customer Required",
            'V6042' => "Customer First Name Required",
            'V6043' => "Customer Last Name Required",
            'V6044' => "Customer Country Code Required",
            'V6045' => "Customer Title Required",
            'V6046' => "Token Customer ID Required",
            'V6047' => "RedirectURL Required",
            'V6051' => "Invalid Customer First Name",
            'V6052' => "Invalid Customer Last Name",
            'V6053' => "Invalid Customer Country Code",
            'V6054' => "Invalid Customer Email",
            'V6055' => "Invalid Customer Phone",
            'V6056' => "Invalid Customer Mobile",
            'V6057' => "Invalid Customer Fax",
            'V6058' => "Invalid Customer Title",
            'V6059' => "Redirect URL Invalid",
            'V6060' => "Redirect URL Invalid",
            'V6061' => "Invalid Customer Reference",
            'V6062' => "Invalid Customer CompanyName",
            'V6063' => "Invalid Customer JobDescription",
            'V6064' => "Invalid Customer Street1",
            'V6065' => "Invalid Customer Street2",
            'V6066' => "Invalid Customer City",
            'V6067' => "Invalid Customer State",
            'V6068' => "Invalid Customer Postalcode",
            'V6069' => "Invalid Customer Email",
            'V6070' => "Invalid Customer Phone",
            'V6071' => "Invalid Customer Mobile",
            'V6072' => "Invalid Customer Comments",
            'V6073' => "Invalid Customer Fax",
            'V6074' => "Invalid Customer Url",
            'V6075' => "Invalid ShippingAddress FirstName",
            'V6076' => "Invalid ShippingAddress LastName",
            'V6077' => "Invalid ShippingAddress Street1",
            'V6078' => "Invalid ShippingAddress Street2",
            'V6079' => "Invalid ShippingAddress City",
            'V6080' => "Invalid ShippingAddress State",
            'V6081' => "Invalid ShippingAddress PostalCode",
            'V6082' => "Invalid ShippingAddress Email",
            'V6083' => "Invalid ShippingAddress Phone",
            'V6084' => "Invalid ShippingAddress Country",
            'V6091' => "Unknown Country Code",
            'V6100' => "Invalid ProcessRequest name",
            'V6101' => "Invalid ProcessRequest ExpiryMonth",
            'V6102' => "Invalid ProcessRequest ExpiryYear",
            'V6103' => "Invalid ProcessRequest StartMonth",
            'V6104' => "Invalid ProcessRequest StartYear",
            'V6105' => "Invalid ProcessRequest IssueNumber",
            'V6106' => "Invalid ProcessRequest CVN",
            'V6107' => "Invalid ProcessRequest AccessCode",
            'V6108' => "Invalid ProcessRequest CustomerHostAddress",
            'V6109' => "Invalid ProcessRequest UserAgent",
            'V6110' => "Invalid ProcessRequest Number",
            'D4401' => "Refer to Issuer",
            'D4402' => "Refer to Issuer, special",
            'D4403' => "No Merchant",
            'D4404' => "Pick Up Card",
            'D4405' => "Do Not Honour",
            'D4406' => "Error",
            'D4407' => "Pick Up Card, Special",
            'D4409' => "Request In Progress",
            'D4412' => "Invalid Transaction",
            'D4413' => "Invalid Amount",
            'D4414' => "Invalid Card Number",
            'D4415' => "No Issuer",
            'D4419' => "Re-enter Last Transaction",
            'D4421' => "No Method Taken",
            'D4422' => "Suspected Malfunction",
            'D4423' => "Unacceptable Transaction Fee",
            'D4425' => "Unable to Locate Record On File",
            'D4430' => "Format Error",
            'D4431' => "Bank Not Supported By Switch",
            'D4433' => "Expired Card, Capture",
            'D4434' => "Suspected Fraud, Retain Card",
            'D4435' => "Card Acceptor, Contact Acquirer, Retain Card",
            'D4436' => "Restricted Card, Retain Card",
            'D4437' => "Contact Acquirer Security Department, Retain Card",
            'D4438' => "PIN Tries Exceeded, Capture",
            'D4439' => "No Credit Account",
            'D4440' => "Function Not Supported",
            'D4441' => "Lost Card",
            'D4442' => "No Universal Account",
            'D4443' => "Stolen Card",
            'D4444' => "No Investment Account",
            'D4451' => "Insufficient Funds",
            'D4452' => "No Cheque Account",
            'D4453' => "No Savings Account",
            'D4454' => "Expired Card",
            'D4455' => "Incorrect PIN",
            'D4456' => "No Card Record",
            'D4457' => "Function Not Permitted to Cardholder",
            'D4458' => "Function Not Permitted to Terminal",
            'D4460' => "Acceptor Contact Acquirer",
            'D4461' => "Exceeds Withdrawal Limit",
            'D4462' => "Restricted Card",
            'D4463' => "Security Violation",
            'D4464' => "Original Amount Incorrect",
            'D4466' => "Acceptor Contact Acquirer, Security",
            'D4467' => "Capture Card",
            'D4475' => "PIN Tries Exceeded",
            'D4482' => "CVV Validation Error",
            'D4490' => "Cutoff In Progress",
            'D4491' => "Card Issuer Unavailable",
            'D4492' => "Unable To Route Transaction",
            'D4493' => "Cannot Complete, Violation Of The Law",
            'D4494' => "Duplicate Transaction",
            'D4496' => "System Error"
    );

    public function __construct(array $settings)
    {
        if (!isset([$settings'partnerId'])) {
            throw new ErrorException('Please make sure you set a partnerId')
        }
        $this->partnerId   = $settings['partnerId'],

        $this->sandbox     = (isset($settings['sandbox'])) ? $settings['sandbox'] : false;
        $this->redirectUrl = (isset($settings['redirectUrl'])) ? $settings['redirectUrl'] : '';
        $this->cancelUrl   = (isset($settings['cancelUrl'])) ? $settings['cancelUrl'] : '';
        $this->logoUrl     = (isset($settings['logoUrl'])) ? $settings['logoUrl'] : '';
        $this->headerText  = (isset($settings['headerText'])) ? $settings['headerText'] : '';
        $this->customView  = (isset($settings['customView'])) ? $settings['customView'] : '';
    }

    /**
     * Set sandbox or live (default: true)
     *
     * @param  bool   $sandbox
     * @return object (this)
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;

        return $this;
    }

    /**
     * Set the eWay partner ID.
     *
     * @param  string $partnerId
     * @return object (this)
     */
    public function setPartnerId($partnerId)
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    /**
     * Set the web address the customer is redirected to with the result of the action.
     *
     * @param  string (url) $redirectUrl
     * @return object       (this)
     */
    public function setRedirectUrl($redirectUrl)
    {
        if (!filter_var($redirectUrl, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            throw new ErrorException('Redirect Url was not a valid URL');
        }

        $this->redirectUrl = $redirectUrl;

        return $this;
    }

    /**
     * The URL that the shared page redirects to if a customer cancels the transaction
     *
     * @param  string (url) $cancelUrl
     * @return object       (this)
     */
    public function SetCancelUrl($cancelUrl)
    {
        if (!filter_var($cancelUrl, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            throw new ErrorException('Cancel Url was not a valid URL');
        }

        $this->cancelUrl = $cancelUrl;

        return $this;
    }

    /**
     * The URL of the merchants logo to display on the shared page
     *
     * @var string (url) $logoUrl
     * @return object (this)
     */
    public function setLogoUrl($logoUrl)
    {
        if (!filter_var($logoUrl, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            throw new ErrorException('Logo Url was not a valid URL');
        }

        $this->logoUrl = $logoUrl;

        return $this;
    }

    /**
     * Short text description to be placed under the logo on the shared page
     *
     * @var string $headerText
     * @return object (this)
     */
    public function setHeaderText($headerText)
    {
        if (strlen($headerText) > 255) {
            throw new ErrorException('Header text must not exceed 255 characters in length');
        }

        $this->headerText = $headerText;

        return $this;
    }

    /**
     * Set the theme of the Responsive Shared Page from 12 predetermined themes
     *
     * @var string $customView
     * @return object (this)
     */
    public function setCustomView($customView)
    {
        $this->customView = $customView;

        return $this;
    }

    /**
     * The customer's IPv4 address.
     *
     * @var string $customerIp
     * @return object (this)
     */
    public function setCustomerIp($customerIp)
    {
        if (filter_var($customerIp, FILTER_VALIDATE_IP)) {
            throw new ErrorException('Please set a valid IP address');
        }

        $this->customerIp = $customerIp;

        return $this;
    }

    /**
     * Transaction amount in lowest denomination
     *
     * @var int $paymentTotalAmount
     * @return object (this)
     */
    public function setPaymentTotalAmount($paymentTotalAmount)
    {
        $this->paymentTotalAmount = (int) round($paymentTotalAmount * 100);

        return $this;
    }

    /**
     * The merchant’s invoice number for this transaction
     *
     * @var string $paymentInvoiceNumber
     * @return object (this)
     */
    public function setPaymentInvoiceNumber($paymentInvoiceNumber)
    {
        if (strlen($paymentInvoiceNumber) > 16) {
            throw new ErrorException('Invoice number must not exceed 16 characters in length');
        }

        $this->paymentInvoiceNumber = $paymentInvoiceNumber;

        return $this;
    }

    /**
     * A description of the purchase that the customer is making
     *
     * @var string $paymentInvoiceDescription
     * @return object (this)
     */
    public function setPaymentInvoiceDescription($paymentInvoiceDescription)
    {
        if (strlen($paymentInvoiceDescription) > 64) {
            throw new ErrorException('Invoice description must not exceed 64 characters in length');
        }

        $this->paymentInvoiceDescription = $paymentInvoiceDescription;

        return $this;
    }

    /**
     * The customer invoice number.
     *
     * @var string $paymentInvoiceReference
     * @return object (this)
     */
    public function setPaymentInvoiceReference($paymentInvoiceReference)
    {
        if (strlen($paymentInvoiceReference) > 50) {
            throw new ErrorException('Invoice reference must not exceed 50 characters in length');
        }

        $this->paymentInvoiceReference = $paymentInvoiceReference;

        return $this;
    }

    /**
     * The customer’s title, empty string allowed.
     *
     * @var string (Values: Mr., Ms., Mrs., Miss, Dr., Sir., Prof.) $customerTitle
     * @return object (this)
     */
    public function setCustomerTitle($customerTitle)
    {
        //check to make sure the correct title is used
        $useableValues = array('Mr.', 'Ms.', 'Mrs.', 'Miss', 'Dr.', 'Sir.', 'Prof.');

        if (in_array($customerTitle, $useableValues)) {
            throw new ErrorException('customer title must be of the follow Values: Mr., Ms., Mrs., Miss, Dr., Sir., Prof.');
        }

        $this->customerTitle = $customerTitle;

        return $this;
    }

    /**
     * The customer's first name.
     *
     * @var string $customerFirstName
     * @return object (this)
     */
    public function setCustomerFirstName($customerFirstName)
    {
        if (strlen($customerFirstName) > 50) {
            throw new ErrorException('First name must not exceed 50 characters in length');
        }

        $this->customerFirstName = $customerFirstName;

        return $this;
    }

    /**
     * The customer's last name.
     *
     * @var string $customerLastName
     * @return object (this)
     */
    public function setCustomerLastName($customerLastName)
    {
        if (strlen($customerLastName) > 50) {
            throw new ErrorException('Last name must not exceed 50 characters in length');
        }

        $this->customerLastName = $customerLastName;

        return $this;
    }

    /**
     * The customer's email address.
     *
     * @var string $customerEmail
     * @return object (this)
     */
    public function setCustomerEmail($customerEmail)
    {
        if (filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            throw new ErrorException('Please set a valid Email Address');
        }

        $this->customerEmail = $customerEmail;

        return $this;
    }

    /**
     * set eWay option 1.
     *
     * @var string $option1
     * @return object (this)
     */
    public function setOption1($option1)
    {
        if (strlen($headerText) > 255) {
            throw new ErrorException('Option 1 must not exceed 255 characters in length');
        }

        $this->option1 = $option1;

        return $this;
    }

    /**
     * set eWay option 2.
     *
     * @var string $option2
     * @return object (this)
     */
    public function setOption2($option2)
    {
        if (strlen($headerText) > 255) {
            throw new ErrorException('Option 2 must not exceed 255 characters in length');
        }

        $this->option2 = $option2;

        return $this;
    }

    /**
     * set eWay option 3.
     *
     * @var string $option3
     * @return object (this)
     */
    public function setOption3($option3)
    {
        if (strlen($headerText) > 255) {
            throw new ErrorException('Option 3 must not exceed 255 characters in length');
        }

        $this->option3 = $option3;

        return $this;
    }

    public function createAccessCode()
    {
        $details = array(
                        'Customer' => array(
                                            'Title'     => $this->customerTitle,
                                            'FirstName' => $this->customerFirstName,
                                            'LastName'  => $this->customerLastName,
                                            'Email'     => $this->customerEmail
                                        ),
                        'Options' => array(
                                            'Value' => $this->option1,
                                            'Value' => $this->option2,
                                            'Value' => $this->option3
                                        ),
                        'Payment' => array(
                                            'TotalAmount'        => $this->paymentTotalAmount,
                                            'InvoiceNumber'      => $this->paymentInvoiceNumber,
                                            'InvoiceDescription' => $this->paymentInvoiceDescription,
                                            'InvoiceReference'   => $this->paymentInvoiceReference
                                        ),
                        'RedirectUrl'      => $this->redirectUrl,
                        'CancelUrl'        => $this->cancelUrl,
                        'Method'           => 'CreateAccessCodeShared',
                        'CustomerIP'       => $this->customerIp,
                        'PartnerId'        => $this->partnerId,
                        'TransactionType'  => 'Purchase',
                        'LogoUrl'          => $this->logoUrl,
                        'HeaderText'       => $this->headerText,
                        'CustomerReadOnly' => true,
                        'CustomView'       => $this->customView
                        );

            )
    }

}
