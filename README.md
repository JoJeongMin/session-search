# Simple Session Search

## Usage

### Controller

```php
class AppController
{
    /**
     * initialize method.
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('SessionSearch', [
            'actions' => ['index'],
            'sessionKey' => 'ReviewIndexSearchSessionKey',
        ]);
    }

    public function index()
    {
    }

    public function searchClear()
    {
        // session 削除
        $this->SessionSearch->sessionDelete();
    }
}
```
