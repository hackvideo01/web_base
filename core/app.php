<?
class app{
	protected $controller = "login";
	protected $action = "welcome";
	protected $params = [];

	function __construct(){
		 $arr = $this->UrlProcess();

		 //xu ly Controller
		 if (file_exists("./controllers/".$arr[0].".php")) {
		 	$this->controller = $arr[0];
		 	unset($arr[0]);
		 }
		 require_once "./controllers/".$this->controller.".php";

		 //xu ly Action
		 if (isset($arr[1])) {
		 	if (method_exists($this->controller, $arr[1])) {
		 		$this->action = $arr[1];
		 	}
		 	unset($arr[1]);
		 }
		 
		 //xu ly Params
		 $this->params = $arr?array_values($arr):[];

		 //in Values controller,action, Array(params)
		 // echo $this->controller."<br>";
		 // echo $this->action."<br>";
		call_user_func_array([new $this->controller,$this->action],$this->params);
	}

	function UrlProcess(){
		if (isset($_GET['url'])) {
			return explode("/", filter_var(trim($_GET['url'], "/")));
		}
	}
}
?>