<?php
namespace SessionSearch\Controller\Component;

use Cake\Controller\Component;

class SessionSearchComponent extends Component
{

    /**
     * Default config
     *
     * ### Options
     * - 'actions' : どこのactionで実行するかを定義.
     *
     * @author jeong
     */
    protected $_defaultConfig = [
        'actions' => ['index'],
        'sessionKey' => 'searchKey'
    ];

    /**
     * startup.
     * configで設定されているactionで接続したらconversionする.
     *
     * @return \Cake\Network\Response|null
     *
     * @author jeong
     */
    public function startup()
    {
        if ($this->_actionCheck()) {
            return $this->conversion();
        }
    }

    /**
     * searchのsessionがありつつGETでこなかったらGETにconversionする
     *
     * @param bool $redirect Redirect on post, default true.
     * @return \Cake\Network\Response|null
     *
     * @author jeong
     */
    public function conversion($redirect = true)
    {
        $session = $this->request->session();
        $sessionKey = $this->config('sessionKey');

        // sessionがあったらqueryにいれる
        if ($session->check($sessionKey) && !$this->request->query) {
            list($url) = explode('?', $this->request->here(false));
            $url .= '?' . http_build_query($session->read($sessionKey));

            return $this->_registry->getController()->redirect($url);
        }

        // getできたのもをsessionにいれる
        if ($this->request->query) {
            $session->write($sessionKey, $this->request->query);
        }
    }

    /**
     * 今のactionがconfigであるかどうかのチェックを行う.
     *
     * @return bool
     *
     * @author jeong
     */
    protected function _actionCheck()
    {
        $actions = $this->config('actions');
        if (is_bool($actions)) {
            return $actions;
        }
        return in_array($this->request->action, (array)$actions, true);
    }

    /**
     * 今のactionがconfigであるかどうかのチェックを行う.
     *
     * @return \Cake\Network\Response|null
     *
     * @author jeong
     */
    public function sessionDelete()
    {
        // session削除
        $session = $this->request->session();
        $sessionKey = $this->config('sessionKey');
        $session->delete($sessionKey);

        return !$session->check($sessionKey);
    }
}

