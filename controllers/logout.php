<? 
class logout{
	function welcome(){
		session_start();
		if (isset($_SESSION['usernameSS'])){
		unset($_SESSION['usernameSS']); // xóa session login
		}

	}
}
?>
