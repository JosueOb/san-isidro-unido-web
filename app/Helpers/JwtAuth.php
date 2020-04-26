<?php
namespace App\Helpers;

use App\User;
use Firebase\JWT\JWT;
use Exception;

class JwtAuth {
	public $key;
	//key for rsa512 is stalin97
	//openssl genrsa -des3 -out sm_private.pem 512
	//openssl rsa -in sm_private.pem -outform PEM -pubout -out sm_public.pem
	public $algoritmoCifrado;
	public $timeExpiration;
	public $allowedRoles;
	// private $privateKey;
	// private $publicKey;

	public function __construct() {
		$this->key = 'ah9;HBW3%asd>u=Y.T#@,gasdKUD4[100%';
		// $this->algoritmoCifrado = 'HS256';
		$this->algoritmoCifrado = 'HS512';
		// $this->algoritmoCifradoDoble = 'RS512';
		$this->timeExpiration = (12 * 6 * 4 * 7 * 24 * 60 * 60);
		// $this->timeExpiration = (7*24*60*60);
		$this->allowedRoles = ['invitado', 'morador', 'policia'];
		// $this->privateKey = file_get_contents(base_path('sm_private.pem'));
		// $this->publicKey = file_get_contents(base_path('sm_private.pem'));
	}

    /**
     * Devuelve un booleano de acuerdo a si el usuario tiene credenciales válidas
     * @param string  $email
     * @param string  $passOrToken
     * @param string  $passOrToken
     *
     * @return boolean
     */
	public function singIn($email, $passOrToken, $provider) {
		$validCredentials = false;
		$user = User::email($email)->rolActive()->with("roles")->first();

		if (!is_null($user)) {
			if ($provider === 'formulario') {
				$validCredentials = (password_verify($passOrToken, $user['password'])) ? true : false;
			} else {
				if (count($user['social_profiles']) > 0) {
					$socialIDCorrect = false;
					foreach ($user['social_profiles'] as $socialProfile) {
						if ($socialProfile['social_id'] === $passOrToken && $socialProfile['provider'] === $provider) {
							$socialIDCorrect = true;
							break;
						}
					}
					$validCredentials = $socialIDCorrect;
				}
			}
		}
		//Retorno si las credenciales son válidas
		return $validCredentials;
	}

    /**
     * Devuelve el token de un usuario
     * Si se pasa la opcion getInfoToken se devuelve el token decodificado
     * @param string  $email
     * @param boolean  $getInfoToken
     *
     * @return string|object
     */
	public function getToken($email, $getInfoToken = null) {
		$user_bdd = User::where("email", $email)->mobileRol()->first();
		$user = $user_bdd->makeHidden('password');
		$token = [
			"sub" => $user['id'],
			"iat" => time(),
			"exp" => time() + $this->timeExpiration,
			"user" => $user,
		];
		//Codificar y Decodificar información
		$jwt = JWT::encode($token, $this->key, $this->algoritmoCifrado);
		$decoded = JWT::decode($jwt, $this->key, [$this->algoritmoCifrado]);
		//Verificar si quiero obtener token codificado o decodificado
		if (is_null($getInfoToken)) {
			$data = $jwt;
		} else {
			$data = $decoded;
		}
		return $data;
	}

	public function testDecoded($token) {
		$decoded = JWT::decode($token, $this->key, [$this->algoritmoCifrado]);
		return $decoded;
	}

    /**
     * Devuelve true or false si el token enviado es válido
     * Si se pasa la opcion getIdentity true devuelve el token decodificado
     * @param mixed  $jwt
     * @param boolean  $getIdentity
     *
     * @return boolean|object
     */
	public function checkToken($jwt, $getIdentity = false) {
        $auth = false;
        $decoded = null;
		try {
            $decoded = JWT::decode($jwt, $this->key, [$this->algoritmoCifrado]);
		} catch (\UnexpectedValueException $e) {
			$auth = false;
		} catch (\DomainException $e) {
			$auth = false;
		} catch (Exception $e) {
			$auth = false;
		}

		if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
			if ($decoded->user->state === 1) {
				$auth = true;
			} else {
				$auth = false;
			}
		} else {
			$auth = false;
		}
		//Verificar si se quiere obtener el token descifrado
		if ($getIdentity) {
			return $decoded;
		}

		return $auth;

	}

    /**
     * Devuelve true or false si el usuario tiene unos determinados roles
     * @param  mixed  $userRoles
     *
     * @return boolean
     */
	public function hasRoles($userRoles) {
		$hasRole = false;
		foreach ($userRoles as $oneRole) {
			if (is_array($userRoles) && count($userRoles) > 0 && in_array(strtolower($oneRole), $this->allowedRoles)) {
				$hasRole = true;
			}
		}
		return $hasRole;
	}

}
