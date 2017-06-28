<?php 

class MenuFactory{
	public static function factory($id_profile){
		switch ($id_profile) {
            case 1:
                return new MenuConsultor;
                break;
            case 2:
                return new MenuGestor;
                break;
            case 3:
                return new MenuAdmin;
                break;
            case 5:
                return new MenuCliente;
                break;
        }
	}
}