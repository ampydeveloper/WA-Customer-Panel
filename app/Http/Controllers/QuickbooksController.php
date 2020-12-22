<?php
  
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Item;

use App\Models\Settings;
use App\Models\User;
use App\Models\Job;
use App\Models\Service;

class QuickbooksController extends Controller
{
    protected $dataService;

    public function __construct(){
        $tokens = Settings::first(['quick_books_access_token', 'quick_books_refresh_token']);
        $this->dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => config('constant.quickbooks.client_id'),
            'ClientSecret' => config('constant.quickbooks.client_secret'),
            'RedirectURI' => "http://wa.customer.leagueofclicks.com/qbauth",
            'scope' => "com.intuit.quickbooks.accounting", //com.intuit.quickbooks.payment
            'enableRequestLogging' => true,
            'accessTokenKey' => $tokens['quick_books_access_token'],
            'refreshTokenKey' => $tokens['quick_books_refresh_token'],
            'QBORealmID' => config('constant.quickbooks.realm_id'),
            'baseUrl' => config('constant.quickbooks.baseUrl')
        ));
    }
    
    public function getToken(){
        $dataService = $this->dataService;
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
        header("Location: " . $authorizationCodeUrl);
        dd($authorizationCodeUrl);
        // return view('qb_auth', compact('authorizationCodeUrl'));
        // dd($authorizationCodeUrl);
        // $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken(config('constant.quickbooks.auth_code'), config('constant.quickbooks.realm_id'));
        // dump($accessTokenObj);
        // $this->dataService->updateOAuth2Token($accessTokenObj);
        // $CompanyInfo = $dataService->getCompanyInfo();
        // dd($CompanyInfo);
        // $refreshTokenValue = $accessTokenObj->getRefreshToken();
        // $dataService['refreshTokenKey'] = $refreshTokenValue;
        // $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        // $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
        // $dataService->updateOAuth2Token($refreshedAccessTokenObj);
        // $this->dataService = $dataService;
        // $accessToken = $dataService->
        // // dd($authorizationCodeUrl);
        // $_ENV['QUICKBACCESSTOKEN'] = 'value';
    }

    public function getAuthCode(Request $request){
        $code = $request->query()['code'];
        $dataService = $this->dataService;
        $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
        $accessTokenObj = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, config('constant.quickbooks.realm_id'));
        Settings::first()->update(['quick_books_refresh_token' => $accessTokenObj->getRefreshToken(), 'quick_books_access_token' => $accessTokenObj->getAccessToken()]);
        $this->dataService->updateOAuth2Token($accessTokenObj);
    }

    public function __refreshToken(){
        $oauth2LoginHelper = new OAuth2LoginHelper(config('constant.quickbooks.client_id'), config('constant.quickbooks.client_secret'));
        $refreshTokenValue = Settings::first('quick_books_refresh_token')['quick_books_refresh_token'];
        $accessTokenObj = $oauth2LoginHelper->refreshAccessTokenWithRefreshToken($refreshTokenValue);
        $accessTokenValue = $accessTokenObj->getAccessToken();
        $refreshTokenValue = $accessTokenObj->getRefreshToken();
        $this->dataService->updateOAuth2Token($accessTokenObj);
        Settings::first()->update(['quick_books_refresh_token' => $accessTokenObj->getRefreshToken(), 'quick_books_access_token' => $accessTokenObj->getAccessToken()]);
    }

    public function createInvoice($customer_id=null, $job_id=null){
        if($customer_id == null || $job_id == null){
            return "Invalid customer/job id";
        }
        $dataService = $this->dataService;
        try{
            $CompanyInfo = $dataService->getCompanyInfo();
            // dump($CompanyInfo);
            if($CompanyInfo == null){
                $this->__refreshToken();
                $dataService = $this->dataService;
            }
        }catch(Exception $e){
            $this->__refreshToken();
            $dataService = $this->dataService;
        }
        // dump($dataService->Query("SELECT * FROM ACCOUNT"));
        // Check Customer or create if not exists
        $customer_details = User::where('id', $customer_id)->first();
        $getCustomer = $dataService->Query(sprintf("SELECT * FROM CUSTOMER WHERE PrimaryEmailAddr='%s'", $customer_details->email));
        if($getCustomer == null){
            $newCustomer = Customer::create([
                'DisplayName' => $customer_details->full_name,
                'PrimaryPhone' => $customer_details->phone,
                // 'Suffix' => '',
                'Title' => $customer_details->prefix,
                'MiddleName' => '',
                'FamilyName' => $customer_details->last_name,
                'GivenName' => $customer_details->full_name,
                'PrimaryEmailAddr' => [
                    'Address' => $customer_details->email
                ]
            ]);
            $resultObj = $dataService->Add($newCustomer);
            $getCustomer = $dataService->Query(sprintf("SELECT * FROM CUSTOMER WHERE PrimaryEmailAddr='%s'", $customer_details->email));
        }
        // Check Service or create if not exists
        $job_details = Job::where('id', $job_id)->first();
        $service = $job_details->service;
        $getService = $dataService->Query(sprintf("SELECT * FROM ITEM WHERE Name='%s' AND Type='Service'", $service->service_name));
        if($getService == null){
            $newService = Item::create([
                'FullyQualifiedName' => $service->service_name,
                'Name' => $service->service_name,
                'Type' => 'Service',
                'UnitPrice' => $service->price,
                'IncomeAccountRef' => [
                    'name' => 'Services', //Need to confirm which account to use
                    'value' => '1'
                ]
            ]);
            $resultObj = $dataService->Add($newService);
            $getService = $dataService->Query(sprintf("SELECT * FROM ITEM WHERE Name='%s' AND Type='Service'", $service->service_name));
        }

        $invoiceToCreate = Invoice::create([
            "DocNumber" => $job_details->id,
            "Line" => [
                [
                    "Description" => "Services for farm: ".$job_details->farm->farm_address,
                    "Amount" => $job_details->amount,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "ItemRef" => [
                            "value" => $getService[0]->Id,
                            "name" => $getService[0]->Name
                        ]
                    ]
                ]
            ],
            "CustomerRef" => [
                "value" => $getCustomer[0]->Id,
                "name" => $getCustomer[0]->GivenName
            ]
        ]);

        $resultObj = $dataService->Add($invoiceToCreate);
        echo "success";
        // $error = $dataService->getLastError();
        // dump($resultObj);
        // dump($error);
        // $CompanyInfo = $dataService->getCompanyInfo();
        // dd($CompanyInfo);
    }
    
    public function getInvoices($q=null, $type=null){
        $dataService = $this->dataService;
        try {
            $CompanyInfo = $dataService->getCompanyInfo();
            // dump($CompanyInfo);
            if ($CompanyInfo == null) {
                $this->__refreshToken();
                $dataService = $this->dataService;
            }
        } catch (Exception $e) {
            $this->__refreshToken();
            $dataService = $this->dataService;
        }
        $query = "SELECT * FROM Invoice";
        // $invoices = $dataService->Query($query);
        // dump($invoices);
        if($q != null && $type != null){
            if($type == 'customer'){
                $email = User::where('id', $q)->orWhere('first_name', 'LIKE', '%'.$q.'%')->orWhere('last_name', 'LIKE', '%'.$q.'%')->first('email');
                if($email != null){
                    $email = $email['email'];
                    $getCustomer = $dataService->Query(sprintf("SELECT * FROM CUSTOMER WHERE PrimaryEmailAddr='%s'", $email));
                    // dump($getCustomer);
                    if($getCustomer != null){
                        $query .= sprintf(" WHERE CustomerRef='%s'", $getCustomer[0]->Id); 
                    }
                }
            }
            if($type == 'invoice' || $type == 'invoiceDownload'){
                $query .= sprintf(" WHERE DocNumber='%s'", $q); 
            }
            if($type == 'service'){
                $service_name = Service::where('service_name', 'LIKE', '%'.$q.'%')->first('service_name');
                if($service_name != null){
                    $service_name = $service_name['service_name'];
                    $getService = $dataService->Query(sprintf("SELECT * FROM ITEM WHERE Name='%s' AND Type='Service'", strtoupper($service_name)));
                    // dump($getService);
                    if($getService != null){
                        $query .= sprintf(" WHERE Line.SalesItemLineDetail.ItemRef='%s'", $getService[0]->Id); 
                    }
                }
            }
        }
        // dd(public_path() . "/Invoices");
        $invoices = $dataService->Query($query);
        if($type == 'invoiceDownload'){
            $invoiceFile = 'Invoices/Invoice-' . $q . '.pdf';
            if(Storage::exists($invoiceFile)){
                return Storage::download($invoiceFile);
            }
            if($invoices != null && count($invoices) > 0){
                $invoice = $invoices[0];
                // dd($invoice);
                $contents = $dataService->DownloadPDF($invoice, null, true);
                Storage::put($invoiceFile, $contents);
                return Storage::download($invoiceFile);
            }
        }
        return $invoices;
    }
}
