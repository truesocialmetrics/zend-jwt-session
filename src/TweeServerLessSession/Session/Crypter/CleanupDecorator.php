<?php
namespace TweeServerLessSession\Session\Crypter;

use Zend\Stdlib\ArrayObject;

class CleanupDecorator extends AbstractDecorator
{
    public function encrypt($data)
    {
        if (strpos($data, '__ZF|')) {
            throw new DomainException('Setup session.serialize_handler = php_serialize in your php.ini file'
                . ' or add ini_set("session.serialize_handler", "php_serialize") to your index file.');
        }
        $session = unserialize($data);
        $session = array_map(function($value) {
            if ($value instanceof ArrayObject) {
                return $value->getArrayCopy();
            }
            return $value;
        }, $session);

        $session['__ZF'] = reset($session['__ZF']['_VALID']);

        return $this->getCrypter()->encrypt(serialize($session));
    }

    public function decrypt($crypted)
    {
        $crypted = $this->getCrypter()->decrypt($crypted);

        $session = unserialize($crypted);
        $session = array_map(function($value) {
            if (is_array($value)) {
                return new ArrayObject($value);
            }
            return $value;
        }, $session);

        $session['__ZF'] = [
            '_REQUEST_ACCESS_TIME' => microtime(true),
            '_VALID' => [
                'Zend\Session\Validator\Id' => $session['__ZF'],
            ],
        ];
        return serialize($session);
    }
}