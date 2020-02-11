<?
if (!function_exists('pre')) {
	function pre()
	{
		$debug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 0);
		$fileline = str_replace($_SERVER['DOCUMENT_ROOT'], "", $debug[0]['file'])." : ".$debug[0]['line'];

		$name = NULL;
		$data = func_get_args();
		switch(func_num_args())
		{
			case 1:
				$data = reset($data);
				break;

			case 2:
				if(is_string($data[0]))
				{
					$name = $data[0];
					$data = $data[1];
				}
				elseif(is_string($data[1]))
				{
					$name = $data[1];
					$data = $data[0];
				}
				break;
		}

		$output = print_r($data, true);

		if(defined('PHP_SAPI') && PHP_SAPI == 'cli')
		{
			echo "\n===========================\n";
			echo "=== start pre ".($name ? "($name) ":"")."\n";
			echo "=== fileline: {$fileline}\n";
			echo $output;
			echo "\n===========================\n\n";
		}
		else
		{
			echo "<pre  style='background:#fff; color:#000; border:1px solid #CCC;padding:10px;border-left:4px solid red; font:normal 11px Arial;'>".($name ? "<b>".$name.":</b>\n":"")."<small style='color: gray;'>{$fileline}</small>\n".$output."</pre>";
		}
	}
}