<?php

/*
Plugin Name: MCstatus
Plugin URI: https://github.com/tim4dev/wordpress/mcstatus
Description: MCstatus is a WordPress Widget that enables you to show data from a Minecraft server.
Author: Yuri Timofeev
Version: 0.0.1
Author URI: http://blog.pro8bit.com/
*/

require dirname(__FILE__) . '/libs/Widgetize.php';
require dirname(__FILE__) . '/libs/ApiClient.php';
require dirname(__FILE__) . '/libs/MinecraftQuery.php';

class MCstatus_Widget extends Widgetize
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct('MCstatus', array(
            'title' => 'Статус сервера',
            'host' => 'yourserver.com',
            'port' => '25565',
            'show_status' => 'on',
            'show_host' => 'on',
            'show_port' => 'on',
            'show_players' => 'on',
            'show_auto_players' => '',
            'show_version' => 'on',
            'show_plugins' => 'on',
            'minotar_size' => '25'
        ));
        // load localization
        load_plugin_textdomain('mcstatus', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
    }
    

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget(array $args, array $instance)
    {
        $instance = $this->hydrate($instance);

        // Get ip if localhost
        if (in_array($instance['host'], array('127.0.0.1', 'localhost'))) {
            $instance['host'] = $_SERVER['SERVER_ADDR'];
        }

        $client = new ApiClient($instance['host'], $instance['port']);
        $server = $client->call();

        require dirname(__FILE__) . '/templates/widget.phtml';
    }

    /**
     * @param array $instance
     * @return string|void
     */
    public function form(array $instance)
    {
        $instance = $this->hydrate($instance);
        require dirname(__FILE__) . '/templates/form.phtml';
    }

    /**
     * @param $newInstance
     * @param $oldInstance
     * @return array
     */
    public function update($newInstance, $oldInstance)
    {
        $instance = array();
        foreach ($newInstance as $option => $value) {

            if((int) $value > 0 && !in_array($option, array('host'))) {
                $value = (int) $value;
            }
            $instance[$option] = strip_tags(trim($value));
        }
        return $instance;
    }
    

}

Widgetize::add('MCstatus_Widget');

