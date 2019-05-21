<div class="interestContracts view">
<h2><?php echo __('Interest Contract'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Users'); ?></dt>
		<dd>
			<?php echo $this->Html->link($interestContract['Users']['name'], array('controller' => 'users', 'action' => 'view', $interestContract['Users']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Author'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['author']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['category']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ciudad'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['ciudad']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Contenido'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['contenido']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Departamento'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['departamento']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['nombre']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Valor'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['valor']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Num Constancia'); ?></dt>
		<dd>
			<?php echo h($interestContract['InterestContract']['num_constancia']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Interest Contract'), array('action' => 'edit', $interestContract['InterestContract']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Interest Contract'), array('action' => 'delete', $interestContract['InterestContract']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $interestContract['InterestContract']['id']))); ?> </li>
		<li><?php echo $this->Html->link(__('List Interest Contracts'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Interest Contract'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Users'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
