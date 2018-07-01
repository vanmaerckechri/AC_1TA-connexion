<?php

require('./model/model.php');

// ACTIVATE SESSION!
Authentification::startSession();

function testCaptcha()
{
	$secret = getSecretCaptchaKey();
    $response = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : false;
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
        . $secret
        . "&response=" . $response
        . "&remoteip=" . $remoteip ;   
	$decode = json_decode(file_get_contents($api_url), true);
	//$_SESSION['smsAlert']['default'] = $decode == true ? '<span class="smsAlert">Veuillez cliquer sur "Je ne suis pas un robot" avant de valider!</span>' : $_SESSION['smsAlert']['default'];
	return $decode;
}

//VIEWS!
function loadHomeView()
{
    require('./view/loginView.php');
}
function loadClassroomView()
{
    require('./view/classroomView.php');
}
function loadPwdView($pwd = '', $isPost = false)
{
	$decode = testCaptcha();
	if ($isPost == true && $decode['success'] == true)
	{
		$_SESSION['password'] = hash('sha256', $pwd);
		checkSession();
	}
    require('./view/pwdView.php');
}

// Recoveries
function callRecovery($postName, $type, $require)
{	
    $decode = testCaptcha();
    if (isset($_POST[$postName]) && $decode['success'] == true)
    {
    	$is_email = filter_var($_POST[$postName], FILTER_VALIDATE_EMAIL);
    	if ($is_email == true)
    	{
    		Recover::start($_POST[$postName], $type);
			require('./view/loginView.php');	
    	}
    	else
    	{
    		$_SESSION['smsAlert']['default'] = "<span class='smsAlert'>Veuillez entrer une adresse email valide!</span>";
    	}
    }
    else
    {
		require($require);
    }
}
function loadNicknameRecoveryView()
{	
	callRecovery('nameRecovery', 'nickname', './view/nnRecoveryView.php');
}
function loadPwdRecoveryView()
{
	callRecovery('pwdRecovery', 'pwd', './view/passwordRecoveryView.php');
}
function newPasswordView($isPost = false)
{
	if ($isPost == true)
	{
		$pwd1 = checkInput($_POST['newPassword'], 'password', 'default');
		$pwd2 = checkInput($_POST['newPassword2'], 'password', 'default');
		if ($pwd1 && $pwd2)
		{
			if ($pwd1 === $pwd2)
			{
				$pwd1 = hash('sha256', $pwd1);
				$pwd2 = hash('sha256', $pwd2);
				UpdatePassword::start($pwd1, $_SESSION['myId']);
				unset($_SESSION['myId']);
				require('./view/loginView.php');
				exit;
			}
			else
			{
				$_SESSION['smsAlert']['password2'] = "<span class='smsAlert'>Les mots de passe ne correspondent pas!</span>";
			}

		}
	}
	require('./view/newPasswordView.php');			
}
// Create admin account
function loadCreateAdminAccountView()
{
	$keepNickname = '""';
	$keepEmail = '""';
	// Captcha google
    $decode = testCaptcha();
	// Check if all inputs are corrects
	if (isset($_POST['createAdminAccountNickname']) && isset($_POST['createAdminAccountEmail']) && isset($_POST['createAdminAccountPassword']) && isset($_POST['createAdminAccountPassword2']))
	{
		$filteredNickname = checkInput($_POST['createAdminAccountNickname'], 'nickname', 'nickname');
		$filteredPwd = checkInput($_POST['createAdminAccountPassword'], 'password', 'password');
		$filteredPwd2 = checkInput($_POST['createAdminAccountPassword2'], 'password', false);
		$is_email = filter_var($_POST['createAdminAccountEmail'], FILTER_VALIDATE_EMAIL);
		// If at least one of them is not correct
		if ($filteredNickname == false || $filteredPwd == false || $filteredPwd2 == false || $is_email == false || $filteredPwd != $filteredPwd2 || $decode['success'] == false)
		{
			if ($filteredNickname != false)
			{
				$keepNickname = htmlspecialchars($filteredNickname, ENT_NOQUOTES);
			}

			if ($is_email == false)
			{
				$_SESSION['smsAlert']['email'] = "<span class='smsAlert'>Veuillez entrer une adresse email valide!</span>";
			}
			else
			{
				$keepEmail = htmlspecialchars($_POST['createAdminAccountEmail'], ENT_NOQUOTES);
			}

			if ($filteredPwd != $filteredPwd2)
			{
				$_SESSION['smsAlert']['password2'] = "<span class='smsAlert'>Les mots de passe ne correspondent pas!</span>";
			}

		}
		// Every inputs are corrects
		else
		{
			$keepNickname = htmlspecialchars($filteredNickname, ENT_NOQUOTES);
			$keepEmail = htmlspecialchars($_POST['createAdminAccountEmail'], ENT_NOQUOTES);
			$newAdminAccount = new RecordAccount($filteredNickname, $_POST['createAdminAccountEmail'], false, $filteredPwd);
			$alreadyExist = $newAdminAccount->getAlreadyExist();
			if ($alreadyExist != true)
			{
				header('Location: ./admin/index.php');
				exit;
			}
		}
	}
	require('./view/newAdminAccountView.php');
}
function checkCodeView($type)
{
	$code = htmlspecialchars($_GET['code'], ENT_NOQUOTES);

	// A voir si on a vmt besoin d'un captcha pour cette étape ?
    $decode = testCaptcha();
    if ($decode['success'] == true)
    {
		$id = checkCode::start($code, $type);
		if ($type == 'activate')
		{
			require('./view/loginView.php');
		}
		else if ($type == 'resetpwd')
		{
			if ($id == false)
			{
				require('./view/loginView.php');		
			}
			else
			{
				$_SESSION['myId'] = $id;
				require('./view/newPasswordView.php');			
			}
		}
    }
    else
    {
    	require('./view/checkCodeView.php');
    }
}