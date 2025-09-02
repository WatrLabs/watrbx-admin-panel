<?php
use watrlabs\router\Routing;
use watrbx\admin;
use watrbx\messages\templates;
use watrlabs\authentication;
use watrbx\gameserver;
global $router; // IMPORTANT: KEEP THIS HERE!

$router->group('/api/v1', function($router) {

    $router->get('/approve-asset', function(){
        
        if(isset($_GET["id"])){
            $id = (int)$_GET["id"];

            global $db;

            $assetinfo = $db->table("assets")->where("id", $id)->first();

            if($assetinfo){
                $update = [
                    "moderation_status"=>"Approved"
                ];

                $db->table("assets")->where("id", $id)->update($update);
                header("Location: /items/queue");
                die();
            }

        }

    });

    $router->get('/deny-asset', function(){
        
        if(isset($_GET["id"])){
            $id = (int)$_GET["id"];

            global $db;

            $assetinfo = $db->table("assets")->where("id", $id)->first();

            if($assetinfo){
                $update = [
                    "moderation_status"=>"Deleted"
                ];

                $db->table("assets")->where("id", $id)->update($update);
                header("Location: /items/queue");
                die();
            }

        }

    });

    $router->post('/update-config', function(){

        if(isset($_POST["id"]) && isset($_POST["newvalue"])){

            $configid = (int)$_POST["id"];
            $newvalue = $_POST["newvalue"];

            global $db;

            $configinfo = $db->table("site_config")->where("id", $configid)->first();

            if($configinfo !== null){
                $update = [
                    "value"=>$newvalue
                ];

                $db->table("site_config")->where("id", $configid)->update($update);

                header("Location: /config");
                die();
            }
        }

        http_response_code(404);
        die();
    });

    $router->post('/send-personal-message', function(){
        $auth = new authentication();

        if(isset($_POST["userid"]) && isset($_POST["subject"]) && isset($_POST["content"])){
            $fromuser = $auth->getuserinfo($_COOKIE["_ROBLOSECURITY"]);
            $touser = (int)$_POST["userid"];

            $subject = htmlspecialchars($_POST["subject"]);
            $content = htmlspecialchars($_POST["content"]);

            $admin = new admin();

            $admin::pm_user($touser, $subject, $content);
            $admin->log_action("send_message", $subject, $fromuser->id);
            die("user messaged.");

        } else {
            http_response_code(400);
            die("Bad Request");
        }
    });

    $router->post('/moderate-user', function(){
        $auth = new authentication();
        $admin = new admin();

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
                        "banneduntil"=>null,
                        "moderator"=>$moderatorinfo->id,
                        "moderatornote"=>htmlspecialchars($moderatornote),
                        "internalnote"=>$internalnote
                    ];

                    $offendinguser = $auth->getuserbyid($userid);

                    $db->table("users")->where("id", $offendinguser->id)->update(["blurb"=>"[ Content Deleted ]"]);
                    $db->table("feed")->where("owner", $offendinguser->id)->delete();
                    $db->table("messages")->where("userfrom", $offendinguser->id)->delete();
                    $db->table("comments")->where("userid", $offendinguser->id)->delete();
                    $db->table("moderation")->insert($insert);
                    break;
            }

            $admin->log_action("moderate_user", $internalnote, $moderatorinfo->id);

            die("User Banned.");

        } else {
            var_dump($_POST);
            die("Something was empty."); // I need to do user friendly messages
            
        }
    });

    $router->post('/award-membership', function(){

        global $db;

        $admin = new admin();

        $goodroles = ["None", "BuildersClub", "TurboBuildersClub", "OutrageousBuildersClub"];

        $bcitemid = 677;
        $tbcitemid = 678;
        $obcitemid = 679;

        if(isset($_POST["userid"]) && isset($_POST["membership"])){

            $userid = (int)$_POST["userid"];
            $membership = $_POST["membership"];

            if(in_array($membership, $goodroles)){
                $update = [
                    "membership"=>$membership
                ];

                if(str_contains($membership, "BuildersClub")){

                    switch ($membership){
                        case "BuildersClub":
                            $hasitem = $db->table("ownedassets")->where("userid", $userid)->where("assetid", $bcitemid)->first();
                            if($hasitem == null){
                                $insert = [
                                    "userid"=>$userid,
                                    "assetid"=>$bcitemid,
                                    "time"=>time()
                                ];

                                $db->table("ownedassets")->insert($insert);
                            }
                            $admin::pm_user($userid, "Membership Update", "Your account membership has been updated.\nYou now have Builders Club!");
                            break;
                        case "TurboBuildersClub":
                            $hasitem = $db->table("ownedassets")->where("userid", $userid)->where("assetid", $tbcitemid)->first();
                            if($hasitem == null){
                                $insert = [
                                    "userid"=>$userid,
                                    "assetid"=>$tbcitemid,
                                    "time"=>time()
                                ];

                                $db->table("ownedassets")->insert($insert);
                            }
                            $admin::pm_user($userid, "Membership Update", "Your account membership has been updated.\nYou now have Turbo Builders Club!");
                            break;
                        case "OutrageousBuildersClub":
                            $hasitem = $db->table("ownedassets")->where("userid", $userid)->where("assetid", $obcitemid)->first();
                            if($hasitem == null){
                                $insert = [
                                    "userid"=>$userid,
                                    "assetid"=>$obcitemid,
                                    "time"=>time()
                                ];

                                $db->table("ownedassets")->insert($insert);
                            }
                            $admin::pm_user($userid, "Membership Update", "Your account membership has been updated.\nYou now have Outrageous Builders Club!");
                            break;
                    }   

                }

                if($membership == "None"){
                    $admin::pm_user($userid, "Membership Update", "Your account membership has been updated.\nYou no longer have Builders Club.\n\nSad to see you go!");
                }

                $db->table("users")->where("id", $userid)->update($update);

                header("Location: /view/$userid");
                die();
                
            }

        }
    });

    $router->post('/award-currency', function(){

        $auth = new authentication();
        $admin = new admin();
        global $db;

        if(isset($_POST["userid"])){
            $userid = (int)$_POST["userid"];

            $userinfo = $auth->getuserbyid($userid);

            if(!empty($userid) && $userinfo !== null){
                if(isset($_POST["robux"])){
                    $robux = (int)$_POST["robux"];

                    if(!empty($robux)){
                        $update = [
                            "robux"=>$userinfo->robux + $robux
                        ];

                        $db->table("users")->where("id", $userinfo->id)->update($update);
                        
                    }

                }

                if(isset($_POST["tix"])){
                    $tix = (int)$_POST["tix"];

                    if(!empty($tix)){
                        $update = [
                            "tix"=>$userinfo->tix + $tix
                        ];

                        $db->table("users")->where("id", $userinfo->id)->update($update);
                        
                    }

                }

                
                $admin::pm_user($userid, "You've been awarded currency.", "Your account balance has been updated!");
                header("Location: /view/$userid");
                die();
            }

        } else {
            header("Location: /");
            die();
        }
    });

    $router->get('/close-job', function(){

        $gameserver = new gameserver();
        global $db;

        if(isset($_GET["jobid"])){

            $jobid = $_GET["jobid"];

            $jobinfo = $db->table("jobs")->where("jobid", $jobid)->first();

            if($jobinfo !== null){
                $result = $gameserver->end_job($jobid);
                if($result !== false){
                    header("Location: /gameserver/open-jobs");
                    die();
                } else {
                    die("Failed to close job.");
                }
            }

        }

    });

    $router->get('/delete-instance', function(){

        $gameserver = new gameserver();
        global $db;

        if(isset($_GET["guid"])){

            $guid = $_GET["guid"];

            $rccinfo = $db->table("rccinstances")->where("guid", $guid)->first();

            if($rccinfo !== null){
                $db->table("rccinstances")->where("guid", $guid)->delete();
                header("Location: /gameserver/rcc-instances");
                die();
            }

        }

    });

    $router->post('/award-item', function(){

        $auth = new authentication();
        $admin = new admin();
        global $db;

        if(isset($_POST["userid"]) && isset($_POST["itemid"])){
            $userid = (int)$_POST["userid"];
            $itemid = (int)$_POST["itemid"];

            if($userid !== null){
                $assetinfo = $db->table("assets")->where("id", $itemid)->first();

                if($assetinfo !== null){
                    $insert = [
                        "userid"=>$userid,
                        "assetid"=>$itemid,
                        "time"=>time()
                    ];

                    $db->table("ownedassets")->insert($insert);

                    $admin::pm_user($userid, "You've been awarded!", "Your inventory has been updated.\n\nYou have recieved " . $assetinfo->name);
                    header("Location: /view/$userid");
                    die();

                }

            }

        }

        header("Location: /");
        die();

    });
    
    $router->post("/get-user-info", function () {

        $auth = new authentication();

        if(isset($_POST["username"])){
            header("Content-type: application/json");
            $username = $_POST["username"];

            global $db;

            $userinfo = $db->table("users")->where("username", $username)->first();

            if($userinfo !== null){
                $userarray = [
                    "name"=>$userinfo->username,
                    "id"=>$userinfo->id,
                    "online"=>$auth->is_online($userinfo->id),
                    "email"=>$userinfo->email,
                    "roleset"=>"Member",
                    "creationdate"=>date("n/j/Y", $userinfo->regtime),
                    "lastactivity"=>date('Y-m-d H:i:s',$userinfo->last_visit)
                ];

                die(json_encode($userarray));
            } else {

            }

        }
    });
    
}, '');