<?php
/**
 * @type \DebugKit\View\AjaxView $this
 * @type \DebugKit\Model\Entity\Request $toolbar
 */

use Cake\Routing\Router;
use Cake\Core\Configure;

?>
<div id="panel-content-container">
    <span id="panel-close" class="button-close">&times;</span>
    <div id="panel-content">
        <!-- content here -->
    </div>
</div>
<!--
<ul id="toolbar" class="toolbar">
    <?php foreach ($toolbar->panels as $panel): ?>
    <li class="panel hidden" data-id="<?php echo $panel->id ?>">
        <span class="panel-button">
            <?php echo h($panel->title) ?>
        </span>
        <?php if (strlen($panel->summary)): ?>
        <span class="panel-summary">
            <?php echo h($panel->summary) ?>
        </span>
        <?php endif ?>
    </li>
    <?php endforeach; ?>
    <li id="panel-button">
        <?php echo $this->Html->image('DebugKit.cake.icon.png', [
            'alt' => 'Debug Kit', 'title' => 'CakePHP ' . Configure::version() . ' Debug Kit'
        ]) ?>
    </li>
</ul>-->
<?php $this->Html->script('DebugKit.debug_kit', [
    'block' => true,
    'id' => '__debug_kit_app',
    'data-id' => $toolbar->id,
    'data-url' => Router::url('/', true),
    'data-webroot' => $this->request->webroot,
]) ?>
