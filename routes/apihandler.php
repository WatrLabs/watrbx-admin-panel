<?php
use watrlabs\router\Routing;
use watrlabs\authentication;
global $router; // IMPORTANT: KEEP THIS HERE!

$router->group('/api/v1', function($router) {

    $router->post('/moderate-user', function(){
        $auth = new authentication();

        if(isset($_POST["account-state"]) && isset($_POST["moderatornote"]) && isset($_POST["internalnote"]) && isset($_POST["userid"]) && $auth->hasaccount()){
            global $db;

            $moderatorinfo = $auth->getuserinfo($_COOKIE["_ROBLOSECURITY"]);

            $accountstatus = $_POST["account-state"];
            $moderatornote = $_POST["moderatornote"];
            $internalnote = $_POST["internalnote"];
            $userid = (int)$_POST["userid"];

            switch ($accountstatus){
                case "remind":
                    $insert = [
                        "userid"=>$userid,
                        "type"=>"reminder",
                        "reviewed"=>time(),
                        "banneduntil"=>time(),
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);
                    
                    break;
                case "warn":
                    $insert = [
                        "userid"=>$userid,
                        "type"=>"warning",
                        "reviewed"=>time(),
                        "banneduntil"=>time(),
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);
                    
                    break;
                case "ban1":
                    $time = time();
                    $daysbanned = 1;
                    $timetoadd = $daysbanned * 86400;
                    $banneduntil = $time + $timetoadd;

                    $insert = [
                        "userid"=>$userid,
                        "type"=>"days",
                        "reviewed"=>time(),
                        "banneduntil"=>$banneduntil,
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "days"=>$daysbanned,
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);

                    break;
                case "ban3":
                    $time = time();
                    $daysbanned = 3;
                    $timetoadd = $daysbanned * 86400;
                    $banneduntil = $time + $timetoadd;

                    $insert = [
                        "userid"=>$userid,
                        "type"=>"days",
                        "reviewed"=>time(),
                        "banneduntil"=>$banneduntil,
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "days"=>$daysbanned,
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);

                    break;
                case "ban7":
                    $time = time();
                    $daysbanned = 7;
                    $timetoadd = $daysbanned * 86400;
                    $banneduntil = $time + $timetoadd;

                    $insert = [
                        "userid"=>$userid,
                        "type"=>"days",
                        "reviewed"=>time(),
                        "banneduntil"=>$banneduntil,
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "days"=>$daysbanned,
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);

                    break;
                case "ban14":
                    $time = time();
                    $daysbanned = 7;
                    $timetoadd = $daysbanned * 86400;
                    $banneduntil = $time + $timetoadd;

                    $insert = [
                        "userid"=>$userid,
                        "type"=>"days",
                        "reviewed"=>time(),
                        "banneduntil"=>$banneduntil,
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "days"=>$daysbanned,
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);

                    break;
                case "delete":
                    $banneduntil = time() * 9;

                    $insert = [
                        "userid"=>$userid,
                        "type"=>"deleted",
                        "reviewed"=>time(),
                        "banneduntil"=>$banneduntil,
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "internalnote"=>$internalnote
                    ];

                    $db->table("moderation")->insert($insert);
                    break;
            }

            die("User Banned.");

        } else {
            var_dump($_POST);
            die("Something was empty."); // I need to do user friendly messages
            
        }
    });
    
    $router->post("/get-user-info", function () {
        if(isset($_POST["username"])){
            header("Content-type: application/json");
            $username = $_POST["username"];

            global $db;

            $userinfo = $db->table("users")->where("username", $username)->first();

            if($userinfo !== null){
                $userarray = [
                    "name"=>$userinfo->username,
                    "id"=>$userinfo->id,
                    "online"=>"Not Implemented",
                    "email"=>$userinfo->email,
                    "roleset"=>"Member",
                    "creationdate"=>date("n/j/Y", $userinfo->regtime),
                    "lastactivity"=>"Unknown"
                ];

                die(json_encode($userarray));
            } else {

            }

        }
    });
    
}, '');