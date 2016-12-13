<extends:vault:layout title="[[Vault : Logs]]" class="wide-content"/>
<?php
/**
 * @var \Spiral\LogViewer\Models\Entities\Log $entity
 * @var \Spiral\LogViewer\Models\Entities\Log $lastLog
 */
?>

<define:actions>
    <?php if (!empty($lastLog)) { ?>
        <vault:uri target="logs:removeAll" icon="delete" class="btn red waves-effect waves-light">
            [[Remove all]]
        </vault:uri>

        <vault:uri target="logs:log" icon="edit" class="btn teal waves-effect waves-light"
                   options="<?= ['id' => $lastLog->getName()] ?>">
            [[View last]]
        </vault:uri>
    <?php } ?>
</define:actions>

<define:content>
    <vault:card title="[[Last log:]]">
        <?php if (empty($lastLog)) { ?>
            <p class="grey-text">[[No logs stored.]]</p>
        <?php } else { ?>
            <p>
                <?= $lastLog->getName() ?>
                <span class="grey-text">(<?= $lastLog->getLast()->getFullName() ?>)</span>
            </p>

            <p>
                <?= $lastLog->whenLast() ?>
                <span class="grey-text">(<?= $lastLog->whenLast(true) ?>)</span>
            </p>
        <?php } ?>
    </vault:card>
    <vault:grid source="<?= $source ?>" as="entity" color="teal">
        <grid:cell label="[[Log name:]]">
            <?= $entity->getName() ?>
            <span class="grey-text">(<?= $entity->getLast()->getFullName() ?>)</span>
        </grid:cell>
        <grid:cell label="[[Last updated:]]">
            <?= $entity->whenLast() ?>
            <span class="grey-text">(<?= $entity->whenLast(true) ?>)</span>
        </grid:cell>
        <grid:cell label="[[Total Size:]]">
            <span>
                <?= e(\Spiral\Support\Strings::bytes($entity->getSize())) ?>
            </span>
        </grid:cell>
        <grid:cell label="[[Rotating logs:]]" value="<?= $entity->getCounter() ?>"/>
        <grid:cell style="text-align:right">
            <vault:uri target="logs:log" icon="edit" options="<?= ['id' => $entity->getName()] ?>"
                       class="btn-flat waves-effect"/>
        </grid:cell>
        <grid:cell style="text-align:right">
            <vault:uri target="logs:removeLog" icon="delete" class="btn red waves-effect waves-light"
                       options="<?= ['id' => $entity->getName()] ?>"></vault:uri>
        </grid:cell>
    </vault:grid>
</define:content>
