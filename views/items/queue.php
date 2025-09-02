
<?php 
use watrlabs\watrkit\pagebuilder;
use watrbx\thumbnails;
$thumbs = new thumbnails();
$pagebuilder = new pagebuilder();
$pagebuilder->set_page_name("Asset Queue");
$pagebuilder->buildheader();



global $db;

$assetinfo = $db->table("assets")->where("moderation_status", "Pending")->first();

if($assetinfo == null){ ?>

    <div class="main-content">
        <h1>Nothing to review</h1>
    </div>
</body>
</html>

<? 
die(); }

$thumb = $thumbs->get_asset_thumb($assetinfo->id);

?>
<div class="main-content">

    <h2><?=$assetinfo->name?></h2>
    <img src="<?=$thumb?>">
    <br><br>
    <a href="/api/v1/approve-asset?id=<?=$assetinfo->id?>">Approve</a> - <a href="/api/v1/deny-asset?id=<?=$assetinfo->id?>">Deny</a>
</div>
</body>
</html>