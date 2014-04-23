<?php namespace Benhawker\EwayShared;

use Benhawker\EwayShared\Exceptions\EwaySharedMissingFieldError;
use Benhawker\EwayShared\Exceptions\EwaySharedValidateFieldError;
use Benhawker\EwayShared\Library\Curl;

class Eway
{

    /**
     *  Sandbox or live (default: true)
     *
     * @var bool
     */
    protected $sandbox;

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
     * Hold the curl object
     *
     * @var object
     */
    protected $curl;

    public function __construct($apiKey, $password, $sandbox = false)
    {
        $this->sandbox  = $sandbox;
        $this->curl     = new Curl($apiKey, $password);
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
     * Set the web address the customer is redirected to with the result of the action.
     *
     * @param  string (url) $redirectUrl
     * @return object       (this)
     */
    public function setRedirectUrl($redirectUrl)
    {
        if (!filter_var($redirectUrl, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            throw new EwaySharedValidateFieldError('Redirect Url was not a valid URL');
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
            throw new EwaySharedValidateFieldError('Cancel Url was not a valid URL');
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
            throw new EwaySharedValidateFieldError('Logo Url was not a valid URL');
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
            throw new EwaySharedValidateFieldError('Header text must not exceed 255 characters in length');
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
        if (!filter_var($customerIp, FILTER_VALIDATE_IP)) {
            throw new EwaySharedValidateFieldError('Please set a valid IP address');
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
        $this->paymentTotalAmount = $paymentTotalAmount * 100;

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
            throw new EwaySharedValidateFieldError('Invoice number must not exceed 16 characters in length');
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
            throw new EwaySharedValidateFieldError('Invoice description must not exceed 64 characters in length');
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
            throw new EwaySharedValidateFieldError('Invoice reference must not exceed 50 characters in length');
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

        if (!in_array($customerTitle, $useableValues)) {
            throw new EwaySharedMissingFieldError('customer title must be of the follow Values: Mr., Ms., Mrs., Miss, Dr., Sir., Prof.');
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
            throw new EwaySharedValidateFieldError('First name must not exceed 50 characters in length');
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
            throw new EwaySharedValidateFieldError('Last name must not exceed 50 characters in length');
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
        if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            throw new EwaySharedValidateFieldError('Please set a valid Email Address');
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
        if (strlen($option1) > 255) {
            throw new EwaySharedValidateFieldError('Option 1 must not exceed 255 characters in length');
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
        if (strlen($option2) > 255) {
            throw new EwaySharedValidateFieldError('Option 2 must not exceed 255 characters in length');
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
        if (strlen($option3) > 255) {
            throw new EwaySharedValidateFieldError('Option 3 must not exceed 255 characters in length');
        }

        $this->option3 = $option3;

        return $this;
    }

    /**
     * Create access code
     *
     * @return array results fromn eway
     */
    public function createAccessCode()
    {
        //crerate array
        $details = array(
                        'Customer' => array(
                                            'Title'     => $this->customerTitle,
                                            'FirstName' => $this->customerFirstName,
                                            'LastName'  => $this->customerLastName,
                                            'Email'     => $this->customerEmail
                                        ),
                        'Options' => array(
                                            array('Value' => $this->option1),
                                            array('Value' => $this->option2),
                                            array('Value' => $this->option3)
                                        ),
                        'Payment' => array(
                                            'TotalAmount'        => $this->paymentTotalAmount,
                                            'InvoiceNumber'      => $this->paymentInvoiceNumber,
                                            'InvoiceDescription' => $this->paymentInvoiceDescription,
                                            'InvoiceReference'   => $this->paymentInvoiceReference
                                        ),
                        'RedirectUrl'         => $this->redirectUrl,
                        'CancelUrl'           => $this->cancelUrl,
                        'Method'              => 'ProcessPayment',
                        'TransactionType'     => 'Purchase',
                        'LogoUrl'             => $this->logoUrl,
                        'HeaderText'          => $this->headerText,
                        'CustomerReadOnly'    => true,
                        'CustomView'          => $this->customView,
                        'VerifyCustomerPhone' => false,
                        'VerifyCustomerEmail' => false
                        );

        //right endpoint depending if sandbox
        $url = ($this->sandbox == true) ? 'https://api.sandbox.ewaypayments.com/CreateAccessCodeShared.json' : 'https://api.ewaypayments.com/CreateAccessCode.json';

        //get results
        return $this->curl->post($url, $details);
    }

    /**
     * Redirects to eway
     *
     * @return redirect
     */
    public function redirectToPayment()
    {
        $result = $this->createAccessCode();

        //redirect to payment page
        header('Location: ' . $result['SharedPaymentUrl']);
        die();
    }

    /**
     * the access is returned in the url you would generally use $_GET['AccessCode']
     *
     * @param  string $accessCode eWay access Code
     * @return array  results of Transaction
     */
    public function getTransactionResults($accessCode)
    {
        //right endpoint depending if sandbox
        $url = ($this->sandbox == true) ? 'https://api.sandbox.ewaypayments.com/GetAccessCodeResult.json' : 'https://api.ewaypayments.com/GetAccessCodeResult.json';

        //get results
        return $this->curl->post($url, array('AccessCode' => $accessCode));
    }
}
