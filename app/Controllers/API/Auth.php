<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use App\Models\AuditLogs;

use App\Models\Users;
use CodeIgniter\API\ResponseTrait;
use Couchbase\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends BaseController
{
    use ResponseTrait;

    public function __construct(){
        $this->users = new Users();
        $this->auditLogs = new AuditLogs();
    }

    public function token(){
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $key = JWT_KEY;
        $user = $this->users->where(['username' => $username])->first();

        if($user){

            if(password_verify($password, $user['password'])){
                //record audit log
                $audit_log = ['id' => $user['id'], 'description'=> "User Logged In"];
                $this->record_audit_log($audit_log);

                $token_array = [
                    'iat' => time(),
                    'ent' => time() + 84600,
                    'id' => $user['id']
                ];
                $token = JWT::encode($token_array,$key, 'HS256');
                return $this->respond(['token' => $token]);
            } else {
                $audit_log = ['id' => $user['id'], 'description'=> "Invalid Credentials Used"];
                $this->record_audit_log($audit_log);
                return $this->respond(['msg' => 'Invalid credentials used']);
            }
        }else {
            // record audit logs, use 0 since the user does not exist
            $audit_log = ['id' => 0, 'description'=> "The user does not exist"];
            $this->record_audit_log($audit_log);
            return $this->respond(['msg' => 'User Does not exist']);
        }
    }

    //This function authenticate token generated
    public function authenticate($token){
        if(!is_null($token)){
            $decoded = $this->decode_token($token);
            //record audit trail
            $audit_log = ['id' => $decoded->id , 'description' => 'Token Authenticated'];
            $this->record_audit_log($audit_log);
            return true;
        } else {
            return false;
        }

    }

    public function decode_token($token){
        $key = JWT_KEY;
        return JWT::decode($token, new Key($key, 'HS256'));
    }

    //function to record audit logs
    public function record_audit_log($where = []){
        $id = $where['id'];
        $description = $where['description'];
        //record audit trail
        $this->auditLogs->insert(['user_id' => $id , 'description' => $description]);
    }
}

