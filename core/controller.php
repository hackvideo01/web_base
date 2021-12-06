<?
class controller{
	public function model($model){
		require_once "./models/".$model.".php";
		return new $model;
	}

	public function view($view){
		
	}
}
?>