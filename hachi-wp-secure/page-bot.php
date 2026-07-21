<?php
/**
 * HACHI Theme — Bot information page.
 *
 * DB ページ不要。functions.php の template_redirect フックから直接 require される。
 *
 * @package HACHI
 */

get_header();
hachi_v3_render_bot();
get_footer();
