<?php

class A_Comment_Controller extends Mixin
{
    function fix_locale()
    {
        global $sitepress;
        return $sitepress->get_locale($this->object->param('lang'));
    }

    function get_comments_action()
    {
        if ($lang = $this->object->param('lang', NULL, FALSE))
        {
            if (class_exists('SitePress'))
            {
                global $sitepress;
                global $locale;
                $locale = $sitepress->get_locale($lang);
                $sitepress->switch_lang($lang);
                remove_filter('locale', array($sitepress, 'locale'));
                add_filter('locale', array($this, 'fix_locale'), -10);
                load_textdomain('default', WP_LANG_DIR . DIRECTORY_SEPARATOR . $locale . ".mo");
            }
        }

        ob_start();

        $mapper = C_Comment_Mapper::get_instance();
        $response = array('responses' => array());

        add_filter('comments_template', array(&$this, 'comments_template'));

        $ids  = explode(',', $this->object->param('id'));

        $page = $this->object->param('page', NULL, 0);
        $type = $this->object->param('type');

        foreach ($ids as $id) {
            $post = $mapper->find_or_create($type, $id, $this->object->param('from'));
            $comments_data = $post->get_comments_data($page);
            $response['responses'][$id] = $comments_data;
        }

        ob_end_clean();

        return $response;
    }

    function comments_template($template)
    {
        $fs = C_Fs::get_instance();
        if (strpos($template, 'ngg_comments') !== FALSE)
            $template = $fs->find_abspath('photocrati-comments#templates/comments.php');
        return $template;
    }
}
