<?php

class C_Comment_Mapper extends C_CustomPost_DataMapper_Driver
{
    public static $_instances = array();

    function define($object_name, $context = FALSE)
    {
        parent::define('photocrati-comments', array($context, 'photocrati-comments'));
        $this->add_mixin('Mixin_Comment_Mapper');
        $this->implement('I_Comment_Mapper');
        $this->set_model_factory_method('comment_container');
    }

    function initialize()
    {
        parent::initialize('photocrati-comments');
    }

    public static function get_instance($context = False)
    {
        if (!isset(self::$_instances[$context]))
            self::$_instances[$context] = new C_Comment_Mapper($context);
        return self::$_instances[$context];
    }
}

class Mixin_Comment_Mapper extends Mixin
{
    function find_by_post_title($name, $model = FALSE)
    {
        $retval = NULL;
        $this->object->select();
        $this->object->where(array('post_title = %s', $name));
        $results = $this->object->run_query(FALSE, $model);
        if ($results)
            $retval = $results[0];

        return $retval;
    }

    function find_or_create($type, $id, $referer=FALSE)
    {
        $name = $this->object->get_stub($type, $id);
        $post = $this->object->find_by_post_title($name, TRUE);

        if (!$post)
        {
            $post = new stdClass;
            $post->name = $name;
            $post->post_title = $name;
            $post->comment_status = 'open';
            $post->post_status = 'publish';
            $post->post_type = 'comments';
			$post->post_excerpt = $referer;
            $this->object->save($post);
            $post = $this->object->find_by_post_title($name, TRUE);
        }

        return $post;
    }

    function get_stub($type, $id)
    {
        return sprintf(__("NextGEN Comment Link - %s - %s", 'nextgen-gallery-pro'), $type, $id);
    }

}
