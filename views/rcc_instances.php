
<?php 
use watrlabs\watrkit\pagebuilder;
$pagebuilder = new pagebuilder();
$pagebuilder->set_page_name("RCC");
$pagebuilder->buildheader();
?>
<div class="main-content">
        <a href="/gameserver/open-jobs" style="margin-left: 44px">Jobs</a>
        <a style="margin-left: 44px">RCC Instances</a>
        <hr class="secondhr">
        <br>
        <br>
        <!-- <a class="bigger" href="/gameserver/start-rcc"><s>Start Instance</s></a> -->
        <br><br>
    <table>
        <thead>
            <tr>
                <th class="bold bigger">Guid</th>
                <th class="bold bigger">Server</th>
                <th class="bold bigger">Idle</th>
                <th class="bold bigger">Place ID</th>
                <th class="bold bigger">Port</th>
                <th class="bold bigger">Delete</th>
            </tr>
        </thead>
        <tbody id="tablebody">
            <?php

            foreach($rccinstances as $rcc){ ?>
                <tr>
                    <td class="bigger"><?=$rcc->guid?></td>
                    <td class="bigger"><?=$rcc->serverid?></td>
                    <td class="bigger"><? if($rcc->is_idle == 1) { echo "True"; } else { echo "False"; } ?></td>
                    <td class="bigger"><?=$rcc->placeid?></td>
                    <td class="bigger"><?=$rcc->port?></td>
                    <td class="bigger"><a style="color: red;" href="/api/v1/delete-instance?guid=<?=$rcc->guid?>">Delete</a></td>
                </tr>
            <? } ?> 
        </tbody>
    </table>
    <br>
    <p style="color: red;" ><b>Only delete rcc instance(s) if it's causing issues. It does NOT shut it down!</b></p>
</div>
</body>
</html>