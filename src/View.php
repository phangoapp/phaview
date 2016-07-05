<?php

namespace PhangoApp\PhaView;
use PhangoApp\PhaUtils\Utils;

class View {

	/**
	* Root path for includes app folders
	*/
	
	static public $root_path=".";
	
	/**
	* A set of paths inside of $root_path contining views. If a view is located, the foreach for search the view is break
	*/
	
	static public $folder_env=array('views/default');
	
	/**
	* A set of paths inside of $root_path contining views. If a view is located, the foreach for search the view is break
	*/
	
	static public $media_env=array('views/default');
	
	/**
	* Basepath of the theme
	*/
	
	static public $theme='';
	
	/**
	* Array for caching the template call...
	*/
	
	static public $cache_template=array();
	
	/**
	* Path of static media files (javascript, images and css).
	*/
	
	static public $path_media='media';
	
	/**
	* Url of static media files (javascript, images and css).
	*/
	
	static public $url_media='media';
	
	/**
	* Media .php path.
	*/
	
	static public $php_file='showmedia.php';

	/**
	* Internal property used for see if this media are in production
	*/
	
	static protected $production=0;
	
	/**
	* Internal property used for define the method used for retrieve the media files.
	*/
	
	static protected $func_media='dynamic_get_media_url';
	
	/**
	* An array where you can add new css in all views. For example, a view that use a special css can use this array and you can insert the value 'special.css' and the principal View can use loadCss method for load all css writed in the array by children views.
	*/
	
	static public $css=array();
	
	/**
	* An array where you can add new css in all views from modules. For example, a view that use a special css can use this array and you can insert the value 'special.css' and the principal View can use load_css method for load all css writed in the array by children views.
	*/
	
	static public $css_module=array();
	
	/**
	* An array where you can add new js in all views. For example, a view that use a special js can use this array and you can insert the value 'special.js' in principal View using loadJs method for load all js writed in the array by children views.
	*/
	
	static public $js=array();
	
	/**
	* An array where you can add new js in all views. For example, a view that use a special js can use this array and you can insert the value 'special.js' in principal View using load_js method for load all js writed in the array by children views.
	*/
	
	static public $js_module=array();
	
	/**
	* An array where you can add new code in <header> tag. For example, a view that need a initialitation code in the principal view can use this array and you can insert the code in principal View using loadHeader method for load all header code writed in the array by children views.
	*/
	
	static public $header=array();
	
	/**
	* An array with config_paths for load configurations.
	*/
	
	static public $config_path=['settings'];
    
    /**
    * A boolean property for set if views are escaped by default
    */
    
    static public $escape=true;
    
    /**
    * The directory where formatted views are saved.
    */
	
    static public $cache_directory='cache/templates';
    
    /**
    * Debug 
    */
	
    static public $debug_tpl=false;
    
	/**
	* The construct for create a view object
	*
	* @param string $folder_base The folder used how base path for search view files
	*/

	/*public function __construct()
	{
	
		$arr_arg=func_get_args();
		
		if(count($arr_arg)>0)
		{
			
			View::$folder_env=$arr_arg;
		
		}
	
		View::$root_path=getcwd();

	}*/
	
	static public function load_config()
	{
	
        //Load config here
        foreach(View::config_path as $config)
        {
        
            Utils::load_config("config_views", $config);
        
        }
	
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

	static public function load_view($arr_template_values, $template, $module='', $escape=true)
	{

		//First see in controller/view/template, if not see in /views/template
		
		$yes_cache=0;
		
		$all_path=array();
		
		if(!isset(View::$cache_template[$template])) 
		{
		
            if($module!='')
            {
            
                //vendor/phangoapp/admin/views
                View::$folder_env[]='vendor/'.$module.'/views';
                
            
            }
		
			foreach(View::$folder_env as $base_path)
			{
                
				$view_real_path=View::$root_path.'/'.$base_path.'/'.$template.'.php';
                
                $view_path=View::$root_path.'/'.View::$cache_directory.'/'.$base_path.'/'.$template.'.php';
				
				$all_path[]=$view_real_path;
                
                $all_cache_path[]=$view_path;
				
				if(is_file($view_real_path))
				{
                    if(View::$escape==true && $escape==true)
                    {
                    
                        if(!is_file($view_path))
                        {
                            
                            $directory_cache=View::$root_path.'/'.View::$cache_directory.'/'.$base_path.'/'.dirname($template);
                            
                            if(!is_dir($directory_cache))
                            {
                                
                                if(!mkdir($directory_cache, 0755, true))
                                {
                                    
                                    throw new \Exception('Error: directory cache cant be created: '.$directory_cache);
                                    
                                    die;
                                    
                                }

                            }
                            
                            
                            
                        }
				
                    }
                }
                    //If escape then get 
                    
                    /*if(View::$escape==true && $escape==true)
                    {
                    
                        if(!is_file($view_path))
                        {
                            
                            $directory_cache=View::$root_path.'/'.View::$cache_directory.'/'.$base_path.'/'.dirname($template);
                            
                            if(!is_dir($directory_cache))
                            {
                                
                                if(!mkdir($directory_cache, 0755, true))
                                {
                                    
                                    throw new \Exception('Error: directory cache cant be created: '.$directory_cache);
                                    
                                    die;
                                    
                                }

                            }
                                
                            View::add_cache_file($view_real_path, $view_path);
                            
                        }
                        elseif(View::$debug_tpl) {
                            
                            $time_cached=filemtime($view_path);
                            
                            $time_real_cached=filemtime($view_real_path);
                            
                            if($time_real_cached>$time_cached)
                            {
                                
                                View::add_cache_file($view_real_path, $view_path);
                                
                            }
                            
                        }
                    
                    }*/
                    
                $all_path[]=$view_real_path;
                    
                if(is_file($view_real_path))
				{

                    include($view_real_path);
                        
                    $yes_cache=1;
                        
                    break;
                
				}
			
			}
			
			//If load view, save function name for call write the html again without call include view too
			
			if($yes_cache==1)
			{
			
				View::$cache_template[$template]=basename($template).'View';
				
			}
			else
			{
			
				throw new \Exception('Error: view not found: '.implode(' and ', $all_path));
				die;
			
			}

		}
		
		ob_start();

		$func_view=View::$cache_template[$template];
		
		if(!function_exists($func_view))
		{
		
			throw new \Exception('Error: Template file loaded but function '.$func_view.' not found: '.implode(' and ', $all_path));
			die;
		
		}
		
		//Load function from loaded view with his parameters
		
		//array_unshift($arr_template_values, $this);

		call_user_func_array($func_view, $arr_template_values);

		$out_template=ob_get_contents();

		ob_end_clean();
		
		return $out_template;

	}
	
    static public function add_cache_file($view_real_path, $view_path)
    {
        
        //Create the cache file
                                
        $file=file_get_contents($view_real_path);
        
        #<?php echo "([^\\"]*);([^\\"]*)"
        
        /*$file=preg_replace('/<\?php echo "([^\\"]*?);+([^\\"]*?)"/', '/<?php echo /', $file);
        
        $file=preg_replace('/<\?=(.*?)\|n\?>/', '<?php PhangoApp\PhaView\View::d($1); ?>', $file);
        
        $file=preg_replace('/<\?php echo\s+([^;]*?);\|n/', '<?php PhangoApp\PhaView\View::d($1);', $file);
        
        $file=preg_replace('/<\?=(.*?)\?>/', '<?=PhangoApp\PhaView\View::e($1)?>', $file);
        
        $file=preg_replace('/<\?php echo\s+(.*?);/', '<?php echo PhangoApp\PhaView\View::e($1);', $file);*/
        
        $file=preg_replace('/%\\{\w+?\}"/', '/View::e()/', $file);
        
        if(!file_put_contents($view_path, $file))
        {
            
            throw new \Exception('Error: file cache cannot be created: '.$view_path);
            
            die;
            
        }
        
    }
    
	/**
	* Method for create a url for access files via .php script
	*
	* @param string $path_file The relative path of file with respect to $folder_end.'/'.$path_media
	*/
	
	static public function dynamic_get_media_url($path_file)
	{
	
		return View::$php_file.'/'.$path_file;
	
	}
	
	/**
	* Method for create a url for access files via http server
	*
	* @param string $path_file The relative path of file with respect to $folder_end.'/'.$path_media
	*
	*/
	
	static public function static_get_media_url($path_file, $module='')
	{
		
		//Need that the paths was theme/media and theme/module/media
		
		if($module!='')
		{
		
			$module.='/';
		
		}
		
		return View::$url_media.'media/'.View::$theme.'/'.$module.$path_file;
	
	}
	
	/**
	* Method for change the method for access to media files. 
	*
	* @param boolean $value Set the production property.If true then access to media files directly, if false, access to media files via specified .php script
	*/
	
	static public function set_production($value=1)
	{
	
		if($value==1)
		{
			
			$production=1;
			View::$func_media='static_get_media_url';
			
		}
		else
		{
		
			$production=0;
		
			View::$func_media='dynamic_get_media_url';
		
		}
	
	}
	
	/**
	* Method for obtain a url for a media file.
	* @param string $path_file The relative path of file with respect to $folder_end.'/'.$path_media
	*/
	
	static public function get_media_url($path_file, $module='')
	{
		
		$func_media=View::$func_media;
		
		return View::$func_media($path_file, $module);
	
	}
	
	/**
	* Method for load media files. Method for load simple media file, is only for development
	*
	* This method is used on php files for retrieve media files using a very simple url dispatcher.
	*
	* @warning  NO USE THIS METHOD IN PRODUCTION.
	*
	*/
	
	static public function load_media_file($url)
	{
	
		//Check files origin.
		
		if(View::$production==0)
		{
		
			$yes_file=0;
			
			$url=preg_replace('/\?.*$/', '', $url);
			
			$arr_url=explode(View::$php_file.'/', $url);
			
			$final_path='';
			
			if(isset($arr_url[1]))
			{
			
				//Clean the path of undesirerable elements.
			
				$arr_path=explode('/', $arr_url[1]);
				
				$c=count($arr_path)-1;
				
				//foreach($arr_path as $key_path => $item_path)
				for($x=0;$x<$c-1;$x++)
				{
				
					$arr_path[$x]=Utils::slugify($arr_path[$x], $respect_upper=0, $replace_space='-', $replace_dot=1, $replace_barr=1);
				
				}
				
				$arr_path[$c]=Utils::slugify($arr_path[$c], $respect_upper=1, $replace_space='-', $replace_dot=0, $replace_barr=1);
				
				$final_path=implode('/', $arr_path);
				
			
			}
			
			foreach(View::$media_env as $folder)
			{
			
				$file_path=View::$root_path.'/'.$folder.'/'.View::$path_media.'/'.$final_path;
				
				if(is_file($file_path))
				{
					
					$yes_file=1;
					
					break;
				
				}
				
			}
			
			if($yes_file==1)
			{
			
				$ext_info=pathinfo($file_path);
			
				settype($ext_info['extension'], 'string');
				
				switch($ext_info['extension'])
				{
				
					default:
					
						$type_mime='text/plain';
					
					break;
					
					case 'js':
					
						$type_mime='application/javascript';
					
					break;
					
					case 'css':
					
						$type_mime='text/css';
					
					break;
					
					case 'gif':
					
						$type_mime='image/gif';
					
					break;
					
					case 'png':
					
						$type_mime='image/png';
					
					break;
					
					case 'jpg':
					
						$type_mime='image/jpg';
					
					break;
                    
                    case 'html':
					
						$type_mime='text/html';
					
					break;
				
				}
				

				
				header('Content-Type: '.$type_mime);
				
				readfile($file_path);
				
				die;
				
			
			}
			else
			{
			
				header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found"); 
					
				echo 'File not found...';
				
				die;
			
			}
		
		}
	
	}
	
	/**
	* Method that use $this->css array for create a series of html tags that load the css stylesheets
	*
	* This function is used inside of <head> html tag normally for load css files.
	*/
	
	static public function load_css()
	{
	
		$arr_final_css=array();
	
		View::$css=array_unique(View::$css);
	
		foreach(View::$css as $css)
		{
			
			$url=View::get_media_url('css/'.$css);
		
			$arr_final_css[]='<link href="'.$url.'" rel="stylesheet" type="text/css"/>'."\n";
		
		}
		
		foreach(View::$css_module as $module_css => $css)
		{
			$css=array_unique($css);
				
			foreach($css as $module => $css_item)
			{
			
				$url=View::get_media_url('css/'.$css_item, $module_css);
			
				$arr_final_css[]='<link href="'.$url.'" rel="stylesheet" type="text/css"/>'."\n";
				
			}
		}
		
		return implode('', $arr_final_css);
	
	}
	
	/**
	* Method that use $this->js array for create a series of html tags that load the javascript files
	*
	* This function is used inside of <head> html tag normally for load js files.
	*/
	
	static public function load_js()
	{
	
		$arr_final_js=array();
		
		View::$js=array_unique(View::$js);
	
		foreach(View::$js as $module_js => $js)
		{
		
			$url=View::get_media_url('js/'.$js);
		
			$arr_final_js[]=$arr_final_jscript[]='<script language="Javascript" src="'.$url.'"></script>'."\n";
		
		}
		
		foreach(View::$js_module as $module_js => $js)
		{
			$js=array_unique($js);
				
			foreach($js as $module => $js_item)
			{
			
				$url=View::get_media_url('js/'.$js_item, $module_js);
				
				$arr_final_js[]='<script language="Javascript" src="'.$url.'"></script>'."\n";
				
			}
		}
		
		return implode('', $arr_final_js);
	
	}
	
	/**
	* Method that use $this->header array for create a series of code (normally javascript) on <head> tag.
	*
	* This function is used inside of <head> html tag normally for load inline javascript code.
	*/
	
	static public function load_header()
	{
	
		$arr_final_header=array();
		
		foreach(View::$header as $header)
		{
		
			$arr_final_header[]=$header."\n";
		
		}
		
		return implode("\n", $arr_final_header);
	
	}
	
	/**
	* A method for make a redirect based on a theme
	*
	*/
	
	static public function load_theme($title, $cont_index)
	{
		
		echo View::load_view(array($title, $cont_index),'home');
	
	}
	
	/**
	* Function for load multiple views for a only source file.
	* 
	* Useful for functions where you need separated views for use on something, When you use load_view for execute a view function, the names used for views are in $func_views array.
	*
	* @param string $template of the view library. Use the same format for normal views. 
	* @param string The names of templates, used how template_name for call views with load_view.
	*/

	static public function load_libraries_views($template, $func_views=array())
	{
	
		foreach(View::$folder_env as $base_path)
		{
		
			$view_path=View::$root_path.'/'.$base_path.'/'.$template.'.php';
			
			if(is_file($view_path))
			{
			
				include($view_path);
				
				foreach($func_views as $template)
				{

					View::$cache_template[$template]=basename($template).'View';

				}
				
				break;
			
			}
		
		}


	}
	
	/**
	* Method for set the flash message
    */
	
	static public function set_flash($text)
	{
	
		$_SESSION['flash_txt']=$text;
	
	}
	
	/**
	* Method for show the flash message
	*/
	
	static public function show_flash()
	{
	
		if(isset($_SESSION['flash_txt']))
		{
			if($_SESSION['flash_txt']!='')
			{
				$text=$_SESSION['flash_txt'];
				
				$_SESSION['flash_txt']='';
			
				return View::load_view(array($text), 'common/utils/flash');
				
			}
		}
		
		return '';
	
	}
	
	/**
	* Method for escape the variables in an view
	*/
	
	static public function e($text)
	{
        
        $text=htmlspecialchars($text);
	
        return $text;
	
	}
    
    static public function d($text)
	{
	
        echo $text;
	
	}
	
}
