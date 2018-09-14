<?php
   namespace App\Model\Behavior;
   use Cake\ORM\Behavior;
   use Cake\Event\Event;
   use Cake\ORM\Entity;
   use Cake\ORM\Query;
   use Cake\Utility\Inflector;
	
	class SluggableBehavior extends Behavior
	{
		public function initialize(array $config)
		{
		// Some initialization code here
		}
		protected $_defaultConfig = [
        'field' => 'name',
        'slug' => 'slug',
        'replacement' => '-',
		];

		public function slug(Entity $entity)
		{
			$config = $this->config();
			$value = $entity->get($config['field']);
			if(!empty($value)){
				$value = $entity->get($config['field']);
		} else {
			  $value = '' ;
		}
			$entity->set($config['slug'], Inflector::slug($value, $config['replacement']));
		}

		public function beforeSave(Event $event, EntityInterface $entity)
		{
			$this->slug($entity);
		}
	}
?>