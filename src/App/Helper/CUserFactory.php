<?php

namespace App\Helper;

final class CUserFactory
{
    private static $id;
    private static $email;
    private static $position;
    private static $roles;
    private static $sbus;

    /**
     * Call this method to get singleton
     *
     * @return \App\Helper\CUserFactory
     */
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new CUserFactory();
        }
        return $inst;
    }

    /**
     * Private constructor for singleton
     *
     */
    private function __construct(){ }

    /**
     * Get the value of id
     */ 
    public static function getId()
    {
        return self::$id;
    }

    /**
     * Set the value of id
     */ 
    public static function setId($id)
    {
        self::$id = $id;
    }

    /**
     * Get the value of email
     */ 
    public static function getEmail()
    {
        return self::$email;
    }

    /**
     * Set the value of email
     */ 
    public static function setEmail($email)
    {
        self::$email = $email;
    }

    /**
     * Get the value of position
     */ 
    public static function getPosition()
    {
        return self::$position;
    }

    /**
     * Set the value of position
     */ 
    public static function setPosition($position)
    {
        self::$position = $position;
    }

    /**
     * Get the value of roles
     */ 
    public static function getRoles()
    {
        return self::$roles;
    }

    /**
     * Set the value of roles
     *
     * @return  self
     */ 
    public static function setRoles($roles)
    {
        self::$roles = $roles;
    }

    /**
     * Get the value of sbus
     */ 
    public static function getSBUs()
    {
        return self::$sbus;
    }

    /**
     * Set the value of sbus
     *
     * @return  self
     */ 
    public static function setSBUs($sbus)
    {
        self::$sbus = $sbus;
    }
}