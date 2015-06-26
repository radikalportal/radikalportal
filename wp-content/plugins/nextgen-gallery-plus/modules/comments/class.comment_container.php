<?php

class C_Comment_Container extends C_DataMapper_Model
{
    function define($properties = array(), $mapper = FALSE, $context = FALSE)
    {
        parent::define($mapper, $properties, $context);
        $this->add_mixin('Mixin_Comment_Container_Validation');
        $this->add_mixin('Mixin_Wordpress_Comment_Container');
    }

    function initialize($properties = array(), $mapper = FALSE, $context = FALSE)
    {
        if (!$mapper)
            $mapper = $this->get_registry()->get_utility($this->_mapper_interface);
        parent::initialize($mapper, $properties);
    }
}

class Mixin_Comment_Container_Validation extends Mixin
{
    function validation()
    {
        $this->object->validates_presence_of('name');
        $this->object->validates_uniqueness_of('name');
        return $this->object->is_valid();
    }
}

class Mixin_Wordpress_Comment_Container extends Mixin
{
    function get_comments_data($page = 0)
    {
        add_action('pre_get_posts', array(&$this, 'set_comment_data_query_args'), PHP_INT_MAX, 1);

        $retval = array();
        $retval['container_id'] = $this->object->{$this->object->id_field};

        ob_start();
        $args = array(
            'post_type' => 'photocrati-comments',
            'p' => $retval['container_id'],
            'cpage' => (int)$page
        );

        // filtering must be disabled for this to function
        M_Photocrati_Comments::$_filter_comments = FALSE;

        query_posts($args);
        while (have_posts()) {
            the_post();
            comments_template('ngg_comments');
        }
        $retval['rendered_view'] = ob_get_contents();

        // restore to our previous state
        wp_reset_query();
        M_Photocrati_Comments::$_filter_comments = TRUE;
        ob_end_clean();

        remove_action('pre_get_posts', array(&$this, 'set_comment_data_query_args'), PHP_INT_MAX, 1);

        return $retval;
    }

    /**
     * Prevent other plugins from adding a filter that negates our ability to do basic database searches
     *
     * @param $query
     */
    function set_comment_data_query_args($query)
    {
        $query->query_vars['suppress_filters'] = TRUE;
    }
}
