<extends:vault:layout title="[[Vault : Logs]]" class="wide-content"/>

<?php
/**
 * @var array|SplFileInfo[]                  $selector
 * @var \Spiral\LogViewer\Entities\LogFile   $lastLog
 * @var \Spiral\LogViewer\Entities\LogFile   $entity
 * @var \Spiral\LogViewer\Helpers\Timestamps $timestamps
 */
?>

<define:actions>
    <?php if (!empty($lastLog)) { ?>
        <vault:guard permission="vault.logs.removeAll">
            <vault:uri target="logs:removeAll" icon="delete"
                       class="btn red waves-effect waves-light">
                [[Remove all]]
            </vault:uri>
        </vault:guard>
    <?php } ?>
</define:actions>

<define:content>
    <?php if (!empty($lastLog)) { ?>
        <vault:card title="[[Last log:]]">
            <div class="row">
                <div class="col s12 m10">
                    <p>
                        <?= $lastLog->name() ?>
                        <span class="grey-text">(<?= $lastLog->filename() ?>)</span>
                    </p>
                    <p>
                        <?= $timestamps->getTime($lastLog->timestamp()) ?>
                        <span class="grey-text">
                            (<?= $timestamps->getTime($lastLog->timestamp(), true) ?>)
                        </span>
                    </p>
                </div>
                <div class="col s12 m2 right-align">
                    <vault:guard permission="vault.logs.view">
                        <vault:uri target="logs:view" icon="edit"
                                   class="btn teal waves-effect waves-light"
                                   options="<?= ['filename' => $lastLog->name()] ?>">
                            [[View last]]
                        </vault:uri>
                    </vault:guard>
                </div>
            </div>
        </vault:card>
    <?php } ?>

    <vault:grid source="<?= $selector ?>" as="entity" color="teal">
        <grid:cell label="[[Filename:]]">
            <span title="<?= $entity->filename() ?>"><?= $entity->name() ?></span>
        </grid:cell>
        <grid:cell label="[[Last updated:]]">
            <?= $timestamps->getTime($entity->timestamp()) ?>
            <span class="grey-text">(<?= $timestamps->getTime($entity->timestamp(), true) ?>)</span>
        </grid:cell>
        <grid:cell label="[[Size:]]">
            <?= e(\Spiral\Support\Strings::bytes($entity->size())) ?>
        </grid:cell>
        <grid:cell style="text-align:right">
            <vault:guard permission="vault.logs.view">
                <vault:uri target="logs:view" icon="edit"
                           options="<?= ['filename' => $entity->name()] ?>"
                           class="btn-flat waves-effect"/>
            </vault:guard>
            <vault:guard permission="vault.logs.remove">
                <vault:uri target="logs:remove" icon="delete"
                           class="btn red waves-effect waves-light"
                           options="<?= ['filename' => $entity->name()] ?>"></vault:uri>
            </vault:guard>
        </grid:cell>
    </vault:grid>
</define:content>
