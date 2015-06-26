<?php
if (post_password_required())
    return;

if (!function_exists('nextgen_comment')) {
function nextgen_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    switch ($comment->comment_type) {
        case 'pingback':
        case 'trackback':
            break;
        default:
            // Proceed with normal comments.
            global $post;
            $class = 'nggpl-' . implode(' nggpl-', get_comment_class());
            ?>
            <li class="<?php echo $class; ?>" id="nggpl-li-comment-<?php comment_ID(); ?>">
                <article id="nggpl-comment-<?php comment_ID(); ?>" class="nggpl-comment">
                    <div class="nggpl-comment-meta nggpl-comment-author nggpl-vcard">
                        <?php printf('<cite>%1$s</cite>', get_comment_author_link());?>
                        |
                        <?php
                        printf(
                            '<time datetime="%1$s">%2$s</time>',
                            get_comment_time('c'),
                            sprintf(__('%1$s'), get_comment_date('F jS, Y'))
                        ); ?>
                        <?php if ($depth <= $args['max_depth']) { ?>
                            |
                            <span class="nggpl-reply">
                                <a href='javascript:void(0)'
                                   class='nggpl-reply-to-comment'
                                   data-comment-id='<?php comment_ID(); ?>'
                                   data-user-name='<?php echo get_comment_author(); ?>'>
                                    <?php print __('Reply'); ?>
                                </a>
                            </span>
                        <?php } ?>
                    </div>
                    <section class="nggpl-comment-content nggpl-comment">
                        <?php echo get_avatar($comment, 40); ?>
                        <?php comment_text(); ?>

                        <?php if ('0' == $comment->comment_approved) { ?>
                            <p class="nggpl-comment-awaiting-moderation">
                                <?php _e('Your comment is awaiting moderation.'); ?>
                            </p>
                        <?php } ?>
                    </section>
                </article>
            </li>
            <?php
            break;
    }
}}

if (!function_exists('nextgen_comments_paginate_links')) {
function nextgen_comments_paginate_links()
{
    $current = get_query_var('cpage');
    $total   = get_comment_pages_count();
    $ids     = array();

    $retval = '<ul>';

    for ($i = ($current - 4); $i < $current; $i++) {
        if ($i >= 1)
            $ids[] = $i;
    }
    $ids[] = $current;
    for ($i = ($current + 1); ($i <= ($current + 4) && $i <= $total); $i++) {
        $ids[] = $i;
    }

    $prev = nextgen_comments_prev_link();
    if ($prev)
        $retval .= '<li>' . $prev . '</li>';

    foreach ($ids as $id) {
        if ($id == $current)
        {
            $retval .= '<li>' . $id . '</li>';
        } else {
            $retval .= '<li><a href="javascript:void(0)" data-page-id="' . $id . '">' . $id .  '</a></li>';
        }
    }

    $next = nextgen_comments_next_link();
    if ($next)
        $retval .= '<li>' . $next . '</li>';

    $retval .= '</ul>';

    return $retval;
}}

if (!function_exists('nextgen_comments_next_link')) {
function nextgen_comments_next_link($label = '', $max_page = 0)
{
    global $wp_query;

    $page = get_query_var('cpage');

    $nextpage = intval($page) + 1;

    if (empty($max_page))
        $max_page = $wp_query->max_num_comment_pages;

    if (empty($max_page))
        $max_page = get_comment_pages_count();

    if ($nextpage > $max_page)
        return;

    if (empty($label))
        $label = __('&raquo;');

    return '<a href="javascript:void(0)" data-page-id="' . $nextpage . '">' . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
}}

if (!function_exists('nextgen_comments_prev_link')) {
function nextgen_comments_prev_link($label = '')
{
    $page = get_query_var('cpage');

    if (intval($page) <= 1)
        return;

    $prevpage = intval($page) - 1;

    if (empty($label))
        $label = __('&laquo;');

    return '<a href="javascript:void(0)" data-page-id="' . $prevpage . '">' . preg_replace('/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label) . '</a>';
}}

?>
<div id='nggpl-comments-wrapper'>
<div id='nggpl-comments-image-share-icons' class="galleria-image-share-icons disabled"></div>
<div id="nggpl-comments" class="nggpl-comments-area scrollable">
    <?php if (have_comments()) { ?>
        <h1 class="nggpl-comments-title">
            <?php
            printf(_n('%1$s comment', '%1$s comments', get_comments_number()),
                number_format_i18n(get_comments_number())
            ); ?>
        </h1>
        <ul class="nggpl-commentlist">
            <?php wp_list_comments(array('callback' => 'nextgen_comment', 'style' => 'ol')); ?>
        </ul>
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) { ?>
            <nav id="nggpl-comment-nav-below" class="nggpl-navigation" role="navigation">
                <div class='nggpl-nav-pagination'><?php echo nextgen_comments_paginate_links(); ?></div>
            </nav>
        <?php } ?>
        <?php
        if (!comments_open() && get_comments_number()) { ?>
            <p class="nggpl-nocomments"><?php _e('Comments are closed.'); ?></p>
        <?php } ?>
    <?php } else { ?>
        <h1 class='nggpl-comments-title'><?php echo __('Comments'); ?></h1>
    <?php } ?>
    <div id='nggpl-comment-status'></div>
    <div id='nggpl-comment-reply-status' class='hidden'>
        <a href='javascript:void(0)'><?php echo __('Click here to cancel reply'); ?></a>
    </div>
    <div id='nggpl-comment-form-wrapper'>
        <?php
        // Because comment_form() includes hard-coded HTML with no option to control the id or class of the parent
        // HTML container we use PHP's output buffering to do a simple string replace later
        ob_start();
        comment_form(array(
            'comment_notes_after' => '<input type="hidden" name="nextgen_generated_comment" value="true"/>',
            'logged_in_as' => '',
            'title_reply' => '',
            'title_reply_to' => '',
            'must_log_in' => '<p class="nggpl-must-log-in">' . sprintf(__('You must be <a href="%s" id="nggpl-comment-logout">logged in</a> to post a comment.'), site_url('wp-login.php', 'login')) . '</p>',
            'comment_field' => '<p class="nggpl-comment-form-comment"><label for="nggpl-comment">' . __('Leave a comment') . '</label><textarea id="nggpl-comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
            'id_submit' => 'nggpl-submit',
            'id_form' => 'nggpl-respond-form'
        ));
        $commentform = ob_get_contents();
        ob_end_clean();
        $commentform = str_replace(
            '<div id="respond" class="comment-respond">',
            '<div id="nggpl-respond" class="nggpl-comment-respond">',
            $commentform
        );
        $commentform = str_replace(
            'class="comment-form"',
            'class="nggpl-comment-form"',
            $commentform
        );
        $commentform = str_replace(
            'class="comment-notes"',
            'class="nggpl-comment-notes"',
            $commentform
        );
        $commentform = str_replace(
            'class="comment-form-author"',
            'class="nggpl-comment-form-author"',
            $commentform
        );
        $commentform = str_replace(
            'class="comment-form-email"',
            'class="nggpl-comment-form"-email',
            $commentform
        );
        $commentform = str_replace(
            'class="comment-form-url"',
            'class="nggpl-comment-form-url"',
            $commentform
        );
        $commentform = str_replace(
            "id='comment_post_ID'",
            "id='nggpl-comment_post_ID'",
            $commentform
        );
        $commentform = str_replace(
            "id='comment_parent'",
            "id='nggpl-comment_parent'",
            $commentform
        );
        echo $commentform;
        ?>
    </div>
    <div id='nggpl-comments-bottom'>&nbsp;</div>
    <br/>
</div>
</div>