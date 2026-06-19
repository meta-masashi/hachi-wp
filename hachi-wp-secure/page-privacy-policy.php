<?php
/**
 * HACHI Theme — Privacy Policy page.
 *
 * DB ページ不要。functions.php の template_redirect フックから直接 require される。
 *
 * @package HACHI
 */

get_header();
hachi_v3_render_privacy();
get_footer();
