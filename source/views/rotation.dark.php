<?php
/**
 * @var \Spiral\LogViewer\Models\Entities\Rotation $rotation
 */
?>
<extends:vault:layout title="[[Vault : <?= $rotation->getName() ?> log rotation]]"
                      class="wide-content"/>

<define:actions>
    <vault:uri target="logs:removeRotation" icon="delete" class="btn red waves-effect waves-light"
               options="<?= ['filename' => $rotation->getFullName()] ?>">
        [[Remove]]
    </vault:uri>

    <vault:uri target="logs:log" class="btn-flat teal-text waves-effect" post-icon="trending_flat"
               options="<?= ['id' => $rotation->getName()] ?>">
        [[BACK]]
    </vault:uri>
</define:actions>

<define:content>
    <vault:card title="<?= $rotation->getFullName() ?>">
        <p>
            <?= $rotation->when() ?>
            <span class="grey-text">(<?= $rotation->when(true) ?>)</span>
        </p>
    </vault:card>

    <vault:card style="overflow-x: auto; white-space: nowrap;">
        <?= htmlspecialchars_decode($rotation->getContent()) ?>
    </vault:card>
</define:content>