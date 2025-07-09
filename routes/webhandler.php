<?php
use watrlabs\router\Routing;
use watrlabs\watrkit\pagebuilder;
use watrlabs\authentication;

global $router;

function checkhelp() {
    echo "hi";
}



$router->get("/", function() {
    $pagebuilder = new pagebuilder();
    $pagebuilder->get_template("index");
});

$router->get("/login", function() {
    $pagebuilder = new pagebuilder();
    $pagebuilder->get_template("login");
});

$router->get("/logs", function() {
    $pagebuilder = new pagebuilder();
    $pagebuilder->get_template("logs");
});

$router->get("/server-info", function() {
    $pagebuilder = new pagebuilder();
    $pagebuilder->get_template("serverinfo");
});

$router->get("/{userid}/send-message", function($userid) {
    $pagebuilder = new pagebuilder();
    $pagebuilder->get_template("send-message", ["userid"=>$userid]);
});

$router->get('/moderate/{userid}', function($userid){
    $pagebuilder = new pagebuilder();
    $pagebuilder->get_template("moderate", ["userid"=>$userid]);
});

$router->post('/api/v1/login', function(){
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        global $db;

        $auth = new authentication();

        $userinfo = $db->table("users")->where("username", $username)->first();

        if($userinfo !== null){
            $hashedpass = $userinfo->password;

            $result = $auth->login($username, $password);
        
            if(isset($result["code"])){
                if($result["code"] == 200){
                    header("Location: /");
                    die();
                } else {
                    die("An error occured.");
                }
                //create_success("Succesfully Logged In!");
                
            } else {
                die("An error occured.");
            }
               

        } else {
            die("Username or password is incorrect.");
        }
    } else {
        die("An error occured.");
    }
});

$router->get("/403", function() {
    //$pagebuilder = new pagebuilder();
    //$pagebuilder->get_template("index");
    global $router;
    $router->return_status(403);
});

$router->get("/regex/testing/{yea}", function($yea) {
    echo $yea;
});

$router->group('/admin', function($router) {
    
    $router->get("/ban/{user}", function ($user) {
        echo "$user<br>";
    });
    
}, 'checkhelp');