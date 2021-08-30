<?php

namespace App\Listeners;

use App\Events\Buyproduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Models\Lead;
use Models\Contact;
use Client\ApiClient;
use App\Models\Product;
use Illuminate\Support\Facades\Redis;
use App\Models\Token;

class SendToAmoCRM implements ShouldQueue
{
    use InteractsWithQueue;
    public $connection = 'database';
    public $queue = 'default';
    public $delay = 0;
    public $tries = 2;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Buyproduct  $event
     * @return void
     */
    public function handle(BuyProduct $event)
    {
        $apiClient = new ApiClient();
        //$accessToken = json_decode(Token::findOrFail(1)->token, true);
        $contactsService = $apiClient->contacts();
        $leadsService = $apiClient->leads();
        $lead = new Lead();
        $lead->name = $event->product->getName();
        $lead->price = $event->product->getPrice();
        if($contact = $contactsService->isContactExists('766597', 1)) {
            $lead->attachContact($contact['_embedded']['contacts'][0]['id'], true);
        } else {
            $contact = new Contact();
            $contact->first_name = Auth::user()['name'];
            $contact->addCustomField(['id' => 650777, 'value' => Auth::user()['email']]);
            $contact->addCustomField(['id' => 766597, 'value' => Auth::user()['id']]);
            $contact = $contactsService->add($contact);
            $lead->attachContact($contact['id'], true);
        }
        
        $lead = $leadsService->add($lead);
        $leadsService->addTask($lead['id'], "Покупка: ".$event->product->getName(), 24 * 60 * 60);
    }

}
