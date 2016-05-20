<?php
namespace TweeServerLessSession\Session;

interface CrypterInterface
{
    public function encrypt($data);

    public function decrypt($crypted);
}