<?php
class authModel implements IModel
{
    public $sessionKey;
    public $username;
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
                $this->authenticated = $this->loginSession(Core::$REQUESTS['sessionKey']);
                if (!$this->authenticated)
                    die('SESSIONKEY_WRONG');
            }

            if (isset(Core::$REQUESTS['action']))
            {
                $action = Core::$REQUESTS['action'];

                if ($action == 'login')
                {
                    if (isset(Core::$REQUESTS['username']) and isset(Core::$REQUESTS['password']))
                    {
                        $username = Core::$REQUESTS['username'];
                        $password = Core::$REQUESTS['password'];

                        if ($this->login($username, $password))
                        {
                            die($this->sessionKey);
                        }
                        else
                        {
                            die('LOGIN_FAILED');
                        }
                    }
                }
            }
        }
    }

    public function loginSession($sessionKey)
    {
        $q = Core::$models['mysqli']->query('SELECT * FROM sessions WHERE sessionKey = "'. Core::$models['mysqli']->escape($sessionKey) .'" LIMIT 1;');

        if (Core::$models['mysqli']->rows($q) == 1)
        {
            $r = Core::$models['mysqli']->fetch($q);


            $q2 = Core::$models['mysqli']->query('SELECT * FROM users WHERE id = "'.$r['userid'].'"');
            $r2 = Core::$models['mysqli']->fetch($q2);

            if ($r['expires'] > time())
            {
                $this->username = $r2['username'];

                $this->sessionKey = $sessionKey;

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
        $q = Core::$models['mysqli']->query('SELECT * FROM users WHERE username = "'.$username.'" AND password = "'.$password.'"');

        if (Core::$models['mysqli']->rows($q) == 1)
        {
            $user = Core::$models['mysqli']->fetch($q);

            $sessionQuery = Core::$models['mysqli']->query('SELECT * FROM sessions WHERE userid = "'.$user['id'].'"');

            if (Core::$models['mysqli']->rows($sessionQuery) == 1)
            {
                $session = Core::$models['mysqli']->fetch($sessionQuery);
                $this->loginSession($session['sessionKey']);
            }
            else
            {
                $this->sessionKey = $this->generateKey();
                Core::$models['mysqli']->query('INSERT INTO sessions VALUES ( NULL, "'.$this->sessionKey.'", "'.$user['id'].'", "1111111111111" )');
            }

            return true;
        }
        else
        {
            return false;
        }
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