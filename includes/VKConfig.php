<?php
namespace Socialify;
defined('ABSPATH') || die();

class VKConfig
{
    public static $option_name = General::$slug . '_config_vkontakte';
    public static $endpoint = site_url('/socialify/Vkontakte/');

    public static $data = [
        'settings_section_insstruction' => __('Получить реквизиты для доступа можно по ссылке: ', 'socialify'),
        'settings_section_title' => __('Vkontakte', 'socialify'),
        'setting_title_id' => 'Vkontakte ID',
        'setting_title_secret' => 'Vkontakte Secret',
    ];

    public static function init(){
        add_action('admin_init', [__CLASS__, 'add_settings']);
        add_filter('socialify_user_profile', [__CLASS__, 'auth_handler'], 11, 2);
    }

    public static function auth_handler($userProfile, $endpoint)
    {
        if('Vkontakte' != $endpoint){
            return $userProfile;
        }

        if(!$config = self::get_config()){
            return $userProfile;
        }

        $adapter = new \Hybridauth\Provider\Vkontakte($config);

        //Attempt to authenticate the user with Facebook
        if($accessToken = $adapter->getAccessToken()){
            $adapter->setAccessToken($accessToken);
        }

        $adapter->authenticate();

        //Retrieve the user's profile
        $userProfile = $adapter->getUserProfile();

        //Disconnect the adapter
        $adapter->disconnect();

        return $userProfile;
    }

    public static function get_config(){

        $config_data = get_option(self::$option_name);
        if(empty($config_data['id']) || empty($config_data['secret'])){
            return false;
        }

        $config = [
            'callback' => self::$endpoint,
            'keys' => [ 'id' => $config_data['id'], 'secret' => $config_data['secret'] ],

        ];

        return $config;
    }

    /**
     * Add settings
     */
    public static function add_settings(){
        add_settings_section(
            $section_id = self::$option_name,
            $section_title = self::$data['settings_section_title'],
            $callback = [__CLASS__, 'render_settings_instructions'],
            General::$settings_group
        );
        register_setting(General::$settings_group, self::$option_name);

        self::add_setting_id();
        self::add_setting_secret();

    }

    public static function render_settings_instructions()
    {
        ?>
        <ol>
            <li>
                <span><?= self::$data['settings_section_insstruction'] ?></span>
                <a href="https://vk.com/apps?act=manage/" target="_blank">https://vk.com/apps?act=manage</a>
            </li>
            <li><?= __('В поле Callback URI запишите: ', 'socialify') ?><code><?= self::$endpoint ?></code></li>
            <li>Ссылка на сайт: <code><?= site_url() ?></code></li>
            <li>Домен если потребуется: <code><?= $_SERVER['SERVER_NAME'] ?></code></li>
        </ol>
        <?php
    }


    public static function add_setting_id(){
        $setting_title = self::$data['setting_title_id'];
        $setting_id = General::$slug . '_vkontakte_id';

        add_settings_field(
            $setting_id,
            $setting_title,
            $callback = function($args){
                printf(
                    '<input type="text" name="%s" value="%s" size="77">',
                    $args['name'], $args['value']
                );
            },
            $page = General::$settings_group,
            $section = self::$option_name,
            $args = [
                'name' => self::$option_name . '[id]',
                'value' => @get_option(self::$option_name)['id'],
            ]
        );
    }

    public static function add_setting_secret(){
        $setting_title = self::$data['setting_title_secret'];
        $setting_id = General::$slug . '_vkontakte_secret';
        add_settings_field(
            $setting_id,
            $setting_title,
            $callback = function($args){
                printf(
                    '<input type="text" name="%s" value="%s" size="77">',
                    $args['name'], $args['value']
                );
            },
            $page = General::$settings_group,
            $section = self::$option_name,
            $args = [
                'name' => self::$option_name . '[secret]',
                'value' => @get_option(self::$option_name)['secret'],
            ]
        );
    }
}

VKConfig::init();