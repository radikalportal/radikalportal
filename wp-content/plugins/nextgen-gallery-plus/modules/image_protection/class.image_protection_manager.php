<?php

class Mixin_Image_Protection_Manager extends Mixin
{
	function _get_protector_list()
	{
		$protector_files = array(
			'apache-config' => array(
				'path' => '.htaccess',
				'content' => '
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule _backup$ - [L,F]
</IfModule>' . "\n",
				'tag-start' => '# BEGIN NextGEN Pro Protection' . "\n",
				'tag-end' => '# END NextGEN Pro Protection' . "\n"
			),
			'php-index' => array(
				'path' => 'index.php',
				'content' => '// silence is golden' . "\n",
				'tag-start' => '<?php # BEGIN NextGEN Pro Protection' . "\n",
				'tag-end' => '# END NextGEN Pro Protection' . "\n"
			)
		);
	
		return $protector_files;
	}
	
	function _find_protector_content($text, $protector)
	{
		$tag_start = $protector['tag-start'];
		$tag_end = $protector['tag-end'];
		$pos_1 = strpos($text, $tag_start);
		$len_1 = strlen($tag_start);
		
		if ($pos_1 !== false)
		{
			$start = $pos_1 + $len_1;
			$pos_2 = strpos($text, $tag_end, $start);
			$len_2 = strlen($tag_end);
			
			if ($pos_2 !== false)
			{
				$content = substr($text, $start, $pos_2 - $start);
				
				return array(
					'content' => $content,
					'start' => $start,
					'end' => $pos_2,
					'size' => $pos_2 - $start
				);
			}
		}
		
		return false;
	}
	
	// $skip_cache not supported yet... we should probably cache this to avoid file access?
	function is_gallery_protected($gallery, $skip_cache = false)
	{
		$storage = C_Gallery_Storage::get_instance();
		$gallery_path = $storage->get_gallery_abspath($gallery);
		
		if ($gallery_path != null && file_exists($gallery_path))
		{
			$protector_files = $this->_get_protector_list();
			$retval = false;
			
			foreach ($protector_files as $name => $protector)
			{
				$path = $protector['path'];
				$full_path = path_join($gallery_path, $path);
				$retval = false;
				
				if (file_exists($full_path))
				{
					$full = file_get_contents($full_path);
					$result = $this->_find_protector_content($full, $protector);
					
					if ($result != null && $result['content'] == $protector['content'])
					{
						$retval = true;
					}
				}
				
				if (!$retval)
				{
					break;
				}
			}
			
			return $retval;
		}
		
		return false;
	}
	
	function protect_gallery($gallery, $force = false)
	{
		$retval = $this->object->is_gallery_protected($gallery);
		
		if ($force || !$retval)
		{
			$storage = C_Gallery_Storage::get_instance();
			$gallery_path = $storage->get_gallery_abspath($gallery);
			
			if ($gallery_path != null && file_exists($gallery_path))
			{
				$protector_files = $this->_get_protector_list();
				
				foreach ($protector_files as $name => $protector)
				{
					$path = $protector['path'];
					$full_path = path_join($gallery_path, $path);
					$full = null;
				
					if (file_exists($full_path))
					{
						$full = @file_get_contents($full_path);
						$result = $this->_find_protector_content($full, $protector);
					
						if ($result != null)
						{
							$full = substr_replace($full, $protector['content'], $result['start'], $result['size']);
						}
					}
					else
					{
						$full = $protector['tag-start'] . $protector['content'] . $protector['tag-end'];
					}
					
					if (is_writable($full_path)) @file_put_contents($full_path, $full);
					
					$retval = true;
				}
			}
		}
	
		return $retval;
	}
	
	// $skip_cache not supported yet... we should probably cache this to avoid file access?
	function is_image_protected($image, $skip_cache = false)
	{
		// TODO
	}
	
	function protect_image($image, $force = false)
	{
		// TODO
	}
}

class C_Image_Protection_Manager extends C_Component
{
    static $_instances = array();

    function define($context=FALSE)
    {
			parent::define($context);

			$this->implement('I_Image_Protection_Manager');
			$this->add_mixin('Mixin_Image_Protection_Manager');
    }

    static function get_instance($context = False)
    {
			if (!isset(self::$_instances[$context]))
			{
					self::$_instances[$context] = new C_Image_Protection_Manager($context);
			}

			return self::$_instances[$context];
    }
}
