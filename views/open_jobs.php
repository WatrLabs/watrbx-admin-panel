
<?php 
use watrlabs\watrkit\pagebuilder;
$pagebuilder = new pagebuilder();
$pagebuilder->set_page_name("Jobs");
$pagebuilder->buildheader();
?>
<div class="main-content">
        <a style="margin-left: 44px">Jobs</a>
        <a href="/gameserver/rcc-instances" style="margin-left: 44px">RCC Instances</a>
        <hr class="secondhr">
        <br>
        <br>
    <p>Note: These only show game related jobs, not render or misc related jobs.</p>
    <table>
        <thead>
            <tr>
                <th class="bold bigger">Job ID</th>
                <th class="bold bigger">Asset ID</th>
                <th class="bold bigger">Port</th>
                <th class="bold bigger">Server</th>
                <th class="bold bigger">RCC Instance</th>
                <th class="bold bigger">Close</th>
            </tr>
        </thead>
        <tbody id="tablebody">
            <?php

            foreach($alljobs as $job){ ?>
                <tr>
                    <td class="bigger"><?=$job->jobid?></td>
                    <td class="bigger"><?=$job->assetid?></td>
                    <td class="bigger"><?=$job->port?></td>
                    <td class="bigger"><?=$job->server?></td>
                    <td class="bigger"><?=$job->rccinstance?></td>
                    <td class="bigger"><a href="/api/v1/close-job?jobid=<?=$job->jobid?>">Close</a></td>
                </tr>
            <? } ?> 
        </tbody>
    </table>

</div>
</body>
</html>