<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{

  		$router = new RouteList;
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
		return $router;
                
/*
        $router = new RouteList;
        $router[] = $module = new RouteList('Admin');
        $module[] = new Route('<presenter>/<action>', 'Admin:default' );
        //admin/login
        $module[] = new Route('admin/login/<presenter>/<action>', 'Admin:login');
        //admin/login/admin/default

        $router[] = $module = new RouteList('Front');
        //    $router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);
        $module[] = new Route('<presenter>/<action>', 'Homepage:default');

        return $router;
*/
 
            
 /*          
		$router = new RouteList;
                    $router[] = new Route('<presenter>/<action>[/<id>]', 'Front:Homepage:default');
                    //$router[] = new Route('<presenter>/<action>[/<id>]', 'Admin:Admin:default');
                    $router = new RouteList();
                    $router[] = new Route('//[/]', 'Front:Homepage:default');
                    $router[] = new Route('admin//[/]', 'Admin:Homepage:default');
                    //$router[] = new Route('customer//[/]', 'Customer:Homepage:default');
                    //$router[] = new Route('/[/]', 'Public:Homepage:default');
		return $router;
 */               
 /*           
                    $router = new RouteList;
                        $router[] = $module = new RouteList('Admin');
                        $module[] = new Route('admin//<presenter>/<action>[/<id>]', 'Admin:default');

                        $router[] = $module = new RouteList('Front');
                        $module[] = new Route('//<presenter>/<action>[/<id>]', 'Homepage:default');
                        
                        //$module[] = new Route('portret', 'Gallery:portret');
                        //$module[] = new Route('glamour', 'Gallery:glamour');
                        //$module[] = new Route('architektura', 'Gallery:architecture');
                        //$module[] = new Route('street-foto', 'Gallery:street');
                        //$module[] = new Route('sport', 'Gallery:sport');
                        //$module[] = new Route('priroda', 'Gallery:nature');
                        
                    return $router;
*/               


/*
        $router = new RouteList;
        $router[] = $module = new RouteList('Admin');
        $module[] = new Route('admin/<presenter>/<action>', 'Admin:default');

        $router[] = $module = new RouteList('Front');
    //    $router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);
        $module[] = new Route('<presenter>/<action>', 'Homepage:default');
        return $router;
*/
/*
        $router = new RouteList;
        $router[] = $module = new RouteList('Admin');
        $module[] = new Route('admin/<presenter>/<action>', ['module' => 'Admin']);

        $router[] = $module = new RouteList('Front');
        //    $router[] = new Route('index.php', 'Front:Default:default', Route::ONE_WAY);
        $module[] = new Route('<presenter>/<action>', 'Homepage:default');

        return $router;
*/
	}
}
