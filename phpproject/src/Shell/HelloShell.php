<?php 
    namespace App\Shell;

	use Cake\Console\Shell;
    use Cake\ORM\TableRegistry;
	
	class HelloShell extends Shell
	{
		public function test()
		{   
		     $this->Brand = 	TableRegistry::get('Brands');
			 $data['name'] = '22222' ;
			 $data['type'] = '1' ;
			 $newbrands = $this->Brand->newEntity() ;
			 $productpatch = $this->Brand->patchEntity($newbrands,$data);
			 $this->Brand->save($productpatch) ;
			$this->out('Hello world.');
		}
	}
?>