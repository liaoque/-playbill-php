<?php


class RoutesController extends \Yaf_Controller_Abstract
{

    public function indexAction()
    {
        $menu = '{"path":"/permission","meta":{"title":"menus.permission","icon":"lollipop","rank":7},"children":[{"path":"/permission/page/index","name":"PermissionPage","meta":{"title":"menus.permissionPage"}},{"path":"/permission/button/index","name":"PermissionButton","meta":{"title":"menus.permissionButton","authority":[]}}]}';
        $menu = json_decode($menu, true);
        AppResponse\AppResponse::success([]);
    }
}
