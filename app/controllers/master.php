<?php 

class Master extends Controller
{


    public function __construct(){
        return $this->index();
    }

    public function index(){

        $this->setRequest();

        if($this->getRequest() !== false){


            $data = $this->getRequest();

            if(isset($data->action))
            {
                $response = false;
                switch($data->action)
                {
                    case 'Create Profile';
                        
                        if($this->verifyData()!==false)
                        {
                            $response = $this->createProfile();
                        }else{
                            $response = false;
                        }

                    break;
                    case 'Update Profile':

                        if($this->verifyData()!==false)
                        {
                            $response = $this->updateProfile();
                            error_log('response please: '.print_r($response, 1));
                        }else{
                            $response = false;
                        }

                    break;
                    case 'Get Profile':

                        $response = $this->getProfile($data->person);

                    break;
                }
                error_log('response please: '.print_r($response, 1));
                echo json_encode($response);
            }

        }

    }

    private function verifyData()
    {
        $data = new stdClass();
        $data->api = 'verify';
        $data->action = 'Profile';
        $data->params = $this->getRequest()->params;

        $res = json_decode(ApiModel::doAPI($data));

        foreach($res as $key=>$value)
        {
            if(empty($value)){
                return false;
            }
        }

        return true;
        
    }

    private function createProfile()
    {
        $res = ApiModel::createPersonProfile($this->getPersonProfile());

        if($res->code !== '6000')
        {
            return false;
        }
        return $res;
    }

    private function updateProfile()
    {
        $res = ApiModel::updatePersonProfile($this->getPersonProfile());
        
        if($res->code !== '6000')
        {
            return false;
        }
        return $res;
    }

    private function getPersonProfile(){

        $input = new stdClass();
        $input = $this->getRequest()->params;

        $data = new stdClass();
        $data->userId = $this->getParam('user', $input);
        $data->about = $this->getParam('about', $input);
        $data->document = $this->getParam('document', $input);
        $data->skill1 = $this->getParam('s1', $input->skills);
        $data->skill2 = $this->getParam('s2', $input->skills);
        $data->skill3 = $this->getParam('s3', $input->skills);
        $data->skill4 = $this->getParam('s4', $input->skills);

        return $data;
    }

    private function getProfile($input)
    {
        $res = ApiModel::getProfile($input);
        return $res;
    }

    private function getParam($object, $input)
    {
        foreach($input as $key => $value){
            if($key == $object){
                error_log('show value: '.$key.' '.$value);
                return $value;
            }
        }
        return NULL;
    }
}