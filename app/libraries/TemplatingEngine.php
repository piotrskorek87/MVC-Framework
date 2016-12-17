<?php 

class TemplatingEngine{

	public 	$template;

	public 	$dictionary = array('/{{(.*)}}/U'				=> '<?php echo $1; ?>',	
								'/@extends\s*\((.*)\)/'		=> '',
								'/@section\s*\((.*)\)/'		=> '',
								'/@include\s*\((.*)\)/'		=> '<?php include($1); ?>',
								'/@if\s*\((.*)\)/'			=> '<?php if($1): ?>',
								'/@elseif\s*\((.*)\)/'		=> '<?php elseif($1): ?>',
								'/@else/'					=> '<?php else: ?>',
								'/@endif/'					=> '<?php endif; ?>',
								'/@foreach\s*\((.*)\)/'		=> '<?php foreach($1): ?>',
			           			'/@endforeach/'				=> '<?php endforeach; ?>',
								);

	public function parse($path){
		$this->extendFunction($path);
		$section = $this->includeFunction($path);	

		foreach($this->dictionary as $key => $value)
		{
		  $section = preg_replace($key, $value, $section);
		}

		$section = preg_replace('/@stop/', '', $section);

		$page = preg_replace('/@render(.*)@stop/s', $section, $this->template);

		file_put_contents('tempTemplate.php', $page);

		//return tempTemplate.php;
	}

	public function includeFunction($path){
		$section = file_get_contents($path);
		$section = preg_replace_callback('/@include\s*\(\'(.*)\'\)/', function($matches){
			$include = file_get_contents($matches[1]);

				$include = $this->includeFunction($matches[1]);

				foreach($this->dictionary as $key => $value)
				{
				  $include = preg_replace($key, $value, $include);
				}
			return $include;
		}, $section);
		return $section;
	}

	public function extendFunction($path){
		$section = file_get_contents($path);
		$section = preg_replace_callback('/@extends\s*\(\'(.*)\'\)/', function($matches){

			$template = file_get_contents($matches[1]);

			$template = $this->includeFunction($matches[1]);

			foreach($this->dictionary as $key => $value)
			{
			  $template = preg_replace($key, $value, $template);
			}
			$this->template = $template;
			return '';
		}, $section);
		return $section;
	}
}

