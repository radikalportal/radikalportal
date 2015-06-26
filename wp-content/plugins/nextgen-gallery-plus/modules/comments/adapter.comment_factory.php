<?php

class A_Comment_Factory extends Mixin
{
    function comment_container($properties=array(), $mapper=FALSE, $context=FALSE)
    {
        return new C_Comment_Container($properties, $mapper, $context);
    }
}