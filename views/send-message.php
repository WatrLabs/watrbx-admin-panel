<?php 
use watrlabs\watrkit\pagebuilder;
use watrlabs\authentication;
$auth = new authentication();
$pagebuilder = new pagebuilder();
$pagebuilder->set_page_name("Moderate A User");
$pagebuilder->buildheader();

$userinfo = $auth->getuserbyid($userid);

if($userinfo == null){
    die('<div class="main-content"><h1>User does not exist.</h1></div></body></html>');
}


?>
<div class="main-content">
        <form action="/api/v1/moderate-user" method="POST">
        <div class="form-group">
                <label for="user-message">Subject:</label>
                <textarea id="user-message" name="moderatornote"></textarea>
            </div>
            <input type="text" name="userid" value="<?=$userinfo->id?>" class="hidden">
        </form>
        <div class="hidden">
            <span style="color: green; margin-top: 16px;">✔Action completed successfully.</span>
        </div>
    </div>
</body>
</html>