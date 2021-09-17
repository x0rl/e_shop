<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Jobs\SalesReport;
use App\Jobs\SalesReportWeekly;
use App\Mail\AbsentResponsibleAdmin;
use App\Mail\SalesReportWeekly as MailSalesReportWeekly;
use App\Models\Message;
//use App\Mail\Test;
use App\Models\Token;
use Client\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use League\OAuth2\Client\Token\AccessToken;
use App\Models\ShoppingList;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\test;
use Illuminate\Support\Facades\Auth;

class AmoCRM extends Controller
{
	public function test() {
		$message = Message::find(5);
		return $message->message;
		return '1';
	}
}
