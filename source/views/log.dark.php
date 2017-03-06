<?php
/**
 * @var \Spiral\LogViewer\Entities\LogFile   $log
 * @var \Spiral\LogViewer\Helpers\Timestamps $timestamps
 */
?>
<extends:vault:layout title="[[Vault : <?= $log->name() ?> log]]" class="wide-content"/>

<define:actions>
    <vault:guard permission="vault.logs.remove">
        <vault:uri target="logs:remove" icon="delete" class="btn red waves-effect waves-light"
                   options="<?= ['filename' => $log->name()] ?>">
            [[Remove all]]
        </vault:uri>
    </vault:guard>
    <vault:uri target="logs" class="btn-flat teal-text waves-effect" post-icon="trending_flat">
        [[BACK]]
    </vault:uri>
</define:actions>

<define:actions>
    <vault:guard permission="vault.logs.remove">
        <vault:uri target="logs:remove" icon="delete" class="btn red waves-effect waves-light"
                   options="<?= ['filename' => $log->name(), 'backToList' => 1] ?>">
            [[Remove]]
        </vault:uri>
    </vault:guard>
    <vault:uri target="logs" class="btn-flat teal-text waves-effect" post-icon="trending_flat">
        [[BACK]]
    </vault:uri>
</define:actions>

<define:content>
    <vault:guard permission="vault.logs.view">
        <vault:card>
            <p>
                <?= $log->name() ?>
                <span class="grey-text">(<?= $log->filename() ?>)</span>
            </p>
            <p class="grey-text">
                <?= $timestamps->getTime($log->timestamp()) ?>
                (<?= $timestamps->getTime($log->timestamp(), true) ?>) </p>
        </vault:card>

        <vault:card style="overflow-x: auto; white-space: nowrap;">
            <?= nl2br($log->content()) ?>
        </vault:card>
    </vault:guard>
</define:content>