<?
class login extends controller{
	function welcome(){
		$db = $this->model("database");
		session_start();
		include_once './views/login.html';

		if (isset($_POST['login'])) {

			if($_SESSION["usernameSS"]==NULL){
				$database = new Database();
				
				if ($_SERVER['REQUEST_METHOD'] == 'POST'){
						$query[] = 'SELECT * FROM users WHERE username="'.$_REQUEST["username"].'"AND password="'.$_REQUEST["password"].'"';
						$query = implode("",$query);
						$list = $database->fetchAll($query);
						if($list){

							// if (!empty($list)) {
							//     foreach ($list as $item) {
							//         echo '<pre style="color:red;font-weight:bold">';
							//         print_r($list);
							//         echo '</pre>';
							//         echo $item['Id'];
							//     }
							// }
							foreach ($list as $value) {
								$_SESSION['usernameSS'] = $value;
							}
							
							return true;
						}else{
							$error_info = "ユーザ名、パスワードが間違っています。";
							echo '
								<div style="text-align:center;color:white;">
									<span>'.$error_info.'</span>
								</div>	
							 	';
							return false;
						}
				}
				return false;
			}

			if($_SESSION["usernameSS"]!=NULL ){
				echo "asdasdok";
			}

		}
	}
}
?>