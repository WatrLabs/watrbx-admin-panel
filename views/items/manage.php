<?php 
use watrlabs\watrkit\pagebuilder;
$pagebuilder = new pagebuilder();
$pagebuilder->set_page_name("Manage asset");
$pagebuilder->buildheader();
?>
<div class="main-content">

    <h2><?=$assetinfo->name?></h2>
    <div>
        <h1>Choose an action:</h1>
        <br><br><br><br>
        <p>
            <a href="#">Manage Info</a><br>
            <a href="#">Toggle Sale (Offsale)</a><br>
            <a href="#">Content Delete</a><br>
        </p>

    </div>

</div>
</body>
</html>