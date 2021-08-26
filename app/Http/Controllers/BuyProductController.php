<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ShoppingList;
use Client\ApiClient;
use Models\Contact;
use Models\Lead;

class BuyProductController extends Controller
{
    public function showPurchasePage(Request $request) {
      $productId = $request->get('id');
      $targetProduct = Product::findOrFail($productId);
      if (!$request->get('quantity'))
        abort(404);
      if ($request->get('submit')) {
        $request->validate([
          'quantity'=>'integer|max:'.$targetProduct->quantity
        ]);
        if (Auth::user()['money'] < $targetProduct->price*$request->get('quantity') == false) {
          $user = User::find(Auth::user()['id']);
          $user->money -= $targetProduct->price * $request->get('quantity');
          $user->save();
          $targetProduct->quantity -= $request->get('quantity');
          $targetProduct->save();
          $newRowInShoppingList = new ShoppingList();
          $newRowInShoppingList->user_id = Auth::user()['id'];
          $newRowInShoppingList->product_id = $productId;
          $newRowInShoppingList->quantity = $request->get('quantity');
          $newRowInShoppingList->save();

          define('TOKEN_FILE', $_SERVER['DOCUMENT_ROOT'].'/token_info_new.json');
          $clientId = '828adff5-ce83-4f59-8f3b-6e93b9388ee5';
          $clientSecret = '0XekCFhLKvJ6DpUD0XOzvnsiyORbn3dCredHp5Y1awzbiOo2kjV8LjCjTkizSl8C';
          $redirectUri = 'https://loca.ru/';
          //$apiClient = new AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
          $apiClient = new ApiClient($clientId, $clientSecret, $redirectUri, 'tbhas2');
          //$apiClient->setAccountBaseDomain('tbhas2.amocrm.ru');
          
          if (!file_exists(TOKEN_FILE)) {
            exit($_SERVER['DOCUMENT_ROOT'].'/token_info_new.json. Access token file not found. use $apiClient->getAccessTokenByCode($code), then $apiClient->saveToken()');
            //todo
          }
          $accessToken = json_decode(file_get_contents(TOKEN_FILE), true);
          $apiClient->setAccessToken($accessToken);
          $contactsService = $apiClient->contacts();
          $leadsService = $apiClient->leads();

          $lead = new Lead();
          $lead->name = $targetProduct->name;
          $lead->price = $targetProduct->price;
          if($contact = $contactsService->isContactExists('766597', Auth::user()['id'])) {
            $lead->attachContact($contact['_embedded']['contacts'][0]['id'], true);
            //var_dump($contact['_embedded']['contacts'][0]);
            //var_dump($contactsService->get(5292663));
          } else {
            $contact = new Contact();
            $contact->first_name = Auth::user()['name'];
            $contact->addCustomField(['id' => 650777, 'value' => Auth::user()['email']]);
            $contact->addCustomField(['id' => 766597, 'value' => Auth::user()['id']]);
            $contact = $contactsService->add($contact);
            $lead->attachContact($contact['id'], true);
          }
            
          $lead = $leadsService->add($lead);
          //var_dump($lead);
          $leadsService->addTask($lead['id'], "Покупка: $targetProduct->name", 24 * 60 * 60);
          $im = $leadsService->get(4092809, 'contacts');
          //var_dump($im->_embedded->contacts);
          $con = $contactsService->get(5292663);
          //var_dump($con);

          
          
          return redirect('/personal_area/shoppingList');
        }
        else
          $message = ['type'=>'secondary', 'text'=>'У вас недостаточно денег'];
      }
      return view('e_shop.PurchasePage', [
        'product'=>$targetProduct,
        'quantity'=>$request->get('quantity'),
        'message'=>$message ?? null
      ]);
    }
}
