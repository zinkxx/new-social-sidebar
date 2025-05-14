<?php

/**
 * Plugin Name: Social Media Sidebar
 * Description: Sayfanın kenarında çoklu sosyal medya bağlantıları gösteren modern ve özelleştirilebilir bir eklenti.
 * Version: 2.0.0
 * Author: Dev Technic
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * GitHub Plugin URI: https://github.com/zinkxx/social-media-sidebar
 */

defined('ABSPATH') || exit; // Güvenlik önlemi

// CSS ve JS dosyalarını ekle
function sm_sidebar_enqueue_assets()
{
    wp_enqueue_style('sm-sidebar-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', [], '2.0.0');
    wp_enqueue_script('sm-sidebar-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', [], '2.0.0', true);
}
add_action('wp_enqueue_scripts', 'sm_sidebar_enqueue_assets');

// Admin panel sayfası
require_once plugin_dir_path(__FILE__) . 'includes/settings-page.php';

// Sidebar'ı ekle
function sm_sidebar_render()
{
    $position = get_option('sm_sidebar_position', 'left');
    $dark_mode = get_option('sm_sidebar_dark_mode', false);
    $class_dark = $dark_mode ? 'sm-dark' : '';
    $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];

    echo '<div class="sm-sidebar sm-' . esc_attr($position) . ' ' . esc_attr($class_dark) . '">';
    foreach ($platforms as $platform) {
        $urls = get_option("sm_sidebar_{$platform}_urls", []);
        if (!empty($urls)) {
            foreach ($urls as $url) {
                if (!empty($url)) {
                    echo '<a href="' . esc_url($url) . '" class="sm-icon sm-' . esc_attr($platform) . '" target="_blank" aria-label="' . esc_attr(ucfirst($platform)) . '"></a>';
                }
            }
        }
    }
    echo '</div>';
}
add_action('wp_footer', 'sm_sidebar_render'); // Sayfanın alt kısmına ekler

// Eklenti ayarlarını kaydet
function sm_sidebar_register_settings()
{
    $platforms = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
    foreach ($platforms as $platform) {
        register_setting('sm_sidebar_settings_group', "sm_sidebar_{$platform}_urls", [
            'type' => 'array',
            'sanitize_callback' => 'sm_sanitize_social_links',
            'default' => [],
        ]);
    }

    register_setting('sm_sidebar_settings_group', 'sm_sidebar_position');
    register_setting('sm_sidebar_settings_group', 'sm_sidebar_dark_mode');
}
add_action('admin_init', 'sm_sidebar_register_settings');

// Sosyal medya bağlantılarının temizlenmesi
function sm_sanitize_social_links($input)
{
    return array_filter(array_map('esc_url_raw', $input));
}
