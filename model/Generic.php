<?php

namespace model;

class Generic
{

    protected $infos;
    protected $db;

    public function __construct() 
    {
        $this->db = \model\DBfactory::getInstance();
    }

    public function getInfos() 
    {

        $req = $this->db->query('SELECT last_name,first_name,chapo,email,adress,github,linkedin,pdf FROM users WHERE userlvl="1" ');
        $result = $req->fetch(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function mailContact($datas) 
    {

        // Ma clé privée
        $secret = "6LdO11EUAAAAAPIIiMCzNZ4_Go_Mj3rbJYyiDGIG";
        // Paramètre renvoyé par le recaptcha
        $response = $_POST['g-recaptcha-response'];
        // On récupère l'IP de l'utilisateur
        $remoteip = $_SERVER['REMOTE_ADDR'];

        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
                . $secret
                . "&response=" . $response
                . "&remoteip=" . $remoteip;

        $decode = json_decode(file_get_contents($api_url), true);

        if ($decode['success'] === true) {
            $to = 'mathieu.dolhen@gmail.com';
            $subject = 'Contact';
            $message = 'De: ' . $datas['last_name'] . ' ' . $datas['first_name'] . "\r\n" . $datas['message'];
            $headers = 'FROM: mathieu.dolhen@klaynetv-online.fr' . "\r\n" .
                    'Reply-To: ' . $datas['email'] . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);
            return true;
        } else {
            return false;
        }
    }

}
