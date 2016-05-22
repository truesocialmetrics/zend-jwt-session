<?php
namespace TweeServerLessSession\Session\SaveHandler;


use TweeServerLessSession\Session\CrypterInterface as CrypterInterface;
use Zend\Session\SaveHandler\SaveHandlerInterface as SaveHandlerInterface;

use Zend\Stdlib\RequestInterface as RequestInterface;
use Zend\Stdlib\ResponseInterface as ResponseInterface;

use Zend\Http\Header\SetCookie as HeaderSetCookie;

class ServerLess implements SaveHandlerInterface
{
    protected $request  = null;
    protected $response = null;
    protected $crypter  = null;

    public function __construct(RequestInterface $request, ResponseInterface $response, CrypterInterface $crypter)
    {
        $this->setRequest($request);
        $this->setResponse($response);
        $this->setCrypter($crypter);
    }

    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setResponse(ResponseInterface $response)
    {
        return $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setCrypter(CrypterInterface $crypter)
    {
        return $this->crypter = $crypter;
    }

    public function getCrypter()
    {
        return $this->crypter;
    }

    // default ssession save handlder interface
    public function close()
    {
        return true;
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($savePath, $name)
    {
        return true;
    }

    public function read($sessionId)
    {
        $cookie = $this->getRequest()->getCookie();
        if (!$cookie) {
            return '';
        }
        if (!$cookie->offsetExists(ini_get('session.name') . '_' . $sessionId)) {
            return '';
        }
        $crypted = $cookie->offsetGet(ini_get('session.name') . '_' . $sessionId);
        if (empty($crypted)) {
            return '';
        }
        return $this->getCrypter()->decrypt($crypted);
    }

    public function write($sessionId, $sessionData)
    {
        $cookie = $this->getRequest()->getCookie();
        if (!$cookie
            || !$cookie->offsetExists(ini_get('session.name'))
            || !$cookie->offsetGet(ini_get('session.name'))) {
            $header = new HeaderSetCookie();
            $header->setName(ini_get('session.name'));
            $header->setValue($sessionId);
            $header->setPath('/');
            $header->setHttponly(true);
            $this->getResponse()->getHeaders()->addHeader($header);
        }

        $header = new HeaderSetCookie();
        $header->setName(ini_get('session.name') . '_' . $sessionId);
        $header->setValue($this->getCrypter()->encrypt($sessionData));
        $header->setPath('/');
        $header->setHttponly(true);
        $this->getResponse()->getHeaders()->addHeader($header);
    }

    public function destroy($sessionId)
    {
        $header = new HeaderSetCookie();
        $header->setName(ini_get('session.name') . '_' . $sessionId);
        $header->setValue('');
        $header->setExpires(time() - 1000000);
        $header->setPath('/');
        $header->setHttponly(true);
        $this->getResponse()->getHeaders()->addHeader($header);
    }
}