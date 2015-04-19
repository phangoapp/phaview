<?php

namespace PhaView;

class View {

	/**
	* Root path for includes app folders
	*/
	
	public $root_path=__DIR__;
	
	/**
	* A set of paths inside of $root_path contining views. If a view is located, the foreach for search the view is break
	*/
	
	public $folder_env=array('views/default', 'app/views');
	
	/**
	* Array for caching the template call...
	*/
	
	public $cache_template=array();
	
	/**
	* The construct for create a view object
	*
	* @param string $folder_base The folder used how base path for search view files
	*/

	public function __construct()
	{
	
		$arr_arg=func_get_args();
		
		if(count($arr_arg)>0)
		{
			
			$this->folder_env=$arr_arg;
		
		}
	
		$this->root_path=getcwd();

	}
	
	/**
	* Very important function used for load views. Is the V in the MVC paradigm. Phango is an MVC framework and has separate code and html.
	*
	* load_view is used for load the views. Views in Phango are php files with a function that have a special name with "View" suffix. For example, if you create a view file with the name blog.php, inside you need create a php function called BlogView(). The arguments of this function can be that you want, how on any normal php function. The view files need to be saved on a "view" folders inside of a theme folder, or a "views/module_name" folder inside of a module being "module_name" the name of the module.
	*
	* @param array $arr_template_values Arguments for the view function of the view.
	* @param string $template Name of the view. Tipically views/$template.php or modules/name_module/views/name_module/$template.php
	* @param string $module_theme If the view are on a different theme and you don't want put the view on the theme, use this variable for go to the other theme.
	*/

	function load_view($arr_template_values_values, $template)
	{

		//First see in controller/view/template, if not see in /views/template
		
		$yes_cache=0;
		
		if(!isset($this->cache_template[$template])) 
		{
		
			foreach($this->folder_env as $base_path)
			{
			
				$view_path=$this->root_path.'/'.$base_path.'/'.$template.'.php';
				
				if(is_file($view_path))
				{
				
					include($view_path);
					
					$yes_cache=1;
					
					break;
				
				}
			
			}
			
			//Search view first on an theme
			
			/*$theme_view=$this->root_path.$container_theme.'views/'.$theme.'/'.strtolower($template).'.php';
			
			//Search view on the real module
			
			$script_module_view=$this->root_path.'modules/'.$this->script_module.'/views/'.strtolower($template).'.php';
			
			//Search view on other module specified.
			
			$module_view=$this->root_path.'modules/'.$module_theme.'/views/'.strtolower($template).'.php';*/
			
			/*if(!is_file($theme_view))
			{
			
				if(!is_file($script_module_view))
				{
				
					if(!is_file($module_view))
					{
					
						$output=ob_get_contents();

						ob_clean();
						
						$check_error_lang[0]='Error while loading template, check that the view exists...';
						$check_error_lang[1]='Error while loading template library '.$template.' in path '.$theme_view.' ,'.$script_module_view.' and '.$module_view.', check config.php or that template library exists... ';

						show_error($check_error_lang[0], $check_error_lang[1], $output);
						
						ob_end_flush();
						
						die;
					
					}
					else
					{
					
						include($module_view);
					
					}
				
				}
				else
				{
				
					include($script_module_view);
				
				}
			
			}
			else
			{
			
				include($theme_view);
			
			}*/

			//If load view, save function name for call write the html again without call include view too
			
			if($yes_cache==1)
			{
			
				$this->cache_template[$template]=basename($template).'View';
				
			}
			else
			{
			
				throw new \Exception('Error: view not found: '.$view_path);
				die;
			
			}

		}
		
		ob_start();

		$func_view=$this->cache_template[$template];
		
		//Load function from loaded view with his parameters

		call_user_func_array($func_view, $arr_template_values);

		$out_template=ob_get_contents();

		ob_end_clean();
		
		return $out_template;

	}
	
	
}

?>