<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Users extends BaseController
{
    use ResponseTrait;

    public function __construct(){
        $this->usersModel = new \App\Models\Users();
    }
    public function get_synced_data()
    {
        $headers = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = $this->get_token($headers);
        $this->auth  = new Auth();
        if( $this->auth->authenticate($token)){
            //decode the token to get the user id
            $decoded = $this->auth->decode_token($token);
            $synced_users = $this->usersModel->where('synced' , 1)->findAll(0);

            //record the audit logs
            $audit_log = ['id' => $decoded->id , 'description' => 'Get Synced Data'];
            $this->auth->record_audit_log($audit_log);

            return $this->respond(['data' => $synced_users]);
        } else {
            //record the audit logs
            $audit_log = ['id' => 0 , 'description' => 'Authentication Failed'];
            $this->auth->record_audit_log($audit_log);
            return $this->respond(['msg' => 'Authentication Failed']);
        }
    }

    public function sync_data()
    {
        $headers = $this->request->getServer('HTTP_AUTHORIZATION');
        $token = $this->get_token($headers);
        $this->auth  = new Auth();
        if( $this->auth->authenticate($token)){

            $decoded = $this->auth->decode_token($token);

            $unsynced  = $this->usersModel->where('synced' , 0)->findAll(0);
            if($unsynced){
                foreach ($unsynced as $row){
                    $data_to_update[] = array(
                        'synced' => 1,
                        'id' => $row['id']
                    );
                }

                $records_updated = $this->usersModel->updateBatch($data_to_update,'id');

                //record the audit logs
                $audit_log = ['id' => $decoded->id , 'description' => 'Synced Data'];
                $this->auth->record_audit_log($audit_log);
                return $this->respond(['data' => $records_updated. " Records Synced"]);
            }else{
                return $this->respond(['message' => "All items have been synced"]);
            }

            return $this->respond(['data' => $records_updated. " Records Synced"]);
        } else {
            //record the audit logs
            $audit_log = ['id' => 0 , 'description' => 'Authentication Failed'];
            $this->auth->record_audit_log($audit_log);
            return $this->respond(['msg' => 'Authentication Failed']);
        }
    }

    public function get_token($headers){
        $token = null;
        if(!empty($headers)) {
            $token = str_replace(  "Bearer ", "", $headers );
        }
        return $token;
    }
}
