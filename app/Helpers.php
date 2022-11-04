<?php

use App\Models\Menu;
use App\Models\Setting;
use Illuminate\Support\Facades\File;


if (!function_exists('menu')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function menu($name)
    {
        $menu = Menu::where('name', $name)->first();
        return $menu->menuItems()->with('childs')->get();
    }
}
if (!function_exists('Setting')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function Setting($name, $default = null)
    {
        return Setting::getByName($name, $default);
    }
}
if (!function_exists('CreateFolder')) {
    /**
     * Create and folder in public path
     *
     */
    function CreateFolder($path)
    {
        if (! File::exists($path)) {
            $dirpath = public_path().'/'. $path;
            return File::makeDirectory($dirpath, 0777, true, true);
        }
    }
}

//override or add env file or key
function overWriteEnvFile($type, $val)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        $val = '"' . trim($val) . '"';
        if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
            file_put_contents($path, str_replace($type . '="' . env($type) . '"', $type . '=' . $val, file_get_contents($path)));
        } else {
            file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
        }
    }
}