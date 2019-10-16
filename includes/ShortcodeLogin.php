<?php
namespace Socialify;
defined('ABSPATH') || die();

/**
 * ShortcodeLogin
 */
final class ShortcodeLogin
{
    /**
     * The init
     */
    public static function init()
    {
        add_shortcode('socialify_login', function (){
            $data = [];
            $data['login_items'] = [
                'email_standard' => [
                    'url' => wp_login_url( home_url() ),
                    'ico_url' => General::$plugin_dir_url . 'assets/svg/email.svg',
                ],
            ];

            $data = apply_filters('socialify_shortcode_data', $data);

            return self::render($data);
        });

        add_action( 'wp_enqueue_scripts', [__CLASS__, 'assets'] );
    }

    /**
     * render
     */
    public static function render($data){
        ob_start(); ?>
        <div class="socialify_shortcode_login">
            <?php foreach ($data['login_items'] as $key => $item): ?>
                <div class="socialify_shortcode_login__item socialify_<?= $key ?>">
                    <a href="<?= $item['url'] ?>">
                        <img src="<?= $item['ico_url'] ?>" alt="">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * assets
     */
    public static function assets()
    {
        wp_enqueue_style(
                'socialify-sc-style',
                $url = General::$plugin_dir_url . 'assets/style.css',
                $dep = array(),
                $ver = filemtime(General::$plugin_dir_path . '/assets/style.css')
        );
    }
}

ShortcodeLogin::init();