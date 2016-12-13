<?php
/**
 * @var \Spiral\LogViewer\Models\Entities\Log           $log
 * @var \Spiral\LogViewer\Models\Entities\Rotation|null $rotation
 * @var \Spiral\LogViewer\Models\Entities\Rotation      $entity
 */
?>
<extends:vault:layout title="[[Vault : <?= $log->getName() ?> log]]" class="wide-content"/>

<define:actions>
    <?php if (!empty($log->getCounter())) { ?>
        <vault:uri target="logs:removeLog" icon="delete" class="btn red waves-effect waves-light"
                   options="<?= ['id' => $log->getName()] ?>">
            [[Remove all]]
        </vault:uri>
    <?php } ?>
    <vault:uri target="logs" class="btn-flat teal-text waves-effect" post-icon="trending_flat">
        [[BACK]]
    </vault:uri>
</define:actions>

<define:content>
    <vault:card title="[[Last Rotation:]]">
        <p>
            <?= $log->getName() ?>
            <span class="grey-text">(<?= $log->getLast()->getFullName() ?>)</span>
        </p>

        <p>
            <?= $log->whenLast() ?>
            <span class="grey-text">(<?= $log->whenLast(true) ?>)</span>
        </p>
        <?php if (!empty($rotation)) { ?>
            <p>[[You have only one rotation stored.]]</p>
        <?php } ?>
    </vault:card>

    <?php if (!empty($rotation)) { ?>
        <p class="card-panel-title">[[You have only one log rotation.]]</p>

        <vault:card style="overflow-x: auto; white-space: nowrap;">
            <?= nl2br($rotation->getContent()) ?>
        </vault:card>
    <?php } else { ?>
        <vault:grid source="<?= $log->getRotations() ?>" as="entity" color="teal">
            <grid:cell label="[[Filename:]]" value="<?= $entity->getFullName() ?>"/>
            <grid:cell label="[[Updated:]]">
                <?= $entity->when() ?>
                <span class="grey-text">(<?= $entity->when(true) ?>)</span>
            </grid:cell>
            <grid:cell label="[[Size:]]">
                <span>
                    <?= e(\Spiral\Support\Strings::bytes($entity->getSize())) ?>
                </span>
            </grid:cell>

            <grid:cell style="text-align:right">
                <vault:uri target="logs:rotation" icon="edit" class="btn-flat waves-effect"
                           options="<?= ['filename' => $entity->getFullName()] ?>"/>
            </grid:cell>

            <grid:cell style="text-align:right">
                <vault:uri target="logs:removeRotation" icon="delete"
                           class="btn red waves-effect waves-light"
                           options="<?= ['filename' => $entity->getFullName()] ?>">
                    [[Remove]]
                </vault:uri>
            </grid:cell>
        </vault:grid>
    <?php } ?>
</define:content>