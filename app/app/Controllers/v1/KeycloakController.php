<?php 
namespace App\Controllers;  
use Ataccama\Adapters\Keycloak;
use Ataccama\Utils\KeycloakAPI;
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Libraries\Authkeycloak;
use Ataccama\Auth\Auth;

class SigninController extends Controller
{

    public function loginAuth(){
        $parameters = array(
            "host"=>"https://keycloak.sdpmlab.org/",
            "realmId"=>"zerotrust",
            "clientId"=>"zerotrust",
        );
        $keycloak = new Keycloak($parameters);
        $myauth = new Authkeycloak($keycloak);
        $loginUrl = $myauth->getLoginUrl();
        $myauth->authorize($_GET['code']);
        echo view('welcome_message');
    }
}