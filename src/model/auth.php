<?php
class authModel implements IModel
{
    public $sessionID;
    public $userName;
    public $authenticated = false;

    private $passwordSalt = 'screenifyserver';

    public function __construct($config)
    {

    }

    public function run()
    {
        if (isset(Core::$models['mysqli']))
        {
            if (isset(Core::$REQUESTS['sessionKey']))
            {
                $authenticated = $this->checkSession(Core::$REQUESTS['sessionKey']);
            }
        }
    }

    public function checkSession($sessionKey)
    {
        $q = Core::$models['mysqli']->query('SELECT * FROM sessions WHERE sessionKey = "'. Core::$models['mysqli']->escape($sessionKey) .'" LIMIT 1;');

        if (Core::$models['mysqli']->rows($q) == 1)
        {
            $r = Core::$models['mysqli']->fetch($q);

            if ($r['expires'] >= time())
            {
                return true;
            }
            else
            {
                $this->removeSession($sessionKey);
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function removeSession($sessionKey)
    {
        Core::$models['mysqli']->query('DELETE FROM sessions WHERE sessionKey = "'.$sessionKey.'"');
    }

    public function login($username, $password)
    {
        
    }

    private function generateKey()
    {
        $charset = array(
            'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            '0','1','2','3','4','5','6','7','8','9'
        );

        $r = '';

        for ($c=0; $c<48; $c++)
        {
            $r .= $charset[rand(0,count($charset)-1)];
        }

        return $r;
    }
}