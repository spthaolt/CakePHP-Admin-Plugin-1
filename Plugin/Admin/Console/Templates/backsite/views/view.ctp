<h2><?php echo "<?php  echo __('{$singularHumanName}') . ': ' . \${$singularVar}['{$modelClass}']['toString'];?>";?></h2>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><?php echo "<?php  echo __('{$singularHumanName} Details');?>";?></a></li>
<?php
    //create tabs for associated models
    $tabnr = 1;
    if (!empty($associations['hasOne'])) :
        foreach ($associations['hasOne'] as $alias => $details):
            ++$tabnr;
            echo "\t\t<li><a href=\"#tabs-{$tabnr}\"><?php echo __('" . Inflector::humanize($details['controller']) . "');?></a></li>\n";
        endforeach;
    endif;
    if (!empty($associations['hasMany'])) :
        foreach ($associations['hasMany'] as $alias => $details):
            ++$tabnr;
            echo "\t\t<li><a href=\"#tabs-{$tabnr}\"><?php echo __('" . Inflector::humanize($details['controller']) . "');?></a></li>\n";
        endforeach;
    endif;
    if (!empty($associations['hasAndBelongsToMany'])) :
        foreach ($associations['hasAndBelongsToMany'] as $alias => $details):
            ++$tabnr;
            echo "\t\t<li><a href=\"#tabs-{$tabnr}\"><?php echo __('" . Inflector::humanize($details['controller']) . "');?></a></li>\n";
        endforeach;
    endif;
?>
    </ul>

    <div id="tabs-1">
        <div class="<?php echo $pluralVar;?> view">
            <dl>
            <?php
            foreach ($fields as $field) {
            	$isKey = false;
            	if (!empty($associations['belongsTo'])) {
            		foreach ($associations['belongsTo'] as $alias => $details) {
            			if ($field === $details['foreignKey']) {
            				$isKey = true;
                            $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
                            $associationControllerPath = $details['controller'];
            				echo "\t\t\t\t<dt><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></dt>\n";
                            echo "\t\t<dd>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['toString'], array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t\t&nbsp;\n\t\t</dd>\n";
                            break;
            			}
            		}
            	}
            	if ($isKey !== true) {
            		echo "\t\t\t\t<dt><?php echo __('" . Inflector::humanize($field) . "'); ?></dt>\n";
            		echo "\t\t\t\t<dd>\n\t\t\t";
                    switch($field) {
                        case 'image1':
                        case 'image2':
                            echo "<?php if(!empty(\${$singularVar}['{$modelClass}']['{$field}'])) echo \$this->Html->image('/files/images/' . \${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
                            break;
                        default:
                            echo "<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n";
                            break;
                    }
                    echo "\t\t\t&nbsp;\n\t\t</dd>\n";
            	}
            }

            ?>
            	</dl>
        </div>
        <div class="actions">
            <h3><?php echo "<?php echo __('Actions'); ?>"; ?></h3>
            <ul>
        <?php
            echo "\t\t\t\t<li><?php echo \$this->Html->link(__('Edit " . $singularHumanName ."'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
            echo "\t\t\t\t<li><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), null, __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
            echo "\t\t\t\t<li><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index')); ?> </li>\n";
            echo "\t\t\t\t<li><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add')); ?> </li>\n";
        ?>
            </ul>
        </div>
    </div>

<?php
    $tabnr = 1;
if (!empty($associations['hasOne'])) :
	foreach ($associations['hasOne'] as $alias => $details):
        $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
        $associationControllerPath = $details['controller'];
    ?>
    <div id="tabs-<?php echo ++$tabnr; ?>">
        <div class="related">
            <h3><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "');?>";?></h3>
        <?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n";?>
            <dl>
        <?php
            foreach ($details['fields'] as $field) {
                echo "\t\t<dt><?php echo __('" . Inflector::humanize($field) . "');?></dt>\n";
                echo "\t\t<dd>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}'];?>\n&nbsp;</dd>\n";
            }
        ?>
            </dl>
        <?php echo "<?php endif; ?>\n";?>
            <div class="actions">
                <ul>
                    <li><?php echo "<?php echo \$this->Html->link(__('Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?></li>\n";?>
                </ul>
            </div>
        </div>
    </div>
	<?php
	endforeach;
endif;

if(empty($associations['hasMany'])) $associations['hasMany'] = array();

foreach ($associations['hasMany'] as $alias => $details):
    $otherSingularVar = Inflector::variable($alias);
    $otherPluralHumanName = Inflector::humanize($details['controller']);
    $otherPluralVar = Inflector::variable($details['controller']);
    $otherControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
    $otherControllerPath = $details['controller'];
        ?>
    <div id="tabs-<?php echo ++$tabnr; ?>">
        <div class="related">
        	<h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "');?>";?></h3>
            <div class="<?php echo $otherPluralVar;?> table">
            <?php echo "<?php echo \$this->element('../{$backendPluginName}{$otherControllerName}/table');?>\n"; ?>
            </div>
            <div class="actions">
                <ul>
                    <li><?php echo "<?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$otherControllerPath}', 'action' => 'add', '{$details['foreignKey']}' => \${$singularVar}['{$modelClass}']['{$primaryKey}']));?>";?> </li>
                </ul>
            </div>
        </div>
    </div>
<?php
endforeach;

//HABTM
if(empty($associations['hasAndBelongsToMany'])) $associations['hasAndBelongsToMany'] = array();

$i = 0;
foreach ($associations['hasAndBelongsToMany'] as $alias => $details):
	$otherSingularVar = Inflector::variable($alias);
	$otherPluralHumanName = Inflector::humanize($details['controller']);
    $associationControllerName = Inflector::pluralize(Inflector::camelize($details['controller']));
    $associationControllerPath = $details['controller'];
	?>
    <div id="tabs-<?php echo ++$tabnr; ?>">
        <div class="related">
        	<h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "');?>";?></h3>
        	<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])):?>\n";?>
        	<table cellpadding = "0" cellspacing = "0">
        	<tr>
        <?php
        			foreach ($details['fields'] as $field) {
        				echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
        			}
        ?>
        		<th class="actions"><?php echo "<?php echo __('Actions');?>";?></th>
        	</tr>
        <?php
        echo "\t<?php
        		\$i = 0;
        		foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
        		echo "\t\t<tr>\n";
        			foreach ($details['fields'] as $field) {
        				echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}'];?></td>\n";
        			}

        			echo "\t\t\t<td class=\"actions\">\n";
        			echo "\t\t\t\t<?php echo \$this->Html->link(__('View'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
        			echo "\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
        			echo "\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), null, __('Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
        			echo "\t\t\t</td>\n";
        		echo "\t\t</tr>\n";

        echo "\t<?php endforeach; ?>\n";
        ?>
        	</table>
        <?php echo "<?php endif; ?>\n\n";?>
        	<div class="actions">
        		<ul>
        			<li><?php echo "<?php echo \$this->Html->link(__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('plugin' => '{$backendPluginNameUnderscored}', 'controller' => '{$backendPluginNameUnderscored}_{$associationControllerPath}', 'action' => 'add'));?>";?> </li>
        		</ul>
        	</div>
        </div>
    </div>
<?php endforeach;?>
</div>
<script type="text/javascript">
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>